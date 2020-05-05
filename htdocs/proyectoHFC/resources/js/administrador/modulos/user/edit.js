import valida from  "@/globalResources/forms/valida.js"
import errors from  "@/globalResources/errors.js"
import peticiones from './peticiones.js'

$(function(){
 

    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

    $("#verPermisosUsuario").click(function(){
         
        let idRol = $("#rolUpdate").val()
       // console.log("el idrol es: ",idRol)
        if(idRol.toLocaleLowerCase() == "seleccionar" || idRol.trim() == ""){
            $("#body-errors-modal").html(`Para mostrar los permisos adicionales debe tener un rol seleccionado.`)
            $("#errorsModal").modal("show") 
            return false
        }
        if (idRol) { 
            if (!INICIAR_PETICION_PERMISOS_CHECK) {
                $("#editPermisosModal").modal("show")
            }else{

                peticiones.seleccionarPermisosByRoles(idRol,"edit",$("#editPermisosModal"),$("#editModulosAndPermisos"),$("#rpta_update_checked_permisos"),PERMISOS_BLOQUEADOS_ROL)
            }
        }else{
            $("#body-errors-modal").html(`Ocurrio un error al traer los permisos del rol seleccionado, intente nuevamente!`)
            $("#errorsModal").modal("show")  
        }
        
    })

    $("#actualizarUsuario").click(function(){

        let validacionUpdate = validacionCotinueUpdate()
        if(!validacionUpdate){ 
            return false
        }

        let usuario = $("#idUpdate").val() 
        let nombre = $("#nombreUpdate").val() 
        let apellidos = $("#apellidosUpdate").val() 
        let documento = $("#documentoUpdate").val() 
        let celular = $("#celularUpdate").val() 
        let correo = $("#correoUpdate").val() 
        let clave = $("#claveUpdate").val() 
        let empresa = $("#empresaUpdate").val() 
        let rol = $("#rolUpdate").val() 
        let estado = $("#estadoUpdate").val()

        let permisos = []
        let permisosGenerales = $("#editModulosAndPermisos input[type=checkbox]")
    
        for (let index = 0; index < permisosGenerales.length; index++) {
         
          if(permisosGenerales[index].checked && permisosGenerales[index].disabled == false){ 
            //formData.append('permisos[]', permisosGenerales[index].value); 
            permisos.push(permisosGenerales[index].value);
     
          }
        }

       // console.log("Los permisos son: ",permisos)

        $("#form_update_detail").css({'display':'none'})
        $("#form_update_load").css({'display':'block'})
        $("#form_update_load").html(`<div id="carga_person">
                                        <div class="loader">Loading...</div>
                                    </div>`) 
       
        $.ajax({
            //url:`/administrador/empresa/${empresa}/rol/${rol}/usuario/${usuario}/update`,
            url:`/administrador/usuario/${usuario}/empresa/${empresa}/rol/${rol}/update`,
            method:"post",
            data:{
                nombre,
                apellidos,
                documento,
                celular,
                correo,
                clave,
                estado,
                permisos,
            },
            dataType: "json", 
        })
        .done(function(data){

            $("#form_update_load").css({'display':'none'})
            $("#form_update_load").html('')
            $("#form_update_detail").css({'display':'flex'})
           // limpia.limpiaFormUser()
    
           // console.log("el result update es: ",data) 
             //$("#errors_Update").html(data)
            if(data.error){
                $("#body-errors-modal").html(data.error)
                $('#errorsModal').modal('show') 
                return false
            }
      
             $("#body-reload-success-modal").html(`Los datos se actualizarón correctamente.`)
             $("#modalSuccessReload").modal("show") 
 
      
        })
        .fail(function(jqXHR, textStatus){
      
            $("#form_update_load").css({'display':'none'})
            $("#form_update_load").html('')
            $("#form_update_detail").css({'display':'flex'})

              console.log("Hay un error en update..")
               console.log( "Error: " ,jqXHR, textStatus);
              //console.log( "Request failed: " ,jqXHR.responseJSON.mensaje);
              // $("#errors_Update").html(jqXHR.responseText)
              //  return false

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

            $("#errors_Update").html(erroresPeticion)
            return false
            
                
          }) 

    })

    $("#rolUpdate").change(function(){
        $("#editModulosAndPermisos input[type='checkbox']").prop('checked', false)
        //$("#editModulosAndPermisos input[type='checkbox']").prop('disabled', false)
        INICIAR_PETICION_PERMISOS_CHECK = true
    })

    $("#verPasswordUser").click(function(){

        let _this = $(this).children("i")
        //console.log("el ellemento hijo es: ",_this)
        peticiones.transformTextPassword($("#claveUpdate"),_this)
    })
     
    loadEditModulosPermisosUser().then(function(){
        checkedPermisosRol()
        checkedPermisosUser()
    });

    $('#modalSuccessReload').on('shown.bs.modal', function (e) {
        $(this).keydown(function(e){
          if(e.which == 13) {
            $('#modalSuccessReload').modal('hide');
          }
          });
      });
      $('#modalSuccessReload').on('hidden.bs.modal', function (e) {
        
            location.reload();
      });


})

