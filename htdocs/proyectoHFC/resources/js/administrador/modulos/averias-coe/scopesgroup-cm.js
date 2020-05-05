import valida from  "@/globalResources/forms/valida.js"
 
import scopeGroup from  "@/globalResources/modulos/scopesGroup.js"

 $(function(){
 
    
  // $("#scopesGroupCM").click(function(){
    $("body").on("click",".scopesGroupCM", function(){

        $("#motivoCambioScopeGroup").val("")
        let mac = $(this).data("uno")
        document.getElementById("cambiarScopeGroupClient").dataset.uno = mac
       
        
        $("#scopesGroupModal").modal("show")
        
    })

    $("body").on("click","#cambiarScopeGroupClient", function(){
     
         
        let validacionScopeGroup = validacionCambioIP()
        if(!validacionScopeGroup){ 
            return false
        } 

        let mac =  document.getElementById("cambiarScopeGroupClient").dataset.uno
        let motivo =  $("#motivoCambioScopeGroup").val()

        console.log("La data enviar es: ",mac,"->",motivo)

        let data = {
            mac,
            motivo,
            "refreshAveriaCoe":true
        }

        scopeGroup.cambioScopesGroup(data,'/administrador/averias-coe/scopegroup-cm-intraway/detalle')
 
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