import peticiones from './peticiones.js'
import gestion from  "@/globalResources/modulos/gestion.js"
import valida from  "@/globalResources/forms/valida.js"

 
$(function(){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("#registrosGestiones").click(function(){
        //console.log("Mostrar el tab de gestion Masiva")
        
        let nodo = $("#nodoGestionStoreIndv").val() || ""
        let troba = $("#trobaGestionStoreIndv").val() || ""

        $("#nodoFilterHistoricoGestion").val(nodo)
        $("#trobaFilterHistoricoGestion").val(troba)

        let validacionFilterHistGes = valFilterHistoricoG()
        if(!validacionFilterHistGes){ 
            return false
        } 
        
        peticiones.redirectTabs($('#registrosGestionesTab')) 
        gestion.loadRegistrosGestiones(nodo,troba)
    })

    $("#filtroBasicoHistoricoGestion").click(function(){
        let nodo = $("#nodoFilterHistoricoGestion").val() || ""
        let troba = $("#trobaFilterHistoricoGestion").val() || ""

        let validacionFilterHistGes = valFilterHistoricoG()
        if(!validacionFilterHistGes){ 
            return false
        } 

        gestion.loadRegistrosGestiones(nodo,troba)
    })

    function valFilterHistoricoG()
    {
         let nodo = $("#nodoFilterHistoricoGestion")
        let troba = $("#trobaFilterHistoricoGestion")

        $("#filtroContentHistorialGestion .validateText").removeClass("valida-error-input") 
        $("#errors_filter_historico_gestion").html(``)
        
        if (nodo.val() != "") {
            if(!valida.isValidAlfaNumerico(nodo.val())){
                valida.isValidateInputText(nodo)
                $("#errors_filter_historico_gestion").html(`El campo nodo no tiene el formato correcto.`)
                return false
            }  
           
        }
        if (troba.val() != "") {
            if(!valida.isValidAlfaNumerico(troba.val())){
                valida.isValidateInputText(troba)
                $("#errors_filter_historico_gestion").html(`El campo troba no tiene el formato correcto.`)
                return false
              } 
        }

        if (nodo.val() == "" && troba.val() == "") {
            valida.isValidateInputText(nodo)
            valida.isValidateInputText(troba)
            $("#errors_filter_historico_gestion").html(`El campo nodo y troba no pueden estar vacios.`)
            return false
        }
       

        $("#filtroContentHistorialGestion .validateText").removeClass("valida-error-input") 
        $("#errors_filter_historico_gestion").html(``) 
      
      
        return true

    }
    
})