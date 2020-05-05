import errors from  "@/globalResources/errors.js"
import valida from  "@/globalResources/forms/valida.js"

$(function(){
 
    $("body").on("click","#activarCM", function(){

        $("#preloadActivarCm").html(``);
        $("#activarCmModal").modal("show")

    })

    $("body").on("click","#activarCMCliente", function(){
 
        let estadoServ = $("#activarCM").data("uno")
        let mac = $("#activarCM").data("dos")
        let justificacion = $("#justificacionActivacion").val()

        let validacionActivacion = validacionCotinueActivacion()
        if(!validacionActivacion){ 
            return false
        }
        

        $("#formActiveCm").addClass("d-none")
        $("#preloadActivarCm").html(`<div id="carga_person">
                                        <div class="loader">Loading...</div>
                                    </div>`);  


        $.ajax({
            url:`/administrador/multiconsulta/activar-cm/detalle`,
            method:"post",
            data:{ 
                estadoServ,
                mac,
                justificacion
            },
            dataType: "json",
            }).done(function(data){

                $("#formActiveCm").removeClass("d-none")
                $("#preloadActivarCm").html(``); 

               // $("#justificacionActivacion").html("")
                $("#justificacionActivacion").val("")
             
                 //console.log("El resultado es:",data)  
     
                $("#rptaActivarFormSend").html(`<div class="container text-center font-weight-bold alert alert-success fade show" role="alert">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                ${data.mensaje}</div>`) 
                
            
            }).fail(function(jqXHR, textStatus){ 
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

function validacionCotinueActivacion()
{
    let motivo = $("#justificacionActivacion") 

    if(!valida.isValidText(motivo.val())){
        valida.isValidateInputText(motivo)
        $("#rptaActivarFormSend").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            El campo Motivo del cambio de Velocidad es requerido</div>`); 
       
        return false
    }

    if(motivo.val().length < 5){
        valida.isValidateInputText(motivo)
        $("#rptaActivarFormSend").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        El campo Motivo del cambio de Velocidad tiene una longitud muy corta.</div>`);  
        return false
    }

    $(".validateText").removeClass("valida-error-input")
    $("#rptaActivarFormSend").html(``)
     
    return true
}