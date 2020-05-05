$(function(){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    loadArbolDecisionesGeneral()

    function loadArbolDecisionesGeneral()
    {
        $('#resultListaArbolDecisiones').DataTable({
                "destroy": true,
                "processing": true, 
                "serverSide": true,
                //"dom":"Bfrtip",
                "dom":'<"row mx-0"'
                        +'<"col-12 col-sm-6"l><"col-12 col-sm-6"f>>'
                    +'<"row position-relative"'
                        +'<"col-sm-12 px-0 table-responsive tableFixHead"t>'
                        +'r>'
                    +'<"row"'
                        +'<"col-12 col-sm-5"i><"col-12 col-sm-7"p>>', 
                "ajax": {  
                    'url':'/administrador/arbol-decision/lista-pasos',
                    "type": "POST",
                    'error': function(jqXHR, textStatus)
                        { 
                            
                    //console.log( "Error: " ,jqXHR, textStatus); 
                    //$("#resultListaArbolDecisiones").html(jqXHR.responseText);
                       $("#body-errors-modal").html(jqXHR.responseText)
                       //$('#errorsModal').modal('show') 
                                 if(jqXHR.status){
                                    if (jqXHR.status == 401) {
                                        location.reload();
                                        return false
                                    } 
                                
                                   /* $("#body-errors-modal").html(`<div class="col-12 mx-0 px-0 text-secondary text-center">
                                                                        Se generó un problema al traer los datos, Intente nuevamente recargando la la Web.
                                                                </div>`)*/
                                    $('#errorsModal').modal('show') 
                                    return false
                                }
                               // cargaPasosArbolDecisiones.ajax.reload();
                            
                                return false 
                        }
                },
                "columns": COLUMNS_LIST_PASOS,
                "language": {
                    "info": "_TOTAL_ registros",
                    "search": "Buscar",
                    "paginate": {
                        "next": "Siguiente",
                        "previous": "Anterior",
                    },
                    "lengthMenu": 'Mostrar <select >'+
                                '<option value="15">15</option>'+
                                '<option value="50">50</option>'+
                                '<option value="100">100</option>'+
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

         
        $('#resultListaArbolDecisiones').css({"font-size":"11px"})

        let tablaHead = $('.tableFixHead').find('thead th')
        $('.tableFixHead').on('scroll', function() {
            // console.log("ejecutando"+this.scrollTop); 
            tablaHead.css('transform', 'translateY('+ this.scrollTop +'px)'); 
        }); 
    }

    
})