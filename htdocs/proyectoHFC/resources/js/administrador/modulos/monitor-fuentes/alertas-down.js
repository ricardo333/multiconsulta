$(function(){

    function leadingZero(value) {
        if (value < 10) {
          return "0" + value.toString();
        }
        return value.toString();
    }

 
    //---------------------------------------------------------//
    $("body").on("click",".alertasDownDescargas", function(){

        let nodo = $(this).data("uno")
        let troba = $(this).data("dos")

        let _this = $(this)

        _this.prop('disabled',true)
        _this.html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        <span class="sr-only">Descargando..</span>`)

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

                _this.prop('disabled',false)
                _this.html(`<img src='/images/icons/ico_down.png' alt='Seguir'>`)

            })

            .fail(function(xhr, jqXHR, textStatus) {
                console.log(xhr)
                console.log(jqXHR)
                console.log(textStatus)
                var errorMessage1 = xhr.status + ': ' + "Hubo un error en los datos, intente en un minuto por favor."

                _this.prop('disabled',false)
                _this.html(`<img src='/images/icons/ico_down.png' alt='Seguir'>`)

                $("#body-errors-modal").html(`<div class="text-danger">${errorMessage1}</div>`)
                $('#errorsModal').modal('show')
                return false
 
            });

    })

})