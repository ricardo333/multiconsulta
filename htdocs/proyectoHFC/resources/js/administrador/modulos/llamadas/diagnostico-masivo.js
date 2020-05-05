import peticiones from './peticiones.js'
import diagnosticoMasivo from  "@/globalResources/modulos/diagnostico-masivo.js"

$(function(){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("body").on("click",".verDiagnosticoMasivo", function(){

        //console.log("Ver diagnostico masivo")
        let n = $(this).data("uno")
        let t = $(this).data("dos")

        peticiones.redirectTabs($('#verDiagMasTab')) 

        let parametros = {
            'n':n,
            't':t
        }

        diagnosticoMasivo.lista($('#resultDiagnosticoMasivo'),'/administrador/llamadas/diagnostico-masivo/view',parametros)
 
    })

})