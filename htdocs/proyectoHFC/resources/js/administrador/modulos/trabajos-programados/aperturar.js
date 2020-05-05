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

    $("body").on("click",".aperturarTP", function(){
         
        let item = $(this).data("uno")

        $("#preloadAperturaTrabajoProg").html(`<div id="carga_person">
                                                    <div class="loader">Loading...</div>
                                                </div>`)
        $("#contentDetalleTPApertura").html(`<div id="carga_person">
                                                    <div class="loader">Loading...</div>
                                                </div>`)
        $("#form_apertura_send").html(`<div id="carga_person">
                                                    <div class="loader">Loading...</div>
                                                </div>`)

        $("#contentAcordionApertura").addClass("d-none")

        peticiones.redirectTabs($("#AperturarTrabajoProgtab"))

         
       $.ajax({
           url:`/administrador/trabajos-programados/${item}/detalle`,
           method:"get",
           data: {
               "formulario":"APERTURA"
           },
           dataType: "json", 
       })
       .done(function(data){
             //console.log("la data return detalle ses: ",data)
 
            let resultadoDetalle = data.response.data
            let resultApertura = data.response.dataApertura
 
            let armandoEstructuraDetalle = `  
                <div class="form row my-2 mx-0" id="contentFormDetalleApertura">`

            armandoEstructuraDetalle += peticiones.armaEstructuraDetalleTP(resultadoDetalle)
                    
            armandoEstructuraDetalle += `</div>` 

            let armandoEstructuraFormulario = `
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label for="fechaAperturaSend">Fecha de Apertura:</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <input type="date" name="fechaAperturaSend" id="fechaAperturaSend" step="1" 
                                                            min="${resultApertura.fechaApertura}"  value="${resultApertura.fechaApertura}"  class="form-control form-control-sm shadow-sm validateText">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label for="horaAperturaSend">Hora de Apertura:</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                            <input type="time" name="horaAperturaSend" id="horaAperturaSend" min="${resultApertura.hora}:00"
                                                            max="23:00" step="3600" value="${resultApertura.hora}:00"  class="form-control form-control-sm shadow-sm validateText"> 
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label for="tecnicoAperturaSend">Técnico:</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                            <select name="tecnicoAperturaSend" id="tecnicoAperturaSend"  class="form-control form-control-sm shadow-sm validateSelect">
                                                                <option value="seleccionar">Seleccionar</option> 
                                                            `
                                resultApertura.tecnicos.forEach(el => {
                                        armandoEstructuraFormulario +=      `<option value="${el.TECNICO}">${el.TECNICO}</option>`
                                })
                    
                                armandoEstructuraFormulario +=   `
                                                            </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label for="carnetTecAperturaSend">Carnet Técnico:</label>
                                                    </div>
                                                    <div class="col-md-8"> 
                                                            <input type="text" id="carnetTecAperturaSend" name="carnetTecAperturaSend"  class="form-control form-control-sm shadow-sm validateText">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label for="supervisorContrataAperturaSend">Supervisor Contrata:</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <input type="text" id="supervisorContrataAperturaSend" name="supervisorContrataAperturaSend"  class="form-control form-control-sm shadow-sm validateText">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <label for="celSupContrataAperturaSend">Cel. Supervisor Contrata:</label>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <input type="text" id="celSupContrataAperturaSend" name="celSupContrataAperturaSend"  class="form-control form-control-sm shadow-sm validateText">
                                                            </div>
                                                        </div>
                                                        <div class="row mt-3">
                                                            <div class="col-md-12">
                                                                <label for="telefonoAperturaSend">Teléfono Técnico:</label>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <input type="text" id="telefonoAperturaSend" name="telefonoAperturaSend"  class="form-control form-control-sm shadow-sm validateText">
                                                            </div>
                                                        </div>
                                                        <div class="row my-3">
                                                            <div class="col-md-12">
                                                                <label for="contrataAperturaSend">Contrata:</label>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <select name="contrataAperturaSend" id="contrataAperturaSend"  class="form-control form-control-sm shadow-sm validateSelect">
                                                                    <option value="seleccionar">Seleccionar</option> 
                                                                    `
                                                                    resultApertura.contratas.forEach(el => {
                                                                        armandoEstructuraFormulario +=      `<option value="${el.contrata}">${el.contrata}</option>`
                                                                    })
                                                                    armandoEstructuraFormulario +=   `
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <label for="imagenAperturaSend" class="col-form-label col-form-label-sm btn btn-outline-info btn-sm d-flex align-items-center justify-content-center">Imagen Estado: </label>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <figure id="info_detalle_imagenApertura" class="card mt-1 figure figura_aper_cierre_image"> 
                                                                    <img id="file_preview_apertura" class="img-apertura" src="/images/upload/trabajos-programados/sinimagen.png">
                                                                    <figcaption id="text_preview_apertura" class="figure-caption text-right">Sin imagen</figcaption>
                                                                </figure>  
                                                                <input type="file" id="imagenAperturaSend" name="imagenAperturaSend"  class="d-none">
                                                            </div>
                                                        </div>
                                                    </div>
                                                   
                                                </div>
                                            </div> 
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label for="observacionesAperturaSend">Observaciones:</label>
                                                    </div>
                                                    <div class="col-12">
                                                        <textarea name="observacionesAperturaSend" id="observacionesAperturaSend" cols="30" rows="4" class="form-control form-control-sm shadow-sm validateText"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">  
                                                    <div class="col-12 text-center text-danger" id="errorValidacionApertura">
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">  
                                                    <div class="col-12 text-center">
                                                        <a href="javascript: void(0)" data-uno="${resultadoDetalle.ITEM}" id="aperturarTPSend" class="btn btn-sm btn-outline-success shadow-sm">Aperturar Trabajo Programado</a>
                                                    </div>
                                                </div>
                                            </div>

                                        `

            $("#contentDetalleTPApertura").html(armandoEstructuraDetalle)
            $("#form_apertura_send").html(armandoEstructuraFormulario)

            $("#preloadAperturaTrabajoProg").html(``) 
            $("#contentAcordionApertura").removeClass("d-none")

       })
       .fail(function(jqXHR, textStatus){

        
            $("#preloadAperturaTrabajoProg").html(``) 
            $("#contentDetalleTPApertura").html("")
            $("#form_apertura_send").html("")
            $("#contentAcordionApertura").removeClass("d-none")
 
            //console.log( "Error: " ,jqXHR, textStatus); 
            //$("#preloadAperturaTrabajoProg").html(jqXHR.responseText)
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
    
            $("#preloadAperturaTrabajoProg").html(`<div class="w-100 text-danger justify-content-center">${erroresPeticion}</div>`) 
            return false
      
        }) 


    })

    $("body").on("change","#imagenAperturaSend",function(){

        //console.log($(this)[0].files[0])

        if($(this)[0].files[0]){
            let imagen_detalle = $(this)[0].files[0]
 
            // 
            $("#text_preview_apertura").html(`<div class="d-flex justify-content-center">
                                                        <div class="spinner-grow text-primary" role="status">
                                                            <span class="sr-only">Loading...</span>
                                                        </div>
                                                    </div>`) 

            let reader = new FileReader();
            reader.onload = function(e) {
                 //console.log("el load es:",e)
                ////console.log(e.target.result) 
                $('#file_preview_apertura').attr('src', e.target.result); 
               $("#text_preview_apertura").html(imagen_detalle["name"])
            }
            reader.readAsDataURL(imagen_detalle)

        }else{ 
            
            $('#file_preview_apertura').attr('src', "/images/upload/trabajos-programados/sinimagen.png"); 
            $("#text_preview_apertura").html("Sin imagen")

        }
    })

    $("body").on("click","#aperturarTPSend", function(){

        let validacionApertura = validacionContinueApertura()
        if(!validacionApertura){ 
            return false
        } 
 
        let item = $(this).data("uno")
        
        
        let fechaDeApertura = $("#fechaAperturaSend").val()
        let hora = $("#horaAperturaSend").val()
        let tecnico = $("#tecnicoAperturaSend").val()
        let carnetTecnico = $("#carnetTecAperturaSend").val()
        let supervisorContrata = $("#supervisorContrataAperturaSend").val()
        let celSupContrata = $("#celSupContrataAperturaSend").val()
        let telefono = $("#telefonoAperturaSend").val()
        let contrata = $("#contrataAperturaSend").val()
        let observaciones = $("#observacionesAperturaSend").val()
        let imagenEstado = $('#imagenAperturaSend')[0].files[0]

        $("#preloadSendAperturaTP").html(`<div id="carga_person">
                                        <div class="loader">Loading...</div>
                                    </div>`)
        $("#contentFormAperturaSend").addClass("d-none")

        let formData = new FormData(); 

        formData.append('fechaDeApertura',fechaDeApertura);
        formData.append('hora',hora);
        formData.append('tecnico',tecnico);
        formData.append('carnetTecnico',carnetTecnico);
        formData.append('supervisorContrata',supervisorContrata);
        formData.append('celSupContrata',celSupContrata);
        formData.append('telefono',telefono);
        formData.append('contrata',contrata);
        formData.append('imagenEstado',imagenEstado);
        formData.append('observaciones',observaciones);

        $.ajax({
            url:`/administrador/trabajos-programados/${item}/aperturar`,
            method:"post",
            async: true,
            data:formData,
            cache: false, 
            contentType: false,
            processData: false
            /*data: {
                fechaDeApertura,
                hora,
                tecnico,
                carnetTecnico,
                supervisorContrata,
                celSupContrata,
                telefono,
                contrata,
                observaciones
            },
            dataType: "json", */
        })
        .done(function(data){
             console.log("la data return detalle ses: ",data)

            $("#preloadSendAperturaTP").html(``)
            $("#contentFormAperturaSend").removeClass("d-none")
                $("#preloadSendAperturaTP").html(data)
               // return false
            $("#form_apertura_send").html("")
            
            $("#body-success-modal").html(`<div class="w-100 text-center text-success">Se aperturó correctamente el trabajo programado.</div>`)
            $("#successModal").modal("show")

            limpia.limpiaFormAperturaTP() 
            $("#observacionesAperturaSend").html("")

              
            let valorFiltroZona = $("#listaZonasFiltro").val()
            let parametros = {'zona':valorFiltroZona}
            let columnas = peticiones.armandoColumnasTP()
            peticiones.loadTrabajosProgramadosList(columnas,parametros)

            peticiones.redirectTabs($('#listaTrabajoPTab'))  

 
        })
        .fail(function(jqXHR, textStatus){

            $("#preloadSendAperturaTP").html(``)
            $("#contentFormAperturaSend").removeClass("d-none")
            
    
            //console.log( "Error: " ,jqXHR, textStatus);  
             // $("#preloadSendAperturaTP").html(jqXHR.responseText)
             //return false

           
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
            
            erroresPeticion = (erroresPeticion.trim() == 0) ? "hubo un error en el servicio, intente nuevamente." : erroresPeticion
     
            $("#preloadSendAperturaTP").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            ${erroresPeticion}</div>`)

            return false
    
        }) 


    })

    function validacionContinueApertura(){

        
        let fechaAperturaSend = $("#fechaAperturaSend")
        let horaAperturaSend = $("#horaAperturaSend")
        let tecnicoAperturaSend = $("#tecnicoAperturaSend")
        let celSupContrataAperturaSend = $("#celSupContrataAperturaSend")
        let telefonoAperturaSend = $("#telefonoAperturaSend")
        let contrataAperturaSend = $("#contrataAperturaSend")
        let imagenEstado = $('#imagenAperturaSend')[0].files[0] || ""

        
       
       
        $(".validateText").removeClass("valida-error-input") 
        $(".validateSelect").removeClass("valida-error-input")
        $("#errorValidacionApertura").html(``)
 
        if(!valida.isValidText(fechaAperturaSend.val())){
            valida.isValidateInputText(fechaAperturaSend)
            $("#errorValidacionApertura").html(`El campo Fecha Apertura es requerida`)
            return false
        }

        if(!valida.isValidText(horaAperturaSend.val())){
            valida.isValidateInputText(horaAperturaSend)
            $("#errorValidacionApertura").html(`El campo Hora Apertura es requerida`)
            return false
        } 

        if(tecnicoAperturaSend.val().toLowerCase() == "seleccionar"){
            valida.isValidateInputText(tecnicoAperturaSend)
            $("#errorValidacionApertura").html(`Seleccione un técnico válido`)
            return false
        }

        if (celSupContrataAperturaSend.val() != "") {
            if(!valida.isValidNumber(celSupContrataAperturaSend.val())){
                valida.isValidateInputText(celSupContrataAperturaSend)
                $("#errorValidacionApertura").html(`El campo Celular Supervisor Contrata debe ser de formato numérico`)
                return false
            }

            if(celSupContrataAperturaSend.val().length > 10 || celSupContrataAperturaSend.val().length < 7){
                valida.isValidateInputText(celSupContrataAperturaSend)
                $("#errorValidacionApertura").html(`El campo Celular Supervisor Contrata debe tener una logintud entre [7 - 10]`)
                return false
              }

        }

        if (telefonoAperturaSend.val() != "") {
            if(!valida.isValidNumber(telefonoAperturaSend.val())){
                valida.isValidateInputText(telefonoAperturaSend)
                $("#errorValidacionApertura").html(`El campo Telefono Técnico debe ser de formato numérico`)
                return false
            }
            if(telefonoAperturaSend.val().length > 10 || telefonoAperturaSend.val().length < 7){
                valida.isValidateInputText(telefonoAperturaSend)
                $("#errorValidacionApertura").html(`El campo Telefono Técnico debe tener una logintud entre [7 - 10]`)
                return false
            }
        }

        if(contrataAperturaSend.val().toLowerCase() == "seleccionar"){
            valida.isValidateInputText(contrataAperturaSend)
            $("#errorValidacionApertura").html(`Seleccione una Contrara válida`)
            return false
        }

        if(imagenEstado == ""){
            valida.isValidateInputText($("#info_detalle_imagenApertura"))
            $("#errorValidacionApertura").html(`La imagen estado es requerida.`)
            return false
        }

        $(".validateText").removeClass("valida-error-input")
        $(".validateSelect").removeClass("valida-error-input")
        $("#errorValidacionApertura").html(``)
       
        return true
        

    }

})