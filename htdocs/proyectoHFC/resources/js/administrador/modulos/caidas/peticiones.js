import errors from  "@/globalResources/errors.js"
import columnas from  "@/globalResources/tablas/columnas.js"

const peticiones = {}

peticiones.redirectTabs = function redirectTabs(identificador) {
    $('#tabsCaidasContent > .tab-pane').removeClass('show');
    $('#tabsCaidasContent > .tab-pane').removeClass('active');
    identificador.tab('show')  
}

peticiones.resetInterval = function resetInterval(){
        if (INTERVAL_LOAD != null) {
                clearInterval(INTERVAL_LOAD)
                //console.log("Se limpio el interval y se debe iniciar nuevamente...")
                INTERVAL_LOAD = setInterval(() => { 

                        if (ESTA_ACTIVO_REFRESH) { 
                            if ($( ".listaCaidas" ).hasClass( "active" )) {
                                peticiones.cargandoPeticionPrincipal()
                            } 
                        }
                
                }, 30000); 
        }
}

peticiones.armandoColumnasUno = function armandoColumnasUno()
{
        let columnasContent =  [ {data: 'id'}  ]

        if (DIAGNOSTICOM_PERMISO) {
                columnasContent.push({data: 'DM'})
        }
        columnasContent.push({data: 'jefatura'})
        if (VER_CRITICOS_PERMISO) {
                columnasContent.push({data: 'critica'})
        }
        columnasContent.push({data:'nodoTroba'})
        if (VER_TRABPROGRAMADOS_PERMISO) {
                columnasContent.push({data:'trabajoP'})
        }
        columnasContent.push( 
                {data:'averias'},
                {data:'cancli'},
                {data:'offline'},
                {data:'remedy'},
                {data:'codmasivaCol'},
                {data:'consultasM1'},
                {data:'fecha_hora'},
                {data:'fecha_fin_col'},
                {data:'tiempoCol'},
                {data:'estado_gestion_col'}
        )
        if (GESTION_PERMISO) {
                columnasContent.push({data: 'gestion_col'})
        }
        columnasContent.push({data: 'fuenteCol'})

        return columnasContent
}
peticiones.armandoColumnasDos = function armandoColumnasDos()
{
        let columnasContent =  [ {data: 'id'} ]
        if (DIAGNOSTICOM_PERMISO) {
                columnasContent.push({data: 'DM'})
        }
        columnasContent.push({data: 'jefatura'}) 

        if (VER_CRITICOS_PERMISO) {
                columnasContent.push({data: 'critica'})
        }

        columnasContent.push({data:'nodoTroba'},{data:'amplificador'})

        if (VER_TRABPROGRAMADOS_PERMISO) {
                columnasContent.push({data:'trabajoP'})
        }

        columnasContent.push( 
                {data:'averiaCol'},
                {data:'cancli'},
                {data:'offline'},
                {data:'remedy'},
                {data:'codmasiva'},
                {data:'consultasLlamadasCol'},
                {data:'fecha_hora'},
                {data:'fecha_fin_col'},
                {data:'tiempoCol'},
                {data:'estado_gestion_col'}
        )
        if (GESTION_PERMISO) {
                columnasContent.push({data: 'gestion_col'})
        }
        columnasContent.push({data: 'fuenteCol'})
         
        return columnasContent
}

