import valida from  "@/globalResources/forms/valida.js"
import peticiones from './peticiones.js'
import errors from  "@/globalResources/errors.js"
import limpia from  "@/globalResources/forms/limpia.js"

$(function(){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });



    $("body").on("click",".gestionarAveria", function(){

        peticiones.redirectTabs($('#gestionIndividualProblemaSenalTab')) 
        //console.log("se deberia redireccionar...")
        $("#formularioContenedorGestionInd").addClass("d-none");
        $("#preloadGestionIndivisual").html(`<div id="carga_person">
                                        <div class="loader">Loading...</div>
                                    </div>`) 


        let nodo = $(this).data("uno")
        let troba = $(this).data("dos")
        let estado = $(this).data("seis")
        let numRequ  = ""

        $.ajax({
            url:`/administrador/problema-senal/gestion/requires`,
            method:"get",
            data: {
                numRequ,
                nodo,
                troba,
                estado
            },
            dataType: "json", 
        })
        .done(function(data){

                $("#preloadGestionIndivisual").html('')
                $("#formularioContenedorGestionInd").removeClass("d-none");

                if(data.error){
                    peticiones.redirectTabs($('#problemaSenalTab')) 
                    $("#body-errors-modal").html(`<div class="text-center text-danger">Hubo un error en la petición, intente nuevamente<div>`)
                    $('#errorsModal').modal('show')   
                    return false
                }

                //Detalle parametros Necesarios

                $("#numRequerimiento").val(data.response.detalleParams.numRequ)
                $("#nodoGestionStoreIndv").val(data.response.detalleParams.nodo)
                $("#trobaGestionStoreIndv").val(data.response.detalleParams.troba)

                let tecnicos = data.response.tecnicos
                let estados = data.response.estados
                let causas = data.response.causas
                let areasResponsables = data.response.areasR
                
                let estructuraTecnicos = `<option class="gestion">Gestion</option>`
                let estructuraEstados = `<option class="seleccionar">Seleccionar</option>`
                let estructuraCausas = `<option class="seleccionar">Seleccionar</option>`
                let estructuraAreasResponsables = `<option class="seleccionar">Seleccionar</option>`

                tecnicos.forEach(el => {
                    estructuraTecnicos += `<option value="${el.nombre1}">${el.nombre1}</option>`
                })
                estados.forEach(el => {
                    estructuraEstados += `<option value="${el.estado}" ${el.estado == data.response.detalleParams.estado? "selected" : ""}>${el.estado}</option>`
                })
                causas.forEach(el => {
                    estructuraCausas += `<option value="${el.idcausa}">${el.causa}</option>`
                })
                areasResponsables.forEach(el => {
                    estructuraAreasResponsables += `<option value="${el.idarea}">${el.area}</option>`
                })

                $("#tecnicoStore").html(estructuraTecnicos);
                $("#estadoStore").html(estructuraEstados);
                $("#causaStore").html(estructuraCausas);
                $("#areaRespMasivaStore").html(estructuraAreasResponsables);

        })
        .fail(function(jqXHR, textStatus){

            $("#preloadGestionIndivisual").html('')
            $("#formularioContenedorGestionInd").removeClass("d-none");

                //console.log( "Error: " ,jqXHR, textStatus);
                //console.log( "Request failed: " ,jqXHR.responseJSON.mensaje);
                $("#errors_store").html(jqXHR.responseText)

                let erroresPeticion =""
                if(jqXHR.responseJSON){
                    if(jqXHR.responseJSON.mensaje){
                        let erroresMensaje = jqXHR.responseJSON.mensaje  //captura objeto
                        let mensaje = errors.mensajeErrorJson(erroresMensaje)
                        erroresPeticion += mensaje 
                    } 
                }
                if(jqXHR.status){
                    let mensaje = errors.codigos(jqXHR.status)
                    erroresPeticion += "<br> "+mensaje
                }
                erroresPeticion = (erroresPeticion.trim() == 0) ? "hubo un error en el servicio, intente nuevamente." : erroresPeticion

                $("#body-errors-modal").html(erroresPeticion)
                $('#errorsModal').modal('show')
                return false

        })
    })


    //Estado Change
    $("body").on("change","#estadoStore", function() {
         
        let valorSelect = $(this).val()
 
        loadOptionsEstadoSelect(valorSelect)
 
    })


    function loadOptionsEstadoSelect(valor)
    { 
        if(valor == "Enviada:ATENTO para liquidar" || valor == "Enviada:COT para liquidar"){
            $(".display_options_by_estado").removeClass("d-none")
            let NUMREQ = $("#numRequerimiento").val() || ""
			 if(NUMREQ  != ""){ 
				$(".display_options_by_liq_masiva").removeClass("d-none")
            } 
			  
		}else{
			$(".display_options_by_estado").addClass("d-none")
			$(".display_options_by_liq_masiva").addClass("d-none")
			 
		}

    }


    //Store Gestion

    $("#registrarGestIndiv").click(function(){

        let valorSelect = $("#estadoStore").val() || " "

        let dataEnviar = {}

        let NUMREQ = $("#numRequerimiento").val() || ""

        dataEnviar.numRequerimiento = NUMREQ
        
        if(valorSelect.trim() == "Enviada:ATENTO para liquidar" || valorSelect.trim() == "Enviada:COT para liquidar"){
 
            let validaSegunEstado = validacionSegunEstadoGestion()
            if(!validaSegunEstado){ 
                return false
            } 

            dataEnviar.causa = $("#causaStore").val()
            dataEnviar.areaResponsable = $("#areaRespMasivaStore").val()

            console.log("paso validacion de segun estado...")

           
            if(NUMREQ != ""){
                console.log("Ingreso a validacion por num req")
                let validaSegunNumReq = validacionSegunNumReq()
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

        $.ajax({
            url:`/administrador/problema-senal/gestion-individual/store`,
            method:"post",
            data:dataEnviar,
            dataType: "json", 
        })
        .done(function(data){

            $("#formularioContenedorGestionInd").removeClass("d-none")
            $("#preloadGestionIndivisual").html(``)

            limpia.limpiaFormGestionIndividual()
            limpia.limpiaHtml($("#observacionesStore"))

            console.log("La data retornada del registro gestion es :",data)

            $("#successModal").modal("show")
            $("#body-success-modal").html(`<div class="w-100 col-12 text-center text-success">
                                                ${data.mensaje}
                                            </div>`)

            let filtroMonitorHfcGpon = $("#display_filter_special").val()
            let COLUMNS_MONITOR_AVERIAS = []
            let BUTTONS_MONITOR_AVERIAS = []
            let tabla = ''

            let parametersDataAverias = {
                'filtroHfcGpon':filtroMonitorHfcGpon,
            }
            

            //if (filtroMonitorHfcGpon == "monitor_averias_hfc") {
                peticiones.redirectTabs($('#problemaSenalTab')) 
                //peticiones.ultimoUpdateMoAv("/administrador/problema-senal/ultimo-update",$("#fecha_ultimo_maver_hfc"))
                COLUMNS_MONITOR_AVERIAS = COLUMNS_MONITOR_AVERIAS_HFC  
                BUTTONS_MONITOR_AVERIAS = BUTTONS_MONITOR_AVERIAS_HFC 
                parametersDataAverias.jefatura = $("#listaJefaturasProblemas").val() 
                parametersDataAverias.estado = $("#listaEstadosProblemas").val() 
                tabla = $('#resultProblemaSenal')
            //} 

            if (tabla != "") {
                peticiones.cargaDataProblemaSenal(COLUMNS_MONITOR_AVERIAS,COLUMNS_DEFS_MONITOR_AVERIAS,BUTTONS_MONITOR_AVERIAS,parametersDataAverias,tabla)
            }
            


        })
        .fail(function(jqXHR, textStatus){
      
            $("#formularioContenedorGestionInd").removeClass("d-none")
            $("#preloadGestionIndivisual").html(``)
      
              //console.log( "Error: " ,jqXHR, textStatus);
              //console.log( "Request failed: " ,jqXHR.responseJSON.mensaje);
               // $("#errors_store").html(jqXHR.responseText)
               // return false
      
              let erroresPeticion =""
              if(jqXHR.responseJSON){
                  if(jqXHR.responseJSON.mensaje){
                      let erroresMensaje = jqXHR.responseJSON.mensaje  //captura objeto
                      let mensaje = errors.mensajeErrorJson(erroresMensaje)
                      erroresPeticion += mensaje 
                  } 
              }
              if(jqXHR.status){
                  let mensaje = errors.codigos(jqXHR.status)
                  erroresPeticion += "<br> "+ mensaje
              }
              erroresPeticion = (erroresPeticion.trim() == 0) ? "hubo un error en el servicio, intente nuevamente." : erroresPeticion

              $("#errorsModal").modal("show")
              $("#body-errors-modal").html(`<div class="w-100 col-12 text-center text-danger">
                                                  ${erroresPeticion}
                                              </div>`) 
       
              return false
       
          }) 

        
    })


    //Detalles

    $("body").on("click",".verDetalleGestion", function(){
        console.log("Mostrar detalles..")
       

        let dataReq = $(this).data("uno");
        if(dataReq == "" || dataReq == null){
            alert("para ver el detalle se requiere de su identificador.")
            return false
        }
 
        $("#detalleGestion").modal("show")
        $("#content_masiva_detalle").html(`<div id="carga_person">
                                            <div class="loader">Loading...</div>
                                        </div>`);

        $.ajax({
            url:`/administrador/problema-senal/gestion-individual/detalle`,
            method:"get", 
            data: { 
                "codigoRequerimiento":dataReq
                },
            dataType:"json",
            })
        .done(function(data) {
           //console.log("la respuesta de lista es: ",data)

           if (data.response.data.length == 0) {
            $("#content_masiva_detalle").html(`<div class="w-100 text-center justify-content-center text-secondary">No se encontraron detalles de la masiva, verificar que la masiva exista recargando nuevamente  la web.</div>`)
            return false
           }
            
            $("#content_masiva_detalle").html(`
                        <div class="form-row row mb-2 col-12">
                                <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">Cod. Tec. Liq.:</label>
                            <div class="col-sm-8">
                                <input type="email" class="form-control form-control-sm" id="colFormLabelSm" value="${data.response.data[0].codtecliq}" readonly>
                            </div>
                        </div>
                        <div class="form-row row mb-2 col-12">
                                <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">Cod. Liq.:</label>
                            <div class="col-sm-8">
                                <input type="email" class="form-control form-control-sm" id="colFormLabelSm" value="${data.response.data[0].codliq}" readonly>
                            </div>
                        </div>
                        <div class="form-row row mb-2 col-12">
                                <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">Det. liq.:</label>
                            <div class="col-sm-8">
                                <input type="email" class="form-control form-control-sm" id="colFormLabelSm" value="${data.response.data[0].detliq}" readonly>
                            </div>
                        </div>
                        <div class="form-row row mb-2 col-12">
                                <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">Cod. Tec. Liq.</label>
                            <div class="col-sm-8">
                                <textarea class="form-control form-control-sm" readonly>${data.response.data[0].observacion}</textarea>
                            </div>
                        </div>
                        <div class="form-row row mb-2 col-12">
                                <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">Afectación:</label>
                            <div class="col-sm-8">
                                <input type="email" class="form-control form-control-sm" id="colFormLabelSm" value="${data.response.data[0].afectacion}" readonly>
                            </div>
                        </div>
                        <div class="form-row row mb-2 col-12">
                                <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">Contrata:</label>
                            <div class="col-sm-8">
                                <input type="email" class="form-control form-control-sm" id="colFormLabelSm" value="${data.response.data[0].contrata}" readonly>
                            </div>
                        </div>
                        <div class="form-row row mb-2 col-12">
                                <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">Nombre técnico:</label>
                            <div class="col-sm-8">
                                <input type="email" class="form-control form-control-sm" id="colFormLabelSm" value="${data.response.data[0].nombretecnico}" readonly>
                            </div>
                        </div>
                        <div class="form-row row mb-2 col-12">
                                <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">Fecha:</label>
                            <div class="col-sm-8">
                                <input type="email" class="form-control form-control-sm" id="colFormLabelSm" value="${data.response.data[0].fechahora}" readonly>
                            </div>
                        </div>
                `)

                return false
    
                
        })
        .fail(function( jqXHR, textStatus ) {
            //console.log( "Request failed: ",jqXHR, textStatus);
            let erroresPeticion =""
            if(jqXHR.responseJSON){
                if(jqXHR.responseJSON.mensaje){
                    let erroresMensaje = jqXHR.responseJSON.mensaje  //captura objeto
                    let mensaje = errors.mensajeErrorJson(erroresMensaje)
                    erroresPeticion += mensaje 
                } 
            }
            if(jqXHR.status){
                let mensaje = errors.codigos(jqXHR.status)
                erroresPeticion += "<br> "+mensaje
            }
            erroresPeticion = (erroresPeticion.trim() == 0) ? "hubo un error en el servicio, intente nuevamente." : erroresPeticion

            $("#content_masiva_detalle").html(`<div class="w-100 text-center justify-content-center text-danger">${erroresPeticion}</div>`)
            return false
        });

    })



})


