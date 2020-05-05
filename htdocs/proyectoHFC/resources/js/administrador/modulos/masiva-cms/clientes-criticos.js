import peticiones from './peticiones.js'

$(function(){

    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    //Criticos
    $("body").on("click",".verListaCriticos", function(){

        let nodo = $(this).data("uno")
        let troba = $(this).data("dos")
        
        let parametros = {
          nodo,troba
        }
  
        peticiones.redirectTabs($('#listaCriticosNodoTrobaTab')) 

        peticiones.listaClientesCriticos($("#resultListaClientesCriticos"),parametros);
    })

      

})