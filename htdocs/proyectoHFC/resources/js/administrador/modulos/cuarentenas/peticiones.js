import errors from  "@/globalResources/errors.js"
import columnas from  "@/globalResources/tablas/columnas.js"

const peticiones = {}

peticiones.redirectTabs = function redirectTabs(identificador) {
    $('#tabsCuarentenasContent > .tab-pane').removeClass('show');
    $('#tabsCuarentenasContent > .tab-pane').removeClass('active');
    identificador.tab('show')  
}

peticiones.cargaListaCuarentenas = function cargaListaCuarentenas()
{
    let columnasCargar = peticiones.armandoColumnasCuarentenas()
  
    let idCuarentena = $("#display_filter_special").val()
    let jefatura =  $("#listaJefaturasCuarentenas").val()
    let reiteradas = ""
    let averiasp = $("#averiaspFiltro").val()
    let codmotv = $("#codigoMotvFiltro").val()
    let tipoEstado = $("#tipoEstadoFiltro").val()
    let segunColor = $("#segunColorFiltro").val()

    if( $('#reiteradasFilter').prop('checked') ) {
        reiteradas = $('#reiteradasFilter').val()
    }
     
    let filtros = {
        idCuarentena,
        jefatura,
        reiteradas,
        averiasp,
        codmotv,
        tipoEstado,
        segunColor
    }

    //console.log("los valores a enviar son: ",filtros)

    peticiones.loadCuarentena(columnasCargar,filtros)
}

peticiones.armandoColumnasCuarentenas = function armandoColumnasCuarentenas()
{       
        let columnasContent =  [ 
                                {data: 'item'},
                                {data: 'jefatura'},
                                /*{data: 'jefatura', render: function(data,type,row){
                                    return `${data}-${row.st}`
                                }},*/
                                {data: 'IDCLIENTECRM'},
                                {data: 'situacion'},
                                {data: 'NAMECLIENT', render: function(data,type,row){
                                        return `${data} <strong>${row.entidad}</strong>`
                                }},
                                {data: 'codigoreq'},
                                {data: 'tecnico'},
                                {data: 'fecha_liquidacion'},
                                {data: 'averiarm'},
                                {data: 'cmts'},
                                {data: 'interface'},
                                {data: 'NODO'},
                                {data: 'TROBA'},
                                {data: 'macstate'},
                                {data: 'USPwr'},//12
                                {data: 'USMER_SNR'},
                                {data: 'DSMER_SNR'},
                                {data: 'DSPwr'},
                                {data: 'RxPwrdBmv'},
                                {data: 'MACADDRESS'},
                                {data: 'STATUS'}, 
                                {data:  null, render: function(data,type,row){
                                      //  console.log("el cliente gestion de carentena es: ",row.clienteGestionCuarentena)
 
                                    let dato = ""
                                    if (row.clienteGestionCuarentena.length > 0) {
                                            if (row.clienteGestionCuarentena[0].observaciones != "" &&  
                                                row.clienteGestionCuarentena[0].tipoaveria != 0) {
                                                    let gestionC = row.clienteGestionCuarentena[0]
                                                    let parametrosGestion = { 
                                                                    'estadoText':gestionC.tipoaveria,
                                                                    'observacionesText':gestionC.observaciones,
                                                                    'usuarioText':gestionC.usuario,
                                                                    'fechahoraText':gestionC.fechahora,
                                                                    'estadoColor':row.tituloColorEstadoGestion,
                                                                    'observacionesColor':row.contenidoColorEstadoGestion,
                                                                    'usuarioColor':row.usuarioColorEstadoGestion,
                                                                    'fechahoraColor':row.fechaColorEstadoGestion
                                                            
                                                    } 
                                                    dato = columnas.armandoEstadoGestionHtml(parametrosGestion) 
                                            }
                                    }

                                    return dato
                                }},
                                {data: null,render: function(data,type,row){
                                    return `<a href="javascript:void(0)" class="btn btn-sm gestionarAveriaCuarentena" data-uno="${row.IDCLIENTECRM}" 
                                                style="color:${row.gestionRegistroColor};" alt="Gestionar Avería Cuarentena" 
                                                title="Gestionar Avería Cuarentena">
                                                 <i class="icofont-list icofont-2x"></i>
                                            </a>`
                                }}
                               
                             ]
      
         
        return columnasContent
}


