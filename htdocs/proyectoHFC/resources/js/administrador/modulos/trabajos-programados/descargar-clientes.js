
$(function(){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    $("body").on("click",".descargarClienteTP", function(){
       
        let item = $(this).data("uno")
        let nodo = $(this).data("dos")
        let troba = $(this).data("tres")

        let _this = $(this)

         
        _this.prop('disabled',true)
        _this.html(`</i> <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        <span class="sr-only">Descargando..</span>`)
        

        $.ajax({
            xhrFields: { responseType: 'blob', },
            url: "/administrador/trabajos-programados/descargar/excel/clientes",
            method: 'get',
            data: {
                item,
                nodo,
                troba
            },
            cache: false, 
            })
            .done(function(result){

                _this.prop('disabled',false)
                _this.html(`<i class="icofont-file-excel icofont-2x"></i>`)

                
               var blob = new Blob([result], {
                    type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                });
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = 'clientes_tp.xlsx';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            })

            .fail(function(xhr, jqXHR, textStatus) { 
                _this.prop('disabled',false)
                _this.html(`<i class="icofont-file-excel icofont-2x"></i>`)
                
                var errorMessage1 = xhr.status + ': ' + "Hubo un error en los datos, intente en un minuto por favor."

                $("#body-errors-modal").html(`<div class="w-100 text-center text-danger">${errorMessage1}</div>`)
                $('#errorsModal').modal('show') 
                 
                return false
                
            });

    })
})