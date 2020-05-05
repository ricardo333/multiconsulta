import peticiones from './peticiones.js'
import errors from  "@/globalResources/errors.js"

$(function(){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
 
    
    $("body").on("click",".verGraficoFuentePoder", function(){
 
        let mac = $(this).data("uno")

        if (mac == null || mac == "" || mac == undefined) {
            console.log("la mac es: ",mac)
            $("#body-errors-modal").html(`<div class="w-100 text-center text-danger">No hay una gráfica disponible para esta fuente.</div>`)
            $('#errorsModal').modal('show')
            return false
        }
      
 
        peticiones.redirectTabs($('#graficoFuentesPoderTab')) 

        $("#resultGraficoDownFuentes").html(`<div id="carga_person">
                                                    <div class="loader">Loading...</div>
                                                </div>`);


        $.ajax({
            url:"/administrador/monitor-fuentes/grafico-fuentes",
            method:"post",
            data:{
                mac
            },
            dataType: "json", 
        })
        .done(function(data){
            //console.log("El resultado es:",data)  

            let colores = data.response.coloresFuente

           
            let chartDataFuentes = [
                { name:"Vol_ent", data:[], color: colores[0].color },
                { name:"Vol_sal", data:[], color: colores[1].color },
                { name:"Cor_sal", data:[], color: colores[2].color },
                { name:"Bateria", data:[], color: colores[3].color }
            ];

            let grafico_data = data.response.data
            let nodoTroba = "";

            grafico_data.forEach(el => {
               //let date = new Date();
               let date = new Date(`${el.fechahora}`);
               // console.log("la fecha es: ",el.fechahora, "=>",date,"-->",date.valueOf())
               //let newFecha = el.hora.split(':')
               //let formatFecha =new Date(date.getFullYear(),date.getMonth(),date.getDay(), newFecha[0], newFecha[1])
               //console.log("la fecha es: ",el.hora, "=> ",formatFecha,"-->",formatFecha.valueOf())

                chartDataFuentes[0].data.push([date.valueOf(),parseFloat(el.InputVoltagefinal)]);
                chartDataFuentes[1].data.push([date.valueOf(),parseFloat(el.OutputVoltagefinal)]);
                chartDataFuentes[2].data.push([date.valueOf(),parseFloat(el.OutputCurrentfinal)]);
                chartDataFuentes[3].data.push([date.valueOf(),parseFloat(el.TotalStringVoltagefinal)]);
                nodoTroba = el.nodo + " - "+el.troba 
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

            Highcharts.chart("resultGraficoDownFuentes", {
                chart: {
                    zoomType: 'x'
                  },
                title: {
                  text: `Gráfico de Fuente`
                },
                subtitle: {
                  text: `<span>Centro de control Movistar1 </span><br><span> Monitoreo de Fuentes de Poder</span><br>  <span>  NODO - TROBA : ${nodoTroba} </span>`
                },
                xAxis: {
                    type: 'datetime',
                    title: {
                      text: 'Horas'
                    }
                },
                yAxis: {
                  title: {
                    text: 'Valores'
                  }
                }, 
                legend: {
                      layout: 'vertical',
                      align: 'right',
                      verticalAlign: 'middle'
                  },
                plotOptions:{
                  area: {
                      fillColor: {
                          linearGradient: {
                            x1: 0,
                            y1: 1,
                            x2: 0,
                            y2: 1,
                            x3: 0,
                            y3: 1
                          },
                          stops: [
                            [0, Highcharts.getOptions().colors[0]],
                            [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
                          ]
                        },
                        lineWidth: 1,
                  }
                },
                series: chartDataFuentes,
                responsive: {
                  rules: [{
                      condition: {
                          maxWidth: 500
                      },
                      chartOptions: {
                          legend: {
                              layout: 'horizontal',
                              align: 'center',
                              verticalAlign: 'bottom'
                          }
                      }
                  }]
              }
           });
            

        })
        .fail(function(jqXHR, textStatus){
           // console.log( "Error: " + jqXHR, textStatus); 
           
            //$("#resultGraficoDownFuentes").html(jqXHR.responseText)

           // return false
            peticiones.redirectTabs($('#monitorFuentesListTab')) 
            $("#resultGraficoDownFuentes").html("")

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