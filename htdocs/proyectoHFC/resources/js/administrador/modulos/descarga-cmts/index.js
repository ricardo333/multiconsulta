import peticiones from './peticiones.js'

$(function(){

    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    INTERVAL_LOAD =  setInterval(() => { 

          if ($( ".listaCaidas" ).hasClass( "active" )) {
            //console.log("Iniciando una nueva peticion....")
            loadPrincipalDescargaCmts()
          } 

    }, 30000); 


    loadPrincipalDescargaCmts()
     
    function loadPrincipalDescargaCmts()
    {
      let columnasDescargaCmts = peticiones.armandoColumnasCmts()
      let tabla = $("#resultDescargaCmts");
      peticiones.cargaCmtsLista(columnasDescargaCmts,BUTTONS_DESCARGAS_CMTS,tabla)
    }
 
    //Maximizar
    $(".maxi_tab").click(function(){
        $("#tabsDescargaCmts").toggleClass("fullscreen");
        if ($("#tabsDescargaCmts").hasClass("fullscreen")) {
        // console.log("tiene la clase full ")
          $(".maxi_tab").html('<i class="icofont-close-line-squared-alt"></i>') 
        }else{
          //console.log("no tiene la clase full ")
          $(".maxi_tab").html('<i class="icofont-maximize"></i>')  
        } 
    })

})


