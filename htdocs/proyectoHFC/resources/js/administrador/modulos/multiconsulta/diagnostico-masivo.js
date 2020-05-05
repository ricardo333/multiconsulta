import peticiones from './peticiones.js'
import errors from  "@/globalResources/errors.js"
import diagnosticoMasivo from  "@/globalResources/modulos/diagnostico-masivo.js"

$(function(){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    $("body").on("click","#diagnostico_masivo", function(){

       // console.log("Ver diagnostico masivo")
        let n = $(this).data("n")
        let t = $(this).data("t")

        peticiones.redirectTabs($('#diagnosticoMasivoTab')) 

        let parametros = {
            'n':n,
            't':t
        }

        diagnosticoMasivo.lista($('#resultDiagnosticoMasivo'),'/administrador/multiconsulta/diagnostico-masivo/detalle',parametros)

   
    })
    
})