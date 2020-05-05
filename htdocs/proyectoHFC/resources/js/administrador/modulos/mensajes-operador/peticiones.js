import errors from  "@/globalResources/errors.js"

const peticiones = {}

peticiones.loadArchivoServicio = function loadArchivoServicio(formData,callBack){
  
    $.ajax({
        url:`/administrador/mensajes-operador/file`,
        method:"POST",
        async: true,
        data:formData,
        cache: false, 
        contentType: false,
        processData: false
      })
      .done(function(data){ 
          
        //console.log("callbak antes del envio:",data);
        return callBack(data);
         
      })
      .fail(function(jqXHR, textStatus, errorThrown){

          //console.log( "Request failed: " ,textStatus ,jqXHR,errorThrown);
           
          return callBack({
            "error":"failed",
            "jqXHR":jqXHR,
            "textStatus":textStatus,
            "errorThrown":errorThrown,
          });
          
      }); 
      
}

export default peticiones
