const descargaServerFile = {}

descargaServerFile.downloadFile = function downloadFile(archivo, ruta)
{
    $("#preloadModal").html(`<div id="carga_person" class="pre-estados-modems">
                              <div class="loader">Loading...</div>
                            </div>`);
    $("#preloadModalMensaje").html(`<h6><b>Tiempo estimado para finalizar la descarga en Excel: 5 minutos</b></h6>
                            <p class="text-primary"><b>Favor de no cerrar o refrescar la página actual</b></p>`);

    $.ajax({
        //xhrFields: { responseType: 'blob', },
        url: "/administrador/ingreso-averias/descarga-file/download",
        method: 'get',
        data: {
            archivo:archivo,
            ruta:ruta
        },
        cache: false, 
        })
        .done(function(result){
            //console.log(archivo);
            $("#preloadModal").html("");
            $("#preloadModalMensaje").html("");
            var blob = new Blob([result], {
                type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            });
            var link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = archivo;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
           
        })
        .fail(function(jqXHR, textStatus, errorThrown){
            console.log( "Request failed: " ,textStatus ,jqXHR,errorThrown);
            $("#preloadModal").html("");
            $("#preloadModalMensaje").html("");
            return textStatus;
            
        });

}

descargaServerFile.downloadFileTotal = function downloadFileTotal(archivo, ruta)
{
    $("#preloadModalLoading").html(`<div id="carga_person">
                              <div class="loader">Loading...</div>
                            </div>`);

    $.ajax({
        //xhrFields: { responseType: 'blob', },
        url: "/administrador/llamadas-nodo/descarga-file/download",
        method: 'get',
        data: {
            archivo:archivo,
            ruta:ruta
        },
        cache: false, 
        })
        .done(function(result){
            //console.log(archivo);
            $("#preloadModalLoading").html("");
            var blob = new Blob([result], {
                type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            });
            var link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = archivo;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
           
        })
        .fail(function(jqXHR, textStatus, errorThrown){
            console.log( "Request failed: " ,textStatus ,jqXHR,errorThrown);
            $("#preloadModalLoading").html("");
            return textStatus;
            
        });

}

descargaServerFile.removeDownloadFile = function removeDownloadFile(archivo, ruta)
{
    $("#preloadModal").html(`<div id="carga_person" class="pre-estados-modems">
                              <div class="loader">Loading...</div>
                            </div>`);
    $("#preloadModalMensaje").html(`<h6><b>Tiempo estimado para finalizar la descarga en Excel: 5 minutos</b></h6>
                            <p class="text-primary"><b>Favor de no cerrar o refrescar la página actual</b></p>`);

    $.ajax({
        //xhrFields: { responseType: 'blob', },
        url: "/administrador/contencion-llamadas/descarga-file/download",
        method: 'get',
        data: {
            archivo:archivo,
            ruta:ruta
        },
        cache: false, 
        })
        .done(function(result){
            //console.log(archivo);
            
            $("#preloadModal").html("");
            $("#preloadModalMensaje").html("");
            var blob = new Blob([result], {
                type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            });
            var link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = archivo;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
           
        })
        .fail(function(jqXHR, textStatus, errorThrown){
            console.log( "Request failed: " ,textStatus ,jqXHR,errorThrown);
            $("#preloadModal").html("");
            $("#preloadModalMensaje").html("");
            return textStatus;
            
        });

}

export default descargaServerFile