import peticiones from './peticiones.js'
import errors from  "@/globalResources/errors.js"
import graficoDown from  "@/globalResources/modulos/grafico-down.js"

$(function(){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("body").on("click",".verGraficoSatDown", function(){

        let n = $(this).data("uno")  //let cmts = $(this).data("uno")
        let t = $(this).data("dos")  //let pto = $(this).data("dos") 
        let parametros = {
                'n':n,
                't':t
        }
        
        peticiones.redirectTabs($('#graficoSaturacionDownsTab')) 

        $("#resultGraficoDown").html(`<div id="carga_person">
                                                    <div class="loader">Loading...</div>
                                                </div>`);

        graficoDown.grafico('/administrador/saturacion-down/grafico',parametros)                                     

    })

})