import peticiones from './peticiones.js'
//import validaKey from  "@/globalResources/forms/inputKey.js"

$(function(){

    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    //validaKey.alfanumerico(".descripcionEtiquetadoPuertos")

    loadPrincipalEtiquetadoPuertos()
     
    function loadPrincipalEtiquetadoPuertos()
    {
      let parametros = {}
      parametros.cmts = $("#listaCmtsEtiquetadoPuertos").val()
      let columnasCaidas = peticiones.armandoColumnasUno()
      let tabla = $("#resultEtiquetadoPuertos");
      peticiones.cargaEtiquetadoPuertosLista(columnasCaidas,BUTTONS_ETIQUETA_PUERTOS,parametros,tabla)
    }

    $("#filtroEtiquetadoPuertos").click(function(){
      $("#filtroContentEtiquetadoPuertos").hide();
      loadPrincipalEtiquetadoPuertos()
    })
 
    //Maximizar
    $(".maxi_tab").click(function(){
        $("#tabsEtiquetadoPuertos").toggleClass("fullscreen");
        if ($("#tabsEtiquetadoPuertos").hasClass("fullscreen")) {
        // console.log("tiene la clase full ")
          $(".maxi_tab").html('<i class="icofont-close-line-squared-alt"></i>') 
        }else{
          //console.log("no tiene la clase full ")
          $(".maxi_tab").html('<i class="icofont-maximize"></i>')  
        } 
    })



})