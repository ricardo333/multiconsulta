import errors from  "@/globalResources/errors.js"

const peticiones = {}
/*
peticiones.cargarMapa = function cargarMapa(latitud, longitud)
{

    $("#content_graficoOutside").html(`<div id="carga_person">
                        <div class="loader">Loading...</div>
                    </div>`);

    $.ajax({
            url:"/administrador/diagnostico-outside/lista",
            method:"get",
            data: function ( d ) {
                d.latitud = latitud;
                d.longitud = longitud;
            },
            dataType: "json", 
        })
        .done(function(data){
        //console.log("El resultado es:",data)  
        let mapa = JSON.parse(data.response.html)
        $("#content_graficoOutside").html(mapa)
        })
        .fail(function(jqXHR, textStatus){
        //console.log( "Error: " + jqXHR, textStatus); 
        $("#content_graficoOutside").html(jqXHR.responseText)
        return false
        
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
            
        contenedor.html(erroresPeticion)
            
        return false
            
        }); 


}
*/
export default peticiones

