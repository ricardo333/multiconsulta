import errors from  "@/globalResources/errors.js"

const scopeGroup = {}

scopeGroup.cambioScopesGroup = function cambioScopesGroup(data,route)
{

        let refreshCoe = data.refreshAveriaCoe

        $("#formScopeGroup").addClass("d-none")
        $("#preloadScopeGroupSend").html(`<div id="carga_person">
                                            <div class="loader">Loading...</div>
                                        </div>`);

         
        $.ajax({
            url:route,
            method:"post",
            data:data,
            dataType: "json",
        }).done(function(data){

            $("#formScopeGroup").removeClass("d-none")
            $("#preloadScopeGroupSend").html("");

            if (refreshCoe) {
                $("#rptaScopeGroupFormSend").html(`<div class="container text-center font-weight-bold alert alert-success fade show" role="alert">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                ${data.mensaje}. Intente Refrescar la Lista para ver los cambios.</div>`); 
            }else{
                $("#rptaScopeGroupFormSend").html(`<div class="container text-center font-weight-bold alert alert-success fade show" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    ${data.mensaje}</div>`); 
            }
  
            
            

            //console.log("el resultado es: ",data)
            

        }).fail(function(jqXHR, textStatus){ 

            $("#formScopeGroup").removeClass("d-none")
            $("#preloadScopeGroupSend").html("");

             //console.log( "Request failed: ",jqXHR, textStatus );
             //$("#rptaScopeGroupFormSend").html(jqXHR.responseText); 
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

            $("#rptaScopeGroupFormSend").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                ${erroresPeticion}</div>`); 
            return false


             
             
   
        }); 

}

export default scopeGroup
