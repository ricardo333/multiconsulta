import peticiones from './peticiones.js'


$(function(){

    //resultMasivaCms($('#resultMasivaCms'),"","")

    loadPrincipalSaturacionDown()

    $("#filtroBasicoMasivas").click(function(){
        let jefatura = $("#listaJefaturasMasivas").val()
        let estado = $("#listaEstadosMasivas").val()
        
        resultMasivaCms($('#resultMasivaCms'),jefatura,estado)
    })


    $("body").on("click",".return_masivaCms", function(){
        peticiones.redirectTabs($('#masivaCmsTab')) 
    })


    function loadPrincipalSaturacionDown() {

        if ($("#filtroCuadroMando").length) {
            let motivo = "cuadroMando"
            let nodo = $("#filtroCuadroMando").val()
            console.log("Este es el nodo enviado:"+nodo)
            resultMasivaCms($('#resultMasivaCms'),"","",motivo,nodo)
        } else {
            resultMasivaCms($('#resultMasivaCms'),"","","","")
        }
        
    }


    function resultMasivaCms(tabla,jefatura,estado,motivo,nodo)
    {
        console.log("la jefatura a enviar es: ",jefatura)
        console.log("el estado a enviar es: ",estado)
        console.log("la jefatura a enviar es: ",motivo)
        console.log("el estado a enviar es: ",nodo)

        let COLUMNS_MONITOR_AVERIAS = []
        let BUTTONS_MONITOR_AVERIAS = []

        //COLUMNS_MONITOR_AVERIAS = COLUMNS_MONITOR_AVERIAS_HFC
        
        COLUMNS_MONITOR_AVERIAS = peticiones.armandoColumnasHFC()
        BUTTONS_MONITOR_AVERIAS = BUTTONS_MONITOR_AVERIAS_HFC

        console.log(BUTTONS_MONITOR_AVERIAS)

       // let filtroJefatura = jefatura;

       let parametersDataMasivas = {
           'jefatura':jefatura,
           'estado':estado,
           'motivo':motivo,
           'nodo':nodo,
       }

       peticiones.cargaDataMasivaCms(COLUMNS_MONITOR_AVERIAS,COLUMNS_DEFS_MONITOR_AVERIAS,BUTTONS_MONITOR_AVERIAS,parametersDataMasivas,tabla)
 
    }


    //Maximizar

    $(".maxi_tab").click(function(){
       
        $("#tabsMasivaCmsContent").toggleClass("fullscreen");
  
        if ($("#tabsMasivaCmsContent").hasClass("fullscreen")) {
            //console.log("tiene la clase full ")
           $(".maxi_tab").html('<i class="icofont-close-line-squared-alt"></i>') 
        }else{
            //console.log("no tiene la clase full ")
          $(".maxi_tab").html('<i class="icofont-maximize"></i>')  
        } 
   
    })





})

