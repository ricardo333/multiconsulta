import errors from  "@/globalResources/errors.js"
import valida from  "@/globalResources/forms/valida.js"

$(function(){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
 
    $("body").on("click","#cambiarVelocidad",function(){
        
       
        let mac = $(this).data("uno")
        let velocidad = $(this).data("dos")

        $("#velocidadCambioRespuesta").html(`<div id="carga_person">
                                                <div class="loader">Loading...</div>
                                            </div>`); 

        $("#velocidadModal").modal("show")

        $.ajax({
            url:"/administrador/multiconsulta/velocidad-cm/detalle",
            method:"get",
            data:{
                mac,
                velocidad
            },
            dataType: "json", 
        })
        .done(function(data){
            //console.log("el resultado es:",data)
            let velocidades = JSON.parse(data.response.html) 
            $("#velocidadCambioRespuesta").html(velocidades); 
              
        })
        .fail(function(jqXHR, textStatus){
            //console.log("Error:",jqXHR, textStatus)
            //$("#velocidadCambioRespuesta").html(jqXHR.responseText); 
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

            $("#velocidadCambioRespuesta").html(erroresPeticion); 
            return false
             
  
        });

    })

    $("body").on("click","#enviar_velocidad",function(){
       
        let nueva_velocidad= $("#nvel").val();
        let f_inicio = $("#f_inicio").val();
        let dias = $("#dias").val();
        let motivo = $("#motivo_cambio_velocidad").val();
        let mac = $(this).data("uno")
        let velocidad_actual = $(this).data("dos")

        let validacionCambioV = validacionCotinueCambio()
        if(!validacionCambioV){ 
            return false
        }


        $("#velocidadCambioRespuesta").addClass("d-none")
        $("#preloadVelocidadCambio").html(`<div id="carga_person">
                                            <div class="loader">Loading...</div>
                                        </div>`);

         
        $.ajax({
            url:`/administrador/multiconsulta/velocidad-cm/update`,
            method:"post",
            data:{ 
                velocidad_actual,
                nueva_velocidad,
                f_inicio,
                dias,
                motivo,
                mac
            },
            dataType: "json",
        }).done(function(data){

            $("#velocidadCambioRespuesta").removeClass("d-none")
            $("#preloadVelocidadCambio").html("");
  
            $("#rpta_formVelocidad_send").html(`<div class="container text-center font-weight-bold alert alert-success fade show" role="alert">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                ${data.mensaje}</div>`); 

            //console.log("el resultado es: ",data)
            

        }).fail(function(jqXHR, textStatus){ 

            $("#velocidadCambioRespuesta").removeClass("d-none")
            $("#preloadVelocidadCambio").html("");

            // console.log( "Request failed: ",jqXHR, textStatus );
            //$("#rpta_formVelocidad_send").html(jqXHR.responseText); 
            //return false

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

            $("#rpta_formVelocidad_send").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                ${erroresPeticion}</div>`); 

            return false
              
   
        }); 

    })
})

function validacionCotinueCambio()
{
    let motivo = $("#motivo_cambio_velocidad") 

    if(!valida.isValidText(motivo.val())){
        valida.isValidateInputText(motivo)
        $("#rpta_formVelocidad_send").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            El campo Motivo del cambio de Velocidad es requerido</div>`); 
       
        return false
    }

    if(motivo.val().length < 5){
        valida.isValidateInputText(motivo)
        $("#rpta_formVelocidad_send").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        El campo Motivo del cambio de Velocidad tiene una longitud muy corta.</div>`);  
        return false
    }

    $(".validateText").removeClass("valida-error-input")
    $("#rpta_formVelocidad_send").html(``)
     
    return true

}