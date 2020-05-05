import peticiones from './peticiones.js'

$(function(){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    peticiones.loadMapaCallPeru()

    $("#filtroBasicoMCP").click(function(){
        $("#filtroMapaCallPeru").modal("hide")
        peticiones.loadMapaCallPeru()
    })

    $("body").on("click","#activarFiltroMapaCallPeru", function(){
        $("#filtroMapaCallPeru").modal("show")
    })

    $("body").on("click","#verHistoricoNivelesPorPuerto", function(){
        peticiones.redirectTabs($("#graficoHistoricoNivelesTab"))
        let puerto = $(this).data("uno")
        peticiones.cargaGraficoHistoricoNiveles(puerto)
    })

    $(".return_mapaCallPTab").click(function(){
        peticiones.redirectTabs($("#mapaCallPeruGrafTab"))
    })

    //Maximizar

    $(".maxi_tab").click(function(){
       
        $("#tabsMapaCallPeruContent").toggleClass("fullscreen");
  
        if ($("#tabsMapaCallPeruContent").hasClass("fullscreen")) {
         // console.log("tiene la clase full ")
           $(".maxi_tab").html('<i class="icofont-close-line-squared-alt"></i>') 
        }else{
         // console.log("no tiene la clase full ")
          $(".maxi_tab").html('<i class="icofont-maximize"></i>')  
        } 
   
      })


})