function procesandoResultadoLista(result,tipoCaida)
{
        for (let i = 0; i < result.length ; i++) {

                if (DIAGNOSTICOM_PERMISO) {
                        if (result[i].nodo !== '' || result[i].troba !== '') {
                                result[i].DM = `<div class="text-center">
                                                        <a href="javascript:void(0)" data-uno="${result[i].nodo}" data-dos="${result[i].troba}"
                                                                class="shadow-sm font-weight-bold verDiagnosticoMasivo" alt="Ver Diagóstico Masivo" title="Ver Diagóstico Masivo">
                                                        DM
                                        </div>`
                        }
                                        /*if (tipoCaida == "caidas_masivas") {
                                                result[i].DM += ` </a> <br/>${result[i].top}`
                                        } 
                                        result[i].DM +=``*/
                }

               

                result[i].codmasivaCol = result[i].codmasiva == null ? "" :result[i].codmasiva

                if (result[i].consultasM1 > 0) {
                        result[i].consultasM1  =  `<a href="javascript:void(0)" data-uno="${result[i].nodo}" data-dos="${result[i].troba}" data-tres="${result[i].fecha_hora}"
                                                        class="shadow-sm font-weight-bold descargarConsultasM" alt="descargar consultas M1" title="descargar consultas M1">
                                                        ${result[i].consultasM}
                                                </a>`
                }else{
                        result[i].consultasM1 = ""
                }
                
                let estadoGestionTemp = " " 
                if (result[i].alertasGestion.length > 0) {
                        let parametrosGestion = { 
                                'estadoText':result[i].alertasGestion[0].estado,
                                'observacionesText':result[i].alertasGestion[0].observaciones,
                                'usuarioText':result[i].alertasGestion[0].usuario,
                                'fechahoraText':result[i].alertasGestion[0].fechahora,
                                'estadoColor':result[i].tituloColorEstadoGestion,
                                'observacionesColor':result[i].contenidoColorEstadoGestion,
                                'usuarioColor':result[i].usuarioColorEstadoGestion,
                                'fechahoraColor':result[i].fechaColorEstadoGestion
                        
                        } 
                        result[i].estado_gestion_col = columnas.armandoEstadoGestionHtml(parametrosGestion)

                        estadoGestionTemp = result[i].alertasGestion[0].estado
                }else{
                        result[i].estado_gestion_col = ``
                }

                if (GESTION_PERMISO) {
                       
                        result[i].gestion_col = ` <div class="text-center d-block"> <a href="javascript:void(0)" class="btn btn-sm btn-light p-0 gestionarAveria" data-uno="${result[i].nodo}" data-dos="${result[i].troba}" data-tres="1"
                        data-cuatro="${result[i].fecha_hora}" data-cinco="${result[i].codmasiva}" data-seis="${estadoGestionTemp}" style="color:${result[i].gestionRegistroColor};" alt="Gestionar Avería" title="Gestionar Avería">
                                        <i class="icofont-list icofont-2x"></i>    
                                </a>`
                        if (tipoCaida != "caidas_torre" && result[i].codmasiva != null) {
                                if (estadoGestionTemp.trim() == "Enviada:ATENTO para liquidar" || estadoGestionTemp.trim() == "Enviada:COT para liquidar") {
                                        
                                        result[i].gestion_col += `<a href="javascript:void(0)" data-uno="${result[i].codmasiva}" class="btn btn-sm verDetalleGestion" style="color:${result[i].gestionDetalleColor};" alt="Ver detalle masiva" title="Ver detalle masiva">
                                                        <i class="icofont-list icofont-2x"></i>   
                                                </a>`
                                        
                                }
                        }
                        if (estadoGestionTemp.trim() == "Asignado: Energia") {
                                result[i].gestion_col += `<a href="javascript:void(0)" data-uno="${result[i].nodo}" data-dos="${result[i].troba}" class="btn btn-sm descargaEnergia" style="color:${result[i].otrosIconsColor};" alt="Descarga Energia" title="Descarga Energia">
                                                        <i class="icofont-file-excel icofont-2x"></i>
                                                        </a>`
                        }
                        result[i].gestion_col += `</div>`
                }

               

                result[i].fuenteCol = ""

                if (result[i].fuente == "SI" && result[i].fuenteEstado=="OF"){
                        result[i].fuenteCol =  ` <img src='/images/icons/fuentecaida.png' height='25' width='25'><br> ${result[i].respaldo == null ? "" : result[i].respaldo }`
                }
                if (result[i].fuente == "SI" && result[i].fuenteEstado=='ON'){
                        result[i].fuenteCol =  ` <img src='/images/icons/fuenteon.png' height='25' width='25'><br> ${result[i].respaldo == null ? "" : result[i].respaldo }` 
                }
                if ((result[i].fuente == "SI" && result[i].fuenteEstado=='PR')){
                        result[i].fuenteCol =  ` <img src='/images/icons/fuenteambar.png' height='25' width='25'><br> ${result[i].respaldo == null ? "" : result[i].respaldo }`
                }

                if (result[i].fuente =='NO' || result[i].fuenteEstado=='')
                {
                        result[i].fuenteCol = ""
                }


                if (result[i].estado != "LEVANTO") {

                        if (result[i].premium =='PREMIUM'){
                                result[i].id = `<img src='/images/icons/premium.png' width='17' height='17'/>${result[i].id }`
                        }
                        if (VER_CRITICOS_PERMISO) {
                                if (result[i].tc == 'TC' || parseInt(result[i].ncrit) > 0) {
                                        result[i].critica = ` <a href="javascript:void(0)" data-uno="${result[i].nodo}" data-dos="${result[i].troba}"
                                                                        class="shadow-sm font-weight-bold verListaCriticos" alt="ver lista criticos" title="ver lista criticos">
                                                                        <img src='/images/icons/critica.png' width='17' height='17' alt=''/>
                                                                </a>`
                                }else{
                                        result[i].critica = ``
                                }
                        }
                        result[i].nodoTroba = ``
                        if (MAPA_PERMISO) {
                                result[i].nodoTroba += `<a href="javascript:void(0)" data-uno="${result[i].nodo}" data-dos="${result[i].troba}"   class="btn btn-sm  verMapa" style="color:${result[i].mapaColor};" alt="Ver Mapa" title="Ver Mapa">
                                                                <i class="icofont-google-map icofont-2x"></i>
                                                        </a><br/>`
                        }

                        result[i].nodoTroba += `${result[i].nodo}`
                        
                        if (result[i].digi == "DIGITALIZACION NUEVA") {
                                result[i].nodoTroba += `<img src='/images/icons/digitalizado.png' width='17' height='17' alt="${result[i].fecha_hora}"/>
                                                        <a href="javascript:void(0)" data-uno="${result[i].nodo}" data-dos="${result[i].troba}"
                                                                class="shadow-sm font-weight-bold verAlertasDown" alt="ver alertas down" title="ver alertas down">
                                                                ${result[i].troba}
                                                        </a>
                                                        `
                        }else{
                                result[i].nodoTroba += `<a href="javascript:void(0)" data-uno="${result[i].nodo}" data-dos="${result[i].troba}"
                                                                class="shadow-sm font-weight-bold verAlertasDown" alt="ver alertas down" title="ver alertas down">
                                                                ${result[i].troba} ${result[i].migalt}
                                                        </a>`
                        }

                        if (VER_TRABPROGRAMADOS_PERMISO) {
                                if (result[i].estadoTrabajoProgramado == "CERRADO") {
                                        result[i].trabajoP = `<a href="javascript:void(0)" data-uno="${result[i].nodo}" data-dos="${result[i].troba}"
                                                                        class="shadow-sm font-weight-bold verTrabajoProgramado" alt="ver trabajo programado" title="ver trabajo programado">
                                                                        <img src='/images/icons/trabajo_programado2.png' alt='Seguir' width=15 height=15 border=0>
                                                                </a>`
                                }else{
                                        if (result[i].estadoTrabajoProgramado == "PENDIENTE" || result[i].estadoTrabajoProgramado == "ENPROCESO") {
                                                result[i].trabajoP = `<a href="javascript:void(0)" data-uno="${result[i].nodo}" data-dos="${result[i].troba}"
                                                                                class="shadow-sm font-weight-bold verTrabajoProgramado" alt="ver trabajo programado" title="ver trabajo programado">
                                                                                <img src='/images/icons/trabajo_programado2.png' alt='Seguir' width=15 height=15 border=0>
                                                                        </a>`
                                        }else{
                                                result[i].trabajoP = `` 
                                        }
                                }
                        }
                        
                        if (tipoCaida == "caidas_masivas") {
                                result[i].averias = `<a href="javascript:void(0)" data-uno="${result[i].nodo}" data-dos="${result[i].troba}" data-tres="${result[i].hoy}"
                                                        class="shadow-sm font-weight-bold verAveriasDown" alt="ver averias down" title="ver averias down">
                                                        ${ result[i].averiasc == 0 ?  "" : result[i].averiasc}
                                                </a> / 
                                                <a href="javascript:void(0)" data-uno="${result[i].nodo}" data-dos="${result[i].troba}" data-tres="${result[i].hoy}"
                                                        class="shadow-sm font-weight-bold verConsultasCtv" alt="ver consultas CTV" title="ver consultas CTV">
                                                        ${result[i].calldmpe == null ? "" : result[i].calldmpe}
                                                </a>` 
                        }else if(tipoCaida == "caidas_noc" || tipoCaida == "caidas_torre" ){
                                result[i].averias = `<a href="javascript:void(0)" data-uno="${result[i].nodo}" data-dos="${result[i].troba}" data-tres="${result[i].hoy}"
                                                                class="shadow-sm font-weight-bold verAveriasDown" alt="ver averias down" title="ver averias down">
                                                                ${ result[i].averiasc == 0 ?  "" : result[i].averiasc}
                                                        </a> `
                        }

                        if (parseInt(result[i].averias) > 0) {
                                result[i].masiva = result[i].averias
                        }else{
                                result[i].masiva = ``
                        }
 
                        result[i].fecha_fin_col =  result[i].crit
                        

                }else{
                        if (VER_CRITICOS_PERMISO) {
                                if (result[i].tc == 'TC') {
                                        result[i].critica = ` <a href="javascript:void(0)" data-uno="${result[i].nodo}" data-dos="${result[i].troba}"
                                                                        class="shadow-sm font-weight-bold verListaCriticos" alt="ver lista criticos" title="ver lista criticos">
                                                                        <img src='/images/icons/critica.png' width='17' height='17' alt=''/>
                                                                </a>`
                                }else{
                                        result[i].critica = ``
                                }
                        } 
                        result[i].nodoTroba = `${result[i].nodo}`
                        if (result[i].digi == "DIGITALIZACION NUEVA") {
                                result[i].nodoTroba += `<img src='/images/icons/digitalizado.png' width='17' height='17' alt="${result[i].fecha_digi}"/>
                                                        <a href="javascript:void(0)" data-uno="${result[i].nodo}" data-dos="${result[i].troba}"
                                                                class="shadow-sm font-weight-bold verAlertasDown" alt="ver alertas down" title="ver alertas down">
                                                                ${result[i].troba}
                                                        </a>
                                                        `
                        }else{
                                result[i].nodoTroba += ` ${result[i].troba} `
                        }

                        if (VER_TRABPROGRAMADOS_PERMISO) {
                                if (result[i].estadoTrabajoProgramado == "CERRADO") {
                                        result[i].trabajoP = `<a href="javascript:void(0)" data-uno="${result[i].nodo}" data-dos="${result[i].troba}"
                                                                        class="shadow-sm font-weight-bold verTrabajoProgramado" alt="ver trabajo programado" title="ver trabajo programado">
                                                                        <img src='/images/icons/trabajo_programado2.png' alt='Seguir' width=15 height=15 border=0>
                                                                </a>`
                                }else{
                                        if (result[i].estadoTrabajoProgramado == "PENDIENTE" || result[i].estadoTrabajoProgramado == "ENPROCESO") {
                                                result[i].trabajoP = `<a href="javascript:void(0)" data-uno="${result[i].nodo}" data-dos="${result[i].troba}"
                                                                                class="shadow-sm font-weight-bold verTrabajoProgramado" alt="ver trabajo programado" title="ver trabajo programado">
                                                                                <img src='/images/icons/trabajo_programado2.png' alt='Seguir' width=15 height=15 border=0>
                                                                        </a>`
                                        }else{
                                                result[i].trabajoP = `` 
                                        }
                                }
                        }
 
                        result[i].averias = `<a href="javascript:void(0)" data-uno="${result[i].nodo}" data-dos="${result[i].troba}" data-tres="${result[i].hoy}"
                                                class="shadow-sm font-weight-bold verAveriasDown" alt="ver averias down" title="ver averias down">
                                                ${ result[i].averiasc == 0 ?  "" : result[i].averiasc}
                                        </a> `

                        result[i].masiva = result[i].averias

                        result[i].fecha_fin_col =  result[i].fecha_fin

                }
 
        }

        return result
}


