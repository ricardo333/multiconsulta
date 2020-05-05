$(function(){

    function leadingZero(value) {
        if (value < 10) {
          return "0" + value.toString();
        }
        return value.toString();
    }


    function habilitacionBtn(value) {

        if ($("#filtroCuadroMando").length) {
            if(value=="SI"){
                document.getElementById('btnTotal').disabled=true;
            }else{
                document.getElementById('btnTotal').disabled=false;
            }
        }else{
            if(value=="SI"){
                document.getElementById('btnTotal').disabled=true;
                document.getElementById('btnTotalAverias').disabled=true;
            }else{
                document.getElementById('btnTotal').disabled=false;
                document.getElementById('btnTotalAverias').disabled=false;
            }
        }
        
    }


    //-----Descarga en Modal-----//
    $("body").on("click",".totalmasiva", function(){

        let jefatura = $("#listaJefaturasMasivas").val()
        let estado = $("#listaEstadosMasivas").val()

        let nodo
        let motivo

        if ($("#var_cuadroMando").length) {
            motivo = "cuadroMando"
            nodo = $("#var_nodo").val()
        } else {
            motivo = "modulo"
            nodo = " "
        }
        
        console.log(motivo)
        console.log(nodo)
        console.log(jefatura)
        console.log(estado)

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
            url: "/administrador/masiva-cms/excel/excelCaidasMasivasTotal",
            method: 'get',
            data: {
                motivo,
                nodo,
                jefatura,
                estado

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
                link.download = 'alertas_report'+fecha+'.xlsx';
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


    $("body").on("click",".totalaverias", function(){

        $("#preloadMaping").html(`<div id="carga_person">
                                <div class="loader">Loading...</div>
                                </div>`)

        habilitacionBtn("SI");

        $.ajax({
            xhrFields: { responseType: 'blob', },
            url: "/administrador/masiva-cms/excel/excelCaidasMasivasAveriasTotal",
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



    //------Reporte de Averias por nodo y troba-----//
    $("body").on("click",".verAlertasMasivasDown", function(){

        let nodo = $(this).data("uno")
        let troba = $(this).data("dos")

        let today = new Date();
        let dd = today.getDate();
        let mm = today.getMonth(mm)+1;
        let yyyy = today.getFullYear();
        let hh = today.getHours();
        let mi = today.getMinutes();
        let ss = today.getSeconds();

        let fecha = yyyy+''+leadingZero(mm)+''+leadingZero(dd)+''+leadingZero(hh)+''+leadingZero(mi)+''+leadingZero(ss);

        console.log(fecha);

        $.ajax({
            xhrFields: { responseType: 'blob', },
            url: "/administrador/monitor-averias/excel/excelCaidasMasivasAlertasDown",
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
                link.download = 'alertas_down'+fecha+'.xlsx';
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



    $("body").on("click",".verAverias", function(){

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







})