import errors from  "@/globalResources/errors.js"
import columnas from  "@/globalResources/tablas/columnas.js"
 
const peticiones = {}

peticiones.armandoColumnasUno = function armandoColumnasUno()
{
        let columnasContent =  [ {data: 'cmts'}  ]
        columnasContent.push({data: 'interface'})
        columnasContent.push({data:'trobas'})
        return columnasContent
}

function procesandoResultadoLista(result)
{
        //onkeypress="return alfanumerico(event)" 
        for (let i = 0; i < result.length ; i++) {

                result[i].trobas = `<div class="flex-align-justify-center">
                                        <div class="width-70 margin-right-15">
                                                <input type='text' onkeypress="return alfanumerico(event)" class="form-control descripcionEtiquetadoPuertos" value='${result[i].trobas == null ? "" : result[i].trobas}' name='troba' style="background:${result[i].fondoEtiquetadoPuertos};color:${result[i].letraEtiquetadoPuertos}">
                                        </div>
                                        <div class="text-left">
                                                <a href="javascript:void(0)" class="btn btn-primary" id="actualizarEtiquetadoPuertos" data-uno="${result[i].trobas}" data-dos="${result[i].interface}" data-tres="${result[i].cmts}" data-cuatro="${result[i].cmtsfil}">Enviar</a>
                                        </div>
                                </div>`

        }
      
        return result
}

peticiones.cargaEtiquetadoPuertosLista = function cargaEtiquetadoPuertosLista(COLUMNS_CAIDAS,BUTTONS_CAIDAS,parametersDataEtiquetadoPuertos,tabla){
     
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
                        'url':'/administrador/etiquetado-puertos/lista',
                        "type": "GET", 
                        "data": function ( d ) {
                                d.filtroCmts = parametersDataEtiquetadoPuertos.cmts;
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
                                    return false
                                }
                                
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