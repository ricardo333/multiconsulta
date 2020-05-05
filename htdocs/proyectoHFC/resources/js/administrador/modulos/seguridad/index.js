import valida from  "@/globalResources/forms/valida.js"
import errors from  "@/globalResources/errors.js"

$(function() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(".updateSeguridadBtn").click(function(){
      
        let idSeguridad = $(this).data("id")
        
        let texto = $(this).data("texto")
        let valor = $(`#parametersSeguridadUpdate${idSeguridad}`)

        let validacionUpdate = validacionCotinueUpdate(valor,texto)
        if(!validacionUpdate){ 
            return false
        } 

        let _this = $(this)

        _this.removeClass("updateSeguridadBtn")
        _this.children("i").addClass("girador")

        console.log("paso validacion",idSeguridad)
   
        if (idSeguridad) {
            $.ajax({
                url:`/administrador/seguridad/${idSeguridad}/update`,
                method:"post",
                data:{ "periodo":valor.val() },
                dataType: "json", 
            })
            .done(function(data){ 
                console.log(data)

                _this.addClass("updateSeguridadBtn")
                _this.children("i").removeClass("girador")

                if(data.error){
                    $("#body-errors-modal").html(data.error)
                    $('#errorsModal').modal('show') 
                    return false
                }

                 $("#errors_update").html(data)
                $("#body-success-modal").html(`Los datos se actualizarón correctamente.`)
                $("#successModal").modal("show") 
               
            })
            .fail(function(jqXHR, textStatus){ 
                  console.log( "Error: " ,jqXHR, textStatus);
                  _this.addClass("updateSeguridadBtn")
                  _this.children("i").removeClass("girador")
                $("#errors_update").html(jqXHR.responseText) 
                if(jqXHR.responseJSON){
                    if(jqXHR.responseJSON.mensaje){
                        let erroresMensaje = jqXHR.responseJSON.mensaje  //captura objeto
                        let mensaje = errors.mensajeErrorJson(erroresMensaje)
                        $("#errors_Update").html(mensaje)
                        return false
                    } 
                }
                if(jqXHR.status){
                    let mensaje = errors.codigos(jqXHR.status)
                    $("#body-errors-modal").html(mensaje)
                    $('#errorsModal').modal('show')
                    return false
                }

                $("#body-errors-modal").html("hubo un problema en la red, intente nuevamente por favor.")
                $('#errorsModal').modal('show') 
                
            })

        }else{
            $("#body-reload-modal").html(`
                                            <p>No se puede encontrar al parametro de seguridad, recargue la página e intentelo de nuevo.</p>
                                        `)
          $("#reloadModal").modal("show") 
        }
        
 
    })
})

function validacionCotinueUpdate(valor,texto)
{
    
    $(".validateText").removeClass("valida-error-input") 
    $("#errors_update").html(``)

     
    if(!valida.isValidText(valor.val())){
        valida.isValidateInputText(valor)
        $("#errors_update").html(`El campo ${texto} es requerido`)
        return false
    } 
    if(!valida.isValidNumber(valor.val())){
        valida.isValidateInputText(valor)
        $("#errors_update").html(`El campo ${texto} debe ser de formato numérico positivo`)
        return false
    } 
    if(valor.val() < 1){
        valida.isValidateInputText(valor)
        $("#errors_update").html(`El campo ${texto} no puede ser menor a 1.`)
        return false
    }
  
    $(".validateText").removeClass("valida-error-input")
    $("#errors_update").html(``)
 
    return true
}