peticiones.loadCuarentena = function loadCuarentena(COLUMNAS_CUARENTENAS,filtros)
{

        $("#resultCuarentenasList tbody").html("");
        $("#filtroContentGCuarentenas").css({"display":"none"})

        $("#display_filter_special").prop("disabled", true); 

        //let filtro

        if ($("#filtroCuadroMando").length) {
                //filtroDashboard = true
                $("#resultCuarentenasList").DataTable({
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
                                titleAttr: 'DESCARGAS AVERIAS CUARENTENAS',
                                action: function ( e, dt, node, config ) {
                                    $("#descargasCuarentenasModal").modal("show");
                                }
                            }
                        ],
                        "ajax": {  
                                'url':`/administrador/cuarentena/${filtros.idCuarentena}/lista`,
                                "type": "GET", 
                                "data": function ( d ) {
            
                                        d.averiasp = filtros.averiasp;
                                        d.reiteradas = filtros.reiteradas;
                                        d.filtroJefatura = filtros.jefatura;
                                        d.codmotv = filtros.codmotv;
                                        d.tipoEstado = filtros.tipoEstado;
                                        d.segunColor = filtros.segunColor;
                                       // d.estado = filtros.estado;
                                       // d.tipoCaida = parametersDataAverias.tipoCaidas;
                                       // d.nodo = parametersDataAverias.nodo;
                                        /*d.num_puer = des_puer;*/
                                },/*
                                'dataSrc': function(json){
                                        //console.log("Termino la carga asi tenga error.. :",json)
                                
                                            //return json
                                                let result = json.data
                                            //  console.log("El result es: ",result)
            
                                                
                                                
                                                //console.log("La data procesada final... es: ",result)
            
                                                return dataProcesada  
                                        
                                    
                                },*/
                                'error': function(jqXHR, textStatus, errorThrown)
                                {  
            
                                         console.log( "Error: " ,jqXHR, textStatus, errorThrown); 
                                       
                                        //alert("Se generó un error con la petición, Se intentará traer nuevamente.")
                                        $("#display_filter_special").prop("disabled", false); 
            
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
                        "columns": COLUMNAS_CUARENTENAS,
                        'columnDefs': [ 
                                {
                                    'targets': '_all',
                                    'createdCell':  function (td, cellData, rowData, row, col) { 
                                        
                                        $(td).css({"background":`${rowData.background}`,"color":`${rowData.colorText}`}); 
                                        ///$(td).addClass("text-center")
                                         //console.log("los cells: ",td, cellData, rowData, row, col)
              
                                      
                                        if (col == 12) {
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
                                        } 
            
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

        } else {
                //filtroDashboard = false
        $("#resultCuarentenasList").DataTable({
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
                    titleAttr: 'DESCARGAS AVERIAS CUARENTENAS',
                    action: function ( e, dt, node, config ) {
                        $("#descargasCuarentenasModal").modal("show");
                    }
                }, 
                {
                    text: 'FILTROS',
                    className: 'btn btn-sm btn-info shadow-sm',
                    titleAttr: 'FILTROS AVERIAS CUARENTENAS',
                    action: function ( e, dt, node, config ) {
                        $("#filtroContentCuarentenas").slideToggle()
                    }
                }
                
            ],
            "ajax": {  
                    'url':`/administrador/cuarentena/${filtros.idCuarentena}/lista`,
                    "type": "GET", 
                    "data": function ( d ) {

                            d.averiasp = filtros.averiasp;
                            d.reiteradas = filtros.reiteradas;
                            d.filtroJefatura = filtros.jefatura;
                            d.codmotv = filtros.codmotv;
                            d.tipoEstado = filtros.tipoEstado;
                            d.segunColor = filtros.segunColor;
                           // d.estado = filtros.estado;
                           // d.tipoCaida = parametersDataAverias.tipoCaidas;
                           // d.nodo = parametersDataAverias.nodo;
                            /*d.num_puer = des_puer;*/
                    },/*
                    'dataSrc': function(json){
                            //console.log("Termino la carga asi tenga error.. :",json)
                    
                                //return json
                                    let result = json.data
                                //  console.log("El result es: ",result)

                                    
                                    
                                    //console.log("La data procesada final... es: ",result)

                                    return dataProcesada  
                            
                        
                    },*/
                    'error': function(jqXHR, textStatus, errorThrown)
                    {  

                             console.log( "Error: " ,jqXHR, textStatus, errorThrown); 
                           
                            //alert("Se generó un error con la petición, Se intentará traer nuevamente.")
                            $("#display_filter_special").prop("disabled", false); 

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
            "columns": COLUMNAS_CUARENTENAS,
            'columnDefs': [ 
                    {
                        'targets': '_all',
                        'createdCell':  function (td, cellData, rowData, row, col) { 
                            
                            $(td).css({"background":`${rowData.background}`,"color":`${rowData.colorText}`}); 
                            ///$(td).addClass("text-center")
                             //console.log("los cells: ",td, cellData, rowData, row, col)
  
                          
                            if (col == 12) {
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
                            } 

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

        }


        $("#resultCuarentenasList").parent().addClass("table-responsive tableFixHead") 
        // $("#filtroContentHFC").removeClass("d-none")

        let tablaHead = $('.tableFixHead').find('thead th')
        $('.tableFixHead').on('scroll', function() {
        // console.log("ejecutando"+this.scrollTop); 
        tablaHead.css('transform', 'translateY('+ this.scrollTop +'px)'); 
        }); 
}

if (GESTION_PERMISO) {
    peticiones.loadHistoricoGestiónCusrentena = function loadHistoricoGestiónCusrentena(idClienteCrm)
    {
 
            $("#resultHistoricoGestionCuarentena").DataTable({
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
                        'url':`/administrador/cuarentenas-general/gestion-individual/lista`,
                        "type": "GET", 
                        "data": function ( d ) {

                                d.idClienteCrm =  idClienteCrm;
                                
                        }, 
                        'error': function(jqXHR, textStatus, errorThrown)
                        {  

                                console.log( "Error: " ,jqXHR, textStatus, errorThrown); 
                            
                                //alert("Se generó un error con la petición, Se intentará traer nuevamente.")
                                

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
                "columns": [
                    {data:'fechahora'},
                    {data:'idcliente'},
                    {data:'observaciones'},
                    {data:'tipoaveria'},
                    {data:'usuario'} 

                ],
                'columnDefs': [ 
                        {
                            
                            "targets": '_all',
                            "orderable" : false,
                            "searchable": false,
                                
                        } 
                ] ,
                "initComplete": function(){
                    // console.log("Termino la carga completa")
                       
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
                            '<option value="300">300</option>'+
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


            $("#resultHistoricoGestionCuarentena").parent().addClass("table-responsive tableFixHead") 
            // $("#filtroContentHFC").removeClass("d-none")

            let tablaHead = $('.tableFixHead').find('thead th')
            $('.tableFixHead').on('scroll', function() {
            // console.log("ejecutando"+this.scrollTop); 
            tablaHead.css('transform', 'translateY('+ this.scrollTop +'px)'); 
            }); 
    }
}


export default peticiones