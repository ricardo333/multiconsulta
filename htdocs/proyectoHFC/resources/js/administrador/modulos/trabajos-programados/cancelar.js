import peticiones from './peticiones.js'
import errors from  "@/globalResources/errors.js"

$(function(){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("body").on("click",".cancelarTP", function(){

         let item = $(this).data("uno")

         peticiones.redirectTabs($("#CancelartrabajoProgtab"))

         $("#contentCancelarTP").html(`<div id="carga_person">
                                            <div class="loader">Loading...</div>
                                        </div>`)
 

        $.ajax({
            url:`/administrador/trabajos-programados/${item}/detalle`,
            method:"get",
            dataType: "json", 
        })
        .done(function(data){
             console.log("la data return detalle es: ",data)

            let resultado = data.response.data
 
            let armandoEstructura = ` 
                <div id="preProcesarCancelacionTP"></div>
                <div class="form row my-2 mx-0" id="contentResultadoDetalleTP">`

            armandoEstructura += peticiones.armaEstructuraDetalleTP(resultado)
                    
            armandoEstructura += `  <div class="form-group row mx-0 px-2 col-12">
                                        <label for="observacionCancelarTP" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">OBSERVACIONES AL CANCELAR: </label>
                                        <textarea class="form-control form-control-sm shadow-sm validateText" id="observacionCancelarTP" rows="3" style="max-height:115px;min-height:115px;"></textarea>
                                    </div>
                                    <div class="form-group row mx-0 px-2 col-12 justify-content-center">
                                        <a href="javascript:void(0)" data-uno="${resultado.ITEM}" class="btn btn-sm btn-outline-danger shadow-sm" id="cancelarTrabajoProgramadoSend">Cancelar Trabajo Programado</a>
                                    </div>
                </div>
                ` 
                $("#contentCancelarTP").html(armandoEstructura)

        })
        .fail(function(jqXHR, textStatus){
      
            //console.log( "Error: " ,jqXHR, textStatus); 
            // console.log( "Request failed: " ,jqXHR.responseJSON.mensaje);
            //$("#contentCancelarTP").html(jqXHR.responseText)
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
     
             $("#contentCancelarTP").html(`<div class="w-100 text-danger justify-content-center">${erroresPeticion}</div>`) 
             return false
       
          }) 

         
    })

    $("body").on("click","#cancelarTrabajoProgramadoSend", function(){
       // 

       let item = $(this).data("uno")

       $("#preProcesarCancelacionTP").html(`<div id="carga_person">
                                                <div class="loader">Loading...</div>
                                            </div>`)

        $("#contentResultadoDetalleTP").addClass("d-none")

        $.ajax({
            url:`/administrador/trabajos-programados/${item}/cancelar`,
            method:"post",
            dataType: "json", 
        })
        .done(function(data){
            //console.log("la data return detalle ses: ",data)

            $("#preProcesarCancelacionTP").html(``) 
            $("#contentResultadoDetalleTP").html(``) 
            $("#contentResultadoDetalleTP").removeClass("d-none")

            let valorFiltroZona = $("#listaZonasFiltro").val()
            let parametros = {'zona':valorFiltroZona}
            let columnas = peticiones.armandoColumnasTP()
            peticiones.loadTrabajosProgramadosList(columnas,parametros)

            peticiones.redirectTabs($('#listaTrabajoPTab')) 

 
        })
        .fail(function(jqXHR, textStatus){
    
            //console.log( "Error: " ,jqXHR, textStatus); 
            // console.log( "Request failed: " ,jqXHR.responseJSON.mensaje);
            //$("#preProcesarCancelacionTP").html(jqXHR.responseText)
            //return false

            $("#preProcesarCancelacionTP").html(``)  
            $("#contentResultadoDetalleTP").removeClass("d-none")
      
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
     
            $("#preProcesarCancelacionTP").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            ${erroresPeticion}</div>`)

            return false
    
        }) 
    })
})