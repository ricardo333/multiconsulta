import errors from  "@/globalResources/errors.js"
 import columnas from  "@/globalResources/tablas/columnas.js"

const peticiones = {}


peticiones.redirectTabs = function redirectTabs(identificador) {
    $('#tabsMonitorAveriasContent > .tab-pane').removeClass('show');
    $('#tabsMonitorAveriasContent > .tab-pane').removeClass('active');
    identificador.tab('show')  
}

peticiones.ultimoUpdateMoAv = function  ultimoUpdateMoAv(rutaUltimaFecha,printDate){
    printDate.html(`<div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>`);
    $.ajax({
        url:`${rutaUltimaFecha}`,
        method:"get",
       /* data:{
            "monitoreo":parameter
        },*/
        async: true,
        cache: false, 
        dataType: "json", 
      })
      .done(function(data){ 
         //console.log("respuesta de envio:",data)
        printDate.html(data.response.datetime);
        
      })
      .fail(function(jqXHR, textStatus, errorThrown){
      // console.log( "Request failed: " ,textStatus ,jqXHR,errorThrown);
       printDate.html("");
      }); 
}

peticiones.armandoColumnasHFC = function armandoColumnasHFC()
{
    //columnasContent COLUMNS_MONITOR_AVERIAS_HFC
    let columnasContent = 
    [
        {data: 'id'}
    ]
    if (DIAGNOSTICOM_PERMISO) {
        columnasContent.push({data: null,
                                                render: function(data,type,row){
                                                    if (row.nodo !== '' || row.troba !== '') {
                                                        return `<div class="text-center">
                                                                <a href="javascript:void(0)" data-uno="${row.nodo}" data-dos="${row.troba}"
                                                                        class="shadow-sm font-weight-bold verDiagnosticoMasivo" alt="Ver Diagóstico Masivo" title="Ver Diagóstico Masivo">
                                                                    DM
                                                                </a>
                                                            </div>`
                                                    }else{
                                                        return ``
                                                    }
                                                        
                                                   
                                                   
                                                }
                                            })
    }
    columnasContent.push(
                                            {data: 'jefatura'},
                                            {data: 'troba',
                                                render: function(data,type,row){

                                                    let estructura = `<div class="text-center">`
                                                    if (MAPA_PERMISO) {
                                                        estructura += `<a href="javascript:void(0)" data-uno="${row.nodo}" data-dos="${row.troba}" class="btn btn-sm verMapa" style="color:${row.mapaColor};" alt="Ver Mapa" title="Ver Mapa">
                                                                            <i class="icofont-google-map icofont-2x"></i>
                                                                        </a><br/>`
                                                    }
                                                    estructura += `<a href="javascript:void(0)" data-uno="${row.nodo}" data-dos="${row.troba}" class="verHistoricoNodoTroba" alt="Ver Histórico" title="Ver Histórico">
                                                                            ${row.nodo} ${row.troba}
                                                                    </a>
                                                                </div>`
                                                    return estructura
                                                }
                                            },
                                            {data: 'consultas', //Llamadas DMPE
                                                render: function(data,type,row){
                                                    return `<div class="text-center">
                                                                <a href="javascript:void(0)" data-uno="${row.nodo}" data-dos="${row.troba}" data-tres="${row.hoy}"
                                                                        class="descargaConsultasDmpeHFC" alt="Descarga consultas CTV" title="Descarga consultas CTV">
                                                                        ${ row.calldmpe == null ? "" : row.calldmpe}
                                                                </a>  <br/>${ row.ultimallamada == null ? "" : row.ultimallamada}
                                                            </div>`
                                                }
                                            },
                                            {data: 'aver',
                                                //href="{{ route('export_excel.excel') }}"
                                                render: function(data,type,row){
                                                    return `<div class="text-center">
                                                                <a href="javascript:void(0)" data-uno="${row.nodo}" data-dos="${row.troba}" 
                                                                        class="descargarAveriasHFC" alt="Descargar Averias" title="Descargar Averias">
                                                                    ${row.aver}
                                                                </a>
                                                            </div>`
                                                }
                                                
                                            },
                                            {data: 'ultreq',
                                                render: function(data,type,row){
                                                    return ` <strong>${data}</strong> <br/> ${row.fec_registro}`
                                                }
                                            },
                                            {data: 'codreqmnt',
                                                render: function(data,type,row){
                                                    return ` <strong> ${ data == null ? "" : data} </strong> <br/> ${ row.fecreg == null ? "" : row.fecreg} `
                                                }
                                            },
                                            {data: 'trabprog'},
                                            {data: 'estado',
                                                    render: function(data,type,row){
                                                    // console.log("esto se recibe:",data,type,row)
                                                        if (data != null) {
                                                            let parametrosGestion = { 
                                                                'estadoText':data,
                                                                'observacionesText':row.observaciones,
                                                                'usuarioText':row.usuario,
                                                                'fechahoraText':row.fechahora,
                                                                'estadoColor':row.colorTextEstado,
                                                                'observacionesColor':row.colorObserv,
                                                                'usuarioColor':row.colorUserEstado,
                                                                'fechahoraColor':row.colorTextEstado
                                                                } 
                                                            return columnas.armandoEstadoGestionHtml(parametrosGestion)

                                                            /*return `
                                                                    ${data}<br>
                                                                    <span style="color:${row.colorObserv}"> ${row.observaciones} </span> <br>
                                                                    <span style="color:${row.colorUserEstado}"> (${row.usuario}) </span> <br>
                                                                    ${row.fechahora}`*/
                                                        }else{
                                                            return "";
                                                        }
                                                    }
                                            } 
                                    )


    if (GESTION_PERMISO) {
        columnasContent.push(
                                    {data: 'estado',
                                        render: function(data,type,row){

                                                    let armandoEsque = `<div class="text-center">`

                                                    armandoEsque += `
                                                    <a href="javascript:void(0)" class="btn btn-sm btn-light p-0 gestionarAveria" data-uno="${row.nodo}" data-dos="${row.troba}" data-tres="0"
                                                        data-cuatro="${row.fechahora}" data-seis="${row.codreqmnt}" data-seis="${data}" style="color:${row.gestionRegistroColor};" alt="Gestionar Avería" title="Gestionar Avería">
                                                            <i class="icofont-list icofont-2x"></i>    
                                                    </a>`
                                                
                                                    
                                                    if (data != null) {
                                                        if (data.trim() == "Enviada:ATENTO para liquidar" || data.trim() == "Enviada:COT para liquidar" || data.trim() == "En Proceso de liquidacion Auto") {
                                                        if (row.codreqmnt != "") {
                                                                armandoEsque += `<a href="javascript:void(0)" data-uno="${row.codreqmnt}" class="btn btn-sm verDetalleGestion" style="color:${row.gestionDetalleColor};" alt="Ver detalle masiva" title="Ver detalle masiva">
                                                                                    <i class="icofont-list icofont-2x"></i>   
                                                                                </a>`
                                                            }
                                                        }
                                                    }
                                                    armandoEsque += `</div>`

                                                    return armandoEsque

                                        }
                                    })
    }

        return columnasContent
}

