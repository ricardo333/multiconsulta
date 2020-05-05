import errors from  "@/globalResources/errors.js"

const peticiones = {}

peticiones.redirectTabs = function redirectTabs(identificador) {
    $('#tabsIngresoAveriasContent > .tab-pane').removeClass('show');
    $('#tabsIngresoAveriasContent > .tab-pane').removeClass('active');
    identificador.tab('show')  
}

peticiones.resetInterval = function resetInterval(){
        if (INTERVAL_LOAD != null) {
                clearInterval(INTERVAL_LOAD)
                INTERVAL_LOAD = setInterval(() => { 
                        
                        if (ESTA_ACTIVO_REFRESH) { 
                            //if ($( ".listaIngresoAveriasJefaturas" ).hasClass( "active" )) {
                              $("#preloadCharger").html("");
                              peticiones.cargandoPeticionPrincipal()
                            //} 
                        }
                
                }, 180000);   //}, 60000); 
        }
}

peticiones.setDataRequiredGraficaFilter = function setDataRequiredGraficaFilter(tipo)
{
        if (tipo == "averiasJefTab") {
          peticiones.cargaGraficaAveriasJefaturasLista()
        }else if(tipo == "averiasMotTab"){
          peticiones.cargaGraficaAveriasMotivosLista()
        }
}

