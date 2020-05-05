 
import errors from  "@/globalResources/errors.js"
import peticiones from './peticiones.js'

$(function(){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("body").on("click","#historico_caidas_trobas", function(){
        //console.log("deberia redirigir al tab..")

        let cmts = $(this).data("uno")
        let inter = $(this).data("dos")
        let nodoTroba = $(this).data("tres")

        let puertoCmts = cmts+inter;

        $("#resultHistoricoCaidasTrobas").html(`<div id="carga_person">
                                                    <div class="loader">Loading...</div>
                                                </div>`);


        peticiones.redirectTabs($('#historicoCaidaTrobasTab')) 
 
        $.ajax({
            url:"/administrador/multiconsulta/historico/caidas/troba",
            method:"post",
            data:{
                puertoCmts,
                nodoTroba
            },
            dataType: "json", 
        })
        .done(function(data){
            //console.log("El resultado es:",data)  
            $("#resultHistoricoCaidasTrobas").html("")

            let colores = data.response.coloresCaidas

            let chartDataHisTroba = [
                { name:"CM Total", data:[], color: colores[0].color },
                { name:"CM Offline", data:[], color: colores[1].color },
                { name:"CM Oper", data:[], color: colores[2].color }
            ];
            let grafico_data = data.response.data

            let cmts = "";
            let interf = "";
            let nodoTroba = data.response.nodoTroba;

            grafico_data.forEach(el => {
                let date = new Date(`${el.fecha_hora}`);
                chartDataHisTroba[0].data.push([date.valueOf(),parseFloat(el.cm_tot)]);
                chartDataHisTroba[1].data.push([date.valueOf(),parseFloat(el.cm_offline)]);
                chartDataHisTroba[2].data.push([date.valueOf(),parseFloat(el.oper)]);
                cmts = el.cmts
                interf = el.interface
            });

            //console.log("el push esta asi: ",chartDataHisTroba)

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

            Highcharts.chart("resultHistoricoCaidasTrobas", {
               
                  title: {
                    text: `Historico Niveles en Troba`
                  },
                  subtitle: {
                    text: `<span>Centro de control Movistar1 </span><br><span> Caidas Masivas de Cable modems</span><br> <span> Ultimos 15 días</span><br/><span> CMTS : ${cmts} | INTERFACE : ${interf} | NODO - TROBA : ${nodoTroba} </span>`
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
            peticiones.redirectTabs($('#multiconsultaTab')) 
            $("#resultHistoricoCaidasTrobas").html("")
             //console.log( "Error: " + jqXHR, textStatus); 
             // $("#resultHistoricoCaidasTrobas").html(jqXHR.responseText)
             // return false;
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