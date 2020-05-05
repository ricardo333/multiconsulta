import peticiones from './peticiones.js'
import errors from  "@/globalResources/errors.js"

$(function(){

    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
 
    $("body").on("click",".gestionIndividualCOE", function(){
 
        let arrayCodReq = []
        let codigoReq = $(this).data("uno")
        arrayCodReq.push(codigoReq)
        console.log("La data enviar es: ",arrayCodReq)

        cargaGestionCoe(arrayCodReq)
         
    })

    function  cargaGestionCoe(arrayCodReq){

        CLIENTES_GESTION = []

        $("#printFormGestionCOE").html(`<div id="carga_person">
                <div class="loader">Loading...</div>
            </div>`); 

        peticiones.redirectTabs($("#gestionCoeTab"))
 
        $.ajax({
            url:`/administrador/averias-coe/gestion/view`,
            method:"post",
            data:{"codigosRequerimientos":arrayCodReq},
            dataType: "json", 
          })
          .done(function(data){
            // console.log("La data resotrnada es: ",data)

             let resultadoArmado = JSON.parse(data.response.detalleView)

             $("#printFormGestionCOE").html(resultadoArmado)

             peticiones.redirectTabs($("#gestionCoeTab"))

             data.response.clientes.forEach(el => {
                CLIENTES_GESTION.push({
                    "codcli":el.codcli,
                    "codreq":el.codreq,
                    "macaddress":el.macaddress,
                    "codsrv":el.codsrv,
                    "nodohfc":el.nodohfc,
                    "trobahfc":el.trobahfc
                })
             });

            // CLIENTES_GESTION.push(data.response.clientes)

            // console.log("Los clientes para gestion son: ",CLIENTES_GESTION)
              
          })
          .fail(function(jqXHR, textStatus){
           console.log("error",jqXHR, textStatus)
             $("#body-errors-modal").html(jqXHR.responseText)
            
            /*$("#printFormGestionCOE").html(""); 
            peticiones.redirectTabs($("#averiasCoeTab"))
 
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
  
            $("#body-errors-modal").html(erroresPeticion)*/
            $('#errorsModal').modal('show') 
  
            return false
   
          })
    }

    //Registrando Gestión
    $("body").on("click","#segundaLineaSelect", function(){ 

        let valor = $(this).val()
        if (valor == "CON TRATAMIENTO") {
            $("#resultadoSegundaLinea").html(`
                <option value="seleccionar">Seleccionar</option>
                <option value="NO VA A CAMPO">No va a Campo</option>
                <option value="PERSISTE EL PROBLEMA">Persiste el Problema</option>
            `)
        }else if (valor == "SIN TRATAMIENTO") {
            $("#resultadoSegundaLinea").html(`
                <option value="seleccionar">Seleccionar</option>
                <option value="ATENDIDO">Atendido</option>
                <option value="SIN CONTACTO">Sin Contacto</option>
                <option value="NO DESEA SOPORTE">No Desea Soporte</option>
            `)
        } else{
            $("#resultadoSegundaLinea").html(`
                <option value="seleccionar">Seleccionar</option>
            `)
        }

    })
    $("body").on("click","#resultadoSegundaLinea", function(){

        let valor = $(this).val()
        if (valor == "NO VA A CAMPO") {
            $("#detalleResultado").html(`
                <option value="seleccionar">Seleccionar</option>
                <option value="SERVICIO OK">servicio Ok</option>
                <option value="SE DA SOLUCION">Se da Solución</option>
            `)
        }
        else if (valor == "PERSISTE EL PROBLEMA") {
            $("#detalleResultado").html(`
                <option value="seleccionar">Seleccionar</option>
                <option value="CON REMEDY">Con Remedy</option>
                <option value="VISITA TECNICA C/ GUIADO">Visita Técnica c/ Guiado</option>
            `)
        }
        else if (valor == "ATENDIDO") {
            $("#detalleResultado").html(`
                <option value="seleccionar">Seleccionar</option>
                <option value="TECNICO EN CASA">Técnico en Casa</option>
                <option value="AVERIA LIQUIDADA">Avería Liquidada</option>
            `)
        }
        else if (valor == "SIN CONTACTO") {
            $("#detalleResultado").html(`
                <option value="seleccionar">Seleccionar</option>
                <option value="CONTACTO NO EFECTIVO">Contacto no Efectivo</option>
                <option value="SIN CONTACTO">Sin Contacto</option>
            `)
        }
        else if (valor == "NO DESEA SOPORTE") {
            $("#detalleResultado").html(`
                <option value="VISITA TECNICA S/ GUIADO">Visita técnnica S/ Guiado</option>
            `)
        }else{
            $("#detalleResultado").html(`
                <option value="seleccionar">Seleccionar</option>
            `)
        }

    })

    $("body").on("click","#sendGestionIndividual", function(){

        let cantidadClientes = CLIENTES_GESTION.length

        if (cantidadClientes == 0) {
            $("#printFormGestionCOE").html(""); 
            peticiones.redirectTabs($("#averiasCoeTab"))
            $("#body-errors-modal").html(`<div class="text-center w-100 text-danger">No se puede identificar datos de clientes a procesar. Intente nuevamente.</div>`)
            $('#errorsModal').modal('show') 
        }

        let segundaLinea = $("#segundaLineaSelect").val()
        let resultadoSegundaLinea = $("#resultadoSegundaLinea").val()
        let detalleResultado = $("#detalleResultado").val()
        let personaContacto = $("#personaContacto").val()
        let numeroContacto = $("#numeroContacto").val()
        let observacionResultado = $("#observacionResultado").val()
        let EstadoDelCaso = $("input[name=EstadoDelCaso]:checked").val() 
        let ResultadoVisita =$("input[name=ResultadoVisita]:checked").val() 
        let observacionVisitaTecnica = $("#observacionVisitaTecnica").val()

       /* console.log("La data ha enviar es: ",
                'segundaLinea: ',segundaLinea,
                'resultadoSegundaLinea: ',resultadoSegundaLinea,
                'detalleResultado: ',detalleResultado,
                'personaContacto: ',personaContacto,
                'numeroContacto: ',numeroContacto,
                'observacionResultado: ',observacionResultado,
                'EstadoDelCaso: ',EstadoDelCaso,
                'ResultadoVisita: ',ResultadoVisita,
                'observacionVisitaTecnica: ',observacionVisitaTecnica,
                'dataClientes',CLIENTES_GESTION
        )*/

        $("#preloadSendGestion").html(`
                    <div class="d-flex justify-content-center align-content-center flex-wrap content-loading-preload" >
                        <div class="spinner-border" role="status" style="width: 150px;height: 150px;">
                        <span class="sr-only">Loading...</span>
                        </div>
                        <div class="text-center w-100">
                            <strong>Guardando Datos.</strong>
                        </div>
                    </div> 
        `)

        $("#content-form").addClass("d-none")
        $("#contentListClientes").addClass("d-none")

        $.ajax({
            url:`/administrador/averias-coe/gestion/store`,
            method:"post",
            data:{ 
                segundaLinea,
                resultadoSegundaLinea,
                detalleResultado,
                personaContacto,
                numeroContacto,
                observacionResultado,
                EstadoDelCaso,
                ResultadoVisita,
                observacionVisitaTecnica,
                'dataClientes':CLIENTES_GESTION
            },
            dataType: "json"
        })
        .done(function(data){

            //console.log("el resultado es: ",data)

            $("#preloadSendGestion").html(``)
            $("#content-form").removeClass("d-none")
            $("#contentListClientes").removeClass("d-none")

            $("#errorGestionProcess").html(`<div class="container text-center font-weight-bold alert alert-success fade show" role="alert">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                ${data.mensaje}</div>`); 
        
            $("#segundaLineaSelect").val("seleccionar")
            $("#resultadoSegundaLinea").val("seleccionar")
            $("#detalleResultado").val("seleccionar")
            $("#personaContacto").val("")
            $("#numeroContacto").val("")
            $("#observacionResultado").val("")
            $("#observacionVisitaTecnica").val("")

        })
        .fail(function( jqXHR, textStatus){
            // console.log("Error:",jqXHR, textStatus)

            $("#preloadSendGestion").html(``)
            $("#content-form").removeClass("d-none")
            $("#contentListClientes").removeClass("d-none")

            $("#errorGestionProcess").html(``)

            //$("#errorGestionProcess").html(jqXHR.responseText)
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
            erroresPeticion = (erroresPeticion.trim() == 0) ? "hubo un error en el servicio, intente nuevamente." : erroresPeticion
  
            

            $("#errorGestionProcess").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            ${erroresPeticion}</div>`); 

         
  
            return false 
        })
  
    })

    //HISTORICO
    $("body").on("click",".verHistoricoGestionCOE", function(){

        //
        let codigoCliente = $(this).data("uno")

        peticiones.redirectTabs($("#historicoAveriasCOETab"))
 
            $("#resultHistoricoGestion").DataTable({
                "destroy": true,
                "processing": true, 
                "serverSide": true,
                "dom":'<"row mx-0"'
                        +'<"col-12 col-sm-6"l><"col-12 col-sm-6"f>>'
                    +'<"row"'
                        +'<"col-sm-12 px-0 table-responsive table-text-xs tableFixHead"t>>'
                    +'<"row"'
                        +'<"col-12 col-sm-5"i><"col-12 col-sm-7"p>>'
                    +'r',
                "ajax": {  
                    'url':`/administrador/averias-coe/gestion/historico`,
                    "type": "GET", 
                    "data": function ( d ) { 
                            d.codigoCliente = codigoCliente; 
                    },
                    'error': function(jqXHR, textStatus, errorThrown)
                        { 
                            
                            // console.log( "Error: " ,jqXHR, textStatus); 
                                
                            // $("#body-errors-modal").html(jqXHR.responseText)
                         
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

                                peticiones.redirectTabs($("#gestionCoeTab"))
 
                            
                            return false
                        }
                },
                "columns": [
                    {data: 'nodo'},
                    {data: 'troba'},
                    {data: 'codigoCliente'},
                    {data: 'mac'},
                    {data: 'codigoServicio'},
                    {data: 'codigoRequerimiento'},
                    {data: 'usuario'},
                    {data: 'segundaLinea'},
                    {data: 'resultadoSegundaLinea'},
                    {data: 'detalleResultado'},
                    {data: 'EstadoDelCaso'},
                    {data: 'fechaRegistro'}
                ],
                'columnDefs': [  
                        {
                            
                            "targets": '_all',
                            "orderable" : false,
                            "searchable": false,
                                
                        } 
                ] ,
                "pageLength": 25,
                "language": {
                    "info": "_TOTAL_ registros",
                    "search": "Buscar",
                    "paginate": {
                        "next": "Siguiente",
                        "previous": "Anterior",
                    },
                    "lengthMenu": 'Mostrar <select >'+
                                '<option value="25">25</option>'+
                                '<option value="50">50</option>'+
                                '<option value="100">100</option>'+
                                '<option value="-1">Todos</option>'+
                                '</select> registros',
                    "loadingRecords": "<div id='carga_person'> <div class='loader'>Cargando...</div></div>",
                    "processing": "<div id='carga_person'> <div class='loader'>Procesando...</div></div>",
                    "emptyTable": "No hay datos disponibles",
                    "zeroRecords": "No hay coincidencias", 
                    "infoEmpty": "",
                    "infoFiltered": ""
                }/*,
                "initComplete": function () {
                    console.log("ya termino lac arga....",this)
                    
                }*/
            });
         
            // $("#resultDiagnosticoMasivo").parent().addClass("table-responsive tableFixHead") 
            $("#resultHistoricoGestion").css({"font-size":"11px"})
        
            let tablaHead = $('.tableFixHead').find('thead th')
            $('.tableFixHead').on('scroll', function() {
                // console.log("ejecutando"+this.scrollTop); 
                tablaHead.css('transform', 'translateY('+ this.scrollTop +'px)'); 
            }); 
   

    })

    $("body").on("click",".return_gestion_coe", function(){
         
        peticiones.redirectTabs($("#gestionCoeTab"))
   
    })

    //GESTION MASIVA

    var ESTADO_GESTION_MASIVA = false
   
    $("body").on("click","#activarGestionMasiva", function(){
         
        if ($(this).hasClass("inactive")) {
           // console.log("La clase está inactiva por lo tanto se activará")
            $(this).removeClass("inactive")
            $(this).removeClass("btn-outline-secondary")
            $(this).addClass("active")
            $(this).addClass("btn-outline-warning")
            $(this).html("Gestión Masiva Activo")

            $(".btnGestionMasiva").removeClass("d-none")
            $("#procesarGestionMasivaSend").removeClass("d-none")
            $("#procesarGestionMasivaSend").prop("disabled", true)

           // $(".btnGestionMasiva").prop("checked",true) 
            $("input[name='btnGestionMasiva']").prop("checked",false)

            ESTADO_GESTION_MASIVA = true
        }else{
           // console.log("La clase esta activa por lo tanto se desactivara")
            $(this).removeClass("active")
            $(this).removeClass("btn-outline-warning")
            $(this).addClass("inactive")
            $(this).addClass("btn-outline-secondary")
            $(this).html("Gestión Masiva")

            $(".btnGestionMasiva").addClass("d-none")
            $("#procesarGestionMasivaSend").addClass("d-none")
            $("#procesarGestionMasivaSend").removeClass("btn-outline-success")

            ESTADO_GESTION_MASIVA = false
        }

    })
 
    $("body").on("click","#resultAveriasCOEMasivas td", function(){
        if (ESTADO_GESTION_MASIVA) {
           // console.log("hiciste click en una celda: ",$(this).closest("tr"))
            let objetoTr = $(this).closest("tr")
            if (objetoTr) {
                objetoTr.find('td input:checkbox').prop('checked',true);
            }

            let seleccionados =  $("input[name='btnGestionMasiva']:checked")
            verificarSeleccion(seleccionados)
        }
 
    })

    $("body").on("dblclick","#resultAveriasCOEMasivas  td", function(){
        if (ESTADO_GESTION_MASIVA) {
            //console.log("hiciste doble click en una celda: ",$(this).closest("tr"))
            let objetoTr = $(this).closest("tr")
            if (objetoTr) {
                objetoTr.find('td input:checkbox').prop('checked',false);
            }

            let seleccionados =  $("input[name='btnGestionMasiva']:checked")
            verificarSeleccion(seleccionados)
              
        }
 
    })

    function  verificarSeleccion(seleccionados)
    {
       // console.log("Los elementos seleccionados son: ",seleccionados)
        if (seleccionados.length <= 1) {
            $("#procesarGestionMasivaSend").prop("disabled", true)
            $("#procesarGestionMasivaSend").removeClass("btn-outline-success")
        }else{
            $("#procesarGestionMasivaSend").prop("disabled", false)
            $("#procesarGestionMasivaSend").addClass("btn-outline-success")
        }

    }

    $("body").on("click","#procesarGestionMasivaSend", function(){

        var checkedValue = []; 
        // let session = ""
         var inputElements = document.getElementsByClassName('btnGestionMasiva');
         for(var i=0; inputElements[i]; ++i){
             if(inputElements[i].checked){
                // console.log("Los datos seleccionados son: ",inputElements[i])
                // session = inputElements[i].dataset.dos,
                checkedValue.push(inputElements[i].dataset.tres)
                /* checkedValue.push({
                     codcli:inputElements[i].dataset.uno,
                     codreq:inputElements[i].dataset.tres,
                     nodohfc:inputElements[i].dataset.cuatro,
                     trobahfc:inputElements[i].dataset.cinco,
                     macaddress:inputElements[i].dataset.seis,
                     codsrv:inputElements[i].dataset.siete,
                 }) */
                //checkedValue = inputElements[i].value;
                //break;
             } 
         }

        // console.log("Los datos a procesar gestion masiva son: ",checkedValue)

        
        cargaGestionCoe(checkedValue)
    })
 

})