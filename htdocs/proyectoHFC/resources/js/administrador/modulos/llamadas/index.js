import peticiones from './peticiones.js'

$(function(){ 

    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    INTERVAL_LOAD =  setInterval(() => {
      if (ESTA_ACTIVO_REFRESH) { 
          if ($( ".listaLlamadas" ).hasClass( "active" )) {
            //console.log("Iniciando una nueva peticion....")
            peticiones.cargandoPeticionPrincipal()
          } 
      }

    }, 180000); 

    loadPrincipalLlamadas()
     
    function loadPrincipalLlamadas()
    {
      let parametros = {}
      parametros.jefatura = $("#listajefaturaLlamadas").val()
      parametros.top = $("#listaTopLlamadas").val()
      parametros.nodo = $("#nodoJefaturaLlamadas").val() 
      let columnasCaidas = peticiones.armandoColumnasUno()
      let tabla = $("#resultLlamadaTrobas");
      peticiones.cargaLlamadasLista(columnasCaidas,BUTTONS_CAIDAS_MASIVAS,parametros,tabla)
    }

    $("#filtroBasicoLlamadas").click(function(){
      $("#filtroContentLlamadas").hide();
      loadPrincipalLlamadas()
    })
 
    //Maximizar
    $(".maxi_tab").click(function(){
        $("#tabsLlamadasContent").toggleClass("fullscreen");
        if ($("#tabsLlamadasContent").hasClass("fullscreen")) {
        // console.log("tiene la clase full ")
          $(".maxi_tab").html('<i class="icofont-close-line-squared-alt"></i>') 
        }else{
          //console.log("no tiene la clase full ")
          $(".maxi_tab").html('<i class="icofont-maximize"></i>')  
        } 
    })

    //Return
    $("body").on("click",".return_llamadas", function(){

      peticiones.redirectTabs($('#llamadasMasivasTab')) 
        
    })


})