import errors from  "@/globalResources/errors.js"

const peticiones = {}


peticiones.redirectTabs = function redirectTabs(identificador) {
    $('#tabsDescargaClientesTrobaContent > .tab-pane').removeClass('show');
    $('#tabsDescargaClientesTrobaContent > .tab-pane').removeClass('active');
    identificador.tab('show')  
}

peticiones.cargaInterfaces = function cargaInterfaces(){
    
    $.ajax({
        url:`/administrador/descarga-clientes-troba/interfaces/list`,
        method:"get",
        dataType: "json", 
      })
      .done(function(data){
         //console.log(data) 
          
        let respuesta = data.response
        if (respuesta.cantidad > 0) {
            //console.log("hay mucha data y se procesará")
            let estructuraSelectMultiple = ``
            respuesta.lista.forEach(el => {
                estructuraSelectMultiple += `<option value="${el.interbus}">${el.interbus}</option>`
                DATA_INTERFACES.push(el.interbus)
            })
            
            $("#interfacesLista").html(estructuraSelectMultiple)
            // console.log("la data cargada de options son: ",DATA_INTERFACES)
        }else{
            $("#interfacesLista").html(`<option>Sin data disponible</option>`)
        } 

         $("#preloadFiltrosGenerales").html( ``)
         $("#preloadFiltrosGenerales").addClass("d-none")
         $("#contentFiltroClientTroba").removeClass("d-none")
 
      })
      .fail(function(jqXHR, textStatus){
        //console.log("error",jqXHR, textStatus)
        //$("#body-errors-modal").html(jqXHR.responseText)
        //$('#errorsModal').modal('show') 
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
        erroresPeticion = (erroresPeticion.trim() == 0) ? "hubo un error en el servicio, intente nuevamente recargando la web." : erroresPeticion

        $("#body-errors-modal").html(erroresPeticion)
        $('#errorsModal').modal('show') 

        return false

      })
}

peticiones.cantidadTrobasPorInterfaces = function cantidadTrobasPorInterfaces(interfaces){

    $("#listaCantidadTrobPuerto").modal("show")

    $("#resultadoCantidadListaTrobasP").html(`<div id="carga_person">
                                                <div class="loader">Loading...</div>
                                            </div>`)

    $.ajax({
        url:`/administrador/descarga-clientes-troba/interface/cantidad-trobas`,
        method:"get",
        data:{interfaces},
        dataType: "json", 
      })
      .done(function(data){
          
          let cantidadDataInterfaces = data.response.cantidad
          if (cantidadDataInterfaces > 0) {
              let dataInterfaces = data.response.lista

              let listaTrobasCountPuert = `<div class="table-responsive">`
                  listaTrobasCountPuert += `<table class="table table-bordered w-auto m-auto table-hover">`

                  listaTrobasCountPuert += `<thead>
                                              <tr>
                                                  <th>Nodo</th>
                                                  <th>Troba</th>
                                                  <th>Clientes</th>
                                              </tr>
                                          </thead>`
                  dataInterfaces.forEach(el => {
                      listaTrobasCountPuert += `<tbody>
                                                  <tr> 
                                                      <td>${el.nodo}</td>
                                                      <td>${el.troba}</td>
                                                      <td>${el.cant}</td>
                                                  </tr>
                                              </tbody>` 
                  })

                  listaTrobasCountPuert += `</table>`

                  listaTrobasCountPuert += `<div>`

                  $("#resultadoCantidadListaTrobasP").html(listaTrobasCountPuert) 
          }else{
            $("#resultadoCantidadListaTrobasP").html(`<div class="w-100 text-center text-danger">No se encontraron trobas en las interfaces enviadas.</div>`) 
          }
           
      })
      .fail(function(jqXHR, textStatus){
        //console.log("error",jqXHR, textStatus)
       //$("#resultadoCantidadListaTrobasP").html(jqXHR.responseText)
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
        erroresPeticion = (erroresPeticion.trim() == 0) ? "hubo un error en el servicio, intente nuevamente recargando la web." : erroresPeticion

        $("#resultadoCantidadListaTrobasP").html(`<div class="text-danger text-center w-100">${erroresPeticion}</div>`)
          
        return false

      })
}

