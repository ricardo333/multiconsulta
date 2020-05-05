import peticiones from './peticiones.js'
import errors from  "@/globalResources/errors.js"
import limpia from  "@/globalResources/forms/limpia.js"
import gestionarFile from  "@/globalResources/modulos/gestionar-file.js"

$(function(){

    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

    $("body").on("change","#fileLoadFile", function(){

        gestionarFile.file($(this)[0].files[0],'#nameFileValidate')

    })

    $("#subirArchivo").click(function(){

       //Le enviamos 2 parametros: donde de va a mostrar el mensaje y el id del campo del file
       let formData = gestionarFile.getFile("#mensajeSubirArchivo","#fileLoadFile");
       procesarDataProcesoValidacion(formData)

    })

    function procesarDataProcesoValidacion(formData)
    {
        peticiones.loadArchivoServicio(formData,function(res){
            
            gestionarFile.sendMensajeFile(res,"#mensajeSubirArchivo");

        })

    }

})