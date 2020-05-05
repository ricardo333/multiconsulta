import descargaServerFile from  "@/globalResources/modulos/descargar-server-file.js"

$(function(){

    function leadingZero(value) {
        if (value < 10) {
          return "0" + value.toString();
        }
        return value.toString();
    }
    
    //Ingreso de Averias por Jefatura y Motivos - Resumen de Ingresos - Descargar excel
    $("body").on("click",".exportAveriasResumenIngresos", function(){
        
        $("#preloadCharger").html(`<div class="preloadCharger"><div id="carga_person">
                                <div class="loader">Loading...</div>
                              </div></div>`)


        let motivo = $(this).data("uno")
        let nombre = motivo.replace(" ", "_").toLowerCase()
        //console.log('valor del estado: '+state);

        let today = new Date();
        let dd = today.getDate();
        let mm = today.getMonth(mm)+1;
        let yyyy = today.getFullYear();
        let hh = today.getHours();
        let mi = today.getMinutes();
        let ss = today.getSeconds();

        let fecha = yyyy+''+leadingZero(mm)+''+leadingZero(dd)+''+leadingZero(hh)+''+leadingZero(mi)+''+leadingZero(ss);
        
        $.ajax({
            xhrFields: { responseType: 'blob', },
            url: "/administrador/ingreso-averias/excel/exportAveriasResumenIngresos",
            method: 'get',
            data: {
                motivo: motivo
            },
            cache: false, 
            })
            .done(function(result){
                //console.log(result)
                $("#preloadCharger").html("");
                var blob = new Blob([result], {
                    type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                });
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = 'reporteIngresoAverias_'+nombre+'_'+fecha+'.xlsx';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            })

            .fail(function(xhr, jqXHR, textStatus) {
                $("#preloadCharger").html("");
                console.log(xhr)
                console.log(jqXHR)
                console.log(textStatus)
                var errorMessage1 = xhr.status + ': ' + "Hubo un error en los datos, intente en un minuto por favor."

                $("#errorExcel").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                ${errorMessage1}</div>`); 
            });
        
    })

    //Ingreso de Averias por Jefatura - Se compara con 1 día antes ...  - Descarga de averias del dia
    $("body").on("click",".downloadAveriasDia", function(){

        let jefatura = $("#jefaturaIngresoAverias").val()
        let troba = $("#trobaIngresoAverias").val()
       
        $("#preloadModal").html(`<div id="carga_person" class="pre-estados-modems">
                              <div class="loader">Loading...</div>
                            </div>`);
        $("#preloadModalMensaje").html(`<h6><b>Tiempo estimado para finalizar la descarga en Excel: 5 minutos</b></h6>
                            <p class="text-primary"><b>Favor de no cerrar o refrescar la página actual</b></p>`);

        let reporte = $(this).data("uno")
        let motivo = $(this).data("dos")
        let nombre = reporte.replace(" ", "_").toLowerCase()

        let today = new Date();
        let dd = today.getDate();
        let mm = today.getMonth(mm)+1;
        let yyyy = today.getFullYear();
        let hh = today.getHours();
        let mi = today.getMinutes();
        let ss = today.getSeconds();

        let fecha = yyyy+''+leadingZero(mm)+''+leadingZero(dd)+''+leadingZero(hh)+''+leadingZero(mi)+''+leadingZero(ss);
        
        $.ajax({
            xhrFields: { responseType: 'blob', },
            url: "/administrador/ingreso-averias/excel/exportAveriasResumenIngresos",
            method: 'get',
            data: {
                motivo: motivo,
                jefatura: jefatura,
                troba: troba
            },
            cache: false, 
            })
            .done(function(result){
                //console.log(result)
                
                $("#preloadModal").html("");
                $("#preloadModalMensaje").html("");
                var blob = new Blob([result], {
                    type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                });
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = 'reporteIngresoAveria_'+nombre+'_'+fecha+'.xlsx';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                
            })

            .fail(function(xhr, jqXHR, textStatus) {
                
                $("#preloadModal").html("");
                $("#preloadModalMensaje").html("");
                console.log(xhr)
                console.log(jqXHR)
                console.log(textStatus)
                var errorMessage1 = xhr.status + ': ' + "Hubo un error en los datos, intente en un minuto por favor."

                $("#errorExcel").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                ${errorMessage1}</div>`); 
                            
            });
        
    })

    //Ingreso de Averias por Jefatura - Se compara con 1 día antes ... - Descarga de Detalle Arbol Última Semana - Ramas Completas
    $("body").on("click",".exportAveriaReporte", function(){
       
        $("#preloadModal").html(`<div id="carga_person" class="pre-estados-modems">
                              <div class="loader">Loading...</div>
                            </div>`);
        $("#preloadModalMensaje").html(`<h6><b>Tiempo estimado para finalizar la descarga en Excel: 5 minutos</b></h6>
                            <p class="text-primary"><b>Favor de no cerrar o refrescar la página actual</b></p>`);

        let reporte = $(this).data("uno")
        let nombre = reporte.replace(" ", "_").toLowerCase()
        //console.log('valor del estado: '+reporte);

        let today = new Date();
        let dd = today.getDate();
        let mm = today.getMonth(mm)+1;
        let yyyy = today.getFullYear();
        let hh = today.getHours();
        let mi = today.getMinutes();
        let ss = today.getSeconds();

        let fecha = yyyy+''+leadingZero(mm)+''+leadingZero(dd)+''+leadingZero(hh)+''+leadingZero(mi)+''+leadingZero(ss);
        
        $.ajax({
            xhrFields: { responseType: 'blob', },
            url: "/administrador/ingreso-averias/excel/excelAveriaReporte",
            method: 'get',
            data: {
                reporte
            },
            cache: false, 
            })
            .done(function(result){
                //console.log(result)
                $("#preloadModal").html("");
                $("#preloadModalMensaje").html("");
                var blob = new Blob([result], {
                    type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                });
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = 'reporteIngresoAveria_'+nombre+'_'+fecha+'.xlsx'; // .xlsx 
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            })

            .fail(function(xhr, jqXHR, textStatus) {
                $("#preloadModal").html("");
                $("#preloadModalMensaje").html("");
                console.log(xhr)
                console.log(jqXHR)
                console.log(textStatus)
                var errorMessage1 = xhr.status + ': ' + "Hubo un error en los datos, intente en un minuto por favor."

                $("#errorExcel").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                ${errorMessage1}</div>`); 
            });
        
    })

    //Ingreso de Averias por Jefatura - Descarga de averias del mes
    $("body").on("click",".downloadAveriasMes", function(){

        let jefatura = $("#jefaturaIngresoAverias").val()
        let troba = $("#trobaIngresoAverias").val()

        $("#preloadModal").html(`<div id="carga_person" class="pre-estados-modems">
                              <div class="loader">Loading...</div>
                            </div>`);
        $("#preloadModalMensaje").html(`<h6><b>Tiempo estimado para finalizar la descarga en Excel: 5 minutos</b></h6>
                            <p class="text-primary"><b>Favor de no cerrar o refrescar la página actual</b></p>`);

        let reporte = $(this).data("uno")
        let mes = $(this).data("dos")
        let nombre = reporte.replace(" ", "_").toLowerCase()
        //console.log('valor del estado: '+mes);

        let today = new Date();
        let dd = today.getDate();
        let mm = today.getMonth(mm)+1;
        let yyyy = today.getFullYear();
        let hh = today.getHours();
        let mi = today.getMinutes();
        let ss = today.getSeconds();

        let fecha = yyyy+''+leadingZero(mm)+''+leadingZero(dd)+''+leadingZero(hh)+''+leadingZero(mi)+''+leadingZero(ss);
        
        $.ajax({
            xhrFields: { responseType: 'blob', },
            url: "/administrador/ingreso-averias/excel/excelExportAveriasMes",
            method: 'get',
            data: {
                mes,
                jefatura,
                troba
            },
            cache: false, 
            })
            .done(function(result){
                //console.log(result)
                
                $("#preloadModal").html("");
                $("#preloadModalMensaje").html("");
                var blob = new Blob([result], {
                    type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                });
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = 'reporteIngresoAveria_'+nombre+'_'+fecha+'.xlsx';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                
            })

            .fail(function(xhr, jqXHR, textStatus) {
                
                $("#preloadModal").html("");
                $("#preloadModalMensaje").html("");
                console.log(xhr)
                console.log(jqXHR)
                console.log(textStatus)
                var errorMessage1 = xhr.status + ': ' + "Hubo un error en los datos, intente en un minuto por favor."

                $("#errorExcel").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                ${errorMessage1}</div>`); 
                           
            });
        
    })

    //Ingreso de Averias por Jefatura - Descarga de detalle Uso de Arbol - Resumen de uso
    $("body").on("click",".downloadIngresoAverias", function(){

        let archivo = $(this).data("uno")
        let ruta = '/temp/'
        //let extension = '.csv'
        //console.log(archivo);

        descargaServerFile.downloadFile(archivo,ruta)
            
    })

})