function validacionSegunEstadoGestion()
{ 
    let valorSelectCausa = $("#causaStore")
    let valorSelectArea = $("#areaRespMasivaStore")
    
    $(".validateText").removeClass("valida-error-input")
    $(".validateSelect").removeClass("valida-error-input")
    $("#errors_store").html(``)

    if(!valida.isValidText(valorSelectCausa.val())){
        valida.isValidateInputText(valorSelectCausa)
        $("#errors_store").html(`Se requiere la selección del campo Causa.`)
        return false
    }  
    if(valorSelectCausa.val().toLowerCase() == "seleccionar"){
        valida.isValidateInputText(valorSelectCausa)
        $("#errors_store").html(`Seleccione una Causa válida`)
        return false
    }
    if(valorSelectCausa.val().toLowerCase() == "seleccionar"){
        valida.isValidateInputText(valorSelectCausa)
        $("#errors_store").html(`Seleccione una Causa válida`)
        return false
    }
    if(!valida.isValidText(valorSelectArea.val())){
        valida.isValidateInputText(valorSelectArea)
        $("#errors_store").html(`Se requiere la selección del campo Area.`)
        return false
    }  
    if(valorSelectArea.val().toLowerCase() == "seleccionar"){
        valida.isValidateInputText(valorSelectArea)
        $("#errors_store").html(`Seleccione un Área válido`)
        return false
    }
    if(valorSelectArea.val().toLowerCase() == "seleccionar"){
        valida.isValidateInputText(valorSelectArea)
        $("#errors_store").html(`Seleccione un Área válido`)
        return false
    }
    
    $(".validateText").removeClass("valida-error-input")
    $(".validateSelect").removeClass("valida-error-input")
    $("#errors_store").html(``)

    return true
 
}


