import peticiones from './peticiones.js'


$(function(){

    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
 
    INTERVAL_LOAD =  setInterval(() => { 

        if (ESTA_ACTIVO_REFRESH) { 
            if ($( ".listaCaidas" ).hasClass( "active" )) {
              //console.log("Iniciando una nueva peticion....")
              peticiones.cargandoPeticionPrincipal()
            } 
        }

    }, 30000); 

   
 
    peticiones.cargandoPeticionPrincipal()
     

    $(".filtroBasicoCaidasGeneral").click(function(){ 
      peticiones.cargandoPeticionPrincipal()
    })

     
    //Tipo Caida

    $("#display_filter_special").change(function(){
      peticiones.cargandoPeticionPrincipal()
    })
 
    //Maximizar

    $(".maxi_tab").click(function(){
       
        $("#tabsCaidasContent").toggleClass("fullscreen");

        if ($("#tabsCaidasContent").hasClass("fullscreen")) {
        // console.log("tiene la clase full ")
          $(".maxi_tab").html('<i class="icofont-close-line-squared-alt"></i>') 
        }else{
          //console.log("no tiene la clase full ")
          $(".maxi_tab").html('<i class="icofont-maximize"></i>')  
        } 
  
    })

    //Return
    $("body").on("click",".return_caidas", function(){
        
      let valorFiltroEspecial = $("#display_filter_special").val();

      let params = peticiones.getDataRequiredFilterCaidas(valorFiltroEspecial);

      peticiones.redirectTabs(params.redirect)

       /* let filtroAveriasHfcGpon = $("#display_filter_special").val()
        if (filtroAveriasHfcGpon == "monitor_averias_hfc") {
            peticiones.redirectTabs($('#monitorAveriasHFCTab')) 
        }else{
            peticiones.redirectTabs($('#monitorAveriasGPONTab')) 
        }*/
       // peticiones.redirectTabs($('#caidasMasivasTab')) 
        
    })

     


})