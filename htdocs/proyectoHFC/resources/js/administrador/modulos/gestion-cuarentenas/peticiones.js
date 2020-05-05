import errors from  "@/globalResources/errors.js"

const peticiones = {}

peticiones.redirectTabs = function redirectTabs(identificador) {
    $('#tabsGestionCuarentenasContent > .tab-pane').removeClass('show');
    $('#tabsGestionCuarentenasContent > .tab-pane').removeClass('active');
    identificador.tab('show')  
}

peticiones.cargaListaGestionCuarentenas = function cargaListaGestionCuarentenas()
{
    let columnasCargar = peticiones.armandoColumnasCuarentenas()

    let jefatura = $("#listaJefaturasGCuarentenas").val()
    let estado = $("#listaEstadosGCuarentenas").val()

    let filtros = {
        jefatura,
        estado
    }

    peticiones.loadGestionCuarentena(columnasCargar,filtros)
}

peticiones.armandoColumnasCuarentenas = function armandoColumnasCuarentenas()
{
        let columnasContent =  [ 
                                {data: 'id'},
                                {data: 'nombre'},
                                //{data: 'nodo'},
                               // {data: 'troba'},
                                {data: 'jefatura'},
                                
                                {data: 'clientes', render: function(data,type,row){
                                    if (data > 0) {
                                        return `<a href="javascript:void(0)" class="d-block text-center verDetalleClientesC" data-uno="${row.id}" data-dos="${row.nombre}">${data}</a>`
                                    }else{
                                        return "<span> </span>"
                                    }
                                }},
                                {data: 'trobas', render: function(data,type,row){
                                    if (data > 0) {
                                        return `<a href="javascript:void(0)" class="d-block text-center verDetalleTrobasC" data-uno="${row.id}" data-dos="${row.nombre}">${data}</a>`
                                    }else{
                                        return "<span> </span>"
                                    }
                                }},
                                {data: 'servicePackageCrmid'},
                                {data: 'scopesGroup'},
                                {data: 'estado'},
                                {data: 'cuadroMando'},
                                {data: 'tipo'},
                                {data: 'fechaInicio'},
                                {data: 'fechaFin'},
                                {data: 'fechaRegistro'}
                             ]
        if (EDITAR_CUARENTENA_PERMISO || ELIMINAR_CUARENTENA_PERMISO) {
                columnasContent.push({data: 'null', render: function(data,type,row){
                        let estructuraButtons = ``
                        if (EDITAR_CUARENTENA_PERMISO &&  row.clientes == 0) {
                            estructuraButtons += `<button data-uno="${row.id}" data-dos="${row.jefatura}" 
                                                                data-tres="${row.nombre}" data-cuatro="${row.servicePackageCrmid}"
                                                                data-cinco="${row.scopesGroup}" data-seis="${row.fechaInicio}"
                                                                data-siete="${row.fechaFin}" data-ocho="${row.estado}" 
                                                                data-nueve="${row.cuadroMando}" data-diez="${row.tipo}"
                                                        class="btn btn-sm btn-outline-primary shadow-sm m-1 editarCuarentenaGestion">
                                                                <i class="icofont-edit-alt icofont-md"></i>
                                                </button>`
                        }
                        if (ELIMINAR_CUARENTENA_PERMISO) {
                            estructuraButtons += `<button data-uno="${row.id}" 
                                                        class="btn btn-sm btn-outline-danger shadow-sm m-1  eliminarCuarentenaGestion">
                                                                <i class="icofont-ui-delete icofont-md"></i>
                                                </button>`
                        }

                        return estructuraButtons
                }})
        }
       
         
        return columnasContent
}


