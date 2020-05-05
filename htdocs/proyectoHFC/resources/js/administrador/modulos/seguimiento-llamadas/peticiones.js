import errors from  "@/globalResources/errors.js"

const peticiones = {}

peticiones.resetInterval = function resetInterval(){
        if (INTERVAL_LOAD != null) {
                clearInterval(INTERVAL_LOAD)
                INTERVAL_LOAD = setInterval(() => { 
                        
                        if (ESTA_ACTIVO_REFRESH) { 
                              $("#preloadCharger").html("");
                              peticiones.cargandoPeticionPrincipal()
                        }
                
                }, 60000);
        }
}


peticiones.cargandoPeticionPrincipal = function cargandoPeticionPrincipal()
{
        //console.log('estoy en cargandoPeticionPrincipal');
        
        $("#seguimientoLlamadasGrafico").html("");	
        $("#preloadGraph").html(`<div id="carga_person" class="pre-estados-modems">
                                <div class="loader">Loading...</div>
                              </div>`);

        $.ajax({
              url:"/administrador/seguimiento-llamadas/grafico-llamadas-contenidas",
              method:"post",
              //dataType: "json",
              cache: false, 
        })
        .done(function(data){

                //console.log("El resultado ess:",data);
                
                $("#preloadGraph").html("");

                let colores = data.response.colorSeguimientoLlamadas
                let estado = data.response.estado

                let resultSeguimiento = data.response.resultHoraTotalSeguimiento
                let ok = resultSeguimiento[0].total - resultSeguimiento[0].contencion
                
                if(estado){
                  
                  let chartDataHisTroba = [
                          { name:"Hoy", data:[],color: colores[0].color},
                          { name:"Promedio", data:[],color: colores[1].color}
                  ];
                  //console.log("El resultado de chartDataHisTroba:",chartDataHisTroba);
                                            
                  let grafico_data = data.response.data
                  let chartData = [];
                  //console.log("El resultado de data:",grafico_data);
                                              
                  grafico_data.forEach(el => {
                          chartDataHisTroba[0].data.push([el.hora,parseFloat(el.Hoy)]);
                          chartDataHisTroba[1].data.push([el.hora,parseFloat(el.Promedio)]);
                          chartData.push(el.hora);
                  });
                  //console.log("El resultado de chartDataHisTroba:",chartDataHisTroba);
                                              
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
                                              
                  Highcharts.chart("seguimientoLlamadasGrafico", {
                          title: {
                                  text: `CENTRO DE CONTROL MOVISTAR1`
                          },
                                                    subtitle: {
                                                      text: `<span>${resultSeguimiento[0].total} Total de Llamadas DMPE</span><br> <span>${ok} No Alarmadas</span><br> <span> ${resultSeguimiento[0].contencion} Llamadas contenidas</span>`
                                                    },
                                                    xAxis: {
                                                      categories:chartData,
                                                      minRange: 1
                                                    },
                                                    yAxis: {
                                                      title: {
                                                        text: 'Cantidad de Llamadas'
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
                  // console.log("el Highcharts esta asi: ",Highcharts) 
                 
                }else{
                  $("#seguimientoLlamadasGrafico").html(`<div class="container">
                                                              
                                                     <div class="row justify-content-md-center">
                                                       <div class="col-lg-8 py-4">
                                                           <div class="card-body-ingresos"> 
                                                             <h4 class="text-center"><b>Gráfica</b></h4>
                                                                <h5 class="text-center"><b>Con los datos seleccionados no se ha podido generar la gráfica</b></h5>
                                                           </div>
                                                         </div>   
                                                     </div>
                             
                                               </div>`);
                }
                
                if (REFRESH_PERMISO) {
                    //console.log("entre a REFRESH_PERMISO")
                    ESTA_ACTIVO_REFRESH = true
                    peticiones.resetInterval()
                }
                                
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
                                              $('#errorsModal').modal('show')
                                            }
                                            return false
                                            
              if (REFRESH_PERMISO) {
                  //console.log("entre a REFRESH_PERMISO")
                  ESTA_ACTIVO_REFRESH = true
                  peticiones.resetInterval()
              }
              
        }); 


}
 
export default peticiones