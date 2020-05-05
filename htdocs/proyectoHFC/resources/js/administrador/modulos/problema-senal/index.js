import peticiones from './peticiones.js'


$(function(){

    resultProblemaSenal($('#resultProblemaSenal'),"","")


    $("#filtroBasicoProblemas").click(function(){
        let jefatura = $("#listaJefaturasProblemas").val()
        let estado = $("#listaEstadosProblemas").val()
        //resultProblemaSenal($('#resultMonitoreoAveriasHfc'),jefatura,estado,$("#display_filter_special").val())
        //resultProblemaSenal($('#resultProblemaSenal'),jefatura,estado,$("#display_filter_special").val())
        resultProblemaSenal($('#resultProblemaSenal'),jefatura,estado)
    })


    $("body").on("click",".return_problemaSenal", function(){
        
        peticiones.redirectTabs($('#problemaSenalTab')) 
        
    })




    //function resultProblemaSenal(tabla,jefatura,estado)
    function resultProblemaSenal(tabla,jefatura,estado)
    {
        console.log("la jefatura a enviar es: ",jefatura)
        console.log("el estado a enviar es: ",estado)

        let COLUMNS_MONITOR_AVERIAS = peticiones.armandoColumnas()
        let BUTTONS_MONITOR_AVERIAS = []
 
        BUTTONS_MONITOR_AVERIAS = BUTTONS_MONITOR_AVERIAS_HFC

 
       let parametersDataAverias = {
           'jefatura':jefatura,
           'estado':estado,
           //'filtroHfcGpon':filtroHfcGpon,
       }

       peticiones.cargaDataProblemaSenal(COLUMNS_MONITOR_AVERIAS,COLUMNS_DEFS_MONITOR_AVERIAS,BUTTONS_MONITOR_AVERIAS,parametersDataAverias,tabla)
 
    }

 

    //Maximizar

    $(".maxi_tab").click(function(){
       
        $("#tabsProblemaSenalContent").toggleClass("fullscreen");
  
        if ($("#tabsProblemaSenalContent").hasClass("fullscreen")) {
         // console.log("tiene la clase full ")
           $(".maxi_tab").html('<i class="icofont-close-line-squared-alt"></i>') 
        }else{
          //console.log("no tiene la clase full ")
          $(".maxi_tab").html('<i class="icofont-maximize"></i>')  
        } 
   
    })





})