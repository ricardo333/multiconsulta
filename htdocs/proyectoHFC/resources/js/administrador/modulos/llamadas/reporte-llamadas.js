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
        }else{
            document.getElementById('btnReverificar').disabled=false;
            document.getElementById('btnSuspendidos').disabled=false;
            document.getElementById('btnGestion').disabled=false;
            document.getElementById('btnTotal').disabled=false;
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

    //------------MODAL---------//
    $("body").on("click",".total", function(){
        //$("body").on("click","#descargasExcel", function(){

            let jefatura = $("#listajefaturaLlamadas").val()
            let top = $("#listaTopLlamadas").val()
            let nodo = $("#nodoJefaturaLlamadas").val()

    
            let today = new Date();
            let dd = today.getDate();
            let mm = today.getMonth(mm)+1;
            let yyyy = today.getFullYear();
            let hh = today.getHours();
            let mi = today.getMinutes();
            let ss = today.getSeconds();
    
            let fecha = yyyy+''+leadingZero(mm)+''+leadingZero(dd)+''+leadingZero(hh)+''+leadingZero(mi)+''+leadingZero(ss);
            
            $("#preloadMaping").html(`<div id="carga_person">
                                      <div class="loader">Loading...</div>
                                    </div>`)
    
            //habilitacionBtn("SI");
            
    
            $.ajax({
                xhrFields: { responseType: 'blob', },
                url: "/administrador/monitor-averias/excel/excelLlamadasTotal",
                method: 'get',
                data: {
                    jefatura,
                    top,
                    nodo
                },
                cache: false,
                })
                .done(function(result){
                    $("#preloadMaping").html("");
                    //habilitacionBtn("NO");
                    console.log(result);
    
                    var blob = new Blob([result], {
                        type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                    });
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = 'llamadas_report'+fecha+'.xlsx';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                })
    
                .fail(function(xhr, jqXHR, textStatus) {
                    $("#preloadMaping").html("");
                    //habilitacionBtn("NO");
                    console.log(xhr)
                    console.log(jqXHR)
                    console.log(textStatus)
                    var errorMessage1 = xhr.status + ': ' + "Hubo un error en los datos, intente en un minuto por favor."
                    
                    alert(errorMessage1);
                    $("#rpta_error").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    ${errorMessage1}</div>`);
                });
    
        })

})