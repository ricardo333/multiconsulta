$(function(){

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
   
    $('body').on("click",".accionEmpresaDelete",function(){
      let empresaId = $(this).data('id')
       
      console.log("el id ha eliminar es:",empresaId)
      let _this = $(this)
      var opcionDelete = confirm("¿Está seguro de eliminar la empresa?, ¡confirme nuevamente por favor!.");
      if (!opcionDelete) {
          return false
        }  
    
      if(empresaId){
        $.ajax({
          url:`/administrador/empresa/${empresaId}/eliminar`,
          method:"post",
          data:{},
          dataType: "json", 
        })
        .done(function(data){
         // console.log(data)
          if(data.error){
            $("#body-errors-modal").html("no se puedo eliminar la empresa, intente nuevamente.")
            $('#errorsModal').modal('show') 
            return false
          } 

          let empresa = data.response.data
          $("#body-success-modal").html(`
            <h5 class="text-success text-center text-uppercase font-weight-bold">Empresa eliminada</h5>
            <p class="text-center font-weight-bold font-italic">Se eliminó la empresa:  "${empresa.empresa}" correctamente</p>
            
            `) 
          $("#successModal").modal("show")
  
          _this.closest('tr').remove();
    
        })
        .fail(function(jqXHR, textStatus){
          console.log("error",jqXHR, textStatus)
          // $("#body-errors-modal").html(jqXHR.responseText)
          $("#body-errors-modal").html("Se generó un problema inesperado, intente nuevamente.")
          $('#errorsModal').modal('show') 
        })
      }else{
          $("#body-errors-modal").html("No se puede encontrar la empresa, recargue la página e intentelo de nuevo.")
          $('#errorsModal').modal('show') 
      }
       
    })
  
  })