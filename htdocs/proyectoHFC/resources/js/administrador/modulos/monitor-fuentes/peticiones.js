import errors from  "@/globalResources/errors.js"
import columnas from  "@/globalResources/tablas/columnas.js"
 

const peticiones = {}

peticiones.redirectTabs = function redirectTabs(identificador) {
    $('#tabsMonitorFuentesContent > .tab-pane').removeClass('show');
    $('#tabsMonitorFuentesContent > .tab-pane').removeClass('active');
    identificador.tab('show')  
}

peticiones.loadMonitorFuentes = function loadMonitorFuentes(){
    let nodo = $("#listaNodosMFFilter").val()
    let tipoBateria = $("#listaTipoBateriaMFFilter").val()
    let estadoDeGestion = $("#listaEstadosMFilter").val()

    let columnasMonitorFuentes = peticiones.columnasFuentes()
    
    let filtros = {
        nodo,
        tipoBateria,
        estadoDeGestion
    }

    peticiones.listaMonitorFuentes(columnasMonitorFuentes,filtros)

}

peticiones.resetInterval = function resetInterval(){
   if (INTERVAL_LOAD != null) {
            clearInterval(INTERVAL_LOAD) }
            //console.log("Se limpio el interval y se debe iniciar nuevamente...")
            INTERVAL_LOAD = setInterval(() => { 

                if (ESTA_ACTIVO_REFRESH) { 
                    if ($( ".listaMonitorFuentesTotal" ).hasClass( "active" )) {
                        peticiones.loadMonitorFuentes()
                    } 
                }
            
            }, 30000); 
    
}

peticiones.columnasFuentes = function columnasFuentes()
{
    let columnasFuentes = [ 
        {data: 'item'},
        {data: 'estadoBateria'},//Estado bateria
    ]
    if (DIAGNOSTICOM_PERMISO) {
        columnasFuentes.push({data: 'DM'}) //DM
    }
    columnasFuentes.push(
        {data: 'NodoTroba'},//Nodo-troba
        {data: 'cancli'},//Cli
        {data: 'offline'},//Off
        {data: 'direccion'},//Direccion
        {data: 'macaddress'},//macaddress
        {data: 'ipaddress'},//IPaddress
        {data: 'InputVoltagefinal'},//Volt-Ent
        {data: 'OutputVoltagefinal'},//Volt_Sal
        {data: 'OutputCurrentfinal'},//Corr_Sal
        {data: 'TotalStringVoltagefinal'},//Bateria
        {data: 'fechahora'},//FechaHora
        {data: 'existeBateria'},//Bat?
        {data: 'estado_gestion_col'},//Gestion
        {data: 'resultadosnmp'}//SNMP 
    )
    if (EDITAR_FUENTE_PERMISO) {
        columnasFuentes.push( 
            {data: null, render: function(data,type,row){
                return `<a href="javascript:void(0)" class="btn btn-sm editFuentes" data-uno="${row.macTransform}"><i class="icofont-ui-edit"></i></a>`
            }}//Edicion
        )
    }
    
    return columnasFuentes
}

peticiones.obtenerColoresSegunVoltajes = function  obtenerColoresSegunVoltajes(color,coloresVoltajes)
{
    let backFinal = ""
    let ColorFinal = ""

    switch (color) {
        case "GREEN":
                backFinal = coloresVoltajes.colores[0].background
                ColorFinal = coloresVoltajes.colores[0].color
            break;
        case "RED":
                backFinal = coloresVoltajes.colores[1].background
                ColorFinal = coloresVoltajes.colores[1].color
            break;
        case "ORANGE":
                backFinal = coloresVoltajes.colores[2].background
                ColorFinal = coloresVoltajes.colores[2].color
            break;
    
        default: 
                backFinal =""
                ColorFinal = ""
            break;
    }

    return {
        "color":ColorFinal,
        "background":backFinal
    }
}

