$(function(){

    function leadingZero(value) {
        if (value < 10) {
          return "0" + value.toString();
        }
        return value.toString();
    }

    function habilitacionBtn(value) {

        if(value=="SI"){
            document.getElementById('btnReverificar').disabled=true;
            document.getElementById('btnSuspendidos').disabled=true;
            document.getElementById('btnGestion').disabled=true;
            document.getElementById('btnTotal').disabled=true;
            document.getElementById('btnEstadoM').disabled=true;
        }else{
            document.getElementById('btnReverificar').disabled=false;
            document.getElementById('btnSuspendidos').disabled=false;
            document.getElementById('btnGestion').disabled=false;
            document.getElementById('btnTotal').disabled=false;
            document.getElementById('btnEstadoM').disabled=false;
        }
        
    }

    function habilitacionBtnGpon(value) {

        if(value=="SI"){
            document.getElementById('btnGestionGpon').disabled=true;
            document.getElementById('btnTotalGpon').disabled=true;
        }else{
            document.getElementById('btnGestionGpon').disabled=false;
            document.getElementById('btnTotalGpon').disabled=false;
        }
        
    }

    /*
    $('#descargasHfcModal').on('show.bs.modal', function (e) {
        
        let seleccion = document.getElementById('display_filter_special').value;

        console.log(seleccion);

        if (seleccion=="monitor_averias_hfc") {
            document.getElementById('opcionesHFC').style.display = 'inline';
            document.getElementById('opcionesGPON').style.display = 'none';
        }else{
            document.getElementById('opcionesHFC').style.display = 'none';
            document.getElementById('opcionesGPON').style.display = 'inline';
        }

    })
    */


    ////----------------------Eventos para HFC-----------------------------////
    $("body").on("click",".descargarAveriasHFC", function(){

        let nodo = $(this).data("uno")
        let troba = $(this).data("dos")

        $.ajax({
            xhrFields: { responseType: 'blob', },
            url: "/export_excel/excel/",
            method: 'get',
            data: {
                nodo,
                troba
            },
            cache: false, 
            })
            .done(function(result){
                
                var blob = new Blob([result], {
                    type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                });
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = 'averias.xlsx';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            })

            .fail(function(xhr, jqXHR, textStatus) {
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


    $("body").on("click",".descargaConsultasDmpeHFC", function(){

        let nodo = $(this).data("uno")
        let troba = $(this).data("dos")

        $.ajax({
            xhrFields: { responseType: 'blob', },
            url: "/export_excel/excelDMPE/",
            method: 'get',
            data: {
                nodo,
                troba
            },
            cache: false, 
            })
            .done(function(result){
                
                var blob = new Blob([result], {
                    type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                });
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = 'consultp_down.xlsx';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            })

            .fail(function(xhr, jqXHR, textStatus) {
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


    $("body").on("click",".reverificar", function(){

        $("#preloadMaping").html(`<div id="carga_person">
                                  <div class="loader">Loading...</div>
                                </div>`)

        habilitacionBtn("SI");

        $.ajax({
            xhrFields: { responseType: 'blob', },
            url: "/export_excel/excelReverificar",
            method: 'get',
            data: {},
            cache: false,
            })
            .done(function(result){
                $("#preloadMaping").html("");

                habilitacionBtn("NO");

                var blob = new Blob([result], {
                    type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                });
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = 'ParametrosOK.xlsx';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            })

            .fail(function(xhr, jqXHR, textStatus) {
                $("#preloadMaping").html("");
                habilitacionBtn("NO");
                console.log(xhr)
                console.log(jqXHR)
                console.log(textStatus)
                var errorMessage1 = xhr.status + ': ' + "Hubo un error en los datos, intente en un minuto por favor."

                $("#rpta_error").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                ${errorMessage1}</div>`); 
            });

    })


    $("body").on("click",".suspendidos", function(){

        $("#preloadMaping").html(`<div id="carga_person">
                                  <div class="loader">Loading...</div>
                                </div>`)

        habilitacionBtn("SI");

        $.ajax({
            xhrFields: { responseType: 'blob', },
            url: "/export_excel/excelSuspendidos/",
            method: 'get',
            data: {},
            cache: false,
            })
            .done(function(result){
                $("#preloadMaping").html("");
                habilitacionBtn("NO");

                var blob = new Blob([result], {
                    type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                });
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = 'Serv_Suspendido.xlsx';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            })

            .fail(function(xhr, jqXHR, textStatus) {
                $("#preloadMaping").html("");
                habilitacionBtn("NO");
                console.log(xhr)
                console.log(jqXHR)
                console.log(textStatus)
                var errorMessage1 = xhr.status + ': ' + "Hubo un error en los datos, intente en un minuto por favor."

                $("#rpta_error").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                ${errorMessage1}</div>`); 
            });

    })



    $("body").on("click",".gestion", function(){

        let today = new Date();
        let dd = today.getDate();
        let mm = today.getMonth(mm)+1;
        let yyyy = today.getFullYear();
        let hh = today.getHours();
        let mi = today.getMinutes();
        let ss = today.getSeconds();

        let fecha = yyyy+''+leadingZero(mm)+''+leadingZero(dd)+''+leadingZero(hh)+''+leadingZero(mi)+''+leadingZero(ss);

        console.log(fecha);

        $("#preloadMaping").html(`<div id="carga_person">
                                  <div class="loader">Loading...</div>
                                </div>`)

        habilitacionBtn("SI");

        $.ajax({
            xhrFields: { responseType: 'blob', },
            url: "/export_excel/excelGestion/",
            method: 'get',
            data: {},
            cache: false,
            })
            .done(function(result){
                $("#preloadMaping").html("");
                habilitacionBtn("NO");
                console.log(result);

                var blob = new Blob([result], {
                    type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                });
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = 'gestion_down_'+fecha+'.xlsx';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            })

            .fail(function(xhr, jqXHR, textStatus) {
                $("#preloadMaping").html("");
                habilitacionBtn("NO");
                console.log(xhr)
                console.log(jqXHR)
                console.log(textStatus)
                var errorMessage1 = xhr.status + ': ' + "Hubo un error en los datos, intente en un minuto por favor."

                $("#rpta_error").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                ${errorMessage1}</div>`); 
            });

    })



    $("body").on("click",".total", function(){

        $("#preloadMaping").html(`<div id="carga_person">
                                  <div class="loader">Loading...</div>
                                </div>`)

        habilitacionBtn("SI");

        //let jefatura = $("#listaJefaturasHfc").val()
        //let estado = $("#listaEstadosHfc").val()

        $.ajax({
            xhrFields: { responseType: 'blob', },
            url: "/export_excel/excelTotal",
            method: 'get',
            data: {
                //jefatura,
                //estado
            },
            cache: false,
            })
            .done(function(result){
                $("#preloadMaping").html("");
                habilitacionBtn("NO");
                console.log(result);

                var blob = new Blob([result], {
                    type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                });
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                //link.download = 'averias_down.csv';
                link.download = 'averias_down.xlsx';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            })

            .fail(function(xhr, jqXHR, textStatus) {
                $("#preloadMaping").html("");
                habilitacionBtn("NO");
                console.log(xhr)
                console.log(jqXHR)
                console.log(textStatus)
                var errorMessage1 = xhr.status + ': ' + "Hubo un error en los datos, intente en un minuto por favor."

                $("#rpta_error").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                ${errorMessage1}</div>`); 
            });

    })


    $("body").on("click",".estadom", function(){

        let today = new Date();
        let dd = today.getDate();
        let mm = today.getMonth(mm)+1;
        let yyyy = today.getFullYear();
        let hh = today.getHours();
        let mi = today.getMinutes();
        let ss = today.getSeconds();

        let fecha = yyyy+''+leadingZero(mm)+''+leadingZero(dd)+''+leadingZero(hh)+''+leadingZero(mi)+''+leadingZero(ss);

        console.log(fecha);

        $("#preloadMaping").html(`<div id="carga_person">
                                  <div class="loader">Loading...</div>
                                </div>`)

        habilitacionBtn("SI");

        $.ajax({
            xhrFields: { responseType: 'blob', },
            url: "/export_excel/excelEstadoM/",
            method: 'get',
            data: {},
            cache: false,
            })
            .done(function(result){
                $("#preloadMaping").html("");
                habilitacionBtn("NO");
                console.log(result);

                var blob = new Blob([result], {
                    type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                });
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = 'estado_m'+fecha+'.xlsx';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            })

            .fail(function(xhr, jqXHR, textStatus) {
                $("#preloadMaping").html("");
                habilitacionBtn("NO");
                console.log(xhr)
                console.log(jqXHR)
                console.log(textStatus)
                var errorMessage1 = xhr.status + ': ' + "Hubo un error en los datos, intente en un minuto por favor."

                $("#rpta_error").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                ${errorMessage1}</div>`); 
            });

    })


    ////----------------------Eventos para GPON-----------------------------////
    
    $("body").on("click",".descargarAveriasGPON", function(){

        let nodo = $(this).data("uno")
        let troba = $(this).data("dos")

        console.log(nodo);
        console.log(troba);

        $.ajax({
            xhrFields: { responseType: 'blob', },
            url: "/export_excel/excel/",
            method: 'get',
            data: {
                nodo,
                troba
            },
            cache: false, 
            })
            .done(function(result){
                
                var blob = new Blob([result], {
                    type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                });
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = 'averias.xlsx';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            })

            .fail(function(xhr, jqXHR, textStatus) {
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


    $("body").on("click",".descargaConsultasDmpeGpon", function(){

        let nodo = $(this).data("uno")
        let troba = $(this).data("dos")

        $.ajax({
            xhrFields: { responseType: 'blob', },
            url: "/export_excel/excelDMPE/",
            method: 'get',
            data: {
                nodo,
                troba
            },
            cache: false, 
            })
            .done(function(result){
                
                var blob = new Blob([result], {
                    type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                });
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = 'consultp_down.xlsx';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            })

            .fail(function(xhr, jqXHR, textStatus) {
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



    $("body").on("click",".gestionGpon", function(){

        let today = new Date();
        let dd = today.getDate();
        let mm = today.getMonth(mm)+1;
        let yyyy = today.getFullYear();
        let hh = today.getHours();
        let mi = today.getMinutes();
        let ss = today.getSeconds();

        let fecha = yyyy+''+leadingZero(mm)+''+leadingZero(dd)+''+leadingZero(hh)+''+leadingZero(mi)+''+leadingZero(ss);

        console.log(fecha);

        $("#preloadMaping2").html(`<div id="carga_person">
                                  <div class="loader">Loading...</div>
                                </div>`)

        habilitacionBtnGpon("SI");

        $.ajax({
            xhrFields: { responseType: 'blob', },
            url: "/export_excel/excelGestion/",
            method: 'get',
            data: {},
            cache: false,
            })
            .done(function(result){
                $("#preloadMaping2").html("");
                habilitacionBtnGpon("NO");
                console.log(result);

                var blob = new Blob([result], {
                    type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                });
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = 'gestion_down_'+fecha+'.xlsx';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            })

            .fail(function(xhr, jqXHR, textStatus) {
                $("#preloadMaping2").html("");
                habilitacionBtnGpon("NO");
                console.log(xhr)
                console.log(jqXHR)
                console.log(textStatus)
                var errorMessage1 = xhr.status + ': ' + "Hubo un error en los datos, intente en un minuto por favor."

                $("#rpta_error").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                ${errorMessage1}</div>`); 
            });

    })


    $("body").on("click",".totalGpon", function(){

        $("#preloadMaping2").html(`<div id="carga_person">
                                  <div class="loader">Loading...</div>
                                </div>`)

        habilitacionBtnGpon("SI");

        $.ajax({
            xhrFields: { responseType: 'blob', },
            url: "/export_excel/excelTotalGpon/",
            method: 'get',
            data: {},
            cache: false,
            })
            .done(function(result){
                $("#preloadMaping2").html("");
                habilitacionBtnGpon("NO");
                console.log(result);

                var blob = new Blob([result], {
                    type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                });
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                //link.download = 'averias_down.csv';
                link.download = 'averias_down.xlsx';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            })

            .fail(function(xhr, jqXHR, textStatus) {
                $("#preloadMaping2").html("");
                habilitacionBtnGpon("NO");
                console.log(xhr)
                console.log(jqXHR)
                console.log(textStatus)
                var errorMessage1 = xhr.status + ': ' + "Hubo un error en los datos, intente en un minuto por favor."

                $("#rpta_error").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                ${errorMessage1}</div>`); 
            });

    })

    

})