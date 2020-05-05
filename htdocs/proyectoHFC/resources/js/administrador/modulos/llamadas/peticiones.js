import errors from  "@/globalResources/errors.js"
import columnas from  "@/globalResources/tablas/columnas.js"

const peticiones = {}

peticiones.redirectTabs = function redirectTabs(identificador) {
        $('#tabsLlamadasContent > .tab-pane').removeClass('show');
        $('#tabsLlamadasContent > .tab-pane').removeClass('active');
        identificador.tab('show')
}

peticiones.resetInterval = function resetInterval(){
        if (INTERVAL_LOAD != null) {
                clearInterval(INTERVAL_LOAD)
                //console.log("Se limpio el interval y se debe iniciar nuevamente...")
                INTERVAL_LOAD = setInterval(() => {
                        if (ESTA_ACTIVO_REFRESH) {
                            if ($( ".listaLlamadas" ).hasClass( "active" )) {
                                peticiones.cargandoPeticionPrincipal()
                            } 
                        }
                
                }, 180000);
        }
}

peticiones.armandoColumnasUno = function armandoColumnasUno()
{
        let columnasContent =  [ {data: 'id'}  ]
        columnasContent.push({data: 'jefatura'})
        columnasContent.push({data:'DM'})
        columnasContent.push({data: 'nodoTroba'})
        columnasContent.push({data: 'Top'})
        columnasContent.push({data: 'calldmpe'})
        columnasContent.push({data: 'averias'})
        columnasContent.push({data: 'ultimallamada'})
        columnasContent.push({data: 'codreqmnt'})
        columnasContent.push({data: 'eventid'})
        columnasContent.push({data: 'usuario'})
        if (VER_TRABPROGRAMADOS_PERMISO) {
                columnasContent.push({data:'trabajoP'})
        }
        columnasContent.push({data:'estado_gestion_col'})
        if (GESTION_PERMISO) {
                columnasContent.push({data: 'gestion_col'})
        }

        return columnasContent
}

function procesandoResultadoLista(result)
{
        for (let i = 0; i < result.length ; i++) {
                if (result[i].nodo !== '' || result[i].troba !== '') {
                        result[i].DM = `<div class="text-center">
                                        <a href="javascript:void(0)" data-uno="${result[i].nodo}" data-dos="${result[i].troba}"
                                                class="shadow-sm font-weight-bold verDiagnosticoMasivo" alt="Ver Diagóstico Masivo" title="Ver Diagóstico Masivo">
                                                [DM]: </a> ${result[i].nodo} ${result[i].troba} <br>${result[i].tiptec}</div>`
                }
                
                result[i].calldmpe = `<a href="javascript:void(0)" data-uno="${result[i].nodo}" data-dos="${result[i].troba}" data-tres="${result[i].hoy}"
                                        class="shadow-sm font-weight-bold verConsultasCtv" alt="ver consultas CTV" title="ver consultas CTV">
                                        ${result[i].calldmpe == null ? "" : result[i].calldmpe}
                                </a>`
                                

                result[i].averias = `<a href="javascript:void(0)" data-uno="${result[i].nodo}" data-dos="${result[i].troba}" data-tres="${result[i].hoy}"
                                        class="shadow-sm font-weight-bold verAveriasDown" alt="ver averias down" title="ver averias down">
                                       ${result[i].averiasc == null ? "" : result[i].averiasc}
                                </a>` 
                
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

                       /* result[i].estado_gestion_col = `
                                        <p class="text-danger text-left" style="color:red">${result[i].alertasGestion[0].estado}</p>
                                        <p class="text-dark text-left" style="color:black"><b>${result[i].alertasGestion[0].observaciones} (${result[i].alertasGestion[0].usuario})</b></p>
                                        <p class="text-danger text-left">${result[i].alertasGestion[0].fechahora}</p>
                                        `*/
                }else{
                        result[i].estado_gestion_col = ``
                }

                result[i].nodoTroba = ``
                if (MAPA_PERMISO || MAPA_PERMISO_CALL) {

                        if (MAPA_PERMISO) {
                                result[i].nodoTroba = `<a href="javascript:void(0)" data-uno="${result[i].nodo}" data-dos="${result[i].troba}" class="text-info verMapa" alt="Ver Mapa" title="Ver Mapa">
                                                                <i class="icofont-google-map icofont-2x"></i>
                                                        </a>`
                        }
                        if (MAPA_PERMISO_CALL) {
                                result[i].nodoTroba += `<a href="javascript:void(0)" data-uno="${result[i].nodo}" data-dos="${result[i].troba}" class="text-info verMapaCall" alt="Ver Mapa" title="Ver Mapa">
                                                                <i class="icofont-telephone icofont-2x"></i>
                                                        </a>`
                        }
                }    

                if (GESTION_PERMISO) {
                       
                        result[i].gestion_col = ` <div class="text-center d-block"> <a href="javascript:void(0)" class="btn btn-sm text-success btn-light p-0 gestionarAveria" data-uno="${result[i].nodo}" data-dos="${result[i].troba}" data-tres="0"
                                                data-cuatro="${result[i].fecha_inicio}" data-cinco="${result[i].codmasiva}" data-seis="${result[i].gestion_estado}" alt="Gestionar Avería" title="Gestionar Avería">
                                                <i class="icofont-list icofont-2x"></i>    
                                                </a>`
                                                
                        result[i].gestion_col += `</div>`
                }
 
        }

        return result
}

