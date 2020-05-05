import errors from  "@/globalResources/errors.js"
import columnas from  "@/globalResources/tablas/columnas.js"

const peticiones = {}


peticiones.redirectTabs = function redirectTabs(identificador) {
    $('#tabsMasivaCmsContent > .tab-pane').removeClass('show');
    $('#tabsMasivaCmsContent > .tab-pane').removeClass('active');
    identificador.tab('show')  
}


peticiones.armandoColumnasHFC = function armandoColumnasHFC()
{
    //columnasContent COLUMNS_MONITOR_AVERIAS_HFC
    let columnasContent = 
    [
        {data: 'id'}
    ]

    columnasContent.push(
                                        {data: 'jefatura',
                                            render: function(data,type,row){
                                                if (row.nodo !== '' || row.troba !== '') {
                                                    return `<div class="text-center">
                                                            <span style="color: black;">${row.jefatura}</span><br>
                                                            <a href="javascript:void(0)" data-uno="${row.nodo}" data-dos="${row.troba}"
                                                                class="shadow-sm font-weight-bold verDiagnosticoMasivo" alt="Ver Diagóstico Masivo" title="Ver Diagóstico Masivo">
                                                                [DM]
                                                            </a>
                                                        </div>`
                                                }else{
                                                    return  `` 
                                                }
                                            }
                                        },
                                        {data: 'ncrit',
                                            render: function(data,type,row){
                                                if (VER_CRITICOS_PERMISO) {
                                                    if (row.tc == 'TC' || row.ncrit > 0) {
                                                        return `<div class="text-center">
                                                                    <a href="javascript:void(0)" data-uno="${row.nodo}" data-dos="${row.troba}"
                                                                        class="shadow-sm font-weight-bold verListaCriticos" alt="ver lista criticos" title="ver lista criticos">
                                                                        <img src='/images/icons/critica.png' width='17' height='17' alt=''/>
                                                                    </a>
                                                                </div>`
                                                    } else {
                                                        return `<span> </span>`
                                                    }
                                                }
                                                return `<span> </span>`
                                            }
                                        },
                                        {data: 'nodo',
                                            render: function(data,type,row){
                                                let estructura = `<div class="text-center">`
                                                if (MAPA_PERMISO) {
                                                    estructura += `<a href="javascript:void(0)" data-uno="${row.nodo}" data-dos="${row.troba}" class="btn btn-sm  text-dark verMapa" alt="Ver Mapa" title="Ver Mapa">
                                                                        <i class="icofont-google-map icofont-2x"></i>
                                                                    </a><br>`
                                                }
                                                estructura += `<span>
                                                                    ${row.nodo}
                                                                </span><br>`
                                                estructura += `<a href="javascript:void(0)" data-uno="${row.nodo}" data-dos="${row.troba}" class="verAlertasMasivasDown" alt="ver alertas down" title="ver alertas down" style="color: black;">
                                                                    ${row.troba}
                                                                </a>
                                                            </div>`
                                                return estructura
                                            }
                                        },
                                        {data: 'trabajo_estado',
                                            render: function(data,type,row){
                                                if (VER_TRABPROGRAMADOS_PERMISO) {
                                                    if(row.trabajo_estado=='ENPROCESO'){
                                                        return `<div class="text-center">
                                                                    <a href="javascript:void(0)" data-uno="${row.nodo}" data-dos="${row.troba}"
                                                                        class="shadow-sm font-weight-bold verTrabajoProgramado" alt="ver trabajo programado" title="ver trabajo programado">
                                                                        <img src='/images/icons/trabajo_programado2.png' alt='Seguir' width=15 height=15 border=0>
                                                                    </a>
                                                                </div>`
                                                    }else{
                                                        return `<span> </span>`
                                                    }
                                                }
                                                return `<span> </span>`
                                            }
                                        },
                                        {data: 'aver',
                                            render: function(data,type,row){
                                                        return `<div class="text-center">
                                                                    <a href="javascript:void(0)" data-uno="${row.nodo}" data-dos="${row.troba}" class="verAverias" alt="ver averias" title="ver alertas down">
                                                                        ${row.aver} / ${row.call} 
                                                                    </a>
                                                                </div>`
                                            }
                                        },
                                        {data: 'dmpe',
                                            render: function(data,type,row){
                                                        return `<div class="text-center">
                                                                    <span>${row.dmpe}</span>
                                                                </div>`
                                            }
                                        },
                                        {data: 'cancli'},
                                        {data: 'umbral'},
                                        {data: 'offline'},
                                        {data: 'remedy'},
                                        {data: 'codmasiva'},
                                        {data: 'fecha_hora'},
                                        {data: 'tiempo'},
                                        {data: 'estado',
                                            render: function(data,type,row){
                                                // console.log("esto se recibe:",data,type,row)
                                                if (row.tp_estado != null) {
                                                    return `<span style="color:${row.colorTextEstado}"> ${row.tp_estado} </span> <br>
                                                            <span style="color:${row.colorObserv}"> ${row.tp_observaciones} </span> <br>
                                                            <span style="color:${row.colorUserEstado}"> (${row.tp_usuario}) </span> <br>
                                                            <span style="color:${row.colorTextEstado}"> ${row.tp_fechahora} </span>`
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
                                                    <a href="javascript:void(0)" class="btn btn-sm text-success btn-light p-0 gestionarAveria" data-uno="${row.nodo}" data-dos="${row.troba}" data-tres="0"
                                                        data-cuatro="${row.fechahora}" data-seis="${row.codmasiva}" data-seis="${row.tp_estado}" alt="Gestionar Avería" title="Gestionar Avería">
                                                            <i class="icofont-list icofont-2x"></i>    
                                                    </a>`

                                                if (row.tp_estado != null) {
                                                    if (row.tp_estado.trim() == "Enviada:ATENTO para liquidar" || row.tp_estado.trim() == "Enviada:COT para liquidar" || row.tp_estado.trim() == "En Proceso de liquidacion Auto") {
                                                        if (row.codmasiva != "") {
                                                            armandoEsque += `<a href="javascript:void(0)" data-uno="${row.codmasiva}" class="btn btn-sm text-dark verDetalleGestion" alt="Ver detalle masiva" title="Ver detalle masiva">
                                                                                <i class="icofont-list icofont-2x"></i>   
                                                                            </a>`
                                                        }
                                                    }
                                                }

                                                armandoEsque += `</div>`

                                                return armandoEsque

                                            }
                                        
                                        }
        )
    }

    columnasContent.push(
                                        {data: 'mac4',
                                            render: function(data,type,row){
                                                if (row.fuente=='SI' && row.estadoFuente=='OF') {
                                                    return `<div class="text-center">
                                                                <img src='/images/icons/fuentecaida.png' alt='fuentecaida' width=15 height=15 border=0>
                                                            </div>`
                                                }

                                                if (row.fuente=='SI' && row.estadoFuente=='ON') {
                                                    return `<div class="text-center">
                                                                <img src='/images/icons/fuenteon.png' alt='fuenteon' width=15 height=15 border=0>
                                                            </div>`
                                                }

                                                if ((row.fuente=='SI' && row.estadoFuente=='PR') || row.estadoFuente=='') {
                                                    return `<div class="text-center">
                                                                <img src='/images/icons/fuenteambar.png' alt='fuenteambar' width=15 height=15 border=0>
                                                            </div>`
                                                }

                                                if (row.fuente=='NO') {
                                                    return `<span> </span>`
                                                }
                                            }
                                        }
    )


    if (ELIMINAR_MASIVA) {
        columnasContent.push(
                                        {data: 'troba',
                                            render: function(data,type,row){
                                                return `<div class="text-center">
                                                            <a href="javascript:void(0)" data-uno="${row.nodo}" data-dos="${row.troba}"
                                                            class="shadow-sm font-weight-bold eliminarMasiva" alt="eliminar masiva" title="eliminar masiva">
                                                            <img src='/images/icons/equis.png' alt='fuenteambar' width=15 height=15 border=0>
                                                            </a>
                                                        </div>`
                                            }                
                                        }
                                    )
    }

        return columnasContent

}



peticiones.cargaDataMasivaCms = function cargaDataMasivaCms(COLUMNS_MONITOR_AVERIAS,COLUMNS_DEFS_MONITOR_AVERIAS,BUTTONS_MONITOR_AVERIAS,
                                        parametersDataMasivas,tabla){


    $("#display_filter_special").prop("disabled", true);

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
        'url':'/administrador/masiva-cms/lista',
        "type": "GET", 
        "data": function ( d ) {

            d.filtroJefatura = parametersDataMasivas.jefatura;
            d.filtroEstado = parametersDataMasivas.estado;
            d.filtroMotivo = parametersDataMasivas.motivo;
            d.filtroNodo = parametersDataMasivas.nodo;

            },

            'error': function(jqXHR, textStatus, errorThrown)
            {  

                console.log( "Error: " ,jqXHR, textStatus, errorThrown); 
                $("#display_filter_special").prop("disabled", false);
                $("#carga_person").html("");
                //alert("Se generó un error con la petición, Se intentará traer nuevamente.")
                //location.reload(); 
                //$("#body-errors-modal").html(jqXHR.responseText)
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
            console.log("Termino la carga completa")
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
                    'url':'/administrador/masiva-cms/criticas/view',
                    "type": "GET", 
                    "dataType": "json", 
                    "data": function ( d ) {

                        d.nodo = parametros.nodo;
                        d.troba = parametros.troba;
                                        
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




peticiones.detalleTrabajoProgramado = function detalleTrabajoProgramado(parametros)
{

    $("#trabajoPDetalleModal").modal("show")
    $("#resultDetalleTrabajProg").html(`<div id="carga_person">
                                        <div class="loader">Loading...</div>
                                        </div>`);

    $.ajax({
            url:"/administrador/masiva-cms/trabajos-programados/view",
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



peticiones.eliminarMasivaCms = function eliminarMasivaCms(parametros)
{

    $("#eliminarMasivaModal").modal("show")
    $("#resultEliminarMasiva").html(`<div id="carga_person">
                                        <div class="loader">Loading...</div>
                                        </div>`);

    $.ajax({
            url:"/administrador/masiva-cms/gestion-masiva/delete",
            method:"get", 
            data: parametros,
            dataType:"json",
            })
            .done(function(data) {
            //console.log("la respuesta de trabajos es: ",data)

            if (data.response.data.length == 0) {
                $("#resultEliminarMasiva").html(`<div class="w-100 text-center justify-content-center text-secondary">No se encontraron detalles del T. Programado, verificar que exista recargando nuevamente  la web.</div>`)
                return false
            }
                        
            $("#resultEliminarMasiva").html(`
                <div class="form-row row mb-2 col-12">
                    <div class="col-sm-8">
                        <input type="email" class="form-control form-control-sm" id="colFormLabelSm" value="${data.response.data.mensaje}" readonly>
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

                $("#resultEliminarMasiva").html(`<div class="w-100 text-center justify-content-center text-danger">${erroresPeticion}</div>`)
                return false
            });

}



peticiones.loadArchivoServicio = function loadArchivoServicio(formData,callBack){

    $.ajax({
        url:`/administrador/masiva-cms/carga-masiva/view`,
        method:"POST",
        async: true,
        data:formData,
        cache: false, 
        contentType: false,
        processData: false
      })
      .done(function(data){ 
          
        console.log("callbak antes del envio:",data);
        return callBack(data);
         
      })
      .fail(function(jqXHR, textStatus, errorThrown){
          console.log( "Request failed: " ,textStatus ,jqXHR,errorThrown);
           
          return callBack({
            "error":"failed",
            "jqXHR":jqXHR,
            "textStatus":textStatus,
            "errorThrown":errorThrown,
          });
          
      }); 

}







export default peticiones




