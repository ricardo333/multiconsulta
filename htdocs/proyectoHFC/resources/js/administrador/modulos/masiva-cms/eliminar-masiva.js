import peticiones from './peticiones.js'

$(function(){

    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    //Criticos
    $("body").on("click",".eliminarMasiva", function(){

        let nodo = $(this).data("uno")
        let troba = $(this).data("dos")

        console.log(nodo)
        console.log(troba)

        if(nodo == "" || troba == ""){ 
            $("#body-errors-modal").html(`<div class="text-danger">No se puede identificar el nodo o troba, intente nuevamente</div>`)
            $('#errorsModal').modal('show')
            return false 
        }
        
        let parametros = {
          nodo,troba
        }
 
        peticiones.eliminarMasivaCms(parametros);


    })

      

})