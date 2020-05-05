import valida from  "@/globalResources/forms/valida.js"
import errors from  "@/globalResources/errors.js"
import peticiones from './peticiones.js'

$(function(){

    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

    
    $("#actualizarRol").click(function(){
        actualizarRol()
    })
 
     
    loadEditModulosPermisosUser().then(function(){
        let checkedUsers = PERMISOS_CHECKED.response.data
        peticiones.checkedPermisosRol(checkedUsers,"edit") 
    });


})

    function actualizarRol()
    {
        let validacionConitnueStore = validacionCotinueUpdate()
            if(!validacionConitnueStore){ 
                return false
            }
            
        //Actualizar
        let rolUpdateId = $("#idUpdate").val()
        let datos = {}
        let rol = $("#nombreUpdate").val()
        let estado = $("#estadoUpdate").val()
        datos.rol = rol
        datos.estado = estado
        
        
        if ($("#especialUpdate")) {
            let esAdministrador = $("#especialUpdate").val()
            datos.esAdministrador = esAdministrador
        }
        if ($("#referenciaUpdate")) {
            let rolPadre = $("#referenciaUpdate").val()
            datos.rolPadre = rolPadre
        }

        let permisos = []
        let permisosGenerales = $("#updateModulosAndPermisosList input[type=checkbox]")
        
        for (let index = 0; index < permisosGenerales.length; index++) {
        
        if(permisosGenerales[index].checked && permisosGenerales[index].disabled == false){ 
            //formData.append('permisos[]', permisosGenerales[index].value); 
            permisos.push(permisosGenerales[index].value); 
        }
        }
        datos.permisos = permisos
    
        $("#form_update_detail").css({'display':'none'})
        $("#form_update_load").css({'display':'block'})
        $("#form_update_load").html(`<div id="carga_person">
                                    <div class="loader">Loading...</div>
                                    </div>`) 

        $.ajax({
            url:`/administrador/rol/${rolUpdateId}/update`,
            method:"post",
            data:datos,
            dataType: "json", 
        })
        .done(function(data){

            $("#form_update_load").css({'display':'none'})
            $("#form_update_load").html('')
            $("#form_update_detail").css({'display':'flex'})
           

           // console.log(data)
            $("#errors_update").html(data)
            if(data.error){
                $("#body-errors-modal").html(data.error)
                $('#errorsModal').modal('show') 
                return false
            }

            let rol = data.response.data
            
            $("#body-success-modal").html(`
            <h5 class="text-success text-center text-uppercase font-weight-bold">Rol actualizado</h5>
            <p class="text-center font-weight-bold font-italic">Se actualizó el rol ${rol.rol} correctamente</p>
            
            `)
            $("#successModal").modal("show")
    
        })
        .fail(function(jqXHR, textStatus){
        
        $("#form_update_load").css({'display':'none'})
        $("#form_update_load").html('')
        $("#form_update_detail").css({'display':'flex'})

            //console.log( "Error: " ,jqXHR, textStatus);
            //console.log( "Request failed: " ,jqXHR.responseJSON.mensaje);
            //$("#errors_update").html(jqXHR.responseText)
            // return false
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
        let estado = $("#estadoUpdate") 
        
            
        $(".validateText").removeClass("valida-error-input")
        $(".validateSelect").removeClass("valida-error-input")
        $("#errors_update").html(``)

        if(!valida.isValidText(nombre.val())){
            valida.isValidateInputText(nombre)
            $("#errors_update").html(`El campo nombre es requerido`)
            return false
        } 

        if(!valida.isValidText(estado.val())){
            valida.isValidateInputText(estado)
            $("#errors_store").html(`El campo estado es requerido`)
            return false
        }  
        if(estado.val().toLowerCase() == "seleccionar"){
            valida.isValidateInputText(estado)
            $("#errors_store").html(`Seleccione un estado válido`)
            return false
        }
        if(!valida.isValidNumber(estado.val())){
            valida.isValidateInputText(estado)
            $("#errors_store").html(`Seleccione un estado válido`)
            return false
        } 
        
        $(".validateText").removeClass("valida-error-input")
        $(".validateSelect").removeClass("valida-error-input")
        $("#errors_update").html(``)
  
        return true
        
    }
  
async function loadEditModulosPermisosUser()
{
    //Armando esquema
    let dataModulos = MODULOS.response.data
    PERMISOS_ROL = PERMISOS_ROL.response.data
    peticiones.armandoEsquemaModulosPermisos(dataModulos,"edit",$("#updateModulosAndPermisosList"))
 
}

 