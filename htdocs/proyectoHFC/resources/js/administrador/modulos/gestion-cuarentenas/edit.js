import peticiones from './peticiones.js'
import valida from  "@/globalResources/forms/valida.js"
import errors from  "@/globalResources/errors.js"

$(function(){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('[name="SearchDualEditNodoTroba1"]').keyup(function (e) {
        var code = e.keyCode || e.which;
         
        //if (code == '9') return;
        //if (code == '27') $(this).val(null);
        //var $rows = $(this).closest('.dual-list').find('#interfacesLista option');
        
        if (code == 13) {
            $(this).prop("disabled",true)

            let palabraBusca = $(this).val() 
            if (palabraBusca.trim() != "") {
               
                $("#listaNodoTrobaEdit1").html(``) 
               
                LISTA_TROBAS_EDIT.forEach(el => { 
                    if (el.toLowerCase().indexOf(palabraBusca.toLowerCase()) != -1) {
                        $("#listaNodoTrobaEdit1").append(`<option value="${el}">${el}</option>`)
                    } 
                })  

            }
            $(this).prop("disabled",false)
        } 

        if ($(this).val() == "" && code != 13) {
            $(this).prop("disabled",true) 
            //$(this).prop("disabled",true)
           // document.getElementById().disabled = true
            $("#listaNodoTrobaEdit1").html(``) 
            LISTA_TROBAS_EDIT.forEach(el => { 
                $("#listaNodoTrobaEdit1").append(`<option value="${el}">${el}</option>`) 
            }) 
            $(this).prop("disabled",false)
        }

        $(this).focus()
       
        
    });

    $('[name="SearchDualEditNodoTroba2"]').keyup(function (e) {
        var code = e.keyCode || e.which;
       
        if (code == '9') return;
        if (code == '27') $(this).val(null);
        var $rows = $(this).closest('.dual-list').find('#listaNodoTrobaEdit2 option');
        var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
        $rows.show().filter(function () {
            var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
            return !~text.indexOf(val);
        }).hide();
    });

    $("#btnLeftEditTrobas").click(function(){
        let datos1 = document.getElementById("listaNodoTrobaEdit1");
        let datos2 = document.getElementById("listaNodoTrobaEdit2");
        let collection = datos2.selectedOptions;
        let cantidad = collection.length;

        for (let i = 0; i < cantidad; i++) {
            let valor = collection[i].text;
            let option = document.createElement('option');
            option.value = valor;
            option.text = collection[i].text;
            datos1.add(option);
        }
 
            
        $.each($('[name="duallistbox_editNodoTroba2"] option:selected'), function( index, value ) {
            LISTA_TROBAS_EDIT.push(value.value)
            $(this).remove();
        }); 
 
    });

    $("#btnRightEditTrobas").click(function(){
        let datos1 = document.getElementById("listaNodoTrobaEdit1");
        let datos2 = document.getElementById("listaNodoTrobaEdit2");
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
            
        $.each($('[name="duallistbox_editNodoTroba1"] option:selected'), function( index, value ) {  
            let nuevoArrayInterfaces = LISTA_TROBAS_EDIT.filter(palabra => { 
                 //console.log("La palabra es: ",palabra) 
                    if (palabra != value.value ) {
                        return palabra
                    }
                });
                LISTA_TROBAS_EDIT = nuevoArrayInterfaces

            $(this).remove();
        });

       
    });




    $("body").on("click",".editarCuarentenaGestion", function(){

        let identificador = $(this).data("uno")
        let jefatura = $(this).data("dos") 
        let nombre = $(this).data("tres")
        let servicepackage = $(this).data("cuatro")
        let scopegroup = $(this).data("cinco")
        let fInicio = $(this).data("seis")
        let fFin = $(this).data("siete")
        let estado = $(this).data("ocho")
        let publicado = $(this).data("nueve")
        let tipo = $(this).data("diez")

        $("#listaNodoTrobaEdit1").html("")
        $("#listaNodoTrobaEdit2").html("")
        LISTA_TROBAS_EDIT = []


        peticiones.redirectTabs($("#cuarentenaEditTab"));
 
        $("#preloadEditCuarentenas").html(`<div id="carga_person">
                                                <div class="loader">Loading...</div>
                                            </div>`)
        $("#EditCuarentena").addClass("d-none")

        $("#ListaNodoTrobaEdit").html("")

        $.ajax({
            url:`/administrador/gestion-cuarentena/${identificador}/detalles`,
            method:"get",
            dataType: "json", 
        })
        .done(function(data){
 
            $("#preloadEditCuarentenas").html(``)
            $("#EditCuarentena").removeClass("d-none") 

            console.log("El detalle de cliente: ",data) 

            let resultado = data.response
            resultado.trobas.forEach(el => {
                $("#listaNodoTrobaEdit1").append(`<option value="${el.nodotroba}">${el.nodotroba}</option>`)
                LISTA_TROBAS_EDIT.push(el.nodotroba)
            })
            resultado.trobasCuarentenas.forEach(el => {
                $("#listaNodoTrobaEdit2").append(`<option value="${el.nodo}-${el.troba}">${el.nodo}-${el.troba}</option>`)
            })

            //LISTA_TROBAS_EDIT

            let servPack = (servicepackage == null || servicepackage == "") ? "Seleccionar": servicepackage
            let scopeGrp = (scopegroup == null || scopegroup == "") ? "Seleccionar": scopegroup

            //console.log("el service Pakc es: ",servPack, "El scope group es: ", scopeGrp)

            $("#listadoEditJefatura").val(jefatura)
            $("#nombreCuarentenaEdit").val(nombre)
            $("#ListaServicePackageEdit").val(servPack)
            $("#ListaScopeGroupEdit").val(scopeGrp)
            $("#fechaInicioEdit").val(fInicio)
            $("#fechaFinEdit").val(fFin)
            $("#ListaEstadoEdit").val(estado)
            $("#ListapublicadoEdit").val(publicado)
            $("#tipoCuarentenaEdit").val(tipo)

            document.getElementById("actualizarCuarentenaSend").dataset.uno = identificador
             
        })
        .fail(function(jqXHR, textStatus){

            $("#preloadEditCuarentenas").html(``)
            $("#EditCuarentena").removeClass("d-none") 
  
            //console.log( "Error: " ,jqXHR, textStatus); 
            // console.log( "Request failed: " ,jqXHR.responseJSON.mensaje);
            $("#resultado_cuarentenas_edit").html(jqXHR.responseText)
            return false

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
     
             $("#resultado_cuarentenas_edit").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                        ${erroresPeticion}</div>`) 
             return false
       
        }) 

        /*peticiones.cargaTrobasProjefatura(jefatura,function(res){

            $("#preloadEditCuarentenas").html(``)
            $("#EditCuarentena").removeClass("d-none") 

            //Errores
            if(res.error == "failed"){
    
                // console.log("Error: ",res.errorThrown,res.jqXHR,res.textStatus) 
                //$("#resultado_cuarentenas_edit").html(`<div class="col-12 text-danger text-center">${res.jqXHR.responseText}</div>`); 
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
                        erroresPeticion += "<br/>"+ `<div class="text-center text-secondary">${mensaje}</div>`
                    } 
                }
                
                erroresPeticion = (erroresPeticion.trim() == 0) ? "hubo un error en el servicio, intente nuevamente." : erroresPeticion

                $("#body-errors-modal").html(`<div class="col-12 text-danger text-center">${erroresPeticion}</div>`)
                $('#errorsModal').modal('show')

                peticiones.redirectTabs($("#cuarentenaListaTab"));

                return false

            }
 
            //console.log("la data return cambio trobas por jefatura es: ",res) 
            $("#preloadEditCuarentenas").html(``)
            $("#EditCuarentena").removeClass("d-none") 
           
            $("#listadoEditJefatura").val(jefatura)

            res.response.trobas.forEach(el => {
                $("#ListaNodoTrobaEdit").append(`<option value="${el.nodotroba}" ${trobaSelect == el.nodotroba? "selected" : ""}>${el.nodotroba}</option>`)
            }) 

            $("#nombreCuarentenaEdit").val(nombre)
            $("#ListaServicePackageEdit").val(servicepackage)
            $("#ListaScopeGroupEdit").val(scopegroup)
            $("#fechaInicioEdit").val(fInicio)
            $("#fechaFinEdit").val(fFin)

            document.getElementById("actualizarCuarentenaSend").dataset.uno = identificador
  
        })*/
 

    })

    $("#listadoEditJefatura").change(function(){
       // console.log("Se genero un change en el select de jefaturas..")
        let jefatura = $(this).val()

        $("#preloadEditCuarentenas").html(`<div id="carga_person">
                <div class="loader">Loading...</div>
            </div>`)
        $("#EditCuarentena").addClass("d-none")

        
        if (jefatura == "") {
            $("#preloadEditCuarentenas").html(``)
            $("#EditCuarentena").removeClass("d-none")

            $("#body-errors-modal").html(`<div class="w-100 text-danger text-center">Seleccione una jefatura válida.</div>`)
            $("#errorsModal").modal("show") 
            return false  
        }
        if (jefatura.trim() != ""){
             
            if(jefatura.toLocaleLowerCase() == "seleccionar") {

                $("#preloadEditCuarentenas").html(``)
                $("#EditCuarentena").removeClass("d-none")

                $("#body-errors-modal").html(`<div class="w-100 text-danger text-center">Seleccione una jefatura válida.</div>`)
                $("#errorsModal").modal("show") 
                return false  
            }

        } 

        //$("#ListaNodoTrobaEdit").html("")

        $("#listaNodoTrobaEdit1").html("")
        $("#listaNodoTrobaEdit2").html("")
        LISTA_TROBAS_EDIT = []


        peticiones.cargaTrobasProjefatura(jefatura,function(res){ 

            //Errores
            if(res.error == "failed"){

                $("#preloadEditCuarentenas").html(``)
                $("#EditCuarentena").removeClass("d-none")
    
                // console.log("Error: ",res.errorThrown,res.jqXHR,res.textStatus) 
                //$("#resultado_cuarentenas_edit").html(`<div class="col-12 text-danger text-center">${res.jqXHR.responseText}</div>`); 
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

                $("#resultado_cuarentenas_edit").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                        ${erroresPeticion}</div>`) 

                
                return false

            }
  
            //console.log("la data return detalle es: ",res)

            res.response.trobas.forEach(el => {
                $("#listaNodoTrobaEdit1").append(`<option value="${el.nodotroba}">${el.nodotroba}</option>`)
                LISTA_TROBAS.push(el.nodotroba)
            }) 
           
            $("#preloadEditCuarentenas").html(``)
            $("#EditCuarentena").removeClass("d-none")

        })

    })

    $("#actualizarCuarentenaSend").click(function(){

        let identificador = $(this).data("uno")

        if (identificador == "" || identificador == null) {
            $("#body-errors-modal").html(`<div class="w-100 text-danger text-center">No se puedo identificar la cuarentena, se canceló la edición, intente nuevamente.</div>`)
            $("#errorsModal").modal("show") 
            peticiones.redirectTabs($("#cuarentenaListaTab"));
            return false  
        }

        let validacionConitnueEdit = validacionContinueEdit()
        if(!validacionConitnueEdit){ 
            return false
        } 

        let jefatura = $("#listadoEditJefatura").val()
        let nombre = $("#nombreCuarentenaEdit").val() 
        let servicePackage = $("#ListaServicePackageEdit").val()
        let scopeGroup = $("#ListaScopeGroupEdit").val()
       //let trobas= $("#ListaNodoTrobaEdit").val()
        let fechaInicio = $("#fechaInicioEdit").val()
        let fechaFin= $("#fechaFinEdit").val()
        let estado= $("#ListaEstadoEdit").val()
        let cuadroDeMando= $("#ListapublicadoEdit").val()
        let tipoDeCuarentena= $("#tipoCuarentenaEdit").val()

        let cantidad = document.getElementById("listaNodoTrobaEdit2").options.length;
        var valores = [];
        for (let i = 0; i < cantidad; i++) {
            var selectValue = document.getElementById("listaNodoTrobaEdit2").options[i].value;
            valores[i] = selectValue;
        }


 
        $("#preloadEditCuarentenas").html(`<div id="carga_person">
                <div class="loader">Loading...</div>
            </div>`)
        $("#EditCuarentena").addClass("d-none")

        $.ajax({
            url:`/administrador/gestion-cuarentena/${identificador}/update`,
            method:"post",
            data: {
                jefatura,
                nombre,
                "trobas":valores,
                servicePackage,
                scopeGroup,
                fechaInicio,
                fechaFin,
                estado,
                cuadroDeMando,
                tipoDeCuarentena
            },
            dataType: "json", 
        })
        .done(function(data){
 
             console.log("la data return Edit es: ",data) 

            $("#successModal").modal("show")
            $("#body-success-modal").html(`<div class="w-100 text-center text-success">${data.mensaje}</div>`)
             
            $("#preloadEditCuarentenas").html(``)
            $("#EditCuarentena").removeClass("d-none")

            peticiones.cargaListaGestionCuarentenas()
            peticiones.redirectTabs($("#cuarentenaListaTab"));
            
  
        })
        .fail(function(jqXHR, textStatus){

            $("#preloadEditCuarentenas").html(``)
            $("#EditCuarentena").removeClass("d-none")
      
            //console.log( "Error: " ,jqXHR, textStatus); 
            // console.log( "Request failed: " ,jqXHR.responseJSON.mensaje);
            //$("#resultado_cuarentenas_edit").html(jqXHR.responseText)
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
     
             $("#resultado_cuarentenas_edit").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                        ${erroresPeticion}</div>`) 
             return false
       
        })

    })

    function validacionContinueEdit()
    { 

        let jefatura = $("#listadoEditJefatura")
        let nombre = $("#nombreCuarentenaEdit")      
        //let trobas= $("#ListaNodoTrobaEdit")
        let cantidadTrobas = document.getElementById("listaNodoTrobaEdit2").options.length;
        let fechaInicio = $("#fechaInicioEdit")
        let fechaFin= $("#fechaFinEdit")
        let tipoDeCuarentena= $("#tipoCuarentenaEdit")
 
        $(".validateText").removeClass("valida-error-input")
        $(".validateSelect").removeClass("valida-error-input")
        $("#resultado_cuarentenas_edit").html(``)

        if(!valida.isValidText(jefatura.val())){
            valida.isValidateInputText(jefatura)
            $("#resultado_cuarentenas_edit").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    El campo jefatura es requerido</div>`)
            
            return false
        } 
        if(!valida.isValidText(nombre.val())){
            valida.isValidateInputText(nombre)
            $("#resultado_cuarentenas_edit").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    El campo nombre es requerido</div>`)
            return false
        } 
        if (cantidadTrobas == 0) {
            valida.isValidateInputText($("#listaNodoTrobaEdit2"))
            $("#resultado_cuarentenas_edit").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    El campo trobas es requerido</div>`)
            return false
        }
        /*if(!valida.isValidText(trobas.val())){
            valida.isValidateInputText(trobas)
            $("#resultado_cuarentenas_edit").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    El campo trobas es requerido</div>`)
            return false
        } 
         
        if(trobas.val().toLowerCase() == "seleccionar"){
            valida.isValidateInputText(trobas)
            $("#resultado_cuarentenas_edit").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    Seleccione una trobas válida</div>`)
            return false
        }*/

        if(tipoDeCuarentena.val().toLowerCase() == "seleccionar"){
            valida.isValidateInputText(tipoDeCuarentena)
            $("#resultado_cuarentenas_edit").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    Seleccione un Tipo de cuarentena válido</div>`)
            return false
        }
       
        if(!valida.isValidText(fechaInicio.val())){
            valida.isValidateInputText(fechaInicio)
            $("#resultado_cuarentenas_edit").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                        El campo fechaInicio es requerido</div>`)
            return false
        }
        if(!valida.isValidText(fechaFin.val())){
            valida.isValidateInputText(fechaFin)
            $("#resultado_cuarentenas_edit").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                        El campo fechaFin es requerido</div>`)
            return false
        }

        $(".validateText").removeClass("valida-error-input")
        $(".validateSelect").removeClass("valida-error-input")
        $("#resultado_cuarentenas_edit").html(``)
       
        return true
 
        

    }

    

    

})