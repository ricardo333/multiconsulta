import valida from  "@/globalResources/forms/valida.js"
import limpia from  "@/globalResources/forms/limpia.js"
import errors from  "@/globalResources/errors.js"

const gestionarFile = {}

gestionarFile.file = function file(file,loadMensaje)
{
        $(loadMensaje).html(``)
        if(file){
            let imagen_detalle = file
    
            //$("#validacionServicio_load").addClass("d-none")
            $(loadMensaje).html(`<div class="w-100 d-flex justify-content-center">
                                            <div class="spinner-grow text-primary" role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                        </div>`)

            let reader = new FileReader();
            reader.onload = function(e) {
                //console.log("el load es:",e)
                ////console.log(e.target.result) 
                // $('#file_preview_totalUpdate').attr('src', e.target.result); 
                //$("#validacionServicio_load").removeClass("d-none")
                $(loadMensaje).html(`<div class="w-100 text-center"><strong>Archivo Seleccionado : </strong>${imagen_detalle["name"]}</div>`)
            }
            
            reader.readAsDataURL(imagen_detalle)
        } 

}

gestionarFile.getFile = function getFile(msgSubirFile,idFile)
{

    //$("#subirMasiva_load").addClass("d-none")
    $(msgSubirFile).html(`<div class="d-flex justify-content-center align-content-center flex-wrap w-100">
        <div class="spinner-border" role="status" style="width: 150px;height: 150px;">
        <span class="sr-only">Loading...</span>
        </div>
        <div class="text-center w-100">
            <strong>Validando Datos</strong>
        </div>
    </div>`)

    let archivoValidaServicio = $(idFile)[0].files[0]

    let formData = new FormData();
    formData.append('archivo',archivoValidaServicio);
    formData.append('exportData',false);
    //console.log("la data a enviar es: ",formData)
    return formData;

}

gestionarFile.sendMensajeFile = function sendMensajeFile(res,msgSubirFile)
{

        if(res.error == "failed"){

            //$("#subirMasiva_load").removeClass("d-none")
            $(msgSubirFile).html(``)

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

            $(msgSubirFile).html(`<div class="col-12 text-danger text-center text-sm font-italic">${erroresPeticion}</div>`); 
            //$(msgSubirFile).html(`<div class="col-12 text-danger text-center text-sm font-italic">ERROR PRUEBA</div>`); 
            
            
            return false 
          
        }


        let procesoRpta = res.response
        //console.log(procesoRpta);
        
        if (procesoRpta.procesoResult == false) {
            
            if (parseInt(procesoRpta.cantidadErrores) > 0) {

                //$("#subirMasiva_load").removeClass("d-none")
                    $(msgSubirFile).html(``)

                    let msjErrors = `<p style="color:#004c54;"><b>Lista de Registros Observados</b></p>`
                    //procesoRpta.errores.forEach(el => {
                    
                    msjErrors += `<table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">ClienteCMS</th>
                                            <th scope="col">Mensaje</th>
                                        </tr>
                                    </thead>
                                    <tbody>`
                    procesoRpta.dataProcesar.forEach(el => {
                        msjErrors += `<tr><td>${el[0]}</td><td>${(el[1])?el[1]:''}</td></tr>`
                    });
                    msjErrors += "</tbody></table>"
    
                    $(msgSubirFile).html(`<div class="w-100">
                                                        ${msjErrors} <br>
                                                        <div class="w-100">
                                                            <div class="w-100 text-center">
                                                            <span>Corregir los registros observados e intentar nuevamente subir el archivo.</span>
                                                            </div>
                                                        </div> 
                                                     </div>`)
                   // $("#buttons_validacionesServ").removeClass("d-none")
    
                    return false
    
            }else{          

                limpia.limpiaHtml($(msgSubirFile))
                limpia.limpiaFormValidaServicio() 
                //$("#nameFileValidate").html(``) 
 
                //$("#subirMasiva_load").addClass("d-none")

                $(msgSubirFile).html(`<div class="text-center w-100">
                                                        <strong>Los Datos se han cargado exitosamente</strong>
                                                    </div>`)

                return false

            }

        }

}

export default gestionarFile