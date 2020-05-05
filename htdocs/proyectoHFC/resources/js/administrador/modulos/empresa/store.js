import valida from  "@/globalResources/forms/valida.js"
import limpia from  "@/globalResources/forms/limpia.js"
import errors from  "@/globalResources/errors.js"
 

$(function(){

    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
 
    $("#crearEmpresa").click(function(){
        registroEmpresaStore()
    })
  
})
 
function registroEmpresaStore()
{
  let validacionConitnueStore = validacionContinueStore()
    if(!validacionConitnueStore){ 
        return false
    }
    
 //registrar
    
    let empresa = $("#nombreStore").val()
      
    $("#form_store_detail").css({'display':'none'})
    $("#form_store_load").css({'display':'block'})
    $("#form_store_load").html(`<div id="carga_person">
                                  <div class="loader">Loading...</div>
                                </div>`) 

    $.ajax({
        url:`/administrador/empresa/store`,
        method:"post",
        data:{empresa},
        dataType: "json", 
    })
    .done(function(data){

        $("#form_store_load").css({'display':'none'})
        $("#form_store_load").html('')
        $("#form_store_detail").css({'display':'flex'})
        limpia.limpiaFormEmpresa()

       //  console.log(data)
         $("#errors_store").html(data)
        if(data.error){
            $("#body-errors-modal").html(data.error)
            $('#errorsModal').modal('show') 
            return false
        }

         let empresa = data.response.data
        
         $("#body-success-modal").html(`
          <h5 class="text-success text-center text-uppercase font-weight-bold">Empresa creada correctamente</h5>
          <p class="text-center font-weight-bold font-italic">La empresa: "${empresa.empresa}" se creo corretamente.</p>
           
         `)
        $("#successModal").modal("show")
  
    })
    .fail(function(jqXHR, textStatus){
      
      $("#form_store_load").css({'display':'none'})
      $("#form_store_load").html('')
      $("#form_store_detail").css({'display':'flex'})

        console.log( "Error: " ,jqXHR, textStatus);
        //console.log( "Request failed: " ,jqXHR.responseJSON.mensaje);
         //$("#errors_store").html(jqXHR.responseText)
        if(jqXHR.responseJSON){
            if(jqXHR.responseJSON.mensaje){
                let erroresMensaje = jqXHR.responseJSON.mensaje  //captura objeto
                let mensaje = errors.mensajeErrorJson(erroresMensaje)
                $("#errors_store").html(mensaje)
                return false
            } 
        }
        if(jqXHR.status){
            let mensaje = errors.codigos(jqXHR.status)
            $("#body-errors-modal").html(mensaje)
            $('#errorsModal').modal('show')
            return false
        }
       $("#body-errors-modal").html("hubo un problema en la red del internet, intente nuevamente por favor.")
        $('#errorsModal').modal('show') 
    }) 
}

function validacionContinueStore()
{
  let nombre = $("#nombreStore")  
    
  $(".validateText").removeClass("valida-error-input")
  $(".validateSelect").removeClass("valida-error-input")
  $("#errors_store").html(``)

  if(!valida.isValidText(nombre.val())){
    valida.isValidateInputText(nombre)
    $("#errors_store").html(`El campo nombre es requerido`)
    return false
  } 
    
  $(".validateText").removeClass("valida-error-input")
  $(".validateSelect").removeClass("valida-error-input")
  $("#errors_store").html(``)
 
  return true
 
}
 


 