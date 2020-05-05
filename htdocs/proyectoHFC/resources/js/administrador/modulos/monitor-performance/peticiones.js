import errors from  "@/globalResources/errors.js"
import columnas from  "@/globalResources/tablas/columnas.js"

const peticiones = {}

function leadingZero(value) {
    if (value < 10) {
      return "0" + value.toString();
    }
    return value.toString();
}


peticiones.resetInterval = function resetInterval(){
  if (INTERVAL_LOAD != null) {
          clearInterval(INTERVAL_LOAD)
          //console.log("Se limpio el interval y se debe iniciar nuevamente...")
          INTERVAL_LOAD = setInterval(() => { 

                  if (ESTA_ACTIVO_REFRESH) { 
                      if ($( ".moduloPerformance" ).hasClass( "active" )) {
                          peticiones.cargandoPeticionPrincipal()
                      } 
                  }
          
          }, 30000); 
  }
}



peticiones.redirectTabs = function redirectTabs(identificador) {
    $('#tabsPerformanceContent > .tab-pane').removeClass('show');
    $('#tabsPerformanceContent > .tab-pane').removeClass('active');
    identificador.tab('show')  
}


peticiones.armandoColumnasGuardian = function armandoColumnasGuardian()
{
        let columnasContent =  [ {data: 'id'}  ]

        columnasContent.push( 
                {data:'tabla'},
                {data:'cant'},
                {data:'fecha'}
        )

        return columnasContent
}


peticiones.armandoColumnasBaseDatos = function armandoColumnasBaseDatos()
{
        let columnasContent =  [ {data: 'it'}  ]

        columnasContent.push( 
                {data:'ID'},
                {data:'DB'},
                {data:'COMMAND'},
                {data:'TIME'},
                {data:'STATE'},
                {data:'INFO'},
                {data:'MEMORY_USED'},
                {data:'kill'}
        )

        return columnasContent
}



function procesandoResultadoListaGuardian(result)
{
        for (let i = 0; i < result.length ; i++) {

            result[i].id = result[i].id
            result[i].tabla = result[i].tabla
            result[i].cant =  result[i].cant
            result[i].fecha =  result[i].fecha
 
        }

        return result
}







function procesandoResultadoLista(result)
{
        for (let i = 0; i < result.length ; i++) {

            result[i].it = `<div class="text-center">
                                <span>
                                    ${result[i].it}
                                </span>
                            </div>`

            result[i].ID = result[i].ID
            result[i].DB = result[i].DB
            result[i].COMMAND =  result[i].COMMAND
            result[i].TIME =  result[i].TIME
            result[i].STATE =  result[i].STATE
            result[i].INFO =  result[i].INFO
            result[i].MEMORY_USED =  result[i].MEMORY_USED
            result[i].kill =  `<a href="javascript:void(0)" data-uno="${result[i].ID}"  
                                  class="shadow-sm font-weight-bold killProcess" alt="kill" title="kill">
                                    ${result[i].kill}
                              </a>`
 
        }

        return result
}



peticiones.getDataRequiredFilterPerformance = function getDataRequiredFilterPerformance(tipo)
{
        let data = {}

        //data.tipoCaidas = $("#display_filter_special").val()
        data.parametros = {}
        //data.parametros.nodo = "" 
        //data.parametros.nodo = nodo
        data.parametros.tipoPerformance = tipo
  
        if (tipo == "monitor_apache") {
          data.redirect = $('#monitorPerformanceApacheTab')
          //data.tabla = $("#resultPerformanceSQL") 
          //data.columnasCaidas = peticiones.armandoColumnas()
        } else if (tipo == "monitor_guardian") {
          data.redirect = $('#monitorPerformanceGuardianTab')
          data.tabla = $("#resultPerformanceGuardian") 
          data.columnasCaidas = peticiones.armandoColumnasGuardian()
        } else {
          data.redirect = $('#monitorPerformanceSQLTab')
          data.tabla = $("#resultPerformanceSQL")
          data.columnasCaidas = peticiones.armandoColumnasBaseDatos()
        }
  
        return data
}



