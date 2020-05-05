import peticiones from './peticiones.js'
import errors from  "@/globalResources/errors.js"
import valida from  "@/globalResources/forms/valida.js"
import limpia from  "@/globalResources/forms/limpia.js"

$(function(){
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('[name="SearchDualList1"]').keyup(function (e) {
        var code = e.keyCode || e.which; 
      
        if (code == 13) {
            $(this).prop("disabled",true)

            let palabraBusca = $(this).val() 
            if (palabraBusca.trim() != "") {
               
                $("#nodoPlanoLista").html(``) 
               
                DATA_NODO_PLANOS.forEach(el => { 
                    if (el.toLowerCase().indexOf(palabraBusca.toLowerCase()) != -1) {
                        $("#nodoPlanoLista").append(`<option value="${el}">${el}</option>`)
                    } 
                })  

            }
            $(this).prop("disabled",false)
        } 

        if ($(this).val() == "" && code != 13) {
            $(this).prop("disabled",true) 
            //$(this).prop("disabled",true)
           // document.getElementById().disabled = true
            $("#nodoPlanoLista").html(``) 
            DATA_NODO_PLANOS.forEach(el => { 
                $("#nodoPlanoLista").append(`<option value="${el}">${el}</option>`) 
            }) 
            $(this).prop("disabled",false)
        }

        $(this).focus()
        
    });


    $('[name="SearchDualList2"]').keyup(function (e) {
        var code = e.keyCode || e.which;
       
        if (code == '9') return;
        if (code == '27') $(this).val(null);
        var $rows = $(this).closest('.dual-list').find('#nodoPlanoStore option');
        var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
        $rows.show().filter(function () {
            var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
            return !~text.indexOf(val);
        }).hide();
    });


    $("#btnLeftTrobas").click(function(){
        let datos1 = document.getElementById("nodoPlanoLista");
        let datos2 = document.getElementById("nodoPlanoStore");
        let collection = datos2.selectedOptions;
        let cantidad = collection.length;

        for (let i = 0; i < cantidad; i++) {
            let valor = collection[i].text;
            let option = document.createElement('option');
            option.text = collection[i].text;
            datos1.add(option);
        }
            
        $.each($('[name="duallistbox_demo2"] option:selected'), function( index, value ) { 
            DATA_NODO_PLANOS.push(value.value)
            $(this).remove();
        }); 
    });
 
    $("#btnRightTrobas").click(function(){
        let datos1 = document.getElementById("nodoPlanoLista");
        let datos2 = document.getElementById("nodoPlanoStore");
        let collection = datos1.selectedOptions;
        let cantidad = collection.length;

        for (let i = 0; i < cantidad; i++) {
            let valor = collection[i].text;
            let option = document.createElement('option');
            option.text = collection[i].text;
            datos2.add(option);
        }
            
        $.each($('[name="duallistbox_demo1"] option:selected'), function( index, value ) { 
            let nuevoArray = DATA_NODO_PLANOS.filter(palabra => {  
                    if (palabra.toLowerCase() != value.value.toLowerCase() ) {
                        return palabra
                    }
                });
            DATA_NODO_PLANOS = nuevoArray

            $(this).remove();
        });
    });

    $("#redirectRegisterTP").click(function(){
        peticiones.redirectTabs($("#RegistroTPTab"))
    })

    $("#tipoTrabajoStore").change(function(){
        let seleccion = $(this).val();

        $("#supervisorTdpContentStore").html(`Cargando Data <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        <span class="sr-only">Descargando..</span>`)
        $("#fechaInicioContentStore").html(`Cargando Data <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    <span class="sr-only">Descargando..</span>`)
        FECHA_INICIO = ""

        $.ajax({
            url: `/administrador/trabajos-programados/tipo-trabajo/${seleccion}/detalles`,
            method: "get",
            dataType:"json"
          })
        .done(function(data) {
             //console.log("la respuesta de fechas es: ",data)

             let respuesta = data.response
             if (respuesta.supervisorTDP.length == 0) { 
                    $("#fechaInicioContentStore").html(`<span class="text-secondary font-italic text-sm">Intente seleccionar el Tipo de Trabajo nuevamente.</span>`)
                    $("#supervisorTdpContentStore").html(`<span class="text-secondary font-italic text-sm">Intente seleccionar el Tipo de Trabajo nuevamente.</span>`)

                    $("#body-errors-modal").html(`<div class="w-100 text-center text-danger">
                                                        No se encontraron datos del supervisor según el tipo de trabajo seleccionado.
                                                    </div>`) 
                    $('#errorsModal').modal('show')
                    return false
             }
             
            let options = `<select name="supervisorTdpStore" id="supervisorTdpStore" class="form-control form-control-sm shadow-sm validateSelect">
                        <option value="seleccionar">Seleccionar</option>`
            let lista = respuesta.supervisorTDP 
            lista.forEach(el => {
                options += `<option value="${el.id}">${el.supervisor}</option>` 
            });
            options += `</select>`
            
            $("#supervisorTdpContentStore").html(options)

            let fecha_inicio = `<input id="fechaInicioStore" type="date" value="${respuesta.fechaInicio}" min="${respuesta.fechaInicio}" step="1" class="form-control form-control-sm shadow-sm validateText">`
            $("#fechaInicioContentStore").html(fecha_inicio)
            FECHA_INICIO = respuesta.fechaInicio
            
        })
        .fail(function( jqXHR, textStatus ) {
            //console.log( "Request failed: " , jqXHR, textStatus );
            //$("#body-errors-modal").html(jqXHR.responseText)
            //$("#body-errors-modal").html(erroresPeticion)
           //return false

           $("#fechaInicioContentStore").html(`<span class="text-secondary font-italic text-sm">Intente seleccionar el Tipo de Trabajo.</span>`)
           $("#supervisorTdpContentStore").html(`<span class="text-secondary font-italic text-sm">Intente seleccionar el Tipo de Trabajo.</span>`)
           FECHA_INICIO = ""

            let erroresPeticion =""
            if(jqXHR.status){
                let mensaje = errors.codigos(jqXHR.status)
                erroresPeticion += `<strong class="text-danger"> ${mensaje} </strong>` 
            }
            if(jqXHR.responseJSON){
                if(jqXHR.responseJSON.mensaje){
                    let erroresMensaje = jqXHR.responseJSON.mensaje  //captura objeto
                    let mensaje = errors.mensajeErrorJson(erroresMensaje)
                    erroresPeticion += "<br>"+ mensaje 
                } 
            }
            
            erroresPeticion = (erroresPeticion.trim() == 0) ? "hubo un error en el servicio, intente nuevamente." : erroresPeticion
    
            $("#body-errors-modal").html(erroresPeticion)
            $('#errorsModal').modal('show')
            return false

        });

    })

    $("#registrarTrabProg").click(function(){
   
        let validacionConitnueStore = validacionContinueStore()
        if(!validacionConitnueStore){ 
            return false
        } 

        //let nodoPlano = $("#nodoPlanoStore").val() 
        var cantidadPlano = document.getElementById("nodoPlanoStore").options.length;
        var valores = [];
        for (let i = 0; i < cantidadPlano; i++) {
            var selectValue = document.getElementById("nodoPlanoStore").options[i].value;
            valores[i] = selectValue;
        }
         
        let  nodoPlano = valores

        let amplificador = $("#amplificadorStore").val()
        let tipoTrabajo = $("#tipoTrabajoStore").val()
        let tipoTrabajoText = $("#tipoTrabajoStore option:selected").text();
        let remedy = $("#remeryStore").val()
        let supervisor = $("#supervisorTdpStore").val()
        let supervisorText =  $("#supervisorTdpStore option:selected").text();
        let fechaMinima =  FECHA_INICIO
        let fechaInicio = $("#fechaInicioStore").val()
        let celularSupervisorTDP = $("#celularSupTdpStore").val()
        let afectacion = $("#afectacionStore").val()
        let HoraInicio = $("#hInicioStore").val()
        let HoraTermino = $("#hTerminoStore").val()
        let corteServicio = $('input[name=CORTESN]:checked').val()
        let observacion = $('#observacionStore').val()

       
        $("#preprocesoRegistroTP").html(`<div id="carga_person">
                                            <div class="loader">Loading...</div>
                                        </div>`)
        $("#contentRegistroTP").addClass("d-none")

        $.ajax({
            url:`/administrador/trabajos-programados/store`,
            method:"post",
            data:{
                nodoPlano,
                amplificador,
                tipoTrabajo,
                tipoTrabajoText,
                remedy,
                supervisor,
                supervisorText,
                fechaMinima,
                fechaInicio,
                celularSupervisorTDP,
                afectacion,
                HoraInicio,
                HoraTermino,
                corteServicio,
                observacion
            },
            dataType: "json", 
        })
        .done(function(data){
            
            //Regresa lista de nodo y planos al inicio 
            //Limpiamos y redireccionamos el inicio 
            resetFormularioTrabajosProgramados()
            $("#preprocesoRegistroTP").html(``)
            $("#contentRegistroTP").removeClass("d-none")

            $("#body-success-modal").html(`<div class="w-100 text-center text-success">Se registro correctamente el trabajo programado.</div>`)
            $("#successModal").modal("show")

            let valorFiltroZona = $("#listaZonasFiltro").val()
            let parametros = {'zona':valorFiltroZona}
            let columnas = peticiones.armandoColumnasTP()
            peticiones.loadTrabajosProgramadosList(columnas,parametros)

            peticiones.redirectTabs($("#listaTrabajoPTab"))
                  

        })
        .fail(function(jqXHR, textStatus){
      
            
            $("#preprocesoRegistroTP").html(``)
            $("#contentRegistroTP").removeClass("d-none")
      
            //console.log( "Error: " ,jqXHR, textStatus);
              // console.log( "Request failed: " ,jqXHR.responseJSON.mensaje);
            //$("#preprocesoRegistroTP").html(jqXHR.responseText)
            //return false

             let erroresPeticion =""
             if(jqXHR.status){
                 let mensaje = errors.codigos(jqXHR.status)
                 erroresPeticion += `<strong class="text-danger"> ${mensaje} </strong>` 
             }
             if(jqXHR.responseJSON){
                 if(jqXHR.responseJSON.mensaje){
                     let erroresMensaje = jqXHR.responseJSON.mensaje  //captura objeto
                     let mensaje = errors.mensajeErrorJson(erroresMensaje)
                     erroresPeticion += "<br>"+ mensaje 
                 } 
             }
             
             erroresPeticion = (erroresPeticion.trim() == 0) ? "hubo un error en el servicio, intente nuevamente." : erroresPeticion
     
             $("#body-errors-modal").html(erroresPeticion)
             $('#errorsModal').modal('show')
             return false
       
          }) 

        

    })

    function validacionContinueStore()
    {
 
        let nodo_plano = document.getElementById("nodoPlanoStore");
        let amplificador = $("#amplificadorStore")
        let tipo_Trabajo_select = $("#tipoTrabajoStore")
       
        let remedy = $("#remeryStore")
        let supervisor = $("#supervisorTdpStore") || ""
        
        let fecha_inicio = $("#fechaInicioStore") || ""
        let celsuptdp = $("#celularSupTdpStore")
        let afectacion = $("#afectacionStore")
        let h_inicio = $("#hInicioStore")
        let h_termino = $("#hTerminoStore")
        let corte_sev = $('input[name=CORTESN]:checked')
        let observacion = $('#observacionStore')
        
        $(".validateText").removeClass("valida-error-input")
        $(".validateSelect").removeClass("valida-error-input")
        $("#errorStoreTP").html(``)

        if(nodo_plano.options.length == 0){
            //console.log("el input nodo plano es: ",nodo_plano)
            nodo_plano.classList.add("valida-error-input")
            //valida.isValidateInputText(nodo_plano)
            $("#errorStoreTP").html(`El campo Nodo - Plano es requerido`)
            return false 
        }
        if(!valida.isValidText(amplificador.val())){
            valida.isValidateInputText(amplificador)
            $("#errorStoreTP").html(`El campo Amplificador es requerido`)
            return false
        } 
        if(!valida.isValidNumber(tipo_Trabajo_select.val())){
            valida.isValidateInputText(tipo_Trabajo_select)
            $("#errorStoreTP").html(`El campo Tipo de Trabajo seleccionado debe ser de formato válido`)
            return false
        }
        if(!valida.isValidText($("#supervisorTdpStore").val() || "")){
            if (supervisor != "")  valida.isValidateInputText(supervisor) 
            $("#errorStoreTP").html(`El campo Supervisor es requerido`)
            return false
        } 
        if(FECHA_INICIO == ""){ 
            if (fecha_inicio != "")  valida.isValidateInputText(fecha_inicio) 
            $("#errorStoreTP").html(`El campo Fecha de Inicio es requerido`)
            return false
        }
        if(!valida.isValidNumber(celsuptdp.val())){
            valida.isValidateInputText(celsuptdp)
            $("#errorStoreTP").html(`El campo Celular Supervisor TDP debe ser de formato numérico`)
            return false
        }
        if(celsuptdp.val().length > 10 || celsuptdp.val().length < 7){
            valida.isValidateInputText(celsuptdp)
            $("#errors_store").html(`El campo Celular Supervisor TDP debe tener una logintud entre [7 - 10]`)
            return false
          }
        if(!valida.isValidText(h_inicio.val())){
            valida.isValidateInputText(h_inicio)
            $("#errorStoreTP").html(`El campo Hora de Inicio es requerido`)
            return false
        }
        if(!valida.isValidText(h_termino.val())){
            valida.isValidateInputText(h_termino)
            $("#errorStoreTP").html(`El campo Hora de Termino es requerido`)
            return false
        }
        if(!valida.isValidText(corte_sev.val())){
            valida.isValidateInputText(corte_sev)
            $("#errorStoreTP").html(`El campo Corte de Servicio es requerido`)
            return false
        }

        $(".validateText").removeClass("valida-error-input")
        $(".validateSelect").removeClass("valida-error-input")
        $("#errorStoreTP").html(``)
      
      
        return true
         
    }

    function resetFormularioTrabajosProgramados()
    {
        let estructuraNodoPlanos = ``
        HISTORICO_NODO_PLANOS.forEach(el => {
            estructuraNodoPlanos += `<option value="${el}">${el}</option>`
        })

        $("#nodoPlanoLista").html(estructuraNodoPlanos)
        DATA_NODO_PLANOS = HISTORICO_NODO_PLANOS

        $("#nodoPlanoStore").html("")
         

        limpia.limpiaFormTrabajoProgramado()
        limpia.limpiaHtml($("#observacionStore"))
        $("#conCorteStore").prop("checked",true)

        let estructuraSupervisoresPdt = ``
        
        SUPERVISORES_LISTA_INICIAL.forEach(el => {
            estructuraSupervisoresPdt +=`<option value="${el.id}">${el.supervisor}</option>`
        })

        $("#supervisorTdpStore").html(estructuraSupervisoresPdt)

        $("#fechaInicioContentStore").html(`<input id="fechaInicioStore" type="date" value="${FECHA_INICIO_INICIAL}" min="${FECHA_INICIO_INICIAL}" step="1" class="form-control form-control-sm shadow-sm validateText">`)
        FECHA_INICIO = FECHA_INICIO_INICIAL


    }

     
})