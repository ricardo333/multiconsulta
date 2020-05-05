import errors from  "@/globalResources/errors.js"
$(function(){

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
   
    $('body').on("click",".accionUsuarioDelete",function(){
      let userIdSelect = $(this).data('id')
       
      console.log("el id ha eliminar es:",userIdSelect)
      let _this = $(this)
      var opcionDelete = confirm("¿Está seguro de eliminar al usuario?, ¡confirme nuevamente por favor!.");
      if (!opcionDelete) {
          return false
        }  
    
      if(userIdSelect){
        $.ajax({
          url:`/administrador/usuario/${userIdSelect}/eliminar`,
          method:"post",
          data:{},
          dataType: "json", 
        })
        .done(function(data){
          //console.log(data)
          if(data.error){
            $("#body-errors-modal").html("no se puedo eliminar al usuario, intente nuevamente.")
            $('#errorsModal').modal('show') 
            return false
          }
     
          $("#body-success-modal").html("Se eliminó al usuario seleccionado correctamente!.")
          $("#successModal").modal("show")
  
          _this.closest('tr').remove();
    
        })
        .fail(function(jqXHR, textStatus){
          console.log("error",jqXHR, textStatus)
          // $("#body-errors-modal").html(jqXHR.responseText)

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

          $("#body-errors-modal").html(erroresPeticion)
          $('#errorsModal').modal('show') 

          return false
 
        })
      }else{
          $("#body-errors-modal").html("No se puede encontrar al usuario, recargue la página e intentelo de nuevo.")
          $('#errorsModal').modal('show') 
      }
      
   
    })
  
  })