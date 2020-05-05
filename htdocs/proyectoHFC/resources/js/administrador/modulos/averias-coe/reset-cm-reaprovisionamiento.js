
import reaprovisionamiento from  "@/globalResources/modulos/cm-reaprovisionamiento.js"

$(function(){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
 
    $("body").on("click",".resetCmReaprovisionamiento", function(){
 
        var opcionReaprov = confirm("¿Está seguro del reaprovisionamiento del CM?, ¡confirme nuevamente por favor!.");
        if (!opcionReaprov) {
            return false
        } 

        let idCliente = $(this).data("uno")
        let idServicio = $(this).data("dos")
        let idProducto = $(this).data("tres")
        let idVenta = $(this).data("cuatro")

        console.log("Los datos enviados son: ",idCliente,idServicio,idProducto,idVenta)

        let data = {
            idCliente,
            idServicio,
            idProducto,
            idVenta,
            "refreshAveriaCoe":true
        }
        reaprovisionamiento.resetCM(data,'/administrador/averias-coe/reset-cm-reaprovisionamiento/detalle')
 

    })
})

