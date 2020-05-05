import valida from  "@/globalResources/forms/valida.js"
import errors from  "@/globalResources/errors.js"

$(function(){

    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

    
    $("#actualizarEmpresa").click(function(){
        actualizarEmpresa()
    })
  

})

    function actualizarEmpresa()
    {
        let validacionConitnueStore = validacionCotinueUpdate()
            if(!validacionConitnueStore){ 
                return false
            }
        //Actualizar
        let idEmpresa = $("#idUpdate").val()
        
        let empresa = $("#nombreUpdate").val()
       
        $("#form_update_detail").css({'display':'none'})
        $("#form_update_load").css({'display':'block'})
        $("#form_update_load").html(`<div id="carga_person">
                                    <div class="loader">Loading...</div>
                                    </div>`) 

        $.ajax({
            url:`/administrador/empresa/${idEmpresa}/update`,
            method:"post",
            data:{empresa},
            dataType: "json", 
        })
        .done(function(data){

            $("#form_update_load").css({'display':'none'})
            $("#form_update_load").html('')
            $("#form_update_detail").css({'display':'flex'})
           

           // console.log(data)
           // $("#errors_update").html(data)
            if(data.error){
                $("#body-errors-modal").html(data.error)
                $('#errorsModal').modal('show') 
                return false
            }

            let empresa = data.response.data
            
            $("#body-success-modal").html(`
            <h5 class="text-success text-center text-uppercase font-weight-bold">Empresa actualizada</h5>
            <p class="text-center font-weight-bold font-italic">Se actualizó la empresa: ${empresa.empresa} correctamente</p>
            
            `)
            $("#successModal").modal("show")
    
        })
        .fail(function(jqXHR, textStatus){
        
        $("#form_update_load").css({'display':'none'})
        $("#form_update_load").html('')
        $("#form_update_detail").css({'display':'flex'})

            console.log( "Error: " ,jqXHR, textStatus);
            //console.log( "Request failed: " ,jqXHR.responseJSON.mensaje);
            //$("#errors_update").html(jqXHR.responseText)
            if(jqXHR.responseJSON){
                if(jqXHR.responseJSON.mensaje){
                    let erroresMensaje = jqXHR.responseJSON.mensaje  //captura objeto
                    let mensaje = errors.mensajeErrorJson(erroresMensaje)
                    $("#errors_update").html(mensaje)
                    return false
                } 
            }
            if(jqXHR.status){
                let mensaje = errors.codigos(jqXHR.status)
                $("#body-errors-modal").html(mensaje)
                $('#errorsModal').modal('show')
                return false
            }
        $("#body-errors-modal").html("hubo un problema en la red del internet, intente nuevamente por favor.")
            $('#errorsModal').modal('show') 
        }) 
    }

    function validacionCotinueUpdate()
    {
        let nombre = $("#nombreUpdate") 
        
            
        $(".validateText").removeClass("valida-error-input")
        $(".validateSelect").removeClass("valida-error-input")
        $("#errors_update").html(``)

        if(!valida.isValidText(nombre.val())){
            valida.isValidateInputText(nombre)
            $("#errors_update").html(`El campo nombre es requerido`)
            return false
        } 
        
        $(".validateText").removeClass("valida-error-input")
        $(".validateSelect").removeClass("valida-error-input")
        $("#errors_update").html(``)
  
        return true
        
    }
  
 

 