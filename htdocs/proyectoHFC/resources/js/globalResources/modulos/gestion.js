import errors from  "@/globalResources/errors.js"

const gestion = {}


gestion.loadRegistrosGestiones = function loadRegistrosGestiones(nodo, troba) {

  //console.log("El nodo y troba son: ",nodo,"--",troba)

        $("#display_filter_special").prop("disabled", true);

        $("#resultHistoricoGestionMasiva").DataTable({
                  "destroy": true,
                  "processing": true, 
                  "serverSide": true,
                  "dom":'<"row mx-0"'
                              +'<"col-12 col-sm-6"l><"col-12 col-sm-6 text-center"B>>'
                          +'<"row"'
                              +'<"col-sm-12 px-0 table-responsive tableFixHead"t>>'
                          +'<"row"'
                              +'<"col-12 col-sm-5"i><"col-12 col-sm-7"p>>'
                          +'r',
                  "buttons":[
                      {
                          text: 'FILTROS',
                          className: 'btn btn-sm btn-info shadow-sm',
                          titleAttr: 'FILTROS EN HISTORIAL GESTIÓN',
                          action: function ( e, dt, node, config ) {
                              //alert( 'Button Opciones' );
                              console.log("opciones:", e, dt, node, config)
                              console.log("Se deberias mostrar los filtros")
                              $("#filtroContentHistorialGestion").slideToggle()
                          }
                      }
                  ],
                  "ajax": {  
                      'url':'/administrador/gestion/lista',
                      "type": "GET", 
                      "dataType": "json", 
                      "data": function ( d ) {
                          
                              d.nodo = nodo;
                              d.troba = troba;
                      },
                      'error': function(jqXHR, textStatus, errorThrown)
                          {   
                            $("#resultHistoricoGestionMasiva_processing").css({"display":"none"})
                            //console.log( "Error: " ,jqXHR, textStatus, errorThrown); 
                            $("#display_filter_special").prop("disabled", false);
                              //alert("Se generó un error con la petición, Se intentará traer nuevamente.")

                             //$("#body-errors-modal").html(jqXHR.responseText)
                             //$('#errorsModal').modal('show')
                             //return false

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
                              erroresPeticion = (erroresPeticion.trim() == 0) ? "hubo un error en el servicio, intente nuevamente recargando la web." : erroresPeticion
                      
                              $("#body-errors-modal").html(`<div class="text-danger">${erroresPeticion}</div>`)
                              $('#errorsModal').modal('show')
                              return false
                            
                          }
                  }, 
                  "columns": [
                    {data: 'fechahora'},
                    {data: 'nodo'},
                    {data: 'troba'},
                    {data: 'estado'},
                    {data: 'tecnico'},
                    {data: 'observaciones'},
                    {data: 'usuario'},
                    {data: 'porc_caida'},
                    {data: 'remedy'},
                    {data: 'serv_afectado'}
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


        $("#resultHistoricoGestionMasiva").parent().addClass("table-responsive tableFixHead") 
        // $("#filtroContentHFC").removeClass("d-none")

        let tablaHead = $('.tableFixHead').find('thead th')
        $('.tableFixHead').on('scroll', function() {
        // console.log("ejecutando"+this.scrollTop); 
            tablaHead.css('transform', 'translateY('+ this.scrollTop +'px)'); 
        }); 
 
}

export default gestion
