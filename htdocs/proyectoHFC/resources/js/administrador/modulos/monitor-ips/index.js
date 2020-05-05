import peticiones from './peticiones.js'

$(function(){

    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    INTERVAL_LOAD =  setInterval(() => {
      if (ESTA_ACTIVO_REFRESH) { 
          if ($( ".MonitorIPS" ).hasClass( "active" )) {
            //console.log("Iniciando una nueva peticion....")
            peticiones.cargandoPeticionPrincipal()
          } 
      }

    }, 180000);

    loadPrincipalMonitorIPS()
     
    function loadPrincipalMonitorIPS()
    {
      let columnasMonitorIPS = peticiones.armandoColumnasUno()
      let tabla = $("#resultMonitorIPS");
      peticiones.cargaMonitorIPSLista(columnasMonitorIPS,tabla)
    }
 
    //Maximizar
    $(".maxi_tab").click(function(){
        $("#tabsMonitorIPS").toggleClass("fullscreen");
        if ($("#tabsMonitorIPS").hasClass("fullscreen")) {
        // console.log("tiene la clase full ")
          $(".maxi_tab").html('<i class="icofont-close-line-squared-alt"></i>') 
        }else{
          //console.log("no tiene la clase full ")
          $(".maxi_tab").html('<i class="icofont-maximize"></i>')  
        } 
    })

})