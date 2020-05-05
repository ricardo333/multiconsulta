import descargaCmts from  "@/globalResources/modulos/descarga-cmts.js"

$(function(){

    //DESCARGA
    $("body").on("click",".downloadPuertoSatDown", function(){

        let archivo = $(this).data("uno")
        //console.log(archivo);

        descargaCmts.downloadCmts(archivo)
            
    })
      

})