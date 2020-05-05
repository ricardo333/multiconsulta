import peticiones from './peticiones.js'
import errors from  "@/globalResources/errors.js"

$(function(){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    $("#listaDistanciaClientes").change(function(){
        obtenerLocalizacion();
    })


    obtenerLocalizacion()


    //-------------------------------------------------------------------------------//
    function cargarMapa(lati,long){
         
        
        $("#mapaOutside").html(`<div id="carga_person">
                                    <div class="loader">Loading...</div>
                                </div>`);

        console.log("La latitud de la posicion actual es: "+lati);
        console.log("La longitud de la posicion actual es: "+long);

        let distancia = $("#listaDistanciaClientes").val();
        console.log("La distancia seleccionada es: "+distancia);


        $.ajax({
            url:"/administrador/diagnostico-outside/lista",
            method:"get",
            data:{
                latitud:long,
                longitud:lati,
                distancia:distancia
            },
            dataType: "json", 
        })
        .done(function(data){
           console.log("El resultado es:",data)  
            let mapa = JSON.parse(data.response.html)
             $("#mapaOutside").html(mapa)
        })
        .fail(function(jqXHR, textStatus){
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

            $("#mapaOutside").html(erroresPeticion)

            return false
 
        });  

    }



    $("#diagnosticar").click(function(){

        $("#mapaOutside").html(`<div id="carga_person">
                                    <div class="loader">Loading...</div>
                                </div>`);

        //let lati = $("#latitud").val();
        //let long = $("#longitud").val();

        let lati = -12.112555;
        let long = -77.020259;

        console.log("La latitud de la posicion actual es: "+lati);
        console.log("La longitud de la posicion actual es: "+long);

        let distancia = $("#listaDistanciaClientes").val();
        console.log("La distancia seleccionada es: "+distancia);


        $.ajax({
            url:"/administrador/diagnostico-outside/diagnostico",
            method:"get",
            data:{
                latitud:long,
                longitud:lati,
                distancia:distancia
            },
            dataType: "json", 
        })
        .done(function(data){
           console.log("El resultado es:",data)  
            let mapa = JSON.parse(data.response.html)
             $("#mapaOutside").html(mapa)
        })
        .fail(function(jqXHR, textStatus){
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

            $("#mapaOutside").html(erroresPeticion)

            return false
 
        });


    })



    //-------------------------------------------------------------------------------//

    
    function obtenerLocalizacion() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        } else { 
            //x.innerHTML = "Geolocation is not supported by this browser.";
        }
    }
    
    function showPosition(position) {

        //var latitud = position.coords.latitude.toFixed(6);
        //var longitud = position.coords.longitude.toFixed(6);

        var latitud = -12.112555;
        var longitud = -77.020259;

        document.getElementById("latitud").value = latitud;
        document.getElementById("longitud").value = longitud;

        console.log(latitud);
        console.log(longitud);
        //document.getElementById("latitud").value = latitud;
        //document.getElementById("longitud").value = longitud;

        cargarMapa(latitud,longitud)
        
    }
    


    //Maximizar
    $(".maxi_tab").click(function(){
       
        $("#tabsDiagnosticoOutsideContent").toggleClass("fullscreen");
  
        if ($("#tabsDiagnosticoOutsideContent").hasClass("fullscreen")) {
         // console.log("tiene la clase full ")
           $(".maxi_tab").html('<i class="icofont-close-line-squared-alt"></i>') 
        }else{
         // console.log("no tiene la clase full ")
          $(".maxi_tab").html('<i class="icofont-maximize"></i>')  
        } 
   
      })


})