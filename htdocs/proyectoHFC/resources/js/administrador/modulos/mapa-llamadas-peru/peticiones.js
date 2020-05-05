import errors from  "@/globalResources/errors.js"
const peticiones = {}

peticiones.redirectTabs = function redirectTabs(identificador) {
    $('#tabsMapaCallPeruContent > .tab-pane').removeClass('show');
    $('#tabsMapaCallPeruContent > .tab-pane').removeClass('active');
    identificador.tab('show')  
}

peticiones.resetInterval = function resetInterval(){
    if (INTERVAL_LOAD != null) {
             clearInterval(INTERVAL_LOAD) }
             //console.log("Se limpio el interval y se debe iniciar nuevamente...")
             INTERVAL_LOAD = setInterval(() => { 
 
                 if (ESTA_ACTIVO_REFRESH) { 
                     if ($( ".listaMapaCallPeruTotal" ).hasClass( "active" )) {
                         peticiones.loadMapaCallPeru()
                     } 
                 }
             
             }, 120000); 
     
 }

peticiones.loadMapaCallPeru = function loadMapaCallPeru()
{
    let jefatura = $("#listaJefaturaFiltro").val()
    let ClteTelDni = $("#filtroClteTelDni").val()

    let latitud = $('option:selected',$("#listaJefaturaFiltro")).data("uno");
    let longitud = $('option:selected',$("#listaJefaturaFiltro")).data("dos");
   // console.log("el valor data uno es: ",longitud,"---",latitud)

    let filtros = {
        jefatura,
        ClteTelDni,
        longitud,
        latitud
    }

    peticiones.cargaGraficoCallMapaPeru(filtros)
 
}

peticiones.cargaGraficoCallMapaPeru =  function cargaGraficoCallMapaPeru(filtros)
{

    if (REFRESH_PERMISO) {
        ESTA_ACTIVO_REFRESH = false
    }

    $("#content_mapa_call_peru").html(`<div id="carga_person">
                                            <div class="loader">Loading...</div>
                                        </div>`)

    $.ajax({
        url:`/administrador/mapa-llamadas-peru/lista`,
        method:"get",
        data:filtros,
        dataType: "json", 
    })
    .done(function(data){

        //console.log(data) 
         
         let mapa = JSON.parse(data.response.html)
         $("#content_mapa_call_peru").html(mapa)

        if (REFRESH_PERMISO) {
                ESTA_ACTIVO_REFRESH = true
                peticiones.resetInterval()
        }

    
    })
    .fail(function(jqXHR, textStatus){

        if (REFRESH_PERMISO) {
                ESTA_ACTIVO_REFRESH = true
                peticiones.resetInterval()
        }

        // console.log("Hay un error en update..")
        //console.log( "Error: " ,jqXHR, textStatus);
        //  //console.log( "Request failed: " ,jqXHR.responseJSON.mensaje);
        //$("#content_mapa_call_peru").html(jqXHR.responseText)
        //return false

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

        $("#content_mapa_call_peru").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            ${erroresPeticion}</div>`); 
        return false
 
    }) 

}

peticiones.cargaGraficoHistoricoNiveles = function cargaGraficoHistoricoNiveles(puerto)
{

    $("#content_grafico_niveles_por_puerto").html(`<div id="carga_person">
                                                        <div class="loader">Loading...</div>
                                                    </div>`)

    $.ajax({
        url:`/administrador/mapa-llamadas-peru/graficoNiveles`,
        method:"get",
        data:{puerto},
        dataType: "json", 
    })
    .done(function(data){

         //console.log(data) 
        $("#content_grafico_niveles_por_puerto").html("")

        let colores = data.response.coloresNiveles
 
        let chartDataFuentes = [
            { name:"PowerUp", data:[], color: colores[0].color },
            { name:"PowerDN", data:[], color: colores[1].color },
            { name:"SnrUP", data:[], color: colores[2].color },
            { name:"SnrDN", data:[], color: colores[3].color },
            { name:"Margen SnrUP", data:[], color: colores[4].color }
        ];

        let grafico_data = data.response.data
        let descripcion = "";

        grafico_data.forEach(el => {
            //let date = new Date();
            let date = new Date(`${el.fecha_hora}`);
            // console.log("la fecha es: ",el.fechahora, "=>",date,"-->",date.valueOf())
            //let newFecha = el.hora.split(':')
            //let formatFecha =new Date(date.getFullYear(),date.getMonth(),date.getDay(), newFecha[0], newFecha[1])
            //console.log("la fecha es: ",el.hora, "=> ",formatFecha,"-->",formatFecha.valueOf())

            chartDataFuentes[0].data.push([date.valueOf(),parseFloat(el.powerup_prom)]);
            chartDataFuentes[1].data.push([date.valueOf(),parseFloat(el.powerds_prom)]);
            chartDataFuentes[2].data.push([date.valueOf(),parseFloat(el.snr_avg)]);
            chartDataFuentes[3].data.push([date.valueOf(),parseFloat(el.snr_down)]);
            chartDataFuentes[4].data.push([date.valueOf(),27]);
            descripcion = el.description 
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

        Highcharts.chart("content_grafico_niveles_por_puerto", {
            chart: {
                zoomType: 'x'
                },
            title: {
                text: `Gráfico Histórico de Niveles por Puerto`
            },
            subtitle: {
                text: `<span>Centro de control Movistar1 </span><br><span> Signal Level Last 72 hours</span><br>  <span>   ${descripcion} </span>`
            },
            xAxis: {
                type: 'datetime',
                title: {
                    text: 'Horas'
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
         
         //let mapa = JSON.parse(data.response.html)
         //$("#content_grafico_niveles_por_puerto").html(mapa)
    
    })
    .fail(function(jqXHR, textStatus){
 
        // console.log("Hay un error en update..")
        //console.log( "Error: " ,jqXHR, textStatus);
        //  //console.log( "Request failed: " ,jqXHR.responseJSON.mensaje);
        //$("#content_grafico_niveles_por_puerto").html(jqXHR.responseText)
        // return false

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

        $("#content_grafico_niveles_por_puerto").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            ${erroresPeticion}</div>`); 
        return false
 
    }) 

}



export default peticiones