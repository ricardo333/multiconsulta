$(function(){

    function leadingZero(value) {
        if (value < 10) {
          return "0" + value.toString();
        }
        return value.toString();
    }
    
    function habilitacionBtn(value) {

        if(value=="SI"){
            $("#resultEstadosModems a").each(function(){
                 $(this).addClass('enlace_desactivado');
            });
        }else{
            $("#resultEstadosModems a").each(function(){
                 $(this).removeClass('enlace_desactivado');
            });
        }
        
    }

    $("body").on("click",".exportExcelEstadosModems", function(){

        $("#preloadMaping").html(`<div id="carga_person" class="pre-estados-modems">
                                  <div class="loader">Loading...</div>
                                </div>`)

        let state = $(this).data("uno")
        //console.log('valor del estado: '+state);
        habilitacionBtn("SI");

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
            url: "/administrador/estados-modems/excel/excelEstadosModems",
            method: 'get',
            data: {
                state
            },
            cache: false, 
            })
            .done(function(result){
                //console.log(result)
                $("#preloadMaping").html("");
                habilitacionBtn("NO");
                var blob = new Blob([result], {
                    type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                });
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = 'estadoModem_'+state+'_'+fecha+'.xlsx';
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

                $("#errorExcel").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                ${errorMessage1}</div>`); 
            });
        
    })

})