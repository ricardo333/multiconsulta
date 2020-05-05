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

        mapa.vistaGeneral($("#mapa_content_carga"),"/administrador/caidas/mapa/view",parametros)
  
    })


    //Edificios
    $("body").on("click",".show_edificio_details", function(){ 

       // console.log("debe mostrar los edificios...")
        peticiones.redirectTabs($('#detalleEdificiosTab'))

        let des_dtt = $(this).data("desdtt")
        let des_via = $(this).data("nomvia")
        let des_puer = $(this).data("numpuer")

        let parametros = {
            'des_dtt':des_dtt,
            'des_via':des_via,
            'des_puer':des_puer
        }

        mapa.detallesEdificios($('#edificios_content_general'),'/administrador/caidas/mapa/edificios/view',parametros)
   
        
    })

     
})
