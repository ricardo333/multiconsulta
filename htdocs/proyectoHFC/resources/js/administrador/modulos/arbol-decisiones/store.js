import valida from  "@/globalResources/forms/valida.js"
import peticiones from './peticiones.js'
import errors from  "@/globalResources/errors.js"

var _THIS_CREATE = ``
$(function(){


    //Agregando Childs
    $("body").on("click",".addChildTree", function(){

       //console.log("el clic add es: ",$(this))
       //console.log("el padre secundario es: ", $(this).parent(".secundarios"))

        $("#createArbolDecisionModal").modal("show")

        $("#content_store_rama").html(`<div id="carga_person">
                                    <div class="loader">Loading...</div>
                                </div>`);

        let padreSelectAdd = $(this).parent(".secundarios")

        //peticion pasos Siguientes
        let tb = padreSelectAdd.data("1")
            
        _THIS_CREATE = padreSelectAdd;

        let idSelected = padreSelectAdd.data("id")
            
        $("#content_store_rama").html(`
                    <div class="card" >
                        <div class="card-body">
                            <div class="row">  
                                <div class="col-md-12">
                                    <div class="form-row ">
                                        <div class="col-md-12 errors mt-2" id="errorStoreRama">
                                        </div>
                                        <div class="col-md-12 p-1">
                                            <input type="text" id="storeDetalleRama" class="form-control form-control-sm shadow-sm" placeholder="Nombre de la decisión">
                                        </div>

                                        <div class="col-6 col-sm-4 col-md-4 p-1">  
                                            <label for="imagenTotalStore" class="col-form-label col-form-label-sm btn btn-outline-info btn-sm d-flex align-items-center justify-content-center p-0">
                                                <i class="icofont-cloud-upload icofont-2x"></i> Imagen Total
                                            </label>  
                                            <figure id="info_detalle_imagenTotalStore" class="card mt-1 figure figura_create_image"> 
                                                <img id="file_preview_total" class="figure-img img-fluid rounded" src="/images/upload/arbol-decisiones/sinimagen.png">
                                                <figcaption id="text_preview_total" class="figure-caption text-right">Sin imagen</figcaption>
                                            </figure>  

                                            <input type="file"  id="imagenTotalStore" class="d-none"> 
                                        </div>
                                        <div class="col-6 col-sm-4 col-md-4 p-1">    
                                            <label for="imagenNegociosStore" class="col-form-label col-form-label-sm btn btn-outline-info btn-sm d-flex align-items-center justify-content-center p-0">
                                                <i class="icofont-cloud-upload icofont-2x"></i> Imagen Negocio
                                            </label>
                                            <figure id="info_detalle_imagenNegociosStore" class="card mt-1 figure figura_create_image"> 
                                                <img id="file_preview_negocio" class="figure-img img-fluid rounded" src="/images/upload/arbol-decisiones/sinimagen.png">
                                                <figcaption id="text_preview_negocio" class="figure-caption text-right">Sin imagen</figcaption>
                                            </figure> 
                                            
                                            <input type="file"  id="imagenNegociosStore" class="d-none"> 
                                        </div>
                                        <div class="col-6 col-sm-4 col-md-4 p-1">  
                                            <label for="imagenMasivaStore" class="col-form-label col-form-label-sm btn btn-outline-info btn-sm d-flex align-items-center justify-content-center p-0">
                                                <i class="icofont-cloud-upload icofont-2x"></i> Imagen Masiva
                                            </label>  
                                            <figure id="info_detalle_imagenMasivaStore" class="card mt-1 figure figura_create_image"> 
                                                <img id="file_preview_masiva" class="figure-img img-fluid rounded" src="/images/upload/arbol-decisiones/sinimagen.png">
                                                <figcaption id="text_preview_masiva" class="figure-caption text-right">Sin imagen</figcaption>
                                            </figure> 
                                            
                                            <input type="file"  id="imagenMasivaStore" class="d-none"> 
                                        </div>
                                         
                                        <div class="col-md-12 text-center mt-2">
                                            <a href="javascript:void(0)" class="btn btn-sm btn-outline-primary shadow-sm" id="storeChildRama" 
                                                    data-1="${tb}" data-2="${idSelected}">Registrar</a>
                                        </div>
                                        
                                    </div>
                                </div>
                                    
                            </div>
                        </div>
                    </div>`)
            
        
       //// console.log($("#storeDetalleRama"))
        //document.getElementById("storeDetalleRama").focus()
        
       // $("#storeDetalleRama").focus()
         
    })

    //Registrando La nueva Rama
    $("body").on("click","#storeChildRama", function(){
        //console.log("se enviarán los datos...")

        let validacionConitnueStore = validacionContinueStoreRamaTable()
        if(!validacionConitnueStore){ 
            return false
        } 
        
        let idDecision = $(this).data("2")
        let tb = $(this).data("1")
        let detalle = $("#storeDetalleRama").val()
        let imagen_total = $('#imagenTotalStore')[0].files[0]
        let imagen_negocio = $('#imagenNegociosStore')[0].files[0]
        let imagen_masiva = $('#imagenMasivaStore')[0].files[0]
        ////  console.log("el identificador es: ",$(this).data("1")," y la tabla es: ",$(this).data("2"))
        
        let formData = new FormData(); 
        //formData.append('action', 'store');
        formData.append('tb',tb);
        formData.append('idDecision',idDecision);
        formData.append('detalle',detalle);
        formData.append('imagen_total', imagen_total); 
        formData.append('imagen_negocio', imagen_negocio); 
        formData.append('imagen_masiva', imagen_masiva); 

        if(_THIS_CREATE == ""){
            alert("Tenemos un problema al obtener datos de la nueva rama, intenta crear nuevamente.")
            return false
        }

        //console.log("La data a enviar son:",formData)
         
        $("#content_store_rama").addClass("d-none")
        $("#load_add_tree_new").html(`<div id="carga_person">
                                            <div class="loader">Loading...</div>
                                        </div>`);

        peticiones.storeRamaChild(formData,function(res){

            $("#content_store_rama").removeClass("d-none")
            $("#load_add_tree_new").html("");

            //Errores
            if(res.error == "failed"){
               //console.log("Error: ",res.errorThrown,res.jqXHR,res.textStatus) 
               //$("#errorStoreRama").html(`<div class="col-12 text-danger text-center">${res.jqXHR.responseText}</div>`); 
               //return false
            if(res.jqXHR.responseJSON){
                if(res.jqXHR.responseJSON.mensaje){
                    let erroresMensaje = res.jqXHR.responseJSON.mensaje  //captura objeto
                    let mensaje = errors.mensajeErrorJson(erroresMensaje)
                    $("#errorStoreRama").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                ${mensaje}</div>`);   
                    return false
                } 
            }
            if(res.jqXHR.status){
                let mensaje = errors.codigos(res.jqXHR.status)
                $("#errorStoreRama").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        ${mensaje}</div>`); 
                return false
            }
            
            return false;
            }

            let data = res.response

            let ruta_image_vacia = "sinimagen.png" 

            let imagen_total = $('#imagenTotalStore')[0].files[0] ? $('#imagenTotalStore')[0].files[0]["name"] : ruta_image_vacia
            let imagen_negocio = $('#imagenNegociosStore')[0].files[0]? $('#imagenNegociosStore')[0].files[0]["name"] : ruta_image_vacia
            let imagen_masiva = $('#imagenMasivaStore')[0].files[0]? $('#imagenMasivaStore')[0].files[0]["name"] : ruta_image_vacia

            let detalleNuevoCreado = $("#storeDetalleRama").val()

            if (_THIS_CREATE.siblings("ul")[0]) {
                    //console.log("tiene hermano ul",_THIS_CREATE.siblings("ul")," solo se debe agregar el li al ul")

                    let estructura = `<li class="items_child">`
                    estructura += `<a href="javascript:void(0)" class="secundarios" 
                                    data-id="${data.nuevaRama.idStore}" 
                                    data-detalle="${detalleNuevoCreado}"
                                    data-1="${data.nuevaRama.tabla}"
                                    id="estructura${data.nuevaRama.idStore}${data.nuevaRama.tabla}"
                                    data-total="${imagen_total}"
                                    data-negocio="${imagen_negocio}"
                                    data-masiva="${imagen_masiva}"
                                    >
                                        <i class="icofont-tick-mark icofont-1x text-danger"></i> 
                                        <span class="text_decision_completo">${detalleNuevoCreado}</span> `
                    if(PERMISO_CREATE){
                        estructura += ` <i class="icofont-ui-add icofont-1x text-primary addChildTree"></i> `
                    }
                    if(PERMISO_EDIT){
                        estructura += ` <i class="icofont-edit-alt text-success text-success pl-1 pr-1 icofont-1x editChildTree"></i> `
                    }
                    if(PERMISO_DELETE){
                        estructura += ` <i class="icofont-ui-delete text-danger pr-1 icofont-1x removeChildTree"></i> `
                    }
                                    
                    estructura += `</a></li>`

                    _THIS_CREATE.siblings("ul").append(estructura)
            }else{
                   //console.log("no tiene hermano ul se debe crear la estructura UL desde cero agregando el li")

                    let estructura = `<ul class="padres" style="display:block">`
                    estructura += `<li class="items_child"> `
                    estructura += `<a href="javascript:void(0)" class="secundarios" 
                                        data-id="${data.nuevaRama.idStore}" 
                                        data-detalle="${detalleNuevoCreado}"
                                        data-1="${data.nuevaRama.tabla}"
                                        id="estructura${data.nuevaRama.idStore}${data.nuevaRama.tabla}"
                                        data-total="${imagen_total}"
                                        data-negocio="${imagen_negocio}"
                                        data-masiva="${imagen_masiva}"
                                        >
                                            <i class="icofont-tick-mark icofont-1x text-danger"></i>
                                            <span class="text_decision_completo">${detalleNuevoCreado}</span> `

                    if(PERMISO_CREATE){
                    estructura += ` <i class="icofont-ui-add icofont-1x text-primary addChildTree"></i> `
                    }
                    if(PERMISO_EDIT){
                        estructura += ` <i class="icofont-edit-alt text-success pl-1 pr-1 icofont-1x editChildTree"></i> `
                    }
                    if(PERMISO_DELETE){
                        estructura += ` <i class="icofont-ui-delete text-danger pr-1 icofont-1x removeChildTree"></i> `
                    }
                    
                    estructura += `</a></li>`

                    estructura += `</ul>`


                _THIS_CREATE.append(`<i class="icofont-dotted-down icofont-1x  ml-3 pr-1 text-success"></i>`)
                _THIS_CREATE.parent(".items_child").append(estructura)
            }
 
            $("#createArbolDecisionModal").modal("hide")
      
            _THIS_CREATE = ``

            //$("#errorStoreRama").html(`<div class="col-12 text-danger text-center">${res}</div>`); 
            //console.log("el resultado sin error es: ",res)

        })
        
    })

    $("body").on("change","#imagenTotalStore",function(){

        //console.log($(this)[0].files[0])

        if($(this)[0].files[0]){
            let imagen_detalle = $(this)[0].files[0]
 
            // 
            $("#text_preview_total").html(`<div class="d-flex justify-content-center">
                                                        <div class="spinner-grow text-primary" role="status">
                                                            <span class="sr-only">Loading...</span>
                                                        </div>
                                                    </div>`) 

            let reader = new FileReader();
            reader.onload = function(e) {
                 //console.log("el load es:",e)
                ////console.log(e.target.result) 
                $('#file_preview_total').attr('src', e.target.result); 
               $("#text_preview_total").html(imagen_detalle["name"])
            }
            reader.readAsDataURL(imagen_detalle)

        }else{ 
            
            $('#file_preview_total').attr('src', "/images/upload/arbol-decisiones/sinimagen.png"); 
            $("#text_preview_total").html("Sin imagen")

        }
    })

    $("body").on("change","#imagenNegociosStore",function(){

        //console.log($(this)[0].files[0])

        if($(this)[0].files[0]){
            let imagen_detalle = $(this)[0].files[0]
 
            // 
            $("#text_preview_negocio").html(`<div class="d-flex justify-content-center">
                                                        <div class="spinner-grow text-primary" role="status">
                                                            <span class="sr-only">Loading...</span>
                                                        </div>
                                                    </div>`) 

            let reader = new FileReader();
            reader.onload = function(e) {
                 //console.log("el load es:",e)
                ////console.log(e.target.result) 
                $('#file_preview_negocio').attr('src', e.target.result); 
               $("#text_preview_negocio").html(imagen_detalle["name"])
            }
            reader.readAsDataURL(imagen_detalle)

        }else{ 
             
            $('#file_preview_negocio').attr('src', "/images/upload/arbol-decisiones/sinimagen.png"); 
            $("#text_preview_negocio").html("Sin imagen")

        }
        
         
    })

    $("body").on("change","#imagenMasivaStore",function(){

        //console.log($(this)[0].files[0])

        if($(this)[0].files[0]){
            let imagen_detalle = $(this)[0].files[0]
 
            // 
            $("#text_preview_masiva").html(`<div class="d-flex justify-content-center">
                                                        <div class="spinner-grow text-primary" role="status">
                                                            <span class="sr-only">Loading...</span>
                                                        </div>
                                                    </div>`) 

            let reader = new FileReader();
            reader.onload = function(e) {
                 //console.log("el load es:",e)
                ////console.log(e.target.result) 
                $('#file_preview_masiva').attr('src', e.target.result); 
               $("#text_preview_masiva").html(imagen_detalle["name"])
            }
            reader.readAsDataURL(imagen_detalle)

        }else{ 
             
            $('#file_preview_masiva').attr('src', "/images/upload/arbol-decisiones/sinimagen.png"); 
            $("#text_preview_masiva").html("Sin imagen")

        } 
        
    })

    //STORE RAMA IN THE TABLE 

    $("#openFormatStoreBrotherRama").click(function(){

        let tb = $(this).data("uno")
        let tablaAnterior = $(this).data("dos")
        let paso = $(this).data("tres")
         

       // console.log("Los datos son: ",tb,"->",tablaAnterior)

        if (tablaAnterior != "") {
            //console.log("hay datos de tabla y paso anterio..")

            $("#createArbolDecisionModal").modal("show")

            $("#content_store_rama").html(`<div id="carga_person">
                                        <div class="loader">Loading...</div>
                                    </div>`);

            peticiones.listaPasoRamasJson(paso, function(res){


                if(res.error == "failed"){
                       // console.log("Error: ",res.errorThrown,res.jqXHR,res.textStatus)  
                       // $("#content_store_rama").html(`<div class="col-12 text-danger text-center">${res.jqXHR.responseText}</div>`)
                        
                       // return false
                     if(res.jqXHR.responseJSON){
                         if(res.jqXHR.responseJSON.mensaje){
                             let erroresMensaje = res.jqXHR.responseJSON.mensaje  //captura objeto
                             let mensaje = errors.mensajeErrorJson(erroresMensaje)
                             $("#content_store_rama").html(`<div class="col-12 text-danger text-center">${mensaje}</div>`)
                             
                             //return false
                         } 
                     }
                     if(res.jqXHR.status){
                         let mensaje = errors.codigos(res.jqXHR.status)
                         $("#content_store_rama").html(``)
                         alert(mensaje);
                       
                         return false
                     }
                 
                     return false;
                 }

                 //console.log("el resultado listado del paso anterior es:", res)
  
                 $("#content_store_rama").html(`<div class="col-12 text-danger text-center">${res}</div>`)
                 cargaFormControlStoreRama(tb,res.response)

                 return false;

            })

        }else{
           // console.log("Los datos están vacios") 

            $("#createArbolDecisionModal").modal("show")

            $("#content_store_rama").html(`<div id="carga_person">
                                        <div class="loader">Loading...</div>
                                    </div>`);

            cargaFormControlStoreRama(tb,{})
        }
         

    })

    function cargaFormControlStoreRama(tb,data)
    {

          
        let detalleForm = `
                                <div class="card" >
                                    <div class="card-body">
                                        <div class="row">  
                                            <div class="col-md-12">
                                                <div class="form-row ">
                                                    <div class="col-md-12 errors mt-2" id="errorStoreRama">
                                                    </div>`

                                if (data.cantidad) {
                                    if (data.cantidad > 0) {
                                        let selectsPasosAnteriores = `<div class="col-md-12 p-1">`
                                            selectsPasosAnteriores += `<select class="form-control form-control-sm shadow-sm validateSelect" id="selectRamaPadre" data-uno="${data.tablaAnterior}">`
                                            selectsPasosAnteriores += `<option value="seleccionar">Seleccionar un paso anterior</option>`
                                            data.list.forEach(el => {
                                                selectsPasosAnteriores += `<option value="${el.id}">${el.detalle}</option>`
                                            });
                                            selectsPasosAnteriores += `</select>`
                                            selectsPasosAnteriores += `</div>`
                                            detalleForm += selectsPasosAnteriores
                                    }
                                }
                                    detalleForm +=  `<div class="col-md-12 p-1">
                                                        <input type="text" id="storeDetalleRama" class="form-control form-control-sm shadow-sm validateText" placeholder="Nombre de la decisión">
                                                    </div>

                                                    <div class="col-6 col-sm-4 col-md-4 p-1">  
                                                        <label for="imagenTotalStore" class="col-form-label col-form-label-sm btn btn-outline-info btn-sm d-flex align-items-center justify-content-center p-0">
                                                            <i class="icofont-cloud-upload icofont-2x"></i> Imagen Total
                                                        </label>  
                                                        <figure id="info_detalle_imagenTotalStore" class="card mt-1 figure figura_create_image"> 
                                                            <img id="file_preview_total" class="figure-img img-fluid rounded" src="/images/upload/arbol-decisiones/sinimagen.png">
                                                            <figcaption id="text_preview_total" class="figure-caption text-right">Sin imagen</figcaption>
                                                        </figure>  

                                                        <input type="file"  id="imagenTotalStore" class="d-none"> 
                                                    </div>
                                                    <div class="col-6 col-sm-4 col-md-4 p-1">    
                                                        <label for="imagenNegociosStore" class="col-form-label col-form-label-sm btn btn-outline-info btn-sm d-flex align-items-center justify-content-center p-0">
                                                            <i class="icofont-cloud-upload icofont-2x"></i> Imagen Negocio
                                                        </label>
                                                        <figure id="info_detalle_imagenNegociosStore" class="card mt-1 figure figura_create_image"> 
                                                            <img id="file_preview_negocio" class="figure-img img-fluid rounded" src="/images/upload/arbol-decisiones/sinimagen.png">
                                                            <figcaption id="text_preview_negocio" class="figure-caption text-right">Sin imagen</figcaption>
                                                        </figure> 
                                                        
                                                        <input type="file"  id="imagenNegociosStore" class="d-none"> 
                                                    </div>
                                                    <div class="col-6 col-sm-4 col-md-4 p-1">  
                                                        <label for="imagenMasivaStore" class="col-form-label col-form-label-sm btn btn-outline-info btn-sm d-flex align-items-center justify-content-center p-0">
                                                            <i class="icofont-cloud-upload icofont-2x"></i> Imagen Masiva
                                                        </label>  
                                                        <figure id="info_detalle_imagenMasivaStore" class="card mt-1 figure figura_create_image"> 
                                                            <img id="file_preview_masiva" class="figure-img img-fluid rounded" src="/images/upload/arbol-decisiones/sinimagen.png">
                                                            <figcaption id="text_preview_masiva" class="figure-caption text-right">Sin imagen</figcaption>
                                                        </figure> 
                                                        
                                                        <input type="file"  id="imagenMasivaStore" class="d-none"> 
                                                    </div>
                                                    
                                                    <div class="col-md-12 text-center mt-2">
                                                        <a href="javascript:void(0)" class="btn btn-sm btn-outline-primary shadow-sm" id="storeBrotherRamaTable" 
                                                                data-1="${tb}">Registrar</a>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                                
                                        </div>
                                    </div>
                                </div>`

        $("#content_store_rama").html(detalleForm)

    }


    $("body").on("click","#storeBrotherRamaTable", function(){

        let validacionConitnueStore = validacionContinueStoreRamaTable()
        if(!validacionConitnueStore){ 
            return false
        } 
 
        let idDecision = $("#selectRamaPadre").val() || ""

        //let tb = $(this).data("1")
        let tb = $("#selectRamaPadre").data("uno") || ""
        let detalle = $("#storeDetalleRama").val()
        let imagen_total = $('#imagenTotalStore')[0].files[0]
        let imagen_negocio = $('#imagenNegociosStore')[0].files[0]
        let imagen_masiva = $('#imagenMasivaStore')[0].files[0]
        ////  console.log("el identificador es: ",$(this).data("1")," y la tabla es: ",$(this).data("2"))
       
        if (tb == "") {
            tb = $(this).data("1")
        }
        
        let formData = new FormData(); 
        //formData.append('action', 'store');
        formData.append('tb',tb);
        formData.append('idDecision',idDecision);
        formData.append('detalle',detalle);
        formData.append('imagen_total', imagen_total); 
        formData.append('imagen_negocio', imagen_negocio); 
        formData.append('imagen_masiva', imagen_masiva); 

        $("#content_store_rama").addClass("d-none")
        $("#load_add_tree_new").html(`<div id="carga_person">
                                            <div class="loader">Loading...</div>
                                        </div>`);

        peticiones.storeRamaChild(formData,function(res){

            $("#content_store_rama").removeClass("d-none")
            $("#load_add_tree_new").html("");

            //Errores
            if(res.error == "failed"){
                //console.log("Error: ",res.errorThrown,res.jqXHR,res.textStatus) 
                //$("#errorStoreRama").html(`<div class="col-12 text-danger text-center">${res.jqXHR.responseText}</div>`); 
                //return false
                if(res.jqXHR.responseJSON){
                    if(res.jqXHR.responseJSON.mensaje){
                        let erroresMensaje = res.jqXHR.responseJSON.mensaje  //captura objeto
                        let mensaje = errors.mensajeErrorJson(erroresMensaje)
                        $("#errorStoreRama").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    ${mensaje}</div>`);   
                        //return false
                    } 
                }
                if(res.jqXHR.status){
                    let mensaje = errors.codigos(res.jqXHR.status)
                    $("#errorStoreRama").html(``);   
                    alert(mensaje);
                   /* $("#errorStoreRama").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            ${mensaje}</div>`); */
                    return false
                }
            
                return false;
            }

            //$("#errorStoreRama").html(res)
            // console.log("resultado del registro es: ",res)
             
            $('#dataDecisionArbol').DataTable().destroy();

            peticiones.rearmandoDataRamasTabla(res.response.list,res.response.nombreTabla)

            

            $("#errorStoreRama").html(`<div class="container text-center font-weight-bold alert alert-success fade show" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            ${res.response.mensaje}</div>`); 

           

            peticiones.cargaDetallesRamas()
 
        })


       
    })

    function validacionContinueStoreRamaTable()
    {

        let PasoAnterior = $("#selectRamaPadre").val() || ''
        let detalleRama = $("#storeDetalleRama") 


        $(".validateSelect").removeClass("valida-error-input")
        $(".validateText").removeClass("valida-error-input")
        $("#errorStoreRama").html(``)

        if (PasoAnterior != '') {

            //console.log("el paso anterior es: ",PasoAnterior)

            PasoAnterior = $("#selectRamaPadre")
           
            if(!valida.isValidText(PasoAnterior.val())){
                valida.isValidateInputText(PasoAnterior)
                $("#errorStoreRama").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            El campo "Paso anterior" es requerido</div>`); 
                return false
            }  
            if(PasoAnterior.val().toLowerCase() == "seleccionar"){
                valida.isValidateInputText(PasoAnterior)
                $("#errorStoreRama").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            Seleccione un "Paso anterior" válido</div>`);  
                return false
            }
            if(!valida.isValidNumber(PasoAnterior.val())){
                valida.isValidateInputText(PasoAnterior)
                $("#errorStoreRama").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                Seleccione un "Paso anterior" válido</div>`);  
                return false
            }
        }
    

        if(!valida.isValidText(detalleRama.val())){
            valida.isValidateInputText(detalleRama)
            $("#errorStoreRama").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        El campo "Nombre de la decisión" es requerido</div>`);  
            return false
        } 
           
       
       

         

        $(".validateSelect").removeClass("valida-error-input")
        $(".validateText").removeClass("valida-error-input")
        $("#errorStoreRama").html(``)

        return true

    }





})