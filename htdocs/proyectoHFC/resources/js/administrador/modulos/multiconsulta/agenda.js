import errors from  "@/globalResources/errors.js"
import peticiones from './peticiones.js'

var NODO_AGENDAR = ""
var IDCLIENTE_AGENDAR = ""
var SERVICIO_CLIENTE = ""
var NOMBRE_CLIENTE = ""
var ID_TIPOAGENDA_ACTIVA = 0
var ID_AGENDA_ACTIVA = 0

var PREAGENDA_PROCESO_ACTUAL = ""

var HTML_TIPO_AGENDA_INICIAL = ""

const VIEW_HORARIO_CUPOS = "VIEW_HORARIOS"
const VIEW_FORMULARIO_CUPO = "VIEW_FORMULARIO_CUPO"

//cuenta regresiva

var timerUpdate = null

const TIEMPO_RESTANTE_MINUTOS = 5


var BLOQUEO_RESERVACION_AGENDA = false
var TARGET = null
var TARGET_ID = null
 
$(function(){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).click(function(event) { 
      TARGET = $(event.target); 
      TARGET_ID = event.target.id
      //console.log("El target  es: ",TARGET)
        //console.log("El target ID es: ",event.target.id)
        //console.log("Tiene la clase icofont-maximize: ",TARGET.hasClass('icofont-maximize'))
        //console.log("el bloqueo dentro del document esta en : ",BLOQUEO_RESERVACION_AGENDA)
        if (BLOQUEO_RESERVACION_AGENDA) {
          //console.log("la validacion del bloqueo es: ",validarBloqueo())
          if (validarBloqueo()) {
            $("#body-detalles-modal").html(` 
                  <section id="content_cancelar_cupo">
                    <div class="w-100 text-danger">
                      Si intenta salir de esta sección, se perderá su reserva, esta seguro de abandonar su supo?
                    </div>
                    <div class="w-100 row m-0 justify-content-center">
                      <button id="abandonarCupoReservado" 
                            class="btn btn-sm btn-outline-info shadow-sm m-1 col-sm-6 col-md-4">Si Abandonar</button>
                      <button type="button" class="btn btn-sm btn-outline-danger shadow-sm m-1 col-sm-6 col-md-4" data-dismiss="modal">Cancelar</button>
                    </div>
                  </section>
              `)
              
              $("#detallesModal").modal("show")

              return false
          }
                  
        }
              
    });

    function validarBloqueo()
    {
     //console.log("el target contenedor es: ",TARGET)
      if (TARGET_ID) {
       return !TARGET.closest('#contenedor_formulario_agenda_principal').length && TARGET_ID !== "abandonarCupoReservado" 
              && !TARGET.hasClass('icofont-maximize') && !TARGET.hasClass('icofont-close-line-squared-alt') && !TARGET.hasClass('maxi_tab')
      }else{
        
        return !TARGET.closest('#contenedor_formulario_agenda_principal').length 
                && !TARGET.hasClass('icofont-maximize') && !TARGET.hasClass('icofont-close-line-squared-alt') && !TARGET.hasClass('maxi_tab')
      }
     
    }

    $("#return_agenda_to_multiconsultaTab").click(function(){
      //console.log("Aqui estamos, deberiamos validar el estado del bloqueo...")
      if (BLOQUEO_RESERVACION_AGENDA) {
        //console.log("la validacion del bloqueo es: ",validarBloqueo())
        if (validarBloqueo()) {
          $("#body-detalles-modal").html(` 
                <section id="content_cancelar_cupo">
                  <div class="w-100 text-danger">
                    Si intenta salir de esta sección, se perderá su reserva, esta seguro de abandonar su supo?
                  </div>
                  <div class="w-100 row m-0 justify-content-center">
                    <button id="abandonarCupoReservado" 
                          class="btn btn-sm btn-outline-info shadow-sm m-1 col-sm-6 col-md-4">Si Abandonar</button>
                    <button type="button" class="btn btn-sm btn-outline-danger shadow-sm m-1 col-sm-6 col-md-4" data-dismiss="modal">Cancelar</button>
                  </div>
                </section>
            `)
            
            $("#detallesModal").modal("show")

            return false
        }
                
      }

      peticiones.redirectTabs($("#multiconsultaTab"));

      $("#preLoadAgendaSend").html("")
      $("#resultPreAgendaContent").html("")
      $("#resultAgendaGrafico").html("")

      if (timerUpdate != null) {
        clearInterval(timerUpdate);
        //console.log("se detuvo el timerUpdate")
      }
       

    })

    

    function cargaPrincipalAgendaDetalles(accionAgenda="agendar"){
 
      let idCliente = $("#preAgendaMulti").data("uno")
      let sw = $("#preAgendaMulti").data("dos")
      let nodo = $("#preAgendaMulti").data("tres")
      let accionCarga = accionAgenda

      $("#resultPreAgendaContent").html(`<div id="carga_person">
                                              <div class="loader">Loading...</div>
                                          </div>`)
      $("#preLoadAgendaSend").html("");
      $("#resultAgendaGrafico").html("")

      $.ajax({
        url:`/administrador/multiconsulta/agenda/detalle`,
        method:"get",
        async: true,
        data:{
          idCliente,
          sw,
          nodo,
          accionCarga
        },
       cache: false, 
       dataType: "json", 
      })
      .done(function(data){ 
        
         //console.log("la data inicial es: ",data)
        
        NODO_AGENDAR =  "" 
        IDCLIENTE_AGENDAR =  "" 
        SERVICIO_CLIENTE = ""
        NOMBRE_CLIENTE = ""
        ID_TIPOAGENDA_ACTIVA = 0
        ID_AGENDA_ACTIVA = 0

        let principalContenido = ``
        
        let tabla =``
        data.response.data.forEach(el => {

          SERVICIO_CLIENTE = el.servicio
          NOMBRE_CLIENTE = el.nameclient
           
          tabla += `<div class="form-group row mx-0 my-1 px-2 col-md-6">
                      <label for="detalleCodCliente" class="col-sm-3 col-md-4 col-form-label col-form-label-sm mb-0 px-0">CLIENTE: </label> 
                      <span class="form-control form-control-sm col-sm-9 col-md-8 bg-secondary">${el.codcli}</span>   
                    </div>
                    <div class="form-group row mx-0 my-1 px-2 col-md-6">
                      <label for="detalleServicio" class="col-sm-3 col-md-4 col-form-label col-form-label-sm mb-0 px-0">SERVICIO: </label> 
                      <span class="form-control form-control-sm  col-sm-9 col-md-8 bg-secondary">${el.servicio}</span>   
                    </div>
                    <div class="form-group row mx-0 my-1 px-2 col-md-6">
                      <label for="detalleCodCliente" class="col-sm-3 col-md-4 col-form-label col-form-label-sm mb-0 px-0">NODO: </label> 
                      <span class="form-control form-control-sm  col-sm-9 col-md-8 bg-secondary">${el.nodo}</span>   
                    </div>
                    <div class="form-group row mx-0 my-1 px-2 col-md-6">
                      <label for="detallenombreCliente" class="col-sm-3 col-md-4 col-form-label col-form-label-sm mb-0 px-0">NOMBRE: </label> 
                      <span class="form-control form-control-sm  col-sm-9 col-md-8 bg-secondary">${el.nameclient}</span>   
                    </div>
                    <div class="form-group row mx-0 my-1 px-2 col-md-12">
                      <label for="detalleDireccion" class="col-sm-3 col-md-2 col-form-label col-form-label-sm mb-0 px-0">DIRECCION: </label> 
                      <span class="form-control form-control-sm  col-sm-9 col-md-10 bg-secondary">${el.DIREC_INST}</span>   
                    </div>
                    `
            NODO_AGENDAR =  el.nodo 
        })
        


        let agendaPendiente = ``
        let detallesParaAgendar = ``

        IDCLIENTE_AGENDAR = data.response.idCliente

        HTML_TIPO_AGENDA_INICIAL = ""
       
        
        if (data.response.estaAgendado && data.response.accionRealizar =="agendar") {

          ID_TIPOAGENDA_ACTIVA = data.response.detalleAgenda[0].tipocliagenda
          ID_AGENDA_ACTIVA = data.response.detalleAgenda[0].id
        
          agendaPendiente += `<div class="w-100 text-center text-primary font-weight-bold my-2">El Cliente ya cuenta con una Agenda pendiente:</div>`
          agendaPendiente += `<div class="table-responsive">
                              <table class="m-auto table-hover table-bordered">
                                <tr>
                                  <th align='left'>Tipo de Agenda: </th>
                                  <td> ${data.response.detalleAgenda[0].tipoAgendaReservado[0].tipoturno}</td>
                                </tr>
                                <tr>
                                  <th align='left'>Fecha de Agenda: </th>
                                  <td> ${data.response.detalleAgenda[0].fecha}</td>
                                </tr>
                                <tr>
                                  <th align='left'>Codreq: </th>
                                  <td> ${data.response.detalleAgenda[0].codreq}</td>
                                </tr>
                                <tr>
                                  <th align='left'>Turno: </th>
                                  <td> ${data.response.detalleAgenda[0].turno}</td>
                                </tr>
                                <tr>
                                  <th align='left'>Fecreg Agenda: </th>
                                  <td> ${data.response.detalleAgenda[0].fecharegistroagenda}</td>
                                </tr>
                                <tr>
                                  
                                  <td colspan="2">
                                      <a href="javascript:void(0)"  data-uno="${data.response.detalleAgenda[0].id}"
                                          data-dos="${data.response.detalleAgenda[0].codcli}" data-tres="2"
                                          id="reAgendarClienteMulti"
                                          class="btn btn-sm btn-outline-success shadow-sm w-100">Reagendar</a></td>
                                </tr>
                              </table>
                              </div>`
              
        }else{
          if (data.response.accionRealizar =="reagendar") {
              ID_TIPOAGENDA_ACTIVA = data.response.detalleAgenda[0].tipocliagenda
              ID_AGENDA_ACTIVA = data.response.detalleAgenda[0].id
          }
          detallesParaAgendar += `
                                  <div class="form-group row mx-0 px-1 my-1 col-12 col-sm-12 col-md-8 " id="detTipoAgendaInicial">`

            
            if (ID_TIPOAGENDA_ACTIVA > 0) {
              HTML_TIPO_AGENDA_INICIAL = `<label for="tipoDeAgenda" class="col-sm-3 col-md-3 col-form-label col-form-label-sm mb-0 px-0">Tipo de Agenda: </label>
                                          ${data.response.detalleAgenda[0].tipoAgendaReservado[0].tipoturno}
                                          <input type="hidden" value="${ID_TIPOAGENDA_ACTIVA}" id="tipoDeAgenda">
                                          `
                                      
            }else{
              HTML_TIPO_AGENDA_INICIAL = `
                                    <label for="tipoDeAgenda" class="col-sm-3 col-md-3 col-form-label col-form-label-sm mb-0 px-0">Tipo de Agenda: </label>    
                                    <div class="input-group col-sm-9 col-md-9 p-0">  
                                        <select name="tipoDeAgenda" id="tipoDeAgenda" class="form-control form-control-sm shadow-sm">
                                        <option value="seleccionar">Seleccionar</option>
                                      ` 
                              data.response.tipoDeAgenda.forEach(el => {
                                HTML_TIPO_AGENDA_INICIAL += `<option value="${el.id}">${el.tipoturno}</option>` 
                              })
            HTML_TIPO_AGENDA_INICIAL += `      </select>  
                                          <!--<span class="input-group-btn">
                                            <a href="javascript:void(0)"  data-uno="${data.response.idCliente}"
                                                    class="btn btn-sm btn-outline-primary shadow-sm  h-100 d-flex align-items-center"  id="cargarAgendaCuposDisponbilesSemanal">
                                                Ver Turnos
                                            </a> 
                                          </span>-->
                                    </div>`
              
            }

            detallesParaAgendar  += HTML_TIPO_AGENDA_INICIAL

            
            detallesParaAgendar  += `</div>
                                    <div class="form-group row mx-0 px-1 my-1 col-12 col-sm-12 col-md-4 justify-content-sm-center justify-content-md-end">
                                      <span class="btn btn-sm btn-warning font-weight-bold" >Tiempo restante: <i id="contadorRegresivoAgendaFinal">00:00</i></span>
                                    </div>
                                  </div>` 

          detallesParaAgendar += `<div class="w-100" id="resultFormProcesoPreAgendar"></div>`
                  
        }

        principalContenido +=tabla
        principalContenido +=agendaPendiente
        principalContenido +=detallesParaAgendar

        $("#resultPreAgendaContent").html(principalContenido)

        if (parseInt(ID_TIPOAGENDA_ACTIVA) > 0 && data.response.accionRealizar =="reagendar") {

            let tipoDeAgenda = $("#tipoDeAgenda").val()
            // let diaDeAgenda = $("#diaDeAgenda").val()
            //let idCliente = $(this).data("uno")
           let idCliente = IDCLIENTE_AGENDAR
           let nodo = NODO_AGENDAR

           if (nodo == "") {
            //$("#preLoadAgendaSend").html(``)
            //$("#resultPreAgendaContent").html(``)
             $("#body-errors-modal").html(`<div class="w-100 text-center text-danger">No se puede identificar el NODO, intente ingresar nuevamente.</div>`)
             $('#errorsModal').modal('show')
            // peticiones.redirectTabs($("#multiconsultaTab")); 
             return false
           }

           let parametros = {
                    tipoDeAgenda,
                  // diaDeAgenda,
                    idCliente,
                    nodo
            }

            //console.log("el valor del timerUpdate es: ",timerUpdate)
            if (timerUpdate != null) {
              clearInterval(timerUpdate);
              //console.log("se detuvo el timerUpdate")
            }
            //console.log("Lo parametros a enviar son : ",parametros)
            cargarTurnosYDiasGeneralesDisponibles(parametros)
          
        }
 
  
      })
      .fail(function(jqXHR, textStatus, errorThrown){
 
        //console.log( "Request failed: " ,textStatus ,jqXHR,errorThrown);

         peticiones.redirectTabs($("#multiconsultaTab"));
 
         //$("#resultPreAgendaContent").html(jqXHR.responseText)
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

         $("#body-errors-modal").html(`<div class="w-100 text-center text-danger">${erroresPeticion}</div>`)
         $('#errorsModal').modal('show')

        /* $("#resultPreAgendaContent").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                             <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                 <span aria-hidden="true">&times;</span>
                                             </button>
                                             ${erroresPeticion}</div>`);*/ 
         return false
          
      }); 


    }

    $("body").on("click","#preAgendaMulti", function(){

      peticiones.redirectTabs($("#preAgendaTab"));

        cargaPrincipalAgendaDetalles()
          
      })


      $("body").on("click","#cargarAgendaCuposDisponbilesSemanal", function(){
             
            let tipoDeAgenda = $("#tipoDeAgenda").val()
           // let diaDeAgenda = $("#diaDeAgenda").val()
            //let idCliente = $(this).data("uno")
            let idCliente = IDCLIENTE_AGENDAR
            let nodo = NODO_AGENDAR

            if (nodo == "") {
             //$("#preLoadAgendaSend").html(``)
             //$("#resultPreAgendaContent").html(``)
              $("#body-errors-modal").html(`<div class="w-100 text-center text-danger">No se puede identificar el NODO, intente ingresar nuevamente.</div>`)
              $('#errorsModal').modal('show')
             // peticiones.redirectTabs($("#multiconsultaTab")); 
              return false
            }

            let parametros = {
                    tipoDeAgenda,
                   // diaDeAgenda,
                    idCliente,
                    nodo
            }

            //console.log("el valor del timerUpdate es: ",timerUpdate)
            if (timerUpdate != null) {
              clearInterval(timerUpdate);
              //console.log("se detuvo el timerUpdate")
            }

            cargarTurnosYDiasGeneralesDisponibles(parametros)
  
      })

      $("body").on("change","#tipoDeAgenda", function(){
             
            let tipoDeAgenda = $(this).val()
           // let diaDeAgenda = $("#diaDeAgenda").val()
            //let idCliente = $(this).data("uno")
            let idCliente = IDCLIENTE_AGENDAR
            let nodo = NODO_AGENDAR

            //console.log("cambio de tipo de agenda, deberia limpiarse el calendario de cupos y resetear el contador a cero nuevamente")
            if (timerUpdate != null) {
              clearInterval(timerUpdate);
              //console.log("se detuvo el timerUpdate")
              $("#contadorRegresivoAgendaFinal").html("00:00")
            }
             
            if (nodo == "") {
             //$("#preLoadAgendaSend").html(``)
             //$("#resultPreAgendaContent").html(``)
              $("#body-errors-modal").html(`<div class="w-100 text-center text-danger">No se puede identificar el NODO, intente ingresar nuevamente.</div>`)
              $('#errorsModal').modal('show')
              peticiones.redirectTabs($("#multiconsultaTab")); 
              //console.log("limpiar todo")
              return false
            }
 
            let parametros = {
                    tipoDeAgenda,
                   // diaDeAgenda,
                    idCliente,
                    nodo
            }

            //console.log("el valor del timerUpdate es: ",timerUpdate)
             

            cargarTurnosYDiasGeneralesDisponibles(parametros)
  
      })

      function cargarTurnosYDiasGeneralesDisponibles(parametros)
      {

        //console.log("el tipo de agenda es: ",parametros.tipoDeAgenda.toLowerCase()," el resultadoes: ",parametros.tipoDeAgenda.toLowerCase() == "seleccionar")
 
        if (parametros.tipoDeAgenda.toLowerCase() == "seleccionar") {
            //console.log("No deberia seguir con el filtro solo limpiar......")
            $("#resultAgendaGrafico").html("")
            //$("#contadorRegresivoAgendaFinal").html("00:00")
            return false
        }

 
        $("#preLoadAgendaSend").html(`<div id="carga_person">
                                            <div class="loader">Loading...</div>
                                        </div>`)
        $("#resultPreAgendaContent").addClass("d-none")
        $("#resultFormProcesoPreAgendar").html("")
        $("#resultAgendaGrafico").html("")


          $.ajax({
            url:`/administrador/multiconsulta/agenda/verificar-turno`,
            method:"get",
            async: true,
            data:parametros,
            cache: false, 
            dataType: "json", 
          })
        .done(function(data){
            $("#preLoadAgendaSend").html(``)
            $("#resultPreAgendaContent").removeClass("d-none")
              
            //Grafico

            PREAGENDA_PROCESO_ACTUAL = VIEW_HORARIO_CUPOS

            countdown(getFechaRegresivoPorMinutos(TIEMPO_RESTANTE_MINUTOS), 'contadorRegresivoAgendaFinal');

            let agendagrafica = ``
            let longitudDias = data.response.diasAgendaTotal.length
            agendagrafica += `<div class="w-100 text-right">Los cupos se actualizarán cuando el <strong class="text-secondary">tiempo restante</strong> llegue a 00:00.</div>`
            agendagrafica += `<div class="table-responsive"> <table class="w-100 table-hover">`
            agendagrafica += `<thead>`
            agendagrafica += `<tr><th class="agenda_horario">Horarios</th>`
            data.response.diasAgendaTotal.forEach(el => {
              agendagrafica += ` 
                                  <th class="agenda_dias">${el.dia}<br/> ${el.fecha}</th>
                                `
            })
            agendagrafica += `</tr></thead>`

            agendagrafica += `<tbody id="detallesAgendaDisponnibles">`
            data.response.rangoHorariosCompletos.forEach(el => {
                agendagrafica += `<tr>
                                  <td>${el.turno}</td>`
                                    
                                    data.response.diasAgendaTotal.forEach(element => {
                                        let textoDiponibilidad = ""
                                      element.rangoHorarios.forEach(horarioEl => {
                                          if (horarioEl.turno == el.turno) {
                                            textoDiponibilidad =  `<button class="btn btn-sm shadow-sm btn-outline-success 
                                                                      selectedCupoAgenda" 
                                                                      data-uno="${element.fecha}" data-dos="${horarioEl.id}"
                                                                      data-tres="${parametros.nodo}" data-cuatro="${data.response.idCliente}"
                                                                      data-detalle-horario="${horarioEl.turno}" data-detalle-dia="${element.dia}. (${element.fecha}) ">
                                                                          ${horarioEl.cuposDisponibles} Cupos
                                                                    </button>`
                                          }
                                      })
                                      if (textoDiponibilidad == "") {
                                        agendagrafica +=  `<td>No Disponible</td>`
                                      }else{
                                        agendagrafica +=  `<td> ${textoDiponibilidad}</td>`
                                      }
                                      

                                    });
                agendagrafica +=  ` </tr>`
            })

            agendagrafica += `</tbody>`
            agendagrafica += `</table></div>`

            $("#resultAgendaGrafico").html(agendagrafica)

           

            //console.log("El resultado es: ", data)
        })
        .fail(function(jqXHR, textStatus, errorThrown){
            //console.log("Error:",jqXHR, textStatus, errorThrown)
            //PREAGENDA_PROCESO_ACTUAL = ""
            //clearInterval(timerUpdate);
            $("#preLoadAgendaSend").html(``)
            $("#resultPreAgendaContent").removeClass("d-none")
            $("#resultAgendaGrafico").html(``)
            $("#resultFormProcesoPreAgendar").html(``)

            //$("#resultFormProcesoPreAgendar").html(jqXHR.responseText)
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

                let textoReAgenda = ""
                if (parseInt(ID_TIPOAGENDA_ACTIVA) > 0) {
                  textoReAgenda = `</br> Puede reintentar listar los horarios de la reagenda desde 
                                            <a class="btn btn-warning btn-sm shadow-sm m-1" id="reintentarListarHorariosDisponiblesReagenda"> Aquí </a>`
                }
    
              // $("#body-errors-modal").html(`<div class="w-100 text-center text-danger">${erroresPeticion}</div>`)
              // $('#errorsModal').modal('show')
                  //resultFormProcesoPreAgendar
                $("#resultAgendaGrafico").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                      <span aria-hidden="true">&times;</span>
                                                  </button>
                                                  ${erroresPeticion}.${textoReAgenda}</div>`); 
           
            
            return false

        })

      }

      //Reintentar listar horarios de reagenda en caso de no cargar correctamente
      $("body").on("click","#reintentarListarHorariosDisponiblesReagenda", function(){

        let tipoDeAgenda = $("#tipoDeAgenda").val()
        // let diaDeAgenda = $("#diaDeAgenda").val()
         //let idCliente = $(this).data("uno")
         let idCliente = IDCLIENTE_AGENDAR
         let nodo = NODO_AGENDAR

         if (nodo == "") {
          //$("#preLoadAgendaSend").html(``)
          //$("#resultPreAgendaContent").html(``)
           $("#body-errors-modal").html(`<div class="w-100 text-center text-danger">No se puede identificar el NODO, intente ingresar nuevamente.</div>`)
           $('#errorsModal').modal('show')
          // peticiones.redirectTabs($("#multiconsultaTab")); 
           return false
         }

         let parametros = {
                 tipoDeAgenda,
                // diaDeAgenda,
                 idCliente,
                 nodo
         }

         //console.log("el valor del timerUpdate es: ",timerUpdate)
         if (timerUpdate != null) {
           clearInterval(timerUpdate);
           //console.log("se detuvo el timerUpdate")
         }

         cargarTurnosYDiasGeneralesDisponibles(parametros)

      })

      //INICIO GRAFICO
        $("body").on("click",".selectedCupoAgenda", function(){
            //console.log("Deberia iniciar la reserva del cupo...")
            //let tipoDeAgenda = $("#tipoDeAgenda").val()
            let dia = $(this).data("uno")
            let tipoTurno = $(this).data("dos")
            let nodo = $(this).data("tres")
            let idCliente = $(this).data("cuatro")

            let detalleHorario = $(this).data("detalleHorario")
            let detalleDia = $(this).data("detalleDia")
            

             /*console.log("La data a enviar es: ",dia,
                        tipoTurno,
                        nodo,
                        idCliente,detalleHorario,detalleDia)*/

            $("#body-detalles-modal").html(`
                <div id="preload_info_agenda_turno">
                </div>
                <section id="content_info_agenda_turno">
                  <div class="w-100 table-responsive">
                    <table class="w-75 m-auto table table-hover">
                      <tr><td>Día</td><td>${detalleDia}</td></tr>
                      <tr><td>Horario</td><td>${detalleHorario}</td></tr>
                    </table>
                  </div>
                  <div class="w-100 row m-0 justify-content-center">
                    <button id="reservarTurnoSeleccionado" data-uno="${dia}" data-dos="${tipoTurno}"
                            data-tres="${nodo}" data-cuatro="${idCliente}"
                    class="btn btn-sm btn-outline-info shadow-sm m-1 col-sm-6 col-md-4">Reservar Cupo</button>
                    <button type="button" class="btn btn-sm btn-outline-danger shadow-sm m-1 col-sm-6 col-md-4" data-dismiss="modal">Cancelar</button>
                  </div>
                </section>
            `)
            $("#detallesModal").modal("show")
 
        })
        
        $("body").on("click","#reservarTurnoSeleccionado", function(){
          //console.log("Debería procesar el turno y registrando su cupo por nodo por 5 minutos...")

          

          /*$("#preload_info_agenda_turno").html(`<div id="carga_person">
                                                    <div class="loader">Loading...</div>
                                                </div>`)*/
          $("#preload_info_agenda_turno").html(`<div class="d-flex justify-content-center align-content-center flex-wrap w-100">
                                                      <div class="spinner-border" role="status" style="width: 80px;height: 80px;">
                                                      <span class="sr-only">Loading...</span>
                                                      </div>
                                                      <div class="text-center w-100">
                                                          <strong>Separando Cupo</strong>
                                                      </div>
                                                  </div>`)
          $("#content_info_agenda_turno").addClass("d-none")

          

         // let tipoDeAgenda = $("#tipoDeAgenda").val()
          let dia = $(this).data("uno")
          let tipoTurno = $(this).data("dos")
          let nodo = $(this).data("tres")
          let idCliente = $(this).data("cuatro")

          $.ajax({
            url:`/administrador/multiconsulta/agenda/verificar-cupos`,
            method:"get",
            async: true,
            data:{
               // tipoDeAgenda,
                tipoTurno,
                dia,
                nodo,
                idCliente
            },
            cache: false, 
            dataType: "json", 
          })
          .done(function(data){

            $("#detallesModal").modal("hide")
            $("#preload_info_agenda_turno").html("")
            $("#content_info_agenda_turno").html("")

            PREAGENDA_PROCESO_ACTUAL = VIEW_FORMULARIO_CUPO

            //console.log("el valor del timerUpdate es: ",timerUpdate)
            if (timerUpdate != null) {
              clearInterval(timerUpdate);
              //console.log("se detuvo el timerUpdate")
            }

            countdown(getFechaRegresivoPorMinutos(TIEMPO_RESTANTE_MINUTOS), 'contadorRegresivoAgendaFinal');

            //console.log("el resultado es: ", data)

            $("#resultAgendaGrafico").html("")
            $("#resultFormProcesoPreAgendar").html(`
                <section class="form row my-2 mx-0" id="contenedor_formulario_agenda_principal">
                    <div class="form-group row m-1 px-2 col-12 col-sm-12 text-center text-primary justify-content-center">
                              <div class="w-100 text-center  alert alert-warning p-2 fade show" role="alert">
                                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                  </button>
                                  En estos momentos tiene un cupo asignado a su cliente, reserve su agenda antes de que el <strong class="text-secondary"> tiempo restante </strong> termine. 
                              </div>

                         
                    </div>
                    <div class="form-group row m-1 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                        <label for="diaPreAgenda" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Día: </label>
                        <span class="form-control form-control-sm col-sm-7  col-md-8 bg-secondary">${data.response.detalleDia.dia} - ${data.response.detalleDia.fecha}</span> 
                        <input type="hidden" id="diaPreAgenda" value="${data.response.detalleDia.fecha}" />
                    </div>
                    <div class="form-group row m-1 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                        <label for="horarioPreAgenda" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Hora: </label>
                        <span class="form-control form-control-sm col-sm-7  col-md-8 bg-secondary">${data.response.detalleHora.turno}</span>  
                        <input type="hidden" id="tipoTurnoPreAgenda" value="${data.response.detalleHora.id}" />
                        <input type="hidden" id="idRangoPreAgenda" value="${data.response.detalleHora.idturno}" />
                        <input type="hidden" id="nodoPreAgenda" value="${nodo}" />
                    </div>
                    <div class="form-group row m-1 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                        <label for="telefonoFijoPreAgenda" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Telf. Fijo: </label>
                        <input type="text" name="telefonoFijoPreAgenda" id="telefonoFijoPreAgenda"  class="col-sm-7  col-md-8 form-control form-control-sm  validateText">
                    </div>
                    <div class="form-group row m-1 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                        <label for="telefonoMovilPreAgenda" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Telf. Movil: </label>
                        <input type="text" name="telefonoMovilPreAgenda" id="telefonoMovilPreAgenda"  class="col-sm-7  col-md-8 form-control form-control-sm  validateText">
                    </div>
                    <div class="form-group row m-1 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                        <label for="codigoReqPreAgenda" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Cod. Requerimiento: </label>
                        <input type="text" name="codigoReqPreAgenda" id="codigoReqPreAgenda"  class="col-sm-7  col-md-8 form-control form-control-sm  validateText">
                    </div>
                    <div class="form-group row m-1 px-2 col-12 col-sm-12">
                        <label for="observacionesPreAgenda" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Observaciones: </label>
                        <textarea name="observacionesPreAgenda" id="observacionesPreAgenda" class="col-sm-12 form-control form-control-sm  validateText" cols="30" rows="5"></textarea>
                    </div>
                    <div class="form-group row mt-2 px-2 col-12 col-sm-12 justify-content-center">
                        <a href="javascript:void(0)" id="procesarPreagendaFormSend" class="btn btn-sm btn-outline-success ">Guardar Agenda</a>
                    </div>
                </section>
            `)

            //Inicio de restriccion de clicks
            /* 
                var BLOQUEO_RESERVACION_AGENDA = null
                var TARGET = null
            */
            //console.log("el target es: ",TARGET)
            //console.log("El bloquero de click es: ",BLOQUEO_RESERVACION_AGENDA)

            if (TARGET != null) {
              //console.log("el target closest es: ",TARGET.closest('#contenedor_formulario_agenda_principal'))
                BLOQUEO_RESERVACION_AGENDA = true 
            }

            //console.log("Ahora el bloquero de click es: ",BLOQUEO_RESERVACION_AGENDA)

           
           // console.log("el target id modal es es: ",$target[0].attributes[0].nodeValue, "la validacion es: ",$target[0].attributes[0].nodeValue !== "abandonarCupoReservado")
            if (parseInt(ID_TIPOAGENDA_ACTIVA) == 0) {
              $("#tipoDeAgenda").prop("disabled",true)
            }
           
 
          })
          .fail(function(jqXHR, textStatus, errorThrown){
            //console.log("Error: ",jqXHR, textStatus, errorThrown)

           // PREAGENDA_PROCESO_ACTUAL = ""
            //clearInterval(timerUpdate);
             
            $("#preload_info_agenda_turno").html("")
            $("#content_info_agenda_turno").removeClass("d-none")

            // $("#preload_info_agenda_turno").html(jqXHR.responseText)
            // $("#content_info_agenda_turno").removeClass("d-none")
 
            //return false

            let erroresPeticion =""
            if(jqXHR.responseJSON){
                if(jqXHR.responseJSON.mensaje){
                    let erroresMensaje = jqXHR.responseJSON.mensaje  //captura objeto
                    let mensaje = errors.mensajeErrorJson(erroresMensaje)
                    erroresPeticion += mensaje 
                } 
            }
            /*if(jqXHR.status){
                let mensaje = errors.codigos(jqXHR.status)
                erroresPeticion += "<br> "+mensaje
            }*/
            erroresPeticion = (erroresPeticion.trim() == 0) ? "hubo un error en el servicio, intente nuevamente." : erroresPeticion

           //$("#body-errors-modal").html(`<div class="w-100 text-center text-danger">${erroresPeticion}</div>`)
           //$('#errorsModal').modal('show')
    
            $("#preload_info_agenda_turno").html(`<div class="container p-1 text-center font-weight-bold alert alert-danger fade show" role="alert">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                ${erroresPeticion}</div>`); 
            return false

          })

        })
      //END GRAFICO

      $("body").on("click","#abandonarCupoReservado", function(){
            BLOQUEO_RESERVACION_AGENDA = false
            //console.log("aqui estamos, se deberia haber limpiado el bloqueo de click, el bloqueo esta en: ",BLOQUEO_RESERVACION_AGENDA)
            //console.log("Inicia retiro de cupo y limpiado de formulario..")

            //Verificar Estado de proceso
            if (PREAGENDA_PROCESO_ACTUAL == VIEW_HORARIO_CUPOS) {
    
              //console.log("El proceso pre agenda esta en vista de horarios de todos los cupos, deberia actualizar el horario llamando a la function cargarTurnosYDiasGeneralesDisponibles")
     
              let tipoDeAgenda = $("#tipoDeAgenda").val() 
              let idCliente = IDCLIENTE_AGENDAR
              let nodo = NODO_AGENDAR
    
              if (nodo == "") {
                //$("#preLoadAgendaSend").html(``)
                //$("#resultPreAgendaContent").html(``)
                $("#body-errors-modal").html(`<div class="w-100 text-center text-danger">No se puede identificar el NODO, intente ingresar nuevamente.</div>`)
                $('#errorsModal').modal('show')
                peticiones.redirectTabs($("#multiconsultaTab")); 
                return false
              }
    
              let parametros = {
                      tipoDeAgenda,
                      idCliente,
                      nodo
              }

              $("#detallesModal").modal("hide")
    
              cargarTurnosYDiasGeneralesDisponibles(parametros)
    
              return false
            }else if (PREAGENDA_PROCESO_ACTUAL == VIEW_FORMULARIO_CUPO) {
              //console.log("El proceso pre agenda esta en  vista del formulario de cupo reservado, deberia mostrar un modal indicando que se reseteará la reserva del cupo.")
              $("#detallesModal").modal("hide")
              resetarReservaCupoNodo()
               
              return false
            }

      })

     
    
    $("body").on("click","#procesarPreagendaFormSend", function(){

      let telefonoFijo = $("#telefonoFijoPreAgenda").val()
      let telefonoMovil = $("#telefonoMovilPreAgenda").val()
      let codigoRequerimiento = $("#codigoReqPreAgenda").val()
      let observaciones = $("#observacionesPreAgenda").val()

      let idCliente = IDCLIENTE_AGENDAR
      let servicioCliente = SERVICIO_CLIENTE
      let nodo = NODO_AGENDAR
      let nombreCliente = NOMBRE_CLIENTE

      let fechaDia = $("#diaPreAgenda").val()
      let turnoHorario = $("#tipoTurnoPreAgenda").val()
      let idRangoHorario = $("#idRangoPreAgenda").val()
      let tipoAgendaCliente = $("#tipoDeAgenda").val()

      let EstadoAgendaProcesar = "agendar"
      let idAgendaActiva = 0

      if (parseInt(ID_TIPOAGENDA_ACTIVA) > 0) {
        EstadoAgendaProcesar = "preagendar"
        idAgendaActiva = ID_AGENDA_ACTIVA
      }

      $("#preLoadAgendaSend").html(`<div id="carga_person">
                                        <div class="loader">Loading...</div>
                                    </div>`)
      $("#resultPreAgendaContent").addClass("d-none")
      $("#resultAgendaGrafico").html(``)

      $.ajax({
        url:`/administrador/multiconsulta/agenda/store`,
        method:"post",
        async: true,
        data:{
          telefonoFijo,
          telefonoMovil,
          codigoRequerimiento,
          observaciones,
          idCliente,
          servicioCliente,
          nodo,
          nombreCliente,
          fechaDia,
          turnoHorario,
          idRangoHorario,
          tipoAgendaCliente,
          EstadoAgendaProcesar,
          idAgendaActiva
        },
        cache: false, 
        dataType: "json", 
      })
      .done(function(data){
        //console.log("el resultado es: ", data)
        $("#preLoadAgendaSend").html(``)
        $("#resultPreAgendaContent").removeClass("d-none")
        $("#resultPreAgendaContent").html(``)
        $("#resultAgendaGrafico").html(``)

        if (timerUpdate != null) {
          clearInterval(timerUpdate);
         // console.log("se detuvo el timerUpdate")
        }

        BLOQUEO_RESERVACION_AGENDA = false 
        $("#body-success-modal").html(`<div class="w-100 text-center text-success">${data.mensaje}</div>`)
        $("#successModal").modal("show")
        cargaPrincipalAgendaDetalles()

         
      })
      .fail(function(jqXHR, textStatus, errorThrown){
        //console.log("Error: ",jqXHR, textStatus, errorThrown)

        $("#preLoadAgendaSend").html(``)
        $("#resultAgendaGrafico").html(``)
        $("#resultPreAgendaContent").removeClass("d-none")
 
        //$("#preLoadAgendaSend").html(jqXHR.responseText)
        //    return false

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
            erroresPeticion += "<br> "+ mensaje
        }
        erroresPeticion = (erroresPeticion.trim() == 0) ? "hubo un error en el servicio, intente nuevamente." : erroresPeticion

       //$("#body-errors-modal").html(`<div class="w-100 text-center text-danger">${erroresPeticion}</div>`)
       //$('#errorsModal').modal('show')

        $("#preLoadAgendaSend").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            ${erroresPeticion}</div>`); 
        return false

      })



    })

    
    //Cuenta regresiva
    const getTime = dateTo => {
      let now = new Date(),
          time = (new Date(dateTo) - now + 1000) / 1000,
          seconds = ('0' + Math.floor(time % 60)).slice(-2),
          minutes = ('0' + Math.floor(time / 60 % 60)).slice(-2),
          hours = ('0' + Math.floor(time / 3600 % 24)).slice(-2),
          days = Math.floor(time / (3600 * 24));
    
      return {
          seconds,
          minutes,
          //hours,
          //days,
          time
      }
    };
     
    const countdown = (dateTo, element) => {
      const item = document.getElementById(element);
    
      timerUpdate = setInterval( () => {
          let currenTime = getTime(dateTo);
          item.innerHTML = `   ${currenTime.minutes} :  ${currenTime.seconds} `;
    
          if (currenTime.time <= 1) {
              clearInterval(timerUpdate);
             // alert('Fin de la cuenta '+ element);
            /* console.log(`Se termino el tiempo debería preguntar si desea mantener la reserva del cupo o ya no p
                        para mandar un ajax de descontar cupo`)*/
            //console.log("El estado actual es: ",PREAGENDA_PROCESO_ACTUAL)
    
            if (PREAGENDA_PROCESO_ACTUAL == "") {
                //console.log("El proceso pre agenda esta vacio, por lo tanto debe estar en stop el cronometro")
                return false
            }else if (PREAGENDA_PROCESO_ACTUAL == VIEW_HORARIO_CUPOS) {
    
              //console.log("El proceso pre agenda esta en vista de horarios de todos los cupos, deberia actualizar el horario llamando a la function cargarTurnosYDiasGeneralesDisponibles")
    
               
              let tipoDeAgenda = $("#tipoDeAgenda").val() 
              let idCliente = IDCLIENTE_AGENDAR
              let nodo = NODO_AGENDAR
    
              if (nodo == "") {
                //$("#preLoadAgendaSend").html(``)
                //$("#resultPreAgendaContent").html(``)
                $("#body-errors-modal").html(`<div class="w-100 text-center text-danger">No se puede identificar el NODO, intente ingresar nuevamente.</div>`)
                $('#errorsModal').modal('show')
              // peticiones.redirectTabs($("#multiconsultaTab")); 
                return false
              }
    
              let parametros = {
                      tipoDeAgenda,
                      idCliente,
                      nodo
              }

              $("#detallesModal").modal("hide")
    
              cargarTurnosYDiasGeneralesDisponibles(parametros)
    
              return false
            }else if (PREAGENDA_PROCESO_ACTUAL == VIEW_FORMULARIO_CUPO) {
              //console.log("El proceso pre agenda esta en  vista del formulario de cupo reservado, deberia mostrar un modal indicando que se reseteará la reserva del cupo.")
              resetarReservaCupoNodo()
              
              return false
            }
    
            //console.log("No ingreso a ninguna opcion de los procesos,",PREAGENDA_PROCESO_ACTUAL)
    
    
          }
    
      }, 1000);
    };
 
    function getFechaRegresivoPorMinutos(number) {
      var fecha = new Date();
      return fecha.setMinutes(fecha.getMinutes() + number);
        
    }

    function resetarReservaCupoNodo()
    {
 
      let tipoTurno = $("#tipoTurnoPreAgenda").val()
      let dia = $("#diaPreAgenda").val()
      let nodo = $("#nodoPreAgenda").val()

      if (timerUpdate != null) {
        clearInterval(timerUpdate);
        //console.log("se detuvo el timerUpdate")
        $("#contadorRegresivoAgendaFinal").html("00:00")
      }

      //console.log("la data a enviar es: ",tipoTurno,dia,nodo)

      $("#preLoadAgendaSend").html(`<div id="carga_person">
                                        <div class="loader">Loading...</div>
                                    </div>`);
      $("#resultPreAgendaContent").addClass("d-none")
      $("#resultAgendaGrafico").html("")
      $("#contenedor_formulario_agenda_principal").html("")

      $.ajax({
        url:`/administrador/multiconsulta/agenda/quitar-cupos`,
        method:"post",
        async: true,
        data:{
            tipoTurno,
            dia,
            nodo
        },
        cache: false, 
        dataType: "json", 
      })
      .done(function(data){
        //console.log("el resultado es: ", data)

        BLOQUEO_RESERVACION_AGENDA = false

        if (parseInt(ID_TIPOAGENDA_ACTIVA) == 0) {
          $("#preLoadAgendaSend").html("");
          $("#resultPreAgendaContent").removeClass("d-none")
          $("#body-errors-modal").html(`<div class="w-100 text-center text-danger">Su cupo reservado fue retirado.</div>`)
          $('#errorsModal').modal('show')

          $("#tipoDeAgenda").prop("disabled",false)
          $("#tipoDeAgenda").val("seleccionar")

        }else{
          $("#resultPreAgendaContent").removeClass("d-none")
          cargaPrincipalAgendaDetalles()
        }

        
 
        //peticiones.redirectTabs($("#multiconsultaTab"));

        
  

      })
      /*.fail(function(jqXHR, textStatus, errorThrown){
        //console.log("Error: ",jqXHR, textStatus, errorThrown)

        $("#preLoadAgendaSend").html("");

        $("#body-errors-modal").html(`<div class="w-100 text-center text-danger">Su tiempo de cupo reservado termino, intente nuevamente.</div>`)
        $('#errorsModal').modal('show')

        peticiones.redirectTabs($("#multiconsultaTab"));

        return false

      })*/

     
 
    }


    //REAGENDAR
    $("body").on("click","#reAgendarClienteMulti", function(){

      let idAgenda = $(this).data("uno")
      let idCliente = $(this).data("dos")
      let sw = $(this).data("tres")

      $("#body-detalles-modal").html(`
                <section id="contene_info_reagendar_redirect">
                  <div class="w-100 text-danger text-center">
                        
                            Recuerde que se eliminará su cupo actual cancelando su agenda y se iniciará una nueva disponible. 
                        <br> ¿Está seguro de reagendar?.
                  </div>
                  <div class="w-100 row m-0 justify-content-center">
                    <button id="redirectReagendarBtn"  data-uno="${idAgenda}" data-dos="${idCliente}" data-tres="${sw}"
                          class="btn btn-sm btn-outline-info shadow-sm m-1 col-sm-6 col-md-4">Reagendar</button>
                    <button type="button" class="btn btn-sm btn-outline-danger shadow-sm m-1 col-sm-6 col-md-4" data-dismiss="modal">Cancelar</button>
                  </div>
                </section>
            `)
      $("#detallesModal").modal("show")

    })

    $("body").on("click", "#redirectReagendarBtn", function(){
        $("#detallesModal").modal("hide")
        cargaPrincipalAgendaDetalles("reagendar")
    })

    
      
     
    
})