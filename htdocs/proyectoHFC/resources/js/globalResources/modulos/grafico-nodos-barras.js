import errors from  "@/globalResources/errors.js"
const graficaLlamadasNodosDia = {}

graficaLlamadasNodosDia.grafico = function grafico(nodo)
{
    
        $("#contencionGraficaLlamadasNodosDia").append(`
                <div id="${nodo}" class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3 contenido-border" style="padding-top: 18px;
                padding-bottom: 18px;"> </div>
        `)

    $.ajax({
        url:"/administrador/grafica-llamadas-nodos-dia/graficas-nodos-barras",
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
        let colores = data.response.colorGraficaLlamadasNodosDia
        let resultLlamadasDia = data.response.resultLlamadasDia
        let tok = $('meta[name="csrf-token"]').attr('content')
       
        let chartDataHisTroba = [
                { name:"Hoy", data:[],color: colores[0].color},
                { name:"Semana3", data:[],color: colores[1].color},
                { name:"Semana2", data:[],color: colores[2].color},
                { name:"Semana1", data:[],color: colores[3].color}
        ];
        
                                  
        let grafico_data = data.response.data
        //let chartData = [];
        let dia = '';
                          
        grafico_data.forEach(el => {
                chartDataHisTroba[0].data.push([el.desdia,parseFloat(el.sem1)]);
                chartDataHisTroba[1].data.push([el.desdia,parseFloat(el.sem2)]);
                chartDataHisTroba[2].data.push([el.desdia,parseFloat(el.sem3)]);
                chartDataHisTroba[3].data.push([el.desdia,parseFloat(el.sem4)]);
                //chartData.push(el.desdia);
                dia = el.desdia;
        });

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
                chart: {
                        type: 'column'
                },
                title: {
                        text: ``
                },
                                          subtitle: {
                                            useHTML: true,          
                                            text: `<span class="text-primary center-display"><b> Nodo: </b> 
                                                        <form method="post" action="/administrador/llamadas-troba" class="d-inline">
                                                                <input type="hidden" name="_token" value="${tok}">
                                                                <input type="hidden" name="nodo" value="${nodo}">  
                                                                <input type="hidden" name="grafica_acumulado_dia" value="true">
                                                                <button type="submit" title="Ver Llamadas por Troba" class="btn btn-link formato-link text-danger"><b>${nodo}</b></button>
                                                        </form>
                                                </span>`
                                          },
                                          xAxis: {
                                            categories: [''],
                                            title: {
                                                text: dia
                                            }
                                          },
                                          yAxis: {
                                            title: {
                                              text: 'Llamadas'
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
                /*
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
                                        */

    }); 
    
}

export default graficaLlamadasNodosDia