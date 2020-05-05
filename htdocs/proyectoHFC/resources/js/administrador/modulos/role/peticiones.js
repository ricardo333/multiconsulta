import filters from '@/globalResources/lists/filters'
import errors from '@/globalResources/errors'

const peticiones = {}
  
//General
 
peticiones.filtrarModulosPorPermisos = function filtrarModulosPorPermisos(permisos)
{
    
    let ListaModulos = permisos.filter((permiso) => {
        if (permiso.permiso.indexOf("index") >= 0) {
            return permiso
        }
    })

    return ListaModulos;
}
  
peticiones.traerPermisosPorRol = function traerPermisosPorRol(modulos)
{ 
    let permisos_rol = PERMISOS_ROL
    
    
    let ListaPermisos = permisos_rol.filter( (modulo) => {
        if(modulos.identificador == modulo.identificadorModulo){
            return modulo
        }  
    }) 
   // console.log(`los sub permisos del modulo ${modulos.nombre} es: `,ListaPermisos)
    return ListaPermisos
}
peticiones.trearPermisosEspeciales = function trearPermisosEspeciales(modulos){

    //console.log("Los modulos de donde se traerá los permisos especiales son: ",modulos)
    //console.log("Los permisos de usuario inicial es: ")
    
        let permisos_especiales = PERMISOS_USER.response.data 
        //console.log("los permisos especiale sons: ",permisos_especiales)

        let ListaPermisos = permisos_especiales.filter( (modulo) => {
            if(modulos.identificador == modulo.identificadorModulo){
                return modulo
            }  
        })
        return ListaPermisos
    
}
  
peticiones.armandoEsquemaModulosPermisos = function armandoEsquemaModulosPermisos(data,texto,printResult)
{

  let esquemaModulos = `<div class="accordion" id="accordionModulosRole${texto}">`

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
                                data-parent="#accordionModulosRole${texto}">
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
//Edit
peticiones.checkedPermisosRol = function checkedPermisosRol(data,texto)
{ 
    data.forEach(el => {
        $(`input#check${texto}`+el.identificador).prop('checked', true)
       // $(`input#check${texto}`+el.identificador).prop('disabled', true)
    });
 
}
 

export default peticiones