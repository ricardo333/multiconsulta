import errors from  "@/globalResources/errors.js"

const peticiones = {}

peticiones.redirectTabs = function redirectTabs(identificador) {
    $('#tabsTrabajosPContent > .tab-pane').removeClass('show');
    $('#tabsTrabajosPContent > .tab-pane').removeClass('active');
    identificador.tab('show')  
}

peticiones.armandoColumnasTP = function armandoColumnasTP()
{
    let columnasTemporales = []

    let APER_CERR_CANC_COLUMNA_PERMISO = APERTURAR_TP_PERMISO || CERRAR_TP_PERMISO || CANCELAR_TP_PERMISO
  

    if (APER_CERR_CANC_COLUMNA_PERMISO || GESTION_INDIV_PERMISO || DESCARGAR_CLIENTES_PERMISO ) {
        columnasTemporales.push({data: 'id' ,render: function(data,type,row){
            let estructuraAcciones = ``
            if (APER_CERR_CANC_COLUMNA_PERMISO) {
                if ( (row.ESTADO == "CERRADO" ||  row.ESTADO == "ENPROCESO" ) && GESTION_INDIV_PERMISO) {
                    //estructuraAcciones += `<a href="javascript:void(0)" class="btn btn-sm btn-outline-secondary shadow-sm">Gestion</a>`
                    estructuraAcciones += `<a href="javascript:void(0)" class="btn btn-sm btn-light p-0 gestionarTrabajoProg" 
                                                data-uno="${row.NODO}" data-dos="${row.TROBA}" data-tres="0"
                                                data-cuatro="" data-cinco="${row.codreqmnt}" data-seis="Liquidacion"
                                                data-siete=${row.ITEM} style="color:${row.gestionRegistroColor};" alt="Gestionar Trabajo Programado" title="Gestionar Trabajo Programado">
                                                    <i class="icofont-list icofont-2x"></i>    
                                            </a>`
                    
                }
                if (row.ESTADO == "PENDIENTE") {
                        if (APERTURAR_TP_PERMISO) {
                            estructuraAcciones += ` <a href="javascript:void(0)" class="btn shadow-sm p-0 aperturarTP" data-uno="${row.ITEM}">
                                                        <img class="img" src="/images/icons/apertura.png" />
                                                    </a>`
                        }
                        if (CANCELAR_TP_PERMISO) {
                            estructuraAcciones += `<a href="javascript:void(0)" class="btn shadow-sm p-0 cancelarTP" data-uno="${row.ITEM}">
                                                    <img class="img" src="/images/icons/cancelar.png" />
                                                    </a>`
                        }  

                }
                if (row.ESTADO == "ENPROCESO") {
                    if (CERRAR_TP_PERMISO) {
                        estructuraAcciones += ` <a href="javascript:void(0)" class="btn shadow-sm p-0 cerrarTP" data-uno="${row.ITEM}">
                                                    <img class="img" src="/images/icons/cierre.png" />
                                                </a>`
                    }
                    if (DESCARGAR_CLIENTES_PERMISO) {
                        estructuraAcciones += `<button  class="btn shadow-sm p-0 descargarClienteTP" data-uno="${row.ITEM}" 
                                                        data-dos="${row.NODO}" data-tres="${row.TROBA}" style="color:${row.colorIcon};" alt="Descargar Clientes de TP" title="Descargar Clientes de TP">
                                                            <i class="icofont-file-excel icofont-2x"></i>
                                                </button>`
                    }    
                }
                if (row.ESTADO == "CERRADO" && DESCARGAR_CLIENTES_PERMISO) {
                    estructuraAcciones += `<button  class="btn shadow-sm p-0 descargarClienteTP" data-uno="${row.ITEM}" 
                                                    data-dos="${row.NODO}" data-tres="${row.TROBA}" style="color:${row.colorIcon};" alt="Descargar Clientes de TP" title="Descargar Clientes de TP">
                                                        <i class="icofont-file-excel icofont-2x"></i>
                                            </button>`
                }
            }

            return estructuraAcciones
            
        }})
    }

    columnasTemporales.push( 
        {data:'ITEM'},
        {data:'iconoCalls', render: function(data,type,row){
                let btnCall = ``
                    if (row.calls != null) {
                        btnCall += `<button class="descargarLlamadasPorNodo" data-uno="${row.NODO}" 
                                                data-dos="${row.TROBA}" data-tres="${row.fechattpp}">
                                        ${  row.calls}
                                    </button>`
                    }
                return `
                    ${btnCall}
                    <button class="bg-transparent border-0 graficaLlamadasTroba" data-uno="${row.NODO}" data-dos="${row.TROBA}">
                        <img src="/images/icons/trabajos-programados/${data}" alt='Seguir' width=20 height=20 border=0>
                    </button>
                `
        }},
        {data:null, render: function(data,type,row){
            if (row.cpend != null) {
                return `
                            <button class="d-block m-auto descargarLlamadaNodoAverias" data-uno="${row.NODO}" 
                                        data-dos="${row.TROBA}" data-tres="${row.fechattpp}" data-cuatro="1">
                                ${row.cpend == null ? "" : row.cpend}
                            </button>
                        `
            }else{
                return ``
            }
                
        }},
        {data:'NODO', render: function(data,type,row) {
                return `${row.NODO} - ${row.TROBA}`
        }},
        {data:'jefatura', render: function(data,type,row) {
            return `${data == null? "" : data}`
        }},
        {data:'AMP'},
        {data:'TIPODETRABAJO'},
        {data:'SUPERVISORTDP'},
        {data:'USUARIOREGISTRO'},
        {data:'FINICIO', render: function(data,type,row) {
            return `${row.FINICIO} De:  ${row.HINICIO} a ${row.HTERMINO}`
        }},
        {data:'CORTESN'},
        {data:'ESTADO'},
        {data:'FECHAREGISTRO'},
        {data:'HORAREGISTRO'},
        {data:'TIPODETRABAJO'},
        {data:'REMEDY'},
        {data:'NOMBRETECNICOAPERTURA'},
        {data:'CELULARSUPERVISORCONTRATA'},
        {data:'CONTRATAAPERTURA'},
        {data:'OBSERVACIONREGISTRO'},
        {data:'FECHAREGISTRO'},
        {data:'FECHAAPERTURA'},
        {data:'IMAGENAPERTURA', render: function(data,type,row){
            if (data != "" && data != null) {
                return `<img class="imagenes_ttpp_aper_cierre" src="/images/upload/trabajos-programados/${row.IMAGENAPERTURA}"
                            data-uno="/images/upload/trabajos-programados/${row.IMAGENAPERTURA}" data-dos="Imagen de Apertura"
                            alt="Detalle Imagen Apertura" title="Detalle Imagen Apertura"
                        />  `	
            }else{
                return ``; 
            }
        }},
        {data:'IMAGENCIERRE', render: function(data,type,row){
            if (data != "" && data != null) {
                return `<img class="imagenes_ttpp_aper_cierre" src="/images/upload/trabajos-programados/${row.IMAGENCIERRE}"
                            data-uno="/images/upload/trabajos-programados/${row.IMAGENCIERRE}" data-dos="Imagen de Cierre"
                            alt="Detalle Imagen Apertura" title="Detalle Imagen Apertura"
                        />  `	
            }else{
                return ``; 
            }
        }}
    )

     return columnasTemporales
}

