import peticiones from './peticiones.js'
import errors from  "@/globalResources/errors.js"

$(function(){
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    //Maximizar

    $(".maxi_tab").click(function(){
       
        $("#tabsTrabajosPContent").toggleClass("fullscreen");
  
        if ($("#tabsTrabajosPContent").hasClass("fullscreen")) {
         // console.log("tiene la clase full ")
           $(".maxi_tab").html('<i class="icofont-close-line-squared-alt"></i>') 
        }else{
          //console.log("no tiene la clase full ")
          $(".maxi_tab").html('<i class="icofont-maximize"></i>')  
        } 
   
    })

    cargaListaPrincipal()

    function cargaListaPrincipal(){
        let valorJefaturaTP = $("#listaJefaturasTP").val()
        let valorEstadoTP = $("#listaEstadosTP").val()
        let parametros = {'jefatura':valorJefaturaTP,"estado":valorEstadoTP}
        let columnas = peticiones.armandoColumnasTP()
        peticiones.loadTrabajosProgramadosList(columnas,parametros)
    }

    $("#filtrobasicoTP").click(function(){
        $("#contentZonasFiltro").css({"display":"none"})
        cargaListaPrincipal() 
    })

    $("body").on("click",".return_trabajoPListTab", function(){ 
        peticiones.redirectTabs($('#listaTrabajoPTab')) 
    })

    $("#descargarTPGeneral").click(function(){

        let _this = $(this)
        _this.prop('disabled',true)
        _this.html(`Descarga Total <i class="icofont-file-excel icofont-md"></i> </i> <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        <span class="sr-only">Descargando..</span>`)

        $.ajax({
            xhrFields: { responseType: 'blob', },
            url: "/administrador/trabajos-programados/descargar/excel/total",
            method: 'get',
            cache: false, 
            })
            .done(function(result){

                _this.prop('disabled',false)
                _this.html(`Descarga Total <i class="icofont-file-excel icofont-md"></i>`)

                
                var blob = new Blob([result], {
                    type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                });
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = 'trabajosProgramadosTotal.xlsx';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            })
            .fail(function(xhr, jqXHR, textStatus) { 
                _this.prop('disabled',false)
                _this.html(`Descarga Total <i class="icofont-file-excel icofont-md"></i>`)
                
                var errorMessage1 = xhr.status + ': ' + "Hubo un error en los datos, intente en un minuto por favor."

                $("#errorDescargaTotalTP").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    ${errorMessage1}</div>`)
                
                    
                return false
                
            });
        

    })

    $("body").on("click",".imagenes_ttpp_aper_cierre", function(){
        let imagen = $(this).data("uno")
        let estado =$(this).data("dos")
        $("#imagenViewDetalleTTPP").prop("src",imagen)
        $("#text_preview_TTPP").html(estado)
        $("#imagenDetallesModal").modal("show")

    })

    //Descargas
    function leadingZero(value) {
        if (value < 10) {
          return "0" + value.toString();
        }
        return value.toString();
    }

    $("body").on("click",".descargarLlamadasPorNodo", function(){

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
            url: "/administrador/trabajos-programados/llamadas-nodo/excel/excelDMPE/",
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

    $("body").on("click",".descargarLlamadaNodoAverias", function(){

        let _this = $(this)
        let textoB = _this.text()
        _this.prop('disabled',true)
       // console.log("el texto es: ",_this.text())
        _this.html(` <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        <span class="sr-only">Descargando..</span>`)

        let nodo = $(this).data("uno")
        let troba = $(this).data("dos")
        let fechaHora = $(this).data("tres")
        let trabajoProgramado = $(this).data("cuatro")

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
            url: "/administrador/trabajos-programados/llamadas-nodo/excel/excelAverias",
            method: 'get',
            data: {
                nodo,
                troba,
                trabajoProgramado,
                fechaHora
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

    $("body").on("click",".graficaLlamadasTroba", function(){
        ///
        let nodo = $(this).data("uno")
        let troba = $(this).data("dos")

       
 
        $("#contentGraficoLlamadas").html(`<div id="carga_person" class="pre-estados-modems">
                                <div class="loader">Loading...</div>
                              </div>`);
            
        peticiones.redirectTabs($("#graficoLlamadasTab"))

        $.ajax({
            url:"/administrador/trabajos-programados/llamadas-troba/grafica",
            method:"post",
            data:{
                nodo, 
                troba
            },
            dataType: "json",
            cache: false, 
      })
      .done(function(data){

              console.log("El resultado grafico es:",data);
              
              $("#contentGraficoLlamadas").html("");

             

              let colores = data.response.colores
              let grafico_data = data.response.data 
              let horaPrincipal = data.response.hora 
              let totalPrincipal = data.response.total 
              let nodoPrincipal = data.response.nodo 
              let trobaPrincipal = data.response.troba 

              let tok = $('meta[name="csrf-token"]').attr('content')
          
                
                let chartDataHisTroba = [
                        { name:"Promedio", data:[],color: colores[0].color},
                        { name:"HoyViernes", data:[],color: colores[1].color}
                ];
                //console.log("El resultado de chartDataHisTroba:",chartDataHisTroba);
                                          
                
                let chartData = [];
                //console.log("El resultado de data:",grafico_data);
                                            
                grafico_data.forEach(el => {
                        chartDataHisTroba[0].data.push([el.hora,parseFloat(el.prom)]);
                        chartDataHisTroba[1].data.push([el.hora,parseFloat(el.hoy)]);
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
                                            
                Highcharts.chart("contentGraficoLlamadas", {
                        title: {
                                text: `CENTRO DE CONTROL MOVISTAR1`
                        },
                                                  subtitle: {
                                                    useHTML: true,   
                                                    text: `<span>ULT REG: ${horaPrincipal}</span><br> 
                                                            <span>Son ${totalPrincipal} Llamadas en DMPE</span><br> 
                                                            <span> Nodo: 
                                                                    <form method="post" action="/administrador/llamadas-troba" class="d-inline">
                                                                        <input type="hidden" name="_token" value="${tok}">
                                                                        <input type="hidden" name="nodo" value="${nodoPrincipal}">  
                                                                        <input type="hidden" name="grafica_llamadas_nodo_tp" value="true">
                                                                        <button type="submit" title="Ver Llamadas por Troba" class="btn btn-link formato-link text-danger"><b>${nodoPrincipal} - ${trobaPrincipal}</b></button>
                                                                    </form>
                                                            </span>`
                                                  },
                                                  xAxis: {
                                                    categories:chartData,
                                                    minRange: 1
                                                  },
                                                  yAxis: {
                                                    title: {
                                                      text: 'Cantidad'
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
               
            
              
              
                              
      })
      .fail(function(jqXHR, textStatus){

        peticiones.redirectTabs($("#listaTrabajoPTab"))
            // console.log("Error:",jqXHR, textStatus)
            // $("#contentGraficoLlamadas").html(jqXHR.responseText)
             
                                           //return false;
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
                                          
             
            
      });

    })


      
})