import peticiones from './peticiones.js'

import mapa from  "@/globalResources/modulos/mapa.js"

$(function(){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
 

    $(".return_verMapaTab").click(function(){
        peticiones.redirectTabs($('#verMapaTab')) 
     })
     
    $("body").on("click",".verMapa", function(){
          
        let n = $(this).data("uno")
        let t = $(this).data("dos")
        let id = 0

        let parametros = {
             n, t, id
        }
        //console.log("parametros a enviar del mapa son: ",parametros)
 
        peticiones.redirectTabs($('#verMapaTab')) 

        mapa.vistaGeneral($("#mapa_content_carga"),"/administrador/llamadas/mapa/view",parametros)
  
    })
     
})
