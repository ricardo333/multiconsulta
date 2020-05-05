const descargaCmts = {}

descargaCmts.downloadCmts = function downloadCmts(archivo)
{

    $.ajax({
        //xhrFields: { responseType: 'blob', },
        url: "/administrador/descarga-cmts/download",
        method: 'get',
        data: {
            archivo
        },
        cache: false, 
        })
        .done(function(result){

            console.log(archivo);
            var nombre = archivo+".txt";
            var blob = new Blob([result], {
                type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            });
            var link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = nombre;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
           
        })
        .fail(function(jqXHR, textStatus, errorThrown){
            console.log( "Request failed: " ,textStatus ,jqXHR,errorThrown);
             
            return textStatus;
            
        });

}

export default descargaCmts