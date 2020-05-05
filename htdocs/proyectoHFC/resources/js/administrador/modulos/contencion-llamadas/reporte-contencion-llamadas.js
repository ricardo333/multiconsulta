import descargaServerFile from  "@/globalResources/modulos/descargar-server-file.js"

$(function(){

    //Ingreso de Averias por Jefatura - Descarga de detalle Uso de Arbol - Resumen de uso
    $("body").on("click",".downloadContencionLlamadas", function(){

        let archivo = $(this).data("uno")
        let ruta = '/temp/'
        //let extension = '.csv'
        //console.log(archivo);

        descargaServerFile.removeDownloadFile(archivo,ruta)
            
    })

})