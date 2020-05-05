import peticiones from './peticiones.js'
import errors from  "@/globalResources/errors.js"

var _THIS_EDIT = `` 
$(function(){

    $("body").on("click",".editDecisionArbol", function(){


        let valorId = $(this).data("uno")
        let detalle = $(this).data("dos")
        let nombre_imagen_total = $(this).data("tres")
        let nombre_imagen_negocio = $(this).data("cuatro")
        let nombre_imagen_masiva = $(this).data("cinco")
        let decision = $(this).data("seis")

        $("#formEditarEstructuraDecision").html(`<div class="form-group errors" id="errorUpdateDecision">
                                            </div>
                                            <div class="form-group">
                                                <label for="textoTableDecisionUpdate" class="col-form-label col-form-label-sm font-weight-bold pr-2">Texto: </label>
                                                <input type="text" class="form-control form-control-sm shadow-sm input-texto" id="textoTableDecisionUpdate" value="${detalle}">
                                                
                                            </div>
                                            
                                            <div class="form-group row">
                                                <div class="col-6 col-sm-4 col-md-4">
                                                    <label for="imagenTableTotalUpdate" class="col-form-label col-form-label-sm btn btn-outline-info btn-sm d-flex align-items-center justify-content-center p-0">
                                                        <i class="icofont-cloud-upload icofont-2x"></i> Imagen Total
                                                    </label>  
                                                    <figure id="info_detalle_imagenTableTotalUpdate" class="card mt-1 figure figura_create_image"> 
                                                        <img id="filePreview_imagenTableTotalUpdate" class="figure-img img-fluid rounded" src="/images/upload/arbol-decisiones/${nombre_imagen_total}">
                                                        <figcaption id="textPreview_imagenTableTotalUpdate" class="figure-caption text-right">${nombre_imagen_total}</figcaption>
                                                    </figure> 
                                                    <input type="file"  id="imagenTableTotalUpdate" class="d-none"> 
                                                </div> 
                                                <div class="col-6 col-sm-4 col-md-4">
                                                    <label for="imagenTableNegocioUpdate" class="col-form-label col-form-label-sm btn btn-outline-info btn-sm d-flex align-items-center justify-content-center p-0">
                                                        <i class="icofont-cloud-upload icofont-2x"></i> Imagen Negocio
                                                    </label>  
                                                    <figure id="info_detalle_imagenTableNegocioUpdate" class="card mt-1 figure figura_create_image"> 
                                                        <img id="filePreview_imagenTableNegocioUpdate" class="figure-img img-fluid rounded" src="/images/upload/arbol-decisiones/${nombre_imagen_negocio}">
                                                        <figcaption id="textPreview_imagenTableNegocioUpdate" class="figure-caption text-right">${nombre_imagen_negocio}</figcaption>
                                                    </figure> 
                                                    <input type="file"  id="imagenTableNegocioUpdate" class="d-none"> 
                                                </div>
                                                <div class="col-6 col-sm-4 col-md-4">
                                                    <label for="imagenTableMasivaUpdate" class="col-form-label col-form-label-sm btn btn-outline-info btn-sm d-flex align-items-center justify-content-center p-0">
                                                        <i class="icofont-cloud-upload icofont-2x"></i> Imagen Masiva
                                                    </label>  
                                                    <figure id="info_detalle_imagenTableMasivaUpdate" class="card mt-1 figure figura_create_image"> 
                                                        <img id="filePreview_imagenTableMasivaUpdate" class="figure-img img-fluid rounded" src="/images/upload/arbol-decisiones/${nombre_imagen_masiva}">
                                                        <figcaption id="textPreview_imagenTableMasivaUpdate" class="figure-caption text-right">${nombre_imagen_masiva}</figcaption>
                                                    </figure> 
                                                    <input type="file"  id="imagenTableMasivaUpdate" class="d-none"> 
                                                </div>
                                            </div>
                                            
                                            <div class="form-group text-center">
                                                    <a href="javascript:void(0)" class="btn btn-sm btn-outline-success shadow-sm" 
                                                            id="btnUpdateTableRama"
                                                            data-1="${valorId}"
                                                            data-2="${decision}"
                                                            >
                                                        Actualizar
                                                    </a>
                                            </div>`)
        $("#EditarEstructuraDecision").modal("show")
 
    })


    //Editar Arbol Estructura Decision

    $("body").on("click",".editChildTree", function(){

        let padreSelectAdd = $(this).parent(".secundarios")

        _THIS_EDIT = padreSelectAdd

        let valorId = padreSelectAdd.data("id")
        let decision = padreSelectAdd.data("1")
        let detalle = padreSelectAdd[0].dataset.detalle

        let nombre_imagen_total = padreSelectAdd[0].dataset.total
        let nombre_imagen_masiva = padreSelectAdd[0].dataset.masiva
        let nombre_imagen_negocio = padreSelectAdd[0].dataset.negocio

        if (valorId) {
            let formulario = ``

            formulario += `
                            <div class="form-group errors" id="errorUpdateDecision">
                
                            </div>
                            <div class="form-group">
                                <label for="textoDecisionUpdate" class="col-form-label col-form-label-sm font-weight-bold pr-2">Texto: </label>
                                <input type="text" class="form-control form-control-sm shadow-sm input-texto" id="textoDecisionUpdate" value="${detalle}">
                                
                            </div>
                            
                            <div class="form-group row">
                                <div class="col-6 col-sm-4 col-md-4">
                                    <label for="imagenTotalUpdate" class="col-form-label col-form-label-sm btn btn-outline-info btn-sm d-flex align-items-center justify-content-center p-0">
                                        <i class="icofont-cloud-upload icofont-2x"></i> Imagen Total
                                    </label>  
                                    <figure id="info_detalle_imagenTotalUpdate" class="card mt-1 figure figura_create_image"> 
                                        <img id="file_preview_totalUpdate" class="figure-img img-fluid rounded" src="/images/upload/arbol-decisiones/${nombre_imagen_total}">
                                        <figcaption id="text_preview_totalUpdate" class="figure-caption text-right">${nombre_imagen_total}</figcaption>
                                    </figure> 
                                    <input type="file"  id="imagenTotalUpdate" class="d-none"> 
                                </div> 
                                <div class="col-6 col-sm-4 col-md-4">
                                    <label for="imagenNegocioUpdate" class="col-form-label col-form-label-sm btn btn-outline-info btn-sm d-flex align-items-center justify-content-center p-0">
                                        <i class="icofont-cloud-upload icofont-2x"></i> Imagen Negocio
                                    </label>  
                                    <figure id="info_detalle_imagenNegocioUpdate" class="card mt-1 figure figura_create_image"> 
                                        <img id="file_preview_negocioUpdate" class="figure-img img-fluid rounded" src="/images/upload/arbol-decisiones/${nombre_imagen_negocio}">
                                        <figcaption id="text_preview_negocioUpdate" class="figure-caption text-right">${nombre_imagen_negocio}</figcaption>
                                    </figure> 
                                    <input type="file"  id="imagenNegocioUpdate" class="d-none"> 
                                </div>
                                <div class="col-6 col-sm-4 col-md-4">
                                    <label for="imagenMasivaUpdate" class="col-form-label col-form-label-sm btn btn-outline-info btn-sm d-flex align-items-center justify-content-center p-0">
                                        <i class="icofont-cloud-upload icofont-2x"></i> Imagen Masiva
                                    </label>  
                                    <figure id="info_detalle_imagenMasivaUpdate" class="card mt-1 figure figura_create_image"> 
                                        <img id="file_preview_masivaUpdate" class="figure-img img-fluid rounded" src="/images/upload/arbol-decisiones/${nombre_imagen_masiva}">
                                        <figcaption id="text_preview_masivaUpdate" class="figure-caption text-right">${nombre_imagen_masiva}</figcaption>
                                    </figure> 
                                    <input type="file"  id="imagenMasivaUpdate" class="d-none"> 
                                </div>
                            </div>
                             
                            <div class="form-group text-center">
                                    <a href="javascript:void(0)" class="btn btn-sm btn-outline-success shadow-sm" 
                                            id="btnUpdateDecisionEst"
                                            data-1="${valorId}"
                                            data-2="${decision}"
                                            >
                                        Actualizar
                                    </a>
                            </div>
                            `
            $("#formEditarEstructuraDecision").html(formulario)
            $("#EditarEstructuraDecision").modal("show")
        }else{
            alert("no se puede identificar lo seleccionado, intente nuevamente regresando hacia atras.")
            return false
        }

    })

    //CHANGE IMAGES  ESTRUCTURE

    $("body").on("change","#imagenTotalUpdate", function(){

        //console.log($(this)[0].files[0])

            if($(this)[0].files[0]){
                let imagen_detalle = $(this)[0].files[0]
    
                $("#text_preview_totalUpdate").html(`<div class="d-flex justify-content-center">
                                                            <div class="spinner-grow text-primary" role="status">
                                                                <span class="sr-only">Loading...</span>
                                                            </div>
                                                        </div>`)  
                let reader = new FileReader();
                reader.onload = function(e) {
                    //console.log("el load es:",e)
                    ////console.log(e.target.result) 
                    $('#file_preview_totalUpdate').attr('src', e.target.result); 
                $("#text_preview_totalUpdate").html(imagen_detalle["name"])
                }
                reader.readAsDataURL(imagen_detalle)

            }else{  
                $('#file_preview_totalUpdate').attr('src', "/images/upload/arbol-decisiones/sinimagen.png"); 
                $("#text_preview_totalUpdate").html("Sin imagen")
            }

    })
    $("body").on("change","#imagenMasivaUpdate", function(){

        //console.log($(this)[0].files[0])

            if($(this)[0].files[0]){
                let imagen_detalle = $(this)[0].files[0]
    
                $("#text_preview_masivaUpdate").html(`<div class="d-flex justify-content-center">
                                                            <div class="spinner-grow text-primary" role="status">
                                                                <span class="sr-only">Loading...</span>
                                                            </div>
                                                        </div>`)  
                let reader = new FileReader();
                reader.onload = function(e) {
                    //console.log("el load es:",e)
                    ////console.log(e.target.result) 
                    $('#file_preview_masivaUpdate').attr('src', e.target.result); 
                $("#text_preview_masivaUpdate").html(imagen_detalle["name"])
                }
                reader.readAsDataURL(imagen_detalle)

            }else{  
                $('#file_preview_masivaUpdate').attr('src', "/images/upload/arbol-decisiones/sinimagen.png"); 
                $("#text_preview_masivaUpdate").html("Sin imagen")
            }

    })
    $("body").on("change","#imagenNegocioUpdate", function(){

        //console.log($(this)[0].files[0])

            if($(this)[0].files[0]){
                let imagen_detalle = $(this)[0].files[0]
    
                $("#text_preview_negocioUpdate").html(`<div class="d-flex justify-content-center">
                                                            <div class="spinner-grow text-primary" role="status">
                                                                <span class="sr-only">Loading...</span>
                                                            </div>
                                                        </div>`)  
                let reader = new FileReader();
                reader.onload = function(e) {
                    //console.log("el load es:",e)
                    ////console.log(e.target.result) 
                    $('#file_preview_negocioUpdate').attr('src', e.target.result); 
                $("#text_preview_negocioUpdate").html(imagen_detalle["name"])
                }
                reader.readAsDataURL(imagen_detalle)

            }else{  
                $('#file_preview_negocioUpdate').attr('src', "/images/upload/arbol-decisiones/sinimagen.png"); 
                $("#text_preview_negocioUpdate").html("Sin imagen")
            }

    })

    //UPDATE ESTRUCTURA RAMA

    $("body").on("click","#btnUpdateDecisionEst",function(){
        let texto = $("#textoDecisionUpdate").val()
        let idEdit = $(this).data("1")
        let pasoDecision = $(this).data("2")
         
        let imagenTotal = $('#imagenTotalUpdate')[0].files[0]
        let imagenNegocios = $('#imagenNegocioUpdate')[0].files[0]
        let imagenMasiva = $('#imagenMasivaUpdate')[0].files[0]

        let formData = new FormData(); 
        formData.append('idEdit',idEdit);
        formData.append('newText',texto);
        formData.append('pasoDecision',pasoDecision);
        formData.append('imagen_total', imagenTotal); 
        formData.append('imagen_negocio', imagenNegocios); 
        formData.append('imagen_masiva', imagenMasiva);


        if(_THIS_EDIT == ""){
            alert("Tenemos un problema al obtener datos de la nueva rama, intenta crear nuevamente.")
            return false
        }

       // console.log("a data a enviar es:",formData)

        $("#formEditarEstructuraDecision").addClass("d-none")
        $("#preloadEditarEstructuraDecision").html(`<div id="carga_person">
                                            <div class="loader">Loading...</div>
                                        </div>`);

        peticiones.updateRamaEstructura(formData,function(res){
            $("#formEditarEstructuraDecision").removeClass("d-none")
            $("#preloadEditarEstructuraDecision").html("");
            //Errores
            if(res.error == "failed"){
                // console.log("Error: ",res.errorThrown,res.jqXHR,res.textStatus) 
                // $("#errorUpdateDecision").html(`<div class="col-12 text-danger text-center">${res.jqXHR.responseText}</div>`); 
                // return false
                
                if(res.jqXHR.status){
                    let mensaje = errors.codigos(res.jqXHR.status)
                    $("#errorUpdateDecision").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    ${mensaje}</div>`); 
                    //return false
                }
                if(res.jqXHR.responseJSON){
                    if(res.jqXHR.responseJSON.mensaje){
                        let erroresMensaje = res.jqXHR.responseJSON.mensaje  //captura objeto
                        let mensaje = errors.mensajeErrorJson(erroresMensaje)
                        $("#errorUpdateDecision").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    ${mensaje}</div>`);  
                        //return false
                    } 
                }
                
                return false;
             }

             
             $('#dataDecisionArbol').DataTable().destroy();

             let updateId = res.response.idUpdate 
             let dataActualizada = res.response.list.find( el => {
                 if(updateId == el.id) return el
             })
             //console.log("el dato actualizado es: ",dataActualizada)
         
             //console.log(_THIS_EDIT)
             _THIS_EDIT[0].dataset.detalle = dataActualizada.detalle
             _THIS_EDIT[0].dataset.masiva = dataActualizada.img_masivo
             _THIS_EDIT[0].dataset.negocio = dataActualizada.img_negocios
             _THIS_EDIT[0].dataset.total = dataActualizada.img_total
             _THIS_EDIT.children(".text_decision_completo").html(dataActualizada.detalle)
             //_THIS_EDIT = ``


             rearmandoDataRamasTabla(res.response.list,res.response.nombreTabla)
              
             $("#errorUpdateDecision").html(`<div class="container text-center font-weight-bold alert alert-success fade show" role="alert">
             <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                 <span aria-hidden="true">&times;</span>
             </button>
             ${res.response.mensaje}</div>`); 

            
 
             peticiones.cargaDetallesRamas()
            //$("#errorUpdateDecision").html(`<div class="col-12 text-danger text-center">${res}</div>`); 
            //console.log("el resultado sin error es: ",res)
  
        })

        
    })


    //CHANGE IMAGES TABLE

    $("body").on("change","#imagenTableTotalUpdate", function(){

        //console.log($(this)[0].files[0])

            if($(this)[0].files[0]){
                let imagen_detalle = $(this)[0].files[0]
    
                $("#textPreview_imagenTableTotalUpdate").html(`<div class="d-flex justify-content-center">
                                                            <div class="spinner-grow text-primary" role="status">
                                                                <span class="sr-only">Loading...</span>
                                                            </div>
                                                        </div>`)  
                let reader = new FileReader();
                reader.onload = function(e) {
                    //console.log("el load es:",e)
                    ////console.log(e.target.result) 
                    $('#filePreview_imagenTableTotalUpdate').attr('src', e.target.result); 
                $("#textPreview_imagenTableTotalUpdate").html(imagen_detalle["name"])
                }
                reader.readAsDataURL(imagen_detalle)

            }else{  
                $('#filePreview_imagenTableTotalUpdate').attr('src', "/images/upload/arbol-decisiones/sinimagen.png"); 
                $("#textPreview_imagenTableTotalUpdate").html("Sin imagen")
            }

    })
    $("body").on("change","#imagenTableMasivaUpdate", function(){

        //console.log($(this)[0].files[0])

            if($(this)[0].files[0]){
                let imagen_detalle = $(this)[0].files[0]
    
                $("#textPreview_imagenTableMasivaUpdate").html(`<div class="d-flex justify-content-center">
                                                            <div class="spinner-grow text-primary" role="status">
                                                                <span class="sr-only">Loading...</span>
                                                            </div>
                                                        </div>`)  
                let reader = new FileReader();
                reader.onload = function(e) {
                    //console.log("el load es:",e)
                    ////console.log(e.target.result) 
                    $('#filePreview_imagenTableMasivaUpdate').attr('src', e.target.result); 
                $("#textPreview_imagenTableMasivaUpdate").html(imagen_detalle["name"])
                }
                reader.readAsDataURL(imagen_detalle)

            }else{  
                $('#filePreview_imagenTableMasivaUpdate').attr('src', "/images/upload/arbol-decisiones/sinimagen.png"); 
                $("#textPreview_imagenTableMasivaUpdate").html("Sin imagen")
            }

    })
    $("body").on("change","#imagenTableNegocioUpdate", function(){

        //console.log($(this)[0].files[0])

            if($(this)[0].files[0]){
                let imagen_detalle = $(this)[0].files[0]
    
                $("#textPreview_imagenTableNegocioUpdate").html(`<div class="d-flex justify-content-center">
                                                            <div class="spinner-grow text-primary" role="status">
                                                                <span class="sr-only">Loading...</span>
                                                            </div>
                                                        </div>`)  
                let reader = new FileReader();
                reader.onload = function(e) {
                    //console.log("el load es:",e)
                    ////console.log(e.target.result) 
                    $('#filePreview_imagenTableNegocioUpdate').attr('src', e.target.result); 
                $("#textPreview_imagenTableNegocioUpdate").html(imagen_detalle["name"])
                }
                reader.readAsDataURL(imagen_detalle)

            }else{  
                $('#filePreview_imagenTableNegocioUpdate').attr('src', "/images/upload/arbol-decisiones/sinimagen.png"); 
                $("#textPreview_imagenTableNegocioUpdate").html("Sin imagen")
            }

    })

     //UPDATE ESTRUCTURA RAMA

     $("body").on("click","#btnUpdateTableRama",function(){
 
        let texto = $("#textoTableDecisionUpdate").val()
        let idEdit = $(this).data("1")
        let pasoDecision = $(this).data("2")
         
        let imagenTotal = $('#imagenTableTotalUpdate')[0].files[0]
        let imagenNegocios = $('#imagenTableNegocioUpdate')[0].files[0]
        let imagenMasiva = $('#imagenTableMasivaUpdate')[0].files[0]

        let formData = new FormData();
        formData.append('idEdit',idEdit);
        formData.append('newText',texto);
        formData.append('pasoDecision',pasoDecision);
        formData.append('imagen_total', imagenTotal); 
        formData.append('imagen_negocio', imagenNegocios); 
        formData.append('imagen_masiva', imagenMasiva);

 

        $("#formEditarEstructuraDecision").addClass("d-none")
        $("#preloadEditarEstructuraDecision").html(`<div id="carga_person">
                                            <div class="loader">Loading...</div>
                                        </div>`);

        peticiones.updateRamaEstructura(formData,function(res){
            $("#formEditarEstructuraDecision").removeClass("d-none")
            $("#preloadEditarEstructuraDecision").html("");
            //Errores
            if(res.error == "failed"){
                // console.log("Error: ",res.errorThrown,res.jqXHR,res.textStatus) 
                // $("#errorUpdateDecision").html(`<div class="col-12 text-danger text-center">${res.jqXHR.responseText}</div>`); 
                // return false
                
                if(res.jqXHR.status){
                    let mensaje = errors.codigos(res.jqXHR.status)
                    $("#errorUpdateDecision").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    ${mensaje}</div>`); 
                   // return false
                }
                if(res.jqXHR.responseJSON){
                    if(res.jqXHR.responseJSON.mensaje){
                        let erroresMensaje = res.jqXHR.responseJSON.mensaje  //captura objeto
                        let mensaje = errors.mensajeErrorJson(erroresMensaje)
                        $("#errorUpdateDecision").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    ${mensaje}</div>`);  
                        return false
                    } 
                }

                $("#errorUpdateDecision").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    Hubo un problema con la actualización, intente nuevamente.</div>`); 
                
                return false;
             }



            // $("#errorUpdateDecision").html(`<div class="col-12 text-danger text-center">${res}</div>`); 

             //return false

             $('#dataDecisionArbol').DataTable().destroy();

             peticiones.rearmandoDataRamasTabla(res.response.list,res.response.nombreTabla)
            
            
             $("#errorUpdateDecision").html(`<div class="container text-center font-weight-bold alert alert-success fade show" role="alert">
             <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                 <span aria-hidden="true">&times;</span>
             </button>
             ${res.response.mensaje}</div>`); 
 
           // $("#dataDecisionArbol").dataTable();
           peticiones.cargaDetallesRamas()
            
           
        })

        
    })

 

})