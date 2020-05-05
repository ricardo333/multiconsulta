import peticiones from './peticiones.js'
import limpia from  "@/globalResources/forms/limpia.js"
import errors from  "@/globalResources/errors.js"

$(function(){

    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });


    $("#return_history").click(function(){
        console.log("retorna historial-...")
        window.history.back();
    })


    $("body").on("change","#fileLoadValidaMasiva", function(){

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
                $("#nameFileValidate").html(`<div class="w-100 text-center"><strong>Archivo Seleccionado : </strong>${imagen_detalle["name"]}</div>`)
            }
            
            reader.readAsDataURL(imagen_detalle)

        } 

    })



    $("#subirArchivoMas").click(function(){

        $("#subirMasiva_load").addClass("d-none")
        $("#rpta_validacionMasi").html(`<div class="d-flex justify-content-center align-content-center flex-wrap w-100">
                        <div class="spinner-border" role="status" style="width: 150px;height: 150px;">
                        <span class="sr-only">Loading...</span>
                        </div>
                        <div class="text-center w-100">
                            <strong>Validando Datos</strong>
                        </div>
                    </div>`)
 
        let archivoValidaServicio = $('#fileLoadValidaMasiva')[0].files[0]
        //let tipoServicio = $("#type_validacionServ").val()
               
        let formData = new FormData();
        //formData.append('tipoDeValidacion',tipoServicio);
        formData.append('archivo',archivoValidaServicio);
        formData.append('exportData',false);

        console.log("la data a enviar es: ",formData)

        procesarDataProcesoValidacion(formData)

    })



    function procesarDataProcesoValidacion(formData)
    {
        peticiones.loadArchivoServicio(formData,function(res){
           
            if(res.error == "failed"){

                $("#subirMasiva_load").removeClass("d-none")
                $("#rpta_validacionMasi").html(``)

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

                //$("#rpta_validacionMasi").html(`<div class="col-12 text-danger text-center text-sm font-italic">${erroresPeticion}</div>`); 
                $("#rpta_validacionMasi").html(`<div class="col-12 text-danger text-center text-sm font-italic">ERROR PRUEBA</div>`); 
                
                
                return false 
              
            }


            let procesoRpta = res.response

            if (procesoRpta.procesoResult == false) {

                
                if (parseInt(procesoRpta.cantidadObservados) > 0) {

                    $("#subirMasiva_load").removeClass("d-none")
                        $("#rpta_validacionMasi").html(``)

                        let msjErrors = `<span>Registros Observados (Una coma de mas)</span>`
                            msjErrors += `<ul class="list-unstyled text-sm text-center text-danger font-italic">`
                        procesoRpta.errores.forEach(el => {
                            msjErrors += `<li>${el}</li>`
                        });
                        msjErrors += "</ul>"
        
                        $("#rpta_validacionMasi").html(`<div class="w-100">
                                                            ${msjErrors} <br>
                                                            <div class="w-100">
                                                                <div class="w-100 text-center">
                                                                    <span>Favor de corregir registros e intentar nuevamente.</span>
                                                                </div>
                                                            </div> 
                                                         </div>`)
                        
                        return false
        
                }
                

                
                if (parseInt(procesoRpta.cantidadErrores) > 0) {

                    $("#subirMasiva_load").removeClass("d-none")
                        $("#rpta_validacionMasi").html(``)

                        let msjErrors = `<span>Registros Observados (mayores registros: "coma en campo Observacion")</span><br>`
                            msjErrors = `<ul class="list-unstyled text-sm text-center text-danger font-italic">`
                        //procesoRpta.errores.forEach(el => {
                        procesoRpta.registro.forEach(el => {
                            msjErrors += `<li>${el}</li>`
                        });
                        msjErrors += "</ul>"
        
                        $("#rpta_validacionMasi").html(`<div class="w-100">
                                                            ${msjErrors} <br>
                                                            <div class="w-100">
                                                                <div class="w-100 text-center">
                                                                    <a href="javascript:void(0)" id="procesarDataValidacion" class="btn btn-sm btn-outline-success shadow-sm m-1">Procesar Data de todas maneras.</a>
                                                                    <a href="javascript:void(0)" id="reProcesarValidacion" class="btn btn-sm btn-outline-success shadow-sm m-1">Cancelar proceso.</a>
                                                                </div>
                                                            </div> 
                                                         </div>`)
                       // $("#buttons_validacionesServ").removeClass("d-none")
        
                        return false
        
                }else{          

                    limpia.limpiaHtml($("#rpta_validacionMasi"))
                    limpia.limpiaFormValidaServicio() 
                    $("#nameFileValidate").html(``) 
     
                    $("#subirMasiva_load").addClass("d-none")
    
                    $("#rpta_validacionMasi").html(`<div class="text-center w-100">
                                                            <strong>Carga de Masiva Terminado</strong>
                                                        </div>`)
    
                    return false
    
                }


            }
            
            
            /*
            else{

                limpia.limpiaHtml($("#rpta_validacionMasi"))
                limpia.limpiaFormValidaServicio() 
                $("#nameFileValidate").html(``) 
 
                $("#subirMasiva_load").addClass("d-none")

                $("#rpta_validacionMasi").html(`<div class="text-center w-100">
                                                        <strong>Carga de Masiva Terminado</strong>
                                                    </div>`)

                return false

            }
            */

            
        })

    }











})