peticiones.getDataRequiredFilterCaidas = function getDataRequiredFilterCaidas(tipo)
{
        let data = {}
        let nodo = ""
        let nodoDashboard = $("#filtroCuadroMando").val()

        if ($("#filtroCuadroMando").length) {
            if(nodoDashboard.length==2) {
                nodo=nodoDashboard
            }
        }

        //data.tipoCaidas = $("#display_filter_special").val()
        data.parametros = {}
        //data.parametros.nodo = "" 
        data.parametros.nodo = nodo
        data.parametros.tipoCaidas = tipo
  
        if (tipo == "caidas_masivas") {
          data.redirect = $('#caidasMasivasTab')
          data.tabla = $("#resultCaidasMasivas") 
          data.parametros.jefatura = $("#listajefaturaCaidasMASIVA").val() 
          data.parametros.estado = $("#listaEstadoCaidasMASIVA").val() 
          data.columnasCaidas = peticiones.armandoColumnasUno()
        }else if(tipo == "caidas_noc"){
          data.redirect = $('#caidasNocTab')
          data.tabla = $("#resultCaidasNoc") 
          data.parametros.jefatura = $("#listajefaturaCaidasNOC").val() 
          data.parametros.estado = $("#listaEstadoCaidasNOC").val() 
          data.columnasCaidas = peticiones.armandoColumnasUno()
        }else if(tipo == "caidas_torre"){
          data.redirect = $('#caidasTorreHfcTab')
          data.tabla = $("#resultCaidasTorreHfc") 
          data.parametros.jefatura = $("#listajefaturaCaidasHFC").val() 
          data.parametros.estado = $("#listaEstadoCaidasHFC").val() 
          data.columnasCaidas = peticiones.armandoColumnasUno()
        }else if(tipo == "caidas_amplificador"){
          data.redirect = $('#caidasAmplificadorTab')
          data.tabla = $("#resultCaidasAmplificador") 
          data.parametros.jefatura = $("#listajefaturaCaidasAMPLIFICADOR").val() 
          data.parametros.troba = $("#listaTrobasCaidasAMPLIFICADOR").val()
          data.columnasCaidas = peticiones.armandoColumnasDos()
        }
  
        return data
}

 
peticiones.cargaCaidasLista = function cargaCaidasLista(COLUMNS_CAIDAS,BUTTONS_CAIDAS,
    parametersDataAverias,tabla){

     
 
        $("#display_filter_special").prop("disabled", true); 
        if (REFRESH_PERMISO) {
                ESTA_ACTIVO_REFRESH = false
        }
       
        
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
                        'url':'/administrador/caidas/lista',
                        "type": "GET", 
                        "data": function ( d ) {

                                d.filtroJefatura = parametersDataAverias.jefatura;
                                d.filtroEstado = parametersDataAverias.estado;
                                d.tipoCaida = parametersDataAverias.tipoCaidas;
                                d.nodo = parametersDataAverias.nodo;
                                /*d.num_puer = des_puer;*/
                        },
                        'dataSrc': function(json){
                                //console.log("Termino la carga asi tenga error.. :",json)
                         
                                       //return json
                                        let result = json.data
                                       //  console.log("El result es: ",result)

                                        let dataProcesada = procesandoResultadoLista(result,parametersDataAverias.tipoCaidas)

                                        
                                        //console.log("La data procesada final... es: ",result)

                                        return dataProcesada  
                                  
                              
                        },
                        'error': function(jqXHR, textStatus, errorThrown)
                        {  

                                  // console.log( "Error: " ,jqXHR, textStatus, errorThrown); 
                                $("#display_filter_special").prop("disabled", false);
                                if (REFRESH_PERMISO) {
                                        ESTA_ACTIVO_REFRESH = true
                                        peticiones.resetInterval()
                                }
                                
                                  //alert("Se generó un error con la petición, Se intentará traer nuevamente.")
                                  //$("#body-errors-modal").html(jqXHR.responseText)
  
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
                                $(td).addClass("text-center")
                                 //console.log("los cells: ",td, cellData, rowData, row, col)
                                 let count = 0
                                 if (DIAGNOSTICOM_PERMISO)   count ++ 
                                 if (VER_CRITICOS_PERMISO)   count ++ 

                                 if (col == count+6) {
                                        if (rowData.estado != "LEVANTO") {
                                                if (parseFloat(rowData.divisionOffline) > 0.3) { 
                                                        $(td).css({"background":`${rowData.letra}`,"color":`${rowData.fondo}`});  
                                                } 
                                        }
                                        
                                 }

                           }
                        },
                        {
                             
                            "targets": '_all',
                           // "orderable" : false,
                            "searchable": false,
                                
                        } 
                ] ,
                "initComplete": function(){
                       // console.log("Termino la carga completa")
                        $("#display_filter_special").prop("disabled", false);
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
                            '<option value="100">100</option>'+
                            '<option value="300">300</option>'+
                            '<option value="500">500</option>'+
                            '<option value="700">700</option>'+
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


            tabla.parent().addClass("table-responsive tableFixHead") 
            // $("#filtroContentHFC").removeClass("d-none")

            let tablaHead = $('.tableFixHead').find('thead th')
            $('.tableFixHead').on('scroll', function() {
            // console.log("ejecutando"+this.scrollTop); 
            tablaHead.css('transform', 'translateY('+ this.scrollTop +'px)'); 
            }); 

}

