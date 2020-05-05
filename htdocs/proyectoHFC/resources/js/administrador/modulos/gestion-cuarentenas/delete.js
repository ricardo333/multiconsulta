import peticiones from './peticiones.js'

var ID_DELETE = 0
var BUTTON_SELECT_DELETE = ""
$(function(){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("body").on("click", ".eliminarCuarentenaGestion", function(){

        ID_DELETE = 0;
        BUTTON_SELECT_DELETE = ""
        let identificador = $(this).data("uno")
        BUTTON_SELECT_DELETE = $(this)
        console.log("El identificador es: ",identificador)

        if (identificador == "" || identificador == null) {
            $("#body-errors-modal").html(`<div class="w-100 text-danger text-center">No se puedo identificar la cuarentena, intente nuevamente <br/>. 
                                                                                    Si el error persiste intente actualizar la web.</div>`)
            $("#errorsModal").modal("show") 
            peticiones.redirectTabs($("#cuarentenaListaTab"));
            return false  
        }

        ID_DELETE = identificador
 
        $("#confirmDeleteModal").modal("show")  
  
    })

    $("#aceptarDeleteModal").click(function(){

        //console.log("Se está confirmando la elminación")
        $("#confirmDeleteModal").modal("hide")  
         
        let identificador = ID_DELETE
 
        BUTTON_SELECT_DELETE.prop('disabled',true)
        BUTTON_SELECT_DELETE.html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    <span class="sr-only">Descargando..</span>`)
        
        $.ajax({
            url:`/administrador/gestion-cuarentena/${identificador}/delete`,
            method:"post",
            dataType: "json", 
        })
        .done(function(data){
 
            //console.log("la data return delete es: ",data) 

            BUTTON_SELECT_DELETE.prop('disabled',false)
            BUTTON_SELECT_DELETE.html(`<i class="icofont-ui-delete icofont-md"></i>`)

            $("#body-success-modal").html(`<div class="w-100 text-center text-success">${data.mensaje}</div>`)
            $("#successModal").modal("show")
 
             peticiones.cargaListaGestionCuarentenas() 
            
  
        })
        .fail(function(jqXHR, textStatus){

            BUTTON_SELECT_DELETE.prop('disabled',false)
            BUTTON_SELECT_DELETE.html(`<i class="icofont-ui-delete icofont-md"></i>`)
 
            //console.log( "Error: " ,jqXHR, textStatus); 
            // console.log( "Request failed: " ,jqXHR.responseJSON.mensaje); 
            //$("#body-errors-modal").html(`<div class="w-100 text-danger text-center">${jqXHR.responseText}</div>`)
            //$("#errorsModal").modal("show") 
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
            
            erroresPeticion = (erroresPeticion.trim() == 0) ? "hubo un error al traer las trobas, intente nuevamente." : erroresPeticion

            $("#body-errors-modal").html(`<div class="w-100 text-danger text-center">${erroresPeticion}</div>`)
            $("#errorsModal").modal("show") 
        
            return false
       
        })
    })

})
