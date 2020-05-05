const historicoNodoTroba = {}

historicoNodoTroba.verHistorialNodoTroba = function verHistorialNodoTroba(tabla,url,parametros,activoFiltroEspecial)
{

    if (activoFiltroEspecial) {
        $("#display_filter_special").prop("disabled", true);
    }
    


     tabla.DataTable({
                "destroy": true,
                "processing": true, 
                "serverSide": true,
                "dom":'<"row mx-0"'
                            +'<"col-12 col-sm-12"l>>'
                        +'<"row"'
                            +'<"col-sm-12 px-0 table-responsive tableFixHead"t>>'
                        +'<"row"'
                            +'<"col-12 col-sm-5"i><"col-12 col-sm-7"p>>'
                        +'r',
                "ajax": {  
                    'url':url,
                    "type": "GET", 
                    "dataType": "json", 
                    "data": function ( d ) {
                        
                            d.nodo = parametros.nodo;
                            d.troba = parametros.troba;
                    },
                    'error': function(jqXHR, textStatus, errorThrown)
                        {  

                   
                        // console.log( "Error: " ,jqXHR, textStatus, errorThrown); 
                        if (activoFiltroEspecial) {
                            $("#display_filter_special").prop("disabled", false);
                        }
                        
                            //alert("Se generó un error con la petición, Se intentará traer nuevamente.")

                            let erroresPeticion =""
                            
                            if(jqXHR.status){
                                let mensaje = errors.codigos(jqXHR.status)
                                erroresPeticion = mensaje
                            }
                            if(jqXHR.responseJSON){
                            if(jqXHR.responseJSON.mensaje){
                                let erroresMensaje = jqXHR.responseJSON.mensaje  //captura objeto
                                let mensaje = errors.mensajeErrorJson(erroresMensaje)
                                erroresPeticion += "<br> "+mensaje 
                            } 
                        }
                            erroresPeticion = (erroresPeticion.trim() == 0) ? "hubo un error en el servicio, intente nuevamente." : erroresPeticion
                    
                            $("#body-errors-modal").html(`<div class="text-danger">${erroresPeticion}</div>`)
                            $('#errorsModal').modal('show')
                            return false
                        
                        }
                }, 
                "columns": [
                        {data: 'nodo'},
                        {data: 'troba'},
                        {data: 'powerup_max'},
                        {data: 'powerup_prom'},
                        {data: 'powerup_min'},
                        {data: 'powerds_max'},
                        {data: 'powerds_prom'},
                        {data: 'powerds_min'},
                        {data: 'snr_avg'},
                        {data: 'snr_down'},
                        {data: 'fecha_hora'},
                        {data: 'cmts'},
                        {data: 'interface'}
                ],
                'columnDefs': [
                            { 
                                "targets":"_all",
                                "orderable" : false,
                                "searchable": false, 
                            } 
                ] ,
                "initComplete": function(){
                    // console.log("Termino la carga completa")  
                    if (activoFiltroEspecial) {
                        $("#display_filter_special").prop("disabled", false);
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
                                '<option value="200">200</option>'+
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

export default historicoNodoTroba