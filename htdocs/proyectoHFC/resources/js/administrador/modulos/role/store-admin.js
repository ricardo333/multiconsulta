import peticiones from './peticiones.js'
$(function(){

    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

       
    $("#referenciaStore").change(function(){
      //storeModulosAndPermisosList
      let idRol = $("#referenciaStore").val()
      console.log("el idrol es: ",idRol)
      if(idRol.toLocaleLowerCase() == "seleccionar" || idRol.trim() == ""){
            MODULOS = MODULOS_AUTH.response.data
            PERMISOS_ROL = PERMISOS_ROL_AUTH.response.data
            peticiones.armandoEsquemaModulosPermisos(MODULOS,"store",$("#storeModulosAndPermisosList"))
          return false
      }
      if(idRol){  
          
            $("#referenciaStore").prop("disabled",true)
            $("#storeModulosAndPermisosList").html(`<div id="carga_person">
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
                    $("#storeModulosAndPermisosList").html("<p class='text-center'>El rol padre no tiene permisos asignados.</p>")
                    return false
                }
                    MODULOS = peticiones.filtrarModulosPorPermisos(permisosSegunRol)
                    PERMISOS_ROL = permisosSegunRol
                    peticiones.armandoEsquemaModulosPermisos(MODULOS,"store",$("#storeModulosAndPermisosList"))
 
                
            })
            .fail(function(jqXHR, textStatus){
                console.log( "Request failed: " ,textStatus ,jqXHR);
                $("#storeModulosAndPermisosList").html(``)
             
                $("#body-errors-modal").html(`Hubo un error en el servicio de permisos, intente seleccionar el rol padre nuevamente por favor!`)
                $('#errorsModal').modal('show') 
                //console.log( "Request failed: " ,jqXHR.responseJSON.mensaje);

            });

            $("#referenciaStore").prop("disabled",false)

      }else{
          $("#body-errors-modal").html(`Ocurrio un error al traer los permisos del rol padre seleccionado, intente nuevamente!`)
          $("#errorsModal").modal("show")  
      }
    })

    $("#especialStore").change(function(){
        if($(this).val() == "SI"){
            $("#referenciaStore")[0].selectedIndex = 0
            $("#referenciaStore").prop("disabled",true)
            
            $("#storeModulosAndPermisosList").html("")
        }else{
            $("#referenciaStore").prop("disabled",false)
            $("#storeModulosAndPermisosList input[type=checkbox]").prop('checked', false)
            $("#storeModulosAndPermisosList input[type=checkbox]").prop("disabled",false)
            MODULOS = MODULOS_AUTH.response.data
            PERMISOS_ROL = PERMISOS_ROL_AUTH.response.data
            peticiones.armandoEsquemaModulosPermisos(MODULOS,"store",$("#storeModulosAndPermisosList"))
        }
    })
 
})
 
 


 