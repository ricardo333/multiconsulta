import peticiones from './peticiones.js'

$(function(){

    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    loadPrincipalEstadosModems()
     
    function loadPrincipalEstadosModems()
    {
      let columnasEstadosModems = peticiones.armandoColumnasUno()
      let tabla = $("#resultEstadosModems");
      peticiones.cargaEstadosModemsLista(columnasEstadosModems,tabla)
    }
 
    //Maximizar
    $(".maxi_tab").click(function(){
        $("#tabsEstadosModems").toggleClass("fullscreen");
        if ($("#tabsEstadosModems").hasClass("fullscreen")) {
        // console.log("tiene la clase full ")
          $(".maxi_tab").html('<i class="icofont-close-line-squared-alt"></i>') 
        }else{
          //console.log("no tiene la clase full ")
          $(".maxi_tab").html('<i class="icofont-maximize"></i>')  
        } 
    })

})