peticiones.cargandoGraficoApache = function cargandoGraficoApache()
{
              
        $("#monitorApacheGrafico").html("");	
        $("#preloadGraph").html(`<div id="carga_person" class="pre-estados-modems">
                                <div class="loader">Loading...</div>
                              </div>`);

        let today = new Date();
        let dd = today.getDate();
        let mm = today.getMonth(mm)+1;
        let yyyy = today.getFullYear();
        let hh = today.getHours();
        let mi = today.getMinutes();
        let ss = today.getSeconds();

        let fecha = 'Fecha Actualización: '+yyyy+'/'+leadingZero(mm)+'/'+leadingZero(dd)+' - '+leadingZero(hh)+':'+leadingZero(mi)+':'+leadingZero(ss);
        console.log(fecha);

        $.ajax({
              url:"/administrador/monitor-performance/grafico-monitor-performance",
              method:"post",
              //dataType: "json",
              cache: false, 
        })
        .done(function(data){

                //console.log("El resultado ess:",data);
                
                $("#preloadGraph").html("");

                let colores = data.response.colorMonitorApache
                let estado = data.response.estado

                //let resultSeguimiento = data.response.resultHoraTotalSeguimiento
                //let ok = resultSeguimiento[0].total - resultSeguimiento[0].contencion
                
                if(estado){
                  
                  let chartDataHisTroba = [
                          { name:"Connect", data:[],color: colores[0].color},
                          { name:"Processing", data:[],color: colores[1].color}
                  ];
                  //console.log("El resultado de chartDataHisTroba:",chartDataHisTroba);
                                            
                  let grafico_data = data.response.data
                  let chartData = [];
                  //console.log("El resultado de data:",grafico_data);
                                              
                  grafico_data.forEach(el => {
                          chartDataHisTroba[0].data.push([el.hora,parseFloat(el.Connect)]);
                          chartDataHisTroba[1].data.push([el.hora,parseFloat(el.Processing)]);
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
                                              
                  Highcharts.chart("monitorApacheGrafico", {
                          title: {
                                  text: `CENTRO DE CONTROL MOVISTAR1`
                          },
                                                    subtitle: {
                                                      text: `<span>MONITOR APACHE</span><br><span>`+fecha+`</span><br><span>Ultima 1/2 Hora</span>`
                                                    },
                                                    xAxis: {
                                                      categories:chartData,
                                                      minRange: 1
                                                    },
                                                    yAxis: {
                                                      title: {
                                                        text: 'Tiempo'
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
                  $("#monitorApacheGrafico").html(`<div class="container">
                                                              
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
                
                /*
                if (REFRESH_PERMISO) {
                    //console.log("entre a REFRESH_PERMISO")
                    ESTA_ACTIVO_REFRESH = true
                    peticiones.resetInterval()
                }
                */
                                
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
              /*
              if (REFRESH_PERMISO) {
                  //console.log("entre a REFRESH_PERMISO")
                  ESTA_ACTIVO_REFRESH = true
                  peticiones.resetInterval()
              }
              */
              
        }); 

}



peticiones.cargaPerformanceGuardianLista = function cargaPerformanceGuardianLista(COLUMNS_CAIDAS,BUTTONS_CAIDAS,
  parametersDataAverias,tabla){

   
      $("#display_filter_special").prop("disabled", true); 
      //if (REFRESH_PERMISO) {
      //        ESTA_ACTIVO_REFRESH = false
      //}
     
      tabla.DataTable({
              "destroy": true,
              "processing": true, 
              "serverSide": true,
              "dom":'<"row mx-0"'
                      +'<"col-12 col-sm-6"l><"col-12 col-sm-6 text-right"B>>'
                      +'<"row"'
                      +'<"col-sm-12 px-0 table-responsive tableFixHead"t>>'
                      +'r',
              "buttons":BUTTONS_CAIDAS,
              "ajax": {  
                      'url':'/administrador/monitor-performance/listaGuardian',
                      "type": "GET", 
                      "data": {},
                      'dataSrc': function(json){
                          
                        let result = json.data
                        let dataProcesada = procesandoResultadoListaGuardian(result)            
                          
                        return dataProcesada        
                            
                      },
                      'error': function(jqXHR, textStatus, errorThrown)
                      {  

                          $("#display_filter_special").prop("disabled", false);     

                          let erroresPeticion =""
                                
                          if(jqXHR.status){
                              let mensaje = errors.codigos(jqXHR.status)
                              erroresPeticion = mensaje
                          }
                          if(jqXHR.responseJSON){
                              if(jqXHR.responseJSON.mensaje){
                                  let erroresMensaje = jqXHR.responseJSON.mensaje  //captura objeto
                                  let mensaje = errors.mensajeErrorJson(erroresMensaje)
                                  erroresPeticion += "<br> "+mensaje 
                              } 
                          }
                          erroresPeticion = (erroresPeticion.trim() == 0) ? "hubo un error en el servicio, intente nuevamente." : erroresPeticion
                        
                          $("#body-errors-modal").html(`<div class="text-danger">${erroresPeticion}</div>`)
                          $('#errorsModal').modal('show')
                          return false

                      }
              }, 
              "columns": COLUMNS_CAIDAS,
              'columnDefs': [
                      {
                         'targets': '_all',
                         'createdCell':  function (td, cellData, rowData, row, col) { 
                               
                              //$(td).css({"background":`${rowData.fondo}`,"color":`${rowData.letra}`}); 
                            $(td).addClass("text-center")

                         }
                      },
                      {
                           
                      "targets": '_all',
                      "orderable" : false,
                      "searchable": false,
                              
                      } 
              ] ,
              "initComplete": function(){
                  // console.log("Termino la carga completa")
                  $("#display_filter_special").prop("disabled", false);
                  /*
                  if (REFRESH_PERMISO) {
                      ESTA_ACTIVO_REFRESH = true
                      peticiones.resetInterval()
                  }
                  */
              },
              "pageLength": 100,
              "language": {
                          "info": "_TOTAL_ registros",
                          "search": "Buscar",
                          "paginate": {},
                          /*
                          "paginate": {
                                  "next": "Siguiente",
                                  "previous": "Anterior",
                          },
                          */
                          "lengthMenu": " ",
                          "loadingRecords": "<div id='carga_person'> <div class='loader'>Cargando...</div></div>",
                          "processing": "<div id='carga_person'> <div class='loader'>Procesando...</div></div>",
                          "emptyTable": "No hay datos disponibles",
                          "zeroRecords": "No hay coincidencias", 
                          "infoEmpty": "",
                          "infoFiltered": ""
              }
          });

          tabla.parent().addClass("table-responsive tableFixHead") 
          // $("#filtroContentHFC").removeClass("d-none")

          let tablaHead = $('.tableFixHead').find('thead th')
          $('.tableFixHead').on('scroll', function() {
          // console.log("ejecutando"+this.scrollTop); 
          tablaHead.css('transform', 'translateY('+ this.scrollTop +'px)'); 
          }); 

}





peticiones.cargaPerformanceSQLLista = function cargaPerformanceSQLLista(COLUMNS_CAIDAS,BUTTONS_CAIDAS,
    parametersDataAverias,tabla,tipoServer){

     
        $("#display_filter_special").prop("disabled", true); 
        //if (REFRESH_PERMISO) {
        //        ESTA_ACTIVO_REFRESH = false
        //}
       
        tabla.DataTable({
                "destroy": true,
                "processing": true, 
                "serverSide": true,
                "dom":'<"row mx-0"'
                        +'<"col-12 col-sm-6"l><"col-12 col-sm-6 text-right"B>>'
                        +'<"row"'
                        +'<"col-sm-12 px-0 table-responsive tableFixHead"t>>'
                        +'<"row"'
                        +'<"col-12 col-sm-5"i><"col-12 col-sm-7"p>>'
                        +'r',
                "buttons":BUTTONS_CAIDAS,
                "ajax": {  
                        'url':'/administrador/monitor-performance/lista',
                        "type": "GET", 
                        "data": function ( d ){
                          d.tipoServer = tipoServer
                        },//function ( d ) {

                                //d.filtroJefatura = parametersDataAverias.jefatura;
                                //d.filtroEstado = parametersDataAverias.estado;
                                //d.tipoCaida = parametersDataAverias.tipoCaidas;
                                //d.nodo = parametersDataAverias.nodo;
                                /*d.num_puer = des_puer;*/
                        //},
                        'dataSrc': function(json){
                            //console.log("Termino la carga asi tenga error.. :",json)                         
                            //return json
                            let result = json.data
                            //  console.log("El result es: ",result)
                            //let dataProcesada = procesandoResultadoLista(result,parametersDataAverias.tipoCaidas)
                            let dataProcesada = procesandoResultadoLista(result)            
                            //console.log("La data procesada final... es: ",result)
                            return dataProcesada        
                              
                        },
                        'error': function(jqXHR, textStatus, errorThrown)
                        {  

                            $("#display_filter_special").prop("disabled", false);
                            /*
                            if (REFRESH_PERMISO) {
                                ESTA_ACTIVO_REFRESH = true
                                peticiones.resetInterval()
                            }
                            */      
  
                            let erroresPeticion =""
                                  
                            if(jqXHR.status){
                                let mensaje = errors.codigos(jqXHR.status)
                                erroresPeticion = mensaje
                            }
                            if(jqXHR.responseJSON){
                                if(jqXHR.responseJSON.mensaje){
                                    let erroresMensaje = jqXHR.responseJSON.mensaje  //captura objeto
                                    let mensaje = errors.mensajeErrorJson(erroresMensaje)
                                    erroresPeticion += "<br> "+mensaje 
                                } 
                            }
                            erroresPeticion = (erroresPeticion.trim() == 0) ? "hubo un error en el servicio, intente nuevamente." : erroresPeticion
                          
                            $("#body-errors-modal").html(`<div class="text-danger">${erroresPeticion}</div>`)
                            $('#errorsModal').modal('show')
                            return false
 
                        }
                }, 
                "columns": COLUMNS_CAIDAS,
                'columnDefs': [
                        {
                           'targets': '_all',
                           'createdCell':  function (td, cellData, rowData, row, col) { 
                                 
                                $(td).css({"background":`${rowData.fondo}`,"color":`${rowData.letra}`}); 
                                //$(td).addClass("text-center")

                           }
                        },
                        {
                             
                        "targets": '_all',
                        "orderable" : false,
                        "searchable": false,
                                
                        } 
                ] ,
                "initComplete": function(){
                    // console.log("Termino la carga completa")
                    $("#display_filter_special").prop("disabled", false);
                    /*
                    if (REFRESH_PERMISO) {
                        ESTA_ACTIVO_REFRESH = true
                        peticiones.resetInterval()
                    }
                    */
                },
                "pageLength": 100,
                "language": {
                            "info": "_TOTAL_ registros",
                            "search": "Buscar",
                            "paginate": {
                                    "next": "Siguiente",
                                    "previous": "Anterior",
                            },
                            "lengthMenu": " ",
                            "loadingRecords": "<div id='carga_person'> <div class='loader'>Cargando...</div></div>",
                            "processing": "<div id='carga_person'> <div class='loader'>Procesando...</div></div>",
                            "emptyTable": "No hay datos disponibles",
                            "zeroRecords": "No hay coincidencias", 
                            "infoEmpty": "",
                            "infoFiltered": ""
                }
            });


            tabla.parent().addClass("table-responsive tableFixHead") 
            // $("#filtroContentHFC").removeClass("d-none")

            let tablaHead = $('.tableFixHead').find('thead th')
            $('.tableFixHead').on('scroll', function() {
            // console.log("ejecutando"+this.scrollTop); 
            tablaHead.css('transform', 'translateY('+ this.scrollTop +'px)'); 
            }); 

}



peticiones.cargandoPeticionPrincipal = function cargandoPeticionPrincipal()
{
              
        let today = new Date();
        let dd = today.getDate();
        let mm = today.getMonth(mm)+1;
        let yyyy = today.getFullYear();
        let hh = today.getHours();
        let mi = today.getMinutes();
        let ss = today.getSeconds();

        let fecha = 'Fecha Actualización: '+yyyy+'/'+leadingZero(mm)+'/'+leadingZero(dd)+' - '+leadingZero(hh)+':'+leadingZero(mi)+':'+leadingZero(ss);
        console.log(fecha);

        //document.getElementById("fechaConsulta").innerHTML = fecha;

        let valorFiltroEspecial = $("#display_filter_special").val();

        let params = peticiones.getDataRequiredFilterPerformance(valorFiltroEspecial);
   
        peticiones.redirectTabs(params.redirect)
        $(".content_filter_basic").css({"display":"none"})
  
        if (valorFiltroEspecial == "monitor_apache") {
          peticiones.cargandoGraficoApache()
        } else if(valorFiltroEspecial == "monitor_guardian"){
          document.getElementById("fechaGuardian").innerHTML = fecha;
          peticiones.cargaPerformanceGuardianLista(params.columnasCaidas,BUTTONS_CAIDAS_MASIVAS,params.parametros,params.tabla)
        } else {
          let tipoServer = valorFiltroEspecial;
          document.getElementById("fechaSQL").innerHTML = fecha;
          peticiones.cargaPerformanceSQLLista(params.columnasCaidas,BUTTONS_CAIDAS_MASIVAS,params.parametros,params.tabla,tipoServer)
        }


        

}




export default peticiones