peticiones.loadGestionCuarentena = function loadGestionCuarentena(COLUMNAS_CUARENTENAS,filtros)
{
        $("#filtroContentGCuarentenas").css({"display":"none"})

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
                /*{
                    text: 'DESCARGAS',
                    className: 'btn btn-sm btn-success shadow-sm',
                    titleAttr: 'DESCARGAS GESTION CUARENTENAS',
                    action: function ( e, dt, node, config ) {
                        //alert( 'Button Opciones HFC' );
                        //console.log("opciones:", e, dt, node, config)
                        $("#descargasHfcModal").modal("show");
                    }
                },*/
                {
                    text: 'FILTROS',
                    className: 'btn btn-sm btn-info shadow-sm',
                    titleAttr: 'FILTROS GESTION CUARENTENAS',
                    action: function ( e, dt, node, config ) {
                        $("#filtroContentGCuarentenas").slideToggle()
                    }
                }
            ],
            "ajax": {  
                    'url':'/administrador/gestion-cuarentena/lista',
                    "type": "GET", 
                    "data": function ( d ) {

                            d.jefatura = filtros.jefatura;
                            d.estado = filtros.estado;
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

                            // console.log( "Error: " ,jqXHR, textStatus, errorThrown); 
                           
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
            "columns": COLUMNAS_CUARENTENAS,
            'columnDefs': [ 
                    {
                        
                        "targets": '_all',
                    // "orderable" : false,
                        "searchable": false,
                            
                    } 
            ] ,
            "initComplete": function(){
                // console.log("Termino la carga completa")
                    
            },
            "pageLength": 50,
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


        $("#resultCuarentenasList").parent().addClass("table-responsive tableFixHead") 
        // $("#filtroContentHFC").removeClass("d-none")

        let tablaHead = $('.tableFixHead').find('thead th')
        $('.tableFixHead').on('scroll', function() {
        // console.log("ejecutando"+this.scrollTop); 
        tablaHead.css('transform', 'translateY('+ this.scrollTop +'px)'); 
        }); 
}

peticiones.cargaGestionCuarentenasClientes = function cargaGestionCuarentenasClientes(identificadorCuarentena)
{
          
        $("#resultCuarentenasClientesList").DataTable({
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
                    'url':`/administrador/gestion-cuarentena/${identificadorCuarentena}/clientes`,
                    "type": "GET", 
                    "data": function ( d ) {

                           // d.jefatura = filtros.jefatura;
                           // d.estado = filtros.estado;
                           // d.tipoCaida = parametersDataAverias.tipoCaidas;
                           // d.nodo = parametersDataAverias.nodo;
                            /*d.num_puer = des_puer;*/
                    },
                    'error': function(jqXHR, textStatus, errorThrown)
                    {  

                            // console.log( "Error: " ,jqXHR, textStatus, errorThrown); 
                           
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
                    
                            peticiones.redirectTabs($('#cuarentenaListaTab')) 

                            $("#body-errors-modal").html(`<div class="text-danger">${erroresPeticion}</div>`)
                            $('#errorsModal').modal('show')
                            return false

                    }
            }, 
            "columns": [
                {data:'id'},
                {data:'idCliente'},
                {data:'jefatura'},
                {data:'nodo'},
                {data:'troba'},
                {data:'servicePackageCrmid'},
                {data:'scopesGroup'}
            ],
            'columnDefs': [ 
                    {
                        
                        "targets": '_all',
                    // "orderable" : false,
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


        $("#resultCuarentenasClientesList").parent().addClass("table-responsive tableFixHead") 
        // $("#filtroContentHFC").removeClass("d-none")

        let tablaHead = $('.tableFixHead').find('thead th')
        $('.tableFixHead').on('scroll', function() {
        // console.log("ejecutando"+this.scrollTop); 
        tablaHead.css('transform', 'translateY('+ this.scrollTop +'px)'); 
        }); 
}

peticiones.cargaGestionCuarentenasTrobas = function cargaGestionCuarentenasTrobas(identificadorCuarentena)
{
          
        $("#resultCuarentenasTrobasList").DataTable({
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
                    'url':`/administrador/gestion-cuarentena/${identificadorCuarentena}/trobas`,
                    "type": "GET", 
                    "data": function ( d ) {

                           // d.jefatura = filtros.jefatura;
                           // d.estado = filtros.estado;
                           // d.tipoCaida = parametersDataAverias.tipoCaidas;
                           // d.nodo = parametersDataAverias.nodo;
                            /*d.num_puer = des_puer;*/
                    },
                    'error': function(jqXHR, textStatus, errorThrown)
                    {  

                            // console.log( "Error: " ,jqXHR, textStatus, errorThrown); 
                           
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
                    
                            peticiones.redirectTabs($('#cuarentenaListaTab')) 

                            $("#body-errors-modal").html(`<div class="text-danger">${erroresPeticion}</div>`)
                            $('#errorsModal').modal('show')
                            return false

                    }
            }, 
            "columns": [
                {data:'id'},
                {data:'nodo'},
                {data:'troba'}
            ],
            'columnDefs': [ 
                    {
                        "targets": '_all',
                    // "orderable" : false,
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


        $("#resultCuarentenasTrobasList").parent().addClass("table-responsive tableFixHead") 
        // $("#filtroContentHFC").removeClass("d-none")

        let tablaHead = $('.tableFixHead').find('thead th')
        $('.tableFixHead').on('scroll', function() {
        // console.log("ejecutando"+this.scrollTop); 
        tablaHead.css('transform', 'translateY('+ this.scrollTop +'px)'); 
        }); 
}

peticiones.cargaTrobasProjefatura = function cargaTrobasProjefatura(jefatura,callBack)
{

    $.ajax({
        url:`/administrador/gestion-cuarentena/jefatura-trobas`,
        method:"get",
        async: true,
        data:{
            jefatura
        },
       cache: false, 
       dataType: "json", 
      })
      .done(function(data){ 
        //console.log("callbak antes del envio:",data)
     
        return callBack(data);
         
      })
      .fail(function(jqXHR, textStatus, errorThrown){
         // console.log( "Request failed: " ,textStatus ,jqXHR,errorThrown);
           
          return callBack({
            "error":"failed",
            "jqXHR":jqXHR,
            "textStatus":textStatus,
            "errorThrown":errorThrown,
          });
          
      }); 
 

}

peticiones.loadArchivoServicio = function loadArchivoServicio(formData,callBack){

        $.ajax({
            url:`/administrador/gestion-cuarentena/store-file`,
            //xhrFields: { responseType: 'blob', },
            method:"POST",
            async: true,
            data:formData,
            cache: false, 
            contentType: false,
            processData: false
          })
          .done(function(data){ 
            //console.log("callbak antes del envio:",data)
         
            return callBack(data);
             
          })
          .fail(function(jqXHR, textStatus, errorThrown){
             // console.log( "Request failed: " ,textStatus ,jqXHR,errorThrown);
               
              return callBack({
                "error":"failed",
                "jqXHR":jqXHR,
                "textStatus":textStatus,
                "errorThrown":errorThrown,
              });
              
          }); 
    
    }

export default peticiones