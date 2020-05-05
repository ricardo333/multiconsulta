import peticiones from './peticiones.js'
import errors from  "@/globalResources/errors.js"

$(function(){

    $("body").on("click",".editFuentes", function(){

        $("#form_update_detail").addClass("d-none")

        peticiones.redirectTabs($("#edicionFuenteTab"))

        let mac = $(this).data("uno")

        $("#form_update_load").html(`<div id="carga_person">
                                        <div class="loader">Loading...</div>
                                    </div>`)
        $("#errors_Update").html("")

        $.ajax({
            url:`/administrador/monitor-fuentes/editar`,
            method:"get",
            data:{
                mac
            },
            dataType: "json", 
        })
        .done(function(data){
  
          //  console.log(data) 

            let resultado = data.response.data
            if (resultado.length == 0) {
                peticiones.redirectTabs($("#monitorFuentesListTab"))
                $("#body-errors-modal").html(`<div class="w-100 text-danger text-center">No se encontro detalles de la fuente.</div>`)
                $('#errorsModal').modal('show')
                return false
            }

            let detalle = resultado[0]

            $("#nodoUpdateFuente").val(detalle.nodo)
            $("#trobaUpdateFuente").val(detalle.troba)
            $("#macUpdateFuente").val(detalle.mac)
            $("#zonalUpdateFuente").val(detalle.zonal)
            $("#distritoUpdateFuente").val(detalle.distrito)
            $("#direccionUpdateFuente").val(detalle.direccion)
            $("#latitudXUpdateFuente").val(detalle.latitudx)
            $("#latitudYUpdateFuente").val(detalle.longitudy)
            $("#marcaTobaUpdateFuente").val(detalle.marcatroba)
            $("#respaldoUpdateFuente").val(detalle.respaldo)
            $("#descripcionUpdateFuente").val(detalle.descricion)
            $("#tieneBateriaUpdateFuente").val(detalle.tienebateria)
            $("#segundaFuenteUpdateFuente").val(detalle.segundafuente)

            $("#errors_Update").html("")
            $("#form_update_load").html("") 
            $("#form_update_detail").removeClass("d-none")
             
      
        })
        .fail(function(jqXHR, textStatus){

            $("#errors_Update").html("")
            $("#form_update_load").html("") 
            $("#form_update_detail").removeClass("d-none")
       
            // console.log("Hay un error en update..")
            //console.log( "Error: " ,jqXHR, textStatus);
              //console.log( "Request failed: " ,jqXHR.responseJSON.mensaje);
            //$("#errors_Update").html(jqXHR.responseText)
            
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

            $("#body-errors-modal").html(`<div class="w-100 text-danger text-center">${erroresPeticion}</div>`)
            $('#errorsModal').modal('show')
            return false
     
        }) 

    })

    $("#actualizarFuente").click(function(){
        ////
        console.log("se deberia enviar, capturar datos..")

        let nodo = $("#nodoUpdateFuente").val()
        let troba = $("#trobaUpdateFuente").val()
        let mac = $("#macUpdateFuente").val()
        let zonal = $("#zonalUpdateFuente").val()
        let distrito = $("#distritoUpdateFuente").val()
        let direccion = $("#direccionUpdateFuente").val()
        let latitudX = $("#latitudXUpdateFuente").val()
        let latitudY = $("#latitudYUpdateFuente").val()
        let marcaToba = $("#marcaTobaUpdateFuente").val()
        let respaldo = $("#respaldoUpdateFuente").val()
        let descripcion = $("#descripcionUpdateFuente").val()
        let tieneBateria = $("#tieneBateriaUpdateFuente").val()
        let segundaFuente = $("#segundaFuenteUpdateFuente").val()

        $("#form_update_detail").addClass("d-none")
  
        $("#form_update_load").html(`<div id="carga_person">
                                        <div class="loader">Loading...</div>
                                    </div>`)
        $("#errors_Update").html("")
            

        $.ajax({
            url:`/administrador/monitor-fuentes/update`,
            method:"post",
            data:{
                nodo,
                troba,
                mac,
                zonal,
                distrito,
                direccion,
                latitudX,
                latitudY,
                marcaToba,
                respaldo,
                descripcion,
                tieneBateria,
                segundaFuente
            },
            dataType: "json", 
        })
        .done(function(data){
  
            //console.log(data) 

            $("#form_update_detail").removeClass("d-none") 
            $("#form_update_load").html("")
            $("#errors_Update").html("")
  
            $("#body-success-modal").html(`<div class="w-100 text-center text-success">Los datos se actualizarón correctamente.</div>`)
            $("#successModal").modal("show") 

            peticiones.redirectTabs($("#monitorFuentesListTab"))
            peticiones.loadMonitorFuentes()
  
             
        })
        .fail(function(jqXHR, textStatus){

            $("#form_update_detail").removeClass("d-none") 
            $("#form_update_load").html("")
            $("#errors_Update").html("")
 
            // console.log("Hay un error en update..")
            //console.log( "Error: " ,jqXHR, textStatus);
            //  //console.log( "Request failed: " ,jqXHR.responseJSON.mensaje);
            //$("#errors_Update").html(jqXHR.responseText)
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

            $("#errors_Update").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                ${erroresPeticion}</div>`); 
            return false
     
        }) 

        

    })

})