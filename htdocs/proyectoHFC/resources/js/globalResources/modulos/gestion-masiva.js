import valida from  "@/globalResources/forms/valida.js"
import errors from  "@/globalResources/errors.js"
import limpia from  "@/globalResources/forms/limpia.js"

$(function(){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    $('[name="SearchDualList1"]').keyup(function (e) {
        var code = e.keyCode || e.which;
        if (code == '9') return;
        if (code == '27') $(this).val(null);
        var $rows = $(this).closest('.dual-list').find('#trobasStore1 option');
        var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
        $rows.show().filter(function () {
            var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
            return !~text.indexOf(val);
        }).hide();
    });


    $('[name="SearchDualList2"]').keyup(function (e) {
        var code = e.keyCode || e.which;
        if (code == '9') return;
        if (code == '27') $(this).val(null);
        var $rows = $(this).closest('.dual-list').find('#trobasStore2 option');
        var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
        $rows.show().filter(function () {
            var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
            return !~text.indexOf(val);
        }).hide();
    });


    $("#btnLeftTrobas").click(function(){
        let datos1 = document.getElementById("trobasStore1");
        let datos2 = document.getElementById("trobasStore2");
        let collection = datos2.selectedOptions;
        let cantidad = collection.length;

        for (let i = 0; i < cantidad; i++) {
            let valor = collection[i].text;
            let option = document.createElement('option');
            option.text = collection[i].text;
            datos1.add(option);
        }
            
        $.each($('[name="duallistbox_demo2"] option:selected'), function( index, value ) {
            $(this).remove();
        });
    });


    $("#btnRightTrobas").click(function(){
        let datos1 = document.getElementById("trobasStore1");
        let datos2 = document.getElementById("trobasStore2");
        let collection = datos1.selectedOptions;
        let cantidad = collection.length;

        for (let i = 0; i < cantidad; i++) {
            let valor = collection[i].text;
            let option = document.createElement('option');
            option.text = collection[i].text;
            datos2.add(option);
        }
            
        $.each($('[name="duallistbox_demo1"] option:selected'), function( index, value ) {
            $(this).remove();
        });
    });





 

    loadOptionsEstadoMasivoSelect($("#estadoStore").val() || "")

    //Estado Change
    $("body").on("change","#estadoStore", function() {
         
        let valorSelect = $(this).val()
 
        loadOptionsEstadoMasivoSelect(valorSelect)
 
    })

    $("#return_history").click(function(){
        console.log("retorna historial-...")
        window.history.back();
    })

    $("#registrarGestMasiva").click(function(){
        let valorSelect = $("#estadoStore").val() || " "

        let dataEnviar = {}

        if(valorSelect.trim() == "Enviada:ATENTO para liquidar" || valorSelect.trim() == "Enviada:COT para liquidar"){

            let validaSegunEstado = validacionSegunEstadoGestion()
            if(!validaSegunEstado){ 
                return false
            } 

            dataEnviar.causa = $("#causaStore").val()
            dataEnviar.areaResponsable = $("#areaRespMasivaStore").val()

            let validaSegunNumReq = validacionCamposOtrosMasiva()
            if(!validaSegunNumReq){ 
                return false
            }

            dataEnviar.codtecliq = $("#codigoTecLiqStore").val()
            dataEnviar.codliq = $("#codigoLiqStore").val()
            dataEnviar.detliq = $("#detLiqStore").val()
            dataEnviar.afectacion = $("#afectacionStore").val()
            dataEnviar.contrata = $("#contrataStore").val()
            dataEnviar.nombretecnico = $("#nombreTecStore").val()

        }

        dataEnviar.tecnico = $("#tecnicoStore").val()
        dataEnviar.estado = valorSelect
        dataEnviar.observaciones = $("#observacionesStore").val()
        dataEnviar.caidaAlcance = $("#caidaCompletaStore").val()
        dataEnviar.servicioAfectado = $("#servicioAfectadoStore").val()
        dataEnviar.remedy = $("#remedyStore").val()

        //-----Obtener Valores de Trobas Seleccionadas-----//
        var cantidad = document.getElementById("trobasStore2").options.length;
        var valores = [];
        for (let i = 0; i < cantidad; i++) {
            var selectValue = document.getElementById("trobasStore2").options[i].value;
            valores[i] = selectValue;
        }
        
        //dataEnviar.trobas = $("#trobasStore1").val() 
        dataEnviar.trobas = valores;

        console.log("Termino todo.. se enviaran estos datos: ",dataEnviar)

        $("#formularioContenedorGestionMs").addClass("d-none");
        $("#preloadGestionMasiva").html(`<div id="carga_person">
                                        <div class="loader">Loading...</div>
                                    </div>`) 


        $.ajax({
            url:`/administrador/gestion/masiva-store`,
            method:"post",
            data:dataEnviar,
            dataType: "json", 
        })
        .done(function(data){
            console.log("La data del registro gestion es :",data)

            $("#formularioContenedorGestionMs").removeClass("d-none");
            $("#preloadGestionMasiva").html(``) 

            limpia.limpiaFormGestionMasiva()
            limpia.limpiaHtml($("#observacionesStore"))

            $("#successModal").modal("show")
            $("#body-success-modal").html(`<div class="w-100 col-12 text-center text-success">
                                                ${data.mensaje}
                                            </div>`)
        })
        .fail(function(jqXHR, textStatus){
              //console.log( "Error: " ,jqXHR, textStatus);
              // console.log( "Request failed: " ,jqXHR.responseJSON.mensaje);
             //$("#errors_store").html(jqXHR.responseText)

             $("#formularioContenedorGestionMs").removeClass("d-none");
             $("#preloadGestionMasiva").html(``) 

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
 
    function loadOptionsEstadoMasivoSelect(valor)
    { 
        console.log("El bvalor es: ", valor)
        if(valor == "Enviada:ATENTO para liquidar" || valor == "Enviada:COT para liquidar"){
            $(".display_options_by_estado").removeClass("d-none")
            $(".display_options_by_liq_masiva").removeClass("d-none") 
			  
		}else{
			$(".display_options_by_estado").addClass("d-none")
			$(".display_options_by_liq_masiva").addClass("d-none")
			 
		}

    }

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

    function validacionCamposOtrosMasiva()
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

})