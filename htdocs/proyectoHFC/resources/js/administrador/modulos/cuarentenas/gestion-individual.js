import valida from  "@/globalResources/forms/valida.js"
import limpia from  "@/globalResources/forms/limpia.js"
import errors from  "@/globalResources/errors.js"
import peticiones from './peticiones.js'

var IDCLIENTEGESTION = 0

$(function(){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });



  $("body").on("click",".gestionarAveriaCuarentena", function(){
        console.log("El id cliente es: ",$(this).data("uno")) 
        IDCLIENTEGESTION = $(this).data("uno")
        peticiones.redirectTabs($("#gestionIndividualTab"))
  })

  $("#registrarGestionInd").click(function(){

    let validacionConitnueStore = validacionContinueStore()
    if(!validacionConitnueStore){ 
        return false
    } 

    if (IDCLIENTEGESTION == 0 || IDCLIENTEGESTION == "") {
        $("#body-errors-modal").html(`<div class="w-100 text-danger">No se identifica al cliente seleccionado, intente nuevamente.<br/> 
                                    Si el error persiste intene recargando la web.</div>`)
        $('#errorsModal').modal('show')
        return false 
    }

    let tipoDeAveria = $("#listaTipoAveriaStore").val() 
    let observaciones = $("#observacionesStore").val() 

    $("#storeCuarentenaGestionIndividual").addClass("d-none")
    $("#resultadoStoreGestionCuarentena").html("")
    $("#preloadCuarentenaGestionInd").html(`<div id="carga_person">
                                                <div class="loader">Loading...</div>
                                            </div>`) 

    $.ajax({
        url:`/administrador/cuarentena-general/gestion-individual/store`,
        method:"post",
        data:{
            tipoDeAveria,
            observaciones,
            "idClienteCRM":IDCLIENTEGESTION
        },
        dataType: "json", 
    })
    .done(function(data){

        $("#storeCuarentenaGestionIndividual").removeClass("d-none")
        $("#resultadoStoreGestionCuarentena").html("")
        $("#preloadCuarentenaGestionInd").html("") 

        $("#listaTipoAveriaStore").val("seleccionar") 
        $("#observacionesStore").val("")
 
        //console.log("El resultado de Store gestion c es: ",data)
 
        $("#body-success-modal").html(`<div class="w-100 text-center text-success">Se creo la gestión del cliente correctamente.</div>`)
        $("#successModal").modal("show")

        peticiones.redirectTabs($("#cuarentenaListaTab"))
        peticiones.cargaListaCuarentenas()
  
    })
    .fail(function(jqXHR, textStatus){
      
        $("#storeCuarentenaGestionIndividual").removeClass("d-none")
        $("#resultadoStoreGestionCuarentena").html("")
        $("#preloadCuarentenaGestionInd").html("") 

        //console.log( "Error: " ,jqXHR, textStatus);
        // console.log( "Request failed: " ,jqXHR.responseJSON.mensaje);
        //$("#resultadoStoreGestionCuarentena").html(jqXHR.responseText)
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

        $("#resultadoStoreGestionCuarentena").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    ${erroresPeticion}</div>`)

         
        return false
 
    }) 
 

  })

  $("#registrosGestionesCuarentenas").click(function(){
    peticiones.redirectTabs($("#historicoGestionIndividualTab"))
    peticiones.loadHistoricoGestiónCusrentena(IDCLIENTEGESTION)
  })
  $("#returnStoreGestionesCuarentenas").click(function(){
    peticiones.redirectTabs($("#gestionIndividualTab"))
    
  })

  function validacionContinueStore()
{
  let listaTipoAveriaStore = $("#listaTipoAveriaStore") 
  //let observacionesStore = $("#observacionesStore") 
   
    
  $(".validateText").removeClass("valida-error-input")
  $(".validateSelect").removeClass("valida-error-input")
  $("#resultadoStoreGestionCuarentena").html(``)

  if(!valida.isValidText(listaTipoAveriaStore.val())){
    valida.isValidateInputText(listaTipoAveriaStore)
    $("#resultadoStoreGestionCuarentena").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            El campo Tipo de Avería es requerido</div>`)
    return false
  } 
  
  if(listaTipoAveriaStore.val().toLowerCase() == "seleccionar"){
    valida.isValidateInputText(listaTipoAveriaStore)
    $("#resultadoStoreGestionCuarentena").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            Seleccione un Tipo de Avería valido</div>`)
    return false
  }

  /*if(!valida.isValidaTextArea(observacionesStore.val())){
    valida.isValidateInputText(observacionesStore)
    $("#resultadoStoreGestionCuarentena").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            El formato de contenido en Observaciones no es correcta</div>`)
    return false
  } */
  
 
  $(".validateText").removeClass("valida-error-input")
  $(".validateSelect").removeClass("valida-error-input")
  $("#resultadoStoreGestionCuarentena").html(``)


  return true
 
}


})