import peticiones from './peticiones.js'


$(function(){

    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    INTERVAL_LOAD =  setInterval(() => { 
        
        if (ESTA_ACTIVO_REFRESH) { 
            //if ($( ".listaIngresoAveriasJefaturas" ).hasClass( "active" )) {
              //console.log("Iniciando una nueva peticion....")
              $("#preloadCharger").html("");
              peticiones.cargandoPeticionPrincipal()
            //} 
        }

    }, 180000); 

    peticiones.cargandoPeticionPrincipal()

    //Filtro
    $(".filtro-ingreso-averias-jefaturas").click(function(){
      $("#filtroAveriasJefaturas").slideToggle()
    })
    $(".filtro-ingreso-averias-motivos").click(function(){
      $("#filtroAveriasMotivos").slideToggle()
    })

    //Modales
    $(".modal-ingreso-averias-jefaturas").click(function(){
      $("#descargasModalIngresoAveriasJefatura").modal("show");
    })

    $(".filtroBasicoCaidasGeneral").click(function(){ 
      peticiones.cargandoPeticionPrincipal()
    })

    //Tipo Caida

    $("#display_filter_special").change(function(){
      peticiones.cargandoPeticionPrincipal()
    })
 
    //Maximizar

    $(".maxi_tab").click(function(){
       
        $("#tabsIngresoAveriasContent").toggleClass("fullscreen");
        if ($("#tabsIngresoAveriasContent").hasClass("fullscreen")) {
        // console.log("tiene la clase full ")
          $(".maxi_tab").html('<i class="icofont-close-line-squared-alt"></i>') 
        }else{
          //console.log("no tiene la clase full ")
          $(".maxi_tab").html('<i class="icofont-maximize"></i>')  
        } 
  
    })

    
    $("#filtroIngresoAveriaJefaturaMotivos").click(function(){

      peticiones.cargaGraficaAveriasMotivosLista()

    })
    
    $("#filtroIngresoAveriaJefatura").click(function(){

      peticiones.cargaGraficaAveriasJefaturasLista()

    })
    
})
