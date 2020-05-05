import peticiones from './peticiones.js'
import valida from  "@/globalResources/forms/valida.js"
import errors from  "@/globalResources/errors.js"

$(function(){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("#jefatura").change(function(){
       // console.log("Se genero un change en el select de jefaturas..")
        let jefatura = $(this).val()
        //console.log(jefatura);

        peticiones.cargaNodosProjefatura(jefatura,function(res){ 
            
            //Errores
            if(res.error == "failed"){
    
                // console.log("Error: ",res.errorThrown,res.jqXHR,res.textStatus) 
                //$("#resultado_cuarentenas_edit").html(`<div class="col-12 text-danger text-center">${res.jqXHR.responseText}</div>`); 
                //return false
 
                let erroresPeticion =""

                if(res.jqXHR.status){
                    let mensaje = errors.codigos(res.jqXHR.status)
                    erroresPeticion += `<strong> ${mensaje} : </strong>`
                }
                if(res.jqXHR.responseJSON){
                    if(res.jqXHR.responseJSON.mensaje){
                        let erroresMensaje = res.jqXHR.responseJSON.mensaje  //captura objeto
                        let mensaje = errors.mensajeErrorJson(erroresMensaje)
                        erroresPeticion += "<br/>"+ mensaje
                    } 
                }
                
                erroresPeticion = (erroresPeticion.trim() == 0) ? "hubo un error en el servicio, intente nuevamente." : erroresPeticion

                return false

            }
            
            //console.log("la data return detalle es: ",res)
            $("#nodo").html("")
            $("#nodo").append(`<option value="">Todos</option>`)
            res.response.nodos.forEach(el => {
                $("#nodo").append(`<option value="${el.nodo}">${el.nodo}</option>`)
            })

        })

    })

})