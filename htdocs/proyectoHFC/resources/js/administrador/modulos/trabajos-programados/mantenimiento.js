import peticiones from './peticiones.js'
import errors from  "@/globalResources/errors.js"

var TIPO_DE_TRABAJOS_H_SIN_ASIGNAR = []
var TIPO_DE_TRABAJOS_H_ASIGNADO = []

$(function(){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('[name="SearchDualListTrabajos1"]').keyup(function (e) {
        var code = e.keyCode || e.which;
         
        //if (code == '9') return;
        //if (code == '27') $(this).val(null);
        //var $rows = $(this).closest('.dual-list').find('#interfacesLista option');
        
        if (code == 13) {
            $(this).prop("disabled",true)

            let palabraBusca = $(this).val() 
            if (palabraBusca.trim() != "") {
               
                $("#tiposTrabajosNoAsig").html(``) 
               
                TIPO_DE_TRABAJOS_H_SIN_ASIGNAR.forEach(el => { 
                    if (el.tipodetrabajo1.toLowerCase().indexOf(palabraBusca.toLowerCase()) != -1) {
                        $("#tiposTrabajosNoAsig").append(`<option value="${el.id}">${el.tipodetrabajo1}</option>`)
                    } 
                })  

            }
            $(this).prop("disabled",false)
        } 

        if ($(this).val() == "" && code != 13) {
            $(this).prop("disabled",true) 
            //$(this).prop("disabled",true)
           // document.getElementById().disabled = true
            $("#tiposTrabajosNoAsig").html(``) 
            TIPO_DE_TRABAJOS_H_SIN_ASIGNAR.forEach(el => { 
                $("#tiposTrabajosNoAsig").append(`<option value="${el.id}">${el.tipodetrabajo1}</option>`) 
            }) 
            $(this).prop("disabled",false)
        }

        $(this).focus()
       
        
    });


    $('[name="SearchDualListTrabajo2"]').keyup(function (e) {
        var code = e.keyCode || e.which;
       
        if (code == '9') return;
        if (code == '27') $(this).val(null);
        var $rows = $(this).closest('.dual-list').find('#tiposTrabajosAsig option');
        var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
        $rows.show().filter(function () {
            var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
            return !~text.indexOf(val);
        }).hide();
    });


    $("#btnLeftTrabajos").click(function(){
        let datos1 = document.getElementById("tiposTrabajosNoAsig");
        let datos2 = document.getElementById("tiposTrabajosAsig");
        let collection = datos2.selectedOptions;
        let cantidad = collection.length;

        for (let i = 0; i < cantidad; i++) {
            let valor = collection[i].text;
            let option = document.createElement('option');
            option.value = valor;
            option.text = collection[i].text;
            datos1.add(option);
        }

        //console.log("El tipo 1 antes de procesar es: ",TIPO_DE_TRABAJOS_H_SIN_ASIGNAR)
        //console.log("El tipo 2 antes de procesar es: ",TIPO_DE_TRABAJOS_H_ASIGNADO)
            
        $.each($('[name="duallistbox_Trabajos2"] option:selected'), function( index, value ) { 
            let nuevoArrayInterfaces = TIPO_DE_TRABAJOS_H_ASIGNADO.filter(palabra => { 
                //console.log("La comparatica es: ",palabra.idtrabajos, "con: ",value.value) //
                    if (palabra.idtrabajos != value.value ) {
                        return {
                            "idsupervisor":palabra.idsupervisor,
                            "idtrabajos":palabra.id,
                            "tipodetrabajo1":palabra.tipodetrabajo1
                        }
                    }else{
                        TIPO_DE_TRABAJOS_H_SIN_ASIGNAR.push({
                            "id":palabra.idtrabajos,
                            "tipodetrabajo1":palabra.tipodetrabajo1
                        })
                    }
                });
                TIPO_DE_TRABAJOS_H_ASIGNADO = nuevoArrayInterfaces
  
            $(this).remove();
        }); 

        //console.log("El tipo 1 es: ",TIPO_DE_TRABAJOS_H_SIN_ASIGNAR)
        //console.log("El tipo 2 es: ",TIPO_DE_TRABAJOS_H_ASIGNADO)
    });
 
    $("#btnRightTrabajos").click(function(){
        let datos1 = document.getElementById("tiposTrabajosNoAsig");
        let datos2 = document.getElementById("tiposTrabajosAsig");
        let collection = datos1.selectedOptions;
        let cantidad = collection.length;

        for (let i = 0; i < cantidad; i++) {
            //console.log("collection es: ",collection[i].value)
            let valor = collection[i].value;
            let option = document.createElement('option');
            option.value = valor;
            option.text = collection[i].text;
            datos2.add(option);
        }
            
        $.each($('[name="duallistbox_trabajos1"] option:selected'), function( index, value ) {  
            let nuevoArrayInterfaces = TIPO_DE_TRABAJOS_H_SIN_ASIGNAR.filter(palabra => { 
                 //console.log("La palabra es: ",palabra) 
                    if (palabra.id != value.value ) {
                        return {
                            "id":palabra.id,
                            "tipodetrabajo1":palabra.tipodetrabajo1
                        }
                    }else{
                        TIPO_DE_TRABAJOS_H_ASIGNADO.push({
                            "idsupervisor":0,
                            "idtrabajos":palabra.id,
                            "tipodetrabajo1":palabra.tipodetrabajo1
                        })
                    }
                });
                TIPO_DE_TRABAJOS_H_SIN_ASIGNAR = nuevoArrayInterfaces

            $(this).remove();
        });

       //console.log("el sin asignar array es: ",TIPO_DE_TRABAJOS_H_SIN_ASIGNAR)
       //console.log("el asignado  array sera: ",TIPO_DE_TRABAJOS_H_ASIGNADO)
    });


    $("#redirectMantenimientoTP").click(function(){
        peticiones.redirectTabs($('#mantenimientoTProgTab')) 
    })

    //NODO
    $("#GUARDAR_NODO").click(function(){
         
        let nodo = $("#NEW_NODO").val();
        let troba = $("#NEW_TROBA").val();

         let expresion_nodo =  /^[a-zA-Z0-9]+$/;
         let expresion_troba =  /^[0-9]+$/;

        if(!expresion_nodo.test(nodo)) {
            $("#body-errors-modal").html(`<div class="w-100 text-danger text-center">El nodo no tiene un formato válido.</div>`)
            $("#errorsModal").modal("show") 
            return false
        }  
        if(!expresion_troba.test(troba)) {
            $("#body-errors-modal").html(`<div class="w-100 text-danger text-center">La troba no tiene un formato válido.</div>`)
            $("#errorsModal").modal("show")  
            return false
        }  

        $("#preloadMantenimientoNodoTroba").html(`<div id="carga_person">
                                                    <div class="loader">Loading...</div>
                                                </div>`)
        $("#form_mantenimiento_nodos_trobas").addClass("d-none")
        
        $.ajax({
            url: "/administrador/trabajos-programados/mantenimiento/nodos-trobas",
            method: "post",
            data: { 
                nodo,
                troba
             },
            dataType:"json",
          })
        .done(function(data) {
           // console.log("La da es: ",data)

            $("#NEW_NODO").val("");
            $("#NEW_TROBA").val("");

            $("#preloadMantenimientoNodoTroba").html(``)
            $("#form_mantenimiento_nodos_trobas").removeClass("d-none")

            $("#result_r_nodo_troba").html(`<div class="container text-center font-weight-bold alert alert-success fade show" role="alert">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                ${data.mensaje}</div>`)
           
        })
        .fail(function( jqXHR, textStatus ) {
              //console.log( "Error: " ,jqXHR, textStatus); 
            // console.log( "Request failed: " ,jqXHR.responseJSON.mensaje);
             //$("#result_r_nodo_troba").html(jqXHR.responseText)
             //return false

            $("#preloadMantenimientoNodoTroba").html(``)
            $("#form_mantenimiento_nodos_trobas").removeClass("d-none")
 
            let erroresPeticion =""
            if(jqXHR.status){
                let mensaje = errors.codigos(jqXHR.status)
                erroresPeticion += `<strong> ${mensaje} </strong>` 
            }
            if(jqXHR.responseJSON){
                if(jqXHR.responseJSON.mensaje){
                    let erroresMensaje = jqXHR.responseJSON.mensaje  //captura objeto
                    let mensaje = errors.mensajeErrorJson(erroresMensaje)
                    erroresPeticion += "<br>"+ mensaje 
                } 
            }
            
            erroresPeticion = (erroresPeticion.trim() == 0) ? "hubo un error en el servicio, intente nuevamente." : erroresPeticion
     
            $("#result_r_nodo_troba").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            ${erroresPeticion}</div>`)

            return false
        });;

    });

    //TIPO DE TRABAJO
    $("#GUARDAR_TRABAJO").click(function(){
        
        let tipoDeTrabajo = $("#NEW_TRABAJO").val(); 
 
         let expresion_t_trab = /^[a-zA-Z0-9 _-]+$/;
        if(!expresion_t_trab.test(tipoDeTrabajo)) {
            $("#body-errors-modal").html(`<div class="w-100 text-danger text-center">El tipo de trabajo no tiene un formato válido.</div>`)
            $("#errorsModal").modal("show") 
            return false 
        } 
         
        $("#preloadMantenimientoTipoTrabajo").html(`<div id="carga_person">
                                                    <div class="loader">Loading...</div>
                                                </div>`)
        $("#form_mantenimiento_tipo_trabajo").addClass("d-none")
         
        $.ajax({
            url: "/administrador/trabajos-programados/mantenimiento/tipo-trabajo",
            method: "post",
            data: { 
                tipoDeTrabajo
             },
             dataType:"json",
          })
        .done(function(data) {
            //console.log("result de tipo de trabaj: ",data)

            $("#preloadMantenimientoTipoTrabajo").html(``)
            $("#form_mantenimiento_tipo_trabajo").removeClass("d-none")

            $("#NEW_TRABAJO").val("")

            $("#result_r_tipo_trabajos").html(`<div class="container text-center font-weight-bold alert alert-success fade show" role="alert">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                ${data.mensaje}</div>`)
 
        })
        .fail(function( jqXHR, textStatus ) {
              //console.log( "Error: " ,jqXHR, textStatus); 
            // console.log( "Request failed: " ,jqXHR.responseJSON.mensaje);
            $("#preloadMantenimientoTipoTrabajo").html(``)
            $("#form_mantenimiento_tipo_trabajo").removeClass("d-none")

             //$("#result_r_tipo_trabajos").html(jqXHR.responseText)
             //return false
 
             let erroresPeticion =""
             if(jqXHR.status){
                 let mensaje = errors.codigos(jqXHR.status)
                 erroresPeticion += `<strong> ${mensaje} </strong>` 
             }
             if(jqXHR.responseJSON){
                 if(jqXHR.responseJSON.mensaje){
                     let erroresMensaje = jqXHR.responseJSON.mensaje  //captura objeto
                     let mensaje = errors.mensajeErrorJson(erroresMensaje)
                     erroresPeticion += "<br>"+ mensaje 
                 } 
             }
             
             erroresPeticion = (erroresPeticion.trim() == 0) ? "hubo un error en el servicio, intente nuevamente." : erroresPeticion
      
             $("#result_r_tipo_trabajos").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
             <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                 <span aria-hidden="true">&times;</span>
             </button>
             ${erroresPeticion}</div>`)
 
             return false
        });;

    });

    //SUPERVISOR
    $("#GUARDAR_SUPERVISOR").click(function(){
        
        let supervisor = $("#new_supervisor").val(); 
 
       let expresion_t_trab = /^[a-zA-Z ]+$/;
        if(!expresion_t_trab.test(supervisor)) {
            $("#body-errors-modal").html(`<div class="w-100 text-danger text-center">El supervisor no tiene un formato válido.</div>`)
            $("#errorsModal").modal("show") 
            return false 
        } 

        $("#preloadMantenimientoSupervidor").html(`<div id="carga_person">
                                                    <div class="loader">Loading...</div>
                                                </div>`)
        $("#form_mantenimiento_supervisor").addClass("d-none")
         
        $.ajax({
            url: "/administrador/trabajos-programados/mantenimiento/supervisor",
            method: "post",
            data: { 
                supervisor
             },
            dataType:"json",
          })
        .done(function(data) {
 
            //console.log(data)

            $("#preloadMantenimientoSupervidor").html(``)
            $("#form_mantenimiento_supervisor").removeClass("d-none")

            $("#new_supervisor").val("")

            $("#result_r_supervisor").html(`<div class="container text-center font-weight-bold alert alert-success fade show" role="alert">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                ${data.mensaje}</div>`)
 
 
        })
        .fail(function( jqXHR, textStatus ) {
               //console.log( "Error: " ,jqXHR, textStatus); 
            // console.log( "Request failed: " ,jqXHR.responseJSON.mensaje);
            $("#preloadMantenimientoSupervidor").html(``)
            $("#form_mantenimiento_supervisor").removeClass("d-none")

             //$("#result_r_supervisor").html(jqXHR.responseText)
             // return false
 
             let erroresPeticion =""
             if(jqXHR.status){
                 let mensaje = errors.codigos(jqXHR.status)
                 erroresPeticion += `<strong> ${mensaje} </strong>` 
             }
             if(jqXHR.responseJSON){
                 if(jqXHR.responseJSON.mensaje){
                     let erroresMensaje = jqXHR.responseJSON.mensaje  //captura objeto
                     let mensaje = errors.mensajeErrorJson(erroresMensaje)
                     erroresPeticion += "<br>"+ mensaje 
                 } 
             }
             
             erroresPeticion = (erroresPeticion.trim() == 0) ? "hubo un error en el servicio, intente nuevamente." : erroresPeticion
      
             $("#result_r_supervisor").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
             <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                 <span aria-hidden="true">&times;</span>
             </button>
             ${erroresPeticion}</div>`)
 
             return false
        });;

    });

    //ASIGNAR TIPO DE TRABAJOS A SUPERVISOR
    $("#mnto_supervisor").change(function(){
        
        let supervisor = $("#mnto_supervisor").val(); 
        let texto_supervisor = $("#mnto_supervisor option:selected").text();
        
        if(supervisor.trim() == "seleccionar") {
            $("#body-errors-modal").html(`<div class="w-100 text-danger text-center">Seleccione un supervisor válido.</div>`)
            $("#errorsModal").modal("show") 
            return false  
        } 

        TIPO_DE_TRABAJOS_H_SIN_ASIGNAR = []
        TIPO_DE_TRABAJOS_H_ASIGNADO = []
 
       
        trabajosNoAsignadosGet(supervisor,texto_supervisor)

    });

    function trabajosNoAsignadosGet(supervisor,texto_supervisor){
 
        $("#preloadMantenimientoSupervidorTipoTrabajo").html(`<div id="carga_person">
                                                                <div class="loader">Loading...</div>
                                                            </div>`)
        $("#form_mantenimiento_supervisor_tipo_trabajo").addClass("d-none")
         

        $.ajax({
            url: `/administrador/trabajos-programados/supervisor/${supervisor}/tipo-trabajos/list`,
            method: "get",
            dataType:"json",
          })
        .done(function(data) {
           // console.log("La data de tipo de trabajos dispnibles son: ",data)
            $("#preloadMantenimientoSupervidorTipoTrabajo").html("")
            $("#form_mantenimiento_supervisor_tipo_trabajo").removeClass("d-none")

            let resultado = data.response
            let estructuraSinAsig = ``
            let estructuraAsig = ``

            if (resultado.listadoSinAsignar.length > 0) {
               
                resultado.listadoSinAsignar.forEach(el => {
                    estructuraSinAsig += `<option value="${el.id}">${el.tipodetrabajo1}</option>`
                    TIPO_DE_TRABAJOS_H_SIN_ASIGNAR.push({
                        "id":el.id,
                        "tipodetrabajo1":el.tipodetrabajo1
                    })
                });  
            }
            $("#tiposTrabajosNoAsig").html(estructuraSinAsig)

            if (resultado.listadoAsignados.length > 0) {
                
                resultado.listadoAsignados.forEach(el => {
                    estructuraAsig += `<option value="${el.idtrabajos}">${el.tipodetrabajo1}</option>`
                    TIPO_DE_TRABAJOS_H_ASIGNADO.push({
                        "idsupervisor":el.idsupervisor,
                        "idtrabajos":el.idtrabajos,
                        "tipodetrabajo1":el.tipodetrabajo1
                    })
                });
                
            } 
            $("#tiposTrabajosAsig").html(estructuraAsig) 

        })
        .fail(function( jqXHR, textStatus ) {
             //console.log( "Error: " ,jqXHR, textStatus); 
            // console.log( "Request failed: " ,jqXHR.responseJSON.mensaje);
            $("#preloadMantenimientoSupervidorTipoTrabajo").html(``)
            $("#form_mantenimiento_supervisor_tipo_trabajo").removeClass("d-none")

             //$("#result_asignaciones_supervisor_tipo_trabajo").html(jqXHR.responseText)
             //return false
              
 
             let erroresPeticion =""
             if(jqXHR.status){
                 let mensaje = errors.codigos(jqXHR.status)
                 erroresPeticion += `<strong> ${mensaje} </strong>` 
             }
             if(jqXHR.responseJSON){
                 if(jqXHR.responseJSON.mensaje){
                     let erroresMensaje = jqXHR.responseJSON.mensaje  //captura objeto
                     let mensaje = errors.mensajeErrorJson(erroresMensaje)
                     erroresPeticion += "<br>"+ mensaje 
                 } 
             }
             
             erroresPeticion = (erroresPeticion.trim() == 0) ? "hubo un error en el servicio, intente nuevamente." : erroresPeticion
      
             $("#result_asignaciones_supervisor_tipo_trabajo").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
             <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                 <span aria-hidden="true">&times;</span>
             </button>
             ${erroresPeticion}</div>`)
 
             return false
        });
    }

    $("#actualizar_s_t_a").click(function(){
        let supervisor = $("#mnto_supervisor").val()

        console.log("El supervisor es: ",supervisor," Los trabajos a vincular es: ", TIPO_DE_TRABAJOS_H_ASIGNADO)
 

        $("#preloadMantenimientoSupervidorTipoTrabajo").html(`<div id="carga_person">
                                                                    <div class="loader">Loading...</div>
                                                                </div>`)
        $("#form_mantenimiento_supervisor_tipo_trabajo").addClass("d-none")


        $.ajax({
            url: `/administrador/trabajos-programados/supervisor/${supervisor}/tipo-trabajos/update`,
            method: "post",
            data: {
                "trabajos":TIPO_DE_TRABAJOS_H_ASIGNADO
            },
            dataType:"json",
        })  
        .done(function(data) {
            console.log("La data de tipo de trabajos dispnibles son: ",data)

            $("#preloadMantenimientoSupervidorTipoTrabajo").html(``)
           $("#form_mantenimiento_supervisor_tipo_trabajo").removeClass("d-none")

           $("#result_asignaciones_supervisor_tipo_trabajo").html(`<div class="container text-center font-weight-bold alert alert-success fade show" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        ${data.mensaje}</div>`)


        })
        .fail(function( jqXHR, textStatus ) {
            //console.log( "Error: " ,jqXHR, textStatus); 
           // console.log( "Request failed: " ,jqXHR.responseJSON.mensaje);
           $("#preloadMantenimientoSupervidorTipoTrabajo").html(``)
           $("#form_mantenimiento_supervisor_tipo_trabajo").removeClass("d-none")

           //$("#result_asignaciones_supervisor_tipo_trabajo").html(jqXHR.responseText)
           //return false

            let erroresPeticion =""
            if(jqXHR.status){
                let mensaje = errors.codigos(jqXHR.status)
                erroresPeticion += `<strong> ${mensaje} </strong>` 
            }
            if(jqXHR.responseJSON){
                if(jqXHR.responseJSON.mensaje){
                    let erroresMensaje = jqXHR.responseJSON.mensaje  //captura objeto
                    let mensaje = errors.mensajeErrorJson(erroresMensaje)
                    erroresPeticion += "<br>"+ mensaje 
                } 
            }
            
            erroresPeticion = (erroresPeticion.trim() == 0) ? "hubo un error en el servicio, intente nuevamente." : erroresPeticion
     
            $("#result_asignaciones_supervisor_tipo_trabajo").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            ${erroresPeticion}</div>`)

            return false
       });

    })

    

    

})