peticiones.cargaCaidasAmplificadorLista = function cargaCaidasAmplificadorLista(COLUMNS_CAIDAS,BUTTONS_CAIDAS,
    parametersDataAverias,tabla){
 
        $("#display_filter_special").prop("disabled", true); 
        if (REFRESH_PERMISO) {
                ESTA_ACTIVO_REFRESH = false
        }
       
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
                        'url':'/administrador/caidas/lista',
                        "type": "GET", 
                        "data": function ( d ) {

                                d.filtroJefatura = parametersDataAverias.jefatura;
                                //d.filtroEstado = parametersDataAverias.estado;
                                d.tipoCaida = parametersDataAverias.tipoCaidas;
                                d.troba = parametersDataAverias.troba;
                                d.nodo = parametersDataAverias.nodo;
                                /*d.num_puer = des_puer;*/
                        },
                        'dataSrc': function(json){
                                //console.log("Termino la carga asi tenga error.. :",json)
                         
                                       //return json
                                        let result = json.data
                                       //  console.log("El result es: ",result)

                                       for (let i = 0; i < result.length ; i++) {

                                        if (DIAGNOSTICOM_PERMISO) {
                                                if (result[i].nodo !== '' || result[i].troba !== '') {
                                                        result[i].DM = `<div class="text-center">
                                                                        <a href="javascript:void(0)" data-uno="${result[i].nodo}" data-dos="${result[i].troba}"
                                                                                class="shadow-sm font-weight-bold verDiagnosticoMasivo" alt="Ver Diagóstico Masivo" title="Ver Diagóstico Masivo">
                                                                        DM
                                                                </div>`
                                                }
                                        }

                                               

                                                if (VER_TRABPROGRAMADOS_PERMISO) {
                                                        if (result[i].estadoTrabajoProgramado == "CERRADO") {
                                                                result[i].trabajoP = `<a href="javascript:void(0)" data-uno="${result[i].nodo}" data-dos="${result[i].troba}"
                                                                                                class="shadow-sm font-weight-bold verTrabajoProgramado" alt="ver trabajo programado" title="ver trabajo programado">
                                                                                                <img src='/images/icons/trabajo_programado2.png' alt='Seguir' width=15 height=15 border=0>
                                                                                        </a>`
                                                        }else{
                                                                if (result[i].estadoTrabajoProgramado == "PENDIENTE" || result[i].estadoTrabajoProgramado == "ENPROCESO") {
                                                                        result[i].trabajoP = `<a href="javascript:void(0)" data-uno="${result[i].nodo}" data-dos="${result[i].troba}"
                                                                                                        class="shadow-sm font-weight-bold verTrabajoProgramado" alt="ver trabajo programado" title="ver trabajo programado">
                                                                                                        <img src='/images/icons/trabajo_programado2.png' alt='Seguir' width=15 height=15 border=0>
                                                                                                </a>`
                                                                }else{
                                                                        result[i].trabajoP = `` 
                                                                }
                                                        }
                                                }

                                                if (result[i].cantConsultAmplif > 0) {
                                                        result[i].consultasLlamadasCol  =  `<a href="javascript:void(0)" data-uno="${result[i].nodo}" data-dos="${result[i].troba}" data-tres="${result[i].fecha_hora}"
                                                                                                class="shadow-sm font-weight-bold descargarConsultasM" alt="descargar consultas Amplificador" title="descargar consultas Amplificador">
                                                                                                ${result[i].cantConsultAmplif}
                                                                                        </a>`
                                                }else{
                                                        result[i].consultasLlamadasCol  = ``
                                                }
                                                


                                                if (GESTION_PERMISO) {
                        
                                                        result[i].gestion_col = ` <div class="text-center d-block"> 
                                                                                        <a href="javascript:void(0)" class="btn btn-sm  btn-light p-0 gestionarAveria" 
                                                                                                        data-uno="${result[i].nodo}" data-dos="${result[i].troba}" data-tres="0"
                                                                                                        data-cuatro="${result[i].fecha_hora}" data-cinco="${result[i].codmasiva}" 
                                                                                                        data-seis="${result[i].estadoGestion}" style="color:${result[i].gestionRegistroColor};" alt="Gestionar Avería" title="Gestionar Avería">
                                                                                                <i class="icofont-list icofont-2x"></i>    
                                                                                        </a>`
                                                         
                                                        result[i].gestion_col += `</div>`
                                                }

                                                result[i].estado_gestion_col = ""
                                                if (result[i].estadoGestion != null) {
                                                       // result[i].estado_gestion_col = result[i].estadoGestion + `(${result[i].usuarioGestion})` + "<br/>"+ result[i].fechaHoraGestion
                                                        let parametrosGestion = { 
                                                                        'estadoText':result[i].estadoGestion,
                                                                        'observacionesText':result[i].observacionesGestion,
                                                                        'usuarioText':result[i].usuarioGestion,
                                                                        'fechahoraText':result[i].fechaHoraGestion,
                                                                        'estadoColor':result[i].tituloColorEstadoGestion,
                                                                        'observacionesColor':result[i].contenidoColorEstadoGestion,
                                                                        'usuarioColor':result[i].usuarioColorEstadoGestion,
                                                                        'fechahoraColor':result[i].fechaColorEstadoGestion
                                                                
                                                        } 
                                                        result[i].estado_gestion_col = columnas.armandoEstadoGestionHtml(parametrosGestion)

                                                       // result[i].estado_gestion_col = result[i].estadoGestion + `(${result[i].usuarioGestion})` + "<br/>"+ result[i].fechaHoraGestion
                                                } 

                                                result[i].averiaCol = result[i].averia > 0 ? result[i].averia : ""

                                                if (VER_CRITICOS_PERMISO) {
                                                        if (result[i].tcaidas == 'CRITICA' && parseInt(result[i].ncrit) > 0) {
                                                                result[i].critica = ` <a href="javascript:void(0)" data-uno="${result[i].nodo}" data-dos="${result[i].troba}"
                                                                                                class="shadow-sm font-weight-bold verListaCriticos" alt="ver lista criticos" title="ver lista criticos">
                                                                                                <img src='/images/icons/critica.png' width='17' height='17' alt=''/>
                                                                                        </a>`
                                                        }else{
                                                                result[i].critica = ``
                                                        }
                                                }

                                                result[i].fuenteCol = ""

                                                if (result[i].fuente == "SI" && result[i].fuenteEstado=="OF"){
                                                        result[i].fuenteCol =  ` <img src='/images/icons/fuentecaida.png' height='25' width='25'><br> ${result[i].respaldo == null ? "" : result[i].respaldo }`
                                                }
                                                if (result[i].fuente == "SI" && result[i].fuenteEstado=='ON'){
                                                        result[i].fuenteCol =  ` <img src='/images/icons/fuenteon.png' height='25' width='25'><br> ${result[i].respaldo == null ? "" : result[i].respaldo }` 
                                                }
                                                if ((result[i].fuente == "SI" && result[i].fuenteEstado=='PR')){
                                                        result[i].fuenteCol =  ` <img src='/images/icons/fuenteambar.png' height='25' width='25'><br> ${result[i].respaldo == null ? "" : result[i].respaldo }`
                                                }

                                                if (result[i].fuente =='NO' || result[i].fuenteEstado=='')
                                                {
                                                        result[i].fuenteCol = ""
                                                }

                                                

                                                
                                                if (result[i].estado != "LEVANTO") {

                                                        

                                                        result[i].nodoTroba = ``
                        
                                                        if (result[i].digi == "Digitalizado") {
                                                                result[i].nodoTroba += `<img src='/images/icons/digitalizado.png' width='17' height='17' alt="${result[i].fecha_hora}"/>
                                                                                        <a href="javascript:void(0)" data-uno="${result[i].nodo}" data-dos="${result[i].troba}"
                                                                                                class="shadow-sm font-weight-bold verAlertasDown" alt="ver alertas down" title="ver alertas down">
                                                                                                ${result[i].nodo} - ${result[i].troba}
                                                                                        </a>
                                                                                        `
                                                        }else{
                                                                if (MAPA_PERMISO) {
                                                                        result[i].nodoTroba += `<a href="javascript:void(0)" data-uno="${result[i].nodo}" data-dos="${result[i].troba}"   class="btn btn-sm  verMapa" style="color:${result[i].mapaColor};" alt="Ver Mapa" title="Ver Mapa">
                                                                                                        <i class="icofont-google-map icofont-2x"></i>
                                                                                                </a><br/>`
                                                                }
                                                                result[i].nodoTroba += `<a href="javascript:void(0)" data-uno="${result[i].nodo}" data-dos="${result[i].troba}"
                                                                                                class="shadow-sm font-weight-bold verAlertasDown" alt="ver alertas down" title="ver alertas down">
                                                                                                ${result[i].nodo} - ${result[i].troba} 
                                                                                        </a>`
                                                        }

                                                        result[i].fecha_fin_col =  result[i].crit
 

                                                }else{
 
                                                        result[i].nodoTroba = ``
                        
                                                        if (result[i].digi == "Digitalizado") {
                                                                result[i].nodoTroba += `<img src='/images/icons/digitalizado.png' width='17' height='17' alt="${result[i].fecha_hora}"/>
                                                                                                ${result[i].nodo} - ${result[i].troba} 
                                                                                        `
                                                        }else{
                                                                       
                                                                result[i].nodoTroba += ` ${result[i].nodo} - ${result[i].troba} `
                                                        }

                                                        result[i].fecha_fin_col =  result[i].fecha_fin

                                                       
                                                        
                                                }

                                       }

                                        
                                        //console.log("La data procesada final... es: ",result)

                                        return result  
                                  
                              
                        },
                        'error': function(jqXHR, textStatus, errorThrown)
                        {  

                                // console.log( "Error: " ,jqXHR, textStatus, errorThrown); 
                                $("#display_filter_special").prop("disabled", false);
                                
                                if (REFRESH_PERMISO) {
                                        ESTA_ACTIVO_REFRESH = true
                                        peticiones.resetInterval()
                                }
                                //alert("Se generó un error con la petición, Se intentará traer nuevamente.")
                                 //$("#body-errors-modal").html(jqXHR.responseText)

                                let erroresPeticion =""
                                
                                if(jqXHR.status){
                                        let mensaje = errors.codigos(jqXHR.status)
                                        erroresPeticion = mensaje
                                        console.log("el mensaje re error codigos es: ",erroresPeticion)
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
                                 
                                $(td).css({"background":`${rowData.background}`,"color":`${rowData.color}`}); 
                                $(td).addClass("text-center")
                                 //console.log("los cells: ",td, cellData, rowData, row, col)

                                 let count = 0
                                 if (VER_TRABPROGRAMADOS_PERMISO)   count ++ 
                                 if (DIAGNOSTICOM_PERMISO)   count ++ 
                                 if (VER_CRITICOS_PERMISO)   count ++ 
 

                                 if (col == count+13) {
                                        
                                        if (rowData.diferenciaFechaHora) { 
                                                $(td).css({"background":`${rowData.colorDifFechasGestionAlert}`,"color":`${rowData.color}`});  
                                        }  
                                        
                                 }

                           }
                        },
                        {
                             
                            "targets": '_all',
                           // "orderable" : false,
                            "searchable": false,
                                
                        } 
                ] ,
                "initComplete": function(){
                       // console.log("Termino la carga completa")
                        $("#display_filter_special").prop("disabled", false); 
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
                            '<option value="100">100</option>'+
                            '<option value="300">300</option>'+
                            '<option value="500">500</option>'+
                            '<option value="700">700</option>'+
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


            tabla.parent().addClass("table-responsive tableFixHead") 
            // $("#filtroContentHFC").removeClass("d-none")

            let tablaHead = $('.tableFixHead').find('thead th')
            $('.tableFixHead').on('scroll', function() {
            // console.log("ejecutando"+this.scrollTop); 
            tablaHead.css('transform', 'translateY('+ this.scrollTop +'px)'); 
            }); 

}