function validacionSegunNumReq()
{
    let codtecliq = $("#codigoTecLiqStore")
    let codliq = $("#codigoLiqStore")
    let detliq = $("#detLiqStore")
    let afectacion = $("#afectacionStore")
    let contrata = $("#contrataStore")
    let remedy = $("#remedyStore")
    let nombreTecnico = $("#nombreTecStore")

    $(".validateText").removeClass("valida-error-input")
    $(".validateSelect").removeClass("valida-error-input")
    $("#errors_store").html(``)

    if(!valida.isValidText(codtecliq.val())){
        valida.isValidateInputText(codtecliq)
        $("#errors_store").html(`El campo Cod. Tec. liq. es requerido`)
        return false
    } 
    if(!valida.isValidAlfaNumerico(codtecliq.val())){
        valida.isValidateInputText(codtecliq)
        $("#errors_store").html(`El campo Cod. Tec. Liq. no tiene el formato alfanumérico correcto.`)
        return false
    } 
    if(codtecliq.val().length > 6){
        valida.isValidateInputText(codtecliq)
        $("#errors_store").html(`La longitud del campo Cod. Tec. Liq. no deben superar los 6 digitos`)
        return false;
    } 
    
    if(!valida.isValidText(codliq.val())){
        valida.isValidateInputText(codliq)
        $("#errors_store").html(`Se requiere del campo Cod. Liq..`)
        return false
    } 
    if(!valida.isValidAlfaNumerico(codliq.val())){
        valida.isValidateInputText(codliq)
        $("#errors_store").html(`El campo Cod. Liq. no tiene el formato alfanumérico correcto.`)
        return false
    } 
    if(codliq.val().length > 2){
        valida.isValidateInputText(codliq)
        $("#errors_store").html(`La longitud del campo Cod. Liq. no deben superar los 2 digitos`)
        return false;
    } 

    if(!valida.isValidText(detliq.val())){
        valida.isValidateInputText(detliq)
        $("#errors_store").html(`Se requiere del campo Det. Liq...`)
        return false
    } 
    if(!valida.isValidAlfaNumerico(detliq.val())){
        valida.isValidateInputText(detliq)
        $("#errors_store").html(`El campo Det. Liq. no tiene el formato alfanumérico correcto.`)
        return false
    } 
    if(detliq.val().length > 2){
        valida.isValidateInputText(detliq)
        $("#errors_store").html(`La longitud del campo Det. Liq no deben superar los 2 digitos`)
        return false;
    } 

    if(!valida.isValidText(afectacion.val())){
        valida.isValidateInputText(afectacion)
        $("#errors_store").html(`Se requiere del campo Afectación.`)
        return false
    }
    if(!valida.isValidText(contrata.val())){
        valida.isValidateInputText(contrata)
        $("#errors_store").html(`Se requiere del campo Contrata.`)
        return false
    }
    if(contrata.val().toLowerCase() == "seleccionar"){
        valida.isValidateInputText(contrata)
        $("#errors_store").html(`Seleccione una Contrata válida`)
        return false
    }
    if(contrata.val().toLowerCase() == "seleccionar"){
        valida.isValidateInputText(contrata)
        $("#errors_store").html(`Seleccione una Contrata válida`)
        return false
    }
    if(!valida.isValidText(nombreTecnico.val())){
        valida.isValidateInputText(nombreTecnico)
        $("#errors_store").html(`Se requiere del campo Nombre Técnico.`)
        return false
    }
    if(!valida.isValidLetters(nombreTecnico.val())){
        valida.isValidateInputText(nombreTecnico)
        $("#errors_store").html(`El campo Nombre Técnico no tiene un formato correcto.`)
        return false
    }

    $(".validateText").removeClass("valida-error-input")
    $(".validateSelect").removeClass("valida-error-input")
    $("#errors_store").html(``)
  
    return true

}
