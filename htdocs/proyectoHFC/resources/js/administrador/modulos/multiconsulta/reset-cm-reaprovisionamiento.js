import reaprovisionamiento from  "@/globalResources/modulos/cm-reaprovisionamiento.js"

$(function(){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    $("#resetCmReaprovisionamiento").click(function(){

        var opcionReaprov = confirm("¿Está seguro del reaprovisionamiento del CM?, ¡confirme nuevamente por favor!.");
        if (!opcionReaprov) {
            return false
        } 

        let idCliente = $(this).data("uno")
        let idServicio = $(this).data("dos")
        let idProducto = $(this).data("tres")
        let idVenta = $(this).data("cuatro")

        let data = {
            idCliente,
            idServicio,
            idProducto,
            idVenta,
            "refreshAveriaCoe":false
        }
        reaprovisionamiento.resetCM(data,'/administrador/multiconsulta/reset-cm-reaprovisionamiento/detalle')
 
    })
})

