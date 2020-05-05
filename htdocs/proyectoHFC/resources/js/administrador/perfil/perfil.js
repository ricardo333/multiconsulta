import valida from  "@/globalResources/forms/valida.js"
import errors from  "@/globalResources/errors.js"

$(function(){

    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      $("#actualizarPerfil").click(function(){
          
        let validacionUpdate = validacionCotinueUpdate()
        if(!validacionUpdate){ 
            return false
        }

        let usuario = $("#idUpdate").val() 
        
        let documento = $("#documentoUpdate").val() 
        let celular = $("#celularUpdate").val() 
        let correo = $("#correoUpdate").val() 

        $("#form_update_detail").css({'display':'none'})
        $("#form_update_load").css({'display':'block'})
        $("#form_update_load").html(`<div id="carga_person">
                                  <div class="loader">Loading...</div>
                                </div>`) 

         
        $.ajax({
            url:`/perfil/usuario/${usuario}/update`,
            method:"post",
            data:{
                documento,
                celular,
                correo
            },
            dataType: "json", 
        })
        .done(function(data){

            $("#form_update_load").css({'display':'none'})
            $("#form_update_load").html('')
            $("#form_update_detail").css({'display':'flex'})
           // limpia.limpiaFormUser()
    
             console.log(data)
             $("#errors_update").html(data)
            if(data.error){
                $("#body-errors-modal").html(data.error)
                $('#errorsModal').modal('show') 
                return false
            }
      
             $("#body-success-modal").html(`Sus datos se actualizarón correctamente.`)
             $("#successModal").modal("show") 
      
        })
        .fail(function(jqXHR, textStatus){
      
            $("#form_update_load").css({'display':'none'})
            $("#form_update_load").html('')
            $("#form_update_detail").css({'display':'flex'})
    
            console.log( "Error: " ,jqXHR, textStatus);
            //console.log( "Request failed: " ,jqXHR.responseJSON.mensaje);
            $("#errors_update").html(jqXHR.responseText)
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

      })

      $("#verPasswordUser").click(function(){
          let passInput = $("#claveUpdate")
          let _this = $(this).children("i")
           
            if(_this.hasClass("fa-eye-slash")){
            _this.removeClass("fa-eye-slash")
            _this.addClass("fa-eye")
            passInput.prop('type','password');
            }else{
            _this.removeClass("fa-eye")
            _this.addClass("fa-eye-slash")
            passInput.prop('type','text');
            }
             
      })

      $("#actualizarPassword").click(function(){
        let validacionUpdatePassword = validacionCotinueUpdatePassword()
        if(!validacionUpdatePassword){ 
            return false
        } 

        let usuario = $("#idUpdate").val()  
        let clave = $("#claveUpdate").val() 

        $("#form_updatePassword_detail").css({'display':'none'})
        $("#form_updatePassword_load").css({'display':'block'})
        $("#form_updatePassword_load").html(`<div id="carga_person">
                                  <div class="loader">Loading...</div>
                                </div>`) 


        $.ajax({
            url:`/perfil/usuario/${usuario}/password/update`,
            method:"post",
            data:{ clave },
            dataType: "json", 
        })
        .done(function(data){

            $("#form_updatePassword_load").css({'display':'none'})
            $("#form_updatePassword_load").html('')
            $("#form_updatePassword_detail").css({'display':'flex'})
           // limpia.limpiaFormUser()
    
            // console.log(data)
            // $("#errors_update_password").html(data)
            if(data.error){
                $("#body-errors-modal").html(data.error)
                $('#errorsModal').modal('show') 
                return false
            }
      
            $("#claveUpdate").val("")

            $("#body-success-modal").html(`Se actualizó su contraseña correctamente.`)
            $("#successModal").modal("show") 
      
        })
        .fail(function(jqXHR, textStatus){
      
            $("#form_updatePassword_load").css({'display':'none'})
            $("#form_updatePassword_load").html('')
            $("#form_updatePassword_detail").css({'display':'flex'})
      
              //console.log( "Error: " ,jqXHR, textStatus);
              //console.log( "Request failed: " ,jqXHR.responseJSON.mensaje);
              // $("#errors_update_password").html(jqXHR.responseText)
              //return false
              if(jqXHR.responseJSON){
                  if(jqXHR.responseJSON.mensaje){
                      let erroresMensaje = jqXHR.responseJSON.mensaje  //captura objeto
                      let mensaje = errors.mensajeErrorJson(erroresMensaje)
                    $("#errors_update_password").html(mensaje)
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
      

      })
})

function validacionCotinueUpdate()
{
   
    let dni = $("#documentoUpdate") 
    let celular = $("#celularUpdate") 
    let correo = $("#correoUpdate") 
    
    $(".validateText").removeClass("valida-error-input")
   
    $("#errors_update").html(``)

    
    if(!valida.isValidText(dni.val())){
        valida.isValidateInputText(dni)
        $("#errors_update").html(`El campo dni es requerido`)
        return false
    } 
    if(!valida.isValidNumber(dni.val())){
        valida.isValidateInputText(dni)
        $("#errors_update").html(`El campo dni debe ser de formato numérico`)
        return false
    } 
    if(dni.val().length > 8 || dni.val().length < 8){
        valida.isValidateInputText(dni)
        $("#errors_update").html(`El campo dni debe tener una logintud de 8 dígitos`)
        return false
    }

    if(!valida.isValidText(celular.val())){
        valida.isValidateInputText(celular)
        $("#errors_update").html(`El campo celular es requerido`)
        return false
    } 
    if(!valida.isValidNumber(celular.val())){
        valida.isValidateInputText(celular)
        $("#errors_update").html(`El campo celular debe ser de formato numérico`)
        return false
    } 
    if(celular.val().length > 9 || celular.val().length < 9){
        valida.isValidateInputText(celular)
        $("#errors_update").html(`El campo celular debe tener una logintud de 9 dígitos`)
        return false
    }
    
    if(!valida.isValidEmail(correo.val())){
        valida.isValidateInputText(correo)
        $("#errors_update").html(`El correo no tiene un formato válido`)
        return false
    }
 
  
    $(".validateText").removeClass("valida-error-input")
    $("#errors_update").html(``)


    return true
    
}


function validacionCotinueUpdatePassword()
{
 
    let clave = $("#claveUpdate")
        
    $(".validateText").removeClass("valida-error-input")
    $("#errors_update_password").html(``)
 
    if(!valida.isValidText(clave.val())){
        valida.isValidateInputText(clave)
        $("#errors_update_password").html(`El campo password es requerido`)
        return false
    }  
     
    if(!valida.isValidPassword(clave.val())){
        valida.isValidateInputText(clave)
        $("#errors_update_password").html(`El campo clave no tiene el formato correcto`)
        return false
    }
  
    $(".validateText").removeClass("valida-error-input") 
    $("#errors_update_password").html(``)


    return true
    
}