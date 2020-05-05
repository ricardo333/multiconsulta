import peticiones from './peticiones.js'

$(function(){

    $("body").on("click",".killProcess", function(){

        let id = $(this).data("uno")
        let tipoServer = $("#display_filter_special").val();

        console.log(id);

        $.ajax({
            url: "/administrador/monitor-performance/kill",
            method: 'get',
            data: {
                id,
                tipoServer
            },
            cache: false, 
            })
            .done(function(result){
                
                //alert(result);
                peticiones.cargandoPeticionPrincipal()

            })

            .fail(function(xhr, jqXHR, textStatus) {
                console.log(xhr)
                console.log(jqXHR)
                console.log(textStatus)
                var errorMessage1 = xhr.status + ': ' + "Hubo un error en los datos, intente en un minuto por favor."

                $("#errorExcel").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                ${errorMessage1}</div>`); 
            });

    })


})