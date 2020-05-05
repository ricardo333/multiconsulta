import peticiones from './peticiones.js'
import valida from  "@/globalResources/forms/valida.js"
import errors from  "@/globalResources/errors.js"
import limpia from  "@/globalResources/forms/limpia.js"

$(function(){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

     

    $('[name="SearchDualStoreNodoTroba1"]').keyup(function (e) {
        var code = e.keyCode || e.which;
         
        //if (code == '9') return;
        //if (code == '27') $(this).val(null);
        //var $rows = $(this).closest('.dual-list').find('#interfacesLista option');
        
        if (code == 13) {
            $(this).prop("disabled",true)

            let palabraBusca = $(this).val() 
            if (palabraBusca.trim() != "") {
               
                $("#listaNodoTrobaStore1").html(``) 
               
                LISTA_TROBAS.forEach(el => { 
                    if (el.toLowerCase().indexOf(palabraBusca.toLowerCase()) != -1) {
                        $("#listaNodoTrobaStore1").append(`<option value="${el}">${el}</option>`)
                    } 
                })  

            }
            $(this).prop("disabled",false)
        } 

        if ($(this).val() == "" && code != 13) {
            $(this).prop("disabled",true) 
            //$(this).prop("disabled",true)
           // document.getElementById().disabled = true
            $("#listaNodoTrobaStore1").html(``) 
            LISTA_TROBAS.forEach(el => { 
                $("#listaNodoTrobaStore1").append(`<option value="${el}">${el}</option>`) 
            }) 
            $(this).prop("disabled",false)
        }

        $(this).focus()
       
        
    });


    $('[name="SearchDualStoreNodoTroba2"]').keyup(function (e) {
        var code = e.keyCode || e.which;
       
        if (code == '9') return;
        if (code == '27') $(this).val(null);
        var $rows = $(this).closest('.dual-list').find('#listaNodoTrobaStore2 option');
        var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
        $rows.show().filter(function () {
            var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
            return !~text.indexOf(val);
        }).hide();
    });


    $("#btnLeftStoreTrobas").click(function(){
        let datos1 = document.getElementById("listaNodoTrobaStore1");
        let datos2 = document.getElementById("listaNodoTrobaStore2");
        let collection = datos2.selectedOptions;
        let cantidad = collection.length;

        for (let i = 0; i < cantidad; i++) {
            let valor = collection[i].text;
            let option = document.createElement('option');
            option.value = valor;
            option.text = collection[i].text;
            datos1.add(option);
        }
 
            
        $.each($('[name="duallistbox_storeNodoTroba2"] option:selected'), function( index, value ) {
            LISTA_TROBAS.push(value.value)
            $(this).remove();
        }); 
 
    });
 
    $("#btnRightStoreTrobas").click(function(){
        let datos1 = document.getElementById("listaNodoTrobaStore1");
        let datos2 = document.getElementById("listaNodoTrobaStore2");
        let collection = datos1.selectedOptions;
        let cantidad = collection.length;

        for (let i = 0; i < cantidad; i++) {
            //console.log("collection es: ",collection[i].value)
            let valor = collection[i].value;
            let option = document.createElement('option');
            option.value = valor;
            option.text = collection[i].text;
            datos2.add(option);
        }
            
        $.each($('[name="duallistbox_storeNodoTroba1"] option:selected'), function( index, value ) {  
            let nuevoArrayInterfaces = LISTA_TROBAS.filter(palabra => { 
                 //console.log("La palabra es: ",palabra) 
                    if (palabra != value.value ) {
                        return palabra
                    }
                });
                LISTA_TROBAS = nuevoArrayInterfaces

            $(this).remove();
        });

       
    });


    $("#redirectStoreCuarentenaTab").click(function(){
 
        peticiones.redirectTabs($('#cuarentenaStoreTab')) 
 
    })

    $("#tipoCuarentenaStore").change(function(){

        //console.log("El valor es: ",$(this).val())
        if ( $(this).val() == "seleccionar") {
            $("#storeCuarentena").addClass("d-none") 
            $("#tipoCuarentenaTextoActivo").html("")
            return false
        }
        if ($(this).val() == "AVERIAS") {
            $("#storeCuarentena").removeClass("d-none")
            $("#tipoCuarentenaTextoActivo").html($(this).val()) 
            return false
        }
        if ($(this).val() == "CRITICOS") {
            $("#storeCuarentena").addClass("d-none")
            $("#tipoCuarentenaTextoActivo").html($(this).val())
            peticiones.redirectTabs($('#cuarentenaStoreFileTab')) 
            return false
        }

    })

    $("#listadoStoreJefatura").change(function(){
        cargaTrobasSegunJefatura()
    })

    function cargaTrobasSegunJefatura()
    {

        $("#preloadStoreCuarentenas").html(`<div id="carga_person">
                                                <div class="loader">Loading...</div>
                                            </div>`)
        $("#storeCuarentena").addClass("d-none")

        let jefatura = $("#listadoStoreJefatura").val()
        let nombre = $("#nombreCuarentenaStore").val()

        console.log("El valor jefatura es: ",jefatura)
 
        if (jefatura == "") {
            $("#preloadStoreCuarentenas").html(``)
            $("#storeCuarentena").removeClass("d-none")

            $("#body-errors-modal").html(`<div class="w-100 text-danger text-center">Seleccione una jefatura válida.</div>`)
            $("#errorsModal").modal("show") 
            return false  
        }
        if (jefatura.trim() != ""){
             
            if(jefatura.toLocaleLowerCase() == "seleccionar") {

                $("#preloadStoreCuarentenas").html(``)
                $("#storeCuarentena").removeClass("d-none")

                $("#body-errors-modal").html(`<div class="w-100 text-danger text-center">Seleccione una jefatura válida.</div>`)
                $("#errorsModal").modal("show") 
                return false  
            }

        } 

        $("#listaNodoTrobaStore1").html("")
        $("#listaNodoTrobaStore2").html("")
        LISTA_TROBAS = []

        /*if (nombre == "") {
            $("#body-errors-modal").html(`<div class="w-100 text-danger text-center">El campo nombre no debe estar vacio.</div>`)
            $("#errorsModal").modal("show") 
            return false  
        }

        if (nombre != "") {
            if (nombre.trim() == ""){
                $("#body-errors-modal").html(`<div class="w-100 text-danger text-center">El campo nombre no debe estar vacio.</div>`)
                $("#errorsModal").modal("show") 
                return false  
            } 
        }*/

        peticiones.cargaTrobasProjefatura(jefatura,function(res){

           

            //Errores
            if(res.error == "failed"){

                $("#preloadStoreCuarentenas").html(``)
                $("#storeCuarentena").removeClass("d-none")
    
                // console.log("Error: ",res.errorThrown,res.jqXHR,res.textStatus) 
                //$("#resultado_cuarentenas_store").html(`<div class="col-12 text-danger text-center">${res.jqXHR.responseText}</div>`); 
                //return false
 
                let erroresPeticion =""

                if(res.jqXHR.status){
                    let mensaje = errors.codigos(res.jqXHR.status)
                    erroresPeticion += `<strong> ${mensaje} : </strong>`
                }
                if(res.jqXHR.responseJSON){
                    if(res.jqXHR.responseJSON.mensaje){
                        let erroresMensaje = res.jqXHR.responseJSON.mensaje  //captura objeto
                        let mensaje = errors.mensajeErrorJson(erroresMensaje)
                        erroresPeticion += "<br/>"+ mensaje
                    } 
                }
                
                erroresPeticion = (erroresPeticion.trim() == 0) ? "hubo un error en el servicio, intente nuevamente." : erroresPeticion

                $("#resultado_cuarentenas_store").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                        ${erroresPeticion}</div>`) 

                
                return false

            }
  
            console.log("la data return detalle es: ",res)

            let resultado = res.response
            resultado.trobas.forEach(el => {
                $("#listaNodoTrobaStore1").append(`<option value="${el.nodotroba}">${el.nodotroba}</option>`)
                LISTA_TROBAS.push(el.nodotroba)
            })
          

            $("#preloadStoreCuarentenas").html(``)
            $("#storeCuarentena").removeClass("d-none")

        })
           

    }

    $("#registrarCuarentenaSend").click(function(){
 
        let validacionConitnueStore = validacionContinueStore()
        if(!validacionConitnueStore){ 
            return false
        } 
 
        let jefatura = $("#listadoStoreJefatura").val()
        let nombre = $("#nombreCuarentenaStore").val()
        
        let servicePackage = $("#ListaServicePackageStore").val()
        let scopeGroup = $("#ListaScopeGroupStore").val()
        let estado = $("#ListaEstadoStore").val()
        let tipoDeCuarentena = $("#tipoCuarentenaStore").val()
        let cuadroDeMando = $("#ListapublicadoStore").val()
        let fechaInicio = $("#fechaInicioStore").val()
        let fechaFin= $("#fechaFinStore").val()

        let cantidad = document.getElementById("listaNodoTrobaStore2").options.length;
        var valores = [];
        for (let i = 0; i < cantidad; i++) {
            var selectValue = document.getElementById("listaNodoTrobaStore2").options[i].value;
            valores[i] = selectValue;
        }
      
        $("#preloadStoreCuarentenas").html(`<div id="carga_person">
                                                    <div class="loader">Loading...</div>
                                                </div>`)
        $("#storeCuarentena").addClass("d-none")

        $.ajax({
            url:`/administrador/gestion-cuarentena/store`,
            method:"post",
            data: {
                jefatura,
                nombre,
                "trobas":valores,
                servicePackage,
                scopeGroup,
                tipoDeCuarentena,
                estado,
                cuadroDeMando,
                fechaInicio,
                fechaFin
            },
            dataType: "json", 
        })
        .done(function(data){
 
            console.log("la data return store es: ",data) 
            $("#preloadStoreCuarentenas").html(``)
            $("#storeCuarentena").removeClass("d-none") 

            limpia.limpiaFormStoreCuarentena()
            $("#listaNodoTrobaStore1").html("")
            $("#listaNodoTrobaStore2").html("")
            LISTA_TROBAS = []
            $("#fechaInicioStore").val(FECHA_INICIO_STORE)
            $("#fechaFinStore").val(FECHA_INICIO_STORE)

            $("#successModal").modal("show")
            $("#body-success-modal").html(`<div class="w-100 text-center text-success">${data.mensaje}</div>`)


            peticiones.cargaListaGestionCuarentenas()
            peticiones.redirectTabs($("#cuarentenaListaTab"));

            /*$("#resultado_cuarentenas_store").html(`<div class="container text-center font-weight-bold alert alert-success fade show" role="alert">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                ${data.mensaje}</div>`) */
  
        })
        .fail(function(jqXHR, textStatus){

            $("#preloadStoreCuarentenas").html(``)
            $("#storeCuarentena").removeClass("d-none")
      
            //console.log( "Error: " ,jqXHR, textStatus); 
            // console.log( "Request failed: " ,jqXHR.responseJSON.mensaje);
             //$("#resultado_cuarentenas_store").html(jqXHR.responseText)
             // return false

             let erroresPeticion =""
             if(jqXHR.status){
                 let mensaje = errors.codigos(jqXHR.status)
                 erroresPeticion += `<strong> ${mensaje} </strong>` 
             }
             if(jqXHR.responseJSON){
                 if(jqXHR.responseJSON.mensaje){
                     let erroresMensaje = jqXHR.responseJSON.mensaje  //captura objeto
                     let mensaje = errors.mensajeErrorJson(erroresMensaje)
                     erroresPeticion += "<br>"+ mensaje 
                 } 
             }
             
             erroresPeticion = (erroresPeticion.trim() == 0) ? "hubo un error al traer las trobas, intente nuevamente." : erroresPeticion
     
             $("#resultado_cuarentenas_store").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                        ${erroresPeticion}</div>`) 
             return false
       
        }) 

    })

    function validacionContinueStore()
    { 
        let jefatura = $("#listadoStoreJefatura")
        let nombre = $("#nombreCuarentenaStore")
        let cantidadTrobas = document.getElementById("listaNodoTrobaStore2").options.length;
        let servicePackage = $("#ListaServicePackageStore")
        let scopeGroup = $("#ListaScopeGroupStore")
        let tipoCuarentena = $("#tipoCuarentenaStore")
        let fechaInicio = $("#fechaInicioStore")
        let fechaFin= $("#fechaFinStore")

        $(".validateText").removeClass("valida-error-input")
        $(".validateSelect").removeClass("valida-error-input")
        $("#resultado_cuarentenas_store").html(``)

        if(!valida.isValidText(jefatura.val())){
            valida.isValidateInputText(jefatura)
            $("#resultado_cuarentenas_store").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    El campo jefatura es requerido</div>`)
            
            return false
        } 
        if(!valida.isValidText(nombre.val())){
            valida.isValidateInputText(nombre)
            $("#resultado_cuarentenas_store").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    El campo nombre es requerido</div>`)
            return false
        } 
        if (cantidadTrobas == 0) {
            valida.isValidateInputText($("#listaNodoTrobaStore2"))
            $("#resultado_cuarentenas_store").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    El campo trobas es requerido</div>`)
            return false
        }
       /* if(!valida.isValidText(servicePackage.val())){
            valida.isValidateInputText(servicePackage)
            $("#resultado_cuarentenas_store").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    El campo servicePackage es requerido</div>`)
            return false
        } 
        if(servicePackage.val().toLowerCase() == "seleccionar"){
            valida.isValidateInputText(servicePackage)
            $("#resultado_cuarentenas_store").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    Seleccione una servicePackage válido</div>`)
            return false
        }
        if(!valida.isValidText(scopeGroup.val())){
            valida.isValidateInputText(scopeGroup)
            $("#resultado_cuarentenas_store").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    El campo scopeGroup es requerido</div>`)
            return false
        } 
        if(scopeGroup.val().toLowerCase() == "seleccionar"){
            valida.isValidateInputText(scopeGroup)
            $("#resultado_cuarentenas_store").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                  Seleccione una scopeGroup válido</div>`)
            return false
        }*/

        if(tipoCuarentena.val().toLowerCase() == "seleccionar"){
            valida.isValidateInputText(tipoCuarentena)
            $("#resultado_cuarentenas_store").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                  Seleccione un Tipo de Cuarentena válido</div>`)
            return false
        }

        if(!valida.isValidText(fechaInicio.val())){
            valida.isValidateInputText(fechaInicio)
            $("#resultado_cuarentenas_store").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                        El campo fechaInicio es requerido</div>`)
            return false
        }
        if(!valida.isValidText(fechaFin.val())){
            valida.isValidateInputText(fechaFin)
            $("#resultado_cuarentenas_store").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                        El campo fechaFin es requerido</div>`)
            return false
        }

        $(".validateText").removeClass("valida-error-input")
        $(".validateSelect").removeClass("valida-error-input")
        $("#resultado_cuarentenas_store").html(``)
       
        return true
 
        

    }

    //FILE

    $(".return_store_cuarentenas_Tab").click(function(){
     
        $("#tipoCuarentenaStore").val("Seleccionar")
        $("#storeCuarentena").addClass("d-none") 
        $("#tipoCuarentenaTextoActivo").html("")

        peticiones.redirectTabs($('#cuarentenaStoreTab')) 

    })
    $("#storeFileRedirectGCuarentena").click(function(){

        $("#preloadStoreCuarentenasFile").html("")
        $("#storeCuarentenaFile").removeClass("d-none")
        $("#nameFileValidate").html("")
        $("#nombreCuarentenaStoreFile").val("")
        $("#ListaEstadoStoreFile").val("Activo")
        $("#ListapublicadoStoreFile").val("Activo")
        $("#fechaInicioStoreFile").val(FECHA_INICIO_STORE)
        $("#fechaFinStoreFile").val(FECHA_INICIO_STORE)
        $("#fileLoadStoreCuarentena").val("") 
        peticiones.redirectTabs($('#cuarentenaStoreFileTab')) 

    })

    $("body").on("change","#fileLoadStoreCuarentena", function(){

        //console.log("la carga due completada del change")
        //console.log($(this),"----------------")
        //console.log($(this)[0],"----------------")
        //console.log($(this)[0].files[0])

         $("#nameFileValidate").html(``)  
 
         
        if($(this)[0].files[0]){
            let imagen_detalle = $(this)[0].files[0]

            $("#validacionServicio_load").addClass("d-none")
            $("#nameFileValidate").html(`<div class="w-100 d-flex justify-content-center">
                                                        <div class="spinner-grow text-primary" role="status">
                                                            <span class="sr-only">Loading...</span>
                                                        </div>
                                                    </div>`)  
            let reader = new FileReader();
            reader.onload = function(e) {
                    console.log("el load es:",e)
                ////console.log(e.target.result) 
                // $('#file_preview_totalUpdate').attr('src', e.target.result); 
                $("#validacionServicio_load").removeClass("d-none")
                $("#nameFileValidate").html(`<div class="w-100 text-center">${imagen_detalle["name"]}</div>`)
            }
            reader.readAsDataURL(imagen_detalle)

        }  
    })

    $("#registrarCuarentenaFileSend").click(function(){

        $("#storeCuarentenaFile").addClass("d-none")
        $("#preloadStoreCuarentenasFile").html(`<div class="d-flex justify-content-center align-content-center flex-wrap w-100">
                        <div class="spinner-border" role="status" style="width: 150px;height: 150px;">
                        <span class="sr-only">Loading...</span>
                        </div>
                        <div class="text-center w-100">
                            <strong>Validando Datos</strong>
                        </div>
                    </div>`)
 
        let archivoValidaServicio = $('#fileLoadStoreCuarentena')[0].files[0]
        let nombre = $("#nombreCuarentenaStoreFile").val()
        let estado = $("#ListaEstadoStoreFile").val()
        let cuadroDeMando = $("#ListapublicadoStoreFile").val()
        //let tipoDeCuarentena = $("#tipoCuarentenaStoreFile").val()
        let tipoDeCuarentena = $("#tipoCuarentenaStore").val()
        let fechaInicio = $("#fechaInicioStoreFile").val()
        let fechaFin = $("#fechaFinStoreFile").val()
               
        let formData = new FormData(); 
     
        formData.append('nombre',nombre);
        formData.append('estado',estado);
        formData.append('cuadroDeMando',cuadroDeMando);
        formData.append('tipoDeCuarentena',tipoDeCuarentena);
        formData.append('fechaInicio',fechaInicio);
        formData.append('fechaFin',fechaFin);
        formData.append('archivo',archivoValidaServicio);
        formData.append('estadoDeGuardado',false);

        //console.log("la data a enviar es: ",formData)

        procesarDataRegistroGestionCuarentena(formData)
    
    })

    function procesarDataRegistroGestionCuarentena(formData)
    {
        //$("#storeCuarentenaFile").removeClass("d-none")
         peticiones.loadArchivoServicio(formData,function(res){
           // console.log("la respuestasaaaaaaa es: ",res)
            $("#validacionServicio_load").removeClass("d-none")
            if(res.error == "failed"){

               
                $("#storeCuarentenaFile").removeClass("d-none")
                $("#preloadStoreCuarentenasFile").html(``)
                
                //$("#rpta_validacionServ").html(``)
                //console.log("Error: ",res.errorThrown,res.jqXHR,res.textStatus) 
                //$("#preloadStoreCuarentenasFile").html(`<div class="col-12 text-danger text-center">${res.jqXHR.responseText}</div>`); 
                //return false

                  
                  let erroresPeticion =""
                if(res.jqXHR.status){
                    erroresPeticion = errors.codigos(res.jqXHR.status) 
                }
                if(res.jqXHR.responseJSON){
                    if(res.jqXHR.responseJSON.mensaje){
                        let erroresMensaje = res.jqXHR.responseJSON.mensaje  //captura objeto
                        let mensaje = errors.mensajeErrorJson(erroresMensaje)
                        erroresPeticion  +=  `<br> <strong>${mensaje}</strong>`
                        
                    } 
                }
                erroresPeticion = (erroresPeticion.trim() == 0) ? "hubo un error en el servicio, intente nuevamente." : erroresPeticion

                $("#preloadStoreCuarentenasFile").html(`<div class="col-12 text-danger text-center text-sm font-italic">${erroresPeticion}</div>`); 
                return false 
              
             }

            // $("#storeCuarentenaFile").removeClass("d-none")
             $("#preloadStoreCuarentenasFile").html(``)

            // console.log("la respuesta es pasando falied..: ",res,"-----")
             $("#preloadStoreCuarentenasFile").html(`<div class="col-12 text-danger text-center text-sm font-italic">${res}</div>`); 
 

             let procesoRpta = res.response
            // console.log("La respuesta es: ",procesoRpta)

            if (procesoRpta.procesoResult == false) {

                if (parseInt(procesoRpta.cantidadErrores) > 0) {

                    //$("#storeCuarentenaFile").addClass("d-none")
                    $("#preloadStoreCuarentenasFile").html(``)

                    let msjErrors = `<ul class="list-unstyled text-sm text-center text-danger font-italic">`
                    procesoRpta.errores.forEach(el => {
                        msjErrors += `<li>${el}</li>`
                    });
                    msjErrors += "</ul>"
    
                    $("#preloadStoreCuarentenasFile").html(`<div class="w-100">
                                                        ${msjErrors} <br>
                                                        <div class="w-100">
                                                            <div class="w-100 text-center">
                                                                <a href="javascript:void(0)" id="procesarStoreFileCuarentena" class="btn btn-sm btn-outline-success shadow-sm m-1">Guardar de todas maneras.</a>
                                                                <a href="javascript:void(0)" id="reProcesarStoreFileCuarentena" class="btn btn-sm btn-outline-success shadow-sm m-1">Cancelar Cuarentena.</a>
                                                            </div>
                                                        </div> 
                                                     </div>`)
                   // $("#buttons_validacionesServ").removeClass("d-none")
    
                    return false
    
                }

                 //Deberia cambiar el Load a Guardando Cuarentena

                //$("#storeCuarentenaFile").addClass("d-none")
                $("#preloadStoreCuarentenasFile").html(`<div class="d-flex justify-content-center align-content-center flex-wrap w-100">
                                                        <div class="spinner-border" role="status" style="width: 150px;height: 150px;">
                                                        <span class="sr-only">Loading...</span>
                                                        </div>
                                                        <div class="text-center w-100">
                                                            <strong>Guardando clientes en Cuarentenas</strong>
                                                        </div>
                                                    </div>`)

            
              
                        
                let formData = new FormData();  
                formData.append('estadoDeGuardado',true);
  
                //console.log("la data a enviar nuevamente es: ",formData)

                procesarDataRegistroGestionCuarentena(formData)

                return false

            }
            //console.log("el store si se proceso en el servidor, estas en true.......")

            //limpia.limpiaHtml($("#rpta_validacionServ"))
            //limpia.limpiaFormValidaServicio() 
            //$("#nameFileValidate").html(``) 

            $("#preloadStoreCuarentenasFile").html(``)  
            $("#storeCuarentenaFile").removeClass("d-none")

            $("#successModal").modal("show")
            $("#body-success-modal").html(`<div class="w-100 text-center text-success">Las cuarentenas se guardarón correctamente.</div>`)

            $("#tipoCuarentenaStore").val("Seleccionar")
            $("#storeCuarentena").addClass("d-none") 
            $("#tipoCuarentenaTextoActivo").html("")

            $("#nombreCuarentenaStoreFile").val("")
            $("#ListaEstadoStoreFile").val("Activo")
            $("#ListapublicadoStoreFile").val("Activo") 
            $("#fechaInicioStoreFile").val(FECHA_INICIO_STORE)
            $("#fechaFinStoreFile").val(FECHA_INICIO_STORE)
            
            peticiones.cargaListaGestionCuarentenas()
            peticiones.redirectTabs($("#cuarentenaListaTab"));
 
           // $("#resultado_cuarentenas_store_file").html(`Se guardaron las cuarentenas corretamente...`) 
 
            
        })

    }

    $("body").on("click", "#procesarStoreFileCuarentena", function(){
         //console.log("aqui el evento click...") 

        $("#storeCuarentenaFile").addClass("d-none")
        $("#preloadStoreCuarentenasFile").html(`<div class="d-flex justify-content-center align-content-center flex-wrap w-100">
                                            <div class="spinner-border" role="status" style="width: 150px;height: 150px;">
                                            <span class="sr-only">Loading...</span>
                                            </div>
                                            <div class="text-center w-100">
                                                <strong>Guardando clientes en Cuarentenas</strong>
                                            </div>
                                        </div>`)
 
 
        
        let formData = new FormData();  
        formData.append('estadoDeGuardado',true);
 
         //console.log("la data a enviar nuevamente es: ",formData)
 
         procesarDataRegistroGestionCuarentena(formData)

    })

    $("body").on("click","#reProcesarStoreFileCuarentena", function(){
        //console.log("Se cancelará proceso") 
        $("#preloadStoreCuarentenasFile").html("")
        $("#storeCuarentenaFile").removeClass("d-none")
        $("#nameFileValidate").html("")
        $("#nombreCuarentenaStoreFile").val("")
        //$("#tipoCuarentenaStoreFile").val("seleccionar")
        $("#ListaEstadoStoreFile").val("Activo")
        $("#ListapublicadoStoreFile").val("Activo")
        $("#fechaInicioStoreFile").val(FECHA_INICIO_STORE)
        $("#fechaFinStoreFile").val(FECHA_INICIO_STORE)
        $("#fileLoadStoreCuarentena").val("")
       
    })



})