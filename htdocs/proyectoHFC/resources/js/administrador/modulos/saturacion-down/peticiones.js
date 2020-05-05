import errors from  "@/globalResources/errors.js"
import columnas from  "@/globalResources/tablas/columnas.js"

const peticiones = {}

peticiones.redirectTabs = function redirectTabs(identificador) {
        $('#tabsSaturacionDown > .tab-pane').removeClass('show');
        $('#tabsSaturacionDown > .tab-pane').removeClass('active');
        identificador.tab('show')
}

peticiones.resetInterval = function resetInterval(){
        if (INTERVAL_LOAD != null) {
                clearInterval(INTERVAL_LOAD)
                //console.log("Se limpio el interval y se debe iniciar nuevamente...")
                INTERVAL_LOAD = setInterval(() => {
                        if (ESTA_ACTIVO_REFRESH) {
                            if ($( ".saturacionDown" ).hasClass( "active" )) {
                                peticiones.cargandoPeticionPrincipal()
                            } 
                        }
                
                }, 30000);
        }
}

peticiones.armandoColumnasUno = function armandoColumnasUno()
{
        let columnasContent =  [ {data: 'id'}  ]
        columnasContent.push({data: 'cmts'})
        columnasContent.push({data:'down'})
        columnasContent.push({data: 'cant'})
        columnasContent.push({data: 'rangosat'})
        columnasContent.push({data: 'fecini'})
        columnasContent.push({data: 'impacto'})
        columnasContent.push({data: 'trob'})
        columnasContent.push({data: 'archivo'})
        columnasContent.push({data: 'link'})
        return columnasContent
}

function procesandoResultadoLista(result)
{
      
        for (let i = 0; i < result.length ; i++) {

          result[i].archivo = `<div>
                                <a href="javascript:void(0)" data-uno="${result[i].archivo}"
                                class="shadow-sm font-weight-bold downloadPuertoSatDown" alt="Descargar Archivo" title="Descargar Archivo" style="color: #0056b3;">
                                        ${result[i].archivo == null ? "" : result[i].archivo}
                                </a></div>`

          result[i].link = ` <a href="javascript:void(0)" class="btn btn-sm text-success btn-light p-0 verGraficoSatDown" data-uno="${result[i].cmts}" data-dos="${result[i].pto}" alt="Ver Gráfico" title="ver Gráfico">
                                <i class="icofont-chart-bar-graph"></i>    
                              </a>`             
           
           
        }
      
        return result
}

peticiones.getDataRequiredFilterSaturacionDown = function getDataRequiredFilterSaturacionDown(tipo)
{
        let data = {}
        data.parametros = {}
        data.parametros.nodo = "" 
        data.parametros.tipoCaidas = tipo
  
        data.redirect = $('#saturacionDownTab')
        data.tabla = $("#resultSaturacionDown")
        data.columnasCaidas = peticiones.armandoColumnasUno()
  
        return data
}

peticiones.cargaSaturacionDownLista = function cargaSaturacionDownLista(COLUMNS_CAIDAS,BUTTONS_CAIDAS,parametersDataSaturacionDown,tabla){
     
        //$("#display_filter_special").prop("disabled", true); 
        tabla.DataTable({
                "destroy": true,
                "processing": true, 
                "serverSide": true,
                "dom":'<"row mx-0"'
                        +'<"col-12 col-sm-6"l><"col-12 col-sm-6 text-right"B>>'
                        +'<"row"'
                        +'<"col-sm-12 px-0 table-responsive tableFixHead"t>>'
                        +'<"row"'
                        +'<"col-12 col-sm-5"i><"col-12 col-sm-7"p>>'
                        +'r',
                "buttons":BUTTONS_CAIDAS,
                "ajax": {  
                        'url':'/administrador/saturacion-down/lista',
                        "type": "GET", 
                        "data": function ( d ) {
                                d.filtroMotivo = parametersDataSaturacionDown.motivo;
                                d.filtroCmts = parametersDataSaturacionDown.cmts;
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
                                
                        }
                }, 
                "columns": COLUMNS_CAIDAS,
                'columnDefs': [
                        {
                           'targets': '_all',
                           'createdCell':  function (td, cellData, rowData, row, col) { 
                                 
                                $(td).css({"background":`${rowData.fondo}`,"color":`${rowData.letra}`}); 
                                $(td).addClass("text-center")
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
                           // "orderable" : false,
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
        let valorFiltroEspecial = $("#input-saturacionDownTab").val();
        let params = peticiones.getDataRequiredFilterSaturacionDown(valorFiltroEspecial);
        
        peticiones.redirectTabs(params.redirect)
        //$(".content_filter_basic").css({"display":"none"})
        peticiones.cargaSaturacionDownLista(params.columnasCaidas,BUTTONS_CAIDAS_MASIVAS,params.parametros,params.tabla)
}

export default peticiones