peticiones.getDataRequiredFilterLlamadas = function getDataRequiredFilterLlamadas(tipo)
{
        let data = {}
        data.parametros = {}
        data.parametros.nodo = "" 
        data.parametros.tipoCaidas = tipo
  
        data.redirect = $('#llamadasMasivasTab')
        data.tabla = $("#resultLlamadaTrobas") 
        data.parametros.jefatura = $("#listajefaturaLlamadas").val()
        data.parametros.top = $("#listaTopLlamadas").val()
        data.parametros.nodo = $("#nodoJefaturaLlamadas").val() 
        data.columnasCaidas = peticiones.armandoColumnasUno()
  
        return data
}

peticiones.cargaLlamadasLista = function cargaLlamadasLista(COLUMNS_CAIDAS,BUTTONS_CAIDAS,parametersDataLlamadas,tabla){
     
        //$("#display_filter_special").prop("disabled", true); 
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
                        'url':'/administrador/llamadas/lista',
                        "type": "GET", 
                        "data": function ( d ) {
                                d.filtroJefatura = parametersDataLlamadas.jefatura;
                                d.filtroTop = parametersDataLlamadas.top;
                                d.nodo = parametersDataLlamadas.nodo;
                        },
                        'dataSrc': function(json){
                                       //return json
                                        let result = json.data
                                       //  console.log("El result es: ",result)
                                        let dataProcesada = procesandoResultadoLista(result)
                                        return dataProcesada
                        },
                        'error': function(jqXHR, textStatus, errorThrown)
                        {  
                                //console.log( "Entre a Error: " ,jqXHR, textStatus, errorThrown); 
                                $("#display_filter_special").prop("disabled", false);
                                if (REFRESH_PERMISO) {
                                        ESTA_ACTIVO_REFRESH = true
                                        peticiones.resetInterval()
                                }
                                //alert("Se generó un error con la petición, Se intentará traer nuevamente.")
                                //location.reload(); 
                                 //$("#body-errors-modal").html(jqXHR.responseText)
                                // $("#body-errors-modal").html(`<div class="w-100 text-center text-danger">Ocurrio un problema con la carga de datos, intente nuevamente recargando la web.</div>`)
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
                        //console.log("Termino la carga completa estoy en initComplete")
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

if (VER_TRABPROGRAMADOS_PERMISO) {
        peticiones.detalleTrabajoProgramado = function detalleTrabajoProgramado(parametros)
        { 
                $("#trabajoPDetalleModal").modal("show")
                $("#resultDetalleTrabajProg").html(`<div id="carga_person">
                                                        <div class="loader">Loading...</div>
                                                </div>`);

                $.ajax({
                        url:"/administrador/llamadas/trabajos-programados/view",
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

peticiones.cargandoPeticionPrincipal = function cargandoPeticionPrincipal()
{
        let valorFiltroEspecial = $("#input-llamadasMasivasTab").val();
        let params = peticiones.getDataRequiredFilterLlamadas(valorFiltroEspecial);
        
        peticiones.redirectTabs(params.redirect)
        //$(".content_filter_basic").css({"display":"none"})
        peticiones.cargaLlamadasLista(params.columnasCaidas,BUTTONS_CAIDAS_MASIVAS,params.parametros,params.tabla)
}

export default peticiones
