import peticiones from './peticiones.js'
import errors from  "@/globalResources/errors.js"

$(function(){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    

 
    $("body").on("click","#graficoDownstream", function(){
  
        let cmts = $(this).data("uno")
        let inter = $(this).data("dos")
 
        peticiones.redirectTabs($('#graficoSaturacionDownsTab')) 

        $("#resultGraficoDownSaturados").html(`<div id="carga_person">
                                                    <div class="loader">Loading...</div>
                                                </div>`);


        $.ajax({
            url:"/administrador/multiconsulta/grafico-trafico-down/detalle",
            method:"post",
            data:{
                cmts,
                inter
            },
            dataType: "json", 
        })
        .done(function(data){
            //console.log("El resultado es:",data)  
            
            let chartData = [];
            let result = data.response.data
            let down = data.response.down
            let cantidadData =  result[0].cant
            let capacidad=parseInt(cantidadData)*38

            result.forEach(el => {
                let date = new Date(`${el.fecha_hora}`);
                chartData.push([ 
                        date.valueOf(),
                        el.uso
                ])
            });
 
            Highcharts.setOptions({
                lang: {
                    months: [
                        'Enero', 'Febrero', 'Marzo', 'Abril',
                        'Mayo', 'Junio', 'Julio', 'Agosto',
                        'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
                    ],
                    weekdays: [
                        'Domingo', 'Lunes', 'Martes', 'Miércoles',
                        'Jueves', 'Viernes', 'Sabado'
                    ],
                    resetZoom:"Restablecer Zoom"
                }
                
            });
              
             Highcharts.chart("resultGraficoDownSaturados", {
                chart: {
                    zoomType: 'x'
                  },
                  title: {
                    text: `Niveles de uso de Puerto DOWN`
                  },
                  subtitle: {
                    text: `<span>CMTS : ${cmts} Puerto Down : ${down}</span> <br> <span>Cant Portadoras habilitadas : ${cantidadData} Capacidad : ${capacidad}Mbps</span><br><span>ÚLTIMOS 3 DÍAS</span>`
                  },
                  xAxis: {
                    type: 'datetime',
                    title: {
                        text: 'Fechas'
                      }
                  },
                  yAxis: {
                    title: {
                      text: 'Promedios'
                    }
                  },
                  legend: {
                    enabled: false
                  },
                  plotOptions:{
                    area: {
                        fillColor: {
                            linearGradient: {
                              x1: 0,
                              y1: 0,
                              x2: 0,
                              y2: 1
                            },
                            stops: [
                              [0, Highcharts.getOptions().colors[0]],
                              [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
                            ]
                          },
                          lineWidth: 1,
                    }
                  },
                  series: [{
                    type: 'area',
                    name: 'Cantidad Promedio',
                    data: chartData
                  }]
             });

        })
        .fail(function(jqXHR, textStatus){
           // console.log( "Error: " + jqXHR, textStatus); 
           
            //$("#resultGraficoDownSaturados").html(jqXHR.responseText)
            peticiones.redirectTabs($('#multiconsultaTab')) 
            $("#resultGraficoDownSaturados").html("")

            let erroresPeticion =""
            if(jqXHR.responseJSON){
                if(jqXHR.responseJSON.mensaje){
                    let erroresMensaje = jqXHR.responseJSON.mensaje  //captura objeto
                    let mensaje = errors.mensajeErrorJson(erroresMensaje)
                    erroresPeticion += mensaje 
                } 
            }
            if(jqXHR.status){
                let mensaje = errors.codigos(jqXHR.status)
                erroresPeticion += "<br> "+mensaje
            }
            
            erroresPeticion = (erroresPeticion.trim() == 0) ? "hubo un error en el servicio, intente nuevamente." : erroresPeticion

            $("#body-errors-modal").html(erroresPeticion)
            $('#errorsModal').modal('show')
            
            return false
 
        });  

    })
})