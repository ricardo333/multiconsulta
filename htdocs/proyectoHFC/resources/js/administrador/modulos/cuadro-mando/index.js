import peticiones from './peticiones.js'


$(function(){

    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    INTERVAL_LOAD =  setInterval(() => { 

        if (ESTA_ACTIVO_REFRESH) { 
            if ($( ".listaCuadroMando" ).hasClass( "active" )) {
              //console.log("Iniciando una nueva peticion....")
              loadPrincipalCuadroMando()
            } 
        }

    }, 30000);



    $("body").on("click",".return_cuadroMando", function(){
        
        peticiones.redirectTabs($('#cuadroMandoTab')) 
        
    })
 
    /*
    INTERVAL_LOAD =  setInterval(() => { 

        if ($( ".listaCaidas" ).hasClass( "active" )) {
            loadPrincipalDescargaCmts()
        } 

    }, 30000); 
    */

    
    $("#filtroCuadroMando").click(function(){
        loadPrincipalCuadroMando()
    })
    


    loadPrincipalCuadroMando()
   
    function loadPrincipalCuadroMando()
    {
        let columnasDescargaCmts = peticiones.armandoColumnasCmts()
        let tabla = $("#resultCuadroMando");
        //let filtro = "";
        peticiones.cargaCmtsLista(columnasDescargaCmts,BUTTONS_CUADRO_MANDO,tabla)

    }

    //Maximizar
    $(".maxi_tab").click(function(){
        $("#tabsCuadroMandoContent").toggleClass("fullscreen");
        if ($("#tabsCuadroMandoContent").hasClass("fullscreen")) {
            $(".maxi_tab").html('<i class="icofont-close-line-squared-alt"></i>') 
        }else{
            $(".maxi_tab").html('<i class="icofont-maximize"></i>')  
        } 
    })

    /*
    $(document).ready(function () {
        $('#resultCuadroMando').dataTable({
            "paging": false
        });
    });
    */



})