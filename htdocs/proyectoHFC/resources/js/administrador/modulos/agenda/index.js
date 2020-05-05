import peticiones from './peticiones.js'

$(function(){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    INTERVAL_LOAD =  setInterval(() => { 

      if (ESTA_ACTIVO_REFRESH) { 
          if ($( ".listaAgendas" ).hasClass( "active" )) {
            //console.log("Iniciando una nueva peticion....")
            peticiones.cargaListaAgendas()
          } 
      }

  }, 60000); 

    peticiones.cargaListaAgendas()

    $("#filtroBasicoAgenda").click(function(){
      peticiones.cargaListaAgendas()
    })



     //Maximizar

     $(".maxi_tab").click(function(){
       
        $("#tabsAgendasContent").toggleClass("fullscreen");
  
        if ($("#tabsAgendasContent").hasClass("fullscreen")) {
          console.log("tiene la clase full ")
           $(".maxi_tab").html('<i class="icofont-close-line-squared-alt"></i>') 
        }else{
          console.log("no tiene la clase full ")
          $(".maxi_tab").html('<i class="icofont-maximize"></i>')  
        } 
   
      })

    //Descarga

    $("#descargaAgendaTotal").click(function(){

        $("#errorsDescargaAgendas").html("")

        let  codigoCliente= $("#filtroCodClienteBasic").val()
        let  estado= $("#filtroEstadoBasic").val()

        let _this = $(this)

        _this.prop('disabled',true)
        _this.html(` Descarga Total <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        <span class="sr-only">Descargando..</span>`)
        
        $.ajax({
            xhrFields: { responseType: 'blob', },
            url: "/administrador/agendas/reporte/excel/total",
            method: 'get',
            data: {
              codigoCliente,
              estado
            },
            cache: false, 
        })
        .done(function(result){

            _this.prop('disabled',false)
            _this.html(`Descarga Total`)
            
            var blob = new Blob([result], {
                type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            });
            var link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = 'agenda_total.xlsx';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        })

        .fail(function(xhr, jqXHR, textStatus) { 
            _this.prop('disabled',false)
            _this.html(`Descarga Total`)
            
            var errorMessage1 = xhr.status + ': ' + "Hubo un error en los datos, intente en un minuto por favor."

            $("#errorsDescargaAgendas").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                        ${errorMessage1}</div>`)
 
            return false
            
        });

    })

    $("#descargaAgendaUltimaSemana").click(function(){

        $("#errorsDescargaAgendas").html("")

        
        let _this = $(this)

        _this.prop('disabled',true)
        _this.html(` Descarga últimos 7 días <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        <span class="sr-only">Descargando..</span>`)
        
        $.ajax({
            xhrFields: { responseType: 'blob', },
            url: "/administrador/agendas/reporte/excel/ultima-semana",
            method: 'get',
            cache: false, 
        })
        .done(function(result){

            _this.prop('disabled',false)
            _this.html(`Descarga últimos 7 días`)
            
            var blob = new Blob([result], {
                type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            });
            var link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = 'agenda_ultima-semana_.xlsx';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        })

        .fail(function(xhr, jqXHR, textStatus) { 
            _this.prop('disabled',false)
            _this.html(`Descarga últimos 7 días`)
            
            var errorMessage1 = xhr.status + ': ' + "Hubo un error en los datos, intente en un minuto por favor."

            $("#errorsDescargaAgendas").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                        ${errorMessage1}</div>`)
 
            return false
            
        });

    })
      
})