import descargaServerFile from  "@/globalResources/modulos/descargar-server-file.js"

$(function(){

    $("body").on("click",".downloadSeguimientoLlamadas", function(){

        let archivo = $(this).data("uno")
        let ruta = '/temp/'

        descargaServerFile.removeDownloadFile(archivo,ruta)
        
    })

})