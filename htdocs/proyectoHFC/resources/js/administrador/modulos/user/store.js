import valida from  "@/globalResources/forms/valida.js"
import limpia from  "@/globalResources/forms/limpia.js"
import errors from  "@/globalResources/errors.js"
import peticiones from './peticiones.js'

$(function(){

    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("#activarModalPermisos").click(function(){
          
        let idRol = $("#rolStore").val()
        console.log("el idrol es: ",idRol)
        if(idRol.toLocaleLowerCase() == "seleccionar" || idRol.trim() == ""){
            $("#body-errors-modal").html(`Para mostrar los permisos adicionales debe tener un rol seleccionado.`)
            $("#errorsModal").modal("show") 
            return false
        }
        if (idRol) {
          if (!INICIAR_PETICION_PERMISOS_CHECK) {
            $("#addPermisosModal").modal("show")
          }else{
            peticiones.seleccionarPermisosByRoles(idRol,"store",$("#addPermisosModal"),$("#storeModulosAndPermisosList"),$("#rpta_store_checked_permisos"),[]) 
          } 
            
        }else{
            $("#body-errors-modal").html(`Ocurrio un error al traer los permisos del rol seleccionado, intente nuevamente!`)
            $("#errorsModal").modal("show")  
        }
       

    })

    $("#crearUsuario").click(function(){
        registroUserStore()
    })

    $("#rolStore").change(function(){
      $("#storeModulosAndPermisosList input[type='checkbox']").prop('checked', false)
      //$("#storeModulosAndPermisosList input[type='checkbox']").prop('disabled', false)
      INICIAR_PETICION_PERMISOS_CHECK = true
    })

    loadStoreModulosPermisosUser()

    
})

function registroUserStore()
{
   let validacionConitnueStore = validacionContinueStore()
    if(!validacionConitnueStore){ 
        return false
    } 
   
 //luego de validar  
 //registrar
    let nombre = $("#nombreStore").val()
    let apellidos = $("#apellidosStore").val()
    let documento = $("#documentoStore").val()
    let celular = $("#celularStore").val()
    let correo = $("#correoStore").val()
    let empresa = $("#empresaStore").val()
    let rol = $("#rolStore").val()

    let permisos = []
    let permisosGenerales = $("#storeModulosAndPermisosList input[type=checkbox]")
 
    for (let index = 0; index < permisosGenerales.length; index++) {
     
      if(permisosGenerales[index].checked && permisosGenerales[index].disabled == false){ 
        //formData.append('permisos[]', permisosGenerales[index].value); 
        permisos.push(permisosGenerales[index].value);
 
      }
    }

    //console.log("Los permisos a enviarse son: ",permisos)
     
   // console.log("el tipo de permisos es:",typeof(permisos), "permisos son:", permisos)

    $("#form_store_detail").css({'display':'none'})
    $("#form_store_load").css({'display':'block'})
    $("#form_store_load").html(`<div id="carga_person">
                                  <div class="loader">Loading...</div>
                                </div>`) 

    $.ajax({
        url:`/administrador/usuario/empresa/${empresa}/rol/${rol}/store`,
        method:"post",
        data:{
            nombre,
            apellidos,
            documento,
            celular,
            correo,
            permisos
        },
        dataType: "json", 
    })
    .done(function(data){

        $("#form_store_load").css({'display':'none'})
        $("#form_store_load").html('')
        $("#form_store_detail").css({'display':'flex'})
        limpia.limpiaFormUser()

        // console.log(data)
         // $("#errors_store").html(data)
        if(data.error){
            $("#body-errors-modal").html(data.error)
            $('#errorsModal').modal('show') 
            return false
        }

        let usuario = data.data.usuario
        let password = data.data.clave

         $("#body-success-modal").html(`
          <h5 class="text-success text-center text-uppercase font-weight-bold">Usuario creado correctamente</h5>
          <p class="text-center font-weight-bold font-italic">Los accesos del usuario son:</p>
          <div class="table-responsive"> 
          <table class="table table-hover table-bordered w-100">
            <thead>
              <tr>
                <th>Usuario</th>
                <th>Password</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>${usuario}</td>
                <td>${password}</td>
              </tr>
            </tbody>
          </table>
         `)
        $("#successModal").modal("show")
  
    })
    .fail(function(jqXHR, textStatus){
      
      $("#form_store_load").css({'display':'none'})
      $("#form_store_load").html('')
      $("#form_store_detail").css({'display':'flex'})

        // console.log( "Error: " ,jqXHR, textStatus);
        // console.log( "Request failed: " ,jqXHR.responseJSON.mensaje);
       // $("#errors_store").html(jqXHR.responseText) 
       // return false

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

        $("#body-errors-modal").html(erroresPeticion)
        $('#errorsModal').modal('show')
        return false
 
    }) 
}

