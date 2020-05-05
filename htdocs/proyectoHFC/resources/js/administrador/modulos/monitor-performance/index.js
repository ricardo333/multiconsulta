import peticiones from './peticiones.js'


$(function(){

    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    INTERVAL_LOAD =  setInterval(() => { 

      if (ESTA_ACTIVO_REFRESH) { 
          if ($( ".moduloPerformance" ).hasClass( "active" )) {
            //console.log("Iniciando una nueva peticion....")
            peticiones.cargandoPeticionPrincipal()
          } 
      }

    }, 30000); 




    peticiones.cargandoPeticionPrincipal()


    $("#display_filter_special").change(function(){
      peticiones.cargandoPeticionPrincipal()
    })



    //Maximizar
    $(".maxi_tab").click(function(){
       
        $("#tabsPerformanceContent").toggleClass("fullscreen");
        if ($("#tabsPerformanceContent").hasClass("fullscreen")) {
          $(".maxi_tab").html('<i class="icofont-close-line-squared-alt"></i>') 
        }else{
          $(".maxi_tab").html('<i class="icofont-maximize"></i>')  
        } 
  
    })





})