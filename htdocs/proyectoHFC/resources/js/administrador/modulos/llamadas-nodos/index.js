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
          peticiones.cargandoPeticionPrincipal()
      }

    }, 60000); 

    loadPrincipalLlamadasNodo()
     
    function loadPrincipalLlamadasNodo()
    {
      let parametros = {}
      parametros.jefatura = $("#listajefatura").val()
      let columnasLlamadasNodo = peticiones.armandoColumnasUno()
      let tabla = $("#resultLlamadasNodo");
      peticiones.cargaLlamadasNodoLista(columnasLlamadasNodo,BUTTONS_LLAMADAS_NODO,parametros,tabla)
    }

    $("#filtroLlamadasNodo").click(function(){
      $("#filtroContentLlamadasNodo").hide();
      loadPrincipalLlamadasNodo()
    })
 
    //Maximizar
    $(".maxi_tab").click(function(){
        $("#tabsLlamadasNodoContent").toggleClass("fullscreen");
        if ($("#tabsLlamadasNodoContent").hasClass("fullscreen")) {
        // console.log("tiene la clase full ")
          $(".maxi_tab").html('<i class="icofont-close-line-squared-alt"></i>') 
        }else{
          //console.log("no tiene la clase full ")
          $(".maxi_tab").html('<i class="icofont-maximize"></i>')  
        } 
    })

    //Return
    $("body").on("click",".return_llamadas", function(){

      peticiones.redirectTabs($('#llamadasNodoTab')) 
        
    })


})