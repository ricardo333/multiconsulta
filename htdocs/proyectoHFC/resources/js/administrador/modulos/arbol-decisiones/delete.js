import peticiones from './peticiones.js'
import errors from  "@/globalResources/errors.js"

var _THIS_DELETE = ``
var DELETE_ARRAY = []

$(function(){

    $("body").on("click",".removeChildTree", function(){


        let confirmDelet = confirm("Se eliminarán todas las ramas hijas, incluyendo lo seleccionado.¿Esta seguro de esta acción?.");
        if (!confirmDelet) { 
          return false
        } 
         
        let PadreEtiquetaA = $(this).parent(".secundarios")

        let PasoActualTbName = $("#openFormatStoreBrotherRama").data("uno")

        _THIS_DELETE = PadreEtiquetaA

         

        PadreEtiquetaA[0].style.background="#eecbcb"

          
        DELETE_ARRAY = []

        DELETE_ARRAY.push({
            "paso":PadreEtiquetaA[0].getAttribute("data-1"),
            "id":PadreEtiquetaA[0].getAttribute("data-id")
        })

        if (PadreEtiquetaA.siblings("ul")[0]) {
            //tiene hijos que eliminar - se debera reccorrer
            let hijosDelete = PadreEtiquetaA.siblings("ul")[0].children 
            ////console.log("los hijos a eliminarse son: ",hijosDelete)

            for (let index = 0; index < hijosDelete.length; index++) {
       
              ////  console.log("Hijos=>",hijosDelete[index])
                hijosDelete[index].children[0].style.background="#eecbcb"
                DELETE_ARRAY.push({
                    "paso":hijosDelete[index].children[0].getAttribute("data-1"),
                    "id":hijosDelete[index].children[0].getAttribute("data-id")
                })
                  if(hijosDelete[index].children[1]){
                    
                   recorrerElementosHijos(hijosDelete[index].children[1])
                        
                 }
                
              } 

        }
   
        $("#resultadoEstructuraCompleta").addClass("d-none")
        $("#preloadEstructuraRamas").html(`<div id="carga_person">
                                            <div class="loader">Loading...</div>
                                        </div>`);
        
                                

        peticiones.deleteRamaEstructura(DELETE_ARRAY,PasoActualTbName,function(res){

            $("#resultadoEstructuraCompleta").removeClass("d-none")
            $("#preloadEstructuraRamas").html("");

            //Errores
            if(res.error == "failed"){
                //console.log("Error: ",res.errorThrown,res.jqXHR,res.textStatus) 
                //$("#body-errors-modal").html(`<div class="col-12 text-danger text-center">${res.jqXHR.responseText}</div>`)
                //$('#errorsModal').modal('show') 
                //return false
                if(res.jqXHR.responseJSON){
                    if(res.jqXHR.responseJSON.mensaje){
                        let erroresMensaje = res.jqXHR.responseJSON.mensaje  //captura objeto
                        let mensaje = errors.mensajeErrorJson(erroresMensaje)
                        $("#body-errors-modal").html(`<div class="col-12 text-danger text-center">${mensaje}</div>`)
                        $('#errorsModal').modal('show') 
                       // return false
                    } 
                }
                if(res.jqXHR.status){
                    let mensaje = errors.codigos(res.jqXHR.status) 
                    alert(mensaje) 
                    return false
                }
                
                return false;
             }

             //console.log("Paso la data, el resultado es: ",res)
 

             $("#body-success-modal").html(`<div class="col-12 text-success text-center">${res.response.mensaje}</div>`)
             $("#successModal").modal("show");

             if (_THIS_DELETE.parent(".items_child").length  > 0) {
               // console.log("existe el items_child",_THIS_DELETE.parent(".items_child"))
                _THIS_DELETE.parent(".items_child").remove()
            }else{
              //  console.log("no existe el items child",_THIS_DELETE.parent(".items_child"))
                _THIS_DELETE.parent("#principal").remove()
            }

            $('#dataDecisionArbol').DataTable().destroy();

            peticiones.rearmandoDataRamasTabla(res.response.list,res.response.nombreTabla)
 
            peticiones.cargaDetallesRamas()
             
        })


    })

    function recorrerElementosHijos(principal){
   
        // console.log("el padre child : ",principal)
        
        let hijosLista = principal.children 
        ////console.log("los hijos del padre son:  ",hijosLista)
        
        for (let index = 0; index < hijosLista.length; index++) {
    
            hijosLista[index].children[0].style.background="#eecbcb"
                    DELETE_ARRAY.push({
                        "paso":hijosLista[index].children[0].getAttribute("data-1"),
                        "id":hijosLista[index].children[0].getAttribute("data-id")
                    })
            
            if(hijosLista[index].children[1]){
            
                recorrerElementosHijos(hijosLista[index].children[1])
            
            }
    
        }
    
    }
     
    
})