function validacionContinueStore()
{
  let nombre = $("#nombreStore") 
  let apellidos = $("#apellidosStore") 
  let dni = $("#documentoStore") 
  let celular = $("#celularStore") 
  let correo = $("#correoStore") 
  let empresa = $("#empresaStore") 
  let rol = $("#rolStore") 
    
  $(".validateText").removeClass("valida-error-input")
  $(".validateSelect").removeClass("valida-error-input")
  $("#errors_store").html(``)

  if(!valida.isValidText(nombre.val())){
    valida.isValidateInputText(nombre)
    $("#errors_store").html(`El campo nombre es requerido`)
    return false
  } 
  if(!valida.isValidLetters(nombre.val())){
    valida.isValidateInputText(nombre)
    $("#errors_store").html(`El campo nombre debe ser solamente de formato texto`)
    return false
  } 

  if(!valida.isValidText(apellidos.val())){
    valida.isValidateInputText(apellidos)
    $("#errors_store").html(`El campo apellidos es requerido`)
    return false
  } 
  if(!valida.isValidLetters(apellidos.val())){
    valida.isValidateInputText(apellidos)
    $("#errors_store").html(`El campo apellidos debe ser solamente de formato texto`)
    return false
  }
 
  if(!valida.isValidText(dni.val())){
    valida.isValidateInputText(dni)
    $("#errors_store").html(`El campo dni es requerido`)
    return false
  } 
  if(!valida.isValidNumber(dni.val())){
    valida.isValidateInputText(dni)
    $("#errors_store").html(`El campo dni debe ser de formato numérico`)
    return false
  } 
  if(dni.val().length > 8 || dni.val().length < 8){
    valida.isValidateInputText(dni)
    $("#errors_store").html(`El campo dni debe tener una logintud de 8 dígitos`)
    return false
  }

  if(!valida.isValidText(celular.val())){
    valida.isValidateInputText(celular)
    $("#errors_store").html(`El campo celular es requerido`)
    return false
  } 
  if(!valida.isValidNumber(celular.val())){
    valida.isValidateInputText(celular)
    $("#errors_store").html(`El campo celular debe ser de formato numérico`)
    return false
  } 
  if(celular.val().length > 9 || celular.val().length < 9){
    valida.isValidateInputText(celular)
    $("#errors_store").html(`El campo celular debe tener una logintud de 9 dígitos`)
    return false
  }
 
  if(!valida.isValidEmail(correo.val())){
    valida.isValidateInputText(correo)
    $("#errors_store").html(`El correo no tiene un formato válido`)
    return false
  }

  if(!valida.isValidText(empresa.val())){
    valida.isValidateInputText(empresa)
    $("#errors_store").html(`El campo empresa es requerido`)
    return false
  }  
  if(empresa.val().toLowerCase() == "seleccionar"){
    valida.isValidateInputText(empresa)
    $("#errors_store").html(`Seleccione una empresa válida`)
    return false
  }
  if(!valida.isValidNumber(empresa.val())){
    valida.isValidateInputText(empresa)
    $("#errors_store").html(`Seleccione una empresa válida`)
    return false
  } 
  
  if(!valida.isValidText(rol.val())){
    valida.isValidateInputText(rol)
    $("#errors_store").html(`El campo rol es requerido`)
    return false
  } 
  if(rol.val().toLowerCase() == "seleccionar"){
    valida.isValidateInputText(rol)
    $("#errors_store").html(`Seleccione un rol válido`)
    return false
  }
  if(!valida.isValidNumber(rol.val())){
    valida.isValidateInputText(rol)
    $("#errors_store").html(`Seleccione un rol válido`)
    return false
  } 
 
  $(".validateText").removeClass("valida-error-input")
  $(".validateSelect").removeClass("valida-error-input")
  $("#errors_store").html(``)


  return true
 
}

function loadStoreModulosPermisosUser()
{
    //Armando esquema
    let dataModulos = MODULOS.response.data;
    peticiones.armandoEsquemaModulosPermisos(dataModulos,"store",$("#storeModulosAndPermisosList"))
 
}


 