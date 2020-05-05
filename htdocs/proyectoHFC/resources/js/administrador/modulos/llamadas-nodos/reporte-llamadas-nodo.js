import descargaServerFile from  "@/globalResources/modulos/descargar-server-file.js"

$(function(){

    function leadingZero(value) {
        if (value < 10) {
          return "0" + value.toString();
        }
        return value.toString();
    }

    $("body").on("click",".verLlamadaNodoDMPE", function(){

        $(".preloadMaping").html(`<div id="carga_person" class="pre-estados-modems">
                                  <div class="loader">Loading...</div>
                                </div>`)

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

        $.ajax({
            xhrFields: { responseType: 'blob', },
            url: "/administrador/llamadas-nodo/excel/excelDMPE/",
            method: 'get',
            data: {
                nodo,
                troba
            },
            cache: false, 
            })
            .done(function(result){

                //console.log(result)
                $(".preloadMaping").html("");
                var blob = new Blob([result], {
                    type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                });
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = 'llamadasNodo_consultp_down_'+fecha+'.xlsx';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);

            })

            .fail(function(xhr, jqXHR, textStatus) {
                
                $(".preloadMaping").html("");
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

    $("body").on("click",".verLlamadaNodoAverias", function(){

        $(".preloadMaping").html(`<div id="carga_person" class="pre-estados-modems">
                                  <div class="loader">Loading...</div>
                                </div>`)
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

        $.ajax({
            xhrFields: { responseType: 'blob', },
            url: "/administrador/llamadas-nodo/excel/excelAverias/",
            method: 'get',
            data: {
                nodo,
                troba
            },
            cache: false, 
            })
            .done(function(result){

                //console.log(result)
                $(".preloadMaping").html("");
                var blob = new Blob([result], {
                    type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                });
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = 'llamadasNodo_consultp_down_'+fecha+'.xlsx';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);

            })

            .fail(function(xhr, jqXHR, textStatus) {
                
                $(".preloadMaping").html("");
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

    $("body").on("click",".verLlamadaNodoTotal", function(){

            let jefatura = $("#listajefatura").val()
    
            let today = new Date();
            let dd = today.getDate();
            let mm = today.getMonth(mm)+1;
            let yyyy = today.getFullYear();
            let hh = today.getHours();
            let mi = today.getMinutes();
            let ss = today.getSeconds();

            //console.log(jefatura);
    
            let fecha = yyyy+''+leadingZero(mm)+''+leadingZero(dd)+''+leadingZero(hh)+''+leadingZero(mi)+''+leadingZero(ss);
    
            $("#preloadModalLoading").html(`<div id="carga_person">
                              <div class="loader">Loading...</div>
                            </div>`);
    
            $.ajax({
                xhrFields: { responseType: 'blob', },
                url: "/administrador/llamadas-nodo/excel/excelTotal",
                method: 'get',
                data: {
                    jefatura
                },
                cache: false,
                })
                .done(function(result){
                    $("#preloadModalLoading").html("");
                    //habilitacionBtn("NO");
                    console.log(result);
    
                    var blob = new Blob([result], {
                        type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                    });
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = 'llamadasNodo_'+fecha+'.xlsx';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                })
    
                .fail(function(xhr, jqXHR, textStatus) {
                    $("#preloadModalLoading").html("");
                    //habilitacionBtn("NO");
                    console.log(xhr)
                    console.log(jqXHR)
                    console.log(textStatus)
                    var errorMessage1 = xhr.status + ': ' + "Hubo un error en los datos, intente en un minuto por favor."
                    
                    //alert(errorMessage1);
                    $("#rpta_error").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    ${errorMessage1}</div>`);
                });
    
        })

})