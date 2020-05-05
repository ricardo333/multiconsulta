import errors from  "@/globalResources/errors.js"
const graficaLlamadasNodos = {}

graficaLlamadasNodos.grafico = function grafico(nodo)
{
    
        $("#contencionGraficaLlamadasNodos").append(`
                <div id="${nodo}" class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-4 contenido-border" style="padding-top: 18px;
                padding-bottom: 18px;"> </div>
        `)

    $.ajax({
        url:"/administrador/grafica-llamadas-nodos/graficas-nodos-lineal",
        method:"post",
        async:true,
        //dataType: "json",
        data:{
             nodo
        },
        cache: false, 
    })
    .done(function(data){

        //console.log("El resultado ess:",data);
        
        $("#preloadGraph").html("");
        
        let estado = data.response.estado
        let colores = data.response.colorGraficaLlamadasNodos
        let resultHoraTotal = data.response.resultHoraTotal
        let tok = $('meta[name="csrf-token"]').attr('content')
          
        let chartDataHisTroba = [
                { name:"Promedio", data:[],color: colores[0].color},
                { name:"Hoy_MartesA", data:[],color: colores[1].color}
        ];
                                  
        let grafico_data = data.response.data
        let chartData = [];
        let dia ='';
        let ult_reg = (resultHoraTotal[0].hora!=null)? 'ULT REG: '+resultHoraTotal[0].hora:'';
        let hora_total = (resultHoraTotal[0].total!=null)? 'Son '+resultHoraTotal[0].total+' Llamadas en DMPE':'';
                                    
        grafico_data.forEach(el => {
                chartDataHisTroba[0].data.push([el.hora,parseFloat(el.prom)]);
                chartDataHisTroba[1].data.push([el.hora,parseFloat(el.hoy)]);
                chartData.push(el.hora);
                dia = el.desdia;
        });
        chartDataHisTroba[1]['name'] = 'Hoy_'+dia;

        Highcharts.setOptions({ // Highcharts/modules/masters/highcharts.sr.js/defaultOptions/lang/months ....
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
                                    
        Highcharts.chart(nodo, {
                title: {
                        text: `CENTRO DE CONTROL MOVISTAR1`
                },
                                          subtitle: {
                                            useHTML: true,
                                            text: `<span class="center-display">${ult_reg} </span><br> <span class="center-display"> ${hora_total} </span><br> <span class="text-primary center-display"><b> Nodo: </b> 
                                                        <form method="post" action="/administrador/llamadas-troba" class="d-inline">
                                                                <input type="hidden" name="_token" value="${tok}">
                                                                <input type="hidden" name="nodo" value="${nodo}">  
                                                                <input type="hidden" name="grafica" value="true">
                                                                <button type="submit" title="Ver Llamadas por Troba" class="btn btn-link formato-link text-danger"><b>${nodo}</b></button>
                                                        </form>
                                                </span>`
                                          },
                                          xAxis: {
                                            categories:chartData,
                                            minRange: 1
                                          },
                                          yAxis: {
                                            title: {
                                              text: 'Promedio'
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
                                          series: chartDataHisTroba,
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
        
                //console.log('error en fail')
                $("#preloadGraph").html("");
                // return false;
                let erroresPeticion =""
                                if(jqXHR.responseJSON){
                                        if(jqXHR.responseJSON.mensaje){
                                                let erroresMensaje = jqXHR.responseJSON.mensaje  //captura objeto
                                                //console.log( "erroresMensaje: " + erroresMensaje); //erroresMensaje: Para procesar la gráfica, se requiere el puerto.
                                                //Para que funcione errors agregar la linea --> import errors from '@/globalResources/errors'
                                                let mensaje = errors.mensajeErrorJson(erroresMensaje) 
                                                //console.log( "mensaje: " + mensaje);
                                                erroresPeticion += mensaje
                                        } 
                                }
                                        
                                if(jqXHR.status){
                                        let mensaje = errors.codigos(jqXHR.status)
                                        erroresPeticion += "<br> "+mensaje
                                        //console.log( "erroresPeticion: " + erroresPeticion);
                                }
                                erroresPeticion = (erroresPeticion.trim() == 0) ? "hubo un error en el servicio, intente nuevamente." : erroresPeticion
                                
                                if(erroresPeticion){
                                        $("#body-errors-modal").html(`<div class='msg_request_error'>${erroresPeticion}</div>`)
                                        //$('#errorsModal').modal('show')
                                }
                                return false
                                        

    }); 
    
}

export default graficaLlamadasNodos