import peticiones from './peticiones.js'
import errors from  "@/globalResources/errors.js"
import valida from  "@/globalResources/forms/valida.js"
import limpia from  "@/globalResources/forms/limpia.js"

$(function(){

    $("body").on("click",".cerrarTP", function(){
       
        //
        let item = $(this).data("uno")

        $("#preloadCerrarTrabajoProg").html(`<div id="carga_person">
                                                    <div class="loader">Loading...</div>
                                                </div>`)
        $("#contentDetalleTPCierre").html(`<div id="carga_person">
                                                    <div class="loader">Loading...</div>
                                                </div>`)
        $("#form_cierre_send").html(`<div id="carga_person">
                                                    <div class="loader">Loading...</div>
                                                </div>`)

        $("#contentAcordionCerrarTP").addClass("d-none")

        peticiones.redirectTabs($("#CerrarTrabajoProgtab"))

        $.ajax({
            url:`/administrador/trabajos-programados/${item}/detalle`,
            method:"get",
            data: {
                "formulario":"CIERRE"
            },
            dataType: "json", 
        })
        .done(function(data){
          //  console.log("la data return detalle ses: ",data)

           let resultadoDetalle = data.response.data
           let resultCierre = data.response.dataCierre

           let armandoEstructuraDetalle = `  
               <div class="form row my-2 mx-0" id="contentFormDetalleCierre">`

           armandoEstructuraDetalle += peticiones.armaEstructuraDetalleTP(resultadoDetalle)
                   
           armandoEstructuraDetalle += `</div>` 

           let armandoEstructuraFormulario = `
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label for="trobasHijasCierreSend">Trobas Hijas:</label>
                                                    </div>
                                                    <div class="col-md-8"> 
                                                        <input type="text" id="trobasHijasCierreSend" name="trobasHijasCierreSend"  class="form-control form-control-sm shadow-sm validateText">
                                                    </div>
                                                </div>
                                            </div>
                                           <div class="form-group">
                                               <div class="row">
                                                   <div class="col-md-4">
                                                       <label for="fechaCierreSend">Fecha de Cierre:</label>
                                                   </div>
                                                   <div class="col-md-8">
                                                       <input type="date" name="fechaCierreSend" id="fechaCierreSend" step="1" 
                                                           min="${resultCierre.fechaCierre}"  value="${resultCierre.fechaCierre}"  class="form-control form-control-sm shadow-sm validateText">
                                                   </div>
                                               </div>
                                           </div>
                                           <div class="form-group">
                                               <div class="row">
                                                   <div class="col-md-4">
                                                       <label for="horaCierreSend">Hora de Cierre:</label>
                                                   </div>
                                                   <div class="col-md-8">
                                                           <input type="time" name="horaCierreSend" id="horaCierreSend" min="${resultCierre.hora}:00"
                                                           max="23:00" step="3600" value="${resultCierre.hora}:00"  class="form-control form-control-sm shadow-sm validateText"> 
                                                   </div>
                                               </div>
                                           </div>
                                           <div class="form-group">
                                               <div class="row">
                                                   <div class="col-md-4">
                                                       <label for="trabajosCierreSend">Trabajo:</label>
                                                   </div>
                                                   <div class="col-md-8">
                                                           <select name="trabajosCierreSend" id="trabajosCierreSend"  class="form-control form-control-sm shadow-sm validateSelect">
                                                               <option value="seleccionar">Seleccionar</option> 
                                                           `
                               resultCierre.trabajos.forEach(el => {
                                       armandoEstructuraFormulario +=      `<option value="${el.TRABAJO}">${el.TRABAJO}</option>`
                               })
                   
                               armandoEstructuraFormulario +=   `
                                                           </select>
                                                   </div>
                                               </div>
                                           </div>
                                           <div class="form-group">
                                               <div class="row">
                                                   <div class="col-md-4">
                                                       <label for="tecnicoCierreSend">Técnico:</label>
                                                   </div>
                                                   <div class="col-md-8">
                                                           <select name="tecnicoCierreSend" id="tecnicoCierreSend"  class="form-control form-control-sm shadow-sm validateSelect">
                                                               <option value="seleccionar">Seleccionar</option> 
                                                           `
                               resultCierre.tecnicos.forEach(el => {
                                       armandoEstructuraFormulario +=      `<option value="${el.TECNICO}">${el.TECNICO}</option>`
                               })
                   
                               armandoEstructuraFormulario +=   `
                                                           </select>
                                                   </div>
                                               </div>
                                           </div>
                                           <div class="form-group">
                                               <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <label for="carnetTecCierreSend">Carnet Técnico:</label>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <input type="text" id="carnetTecCierreSend" name="carnetTecCierreSend"  class="form-control form-control-sm shadow-sm validateText">
                                                            </div>
                                                        </div>
                                                        <div class="row mt-3">
                                                            <div class="col-md-12">
                                                                <label for="telefonoCierreSend">Teléfono Técnico:</label>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <input type="text" id="telefonoCierreSend" name="telefonoCierreSend"  class="form-control form-control-sm shadow-sm validateText">
                                                            </div>
                                                        </div>
                                                        <div class="row my-3">
                                                            <div class="col-md-12">
                                                                <label for="contrataCierreSend">Contrata:</label>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <select name="contrataCierreSend" id="contrataCierreSend"  class="form-control form-control-sm shadow-sm validateSelect">
                                                                    <option value="seleccionar">Seleccionar</option> 
                                                                    `
                                                                    resultCierre.contratas.forEach(el => {
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
                                                                <label for="imagenCierreSend" class="col-form-label col-form-label-sm btn btn-outline-info btn-sm d-flex align-items-center justify-content-center">Imagen Estado: </label>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <figure id="info_detalle_imagenCierre" class="card mt-1 figure figura_aper_cierre_image"> 
                                                                    <img id="file_preview_cierre" class="img-cierre" src="/images/upload/trabajos-programados/sinimagen.png">
                                                                    <figcaption id="text_preview_cierre" class="figure-caption text-right">Sin imagen</figcaption>
                                                                </figure>  
                                                                <input type="file" id="imagenCierreSend" name="imagenCierreSend"  class="d-none">
                                                            </div>
                                                        </div>
                                                    </div>
                                               </div>
                                           </div>
                                           <div class="form-group">
                                               <div class="row">
                                                   <div class="col-md-4">
                                                       <label for="observacionesCierreSend">Observaciones:</label>
                                                   </div>
                                                   <div class="col-12">
                                                       <textarea name="observacionesCierreSend" id="observacionesCierreSend" cols="30" rows="4" class="form-control form-control-sm shadow-sm validateText"></textarea>
                                                   </div>
                                               </div>
                                           </div>
                                           <div class="form-group">
                                               <div class="row">  
                                                   <div class="col-12 text-center text-danger" id="errorValidacionCierre">
                                                       
                                                   </div>
                                               </div>
                                           </div>
                                           <div class="form-group">
                                               <div class="row">  
                                                   <div class="col-12 text-center">
                                                       <a href="javascript: void(0)" data-uno="${resultadoDetalle.ITEM}" data-dos="${resultadoDetalle.HINICIO}" id="cerrarTPSend" class="btn btn-sm btn-outline-success shadow-sm">Cerrar Trabajo Programado</a>
                                                   </div>
                                               </div>
                                           </div>

                                       `

           $("#contentDetalleTPCierre").html(armandoEstructuraDetalle)
           $("#form_cierre_send").html(armandoEstructuraFormulario)

           $("#preloadCerrarTrabajoProg").html(``) 
           $("#contentAcordionCerrarTP").removeClass("d-none")

      })
      .fail(function(jqXHR, textStatus){

       
           $("#preloadCerrarTrabajoProg").html(``) 
           $("#contentDetalleTPCierre").html("")
           $("#form_cierre_send").html("")
           $("#contentAcordionCerrarTP").removeClass("d-none")

            //console.log( "Error: " ,jqXHR, textStatus); 
            //$("#preloadCerrarTrabajoProg").html(jqXHR.responseText)
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
   
           $("#preloadCerrarTrabajoProg").html(`<div class="w-100 text-danger justify-content-center">${erroresPeticion}</div>`) 
           return false
     
       }) 


    })

    $("body").on("change","#imagenCierreSend",function(){

        //console.log($(this)[0].files[0])

        if($(this)[0].files[0]){
            let imagen_detalle = $(this)[0].files[0]
 
            // 
            $("#text_preview_cierre").html(`<div class="d-flex justify-content-center">
                                                        <div class="spinner-grow text-primary" role="status">
                                                            <span class="sr-only">Loading...</span>
                                                        </div>
                                                    </div>`) 

            let reader = new FileReader();
            reader.onload = function(e) {
                 //console.log("el load es:",e)
                ////console.log(e.target.result) 
                $('#file_preview_cierre').attr('src', e.target.result); 
               $("#text_preview_cierre").html(imagen_detalle["name"])
            }
            reader.readAsDataURL(imagen_detalle)

        }else{ 
            
            $('#file_preview_cierre').attr('src', "/images/upload/trabajos-programados/sinimagen.png"); 
            $("#text_preview_cierre").html("Sin imagen")

        }
    })

    $("body").on("click","#cerrarTPSend", function(){

        let validacionCierreTP = validacionContinueCierre()
        if(!validacionCierreTP){ 
            return false
        }
         
        let item = $(this).data("uno")
        let horaDeInicio = $(this).data("dos")
        
        
        let trobasHijas = $("#trobasHijasCierreSend").val()
        let fechaDeCierre = $("#fechaCierreSend").val()
        let horaDeCierre = $("#horaCierreSend").val()
        let trabajo = $("#trabajosCierreSend").val()
        let tecnico = $("#tecnicoCierreSend").val()
        let carnetTecnico = $("#carnetTecCierreSend").val()
        let telefonoTecnico = $("#telefonoCierreSend").val()
        let contrata = $("#contrataCierreSend").val()
        let observaciones = $("#observacionesCierreSend").val()
        let imagenEstado = $('#imagenCierreSend')[0].files[0]

        $("#preloadSendCierreTP").html(`<div id="carga_person">
                                            <div class="loader">Loading...</div>
                                        </div>`)
        $("#contentFormCierreSend").addClass("d-none")

        let formData = new FormData(); 

        formData.append('horaDeInicio',horaDeInicio);
        formData.append('trobasHijas',trobasHijas);
        formData.append('fechaDeCierre',fechaDeCierre);
        formData.append('horaDeCierre',horaDeCierre);
        formData.append('trabajo',trabajo);
        formData.append('tecnico',tecnico);
        formData.append('carnetTecnico',carnetTecnico);
        formData.append('telefonoTecnico',telefonoTecnico);
        formData.append('contrata',contrata);
        formData.append('observaciones',observaciones);
        formData.append('imagenEstado',imagenEstado);


        $.ajax({
            url:`/administrador/trabajos-programados/${item}/cerrar`,
            method:"post",
            async: true,
            data:formData,
            cache: false, 
            contentType: false,
            processData: false
            /*data: {
                trobasHijas,
                fechaDeCierre,
                horaDeCierre,
                trabajo,
                tecnico,
                carnetTecnico,
                telefonoTecnico,
                contrata,
                observaciones,
                horaDeInicio
            },
            dataType: "json", */
        })
        .done(function(data){
             //console.log("la data return detalle ses: ",data)

            $("#preloadSendCierreTP").html(``)
            $("#contentFormCierreSend").removeClass("d-none")
            $("#form_cierre_send").html("")
            
            $("#body-success-modal").html(`<div class="w-100 text-center text-success">Se cerró correctamente el trabajo programado.</div>`)
            $("#successModal").modal("show")

            limpia.limpiaFormCierreTP() 
            $("#observacionesCierreSend").html("")

              
            let valorFiltroZona = $("#listaZonasFiltro").val()
            let parametros = {'zona':valorFiltroZona}
            let columnas = peticiones.armandoColumnasTP()
            peticiones.loadTrabajosProgramadosList(columnas,parametros)

            peticiones.redirectTabs($('#listaTrabajoPTab')) 

 
        })
        .fail(function(jqXHR, textStatus){

            $("#preloadSendCierreTP").html(``)
            $("#contentFormCierreSend").removeClass("d-none")
            
    
            //console.log( "Error: " ,jqXHR, textStatus);  
            // $("#preloadSendCierreTP").html(jqXHR.responseText)
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
     
            $("#preloadSendCierreTP").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            ${erroresPeticion}</div>`)

            return false
    
        }) 

    })

    function validacionContinueCierre()
    {
  
        let trabajo = $("#trabajosCierreSend")
        let tecnico = $("#tecnicoCierreSend") 
        let telefonoTecnico = $("#telefonoCierreSend")
        let contrata = $("#contrataCierreSend")
        let imagenEstado = $('#imagenCierreSend')[0].files[0] || ""
        
       
        $(".validateText").removeClass("valida-error-input") 
        $(".validateSelect").removeClass("valida-error-input")
        $("#errorValidacionCierre").html(``)

        if(trabajo.val().toLowerCase() == "seleccionar"){
            valida.isValidateInputText(trabajo)
            $("#errorValidacionCierre").html(`Seleccione un trabajo válido`)
            return false
        }
        if(tecnico.val().toLowerCase() == "seleccionar"){
            valida.isValidateInputText(tecnico)
            $("#errorValidacionCierre").html(`Seleccione un técnico válido`)
            return false
        }

        if (telefonoTecnico.val() != "") {
            if(!valida.isValidNumber(telefonoTecnico.val())){
                valida.isValidateInputText(telefonoTecnico)
                $("#errorValidacionCierre").html(`El campo Telefono Técnico debe ser de formato numérico`)
                return false
            }
            if(telefonoTecnico.val().length > 10 || telefonoTecnico.val().length < 7){
                valida.isValidateInputText(telefonoTecnico)
                $("#errorValidacionCierre").html(`El campo Telefono Técnico debe tener una logintud entre [7 - 10]`)
                return false
            }
        }

        if(contrata.val().toLowerCase() == "seleccionar"){
            valida.isValidateInputText(contrata)
            $("#errorValidacionCierre").html(`Seleccione una contrata válida`)
            return false
        }

        if(imagenEstado == ""){
            valida.isValidateInputText($("#info_detalle_imagenCierre"))
            $("#errorValidacionCierre").html(`La imagen estado es requerida.`)
            return false
        }

        $(".validateText").removeClass("valida-error-input")
        $(".validateSelect").removeClass("valida-error-input")
        $("#errorValidacionCierre").html(``)
       
        return true


    }

})