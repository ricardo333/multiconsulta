import peticiones from './peticiones.js'
$(function(){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("#referenciaUpdate").change(function(){
    let idRol = $("#referenciaUpdate").val()

    if(idRol.toLocaleLowerCase() == "seleccionar" || idRol.trim() == ""){
            MODULOS = MODULOS_AUTH.response.data
            PERMISOS_ROL = PERMISOS_ROL_AUTH.response.data
            peticiones.armandoEsquemaModulosPermisos(MODULOS,"edit",$("#updateModulosAndPermisosList"))

            let checkedUsers = PERMISOS_CHECKED.response.data
            peticiones.checkedPermisosRol(checkedUsers,"edit")

        return false
    }
    if(idRol){   

            $("#referenciaUpdate").prop("disabled",true)
            $("#updateModulosAndPermisosList").html(`<div id="carga_person">
                                                    <div class="loader">Loading...</div>
                                                    </div>`)
            
            $.ajax({
                url:`/administrador/roles/${parseInt(idRol)}/permisos`,
                method:"get",
                dataType: "json", 
            })
            .done(function(data){
                //console.log("los permisos del rol seleccionado:", data)
                let permisosSegunRol = data.response.data
            
                if(permisosSegunRol.length <= 0){
                    $("#updateModulosAndPermisosList").html("<p class='text-center'>El rol padre no tiene permisos asignados.</p>")
                    return false
                }
                    MODULOS = peticiones.filtrarModulosPorPermisos(permisosSegunRol)
                    PERMISOS_ROL = permisosSegunRol 
                    peticiones.armandoEsquemaModulosPermisos(MODULOS,"edit",$("#updateModulosAndPermisosList"))

                    let checkedUsers = PERMISOS_CHECKED.response.data
                    
                    peticiones.checkedPermisosRol(checkedUsers,"edit") 
                
            })
            .fail(function(jqXHR, textStatus){
                console.log( "Request failed: " ,textStatus ,jqXHR);
                $("#updateModulosAndPermisosList").html(``)
            
                $("#body-errors-modal").html(`Hubo un error en el servicio de permisos, intente seleccionar el rol padre nuevamente por favor!`)
                $('#errorsModal').modal('show') 
                //console.log( "Request failed: " ,jqXHR.responseJSON.mensaje);

            });

            $("#referenciaUpdate").prop("disabled",false)

    }else{
        $("#body-errors-modal").html(`No se puede identificar al rol padre, intente seleccionar nuevamente!`)
        $("#errorsModal").modal("show")  
    }

    })

    $("#especialUpdate").change(function(){
    if($(this).val() == "SI"){
        $("#referenciaUpdate")[0].selectedIndex = 0
        $("#referenciaUpdate").prop("disabled",true)
        
        $("#updateModulosAndPermisosList").html("")
    }else{
        $("#referenciaUpdate").prop("disabled",false)
        $("#updateModulosAndPermisosList input[type=checkbox]").prop('checked', false)
        $("#updateModulosAndPermisosList input[type=checkbox]").prop("disabled",false)

        MODULOS = MODULOS_AUTH.response.data
        PERMISOS_ROL = PERMISOS_ROL_AUTH.response.data
        peticiones.armandoEsquemaModulosPermisos(MODULOS,"update",$("#updateModulosAndPermisosList"))
 
    }
})
})