peticiones.armandoColumnasGPON = function armandoColumnasGPON()
{
    //columnasContent COLUMNS_MONITOR_AVERIAS_GPON
     
    var columnasContent = 
        [
            {data: 'id'}
        ]
        if (DIAGNOSTICOM_PERMISO) {
            columnasContent.push( {data: null,
                                                render: function(data,type,row){
                                                    //console.log("la data de estado es: ",data,type,row)
                                                    if (row.nodo !== '' || row.troba !== '') {
                                                        return `<div class="text-center">
                                                                <a href="javascript:void(0)" data-uno="${row.nodo}" data-dos="${row.troba}"
                                                                        class="shadow-sm font-weight-bold verDiagnosticoMasivo" alt="Ver Diagóstico Masivo" title="Ver Diagóstico Masivo">
                                                                    DM
                                                                </a>
                                                            </div>`
                                                    }else{
                                                         return ``
                                                    }
                                                    
                                                }
                                            })
        }
          
        columnasContent.push( 
                                            {data: 'jefatura',
                                                render:function(data,type,row){
                                                    if (row.premium == "PREMIUM") {
                                                        return `<div class="text-center">
                                                                    <img src='/images/icons/premium.png' width='14' height='14'/>
                                                                    <a href="javascript:void(0)" data-uno="${row.nodo}" data-dos="${row.troba}"
                                                                            class="shadow-none font-weight-bold  verJefatura" alt="Premium" title="Premium">
                                                                        ${data}
                                                                    </a>
                                                                </div>`
                                                    }else{
                                                        return `<div class="text-center">
                                                                    <a href="javascript:void(0)" data-uno="${row.nodo}" data-dos="${row.troba}"
                                                                            class=" shadow-none font-weight-bold  verJefatura" alt="Ver Jefatura" title="Ver Jefatura">
                                                                        ${data}
                                                                    </a>
                                                                </div>`
                                                    }
                                                    
                                                }
                                            },
                                            {data: 'troba',
                                                render: function(data,type,row){

                                                    let estructura = `<div class="text-center">`

                                                    if (MAPA_PERMISO) {
                                                        estructura += `<a href="javascript:void(0)" data-uno="${row.nodo}" data-dos="${row.troba}" class="btn btn-sm verMapa" style="color:${row.mapaColor};" alt="Ver Mapa" title="Ver Mapa">
                                                                            <i class="icofont-google-map icofont-2x"></i> 
                                                                        </a><br/>`
                                                    }
                                                    estructura += `<a href="javascript:void(0)" data-uno="${row.nodo}" data-dos="${row.troba}" class="verHistoricoNodoTroba" alt="Ver Histórico" title="Ver Histórico">
                                                                            ${row.nodo} ${row.troba}
                                                                    </a> 
                                                                </div>`
                                                    return estructura
                                                }
                                            },
                                            {data: 'consultas', //Llamadas DMPE
                                                render: function(data,type,row){
                                                    return `<div class="text-center">
                                                                <a href="javascript:void(0)" data-uno="${row.nodo}" data-dos="${row.troba}" data-tres="${row.hoy}"
                                                                        class=" descargaConsultasDmpeGpon" alt="Descarga Consultas CTV" title="Descarga Consultas CTV">
                                                                        ${ data == null ? "" :data}
                                                                </a>  
                                                            </div>`
                                                }
                                            },
                                            {data: 'aver',
                                                //href="{{ route('export_excel.excel') }}"
                                                render: function(data,type,row){
                                                    return `<a href="javascript:void(0)" data-uno="${row.nodo}" data-dos="${row.troba}" 
                                                    class=" descargarAveriasGPON" alt="Descargar Averias GPON" title="Descargar Averias GPON">
                                                            ${row.aver}
                                                            </a>`
                                                }
                                                
                                            },
                                            {data: 'ultreq',
                                                render: function(data,type,row){
                                                    return ` <strong>${data}</strong> <br/> ${row.fec_registro}`
                                                }
                                            },
                                            {data: 'codreqmnt'},
                                            {data: 'trabprog'},
                                            {data: 'estado',
                                                    render: function(data,type,row){
                                                    // console.log("esto se recibe:",data,type,row)
                                                        if (data != null) {

                                                            let parametrosGestion = { 
                                                                'estadoText':data,
                                                                'observacionesText':row.observaciones,
                                                                'usuarioText':row.usuario,
                                                                'fechahoraText':row.fechahora,
                                                                'estadoColor':row.colorTextEstado,
                                                                'observacionesColor':row.colorObserv,
                                                                'usuarioColor':row.colorUserEstado,
                                                                'fechahoraColor':row.colorTextEstado
                                                                } 
                                                            return columnas.armandoEstadoGestionHtml(parametrosGestion)

                                                            /*return `
                                                                    ${data}<br>
                                                                    <span style="color:${row.colorObserv}"> ${row.observaciones} </span> <br>
                                                                    <span style="color:${row.colorUserEstado}"> (${row.usuario}) </span> <br>
                                                                    ${row.fechahora}`*/
                                                        }else{
                                                            return "";
                                                        }
                                                    }
                                            }
                                        )
         
        
        if (GESTION_PERMISO) {
            columnasContent.push(
                                        {data: 'estado',
                                            render: function(data,type,row){
 
                                                        let armandoEsque = `<div class="text-center">`

                                                        armandoEsque += `
                                                            <a href="javascript:void(0)" class=" p-0 gestionarAveria" data-uno="${row.nodo}" data-dos="${row.troba}" data-tres="0"
                                                                data-cuatro="${row.fechahora}" data-cinco="${row.codreqmnt}" data-seis="${data}" style="color:${row.gestionRegistroColor};" alt="Gestionar Avería" title="Gestionar Avería">
                                                                    <i class="icofont-list icofont-2x"></i>    
                                                            </a>`

                                                        armandoEsque += `</div>`

                                                        return armandoEsque
 
                                            }
                                        })
        }

        return columnasContent
}

