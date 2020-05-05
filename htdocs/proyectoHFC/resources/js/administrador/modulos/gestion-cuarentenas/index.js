import peticiones from './peticiones.js'

$(function(){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    //Maximizar

    $(".maxi_tab").click(function(){
 
        $("#tabsGestionCuarentenasContent").toggleClass("fullscreen");
  
        if ($("#tabsGestionCuarentenasContent").hasClass("fullscreen")) {
         // console.log("tiene la clase full ")
           $(".maxi_tab").html('<i class="icofont-close-line-squared-alt"></i>') 
        }else{
          //console.log("no tiene la clase full ")
          $(".maxi_tab").html('<i class="icofont-maximize"></i>')  
        } 
   
    })


    peticiones.cargaListaGestionCuarentenas()

      
    $("body").on("click",".return_lista_cuarentenas_Tab", function(){ 
        peticiones.redirectTabs($('#cuarentenaListaTab')) 
    })

    $("#filtroBasicoGCuarentenas").click(function(){
        peticiones.cargaListaGestionCuarentenas()
    })

    $("body").on("click",".verDetalleClientesC", function(){
       
        peticiones.redirectTabs($('#cuarentenaClientesListTab')) 
         
        let identificadorCuarentena = $(this).data("uno")
        let nombreCuarentena = $(this).data("dos")

        if (identificadorCuarentena == "" || identificadorCuarentena == null) {
            peticiones.redirectTabs($('#cuarentenaListaTab')) 
            $("#body-errors-modal").html(`<div class="w-100 text-danger">No se puede Reconocer la Cuarentena, intente nuevamente recargando la web.</div>`)
            $('#errorsModal').modal('show')
            return false
        }

       // console.log("el nombre es: ",nombreCuarentena)

        $("#nombreCuarentenaClienteDetalle").html(`${nombreCuarentena}`)
        
        peticiones.cargaGestionCuarentenasClientes(identificadorCuarentena)
 
    })

    $("body").on("click",".verDetalleTrobasC", function(){
       
        peticiones.redirectTabs($('#cuarentenaTrobasListTab')) 
         
        let identificadorCuarentena = $(this).data("uno")
        let nombreCuarentena = $(this).data("dos")

        if (identificadorCuarentena == "" || identificadorCuarentena == null) {
            peticiones.redirectTabs($('#cuarentenaListaTab')) 
            $("#body-errors-modal").html(`<div class="w-100 text-danger">No se puede Reconocer la Cuarentena, intente nuevamente recargando la web.</div>`)
            $('#errorsModal').modal('show')
            return false
        }

       // console.log("el nombre es: ",nombreCuarentena)

        $("#nombreCuarentenaTrobaDetalle").html(`${nombreCuarentena}`)
        
        peticiones.cargaGestionCuarentenasTrobas(identificadorCuarentena)
 
    })
 
 

})