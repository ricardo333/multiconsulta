import peticiones from './peticiones.js'
import historicoNodoTroba from  "@/globalResources/modulos/historico-nodo-trobas.js"

$(function(){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    resultMonitoreoAverias($('#resultMonitoreoAveriasHfc'),"","",$("#display_filter_special").val())


    $("#filtroBasicoHfc").click(function(){
        let jefatura = $("#listaJefaturasHfc").val()
        let estado = $("#listaEstadosHfc").val()
        resultMonitoreoAverias($('#resultMonitoreoAveriasHfc'),jefatura,estado,$("#display_filter_special").val())
    })
     
     
    $("#filtroBasicoGpon").click(function(){
        let jefatura = $("#listaJefaturasGpon").val()
        let estado = $("#listaEstadosGpon").val()
        resultMonitoreoAverias($('#resultMonitoreoAveriasGpon'),jefatura,estado,$("#display_filter_special").val())
    })

    
  
    $("body").on("click",".return_monitorAverias", function(){
        
        let filtroAveriasHfcGpon = $("#display_filter_special").val()
        if (filtroAveriasHfcGpon == "monitor_averias_hfc") {
            peticiones.redirectTabs($('#monitorAveriasHFCTab')) 
        }else{
            peticiones.redirectTabs($('#monitorAveriasGPONTab')) 
        }
       
        
    })

    
    $("body").on("click", ".verHistoricoNodoTroba", function(){
 
        let nodo = $(this).data("uno")
        let troba = $(this).data("dos")
        
        peticiones.redirectTabs($('#historicoNodoTrobaTab')) 

        $("#historico_nodo_troba_actual").html(`${nodo} - ${troba}`)

        let parametros = {
            'nodo':nodo,
            'troba':troba
        }

        historicoNodoTroba.verHistorialNodoTroba($("#resultHistoricoNodoTroba"),'/administrador/monitor-averias/historico/nodo-troba',parametros,true)

        //peticiones.verHistorialNodoTroba(nodo,troba)

    })


    function resultMonitoreoAverias(tabla,jefatura,estado,filtroHfcGpon)
    {
        //console.log("la jefatura a enviar es: ",jefatura,"-> las averias filtro es: ",filtroHfcGpon)

        let COLUMNS_MONITOR_AVERIAS = []
        let BUTTONS_MONITOR_AVERIAS = []
        
        if (filtroHfcGpon == "monitor_averias_hfc") {
           COLUMNS_MONITOR_AVERIAS = peticiones.armandoColumnasHFC()
           BUTTONS_MONITOR_AVERIAS = BUTTONS_MONITOR_AVERIAS_HFC
          
        }else if(filtroHfcGpon == "monitor_averias_gpon"){
           COLUMNS_MONITOR_AVERIAS = peticiones.armandoColumnasGPON()
           BUTTONS_MONITOR_AVERIAS = BUTTONS_MONITOR_AVERIAS_GPON
        }else{
            $("#body-errors-modal").html(`<div class="w-100 text-center text-danger">Seleccione un filtro de monitoreo de averías válido</div>`)
            $('#errorsModal').modal('show') 
            return false
        }
       // let filtroJefatura = jefatura;

       let parametersDataAverias = {
           'jefatura':jefatura,
           'estado':estado,
           'filtroHfcGpon':filtroHfcGpon,
       }

       peticiones.cargaDataMonitorAverias(COLUMNS_MONITOR_AVERIAS,COLUMNS_DEFS_MONITOR_AVERIAS,BUTTONS_MONITOR_AVERIAS,parametersDataAverias,tabla)
 
    }

    $("#display_filter_special").change(function(){
        //console.log("Cambio de Filtro",$(this).val())
       
        if ($(this).val() == "monitor_averias_hfc") {
            peticiones.redirectTabs($('#monitorAveriasHFCTab')) 
            peticiones.ultimoUpdateMoAv("/administrador/monitor-averias/ultimo-update",$("#fecha_ultimo_maver_hfc"))
            resultMonitoreoAverias($('#resultMonitoreoAveriasHfc'),"","",$(this).val())
        } 
        if ($(this).val() == "monitor_averias_gpon") {
            peticiones.redirectTabs($('#monitorAveriasGPONTab'))
            peticiones.ultimoUpdateMoAv("/administrador/monitor-averias/ultimo-update",$("#fecha_ultimo_maver_gpon"))
            resultMonitoreoAverias($("#resultMonitoreoAveriasGpon"),"","",$(this).val())
        }

    })
      
    //Maximizar

    $(".maxi_tab").click(function(){
       
        $("#tabsMonitorAveriasContent").toggleClass("fullscreen");
  
        if ($("#tabsMonitorAveriasContent").hasClass("fullscreen")) {
         // console.log("tiene la clase full ")
           $(".maxi_tab").html('<i class="icofont-close-line-squared-alt"></i>') 
        }else{
          //console.log("no tiene la clase full ")
          $(".maxi_tab").html('<i class="icofont-maximize"></i>')  
        } 
   
    })


})