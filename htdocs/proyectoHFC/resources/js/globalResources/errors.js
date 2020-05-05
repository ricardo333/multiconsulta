const errors = {}

errors.codigos = function codigos(codigo){

  let texto = ``

  if (codigo == 401) {
    location.reload();
    texto = `Sesión terminada.`
  }
    
    switch (codigo) {
        case 204: 
          texto = `La petición se ha completado con éxito pero su respuesta no tiene ningún contenido.` 
          break;
        case 401: 
          texto = `Su sesión expiro.` 
          break;
        case 403: 
          texto = `Petición no autoriazada.`
          break;
        case 404:
          texto = `Petición no encontrada.`
          break;
        case 405:
          texto = `Error en el servicio. Intente nuevamente.`
          break;
        case 409:
          texto = `Conflicto de petición en el servidor. Intente nuevamente. Si persiste el error, actualizar la Web.`
          break;
        case 422:
          texto = `No se puede procesar la petición. Verifique los datos enviados.`
          break;
        case 500:
          texto = `Falla inesperada. Intente nuevamente.`
          break;  
        default:
            texto = `Falla inesperada con la petición. Intente nuevamente.`
          break;
      }

      return texto

}

errors.mensajeErrorJson = function mensajeErrorJson(erroresJson){
 
          //console.log("el tipo de mensaje es:",typeof(erroresJson),erroresJson)
          if (typeof(erroresJson) == "string") { 
              return erroresJson
          }
          //recorreo objeto como array
          let msj = ``
          Object.keys(erroresJson).forEach(key =>{
            //console.log("El key es: -",key,"-")
            if (!isNaN(key)) {
              msj +=`<li>${erroresJson[key]}</li>`
            }else{
              msj +=`${key} : ${erroresJson[key]} <br/>`
            }
              
          }) 
          return msj; 
}

export default errors