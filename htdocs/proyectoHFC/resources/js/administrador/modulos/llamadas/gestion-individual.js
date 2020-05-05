import peticiones from './peticiones.js'
import errors from  "@/globalResources/errors.js"

import gestionIndividual from  "@/globalResources/modulos/gestion-individual.js"

$(function(){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
 

    gestionIndividual.loadOptionsEstadoSelect($("#estadoStore").val() || "")

    $("body").on("click",".gestionarAveria", function(){
        
            peticiones.redirectTabs($('#gestionIndividualTab'))
            let nodo = $(this).data("uno")
            let troba = $(this).data("dos")
            let estado = $(this).data("seis")
            let numRequ  = $(this).data("cinco")

            let parametros = {
                'nodo':nodo,
                'troba':troba,
                'estado':estado,
                'numRequ':numRequ
            }
            
            console.log("Los parametros load son: ",parametros)
            //$('#caidasMasivasTab')
            gestionIndividual.loadRequires(`/administrador/llamadas/gestion/requires`,parametros)
         
    })

    //Estado Change
    $("body").on("change","#estadoStore", function() {
         
        let valorSelect = $(this).val()
 
        gestionIndividual.loadOptionsEstadoSelect(valorSelect)
 
    })

     
    //Store Gestion

    $("#registrarGestIndiv").click(function(){

        let valorSelect = $("#estadoStore").val() || " "

        let dataEnviar = {}

        let NUMREQ = $("#numRequerimiento").val() || ""

        dataEnviar.numRequerimiento = NUMREQ
        
        if(valorSelect.trim() == "Enviada:ATENTO para liquidar" || valorSelect.trim() == "Enviada:COT para liquidar"){
 
            let validaSegunEstado = gestionIndividual.validacionSegunEstadoGestion()
            if(!validaSegunEstado){ 
                return false
            } 

            dataEnviar.causa = $("#causaStore").val()
            dataEnviar.areaResponsable = $("#areaRespMasivaStore").val()

           // console.log("paso validacion de segun estado...")

           
            if(NUMREQ != ""){
                //console.log("Ingreso a validacion por num req")
                let validaSegunNumReq = gestionIndividual.validacionSegunNumReq()
                if(!validaSegunNumReq){ 
                    return false
                }

                dataEnviar.numRequerimiento = NUMREQ
                dataEnviar.codtecliq = $("#codigoTecLiqStore").val()
                dataEnviar.codliq = $("#codigoLiqStore").val()
                dataEnviar.detliq = $("#detLiqStore").val()
                dataEnviar.afectacion = $("#afectacionStore").val()
                dataEnviar.contrata = $("#contrataStore").val()
                dataEnviar.nombretecnico = $("#nombreTecStore").val()
 
            }else{
                dataEnviar.numRequerimiento = 0
            } 
 
        }

        dataEnviar.tecnico = $("#tecnicoStore").val()
        dataEnviar.estado = valorSelect
        dataEnviar.observaciones = $("#observacionesStore").val()
        dataEnviar.caidaAlcance = $("#caidaCompletaStore").val()
        dataEnviar.servicioAfectado = $("#servicioAfectadoStore").val()
        dataEnviar.remedy = $("#remedyStore").val()
        
        dataEnviar.nodo = $("#nodoGestionStoreIndv").val()
        dataEnviar.troba = $("#trobaGestionStoreIndv").val()
       
        console.log("Termino todo.. se enviaran estos datos: ",dataEnviar)

        
        $("#formularioContenedorGestionInd").addClass("d-none")
        $("#preloadGestionIndivisual").html(`<div id="carga_person">
                                            <div class="loader">Loading...</div>
                                            </div>`) 
        
        gestionIndividual.registroGestionTroba(`/administrador/llamadas/gestion-individual/store`,dataEnviar)
  
    })

    //Detalles

    $("body").on("click",".verDetalleGestion", function(){
        //console.log("Mostrar detalles..")
       

        let dataReq = $(this).data("uno");
        if(dataReq == "" || dataReq == null){ 
            $("#body-errors-modal").html(`<div class="text-danger">No se puede identificar el requerimiento, intente nuevamente.</div>`)
            $('#errorsModal').modal('show')
            return false 
        }

        let parametros = { 
            "codigoRequerimiento":dataReq
        }

        gestionIndividual.detalleGestion("/administrador/caidas/gestion-individual/detalle",parametros)
  
    })

})




