import peticiones from './peticiones.js'
import ingresoAverias from  "@/administrador/modulos/ingreso-averias/peticiones.js"

$(function(){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("body").on("click",".redirigeMando", function(){

        
        let n = $(this).data("uno")
        let url = ""

        peticiones.redirectTabs($('#verIngresoAveriasTab')) 

        if (n=="Total_Averias") {
            url = "/administrador/ingreso-averias"
        }
       
        /*
        let parametro = {
            'n':n
        }
        */
       let motivo = "cuadroMando"

      // console.log("Los valores enviados son:"+url+" "+motivo)


       //ingresoAverias.cargandoPeticionPrincipal($('#resultDiagnosticoMasivo'),'/administrador/caidas/diagnostico-masivo/view',parametros)
       peticiones.enrutamiento(url,motivo)
 
    })

})