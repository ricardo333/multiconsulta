import peticiones from './peticiones.js'
import errors from  "@/globalResources/errors.js"

var estructuraArbolCompleta = ``
var identificadorEstructuraSelect = ``
var detalleEstructuraSelect = ``

$(function(){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("body").on("click",".estructuraDecisionArbol", function(){
 
        peticiones.redirectTabs($('#estructuraDecisionesTab')) 

        
        $("#resultadoEstructuraCompleta").html(`<div id="carga_person">
                                                <div class="loader">Loading...</div>
                                            </div>`)

        let identificador = $(this).data("uno")
        let detalle = $(this).data("dos")
        let tabla = $(this).data("tres")

        identificadorEstructuraSelect = identificador
        detalleEstructuraSelect =  detalle

        getEstructuraDecision(tabla,identificador,detalle)

    })

    $("body").on("click",".secundarios", function(){
        ////  console.log("clic a: ",$(this))
         $(this).siblings("*").css({"display":"block"});
         //// console.log(padreLi)
      })
      $("body").on("dblclick",".secundarios", function(){
         //// console.log("clic a: ",$(this))
         $(this).siblings("*").css({"display":"none"});
         //// console.log(padreLi)
      })
  
})

function getEstructuraDecision(tabla,identificador,detalle)
{

    peticiones.procesandoEstructurasArbol(tabla,identificador,detalle,function(res){
         
         //Errores
        if(res.error == "failed"){
            //console.log("Error: ",res.errorThrown,res.jqXHR,res.textStatus) 
            //$("#resultadoEstructuraCompleta").html(`<div class="col-12 text-danger text-center">${res.jqXHR.responseText}</div>`); 
            //return false
        
            if(res.jqXHR.status){
                let mensaje = errors.codigos(res.jqXHR.status)
                $("#resultadoEstructuraCompleta").html("");
                $("#body-errors-modal").html(`<div class="col-12 text-danger text-center">${mensaje}</div>`)
                $('#errorsModal').modal('show')
                return false
            }
            if(res.jqXHR.responseJSON){
                if(res.jqXHR.responseJSON.mensaje){
                    let erroresMensaje = res.jqXHR.responseJSON.mensaje  //captura objeto
                    let mensaje = errors.mensajeErrorJson(erroresMensaje)
                    $("#resultadoEstructuraCompleta").html(`<div class="col-12 h6 text-danger text-center">${mensaje}</div>`)
                    return false
                } 
            }
        
        return false;
        }
         
        //console.log("paso errores... la data es: ",res)

        //Inicia
        let arrayArbolDecisiones = res.response.arbol[0].datos.decisiones
        let tablaArbol =  res.response.arbol[0].tabla

        estructuraArbolCompleta =`<ul class="padres"  id="padreContenedor">`

        arrayArbolDecisiones.forEach( e => {

            //Agregando select para mostrar su estructura
            let AddCssIdentifidacor = ``
            if( e.id == identificadorEstructuraSelect && e.detalle == detalleEstructuraSelect){
                AddCssIdentifidacor = `id="seleccionado"`
            }

            estructuraArbolCompleta += `<li id="principal">
                                            <a href="javascript:void(0)" class="secundarios" ${AddCssIdentifidacor} 
                                                data-id="${e.id}" data-detalle="${e.detalle}" data-1="${tablaArbol}"
                                                 id="estructura${e.id}${tablaArbol}" 
                                                 data-masiva="${e.img_masivo == null ? 'sinimagen.png' : e.img_masivo}"
                                                 data-negocio="${e.img_negocios == null ? 'sinimagen.png' : e.img_negocios}" 
                                                 data-total="${e.img_total == null ? 'sinimagen.png' : e.img_total}">
                                                 <i class="icofont-tick-mark icofont-sm text-danger"></i> 
                                                 <span class="text_decision_completo">${e.detalle} </span>
                                                 
                                                
                                            `
                                            if (PERMISO_CREATE) {
                                                estructuraArbolCompleta +=` <i class="icofont-ui-add icofont-1x text-primary pl-1 pr-1 addChildTree"></i> `
                                            }
                                            if (PERMISO_EDIT) {
                                                estructuraArbolCompleta +=` <i class="icofont-edit-alt text-success pl-1 pr-1 icofont-1x editChildTree"></i> `
                                            }
                                            if (PERMISO_DELETE) {
                                                estructuraArbolCompleta +=` <i class="icofont-ui-delete text-danger pl-1 pr-1 icofont-1x removeChildTree"></i> `
                                            }

                                            estructuraArbolCompleta +=`  <i class="icofont-dotted-down icofont-1x text-success ml-3 pr-1"></i> </a>`

                                            //Inicio de Decisiones GROUP
                if(e.decisionesGroup){
                    cargaDecisionesGroup(e) 
                }
                estructuraArbolCompleta += `</li>`
                //End Decisiones Group
        })

        estructuraArbolCompleta += `</ul>`;

        $("#resultadoEstructuraCompleta").html(estructuraArbolCompleta);
        
         
        $("#seleccionado").parents(".padres").css({"display":"block"})

    })

}

