import errors from  "@/globalResources/errors.js"
$(function(){

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
   
    $('body').on("click",".accionRolDelete",function(){
      let rolIdentificador = $(this).data('id')
       
      //console.log("el id ha eliminar es:",rolIdentificador)
      let _this = $(this)
      var opcionDelete = confirm("¿Está seguro de eliminar al rol?, ¡confirme nuevamente por favor!.");
      if (!opcionDelete) {
          return false
        }  
    
      if(rolIdentificador){
        $.ajax({
          url:`/administrador/rol/${rolIdentificador}/eliminar`,
          method:"post",
          data:{},
          dataType: "json", 
        })
        .done(function(data){
         // console.log(data)
          if(data.error){
            $("#body-errors-modal").html("no se puedo eliminar al rol, intente nuevamente.")
            $('#errorsModal').modal('show') 
            return false
          } 

          let rol = data.response.data
          $("#body-success-modal").html(`
            <h5 class="text-success text-center text-uppercase font-weight-bold">Rol eliminado</h5>
            <p class="text-center font-weight-bold font-italic">Se eliminó el rol ${rol.rol} correctamente</p>
            
            `) 
          $("#successModal").modal("show")
  
          _this.closest('tr').remove();
    
        })
        .fail(function(jqXHR, textStatus){
           console.log("error",jqXHR, textStatus)
          // $("#body-errors-modal").html(jqXHR.responseText)
          if(jqXHR.responseJSON){
            if(jqXHR.responseJSON.mensaje){
                  let erroresMensaje = jqXHR.responseJSON.mensaje  //captura objeto
                  let mensaje = errors.mensajeErrorJson(erroresMensaje)
                  $("#body-errors-modal").html(mensaje)
                  $('#errorsModal').modal('show') 
                  return false
              } 
          }
          if(jqXHR.status){
              let mensaje = errors.codigos(jqXHR.status)
              $("#body-errors-modal").html(mensaje)
              $('#errorsModal').modal('show')
              return false
          }
          $("#body-errors-modal").html("Se generó un problema inesperado, intente nuevamente.")
          $('#errorsModal').modal('show') 
        })
      }else{
          $("#body-errors-modal").html("No se puede encontrar al rol, recargue la página e intentelo de nuevo.")
          $('#errorsModal').modal('show') 
      }
      
   
    })
  
  })