function validacionCotinueUpdate()
{
    let nombre = $("#nombreUpdate") 
    let apellidos = $("#apellidosUpdate") 
    let dni = $("#documentoUpdate") 
    let celular = $("#celularUpdate") 
    let correo = $("#correoUpdate") 
    let clave = $("#claveUpdate")
    let empresa = $("#empresaUpdate") 
    let rol = $("#rolUpdate") 
        
    $(".validateText").removeClass("valida-error-input")
    $(".validateSelect").removeClass("valida-error-input")
    $("#errors_Update").html(``)

    if(!valida.isValidText(nombre.val())){
        valida.isValidateInputText(nombre)
        $("#errors_Update").html(`El campo nombre es requerido`)
        return false
    } 
    if(!valida.isValidLetters(nombre.val())){
        valida.isValidateInputText(nombre)
        $("#errors_Update").html(`El campo nombre debe ser solamente de formato texto`)
        return false
    } 

    if(!valida.isValidText(apellidos.val())){
        valida.isValidateInputText(apellidos)
        $("#errors_Update").html(`El campo apellidos es requerido`)
        return false
    } 
    if(!valida.isValidLetters(apellidos.val())){
        valida.isValidateInputText(apellidos)
        $("#errors_Update").html(`El campo apellidos debe ser solamente de formato texto`)
        return false
    }
    
    if(!valida.isValidText(dni.val())){
        valida.isValidateInputText(dni)
        $("#errors_Update").html(`El campo dni es requerido`)
        return false
    } 
    if(!valida.isValidNumber(dni.val())){
        valida.isValidateInputText(dni)
        $("#errors_Update").html(`El campo dni debe ser de formato numérico`)
        return false
    } 
    if(dni.val().length > 8 || dni.val().length < 8){
        valida.isValidateInputText(dni)
        $("#errors_Update").html(`El campo dni debe tener una logintud de 8 dígitos`)
        return false
    }

    if(!valida.isValidText(celular.val())){
        valida.isValidateInputText(celular)
        $("#errors_Update").html(`El campo celular es requerido`)
        return false
    } 
    if(!valida.isValidNumber(celular.val())){
        valida.isValidateInputText(celular)
        $("#errors_Update").html(`El campo celular debe ser de formato numérico`)
        return false
    } 
    if(celular.val().length > 9 || celular.val().length < 9){
        valida.isValidateInputText(celular)
        $("#errors_Update").html(`El campo celular debe tener una logintud de 9 dígitos`)
        return false
    }
    
    if(!valida.isValidEmail(correo.val())){
        valida.isValidateInputText(correo)
        $("#errors_Update").html(`El correo no tiene un formato válido`)
        return false
    }

    if(!valida.isValidText(empresa.val())){
        valida.isValidateInputText(empresa)
        $("#errors_Update").html(`El campo empresa es requerido`)
        return false
    }  
    if(empresa.val().toLowerCase() == "seleccionar"){
        valida.isValidateInputText(empresa)
        $("#errors_Update").html(`Seleccione una empresa válida`)
        return false
    }
    if(!valida.isValidNumber(empresa.val())){
        valida.isValidateInputText(empresa)
        $("#errors_Update").html(`Seleccione una empresa válida`)
        return false
    } 
    
    if(!valida.isValidText(rol.val())){
        valida.isValidateInputText(rol)
        $("#errors_Update").html(`El campo rol es requerido`)
        return false
    } 
    if(rol.val().toLowerCase() == "seleccionar"){
        valida.isValidateInputText(rol)
        $("#errors_Update").html(`Seleccione un rol válido`)
        return false
    }
    if(!valida.isValidNumber(rol.val())){
        valida.isValidateInputText(rol)
        $("#errors_Update").html(`Seleccione un rol válido`)
        return false
    } 

    if (clave.val().trim().length > 0) {
        if(!valida.isValidPassword(clave.val())){
            valida.isValidateInputText(clave)
            $("#errors_Update").html(`El campo clave no tiene el formato correcto`)
            return false
        }
    } 
  
    $(".validateText").removeClass("valida-error-input")
    $(".validateSelect").removeClass("valida-error-input")
    $("#errors_Update").html(``)


    return true
    
}
 
function checkedPermisosRol()
{
    let checkedUsers = PERMISOS_CHECKED_ROL.response.data
    // console.log("Los checks a recorrer son: ",checkedUsers)
    checkedUsers.forEach(el => {
         $(`input#checkedit`+el.identificador).prop('checked', true)
         $(`input#checkedit`+el.identificador).parent().addClass("permisosRolColor");
        //$(`input#checkedit`+el.identificador).prop('disabled', true) //descomentar luego
         
    });

    if (PERMISOS_BLOQUEADOS_ROL.response) {
        if (PERMISOS_BLOQUEADOS_ROL.response.length > 0) {
            PERMISOS_BLOQUEADOS_ROL.response.forEach(el => {
                //console.log(el);
                 $(`input#checkedit`+el).prop('checked', false)
                 $(`input#checkedit`+el).parent().addClass("permisosRolColor");
            })
        }
    }
 
}
function checkedPermisosUser()
{
     
    let checkedUsers = PERMISOS_CHECKED_USER.response.data

    checkedUsers.forEach(el => {
        $(`input#checkedit`+el.identificador).prop('checked', true)
    });
 
}

async function loadEditModulosPermisosUser()
{
    //Armando esquema
    let dataModulos = MODULOS.response.data;
    peticiones.armandoEsquemaModulosPermisos(dataModulos,"edit",$("#editModulosAndPermisos"))
 
}

 