peticiones.cargaDataMonitorAverias = function cargaDataMonitorAverias(COLUMNS_MONITOR_AVERIAS,COLUMNS_DEFS_MONITOR_AVERIAS,BUTTONS_MONITOR_AVERIAS,
                                                                        parametersDataAverias,tabla){

           
            $("#display_filter_special").prop("disabled", true);

            //console.log("Carga......")

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
                "buttons":BUTTONS_MONITOR_AVERIAS,
                "ajax": {  
                    'url':'/administrador/monitor-averias/lista',
                    "type": "GET", 
                    "data": function ( d ) {
                        
                            d.filtroJefatura = parametersDataAverias.jefatura;
                            d.filtroEstado = parametersDataAverias.estado;
                            d.filtroHfcGpon = parametersDataAverias.filtroHfcGpon;
                            /*d.num_puer = des_puer;*/
                    },
                    /*'dataSrc': function(json){
                            console.log("Termino la carga asi tenga error.. :",json)
                            $("#display_filter_special").prop("disabled", false);
                            return json
                    },*/
                    'error': function(jqXHR, textStatus, errorThrown)
                        {  
                        //console.log( "Error: " ,jqXHR, textStatus, errorThrown); 
                        $("#display_filter_special").prop("disabled", false);
                            //alert("Se generó un error con la petición, Se intentará traer nuevamente.")
                            //location.reload(); 
                           // $("#body-errors-modal").html(jqXHR.responseText)
                           $("#body-errors-modal").html(`<div class="w-100 text-center text-danger">Ocurrio un problema con la carga de datos, intente nuevamente recargando la web.</div>`)
                            $('#errorsModal').modal('show') 
                                if(jqXHR.status){
                                    if (jqXHR.status == 401) {
                                        location.reload();
                                        return false
                                    } 
                                
                                // peticiones.redirectTabs($('#multiMapTab')) 
                                    return false
                                } 
                            // peticiones.redirectTabs($('#multiMapTab')) 
                                
                                return false 
                        }
                }, 
                "columns": COLUMNS_MONITOR_AVERIAS,
                'columnDefs': COLUMNS_DEFS_MONITOR_AVERIAS ,
                "initComplete": function(){
                        //console.log("Termino la carga completa")
                        $("#display_filter_special").prop("disabled", false);
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

peticiones.verHistorialNodoTroba = function peticiones(nodo,troba){

    $("#display_filter_special").prop("disabled", true);


        $("#resultHistoricoNodoTroba").DataTable({
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
                    'url':'/administrador/monitor-averias/historico/nodo-troba',
                    "type": "GET", 
                    "dataType": "json", 
                    "data": function ( d ) {
                        
                            d.nodo = nodo;
                            d.troba = troba;
                    },
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
                        {data: 'nodo'},
                        {data: 'troba'},
                        {data: 'powerup_max'},
                        {data: 'powerup_prom'},
                        {data: 'powerup_min'},
                        {data: 'powerds_max'},
                        {data: 'powerds_prom'},
                        {data: 'powerds_min'},
                        {data: 'snr_avg'},
                        {data: 'snr_down'},
                        {data: 'fecha_hora'},
                        {data: 'cmts'},
                        {data: 'interface'}
                ],
                'columnDefs': [
                            { 
                                "targets":"_all",
                                "orderable" : false,
                                "searchable": false, 
                            } 
                ] ,
                "initComplete": function(){
                    // console.log("Termino la carga completa") 
                    $("#display_filter_special").prop("disabled", false);
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
                                '<option value="200">200</option>'+
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


        $("#resultHistoricoNodoTroba").parent().addClass("table-responsive tableFixHead") 
        // $("#filtroContentHFC").removeClass("d-none")

        let tablaHead = $('.tableFixHead').find('thead th')
        $('.tableFixHead').on('scroll', function() {
        // console.log("ejecutando"+this.scrollTop); 
        tablaHead.css('transform', 'translateY('+ this.scrollTop +'px)'); 
        }); 
}

export default peticiones