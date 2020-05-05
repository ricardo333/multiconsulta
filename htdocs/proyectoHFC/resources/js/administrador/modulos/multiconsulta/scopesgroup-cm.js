import valida from  "@/globalResources/forms/valida.js"
import errors from  "@/globalResources/errors.js"
import scopeGroup from  "@/globalResources/modulos/scopesGroup.js"

 $(function(){
 
    
  // $("#scopesGroupCM").click(function(){
    $("body").on("click","#scopesGroupCM", function(){
        
        $("#scopesGroupModal").modal("show")
        
    })

    $("body").on("click","#cambiarScopeGroupClient", function(){
     
         
        let validacionScopeGroup = validacionCambioIP()
        if(!validacionScopeGroup){ 
            return false
        } 

        let mac =  $("#scopesGroupCM").data("uno")
        let motivo =  $("#motivoCambioScopeGroup").val()

        let data = {
            mac,
            motivo,
            "refreshAveriaCoe":false
        }

        scopeGroup.cambioScopesGroup(data,'/administrador/multiconsulta/scopegroup-cm-intraway/detalle')
 
    }) 

 })
 
function validacionCambioIP()
{

    let motivo = $("#motivoCambioScopeGroup")

    if(motivo.val().toLowerCase() == "seleccionar"){
        valida.isValidateInputText(motivo) 
        $("#rptaScopeGroupFormSend").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            Seleccione un motivo válido</div>`); 
        return false
    }
 
    $(".validateSelect").removeClass("valida-error-input")
    $("#rptaScopeGroupFormSend").html(``)
     
      return true
} 