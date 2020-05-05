import peticiones from './peticiones.js'
import errors from  "@/globalResources/errors.js"

$(function(){

      
    $("body").on("click","#arbolDecisiones", function(){

        let arbolDeciElement = document.getElementById('arbolDecisiones');

        let itemArbolIsActivo = arbolDeciElement.dataset.cinco
   
        if (itemArbolIsActivo == "activo") {
           // console.log("esta activo, no debe hacer peticion, solamente cambiar de item",itemArbolIsActivo)
            peticiones.redirectTabs($('#arboldeDecisionesMultiTab')) 
            return false;
        }

        $("#resultArbolDecisioneMulti").addClass("d-none")
        $("#preloadArbolDecisionesActions").html(`<div id="carga_person">
                                        <div class="loader">Loading...</div>
                                    </div>`); 


        //Por mensaje
        let imagenArbolDecision =  arbolDeciElement.dataset.uno
        let mensajeAveria =  arbolDeciElement.dataset.cuatro
        

        
        peticiones.redirectTabs($('#arboldeDecisionesMultiTab')) 

        // 
         
        peticiones.cargaArbolDecisiones(function(res){
           
             
            //Errores
            if(res.error == "failed"){
                $("#resultArbolDecisioneMulti").removeClass("d-none")
                $("#preloadArbolDecisionesActions").html(""); 

                peticiones.redirectTabs($('#multiconsultaTab')) 
               //  console.log("Error: ",res.errorThrown,res.jqXHR,res.textStatus) 
               //$("#resultArbolDecisioneMulti").html(`<div class="col-12 text-danger text-center">${res.jqXHR.responseText}</div>`); 
               //return false

               let erroresPeticion =""
                if(jqXHR.responseJSON){
                    if(jqXHR.responseJSON.mensaje){
                        let erroresMensaje = jqXHR.responseJSON.mensaje  //captura objeto
                        let mensaje = errors.mensajeErrorJson(erroresMensaje)
                        erroresPeticion += mensaje 
                    } 
                }
                if(jqXHR.status){
                    let mensaje = errors.codigos(jqXHR.status)
                    erroresPeticion += "<br> "+mensaje
                }
                erroresPeticion = (erroresPeticion.trim() == 0) ? "hubo un error en el servicio, intente nuevamente." : erroresPeticion


                $("#resultArbolDecisioneMulti").html("");
                $("#body-errors-modal").html(`<div class="col-12 text-danger text-center">${erroresPeticion}</div>`)
                $('#errorsModal').modal('show')
 
                return false

            }

            arbolDeciElement.dataset.cinco = "activo"

            //console.log("el resultado de carga natural es: ",res)
            

            let cantidad = res.response.cantidad
           
            let marcacionRapida = res.response.marcacionRapida

            let primeraDecision = res.response.primeraDecision

            let resultadoDom = ``

            if (marcacionRapida.length > 0) {
                let marcacionesForm = `<div class="col-12 form-row p-0 mx-0 mb-2"> 
                                                <div class="w-100 mt-2 text-center">`
                marcacionRapida.forEach(el => {
                    marcacionesForm += ` <label class="content-form-radio m-0 font-weight-light text-sm-arbol-decisiones"><input type="radio" name="solucion" value ="${el.id}"> ${el.detalle}</label>`
                });

                marcacionesForm +=` </div>
                                        </div>`
                                               
                resultadoDom  += marcacionesForm                  
                                               
            }

            resultadoDom += ` <div class="col-md-5 p-1">
                                <h6 class="text-center text-primary font-weight-bold">Estimado usuario, Es obligatorio seguir el árbol hasta el final:</h6>
                            </div>
                            <div class="col-md-7 p-1">
                                    <h6 class="text-center text-primary font-weight-bold">Aqui las recomendaciones para hacer una buena gestión al Cliente:</h6>
                                    <h6 class="text-center text-danger font-weight-bold">Recuerde primero hacer validación comercial del cliente (Atis)</h6>
                            </div>`

            resultadoDom += `<div class="col-12 row px-0 mx-0 " id="contenedor_decisiones_style">
                            <div class="col-md-5 p-1">
                                <div class="form card p-1" id="form-decisiones">`
                                
                                    if (primeraDecision.cantidad > 0) {

                                    let primeraDecisionDom = `<section id="form-1" class="forms_deciciones" data-1="1">
                                                                    <div class="container form-row p-0">
                                                                        <div class="input-group mb-2">
                                                                            <label for="paso_cero" class="font-weight-bold col-form-label col-form-label-sm mx-1 mr-3 text-sm-arbol-decisiones">Paso N° 0:</label>
                                                                            <select name="paso_inicial" 
                                                                                    class="form-control form-control-sm shadow-sm text-sm-arbol-decisiones changeSelectDecision" 
                                                                                    data-1="1">
                                                                                <option value="seleccionar">Seleccionar</option>`
                                                                    
                                                                                primeraDecision.listado.forEach(el => {
                                                                                        
                                                                                            primeraDecisionDom += `<option value="${el.id}"> ${el.detalle}</option>`
                                                                                    
                                                                                });
                                                                                
                                                                    primeraDecisionDom +=       `</select> 
                                                                        </div> 
                                                                    </div>
                                                                </section>`

                                            resultadoDom += primeraDecisionDom
                                        
                                    }



            if (cantidad > 0) {
                let listaPasos = res.response.list 
                let selectsDom = `` 
                listaPasos.forEach(el => {
                    if (el.posicion != 0) {
                        selectsDom += `<section id="form-${el.id}"
                                            class="forms_deciciones" 
                                            data-1="${el.id}">  
                                    </section>`
                    }
                    
                });
                resultadoDom += selectsDom
            }
            resultadoDom+= ` <section id="guardar_content">
                                        
                                </section>`
            resultadoDom+= `</div>
                            </div>`

            resultadoDom+= `<div class="col-md-7 p-1"> 
                                    <section class="card"> 
                                        <div class="card-body text-center" id="loadImgLoad">
                                            
                                        </div>
                                    </section>
                                </div>
                            </div>`

            $("#resultArbolDecisioneMulti").html(resultadoDom); 

            //Mensaje

            if (mensajeAveria != "") {
                peticiones.cargaArbolDecisionesPorMensaje(mensajeAveria,imagenArbolDecision, function(res){
 

                    //Errores
                    if(res.error != "failed"){
                        //console.log("la data de mensajes son:",res)
                        res.response.generalListado.forEach(el => {
                            // console.log("el listado es: ",el)
                                let selectsArmando = `  `
                
                                selectsArmando += `
                                            <div class="container form-row p-0">
                                            
                                            
                                                <div class="input-group mb-2">
                                                    <label for="paso_col${el.tablaId}" class="font-weight-bold col-form-label col-form-label-sm mx-1 mr-3  text-sm-arbol-decisiones">${el.pasoText}:</label>
                                                    <select name="paso_inicial" id="paso_col${el.tablaId}" class="form-control form-control-sm shadow-sm  text-sm-arbol-decisiones 
                                                                    changeSelectDecision" data-1="${el.tablaId}">`
                
                                                    if(parseInt(el.seleccion.length) > 0){
                
                                                        selectsArmando += `<option value="seleccionar" selected> Seleccionar</option>`
                
                                                        el.seleccion.forEach(element => {
                                                            selectsArmando += `<option value="${element.id}" ${el.seleccionado == element.id ? "selected": "" }> ${element.detalle}</option>`
                                
                                                        }) 
                
                                                    }else{
                                                        selectsArmando += `<option value="" selected> Fin del proceso</option>`
                                                    }
                                                
                                                    selectsArmando += `           </select>
                                                                        </div>`
                                                
                                                            if(parseInt(el.seleccion.length) < 1){
                                                                
                                                                $("#guardar_content").html(` <div class="input-group mb-2">
                                                                        <a href="javascript:void(0);" class="btn btn-sm btn-outline-success w-50 m-auto" id="guardar_decisiones_arbol"> Guardar</a>
                                                                        </div>`
                                                                    )
                                                            }else{
                                                                ARBOL_DECISIONES_ACUMULADOR.push({
                                                                        "paso":el.tablaId,
                                                                        "valorSelect":el.seleccionado
                                                                })
                                                            }
                                                            
                                                selectsArmando += `</div>`  
                
                                $(`#form-${el.posicion}`).html(`${selectsArmando}`)

                                
                               
                            })

                            
          
                    }else{
                       // console.log("se genero un error al traer el mensaje : ",res.jqXHR.responseText)
                    }

                    $("#resultArbolDecisioneMulti").removeClass("d-none")
                    $("#preloadArbolDecisionesActions").html(""); 
        
                    
        
                })
            }else{
                $("#resultArbolDecisioneMulti").removeClass("d-none")
                $("#preloadArbolDecisionesActions").html(""); 
            }

           
           

            
                                    

                                

        })

          
            

    })

    

    $("body").on("change",".changeSelectDecision", function(){
        //console.log($(this).length,"es la longitud de selects")

       
       if($(this).length > 1){
           alert("Solo se permite una selección, intente nuevamente.")
           return false;
       }

       if($(this).val().toLowerCase() == "seleccionar"){
           alert("Seleccione una opción válida")
           return false;
       }
       if($(this).val().trim() == ""){
           alert("Seleccione una opción válida")
           return false;
       }

       //console.log("el valor seleccionado es:",$(this).val())
       let valorSelect = $(this).val()
       let data1 = $(this).data('1')//paso actual
       
       

       peticionDecision(valorSelect,data1)//irá dentro del vacio
   })

    $("body").on("click","#guardar_decisiones_arbol", function(){

            $("#resultArbolDecisioneMulti").addClass("d-none")
            $("#preloadArbolDecisionesActions").html(`<div class="d-flex justify-content-center align-content-center flex-wrap" style="height:450px;">
                                                        <div class="spinner-border" role="status" style="width: 150px;height: 150px;">
                                                        <span class="sr-only">Loading...</span>
                                                        </div>
                                                        <div class="text-center w-100">
                                                            <strong>Guardando Datos.</strong>
                                                        </div>
                                                    </div>`); 
        
            let solucion = $('input[type=radio][name=solucion]:checked').val() || "";

            let pasosIteraccion = ARBOL_DECISIONES_ACUMULADOR
            let codCliente = $("#arbolDecisiones").data("dos") 

            //console.log("el cheked seleccionado es: ",solucion)
            //console.log("Los pasos de iteraccion son: ",pasosIteraccion)

            peticiones.registrosPasosArbol(pasosIteraccion,solucion,codCliente, function(res){


                $("#preloadArbolDecisionesActions").html(""); 
                $("#resultArbolDecisioneMulti").removeClass("d-none")

                //Errores
                if(res.error == "failed"){
                    let erroresPeticion =""
                    //console.log("Error: ",res.errorThrown,res.jqXHR,res.textStatus) 
                    //$("#body-errors-modal").html(`<div class="col-12 text-danger text-center">${res.jqXHR.responseText}</div>`)
                    //$('#errorsModal').modal('show') 
                    //return false
                    if(res.jqXHR.responseJSON){
                        if(res.jqXHR.responseJSON.mensaje){
                            let erroresMensaje = res.jqXHR.responseJSON.mensaje  //captura objeto
                            let mensaje = errors.mensajeErrorJson(erroresMensaje)

                            erroresPeticion += mensaje
                        
                            
                        } 
                    }
                    if(res.jqXHR.status){
                        let mensaje = errors.codigos(res.jqXHR.status)
                        erroresPeticion += "<br>"+mensaje 
                    }

                    $("#body-errors-modal").html(`<div class="col-12 text-danger text-center">${erroresPeticion}</div>`)
                    $('#errorsModal').modal('show')
                    
                    return false;
                }

                peticiones.redirectTabs($('#multiconsultaTab')) 

                //console.log("el resultado del registro es: ",res)
                $("#body-success-modal").html(`<div class="col-12 text-secondary text-center">${res.mensaje}</div>`)
                $('#successModal').modal('show')

                ARBOL_DECISIONES_MARCARAPIDA = []

                let arbolDeciElement = document.getElementById('arbolDecisiones');
 
                arbolDeciElement.dataset.cinco = "inactivo"

                $("#resultArbolDecisioneMulti").html("");



            })
    
    })

    $("body").on("change","input[type=radio][name=solucion]", function(){

        $("#guardar_content").html(` <div class="input-group mb-2">
            <a href="javascript:void(0);" class="btn btn-sm btn-outline-success w-50 m-auto" id="guardar_decisiones_arbol"> Guardar</a>
            </div>`
        ) 

    })
    
    function peticionDecision(valorSelect,data1)
    {
 
        let imagen = $("#arbolDecisiones").data("uno") 
       
        /*console.log(` ${idUsuario},  ${codCliente}, ${valorSelect}, ${data1})*/

       

        $("#resultArbolDecisioneMulti").addClass("d-none")
        $("#preloadArbolDecisionesActions").html(`<div id="carga_person">
                                                        <div class="loader">Loading...</div>
                                                    </div>`); 

        //console.log("Los datos a enviar son: ",valorSelect,data1)

         
        peticiones.cargaPeticionArbol(valorSelect,data1,imagen,function(res){

            $("#preloadArbolDecisionesActions").html(""); 
            $("#resultArbolDecisioneMulti").removeClass("d-none")

            //Errores
            if(res.error == "failed"){
                let erroresPeticion =""
                //console.log("Error: ",res.errorThrown,res.jqXHR,res.textStatus) 
               //$("#body-errors-modal").html(`<div class="col-12 text-danger text-center">${res.jqXHR.responseText}</div>`)
               //$('#errorsModal').modal('show') 
               //return false
            if(res.jqXHR.responseJSON){
                if(res.jqXHR.responseJSON.mensaje){
                    let erroresMensaje = res.jqXHR.responseJSON.mensaje  //captura objeto
                    let mensaje = errors.mensajeErrorJson(erroresMensaje)

                    erroresPeticion += mensaje
                   
                    
                } 
            }
            if(res.jqXHR.status){
                let mensaje = errors.codigos(res.jqXHR.status)
                erroresPeticion += "<br>"+mensaje 
            }

            $("#body-errors-modal").html(`<div class="col-12 text-danger text-center">${erroresPeticion}</div>`)
            $('#errorsModal').modal('show')
            
            return false;
            }

            //console.log("el resultado de la data es: ",res)

            
            loadFormPrint(res.response)

            ARBOL_DECISIONES_ACUMULADOR.push({
                    "paso":data1,
                    "valorSelect":valorSelect
            })

           

        })


    }

    function loadFormPrint(data)
    {
        limpiarPosteriores(data.pasoActual)

         
        let   new_form = `
                        <div class="container form-row p-0">
                        
                            <div class="input-group mb-2">
                                <label for="paso_${data.pasoActual}" class="font-weight-bold col-form-label col-form-label-sm text-sm-arbol-decisiones  mx-1 mr-3">${data.pasoText}:</label>
                                <select name="paso_inicial" id="paso_${data.pasoActual}" class="form-control form-control-sm shadow-sm text-sm-arbol-decisiones
                                                changeSelectDecision" data-1="${data.pasoActual}">`

                                if(parseInt(data.dataList.length) > 0){
                                    new_form += `<option value="seleccionar" selected> Seleccionar</option>`
                                    data.dataList.forEach(el => {
                                        new_form += `<option value="${el.id}"> ${el.detalle}</option>`
                                    } ) 
                                }else{
                                    new_form += `<option value="" selected> Fin del proceso</option>`
                            }
                            
                new_form += `           </select> 
                            </div> `
                            
                            if(parseInt(data.dataList.length) < 1){
                            
                                $("#guardar_content").html(` <div class="input-group mb-2">
                                            <a href="javascript:void(0);" class="btn btn-sm btn-outline-success w-50 m-auto" id="guardar_decisiones_arbol"> Guardar</a>
                                            </div>`
                                        )
                            
                            }
                            

                new_form +=  `</div> `


        $(`#form-${data.pasoActual}`).html(new_form);
         
        $("#loadImgLoad").html(`
                    <img src="/images/upload/arbol-decisiones/${data.imagen}" class="img-fluid" id="" alt="Responsive image"> 
                `)
            
        
                //console.log("la data de decisiones al final es: ",ARBOL_DECISIONES_ACUMULADOR)
        
    }

    function limpiarPosteriores(numeroFormulario){
     
        let cantidadformulariosTotales = $(".forms_deciciones").length
         
        for (let index = parseInt(numeroFormulario); index < cantidadformulariosTotales; index++) {
                //console.log("se limpiarán los form #form-",index)
                //console.log("de debe limpiar el array desde : ",ARBOL_DECISIONES_ACUMULADOR[numeroFormulario-1],"=>",numeroFormulario-1)
                //Intentar con IndexOf creo que deberia ser mejor, para buscar por Id de tabla..
            if (ARBOL_DECISIONES_ACUMULADOR[numeroFormulario-2]) {
                //ARBOL_DECISIONES_ACUMULADOR[index-2] elminar el 
                ARBOL_DECISIONES_ACUMULADOR.splice([numeroFormulario-2], 1)
                // console.log("quedaria asi: ",ARBOL_DECISIONES_ACUMULADOR)
            }
            $(`#form-${index}`).html('')
        }
        
    }
        

})