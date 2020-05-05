import errors from  "@/globalResources/errors.js"

const  reaprovisionamiento = {}

reaprovisionamiento.resetCM = function resetCM(parametros,route)
{

    let refreshAveriaCoe = parametros.refreshAveriaCoe
    if (refreshAveriaCoe == false) {
        $("#contenedor_multiconsulta_body").addClass("d-none")
        $("#preload_multi").html(`<div id="carga_person">
                                        <div class="loader">Loading...</div>
                                    </div>`); 
    }
   
        $.ajax({
            url:route,
            method:"post",
            data:parametros,
            dataType: "json", 
        })
        .done(function(data){

            if (refreshAveriaCoe == false) {
                $("#contenedor_multiconsulta_body").removeClass("d-none")
                $("#preload_multi").html(``); 
            }
 
             // console.log("El resultado es:",data) 
              
            $("#body-success-modal").html(`<div class="text-center text-secondary text-sm">${data.mensaje}</div>`)
            $('#successModal').modal('show')
            
           
        })
        .fail(function(jqXHR, textStatus){

            if (refreshAveriaCoe == false) {
                $("#contenedor_multiconsulta_body").removeClass("d-none")
                $("#preload_multi").html(``);
            }
          

            // console.log( "Error: " + jqXHR, textStatus); 
            // 
           // $("#body-errors-modal").html(jqXHR.responseText)
           // $('#errorsModal').modal('show')

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

           $("#body-errors-modal").html(erroresPeticion)
           $('#errorsModal').modal('show')

           return false
            
        }); 

}



export default reaprovisionamiento