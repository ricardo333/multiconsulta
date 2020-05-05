import peticiones from './peticiones.js'

$(function(){

    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    INTERVAL_LOAD =  setInterval(() => {
      if (ESTA_ACTIVO_REFRESH) { 
          if ($( ".conteoModems" ).hasClass( "active" )) {
            //console.log("Iniciando una nueva peticion....")
            peticiones.cargandoPeticionPrincipal()
          } 
      }

    }, 150000); 

    loadPrincipalConteoModems()
     
    function loadPrincipalConteoModems()
    {
      let columnasConteoModems = peticiones.armandoColumnasUno()
      let tabla = $("#resultConteoModems");
      peticiones.cargaConteoModemsLista(columnasConteoModems,tabla)
    }
 
    //Maximizar
    $(".maxi_tab").click(function(){
        $("#tabsConteoModems").toggleClass("fullscreen");
        if ($("#tabsConteoModems").hasClass("fullscreen")) {
        // console.log("tiene la clase full ")
          $(".maxi_tab").html('<i class="icofont-close-line-squared-alt"></i>') 
        }else{
          //console.log("no tiene la clase full ")
          $(".maxi_tab").html('<i class="icofont-maximize"></i>')  
        } 
    })

})