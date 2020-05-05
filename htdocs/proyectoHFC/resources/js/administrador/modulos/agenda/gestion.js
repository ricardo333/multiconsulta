import peticiones from './peticiones.js'
import errors from  "@/globalResources/errors.js"

$(function(){

    $("body").on("click",".gestionAgenda", function(){
        let idCliente = $(this).data("uno")
        if (idCliente == "" || idCliente == undefined || idCliente == null) {
            $("#body-errors-modal").html(`<div class="w-100 text-danger text-center">No se puede identificar al cliente, intente actualizando la web nuevamente.</div>`)
            $('#errorsModal').modal('show')
            return false
        }
        peticiones.redirectTabs($("#gestionIndividualTab"))
        
        $("#content_btn_dinamic_historico").html(`
                                <a href="javascript:void(0)" id="registrosGestiones"  
                                        class="btn btn-sm btn-outline-primary shadow-sm" data-uno="${idCliente}">
                                    <i class="fa fa-arrow-right"></i> 
                                    Histórico de Agenda
                                </a>`)
         
    })

    $(".return_agenda").click(function(){
        peticiones.redirectTabs($("#AgendasListadoTab"))
    })

    $("#storeSendAgendaGestion").click(function(){

        let idAgenda = $("#registrosGestiones").data("uno")

        if (idAgenda == "" || idAgenda == undefined || idAgenda == null) {
            peticiones.redirectTabs($("#AgendasListadoTab"))
            $("#body-errors-modal").html(`<div class="w-100 text-danger text-center">No se puede identificar la agenda, intente actualizando la web nuevamente.</div>`)
            $('#errorsModal').modal('show')
            return false
        }

        $("#preloadGestionAgenda").html(`<div id="carga_person">
                                            <div class="loader">Loading...</div>
                                        </div>`)
        $("#contentFormAgenda").addClass("d-none")

        let estado = $("#estadoGestionAgendaStore").val()
        let quiebre = $("#quiebreGestionAgendaStore").val()
        let observacion = $("#observacionesGestionAgendaStore").val()
          

        $.ajax({
            url:`/administrador/agendas/gestion/store`,
            method:"post",
            data:{
                idAgenda,
                estado,
                quiebre,
                observacion
            },
            dataType: "json", 
        })
        .done(function(data){
            console.log("la data es: ",data)
            $("#preloadGestionAgenda").html(``)
            $("#contentFormAgenda").removeClass("d-none")
 
            //console.log("El resultado de Store gestion c es: ",data)

            $("#body-success-modal").html(`<div class="w-100 text-center text-success">${data.mensaje}</div>`)
            $("#successModal").modal("show")
            peticiones.redirectTabs($("#AgendasListadoTab"))
            peticiones.cargaListaAgendas()

        })
        .fail(function(jqXHR, textStatus){
            
            $("#preloadGestionAgenda").html(``)
            $("#contentFormAgenda").removeClass("d-none")

             console.log( "Error: " ,jqXHR, textStatus);
            //console.log( "Request failed: " ,jqXHR.responseJSON.mensaje);
            // $("#resultFormSendAgenda").html(jqXHR.responseText)
            // return false

            let erroresPeticion =""
            if(jqXHR.responseJSON){
                if(jqXHR.responseJSON.mensaje){
                    let erroresMensaje = jqXHR.responseJSON.mensaje  //captura objeto
                    let mensaje = errors.mensajeErrorJson(erroresMensaje)
                    erroresPeticion += mensaje 
                } 
            }
            if(jqXHR.status){
                let mensaje = errors.codigos(jqXHR.status)
                erroresPeticion += "<br> "+mensaje
            }
            erroresPeticion = (erroresPeticion.trim() == 0) ? "hubo un error en el registro, intente nuevamente." : erroresPeticion

            $("#resultFormSendAgenda").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                        ${erroresPeticion}</div>`)

                
            return false

        }) 

    })

    function cargaHistorialGestionAgenda()
    {
        let idAgenda = $("#registrosGestiones").data("uno")

        if (idAgenda == "" || idAgenda == undefined || idAgenda == null) {
            peticiones.redirectTabs($("#AgendasListadoTab"))
            $("#body-errors-modal").html(`<div class="w-100 text-danger text-center">No se puede identificar la agenda, intente actualizando la web nuevamente.</div>`)
            $('#errorsModal').modal('show')
            return false
        }

        peticiones.redirectTabs($("#historialGestionIndividualTab"))

        $("#resultHistorialAgendaCli").DataTable({
            "destroy": true,
            "processing": true, 
            "serverSide": true,
            "dom":'<"row mx-0"'
                    +'<"col-12 col-sm-6"l>>'
                    +'<"row"'
                    +'<"col-sm-12 px-0 table-responsive tableFixHead"t>>'
                    +'<"row"'
                    +'<"col-12 col-sm-5"i><"col-12 col-sm-7"p>>'
                    +'r',
            "ajax": {  
                    'url':`/administrador/agendas/gestion/lista`,
                    "type": "GET", 
                    "data": function ( d ) { 
                             d.idAgenda = idAgenda;
                    },
                    'error': function(jqXHR, textStatus, errorThrown)
                    {  
    
                             console.log( "Error: " ,jqXHR, textStatus, errorThrown); 
                           
                            //alert("Se generó un error con la petición, Se intentará traer nuevamente.")
                          
    
                            //$("#body-errors-modal").html(jqXHR.responseText)
                            //$('#errorsModal').modal('show')
                            //return false
                            peticiones.redirectTabs($("#AgendasListadoTab"))
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
                            erroresPeticion = (erroresPeticion.trim() == 0) ? "hubo un error en el servicio al listar el historial, intente nuevamente." : erroresPeticion
                    
                            $("#body-errors-modal").html(`<div class="text-danger">${erroresPeticion}</div>`)
                            $('#errorsModal').modal('show')
                            return false
    
                    }
            }, 
            "columns": [
                {data: 'idagenda'},
                {data: 'estado'},
                {data: 'quiebre'},
                {data: 'comentario'},
                {data: 'usuario'},
                {data: 'fechamov'}
            ],
            'columnDefs': [ 
                    {
                        'targets': '_all',
                        'createdCell':  function (td, cellData, rowData, row, col) { 
                             
    
                        }
                    }
            ] ,
            "initComplete": function(){
                // console.log("Termino la carga completa")
               
            },
            "pageLength": 15,
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
                        '<option value="150">150</option>'+
                        '<option value="250">250</option>'+
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
    
        $("#resultHistorialAgendaCli").parent().addClass("table-responsive tableFixHead") 
            // $("#filtroContentHFC").removeClass("d-none")
    
        let tablaHead = $('.tableFixHead').find('thead th')
        $('.tableFixHead').on('scroll', function() {
        // console.log("ejecutando"+this.scrollTop); 
        tablaHead.css('transform', 'translateY('+ this.scrollTop +'px)'); 
        }); 

         

    }

    //Historial de Agenda

    $("body").on("click", "#registrosGestiones", function(){
        cargaHistorialGestionAgenda()
    })

})