if (VER_CRITICOS_PERMISO) {
        peticiones.listaClientesCriticos = function listaClientesCriticos(tabla,parametros)
        {

                $("#display_filter_special").prop("disabled", true);

                tabla.DataTable({
                        "destroy": true,
                        "processing": true, 
                        "serverSide": true,
                        "dom":'<"row mx-0"'
                                +'<"col-12 col-sm-12"l>>'
                                +'<"row"'
                                +'<"col-sm-12 px-0 table-responsive tableFixHead"t>>'
                                +'<"row"'
                                +'<"col-12 col-sm-5"i><"col-12 col-sm-7"p>>'
                                +'r',
                        "ajax": {  
                                'url':'/administrador/caidas/criticas/view',
                                "type": "GET", 
                                "dataType": "json", 
                                "data": function ( d ) {

                                        d.nodo = parametros.nodo;
                                        d.troba = parametros.troba;
                                        //d.amplificador = parametros.amplificador;
                                        
                                },/*
                                'dataSrc': function(json){ 
                                                let result = json.data 
                                                return result   
                                },*/
                                'error': function(jqXHR, textStatus, errorThrown)
                                {  
                                        // console.log( "Error: " ,jqXHR, textStatus, errorThrown); 
                                        $("#display_filter_special").prop("disabled", false);
                                        //alert("Se generó un error con la petición, Se intentará traer nuevamente.")

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
                        "columns": [
                                {data:"IDCLIENTECRM"},
                                {data:"idempresacrm"},
                                {data:"NAMECLIENT"},
                                {data:"NODO"},
                                {data:"TROBA"},
                                {data:"amplificador"},
                                {data:"tap"},
                                {data:"telf1"},
                                {data:"telf2"},
                                {data:"movil1"},
                                {data:"MACADDRESS"},
                                {data:"cmts"},
                                {data:"f_v"},
                                {data:"entidad"}
                        ],
                        "initComplete": function(){
                        // console.log("Termino la carga completa")
                                $("#display_filter_special").prop("disabled", false);
                        },
                        "pageLength": 15,
                        "language": {
                                "info": "_TOTAL_ registros",
                                "search": "Buscar",
                                "paginate": {
                                        "next": "Siguiente",
                                        "previous": "Anterior",
                                },
                                "lengthMenu": 'Mostrar <select >'+
                                '<option value="15">15</option>'+
                                '<option value="50">50</option>'+
                                '<option value="100">100</option>'+
                                '<option value="200">200</option>'+
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

                tabla.parent().addClass("table-responsive tableFixHead") 
                // $("#filtroContentHFC").removeClass("d-none")

                let tablaHead = $('.tableFixHead').find('thead th')
                $('.tableFixHead').on('scroll', function() {
                // console.log("ejecutando"+this.scrollTop); 
                tablaHead.css('transform', 'translateY('+ this.scrollTop +'px)'); 
                }); 
        }
}
if (VER_TRABPROGRAMADOS_PERMISO) {
        peticiones.detalleTrabajoProgramado = function detalleTrabajoProgramado(parametros)
        { 
                $("#trabajoPDetalleModal").modal("show")
                $("#resultDetalleTrabajProg").html(`<div id="carga_person">
                                                        <div class="loader">Loading...</div>
                                                </div>`);

                $.ajax({
                        url:"/administrador/caidas/trabajos-programados/view",
                        method:"get", 
                        data: parametros,
                        dataType:"json",
                        })
                .done(function(data) {
                  //console.log("la respuesta de trabajos es: ",data)

                        if (data.response.data.length == 0) {
                                $("#resultDetalleTrabajProg").html(`<div class="w-100 text-center justify-content-center text-secondary">No se encontraron detalles del T. Programado, verificar que exista recargando nuevamente  la web.</div>`)
                                return false
                        }
                        
                        $("#resultDetalleTrabajProg").html(`
                                <div class="form-row row mb-2 col-12">
                                        <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">Nodo:</label>
                                        <div class="col-sm-8">
                                        <input type="email" class="form-control form-control-sm" id="colFormLabelSm" value="${data.response.data[0].NODO}" readonly>
                                        </div>
                                </div>
                                <div class="form-row row mb-2 col-12">
                                        <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">Troba:</label>
                                        <div class="col-sm-8">
                                        <input type="email" class="form-control form-control-sm" id="colFormLabelSm" value="${data.response.data[0].TROBA}" readonly>
                                        </div>
                                </div>
                                <div class="form-row row mb-2 col-12">
                                        <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">Tipo de Trabajo:</label>
                                        <div class="col-sm-8">
                                        <input type="email" class="form-control form-control-sm" id="colFormLabelSm" value="${data.response.data[0].TIPODETRABAJO}" readonly>
                                        </div>
                                </div>
                                <div class="form-row row mb-2 col-12">
                                        <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">Supervisor:</label>
                                        <div class="col-sm-8">
                                                <input type="email" class="form-control form-control-sm" id="colFormLabelSm" value="${data.response.data[0].SUPERVISOR}" readonly>
                                        </div> 
                                </div>
                                <div class="form-row row mb-2 col-12">
                                        <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">Fecha Inicio:</label>
                                        <div class="col-sm-8">
                                        <input type="email" class="form-control form-control-sm" id="colFormLabelSm" value="${data.response.data[0].FINICIO}" readonly>
                                        </div>
                                </div>
                                <div class="form-row row mb-2 col-12">
                                        <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">Hora Inicio:</label>
                                        <div class="col-sm-8">
                                        <input type="email" class="form-control form-control-sm" id="colFormLabelSm" value="${data.response.data[0].HINICIO}" readonly>
                                        </div>
                                </div>
                                <div class="form-row row mb-2 col-12">
                                        <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">Hora Fin:</label>
                                        <div class="col-sm-8">
                                        <input type="email" class="form-control form-control-sm" id="colFormLabelSm" value="${data.response.data[0].HTERMINO}" readonly>
                                        </div>
                                </div>
                                <div class="form-row row mb-2 col-12">
                                        <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">Horario:</label>
                                        <div class="col-sm-8">
                                        <input type="email" class="form-control form-control-sm" id="colFormLabelSm" value="${data.response.data[0].HORARIO}" readonly>
                                        </div>
                                </div>
                                <div class="form-row row mb-2 col-12">
                                        <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">CORTE:</label>
                                        <div class="col-sm-8">
                                        <input type="email" class="form-control form-control-sm" id="colFormLabelSm" value="${data.response.data[0].CORTESN}" readonly>
                                        </div>
                                </div>
                                <div class="form-row row mb-2 col-12">
                                        <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">ESTADO:</label>
                                        <div class="col-sm-8">
                                        <input type="email" class="form-control form-control-sm" id="colFormLabelSm" value="${data.response.data[0].ESTADO}" readonly>
                                        </div>
                                </div>
                        `)

                        return false

                        
                })
                .fail(function( jqXHR, textStatus ) {
                        //console.log( "Request failed: ",jqXHR, textStatus);
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

                        $("#resultDetalleTrabajProg").html(`<div class="w-100 text-center justify-content-center text-danger">${erroresPeticion}</div>`)
                        return false
                });

        }
}

 
peticiones.cargandoPeticionPrincipal = function   cargandoPeticionPrincipal()
{
        let valorFiltroEspecial

        if ($("#filtroCuadroMando").length) {
            let tipoCaida = $("#filtroCuadroMando").val()

            if (tipoCaida.length==2) {
                valorFiltroEspecial = "caidas_masivas"
            } else {
                valorFiltroEspecial = tipoCaida
            }

            //valorFiltroEspecial = tipoCaida
        } else {
            valorFiltroEspecial = $("#display_filter_special").val();
        }

        //let valorFiltroEspecial = $("#display_filter_special").val();

        let params = peticiones.getDataRequiredFilterCaidas(valorFiltroEspecial);
   
   
        peticiones.redirectTabs(params.redirect)
        $(".content_filter_basic").css({"display":"none"})
  
        if (valorFiltroEspecial == "caidas_amplificador") {
          
          peticiones.cargaCaidasAmplificadorLista(params.columnasCaidas,BUTTONS_CAIDAS_MASIVAS,params.parametros,params.tabla)
  
        }else{
  
          peticiones.cargaCaidasLista(params.columnasCaidas,BUTTONS_CAIDAS_MASIVAS,params.parametros,params.tabla)
  
        }
}
 

export default peticiones
