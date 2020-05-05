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

      
    loadStoreModulosPermisosUser()
 
    $("#crearRol").click(function(){
        registroRolStore()
    })
  
})

function loadStoreModulosPermisosUser()
{
    //Armando esquema
    let dataModulos = MODULOS.response.data

    PERMISOS_ROL = PERMISOS_ROL.response.data
    
    peticiones.armandoEsquemaModulosPermisos(dataModulos,"store",$("#storeModulosAndPermisosList"))
 
}

function registroRolStore()
{
  let validacionConitnueStore = validacionContinueStore()
    if(!validacionConitnueStore){ 
        return false
    }
    
 //registrar
    let datos = {}
    let rol = $("#nombreStore").val()
    let estado = $("#estadoStore").val()
    datos.rol = rol
    datos.estado = estado
    
    
    if ($("#especialStore")) {
      let esAdministrador = $("#especialStore").val()
      datos.esAdministrador = esAdministrador
    }
    if ($("#referenciaStore")) {
      let rolPadre = $("#referenciaStore").val()
      datos.rolPadre = rolPadre
    }

    let permisos = []
    let permisosGenerales = $("#storeModulosAndPermisosList input[type=checkbox]")
    
    for (let index = 0; index < permisosGenerales.length; index++) {
     
      if(permisosGenerales[index].checked && permisosGenerales[index].disabled == false){ 
        //formData.append('permisos[]', permisosGenerales[index].value); 
        permisos.push(permisosGenerales[index].value); 
      }
    }
    datos.permisos = permisos

    //console.log("los datos enviados son: ",datos)
 
    $("#form_store_detail").css({'display':'none'})
    $("#form_store_load").css({'display':'block'})
    $("#form_store_load").html(`<div id="carga_person">
                                  <div class="loader">Loading...</div>
                                </div>`) 

    $.ajax({
        url:`/administrador/rol/store`,
        method:"post",
        data:datos,
        dataType: "json", 
    })
    .done(function(data){

        $("#form_store_load").css({'display':'none'})
        $("#form_store_load").html('')
        $("#form_store_detail").css({'display':'flex'})
        limpia.limpiaFormRol()

         //console.log(data)
         $("#errors_store").html(data)
        if(data.error){
            $("#body-errors-modal").html(data.error)
            $('#errorsModal').modal('show') 
            return false
        }

         let rol = data.response.data
        
         $("#body-success-modal").html(`
          <h5 class="text-success text-center text-uppercase font-weight-bold">Rol creado correctamente</h5>
          <p class="text-center font-weight-bold font-italic">El rol ${rol.rol} se creo corretamente </p>
           
         `)
        $("#successModal").modal("show")
  
    })
    .fail(function(jqXHR, textStatus){
      
      $("#form_store_load").css({'display':'none'})
      $("#form_store_load").html('')
      $("#form_store_detail").css({'display':'flex'})

        //console.log( "Error: " ,jqXHR, textStatus);
        //console.log( "Request failed: " ,jqXHR.responseJSON.mensaje);
        //$("#errors_store").html(jqXHR.responseText)
        //return false
        if(jqXHR.responseJSON){
            if(jqXHR.responseJSON.mensaje){
                let erroresMensaje = jqXHR.responseJSON.mensaje  //captura objeto
                let mensaje = errors.mensajeErrorJson(erroresMensaje)
                $("#errors_store").html(mensaje)
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

function validacionContinueStore()
{
  let nombre = $("#nombreStore")  
  let estado = $("#estadoStore")  
    
  $(".validateText").removeClass("valida-error-input")
  $(".validateSelect").removeClass("valida-error-input")
  $("#errors_store").html(``)

  if(!valida.isValidText(nombre.val())){
    valida.isValidateInputText(nombre)
    $("#errors_store").html(`El campo nombre es requerido`)
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
  $("#errors_store").html(``)
 
  return true
 
}
 


 