function cargaDecisionesGroup(e){
    ////console.log("Datos carga: ",e.decisionesGroup.Alternativas.decisiones)
     ////console.log("Datos carga:",e)
   
    let scopeGroup = e.decisionesGroup.Alternativas.decisiones
    
    if(scopeGroup.length > 0){
       //// console.log("el padre,",scopeGroup,", tiene ",scopeGroup.length," hijos")
      ////  console.log("ingreso al length scopegroup...")
         let tablaArbolNew = e.decisionesGroup.tabla
         estructuraArbolCompleta += `<ul class="padres">`
         
        scopeGroup.forEach( function(element) {

            let AddNewCssIdentifidacor = ``
            if( element.id == identificadorEstructuraSelect && element.detalle == detalleEstructuraSelect){
                AddNewCssIdentifidacor = `id="seleccionado"`
            }
           //// console.log("estas en el interior  con el elemento; ",element)
            estructuraArbolCompleta+= `<li class="items_child">
                                            <a  href="javascript:void(0)" class="secundarios" ${AddNewCssIdentifidacor} 
                                                data-id="${element.id}" data-detalle="${element.detalle}" data-1="${tablaArbolNew}"
                                                id="estructura${element.id}${tablaArbolNew}" 
                                                data-masiva="${element.img_masivo == null ? 'sinimagen.png' : element.img_masivo}"
                                                data-negocio="${element.img_negocios == null ? 'sinimagen.png' : element.img_negocios}" 
                                                data-total="${element.img_total == null ? 'sinimagen.png' : element.img_total}">
                                                 <i class="icofont-tick-mark icofont-sm text-danger"></i> 
                                                 <span class="text_decision_completo">${element.detalle}</span>
                                        `

                                        if (PERMISO_CREATE) {
                                            estructuraArbolCompleta +=` <i class="icofont-ui-add icofont-1x text-primary pl-1 pr-1 addChildTree"></i> `
                                        }
                                        if (PERMISO_EDIT) {
                                            estructuraArbolCompleta +=` <i class="icofont-edit-alt text-success pl-1 pr-1 icofont-1x editChildTree"></i> `
                                        }

                                        if (PERMISO_DELETE) {
                                            estructuraArbolCompleta +=` <i class="icofont-ui-delete text-danger pl-1 pr-1 icofont-1x removeChildTree"></i> `
                                        }

                                        if(element.decisionesGroup){
                                            if (element.decisionesGroup.Alternativas.decisiones.length > 0) {
                                                estructuraArbolCompleta+=` <i class="icofont-dotted-down icofont-1x  ml-3 pr-1 text-success"></i>`
                                            } 
                                        }
            estructuraArbolCompleta+=`  </a>`
                if(element.decisionesGroup){
                    
                    cargaDecisionesGroup(element)
                }
            estructuraArbolCompleta += `</li>`
        })
        estructuraArbolCompleta += `</ul>`
        
       
    }

}