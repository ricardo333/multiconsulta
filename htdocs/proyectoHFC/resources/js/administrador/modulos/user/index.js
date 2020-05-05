import errors from  "@/globalResources/errors.js"

 $(function(){

    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

     //peticiones.cargaCompletaUsuarios(SORTBY,0)
      //var cargaUsuariosLista = $('#listUsersPrint').DataTable({
      $('#listUsersPrint').DataTable({
                                    "serverSide": true,
                                    "processing": true,
                                    "dom":'<"row mx-0"'
                                                +'<"col-12 col-sm-6"l><"col-12 col-sm-6"f>>'
                                            +'<"row position-relative"'
                                                +'<"col-sm-12 px-0 table-responsive table-text-xs tableFixHead"t>'
                                                +'r>'
                                            +'<"row"'
                                                +'<"col-12 col-sm-5"i><"col-12 col-sm-7"p>>',
                                    "ajax": {
                                    "url":"/administrador/usuarios/lista", 
                                        "error": function(jqXHR, textStatus)
                                        {  
                                           // console.log( "Error: " ,jqXHR, textStatus); 

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
                                    "columns": COLUMNS_LIST_USERS,
                                    'columnDefs': [ 
                                            { 
                                                "targets": [1,5],
                                                "orderable" : false,
                                                "searchable": false,  
                                            } 
                                    ] ,"initComplete": function(){
                                        // console.log("Termino la carga completa")
                                        $("#listUsersPrint_filter input").attr('placeholder','Usuario, DNI o Email');
                                            
                                    },
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
                                        "emptyTable": "No hay usuarios disponibles",
                                        "zeroRecords": "No hay coincidencias", 
                                        "infoEmpty": "",
                                        "infoFiltered": ""
                                    }
                                });
        

        $("#listUsersPrint").parent().addClass("table-responsive tableFixHead") 
     
  /**Tabla fixed */
  var th = $('.tableFixHead').find('thead th')
 
  $('.tableFixHead').on('scroll', function() {
      //console.log("ejecutando"+this.scrollTop); 
      th.css('transform', 'translateY('+ this.scrollTop +'px)'); 
  });
  /**Tabla tabla fixed */
   
 })
 

 