peticiones.listaMonitorFuentes = function listaMonitorFuentes(COLUMNAS_MONITOR_FUENTES,filtros)
{

    if (REFRESH_PERMISO) {
        ESTA_ACTIVO_REFRESH = false
    }

    $("#resultMFuentesList").DataTable({
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
            "buttons": [
                {
                    text: 'DESCARGAS',
                    className: 'btn btn-sm btn-success shadow-sm',
                    titleAttr: 'DESCARGAS MONITOR FUENTES',
                    action: function ( e, dt, node, config ) {
                        //alert( 'Button Opciones HFC' );
                        //console.log("opciones:", e, dt, node, config)
                        $("#resultOpcionesMonitoreoFuentes").html("")
                        $("#descargasMonitorFuentesModal").modal("show");
                    }
                }, 
                {
                    text: 'FILTROS',
                    className: 'btn btn-sm btn-info shadow-sm',
                    titleAttr: 'FILTROS MONITOR FUENTES',
                    action: function ( e, dt, node, config ) {
                        $("#filtroContentMFuentes").slideToggle()
                    }
                }
            ],
            "ajax": {  
                    'url':`/administrador/monitor-fuentes/lista`,
                    "type": "GET", 
                    "data": function ( d ) {

                            d.nodo = filtros.nodo;
                            d.tipoBateria = filtros.tipoBateria; 
                            d.estadoDeGestion = filtros.estadoDeGestion; 
                       
                    },
                    'dataSrc': function(json){
                            //console.log("Termino la carga asi tenga error.. :",json)
                    
                                //return json
                                    let result = json.data
                                    let coloresVoltajes = json.coloresVoltajes
                                    let segunEstados = json.segunEstados
                                //console.log("la data es: ",json)
                                //console.log("El result es: ",result)
                                let item = 0

                                for (let index = 0; index < result.length; index++) {
                                    item ++
                                    
                                    result[index].item = item 
                                    let bat = "S"
                                    let  imagen="bat_off.png"

                                    if(result[index].tienebateria=='N'){ 
                                        bat='N' 
                                        // imagen="bat_off.png"
                                    }  
 
                                    let mac = result[index].macaddress.replace(/[\.]+/g,'');

                                    result[index].macTransform = mac

                                    result[index].existeBateria = bat
							
                                    if (result[index].TotalStringVoltagefinalcolor=='GREEN' && bat =='S' && result[index].resultadosnmp=='SNMPOK') imagen="bat_verde.png"  
                                    if (result[index].TotalStringVoltagefinalcolor=='RED' && bat =='S' && result[index].resultadosnmp=='SNMPOK') imagen="bat_roja.png" 
                                    if (result[index].TotalStringVoltagefinalcolor=='ORANGE' &&  bat =='S' && result[index].resultadosnmp=='SNMPOK') imagen="bat_ambar.png" 

                                    result[index].InputVoltagefinalcolorF = peticiones.obtenerColoresSegunVoltajes(result[index].InputVoltagefinalcolor,coloresVoltajes)
                                    result[index].OutputVoltagefinalcolorF = peticiones.obtenerColoresSegunVoltajes(result[index].OutputVoltagefinalcolor,coloresVoltajes)
                                    result[index].OutputCurrentfinalcolorF = peticiones.obtenerColoresSegunVoltajes(result[index].OutputCurrentfinalcolor,coloresVoltajes)
                                    result[index].TotalStringVoltagefinalcolorF = peticiones.obtenerColoresSegunVoltajes(result[index].TotalStringVoltagefinalcolor,coloresVoltajes)

                                    let estadoBateria =""
                                    
                                    if (GRAFICOFUENTES_PERMISO) {
                                       
                                        estadoBateria += ` <a href="javascript:void(0)" data-uno="${result[index].macaddress}" class="verGraficoFuentePoder">
                                                                <img src="/images/icons/${imagen}" style="width:10px;">
                                                            </a> `
                                    }
                                    if (DESCARGAR_ALERTAS_DOWN_PERMISO) {
                                        estadoBateria += `  <button alt="Seguimiento" class="p-0 card-widget alertasDownDescargas"
                                                                data-uno="${result[index].nodo}" data-dos="${result[index].troba}" data-tres="1" 
                                                                data-cuatro="1"  data-cinco="0">
                                                                    <img src='/images/icons/ico_down.png' alt='Seguir'>
                                                            </button> `
                                    }
                                   
                                    if(result[index].marca=='MULTILINK' && DETALLE_MULTILINK_PERMISO){
                                        estadoBateria += ` <a href="javascript:void(0)" data-uno="${result[index].ipaddress}" data-uno="8080" class="verMultilinkDetalle">${result[index].marca}</a>`
                                    }else{
                                            estadoBateria+= `${result[index].marca}` 
                                    }

                                    result[index].estadoBateria = estadoBateria

                                    if (DIAGNOSTICOM_PERMISO) {
                                        if (result[index].nodo != null && result[index].troba != null) {
                                            result[index].DM = `<div class="text-center">
                                                        <a href="javascript:void(0)" data-uno="${result[index].nodo}" data-dos="${result[index].troba}"
                                                                class="shadow-sm font-weight-bold verDiagnosticoMasivo" alt="Ver Diagóstico Masivo" title="Ver Diagóstico Masivo">
                                                        DM
                                                    </div>`
                                        }else{
                                            result[index].DM = ``
                                        }
                                       
                                    }
 
                                    let  NodoTroba =""
                                    
                                    if (MAPAFUENTES_PERMISO) {
                                        NodoTroba += ` 
                                                        <a href="javascript:void(0)" data-uno="${result[index].nodo}" data-dos="${result[index].troba}" data-tres="1" 
                                                                    data-cuatro="1"  data-cinco="0" class="verMapaFuentes" alt='Ver mapa Fuentes'>
                                                                    <i class="icofont-google-map icofont-2x"></i>
                                                        </a>
                                                        
                                                    `
                                    }
                                    
                                        NodoTroba += `  ${result[index].nodo} `

                                    if (DESCARGAR_HISTORICO_DOWN_PERMISO) {
                                        NodoTroba += `<button   data-uno="${result[index].macaddress}"class="p-0 card-widget descargarHistoricoFuentesDown" alt='Descargar Historico Fuentes'>
                                                         ${result[index].troba}
                                                        </button>`
                                    }else{
                                        NodoTroba += ` ${result[index].troba}`
                                    }
                                        
                                    result[index].NodoTroba = NodoTroba
 
                                    if (result[index].usuario != null) {
                                            let parametrosGestion = { 
                                                    'estadoText':result[index].estado_ges,
                                                    'observacionesText':result[index].observaciones,
                                                    'usuarioText':result[index].usuario,
                                                    'fechahoraText':result[index].fechahora_ges,
                                                    'estadoColor':segunEstados.colores[0].tituloColorEstadoGestion,
                                                    'observacionesColor':segunEstados.colores[0].contenidoColorEstadoGestion,
                                                    'usuarioColor':segunEstados.colores[0].usuarioColorEstadoGestion,
                                                    'fechahoraColor':segunEstados.colores[0].fechaColorEstadoGestion                                            } 
                                            result[index].estado_gestion_col = columnas.armandoEstadoGestionHtml(parametrosGestion)

                                            
                                    }else{
                                            result[index].estado_gestion_col = ``
                                    }

 
                                }
 
                                // console.log("La data procesada final... es: ",result)

                                 return result  
                            
                        
                    },
                    'error': function(jqXHR, textStatus, errorThrown)
                    {  

                            if (REFRESH_PERMISO) {
                                    ESTA_ACTIVO_REFRESH = true
                                    peticiones.resetInterval()
                            }

                            console.log( "Error: " ,jqXHR, textStatus, errorThrown); 
                        
                            //alert("Se generó un error con la petición, Se intentará traer nuevamente.")
                          

                           // $("#body-errors-modal").html(jqXHR.responseText)
                           // $('#errorsModal').modal('show')
                           // return false

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
            "columns": COLUMNAS_MONITOR_FUENTES,
            'columnDefs': [ 
                    {
                        'targets': '_all',
                        'createdCell':  function (td, cellData, rowData, row, col) { 
                            
                           // $(td).css({"background":`${rowData.background}`,"color":`${rowData.colorText}`}); 
                            ///$(td).addClass("text-center")
                            //console.log("los cells: ",td, cellData, rowData, row, col)

                            let count = 0

                            if (DIAGNOSTICOM_PERMISO)   count ++ 

                            if (col == count+8) { //InputVoltagefinalcolorF
                                    $(td).css({"background":`${rowData.InputVoltagefinalcolorF.background}`,"color":`${rowData.InputVoltagefinalcolorF.color}`});      
                            }
                            if (col == count+9) { //OutputVoltagefinalcolorF
                                    $(td).css({"background":`${rowData.OutputVoltagefinalcolorF.background}`,"color":`${rowData.OutputVoltagefinalcolorF.color}`});      
                            }
                            if (col == count+10) { //OutputCurrentfinalcolorF
                                    $(td).css({"background":`${rowData.OutputCurrentfinalcolorF.background}`,"color":`${rowData.OutputCurrentfinalcolorF.color}`});      
                            }
                            if (col == count+11) { //TotalStringVoltagefinalcolorF
                                    $(td).css({"background":`${rowData.TotalStringVoltagefinalcolorF.background}`,"color":`${rowData.TotalStringVoltagefinalcolorF.color}`});      
                            }

                        
                        }
                    } ,
                    {
                        
                        "targets": '_all',
                        "orderable" : false,
                        "searchable": false,
                            
                    } 
            ] ,
            "initComplete": function(){
                // console.log("Termino la carga completa")
                if (REFRESH_PERMISO) {
                        ESTA_ACTIVO_REFRESH = true
                        peticiones.resetInterval()
                }
                    
            },
            "pageLength": 100,
            "language": {
                        "info": "_TOTAL_ registros",
                        "search": "Buscar",
                        "paginate": {
                                "next": "Siguiente",
                                "previous": "Anterior",
                        },
                        "lengthMenu": 'Mostrar <select >'+
                        '<option value="50">50</option>'+
                        '<option value="100">100</option>'+
                        '<option value="300">300</option>'+
                        '<option value="500">500</option>'+
                        '<option value="-1">Todos</option>'+
                        '</select> registros',
                        "loadingRecords": "<div id='carga_person'> <div class='loader'>Cargando...</div></div>",
                        "processing": "<div id='carga_person'> <div class='loader'>Procesando...</div></div>",
                        "emptyTable": "No hay datos disponibles",
                        "zeroRecords": "No hay coincidencias", 
                        "infoEmpty": "",
                        "infoFiltered": ""
            }
    });


        $("#resultMFuentesList").parent().addClass("table-responsive tableFixHead") 
        // $("#filtroContentHFC").removeClass("d-none")

        let tablaHead = $('.tableFixHead').find('thead th')
        $('.tableFixHead').on('scroll', function() {
        // console.log("ejecutando"+this.scrollTop); 
        tablaHead.css('transform', 'translateY('+ this.scrollTop +'px)'); 
        }); 

}
 

export default peticiones