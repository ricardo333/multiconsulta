import peticiones from './peticiones.js'
import limpia from  "@/globalResources/forms/limpia.js"
import errors from  "@/globalResources/errors.js"

$(function(){
 
    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

     
 
    $("body").on("change","#fileLoadValidaServicio", function(){

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
                $("#nameFileValidate").html(`<div class="w-100 text-center"><strong>Archivo Seleccionado : </strong>${imagen_detalle["name"]}</div>`)
            }
            reader.readAsDataURL(imagen_detalle)

        } 
 

    })

    $("#subirArchivoVal").click(function(){

        $("#validacionServicio_load").addClass("d-none")
        $("#rpta_validacionServ").html(`<div class="d-flex justify-content-center align-content-center flex-wrap w-100">
                        <div class="spinner-border" role="status" style="width: 150px;height: 150px;">
                        <span class="sr-only">Loading...</span>
                        </div>
                        <div class="text-center w-100">
                            <strong>Validando Datos</strong>
                        </div>
                    </div>`)
 
        let archivoValidaServicio = $('#fileLoadValidaServicio')[0].files[0]
        let tipoServicio = $("#type_validacionServ").val()
               
        let formData = new FormData(); 
        formData.append('tipoDeValidacion',tipoServicio);
        formData.append('archivo',archivoValidaServicio);
        formData.append('exportData',false);

        //console.log("la data a enviar es: ",formData)

        procesarDataProcesoValidacion(formData)

        
          
    })

    function procesarDataProcesoValidacion(formData)
    {
  
        peticiones.loadArchivoServicio(formData,function(res){
           //console.log("la respuesta es: ",res)
           //$("#validacionServicio_load").removeClass("d-none")
            if(res.error == "failed"){
                //$("#rpta_validacionServ").html(``)
                //console.log("Error: ",res.errorThrown,res.jqXHR,res.textStatus) 
                //$("#rpta_validacionServ").html(`<div class="col-12 text-danger text-center">${res.jqXHR.responseText}</div>`); 
                //return false

                  $("#validacionServicio_load").removeClass("d-none")
                  $("#rpta_validacionServ").html(``)

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

                $("#rpta_validacionServ").html(`<div class="col-12 text-danger text-center text-sm font-italic">${erroresPeticion}</div>`); 
                return false 
              
             }

            // console.log("la respuesta es pasando falied..: ",res,"-----")
             $("#rpta_validacionServ").html(`<div class="col-12 text-danger text-center text-sm font-italic">${res}</div>`); 

             let procesoRpta = res.response

            if (procesoRpta.procesoResult == false) {
                if (parseInt(procesoRpta.cantidadErrores) > 0) {

                        $("#validacionServicio_load").removeClass("d-none")
                        $("#rpta_validacionServ").html(``)

                        let msjErrors = `<ul class="list-unstyled text-sm text-center text-danger font-italic">`
                        procesoRpta.errores.forEach(el => {
                            msjErrors += `<li>${el}</li>`
                        });
                        msjErrors += "</ul>"
        
                        $("#rpta_validacionServ").html(`<div class="w-100">
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
        
                }

                //Deberia cambiar el Load a Procesando Data

                $("#validacionServicio_load").addClass("d-none")
                $("#rpta_validacionServ").html(`<div class="d-flex justify-content-center align-content-center flex-wrap w-100">
                        <div class="spinner-border" role="status" style="width: 150px;height: 150px;">
                        <span class="sr-only">Loading...</span>
                        </div>
                        <div class="text-center w-100">
                            <strong>Procesando Data</strong>
                        </div>
                    </div>`)
 
                    let tipoServicio = $("#type_validacionServ").val()
                        
                    let formData = new FormData(); 
                    formData.append('tipoDeValidacion',tipoServicio);
                    formData.append('exportData',true);

                    //console.log("la data a enviar nuevamente es: ",formData)

                    procesarDataProcesoValidacion(formData)

                    return false
                 
            }else{
                //console.log("el resultado si se proceso en el servidor, estas en true.......")


                limpia.limpiaHtml($("#rpta_validacionServ"))
                limpia.limpiaFormValidaServicio() 
                $("#nameFileValidate").html(``) 
 
                $("#validacionServicio_load").addClass("d-none")
                $("#rpta_validacionServ").html(`<div class="d-flex justify-content-center align-content-center flex-wrap w-100">
                        <div class="spinner-border" role="status" style="width: 150px;height: 150px;">
                        <span class="sr-only">Loading...</span>
                        </div>
                        <div class="text-center w-100">
                            <strong>Preparando descarga de archivo</strong>
                        </div>
                    </div>`)
 
               // window.location = procesoRpta.ruta
               // window.open(`procesoRpta.ruta`, '_blank');

               let nombreArchivo = procesoRpta.nombre

               $.ajax({
                xhrFields: { responseType: 'blob', },
                url: `${procesoRpta.ruta}`,
                method: 'get',
                cache: false,
                })
                .done(function(result){

                    $("#validacionServicio_load").removeClass("d-none")
                    $("#rpta_validacionServ").html(``)
                     
                   // console.log("La respuesta de descarga es con blob: ",result)
                    var blob = new Blob([result], {
                        type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                    });
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = nombreArchivo;
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                }) 
                .fail(function(xhr, jqXHR, textStatus) {
                  
                   // console.log("Error:",xhr, jqXHR, textStatus)
                    $("#validacionServicio_load").removeClass("d-none")
                    $("#rpta_validacionServ").html(``)

                    let erroresPeticion =""
                    if(jqXHR.status){
                        erroresPeticion = errors.codigos(jqXHR.status) 
                    }
                    if(jqXHR.responseJSON){
                        if(jqXHR.responseJSON.mensaje){
                            let erroresMensaje = jqXHR.responseJSON.mensaje  //captura objeto
                            let mensaje = errors.mensajeErrorJson(erroresMensaje)
                            erroresPeticion  =  `<strong>${mensaje}</strong>`+":" + "<br> " + erroresPeticion  
                        } 
                    }
                    erroresPeticion = (erroresPeticion.trim() == 0) ? "hubo un error en el servicio, intente nuevamente." : erroresPeticion
    
                    $("#rpta_validacionServ").html(`<div class="col-12 text-danger text-center text-sm font-italic">${erroresPeticion}</div>`); 

                 
                });

                //return false

            }
            
        })

    }

    $("body").on("click","#procesarDataValidacion", function(){
  
        //console.log("aqui el evento click...") 

        $("#validacionServicio_load").addClass("d-none")
        $("#rpta_validacionServ").html(`<div class="d-flex justify-content-center align-content-center flex-wrap w-100">
                <div class="spinner-border" role="status" style="width: 150px;height: 150px;">
                <span class="sr-only">Loading...</span>
                </div>
                <div class="text-center w-100">
                    <strong>Procesando Data</strong>
                </div>
            </div>`)


        let tipoServicio = $("#type_validacionServ").val() 
        let formData = new FormData(); 
        formData.append('tipoDeValidacion',tipoServicio);
        formData.append('exportData',true);

        //console.log("la data a enviar nuevamente es: ",formData)

        procesarDataProcesoValidacion(formData)

    })

    $("body").on("click","#reProcesarValidacion", function(){
        //console.log("Se cancelará proceso") 
        limpia.limpiaHtml($("#rpta_validacionServ"))
        limpia.limpiaFormValidaServicio() 
        $("#nameFileValidate").html(``) 
    })

   

})