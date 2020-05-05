import errors from  "@/globalResources/errors.js"

const peticiones = {}

peticiones.redirectTabs = function redirectTabs(identificador) {
        $('#tabsDescargaCmts > .tab-pane').removeClass('show');
        $('#tabsDescargaCmts > .tab-pane').removeClass('active');
        identificador.tab('show')
}


peticiones.armandoColumnasCmts = function armandoColumnasCmts()
{
        let columnasContent =  [ {data: 'archivo'}  ]
        columnasContent.push({data: 'archivo_sum'})
        columnasContent.push({data: 'time_sum'})
        columnasContent.push({data: 'archivo_phy'})
        columnasContent.push({data: 'time_phy'})
        columnasContent.push({data: 'archivo_scm'})
        columnasContent.push({data: 'time_scm'})
        return columnasContent
}

function procesandoResultadoLista(result)
{   

    for (let i = 0; i < result.length ; i++) {

        result[i].archivo = `<span class="font-weight-bold">${result[i].archivo}</span>`

        if (result[i].time_sum=="No disponible") {

                result[i].archivo_sum = `<span class="font-weight-bold" style="color:red">${result[i].archivo_sum}</span>`

        } else {

                result[i].archivo_sum = `<div>
                        <a href="javascript:void(0)" data-uno="${result[i].archivo_sum}"
                        class="shadow-sm font-weight-bold downloadCmts" alt="Cmts Sum" title="Cmts Sum">
                                ${result[i].archivo_sum}
                        </a></div>`
                
        }

        result[i].time_sum = `<span class="font-weight-bold">${result[i].time_sum}</span>`

        if (result[i].time_phy=="No disponible") {

                result[i].archivo_phy = `<span class="font-weight-bold" style="color:red">${result[i].archivo_phy}</span>`

        } else {

                result[i].archivo_phy = `<div>
                        <a href="javascript:void(0)" data-uno="${result[i].archivo_phy}"
                        class="shadow-sm font-weight-bold downloadCmts" alt="Cmts Sum" title="Cmts Sum">
                                ${result[i].archivo_phy}
                        </a></div>`
                
        }

        result[i].time_phy = `<span class="font-weight-bold">${result[i].time_phy}</span>`;

        if (result[i].time_scm=="No disponible") {

                result[i].archivo_scm = `<span class="font-weight-bold" style="color:red">${result[i].archivo_scm}</span>`

        } else {

                result[i].archivo_scm = `<div>
                        <a href="javascript:void(0)" data-uno="${result[i].archivo_scm}"
                        class="shadow-sm font-weight-bold downloadCmts" alt="Cmts Sum" title="Cmts Sum">
                                ${result[i].archivo_scm}
                        </a></div>`
                
        }

        result[i].time_scm = `<span class="font-weight-bold">${result[i].time_scm}</span>`

    }

    return result
}


peticiones.cargaCmtsLista = function cargaCmtsLista(COLUMNS_CAIDAS,BUTTONS_DESCARGAS_CMTS,tabla)
{
     
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
            "buttons": BUTTONS_DESCARGAS_CMTS,
            "ajax": {  
                    'url':'/administrador/descarga-cmts/lista',
                    "type": "GET", 
                    "data": function ( d ) {
                           // d.filtroJefatura = parametersDataEstadosModems.jefatura;
                    },
                    'dataSrc': function(json){
                            //return json
                            let result = json.data
                            //  console.log("El result es: ",result)
                            let dataProcesada = procesandoResultadoLista(result)
                            return dataProcesada
                    },
                    'error': function(jqXHR, textStatus, errorThrown)
                    {  
                            //console.log( "Entre a Error: " ,jqXHR, textStatus, errorThrown); 
                            /*
                            if (REFRESH_PERMISO) {
                                    ESTA_ACTIVO_REFRESH = true
                                    peticiones.resetInterval()
                            }
                            */
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
                            
                            //$(td).css({"background":`${rowData.fondo}`,"color":`${rowData.letra}`});
                            $(td).addClass("text-center")
                            
                             /*
                             let count = 0
                             if (DIAGNOSTICOM_PERMISO)   count ++ 
                             if (VER_CRITICOS_PERMISO)   count ++ 

                             if (col == count+6) {
                                    if (rowData.estado != "LEVANTO") {
                                            if (parseFloat(rowData.divisionOffline) > 0.3) { 
                                                    $(td).css({"background":`${rowData.letra}`,"color":`${rowData.fondo}`});  
                                            } 
                                    }
                                    
                             }
                             */

                       }
                    },

                    {
                         
                        "targets": '_all',
                        "orderable" : false,  //Quito el ordenamiento de la cabecera cuando se hace click
                        "searchable": false,
                            
                    } 
            ] ,
            
            "initComplete": function(){
                    //console.log("Termino la carga completa estoy en initComplete peticiones.js line 147")
                    //$("#display_filter_special").prop("disabled", false);
                    /*
                    if (REFRESH_PERMISO) {
                            ESTA_ACTIVO_REFRESH = true
                            peticiones.resetInterval()
                            
                    }
                    */
                 
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



export default peticiones