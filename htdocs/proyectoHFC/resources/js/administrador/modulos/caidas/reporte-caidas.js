$(function(){

    function leadingZero(value) {
        if (value < 10) {
          return "0" + value.toString();
        }
        return value.toString();
    }




    //---------------------------------------------------------//
    $("body").on("click",".verAlertasDown", function(){

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
            url: "/administrador/monitor-averias/excel/excelCaidasAlertasDown",
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




    $("body").on("click",".verAveriasDown", function(){

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


    $("body").on("click",".verConsultasCtv", function(){

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


    $("body").on("click",".descargaEnergia", function(){

        let nodo = $(this).data("uno")
        let troba = $(this).data("dos")

        $.ajax({
            xhrFields: { responseType: 'blob', },
            url: "/administrador/monitor-averias/excel/excelCaidasEnergia",
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
                link.download = 'clientes_ttpp.xlsx';
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


    //------------MODAL---------//
    $("body").on("click","#btnTotal", function(){
    //$("body").on("click","#descargasExcel", function(){

        let tipoCaida

        let nodo
        let motivo

        let filt1
        let filt2


        if ($("#filtroCuadroMando").length) {
            motivo = "cuadroMando"
            nodo = $("#filtroCuadroMando").val()
            if (nodo.lenght==2) {
                tipoCaida = "caidas_masivas"
                filt1 = ""
                filt2 = ""
            } else {
                tipoCaida = nodo
                filt1 = ""
                filt2 = ""
            }
        } else {
            motivo = "modulo"
            nodo = " "
            tipoCaida = $("#display_filter_special").val()

            if (tipoCaida=="caidas_masivas") {
                filt1 = $("#listajefaturaCaidasMASIVA").val()
                filt2 = $("#listaEstadoCaidasMASIVA").val()
            } else if (tipoCaida=="caidas_noc") {
                filt1 = $("#listajefaturaCaidasNOC").val()
                filt2 = $("#listaEstadoCaidasNOC").val()
            } else if (tipoCaida=="caidas_torre") {
                filt1 = $("#listajefaturaCaidasHFC").val() 
                filt2 = $("#listaEstadoCaidasHFC").val()
            } else if (tipoCaida=="caidas_amplificador") {
                filt1 = $("#listajefaturaCaidasAMPLIFICADOR").val() 
                filt2 = $("#listaTrobasCaidasAMPLIFICADOR").val()
            }

        }


        console.log(tipoCaida)
        console.log(motivo)
        console.log(nodo)
        console.log(filt1)
        console.log(filt2)
        
        
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

        //habilitacionBtn("SI");
        let _this = $(this)
        _this.prop("disabled", true);   

        $.ajax({
            xhrFields: { responseType: 'blob', },
            url: "/administrador/monitor-averias/excel/excelCaidasTotal",
            method: 'get',
            data: {
                tipoCaida,
                motivo,
                nodo,
                filt1,
                filt2
                
            },
            cache: false,
            })
            .done(function(result){
                $("#preloadMaping").html("");
                //habilitacionBtn("NO");
                _this.prop("disabled", false);   
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
                _this.prop("disabled", false); 
                $("#preloadMaping").html("");
                //habilitacionBtn("NO");
                console.log(xhr)
                console.log(jqXHR)
                console.log(textStatus)
                var errorMessage1 = xhr.status + ': ' + "Hubo un error en los datos, intente en un minuto por favor."
                
                //alert(errorMessage1);
                $("#resultOpcionesCuarentenas").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                ${errorMessage1}</div>`);
            });

    })





})