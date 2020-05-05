import errors from  "@/globalResources/errors.js"
const peticiones = {}

peticiones.redirectTabs = function redirectTabs(identificador) {
    $('#tabsAgendasContent > .tab-pane').removeClass('show');
    $('#tabsAgendasContent > .tab-pane').removeClass('active');
    identificador.tab('show')  
}

peticiones.resetInterval = function resetInterval(){
    if (INTERVAL_LOAD != null) {
            clearInterval(INTERVAL_LOAD)
            //console.log("Se limpio el interval y se debe iniciar nuevamente...")
            INTERVAL_LOAD = setInterval(() => { 

                    if (ESTA_ACTIVO_REFRESH) { 
                        if ($( ".listaAgendas" ).hasClass( "active" )) {
                            peticiones.cargaListaAgendas()
                        } 
                    }
            
            }, 60000); 
    }
}

peticiones.cargaListaAgendas = function cargaListaAgendas()
{
    let  columnasCargar = peticiones.armandoColumnasAgendas()
    let  codigoCliente= $("#filtroCodClienteBasic").val()
    let  estado= $("#filtroEstadoBasic").val()
    
    if( $('#reiteradasFilter').prop('checked') ) {
        reiteradas = $('#reiteradasFilter').val()
    }
     
    let filtros = {
        estado,
        codigoCliente
    }

    //console.log("los valores a enviar son: ",filtros)

    peticiones.loadAgendaCompleta(columnasCargar,filtros)
}

peticiones.armandoColumnasAgendas = function armandoColumnasAgendas()
{        
        let columnasContent =  [ 
                                {data: 'id'},
                                {data: 'codcli'},
                                {data: 'codserv'},
                                {data: 'nodo'},
                                {data: 'telefono1'},
                                {data: 'telefono2'},
                                {data: 'nameclient'},
                                {data: 'codreq'},
                                {data: 'fecha'},
                                {data: 'turno'},
                                {data: 'tipoturno'},
                                {data: 'estado'},
                                {data: 'quiebre'},
                                {data: 'fecharegistroagenda'},
                                {data: 'comentario'} 
                             ]
      
         if (GESTION_PERMISO) {
            columnasContent.push(
                {data: null, render: function(data,type,row){
                    return `<a href="javascript:void(0)" class="btn btn-sm gestionAgenda" data-uno="${row.id}"><i class="icofont-list icofont-2x"></i></a>`
                }}//Edicion
            )
         }
        return columnasContent
}

peticiones.loadAgendaCompleta = function loadAgendaCompleta(columnasCargar,filtros)
{
 

    if (REFRESH_PERMISO) {
            ESTA_ACTIVO_REFRESH = false
    }

    $("#resultAgendasLista").DataTable({
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
                titleAttr: 'DESCARGAS AGENDAS',
                action: function ( e, dt, node, config ) {
                    $("#descargarAgendasModal").modal("show");
                }
            }, 
            {
                text: 'FILTROS',
                className: 'btn btn-sm btn-info shadow-sm',
                titleAttr: 'FILTROS     agenda',
                action: function ( e, dt, node, config ) {
                    $("#filtroContentAgenda").slideToggle()
                }
            }
        ],
        "ajax": {  
                'url':`/administrador/agendas/lista`,
                "type": "GET", 
                "data": function ( d ) {

                         d.estado = filtros.estado;
                         d.codigoCliente = filtros.codigoCliente;
                        //d.filtroJefatura = filtros.jefatura; 
                },
                'error': function(jqXHR, textStatus, errorThrown)
                {  

                         console.log( "Error: " ,jqXHR, textStatus, errorThrown); 
                       
                        //alert("Se generó un error con la petición, Se intentará traer nuevamente.")
                      
                        if (REFRESH_PERMISO) {
                                ESTA_ACTIVO_REFRESH = true
                                peticiones.resetInterval()
                        }

                        //$("#body-errors-modal").html(jqXHR.responseText)
                        //$('#errorsModal').modal('show')
                        //return false

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
        "columns": columnasCargar,
        'columnDefs': [ 
                {
                    'targets': '_all',
                    'createdCell':  function (td, cellData, rowData, row, col) { 
                        
                        //$(td).css({"background":`${rowData.background}`,"color":`${rowData.colorText}`}); 
                        ///$(td).addClass("text-center")
                         //console.log("los cells: ",td, cellData, rowData, row, col)

                      
                       /* if (col == 12) {
                           // console.log("El background deberia se: ,",td, cellData, rowData, row, col, rowData.backgroundUSPwr,"---",rowData.colorUSPwr)
                                $(td).css({"background":`${rowData.backgroundUSPwr}`,"color":`${rowData.colorUSPwr}`});  
                        }
                        if (col == 13) {
                                $(td).css({"background":`${rowData.backgroundUSMER_SNR}`,"color":`${rowData.colorUSMER_SNR}`});       
                        }
                        if (col == 14) {
                                $(td).css({"background":`${rowData.backgroundsnrdn}`,"color":`${rowData.colorsnrdn}`});       
                        }
                        if (col == 15) {
                                $(td).css({"background":`${rowData.backgroundDSPwr}`,"color":`${rowData.colorDSPwr}`});       
                        } */

                    }
                }/*,
                {
                    
                    "targets": '_all',
                // "orderable" : false,
                    "searchable": false,
                        
                } */
        ] ,
        "initComplete": function(){
            // console.log("Termino la carga completa")
            $("#filtroContentAgenda").css({'display':'none'})
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

    $("#resultAgendasLista").parent().addClass("table-responsive tableFixHead") 
        // $("#filtroContentHFC").removeClass("d-none")

        let tablaHead = $('.tableFixHead').find('thead th')
        $('.tableFixHead').on('scroll', function() {
        // console.log("ejecutando"+this.scrollTop); 
        tablaHead.css('transform', 'translateY('+ this.scrollTop +'px)'); 
        }); 

    
}

  
export default peticiones