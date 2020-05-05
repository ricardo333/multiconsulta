import errors from  "@/globalResources/errors.js"
import columnas from  "@/globalResources/tablas/columnas.js"

const peticiones = {}

peticiones.redirectTabs = function redirectTabs(identificador) {
        $('#tabsLlamadasNodoContent > .tab-pane').removeClass('show');
        $('#tabsLlamadasNodoContent > .tab-pane').removeClass('active');
        identificador.tab('show')
}

peticiones.resetInterval = function resetInterval(){
        if (INTERVAL_LOAD != null) {
                clearInterval(INTERVAL_LOAD)
                //console.log("Se limpio el interval y se debe iniciar nuevamente...")
                INTERVAL_LOAD = setInterval(() => {
                        if (ESTA_ACTIVO_REFRESH) {
                            peticiones.cargandoPeticionPrincipal()
                        }
                }, 60000); 
        }
}

peticiones.armandoColumnasUno = function armandoColumnasUno()
{   
        let columnasContent =  [ {data: 'jefatura'}  ]
        columnasContent.push({data: 'nodos'})
        columnasContent.push({data:'cant'})
        columnasContent.push({data: 'trobas'})
        columnasContent.push({data: 'promediocall'})
        columnasContent.push({data: 'aver'})
        columnasContent.push({data: 'ultimallamada'})
        return columnasContent
}

function procesandoResultadoLista(result)
{
        var f = new Date();
        var hoy= f.getFullYear() + "-" + (f.getMonth() +1) + "-" + f.getDate();
        let tok = $('meta[name="csrf-token"]').attr('content')

        for (let i = 0; i < result.length ; i++) {
                
               
                
                result[i].cant = `<div class="text-center">
                                <a href="javascript:void(0)" data-uno="${result[i].nodo}" data-dos="" data-tres="${hoy}"
                                        class="shadow-sm verLlamadaNodoDMPE" alt="Ver Llamadas por Nodo DMPE" title="Ver Llamadas por Nodo DMPE">
                                        ${result[i].cant} </a></div>`

                result[i].aver = `<div class="text-center">
                                <a href="javascript:void(0)" data-uno="${result[i].nodo}" data-dos="" data-tres="${hoy}"
                                        class="shadow-sm verLlamadaNodoAverias" alt="Ver Llamadas por Nodo Avería" title="Ver Llamadas por Nodo Avería">
                                        ${result[i].aver == null ? "" : result[i].aver}</a></div>`

                result[i].nodos = `
                                <form method="post" action="/administrador/llamadas-troba">
                                        <input type="hidden" name="_token" value="${tok}">
                                        <input type="hidden" name="nodo" value="${result[i].nodo}">  
                                        <button type="submit" title="Ver Llamadas por Troba" class="btn btn-link formato-link" style="color: ${result[i].colorItem}">${result[i].nodo}</button>
                                </form>`

        }

        return result
}

peticiones.getDataRequiredFilterLlamadas = function getDataRequiredFilterLlamadas(tipo)
{
        let data = {}
        data.parametros = {}
        data.parametros.nodo = "" 
        data.parametros.tipoCaidas = tipo
  
        data.redirect = $('#llamadasNodoTab')
        data.tabla = $("#resultLlamadasNodo") 
        data.parametros.jefatura = $("#listajefatura").val() 
        data.parametros.top = $("#listaTopLlamadas").val()
        data.columnasCaidas = peticiones.armandoColumnasUno()
  
        return data
}

