import peticiones from './peticiones.js'


$(function(){

    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    INTERVAL_LOAD =  setInterval(() => { 
        
        if (ESTA_ACTIVO_REFRESH) { 
              //console.log("Iniciando una nueva peticion....")
              $("#preloadCharger").html("");
              peticiones.cargandoPeticionPrincipal()
        }

    }, 30000); 

    peticiones.cargandoPeticionPrincipal()

    //Modales
    $(".modal-seguimiento-llamadas-descargar").click(function(){
      $("#descargasModalSeguimientoLlamadas").modal("show");
    })
 
    //Maximizar

    $(".maxi_tab").click(function(){
       
        $("#tabsSeguimientoLlamadasContent").toggleClass("fullscreen");
        if ($("#tabsSeguimientoLlamadasContent").hasClass("fullscreen")) {
        // console.log("tiene la clase full ")
          $(".maxi_tab").html('<i class="icofont-close-line-squared-alt"></i>') 
        }else{
          //console.log("no tiene la clase full ")
          $(".maxi_tab").html('<i class="icofont-maximize"></i>')  
        } 
  
    })
    
})