import peticiones from './peticiones.js'

$(function(){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    $("body").on("click",".verMultilinkDetalle", function(){
        ///

        peticiones.redirectTabs($('#verMultilinkGraficoTab')) 

        let ip = $(this).data("uno")

        console.log("la IP: ",ip)

        $("#graficoResultadoMultilink").html(`<div id="carga_person">
                                            <div class="loader">Loading...</div>
                                        </div>`)

        $.ajax({
            url:`/administrador/monitor-fuentes/multilink`,
            method:"get",
            data:{ip},
            dataType: "json", 
        })
        .done(function(data){
            $("#graficoResultadoMultilink").html(`<iframe src="http://${ip}:8080" frameborder="0" class="iframe_fuentes"></iframe>`)
           // console.log(data) 
   
        })
        .fail(function(jqXHR, textStatus){

           // console.log("errors:",jqXHR, textStatus)
            // $("#graficoResultadoMultilink").html(jqXHR.responseText)
            peticiones.redirectTabs($('#monitorFuentesListTab')) 
             $("#graficoResultadoMultilink").html("")
             $("#body-errors-modal").html(`<div class="w-100 text-center text-danger">No se encontro el gráfico para la fuente indicada.</div>`)
             $('#errorsModal').modal('show')
            return false
  
        }) 
    })

})