peticiones.cargaGraficaAveriasJefaturasLista = function cargaGraficaAveriasJefaturasLista(){

        let valorFiltroEspecial = $("#display_filter_special").val()
        let jefatura = $("#"+valorFiltroEspecial+" #jefaturaIngresoAverias").val()
        let troba = $("#"+valorFiltroEspecial+" #trobaIngresoAverias").val()
        let motivoCuadroMando = $("#"+valorFiltroEspecial+" #motivoCuadroMando").val()

        //console.log(jefatura+'-'+troba);
        
        $("#averiasJefaturaGrafico").html("");
        $("#averiasJefaturaDetalle").html("");
        $("#averiasJefaturaGraficoPie").html("");
        //$("#averiasJefTabFiltro").html("");
        //const title = document.querySelector("h1")
        document.querySelector("#"+valorFiltroEspecial+" .averias-jefatura-filtro").setAttribute('disabled','true')
        document.querySelector("#"+valorFiltroEspecial+" .averias-jefatura-descargar").setAttribute('disabled','true')	
        $("#preloadGraphJef").html(`<div id="carga_person" class="pre-estados-modems">
                                <div class="loader">Loading...</div>
                              </div>`);

        $.ajax({
              url:"/administrador/ingreso-averias/grafico-averias-jefatura",
              method:"post",
              //dataType: "json", 
              data: {
                jefatura,
                troba
              },
              cache: false, 
        })
        .done(function(data){

                //console.log("El resultado ess:",data);
                
                document.querySelector("#"+valorFiltroEspecial+" .averias-jefatura-filtro").removeAttribute('disabled')
                document.querySelector("#"+valorFiltroEspecial+" .averias-jefatura-descargar").removeAttribute('disabled')
              
                $("#averiasJefaturaGrafico").html("");
                $("#preloadGraphJef").html("");
                $("#averiasJefaturaGraficoPie").html("");

                let colores = data.response.coloresNiveles
                let parametros = data.response.param
                let total = data.response.total
                let aniomes = data.response.aniomes
                let jefaturax = data.response.jefaturax
                let estado = data.response.estado
                let resultResumenAverias = data.response.resultResumenAverias
                let regDetalle = ''
                let descargasDetalle = ''
                let porc = 0   

                if( resultResumenAverias.length > 0 ){

                  resultResumenAverias.forEach(el => {

                    porc = (el.Cant / total[0].tot) * 100
                    porc = Math.round(porc * 100) / 100
                    regDetalle += `<tr>  
                                    <td>${el.Detalle}</td>
                                    <td class="text-right">${el.Cant}</td>
                                    <td class="text-right">${porc}</td>
                                    <td class="text-center">
                                      <a href="javascript:void(0)" title="Descargar excel" data-uno="${el.Detalle}" class="exportAveriasResumenIngresos"><i class="icofont-download btn-success"></i></a>
                                    </td>
                                  </tr>`

                  });
    
                  $("#averiasJefaturaDetalle").html(`<div class="container">
                                                        
                                                        <div class="row justify-content-md-center">
                                                           <div class="col-lg-8 py-4">
                                                              <div class="card-body-ingresos"> 
                                                                <h4 class="text-center"><b>Resumen de Ingresos:</b></h4>
                                                                <div id="preloadReporte" class="pre-load-reporte-averias preloadReporte"> </div>
                                                                <table id="resultSaturacionDown" class="table table-hover table-bordered w-100 table-text-xs">
                                                                        <thead>
                                                                                <tr>  
                                                                                <th>Detalle</th>
                                                                                <th>Cant</th>
                                                                                <th>Porc</th>
                                                                                <th>Down</th>
                                                                                </tr>
                                                                        </thead> 
                                                                        <tbody>
                                                                                ${regDetalle}
                                                                        </tbody> 
                                                                </table>
                                                              </div>
                                                            </div>   
                                                        </div>
    
                                                  </div>`);
    
                }else{
    
                        $("#averiasJefaturaDetalle").html(`<div class="container">
                                                              
                        <div class="row justify-content-md-center">
                          <div class="col-lg-8">
                              <div class="card-body-ingresos"> 
                                <h4 class="text-center"><b>Resumen de Ingresos:</b></h4>
                                <div id="preloadReporte" class="pre-load-reporte-averias preloadReporte"> </div>
                                <table id="resultSaturacionDown" class="table table-hover table-bordered w-100 table-text-xs">
                                        <thead>
                                                <tr>  
                                                  <th>Detalle</th>
                                                  <th>Cant</th>
                                                  <th>Porc</th>
                                                  <th>Down</th>
                                                </tr>
                                        </thead> 
                                        <tbody>
                                          <tr>  
                                            <td colspan="4" class="text-center"><b>No se han encontrado datos para el listado</b></td>
                                        </tr>
                                        </tbody> 
                                </table>
                              </div>
                            </div>   
                        </div>

                  </div>`);
    
                }  

                if(motivoCuadroMando=='cuadroMando'){
                  descargasDetalle = `
                  <div class="col-lg-12">
                      <div class="card-body-detalles text-center">
                        
                        <div style="padding:8px">
                          <a href="javascript:void(0)" data-uno="averias-dia" data-dos="" class="downloadAveriasDia btn btn-sm btn-success">Descarga de Averías del día</a>
                        </div>
                        <div style="padding:8px">
                          <a href="javascript:void(0)" data-uno="averias-down-t" data-dos="${aniomes}" class="downloadAveriasMes btn btn-sm btn-success">Descarga Averías del Mes</a>
                        </div>
                       
                      </div>
                  </div>`
                }else{
                  descargasDetalle = `
                  <div class="col-lg-12">
                      <div class="card-body-detalles text-center">
                        <div style="padding:8px">
                          <a href="javascript:void(0)" data-uno="ReporteFinalArbol.csv" class="downloadIngresoAverias btn btn-sm btn-success">Resumen de uso</a>
                        </div>
                        <div style="padding:8px">
                          <a href="javascript:void(0)" data-uno="averias-dia" data-dos="" class="downloadAveriasDia btn btn-sm btn-success">Descarga de Averías del día</a>
                        </div>
                        <div style="padding:8px">
                          <a href="javascript:void(0)" data-uno="averias-down-t" data-dos="${aniomes}" class="downloadAveriasMes btn btn-sm btn-success">Descarga Averías del Mes</a>
                        </div>
                        <div style="padding:8px">
                          <a href="javascript:void(0)" data-uno="reporte-arbol" class="exportAveriaReporte btn btn-sm btn-success">Detalle Arbol Última Semana - Ramas Completas</a>
                        </div>
                        <div style="padding:8px">
                          <a href="javascript:void(0)" data-uno="ResultadoArbol.csv" data-dos="ResultadoArbol" class="downloadIngresoAverias btn btn-sm btn-success">Detalle Uso de Arbol - Última Semana - Última marcación</a>
                        </div>
                      </div>
                  </div>`
                }
                
                $("#resultOpcionesIngresoAveriasJefaturas").html(descargasDetalle);
                
                if(estado === 'true'){
                  
                  let chartDataHisTroba = [
                          { name:"Ayer", data:[],color: colores[0].color},
                          { name:"Prom", data:[],color: colores[1].color},
                          { name:"Hoy", data:[] ,color: colores[2].color},
                          { name:"LIQ", data:[],color: colores[3].color},
                          { name:"Calls", data:[],color: colores[4].color},
                          { name:"Av. Arbol", data:[] ,color: colores[5].color},
                          { name:"Ges Arbol", data:[] ,color: colores[6].color}
                  ];
                  //console.log("El resultado de chartDataHisTroba:",chartDataHisTroba);
                                            
                  let grafico_data = data.response.data
                  //console.log("El resultado de data:",grafico_data);
                                              
                  let cmts = "";
                  let cant = "";
                  let down = "";
                  let capacidad = 0;
                                              
                  grafico_data.forEach(el => {
                          
                          chartDataHisTroba[0].data.push([parseFloat(el.hora),parseFloat(el.ayer)]);
                          chartDataHisTroba[1].data.push([parseFloat(el.hora),parseFloat(el.antes)]);
                          chartDataHisTroba[2].data.push([parseFloat(el.hora),parseFloat(el.hoy)]);
                          chartDataHisTroba[3].data.push([parseFloat(el.hora),parseFloat(el.liq)]);
                          chartDataHisTroba[4].data.push([parseFloat(el.hora),parseFloat(el.llamadas)]);
                          chartDataHisTroba[5].data.push([parseFloat(el.hora),parseFloat(el.arbol)]);
                          chartDataHisTroba[6].data.push([parseFloat(el.hora),parseFloat(el.arboltot)]);
                                                
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
                                              
                  Highcharts.chart("averiasJefaturaGrafico", {
                          title: {
                                  text: `CENTRO DE CONTROL MOVISTAR1`
                          },
                                                    subtitle: {
                                                      text: `<span>ULT REG: ${parametros[0].hora} </span><br> <h2> ${total[0].tot} Averias Registradas </h2>${jefaturax}`
                                                    },
                                                    xAxis: {
                                                      type: 'integer',
                                                      title: {
                                                          text: 'Hora'
                                                        }
                                                    },
                                                    yAxis: {
                                                      title: {
                                                        text: 'Averías Ingresadas'
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

                  $("#averiasJefaturaGraficoPie").html(`<b>Se compara con 1 dia antes y con el promedio del mismo dia de la semana del mes</b>`);

                }else{
                  $("#averiasJefaturaGrafico").html(`<div class="container">
                                                              
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
              $("#preloadGrafico").html("");
              $("#averiasJefaturaGrafico").html("")
              $("#averiasJefaturaGraficoPie").html("");
              document.querySelector("#"+valorFiltroEspecial+" .averias-jefatura-filtro").removeAttribute('disabled')
              document.querySelector("#"+valorFiltroEspecial+" .averias-jefatura-descargar").removeAttribute('disabled')
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

peticiones.cargaGraficaAveriasMotivosLista = function cargaGraficaAveriasMotivosLista(){

        let valorFiltroEspecial = $("#display_filter_special").val()
        let jefatura = $("#"+valorFiltroEspecial+" #jefaturaIngresoAverias").val()
        let troba = $("#"+valorFiltroEspecial+" #trobaIngresoAverias").val()

        document.querySelector("#"+valorFiltroEspecial+" .averias-jefatura-filtro").setAttribute('disabled','true')

        $("#averiasMotivosGrafico").html("");
        $("#averiasMotivosDetalle").html("");
        $("#preloadGraph").html(`<div id="carga_person" class="pre-estados-modems">
                                <div class="loader">Loading...</div>
                              </div>`);
      
      $.ajax({
        url:"/administrador/ingreso-averias/grafico-averias-motivos",
        method:"post",
        data:{
          jefatura,
          troba
        },
        dataType: "json", 
        })
        .done(function(data){

            //console.log("El resultado es:",data);
            document.querySelector("#"+valorFiltroEspecial+" .averias-jefatura-filtro").removeAttribute('disabled')
            $("#averiasMotivosGrafico").html("");
            $("#preloadGraph").html("");
            
            let colores = data.response.coloresNiveles
            let parametros = data.response.param
            let total = data.response.total
            let jefaturax = data.response.jefaturax
            let estado = data.response.estado

            let resultResumenAverias = data.response.resultResumenAverias
            let regDetalle = ''
            let porc = 0

            if( resultResumenAverias.length > 0 ){

              resultResumenAverias.forEach(el => {

                porc = (el.Cant / total[0].tot) * 100
                porc = Math.round(porc * 100) / 100
                regDetalle += `<tr>  
                                  <td>${el.Detalle}</td>
                                  <td class="text-right">${el.Cant}</td>
                                  <td class="text-right">${porc}</td>
                                  <td class="text-center">
                                    <a href="javascript:void(0)" title="Descargar excel" data-uno="${el.Detalle}" class="exportAveriasResumenIngresos"><i class="icofont-download btn-success"></i></a>
                                  </td>
                                </tr>`

              });

              $("#averiasMotivosDetalle").html(`<div class="container">
                                                    
                                                    <div class="row justify-content-md-center">
                                                       <div class="col-lg-8 py-4">
                                                          <div class="card-body-ingresos"> 
                                                            <h4 class="text-center"><b>Resumen de Ingresos:</b></h4>
                                                            <div id="preloadReporte" class="pre-load-reporte-averias preloadReporte"> </div>
                                                            <table id="resultSaturacionDown" class="table table-hover table-bordered w-100 table-text-xs">
                                                                    <thead>
                                                                            <tr>  
                                                                            <th>Detalle</th>
                                                                            <th>Cant</th>
                                                                            <th>Porc</th>
                                                                            <th>Down</th>
                                                                            </tr>
                                                                    </thead> 
                                                                    <tbody>
                                                                            ${regDetalle}
                                                                    </tbody> 
                                                            </table>
                                                          </div>
                                                        </div>   
                                                    </div>

                                              </div>`);

            }else{

              $("#averiasMotivosDetalle").html(`<div class="container">
                                                              
                                                  <div class="row justify-content-md-center">
                                                    <div class="col-lg-8">
                                                        <div class="card-body-ingresos"> 
                                                          <h4 class="text-center"><b>Resumen de Ingresos:</b></h4>
                                                          <div id="preloadReporte" class="pre-load-reporte-averias preloadReporte"> </div>
                                                          <table id="resultSaturacionDown" class="table table-hover table-bordered w-100 table-text-xs">
                                                                  <thead>
                                                                          <tr>  
                                                                            <th>Detalle</th>
                                                                            <th>Cant</th>
                                                                            <th>Porc</th>
                                                                            <th>Down</th>
                                                                          </tr>
                                                                  </thead> 
                                                                  <tbody>
                                                                    <tr>  
                                                                      <td colspan="4" class="text-center"><b>No se han encontrado datos para el listado</b></td>
                                                                  </tr>
                                                                  </tbody> 
                                                          </table>
                                                        </div>
                                                      </div>   
                                                  </div>

                                            </div>`);

            }

            if(estado === 'true'){

              let chartDataHisTroba = [
                  { name:"MalaSenal_SinSenal", data:[],color: colores[0].color},
                  { name:"NoNavega", data:[],color: colores[1].color},
                  { name:"Decoder", data:[] ,color: colores[2].color},
                  { name:"Lentitud", data:[],color: colores[3].color},
                  { name:"Masiva", data:[],color: colores[4].color},
                  { name:"Otros", data:[] ,color: colores[5].color},
                  { name:"Voip", data:[] ,color: colores[6].color}
              ];
              //console.log("El resultado de chartDataHisTroba:",chartDataHisTroba);
              
              let grafico_data = data.response.data
              //console.log("El resultado de data:",grafico_data);
              
              let cmts = "";
              let cant = "";
              let down = "";
              let capacidad = 0;
              
              grafico_data.forEach(el => {
                  
                  chartDataHisTroba[0].data.push([parseFloat(el.hora),parseFloat(el.MalaSenal_SinSenal)]);
                  chartDataHisTroba[1].data.push([parseFloat(el.hora),parseFloat(el.NoNavega)]);
                  chartDataHisTroba[2].data.push([parseFloat(el.hora),parseFloat(el.Decoder)]);
                  chartDataHisTroba[3].data.push([parseFloat(el.hora),parseFloat(el.Lentitud)]);
                  chartDataHisTroba[4].data.push([parseFloat(el.hora),parseFloat(el.Masiva)]);
                  chartDataHisTroba[5].data.push([parseFloat(el.hora),parseFloat(el.Otros)]);
                  chartDataHisTroba[6].data.push([parseFloat(el.hora),parseFloat(el.Voip)]);
                
              });

              //console.log("el push esta asi: ",chartDataHisTroba)
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
              
              Highcharts.chart("averiasMotivosGrafico", {
                    title: {
                      text: `CENTRO DE CONTROL MOVISTAR1`
                    },
                    subtitle: {
                      text: `<span>ULT REG: ${parametros[0].hora} </span><br> <h2> ${total[0].tot} Averias Registradas </h2>${jefaturax}`
                    },
                    xAxis: {
                      type: 'integer',
                      title: {
                          text: 'Hora'
                        }
                    },
                    yAxis: {
                      title: {
                        text: 'Averías Ingresadas'
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
            
            }else{
              $("#averiasMotivosGrafico").html(`<div class="container">
                                                          
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
            
            // console.log("el Highcharts esta asi: ",Highcharts)

            if (REFRESH_PERMISO) {
              //console.log("entre a REFRESH_PERMISO")
              ESTA_ACTIVO_REFRESH = true
              peticiones.resetInterval()
            }
            
        })
        .fail(function(jqXHR, textStatus){
            //console.log('error en fail')
            //Se envio : throw new HttpException(402,"Para procesar la gráfica, se requiere el puerto.");
              //console.log( "jqXHR.status: " + jqXHR.status);  //402
              //console.log( "textStatus: " + textStatus);  //Error
              //console.log( "jqXHR.responseJSON.mensaje: " + jqXHR.responseJSON.mensaje);  //jqXHR.responseJSON.mensaje: Para procesar la gráfica, se requiere el puerto.
            document.querySelector("#"+valorFiltroEspecial+" .averias-jefatura-filtro").removeAttribute('disabled')
            $("#averiasMotivosGrafico").html("");
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
            if (REFRESH_PERMISO) {
                //console.log("entre a REFRESH_PERMISO")
                ESTA_ACTIVO_REFRESH = true
                peticiones.resetInterval()
            }
            return false
            
        }); 
      

}
 
peticiones.cargandoPeticionPrincipal = function   cargandoPeticionPrincipal()
{

        let valorFiltroEspecial = $("#display_filter_special").val()
        //let valorFiltroEspecial = "averiasJefTab"
        peticiones.redirectTabs($('#'+valorFiltroEspecial))
        peticiones.setDataRequiredGraficaFilter(valorFiltroEspecial)

}

peticiones.cargaTrobasProjefatura = function cargaTrobasProjefatura(jefatura,callBack)
{

    $.ajax({
        url:`/administrador/ingreso-averias/jefatura-trobas`,
        method:"get",
        async: true,
        data:{
            jefatura
        },
       cache: false, 
       dataType: "json", 
      })
      .done(function(data){ 
        //console.log("callbak antes del envio:",data)
        return callBack(data);
      })
      .fail(function(jqXHR, textStatus, errorThrown){
         // console.log( "Request failed: " ,textStatus ,jqXHR,errorThrown);
           
          return callBack({
            "error":"failed",
            "jqXHR":jqXHR,
            "textStatus":textStatus,
            "errorThrown":errorThrown,
          });
          
      }); 
 

}
 
export default peticiones
