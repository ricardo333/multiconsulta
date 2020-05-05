import peticiones from './peticiones.js'

$(function(){ 

    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    INTERVAL_LOAD =  setInterval(() => {
      if (ESTA_ACTIVO_REFRESH) { 
          if ($( ".saturacionDown" ).hasClass( "active" )) {
            //console.log("Iniciando una nueva peticion....")
            peticiones.cargandoPeticionPrincipal()
          } 
      }

    }, 30000);

    loadPrincipalSaturacionDown()
     
    function loadPrincipalSaturacionDown()
    {

      /*
      let parametros = {}
      parametros.cmts = $("#listaPuertosSaturacionDown").val()
      let columnasCaidas = peticiones.armandoColumnasUno()
      let tabla = $("#resultSaturacionDown");
      peticiones.cargaSaturacionDownLista(columnasCaidas,BUTTONS_CAIDAS_MASIVAS,parametros,tabla)
      */

      
      let parametros = {}
      
      if ($("#filtroCuadroMando").length) {
        parametros.motivo = "cuadroMando"
        parametros.cmts = $("#filtroCuadroMando").val()
        let columnasCaidas = peticiones.armandoColumnasUno()
        let tabla = $("#resultSaturacionDown");
        peticiones.cargaSaturacionDownLista(columnasCaidas,BUTTONS_CAIDAS_MASIVAS,parametros,tabla)
      } else {
        parametros.motivo = ""
        parametros.cmts = $("#listaPuertosSaturacionDown").val()
        let columnasCaidas = peticiones.armandoColumnasUno()
        let tabla = $("#resultSaturacionDown");
        peticiones.cargaSaturacionDownLista(columnasCaidas,BUTTONS_CAIDAS_MASIVAS,parametros,tabla)
      }
      
      
    }

    $("#filtroSaturacionDown").click(function(){
      $("#filtroContentSaturacionDown").hide();
      loadPrincipalSaturacionDown()
    })
 
    //Maximizar
    $(".maxi_tab").click(function(){
        $("#tabsSaturacionDown").toggleClass("fullscreen");
        if ($("#tabsSaturacionDown").hasClass("fullscreen")) {
        // console.log("tiene la clase full ")
          $(".maxi_tab").html('<i class="icofont-close-line-squared-alt"></i>') 
        }else{
          //console.log("no tiene la clase full ")
          $(".maxi_tab").html('<i class="icofont-maximize"></i>')  
        } 
    })

    //Return
    $("body").on("click",".return_saturacion_down", function(){

      peticiones.redirectTabs($('#saturacionDownTab')) 
        
    })


})