peticiones.loadTrabajosProgramadosList = function loadTrabajosProgramadosList(COLUMNAS,parametros)
{
 
    //$('#resultTrabajosProg').DataTable().destroy();
     
    $("#resultTrabajosProg").DataTable({
        "destroy": true,
        "processing": true, 
        "serverSide": true,
        "dom":'<"row mx-0"'
                    +'<"col-12 col-sm-6"l><"col-12 col-sm-6 text-right"B>>'
                +'<"row"'
                    +'<"col-sm-12 px-0 table-responsive"t>>'
                +'<"row"'
                    +'<"col-12 col-sm-5"i><"col-12 col-sm-7"p>>'
                +'r',
        "buttons":[ 
            {
                text: 'DESCARGAS',
                className: 'btn btn-sm btn-success shadow-sm',
                titleAttr: 'DESCARGAS EN TRABAJOS PROGRAMAODS',
                action: function ( e, dt, node, config ) { 
                     $("#descargasTPModal").modal("show");
                }
            },
            {
                text: 'FILTROS',
                className: 'btn btn-sm btn-info shadow-sm',
                titleAttr: 'FILTROS POR ZONAS',
                action: function ( e, dt, node, config ) {
                    
                    $("#contentZonasFiltro").slideToggle()
                }
            }
        ],
        "ajax": {  
            'url':'/administrador/trabajos-programados/lista',
            "type": "GET", 
            "data": function ( d ) { 
                    d.jefatura = parametros.jefatura; 
                    d.estado = parametros.estado; 
            }/*,
            'dataSrc': function(json){
               
                    let result = json.data
  
                  console.log("La data retornada es: ",result)
 
               //return result
                
                
            }*/, 
            'error': function(jqXHR, textStatus, errorThrown)
            {   
                //$("#body-errors-modal").html(jqXHR.responseText)
                //$('#errorsModal').modal('show')
                //return false
               
                let erroresPeticion =""
                            
                if(jqXHR.status){
                        let mensaje = errors.codigos(jqXHR.status)
                        erroresPeticion = mensaje
                       // console.log("el mensaje re error codigos es: ",erroresPeticion)
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
        "columns": COLUMNAS,
        'columnDefs': [
                        {
                            'targets': '_all',
                            'createdCell':  function (td, cellData, rowData, row, col) { 
                                    //console.log(td, cellData, rowData, row, col)
                                    $(td).css({"background":`${rowData.background}`,"color":`${rowData.color}`}); 
                                     
                            }
                        },
                        { 
                            "targets": '_all',
                        // "orderable" : false,
                            "searchable": false
                        } 
                ], 
        "initComplete": function(){
               // console.log("Termino la carga completa")

               $("#resultTrabajosProg").parent().addClass("table-responsive tableFixHead")  
                
               let tablaHead = $('.tableFixHead').find('thead th')
               let primera_col = $('.tableFixHead tbody tr td:nth-child(1)')
           
               $('.tableFixHead').on('scroll', function() {
               // console.log("ejecutando"+this.scrollTop); 
                   primera_col.css({'transform':'translateX('+ this.scrollLeft +'px)'});
                   tablaHead.css('transform', 'translateY('+ this.scrollTop +'px)'); 
               }); 
              
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

   
 
}

peticiones.armaEstructuraDetalleTP = function armaEstructuraDetalleTP(resultado)
{

    let estructura = `
                    <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                        <label for="itemDetalle" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">ITEM: </label>
                        <div class="col-sm-7 col-md-8 form-control form-control-sm">${resultado.ITEM}</div>
                    </div>
                    <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                        <label for="nodoDetalle" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">NODO : </label>
                        <div class="col-sm-7 col-md-8 form-control form-control-sm">${resultado.NODO}</div>
                    </div>
                    <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                        <label for="trobaDEtalle" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">TROBA : </label>
                        <div class="col-sm-7 col-md-8 form-control form-control-sm">${resultado.TROBA}</div>
                    </div>
                    <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                        <label for="amplificadorDetalle" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">AMP : </label>
                        <div class="col-sm-7 col-md-8 form-control form-control-sm">${resultado.AMP}</div>
                    </div>
                    <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                        <label for="tipoDeTrabajoDetalle" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">TIPODETRABAJO : </label>
                        <div class="col-sm-7 col-md-8 form-control form-control-sm">${resultado.TIPODETRABAJO}</div>
                    </div>
                    <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                        <label for="supervisorDetalle" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">SUPERVISOR : </label>
                        <div class="col-sm-7 col-md-8 form-control form-control-sm">${resultado.SUPERVISOR}</div>
                    </div>
                    <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                        <label for="FInicioDetalle" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">FINICIO : </label>
                        <div class="col-sm-7 col-md-8 form-control form-control-sm">${resultado.FINICIO}</div>
                    </div>
                    <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                        <label for="HInicioDetalle" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">HINICIO : </label>
                        <div class="col-sm-7 col-md-8 form-control form-control-sm">${resultado.HINICIO}</div>
                    </div>
                    <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                        <label for="HTerminoDetalle" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">HTERMINO : </label>
                        <div class="col-sm-7 col-md-8 form-control form-control-sm">${resultado.HTERMINO}</div>
                    </div>
                    <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                        <label for="horarioDetalle" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">HORARIO : </label>
                        <div class="col-sm-7 col-md-8 form-control form-control-sm">${resultado.HORARIO}</div>
                    </div>
                    <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                        <label for="corteDetalle" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">CORTESN : </label>
                        <div class="col-sm-7 col-md-8 form-control form-control-sm">${resultado.CORTESN}</div>
                    </div>
                    <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                        <label for="corteDetalle" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">OPERADOR : </label>
                        <div class="col-sm-7 col-md-8 form-control form-control-sm">${resultado.OPERADOR}</div>
                    </div>
                    <div class="form-group row mx-0 px-2 col-12">
                        <label for="observacionesDetalle" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">OBSERVACIONES AL CREAR: </label>
                        <div class="w-100">${resultado.OBSERVACION == "" || resultado.OBSERVACION == null ? "Sin observaciones." :  resultado.OBSERVACION }</div>
                    </div>
        `
    return estructura

}

export default peticiones