peticiones.cargaLlamadasNodoLista = function cargaLlamadasNodoLista(COLUMNS_CAIDAS,BUTTONS_CAIDAS,parametersDataLlamadas,tabla){
     
        //$("#display_filter_special").prop("disabled", true); 
        tabla.DataTable({
                "destroy": true,
                "processing": true, 
                "order": [[ 2, "desc" ]],
                "serverSide": true,
                "dom":'<"row mx-0"'
                        +'<"col-12 col-sm-6"l><"col-12 col-sm-6 text-right"B>>'
                        +'<"row"'
                        +'<"col-sm-12 px-0 table-responsive tableFixHead"t>>'
                        +'<"row"'
                        +'<"col-12 col-sm-5"i><"col-12 col-sm-7"p>>'
                        +'r',
                "buttons":BUTTONS_LLAMADAS_NODO,
                "ajax": {  
                        'url':'/administrador/llamadas-nodo/lista',
                        "type": "GET", 
                        "data": function ( d ) {
                                d.filtroJefatura = parametersDataLlamadas.jefatura;
                        },
                        'dataSrc': function(json){
                                       //return json
                                        let result = json.data
                                        //console.log("El result es: ",result)
                                        let dataProcesada = procesandoResultadoLista(result)
                                        return dataProcesada
                        },
                        'error': function(jqXHR, textStatus, errorThrown)
                        {  
                                /*
                                //console.log( "Entre a Error: " ,jqXHR, textStatus, errorThrown); 
                                $("#display_filter_special").prop("disabled", false);
                                if (REFRESH_PERMISO) {
                                        ESTA_ACTIVO_REFRESH = true
                                        peticiones.resetInterval()
                                }
                                //alert("Se generó un error con la petición, Se intentará traer nuevamente.")
                                //location.reload(); 
                                 //$("#body-errors-modal").html(jqXHR.responseText)
                                // $("#body-errors-modal").html(`<div class="w-100 text-center text-danger">Ocurrio un problema con la carga de datos, intente nuevamente recargando la web.</div>`)
                                $('#errorsModal').modal('show') 
                                if(jqXHR.status){
                                        if (jqXHR.status == 401) {
                                                location.reload();
                                                return false
                                        } 

                                    // peticiones.redirectTabs($('#multiMapTab')) 
                                    return false
                                } 
                                // peticiones.redirectTabs($('#multiMapTab')) 

                                return false 
                                */
                        }
                }, 
                "columns": COLUMNS_CAIDAS,
                'columnDefs': [
                        {
                           //"targets": [ 0 ],             //Le indico que la columna 0 no sea visible con false
                           //"visible": false,
                           'targets': '_all',
                           'createdCell':  function (td, cellData, rowData, row, col) { 
                                //console.log( 'entro: '+`${rowData.fondo}`+`${rowData.letra}`)
                                //dd(rowData.backgroundInit_r1);
                                $(td).css({"background":`${rowData.fondo}`,"color":`${rowData.letra}`});
                                $(td).addClass("text-center")
                              
                                if(col == 0 || col == 1 || col == 5){
                                   $(td).css({"color":`${rowData.colorItem}`});
                                }
                                if(col == 2 || col == 3 || col == 4 || col == 6){
                                   $(td).css({"color":`${rowData.colorxDefect}`});
                                }
                                
                                 //console.log("los cells: ",td, cellData, rowData, row, col)
                                 let count = 0

                                 if (col == count+6) {
                                        if (rowData.estado != "LEVANTO") {
                                                if (parseFloat(rowData.divisionOffline) > 0.3) { 
                                                        $(td).css({"background":`${rowData.letra}`,"color":`${rowData.fondo}`});  
                                                } 
                                        }
                                        
                                 }

                           }
                        },
                        {
                             
                            "targets": '_all',
                            "orderable" : false,  //Quito el ordenamiento de la cabecera cuando se hace click
                            "searchable": false,
                                
                        } 
                ] ,
                "initComplete": function(){
                        //console.log("Termino la carga completa estoy en initComplete")
                        $("#display_filter_special").prop("disabled", false);
                        if (REFRESH_PERMISO) {
                                ESTA_ACTIVO_REFRESH = true
                                peticiones.resetInterval()
                                
                        }
                },
                "pageLength": 100,
                "language": {
                            "info": "_TOTAL_ registros",
                            "search": "Buscar",
                            "paginate": {
                                    "next": "Siguiente",
                                    "previous": "Anterior",
                            },
                            "lengthMenu": 'Mostrar <select >'+
                            '<option value="100">100</option>'+
                            '<option value="300">300</option>'+
                            '<option value="500">500</option>'+
                            '<option value="700">700</option>'+
                            '<option value="-1">Todos</option>'+
                            '</select> registros',
                            "loadingRecords": "<div id='carga_person'> <div class='loader'>Cargando...</div></div>",
                            "processing": "<div id='carga_person'> <div class='loader'>Procesando...</div></div>",
                            "emptyTable": "No hay datos disponibles",
                            "zeroRecords": "No hay coincidencias", 
                            "infoEmpty": "",
                            "infoFiltered": ""
                }
            });

            tabla.parent().addClass("table-responsive tableFixHead") 
            // $("#filtroContentHFC").removeClass("d-none")

            let tablaHead = $('.tableFixHead').find('thead th')
            $('.tableFixHead').on('scroll', function() {
            // console.log("ejecutando"+this.scrollTop); 
            tablaHead.css('transform', 'translateY('+ this.scrollTop +'px)'); 
            }); 

}

peticiones.cargandoPeticionPrincipal = function cargandoPeticionPrincipal()
{
        let valorFiltroEspecial = $("#input-llamadasNodoTab").val();
        let params = peticiones.getDataRequiredFilterLlamadas(valorFiltroEspecial);
        
        peticiones.redirectTabs(params.redirect)
        //$(".content_filter_basic").css({"display":"none"})
        peticiones.cargaLlamadasNodoLista(params.columnasCaidas,BUTTONS_LLAMADAS_NODO,params.parametros,params.tabla)
}

export default peticiones
