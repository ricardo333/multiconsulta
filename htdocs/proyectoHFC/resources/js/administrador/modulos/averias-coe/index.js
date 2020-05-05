import peticiones from './peticiones.js'
import interfaces from  "@/globalResources/modulos/interfaces.js"

$(function(){

    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    peticiones.cargandoPeticionPrincipal()

    $("#filtroBasicoCOE").click(function(){
        peticiones.cargandoPeticionPrincipal()
    })

    //Maximizar

    $(".maxi_tab").click(function(){
      
      $("#tabsAveriasCoeContent").toggleClass("fullscreen");

      if ($("#tabsAveriasCoeContent").hasClass("fullscreen")) {
        // console.log("tiene la clase full ")
          $(".maxi_tab").html('<i class="icofont-close-line-squared-alt"></i>') 
      }else{
        //console.log("no tiene la clase full ")
        $(".maxi_tab").html('<i class="icofont-maximize"></i>')  
      } 
  
    })

    //Return
    $("body").on("click",".return_averias_coe", function(){
         
        peticiones.redirectTabs($("#averiasCoeTab"))
   
    })

    //Ruido Historico
    $("body").on("click",".verhistoricoRuidoInterfaz", function(){
       
      let interfaz = $(this).data("uno")

      peticiones.redirectTabs($("#historicoRuidoInterfazTab"))

      interfaces.historicoRuido(interfaz,'/administrador/averias-coe/historico/ruidos-interfaz')
  
    })
 
    //Descargas
    function leadingZero(value) {
        if (value < 10) {
          return "0" + value.toString();
        }
        return value.toString();
    }

    $("body").on("click",".llamadasDMPEUltimosDias", function(){

        let _this = $(this)
        let textoB = _this.text()
        _this.prop('disabled',true)
      // console.log("el texto es: ",_this.text())
        _this.html(` <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        <span class="sr-only">Descargando..</span>`)

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
            url: "/administrador/averias-coes/llamadas-nodo/excel/excelDMPE/",
            method: 'get',
            data: {
                nodo,
                troba
            },
            cache: false, 
            })
            .done(function(result){

                //console.log(result)
                _this.prop('disabled',false)
                _this.html(`${textoB}`)
                
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
                _this.prop('disabled',false)
                _this.html(`${textoB}`)
                
                console.log(xhr)
                console.log(jqXHR)
                console.log(textStatus)
                var errorMessage1 = xhr.status + ': ' + "Hubo un error en los datos, intente en un minuto por favor."

                $("#body-errors-modal").html(`<div class="w-100 text-center text-danger">${errorMessage1}</div>`)
                $('#errorsModal').modal('show')
 
            });

    })

    $("body").on("click",".averiasTotalUltimosDias", function(){

        let _this = $(this)
        let textoB = _this.text()
        _this.prop('disabled',true)
      // console.log("el texto es: ",_this.text())
        _this.html(` <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        <span class="sr-only">Descargando..</span>`)

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
            url: "/administrador/averias-coes/averias/excel",
            method: 'get',
            data: {
                nodo,
                troba
            },
            cache: false, 
            })
            .done(function(result){

                //console.log(result)
                _this.prop('disabled',false)
                _this.html(`${textoB}`)
                
                var blob = new Blob([result], {
                    type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                });
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = 'averias_m1_'+fecha+'.xlsx';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);

            })

            .fail(function(xhr, jqXHR, textStatus) {
                _this.prop('disabled',false)
                _this.html(`${textoB}`)
                
                console.log(xhr)
                console.log(jqXHR)
                console.log(textStatus)
                var errorMessage1 = xhr.status + ': ' + "Hubo un error en los datos, intente en un minuto por favor."

                $("#body-errors-modal").html(`<div class="w-100 text-center text-danger">${errorMessage1}</div>`)
                $('#errorsModal').modal('show')
 
            });

    })


})