peticiones.cargaPromedioNivelesCmtsPorPuerto = function cargaPromedioNivelesCmtsPorPuerto(tabla, ruta, puerto){

            tabla.DataTable({
                "destroy": true,
                "processing": true, 
                "serverSide": true,
                "dom":'<"row mx-0"'
                        +'<"col-12 col-sm-6"l>>'
                        +'<"row"'
                        +'<"col-sm-12 px-0 table-responsive tableFixHead"t>>'
                        +'<"row"'
                        +'<"col-12 col-sm-5"i><"col-12 col-sm-7"p>>'
                        +'r',
                "ajax": {  
                        'url':ruta,
                        "type": "GET", 
                        "dataType": "json", 
                        "data": function ( d ) {

                                d.puerto = puerto;
                              
                        },
                        'dataSrc': function(json){
                                //console.log("Termino la carga asi tenga error.. :",json)
                        
                                    //return json
                                        let result = json.data
                                    //  console.log("El result es: ",result)

                                         for (let index = 0; index < result.length; index++) {
                                                
                                            result[index].interfaceCol = `<a href="javascript:void(0)" data-uno="${result[index].cmts}${result[index].Interface}"  class="verHistoricoNivelesCmtsPorPuerto" alt="Historico de Niveles CMTS por puerto">
                                                                            ${result[index].Interface}
                                                                    </a>`
                                            result[index].descripcionCol = `<button  data-uno="${result[index].cmts}${result[index].Interface}"  class="verSnrCablemodem" alt="Cablemodem SNR">
                                                                            ${result[index].description}
                                                                    </button>`

                                         }

                                        return result  
                                
                            
                        },
                        'error': function(jqXHR, textStatus, errorThrown)
                        {  

                               console.log( "Error: " ,jqXHR, textStatus, errorThrown); 
                               $("#body-errors-modal").html(jqXHR.responseText)
                               $('#errorsModal').modal('show')
                               return false
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
                    {data:'cmts'},
                    {data:'interfaceCol'},
                    {data:'descripcionCol'},
                    {data:'powerup_max'},
                    {data:'powerup_prom'},
                    {data:'powerup_min'},
                    {data:'powerds_max'},
                    {data:'powerds_prom'},
                    {data:'powerds_min'},
                    {data:'snr_avg'},
                    {data:'fecha_hora'}
                ],
                'columnDefs': [
                        {
                            'targets': '_all',
                            'createdCell':  function (td, cellData, rowData, row, col) { 
                                    
                                    $(td).css({"background":`${rowData.backgrounPrincipal}`,"color":`${rowData.colorPrincipal}`}); 
                                    $(td).addClass("text-center")
                                      
                                    if (col == 4) { //powerup_prom
                                        $(td).css({"background":`${rowData.backgrounPowerUpProm}`,"color":`${rowData.colorPowerUpProm}`});    
                                    }
                                    if (col == 5 || col==6) { //powerup_min - powerds_max
                                        $(td).css({"background":`${rowData.backgrounPrincipal}`,"color":`${rowData.colorPrincipal}`});    
                                    }
                                    if (col == 7) { // powerds_prom
                                        $(td).css({"background":`${rowData.backgrounPowerDowsProm}`,"color":`${rowData.colorPowerDowsProm}`});    
                                    }
                                    if (col == 8) { // powerds_min
                                        $(td).css({"background":`${rowData.backgrounPrincipal}`,"color":`${rowData.colorPrincipal}`});    
                                    }
                                    if (col == 9) { // snr_avg
                                        $(td).css({"background":`${rowData.backgrounSnrArvg}`,"color":`${rowData.colorSnrArvg}`});    
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
                    console.log("Termino la carga completa")
                         
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

peticiones.cargaHistoricoNivelesCmtsPorPuerto = function cargaHistoricoNivelesCmtsPorPuerto(tabla, ruta, puerto){

            tabla.DataTable({
                "destroy": true,
                "processing": true, 
                "serverSide": true,
                "dom":'<"row mx-0"'
                        +'<"col-12 col-sm-6"l>>'
                        +'<"row"'
                        +'<"col-sm-12 px-0 table-responsive tableFixHead"t>>'
                        +'<"row"'
                        +'<"col-12 col-sm-5"i><"col-12 col-sm-7"p>>'
                        +'r',
                "ajax": {  
                        'url':ruta,
                        "type": "GET", 
                        "dataType": "json", 
                        "data": function ( d ) {

                                d.puerto = puerto;
                              
                        },
                        'dataSrc': function(json){
                                 //console.log("Termino la carga asi tenga error.. :",json)
                        
                                    //return json
                                        let result = json.data
                                    
                                        return result  
                                
                            
                        },
                        'error': function(jqXHR, textStatus, errorThrown)
                        {  

                               //console.log( "Error: " ,jqXHR, textStatus, errorThrown); 
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
                    {data:'cmts'},
                    {data:'Interface'},
                    {data:'description'},
                    {data:'powerup_max'},
                    {data:'powerup_prom'},
                    {data:'powerup_min'},
                    {data:'powerds_max'},
                    {data:'powerds_prom'},
                    {data:'powerds_min'},
                    {data:'snr_avg'},
                    {data:'snr_down'},
                    {data:'fecha_hora'}
                ],
                'columnDefs': [
                        {
                            'targets': '_all',
                            'createdCell':  function (td, cellData, rowData, row, col) { 
                                    
                                    $(td).css({"background":`${rowData.backgrounPrincipal}`,"color":`${rowData.colorPrincipal}`}); 
                                    $(td).addClass("text-center")
                                      
                                    if (col == 4) { //powerup_prom
                                        $(td).css({"background":`${rowData.backgrounPowerUpProm}`,"color":`${rowData.colorPowerUpProm}`});    
                                    }
                                    if (col == 5 || col==6) { //powerup_min - powerds_max
                                        $(td).css({"background":`${rowData.backgrounPrincipal}`,"color":`${rowData.colorPrincipal}`});    
                                    }
                                    if (col == 7) { // powerds_prom
                                        $(td).css({"background":`${rowData.backgrounPowerDowsProm}`,"color":`${rowData.colorPowerDowsProm}`});    
                                    }
                                    if (col == 8) { // powerds_min
                                        $(td).css({"background":`${rowData.backgrounPrincipal}`,"color":`${rowData.colorPrincipal}`});    
                                    }
                                    if (col == 9) { // snr_avg
                                        $(td).css({"background":`${rowData.backgrounSnrArvg}`,"color":`${rowData.colorSnrArvg}`});    
                                    }
                                    if (col == 10) { // snr_down
                                        $(td).css({"background":`${rowData.backgrounSnrDown}`,"color":`${rowData.colorSnrDown}`});    
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
                    console.log("Termino la carga completa")
                         
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

export default peticiones