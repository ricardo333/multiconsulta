import errors from  "@/globalResources/errors.js"

$(function(){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("body").on("click","#actualizarEtiquetadoPuertos", function(){
      
        //let n = $(this).data("uno")     //let trobas = $(this).data("uno")
        let n = $(this).parent().parent().find(".descripcionEtiquetadoPuertos").val()     
        let t = $(this).data("dos")     //let interface = $(this).data("dos") 
        let r = $(this).data("tres")    //let cmts = $(this).data("tres") 
        let s = $(this).data("cuatro")  //let cmtsfil = $(this).data("cuatro") 

        let parametros = {
                'n':n,
                't':t,
                'r':r,
                's':s
        }

        $("#preloadMaping").html(`<div id="carga_person" class="pre-estados-modems">
                                  <div class="loader">Loading...</div>
                                </div>`)

        $.ajax({
            url:`/administrador/etiquetado-puertos/actualizar`,
            method:"post",
            data:{ 
              n,
              t,
              r,
              s
            },
            dataType: "json",
            }).done(function(data){
                //console.log('hola');
                $("#preloadMaping").html("");
                //console.log(data.response);
                //console.log('hola');
                $("#mensajeEtiquetaPuertosModal").modal("show");   
                $("#resultMensajeEtiquetaPuertosModal").html(`<h5 class="text-success text-center text-uppercase font-weight-bold">El registro:</h5>                                  
                                                      <table class="table table-hover table-bordered w-100">
                                                        <thead>
                                                          <tr class="text-center">
                                                            <th>Cmts</th>
                                                            <th>Interface</th>
                                                            <th>Descripción</th>
                                                          </tr>
                                                        </thead>
                                                        <tbody>
                                                          <tr class="text-center">
                                                            <td>${data.response.r}</td>
                                                            <td>${data.response.t}</td>
                                                            <td>${data.response.n}</td>
                                                          </tr>
                                                        </tbody>
                                                      </table>
                                                      <p class="text-center font-weight-bold font-italic">Se ha actualizado correctamente</p>`);
            
            }).fail(function(jqXHR, textStatus){ 
                $("#preloadMaping").html("");
                
                $("#formActiveCm").removeClass("d-none")
                $("#preloadActivarCm").html(``);
    
                console.log( "Error: " + jqXHR, textStatus); 
                 
                 //$("#rptaActivarFormSend").html(jqXHR.responseText) 
                 //return false;

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
                erroresPeticion = (erroresPeticion.trim() == 0) ? "hubo un error en el servicio, intente nuevamente." : erroresPeticion

                $("#rptaActivarFormSend").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                        ${erroresPeticion}</div>`) 
                return false
                

            });
          
    })

})