import errors from '@/globalResources/errors'

const peticiones = {}
  
//General
peticiones.transformTextPassword = function transformTextPassword(dataPassword,_this)
{
    if(_this.hasClass("fa-eye-slash")){
      _this.removeClass("fa-eye-slash")
      _this.addClass("fa-eye")
      dataPassword.prop('type','password');
    }else{
      _this.removeClass("fa-eye")
      _this.addClass("fa-eye-slash")
      dataPassword.prop('type','text');
    }
}
peticiones.traerPermisosPorRol = function traerPermisosPorRol(modulos)
{ 
    let permisos_rol = PERMISOS_ROL.response.data
    
    let ListaPermisos = permisos_rol.filter( (modulo) => {
        if(modulos.identificador == modulo.identificadorModulo){
            return modulo
        }  
    }) 
   // console.log(`los sub permisos del modulo ${modulos.nombre} es: `,ListaPermisos)
    return ListaPermisos
}

peticiones.trearPermisosEspeciales = function trearPermisosEspeciales(modulos){

    let permisos_especiales = PERMISOS_ESPECIALES.response.data

    let ListaPermisos = permisos_especiales.filter( (modulo) => {
        if(modulos.identificador == modulo.identificadorModulo){
            return modulo
        }  
    })
    return ListaPermisos
}
  
peticiones.armandoEsquemaModulosPermisos = function armandoEsquemaModulosPermisos(data,texto,printResult)
{

  let esquemaModulos = `<div class="row">
                            <div class="col-12 col-md-6 permisosRolColor"><span class="colores_leyend permisosRolBack"></span>Permisos del rol</div>
                            <div class="col-12 col-md-6 permisosEspecialesColor"><span class="colores_leyend permisosEspecialesBack"></span>Permisos Especiales</div>
                        </div>`
   esquemaModulos += `<div class="accordion" id="accordionModulosUser${texto}">`

  data.forEach(el => {
        esquemaModulos += `<div class="card">
                                <div class="card-header p-0" id="${el.nombre}${texto}">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link btn-sm collapsed w-100 text-left font-weight-bold" 
                                                type="button" data-toggle="collapse" data-target="#modul${el.identificador}" 
                                                aria-expanded="true" aria-controls="modul${el.identificador}">
                                            <i class="fa fa-angle-double-right"></i> ${el.nombre}
                                        </button>
                                    </h5>
                                </div>
                            </div>`
        esquemaModulos += `<div id="modul${el.identificador}" class="collapse" aria-labelledby="${el.identificador}${texto}" 
                                data-parent="#accordionModulosUser${texto}">
                                <div class="card-body font-italic p-1">`

        esquemaModulos += `<label class=" form-control-sm b-0">
                                <input type="checkbox" name="permissions[]" class="validateCheckbox" 
                                        value="${el.identificador}" id="check${texto}${el.identificador}">
                                <span>${el.descripcion}</span>
                            </label>`
                                    
        let lista_modulosRol = this.traerPermisosPorRol(el)
            lista_modulosRol.forEach(per => {
                esquemaModulos += `<label class=" form-control-sm b-0">
                                    <input type="checkbox" name="permissions[]" class="validateCheckbox" 
                                            value="${per.identificador}" id="check${texto}${per.identificador}">
                                    <span>${per.descripcion}</span>
                                </label>`
            });
        let lista_modulosEspecial = this.trearPermisosEspeciales(el)
            lista_modulosEspecial.forEach(per => {
                esquemaModulos += `<label class=" form-control-sm b-0">
                                    <input type="checkbox" name="permissions[]" class="validateCheckbox" 
                                            value="${per.identificador}" id="check${texto}${per.identificador}">
                                    <span>${per.descripcion}</span>
                                </label>`
            });

        esquemaModulos += ` </div>
                            </div>`
  
    });

    esquemaModulos += `</div>`

    printResult.html(esquemaModulos)
}
//Store
///administrador/roles/{rol}/permisos
peticiones.seleccionarPermisosByRoles = function seleccionarPermisosByRoles(idRol,dataIdent,modalShow,loadEsquema,loadEsquemaRpta, permisosBloqueados){
    console.log("esta haciendo peticiones de permisos por rol")
    modalShow.modal("show")

  loadEsquema.css({"display":"none"})
  loadEsquemaRpta.html(`<div id="carga_person">
                          <div class="loader">Loading...</div>
                        </div>`)
  loadEsquemaRpta.css({"display":"block"})

  $.ajax({
    url:`/administrador/roles/${parseInt(idRol)}/permisos`,
    method:"get",
    dataType: "json", 
  })
  .done(function(data){
    //console.log("los permisos del rol seleccionado:", data)
    let permisosSegunRol = data.response.data
    
    permisosSegunRol.forEach(el => {
        //console.log(el);
        $(`input#check${dataIdent}`+el.identificador).prop('checked', true)
        $(`input#check${dataIdent}`+el.identificador).parent().addClass("permisosRolColor");
        //$(`input#check${dataIdent}`+el.identificador).prop('disabled', true) //descomentar luego
    }) 

    //console.log("el permiso bloqueado es: ",permisosBloqueados)
    if (permisosBloqueados.response) {
        if (permisosBloqueados.response.length > 0) {
            permisosBloqueados.response.forEach(el => {
                //console.log(el);
                 $(`input#check${dataIdent}`+el).prop('checked', false)
                 $(`input#check${dataIdent}`+el).parent().addClass("permisosRolColor");
            })
        }
    }
    

    loadEsquema.css({"display":"block"})
    loadEsquemaRpta.html(``)
    loadEsquemaRpta.css({"display":"none"})

    INICIAR_PETICION_PERMISOS_CHECK = false
     
  })
  .fail(function(jqXHR, textStatus){
    console.log( "Request failed: " ,textStatus ,jqXHR);

    if(jqXHR.responseJSON){
        if(jqXHR.responseJSON.mensaje){
            let erroresMensaje = jqXHR.responseJSON.mensaje  //captura objeto
            let mensaje = errors.mensajeErrorJson(erroresMensaje)
            loadEsquemaRpta.html(mensaje)
            setTimeout(() => {  loadEsquemaRpta.html(``) }, 7000);
            return false
        } 
    }
    if(jqXHR.status){
        let mensaje = errors.codigos(jqXHR.status)
        loadEsquemaRpta.html(mensaje)
        setTimeout(() => {  loadEsquemaRpta.html(``) }, 7000);
        return false
    }

    loadEsquemaRpta.html(`Hubo un error en el servicio de permisos, cierre el modal e intente abrir nuevamente!`)
    
    setTimeout(() => {  loadEsquemaRpta.html(``) }, 7000);
    /*$("#body-errors-modal").html(`Hubo un error en el servicio de permisos, intente nuevamente por favor!`)
    $('#errorsModal').modal('show')  */
    //console.log( "Request failed: " ,jqXHR.responseJSON.mensaje);
    
  });
}

export default peticiones