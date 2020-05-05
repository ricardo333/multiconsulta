import peticiones from './peticiones.js'

$(function(){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    /*INTERVAL_LOAD =  setInterval(() => { 

        if (ESTA_ACTIVO_REFRESH && REFRESH_PERMISO) { 
            if ($( ".listaMonitorFuentesTotal" ).hasClass( "active" )) {
              //console.log("Iniciando una nueva peticion....")
              peticiones.loadMonitorFuentes()
            } 
        }

    }, 30000); */

  
    peticiones.loadMonitorFuentes()

    $(".return_monitorFuentesTab").click(function(){
        peticiones.redirectTabs($('#monitorFuentesListTab')) 
    })

    $("#filtroBasicoMFuentes").click(function(){
        peticiones.loadMonitorFuentes()
    })

    function leadingZero(value) {
        if (value < 10) {
          return "0" + value.toString();
        }
        return value.toString();
    }

    $("#btnTotal").click(function(){

        let nodo = $("#listaNodosMFFilter").val() || null
        let tipoBateria = $("#listaTipoBateriaMFFilter").val() || null
        let estadoDeGestion = $("#listaEstadosMFilter").val() || null
 
        let filtros = {
            nodo,
            tipoBateria,
            estadoDeGestion
        }

        $("#resultOpcionesMonitoreoFuentes").html("")
        $("#content_btn_dowload_fuentes").addClass("d-none")
        $("#preloadMaping").html(`<div id="carga_person">
                                <div class="loader">Loading...</div>
                            </div>`)
   
        let today = new Date();
        let dd = today.getDate();
        let mm = today.getMonth(mm)+1;
        let yyyy = today.getFullYear();
        let hh = today.getHours();
        let mi = today.getMinutes();
        let ss = today.getSeconds();

        let fecha = yyyy+''+leadingZero(mm)+''+leadingZero(dd)+''+leadingZero(hh)+''+leadingZero(mi)+''+leadingZero(ss);

        //console.log(fecha);

        $.ajax({
            xhrFields: { responseType: 'blob', },
            url: "/administrador/monitor-fuentes/excel/total",
            method: 'get',
            data: filtros,
            cache: false, 
            })
            .done(function(result){
                
                var blob = new Blob([result], {
                    type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                });
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = 'monitor_fuentes_total_'+fecha+'.xlsx';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);

                $("#resultOpcionesMonitoreoFuentes").html("")
                $("#content_btn_dowload_fuentes").removeClass("d-none")
                $("#preloadMaping").html("")
 

            })

            .fail(function(jqXHR, textStatus) { 

                $("#resultOpcionesMonitoreoFuentes").html("")
                $("#content_btn_dowload_fuentes").removeClass("d-none")
                $("#preloadMaping").html("")

                console.log(jqXHR)
                console.log(textStatus)
                var errorMessage1 = jqXHR.status + ': ' + "Hubo un error en los datos, intente en un minuto por favor."
 
                //$("#resultOpcionesMonitoreoFuentes").html(jqXHR.responseText)
                //return false
                 

                $("#resultOpcionesMonitoreoFuentes").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                        ${errorMessage1}</div>`); 
  
                return false
 
            });



    })

        //Maximizar

        $(".maxi_tab").click(function(){
       
            $("#tabsMonitorFuentesContent").toggleClass("fullscreen");
      
            if ($("#tabsMonitorFuentesContent").hasClass("fullscreen")) {
              //console.log("tiene la clase full ")
               $(".maxi_tab").html('<i class="icofont-close-line-squared-alt"></i>') 
            }else{
              //console.log("no tiene la clase full ")
              $(".maxi_tab").html('<i class="icofont-maximize"></i>')  
            } 
       
          })

     

})