import errors from  "@/globalResources/errors.js"
import peticiones from './peticiones.js'
import interfaces from  "@/globalResources/modulos/interfaces.js"

var control 

 
$(function(){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    /*$('#tabsMultiContent').click(function(event){
      event.stopPropagation();
    });*/

     
     //Busqueda Multiconsulta
     $("#form_multiconsulta #search_m").click(function(){
         
      buscar();
        
    })
    $('#text_m').keydown(function(e) {
			if (e.keyCode == 13) {
				e.preventDefault();
				buscar();
			} 
    });

      
    //Multiples resultados
    $("body").on("click",'#tablaresvarios tbody tr', function(event){
        event.preventDefault();  
       console.log("Aqui el click de multiples")
     
     // activarLoader()
        var $td= $(this).closest('tr').children('td');  

        var macaddress= $td.eq(0).text(); 	
        console.log("el mac addres es: "+macaddress);
        $("#text_m").val(macaddress);
        $("#type_m").val(2);
        $("#searchModal").modal('hide')//oculta modal
      
        //burcarPorMacAddress(2,macaddress)
        buscar()
         
    })

    //Maximizar

    $(".maxi_tab").click(function(){
       
      $("#tabsMultiContent").toggleClass("fullscreen");

      if ($("#tabsMultiContent").hasClass("fullscreen")) {
        //console.log("tiene la clase full ")
         $(".maxi_tab").html('<i class="icofont-close-line-squared-alt"></i>') 
      }else{
       // console.log("no tiene la clase full ")
        $(".maxi_tab").html('<i class="icofont-maximize"></i>')  
      } 
 
    })

    $("body").on("click",".return_multiconsultaTab", function(){
        
        peticiones.redirectTabs($('#multiconsultaTab')) 
    })

    $("body").on("click","#storeUpdateATelefonos", function(){

      let telefono1 = document.getElementById("storeUpdateATelefonos").dataset.uno //$("#storeUpdateATelefonos").data("uno") 
      let telefono2 = document.getElementById("storeUpdateATelefonos").dataset.dos //$("#storeUpdateATelefonos").data("dos")        
      let telefono3 = document.getElementById("storeUpdateATelefonos").dataset.tres //$("#storeUpdateATelefonos").data("tres")        


      $("#telefonoUnoStoreUp").val(telefono1)
      $("#telefonoDosStoreUp").val(telefono2)
      $("#telefonoTresStoreUp").val(telefono3)

      $("#multiconsultaModal").modal("show")
 
    })

    $("#guardarTelefonosMulti").click(function(){

      $("#preload_form_telefono").html(`<div id="carga_person">
                                      <div class="loader">Loading...</div>
                                  </div>`)
      $("#form_telefono_content").addClass("d-none")

      $("#rpta_telefono_multi").html("")

      let idCliente = $("#storeUpdateATelefonos").data("cuatro") || ""
      let telefono1 = $("#telefonoUnoStoreUp").val()
      let telefono2 = $("#telefonoDosStoreUp").val()
      let telefono3 = $("#telefonoTresStoreUp").val()

      if (idCliente == "" || idCliente == null) {
        $("#preload_form_telefono").html(``)
        $("#form_telefono_content").removeClass("d-none")
        $("#rpta_telefono_multi").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                      </button>
                                      No se puede identificar al cliente, intente nuevamente recargando la web.</div>`)
        return false
      }

      $.ajax({
        url:`/administrador/multiconsulta/telefono/store-update`,
        method:"POST",
        async: true,
        data:{
          idCliente,
          telefono1,
          telefono2,
          telefono3
        },
       cache: false, 
       dataType: "json", 
      })
      .done(function(data){ 
        //console.log("callbak antes del envio:",data)

       // console.log("La data es: ",data)
        $("#preload_form_telefono").html(``)
        $("#form_telefono_content").removeClass("d-none")
        $("#rpta_telefono_multi").html("")

        let resultado = data.response

        $("#telefonoUnoStoreUp").val(`${resultado.data[0].telef1}`)
        $("#telefonoDosStoreUp").val(`${resultado.data[0].telef2}`)
        $("#telefonoTresStoreUp").val(`${resultado.data[0].telef3}`)

        document.getElementById("storeUpdateATelefonos").dataset.uno = resultado.data[0].telef1
        document.getElementById("storeUpdateATelefonos").dataset.dos = resultado.data[0].telef2
        document.getElementById("storeUpdateATelefonos").dataset.tres = resultado.data[0].telef3
     
          
        $("#rpta_telefono_multi").html(`<div class="container text-center font-weight-bold alert alert-success fade show" role="alert">
                              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                              </button>
                              ${resultado.mensaje}</div>`)

      })
      .fail(function(jqXHR, textStatus, errorThrown){

        $("#preload_form_telefono").html(``)
        $("#form_telefono_content").removeClass("d-none")
        $("#rpta_telefono_multi").html("")

        console.log("Request failed: " ,textStatus ,jqXHR,errorThrown);
 
       // $("#rpta_telefono_multi").html(jqXHR.responseText)
       // return false

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

         $("#rpta_telefono_multi").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                             <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                 <span aria-hidden="true">&times;</span>
                                             </button>
                                             ${erroresPeticion}</div>`); 
         return false
          
      }); 

    })

    //Historico de Interfaces Ruido
    $("body").on("click","#verhistoricoRuidoInterfaz", function(){
      let interfaz = $(this).data("uno")

      peticiones.redirectTabs($("#historicoRuidoInterfazTab"))

      interfaces.historicoRuido(interfaz,'/administrador/multiconsulta/historico/ruidos-interfaz')
    })

    
 
})

  
function buscar()
{
    let type_data = $("#form_multiconsulta #type_m").val()
    let text =  $("#form_multiconsulta #text_m").val()

    /* if (type_data == 1) {
        if (text.substr(0,1) == 0) {
          $("#body-errors-modal").html(`<div class="col-12 text-danger text-center">El formato del codigo del cliente no es válido.</div>`)
          $('#errorsModal').modal('show')
          return false
        }
     }*/
     
    start_time()

    peticiones.searchCountMulticonsulta(type_data,text,function(res){
      clearInterval(control);
      $("#multiconsulta_search").removeClass("d-none")
      //console.log("aqui luego del callbak")
      //  console.log("la data return count multiconsulta es: ",res)
       
        //Errores
          if(res.error == "failed"){
 
              // console.log("Error: ",res.errorThrown,res.jqXHR,res.textStatus) 
              // $("#rpta_multiconsulta").html(`<div class="col-12 text-danger text-center">${res.jqXHR.responseText}</div>`); 
              // return false
  
              $("#rpta_multiconsulta").html("");

              let erroresPeticion =""

              if(res.jqXHR.status){
                  let mensaje = errors.codigos(res.jqXHR.status)
                  erroresPeticion += `<strong> ${mensaje} : </strong>`
              }
              if(res.jqXHR.responseJSON){
                  if(res.jqXHR.responseJSON.mensaje){
                      let erroresMensaje = res.jqXHR.responseJSON.mensaje  //captura objeto
                      let mensaje = errors.mensajeErrorJson(erroresMensaje)
                      erroresPeticion += "<br/>"+ `<div class="text-center text-secondary">${mensaje}</div>`
                  } 
              }
               
              erroresPeticion = (erroresPeticion.trim() == 0) ? "hubo un error en el servicio, intente nuevamente." : erroresPeticion

              $("#body-errors-modal").html(`<div class="col-12 text-danger text-center">${erroresPeticion}</div>`)
              $('#errorsModal').modal('show')

              return false
 
          }
           
        //Nulo
          let data = res.response
          if(data.cantidad  == 0){
            $("#rpta_multiconsulta").html(`<div class="col-12 text-center text-danger">0 Clientes Encontrados</div>`);
              return false;
          } 
    
        //DATA CORRECTA
 
        //validando cantidad de resultado
        let cantidadResultado = data.cantidad
        
        if (cantidadResultado > 1) {
            //console.log("cuentas con muchos resultados,saldra popup"); 
            //Armando el resultado
            if (cantidadResultado >= 20) {
              $("#rpta_multiconsulta").html(`<div class="col-12 text-center text-danger">Demasiados clientes, intente realizar la busqueda por MacAddress.</div>`);
              return false;
            }
            if (data.type == "nclientes") {
              let resultadoArmado = JSON.parse(data.resultado)
               //console.log("resultado multiples con nclientes",resultadoArmado)
              peticiones.armandoMultiplesResultadosNClientes(resultadoArmado)
              return false
            }
            if (data.type == "intraway") {
              // console.log("resultado multiples con intraway")
              
              let resultadoArmado = JSON.parse(data.resultado)
              // console.log(resultadoArmado)
              peticiones.armandoMultiplesResultadosIntraway(resultadoArmado)
              return false 
            } 
             
        } 

        //console.log("no tiene para modales...")

        let resultadoPrint = JSON.parse(data.resultado)

        $("#rpta_multiconsulta").html(resultadoPrint);
    
      })
}
  
function start_time()
{
 
  $("#multiconsulta_search").addClass("d-none")
  $("#cronomtero_busqueda_multi").html("00:00:00");
  $("#rpta_multiconsulta").html(`<div id="carga_person">
                                        <div class="loader">Loading...</div>
                                    </div>`); 

  let  timeInicial = new Date();
  control = setInterval(function(){
       let timeActual = new Date();
       let acumularTime = timeActual - timeInicial;
       //console.log("La diferencia de fechas es :",acumularTime)
       let acumularTime2 = new Date();
       acumularTime2.setTime(acumularTime); 
       //console.log("el acumularTime2es :",acumularTime2)
       let cc = Math.round(acumularTime2.getMilliseconds()/10);
       let ss = acumularTime2.getSeconds();
       let mm = acumularTime2.getMinutes();
         
       if (cc < 10) {cc = "0"+cc;}
       if (ss < 10) {ss = "0"+ss;} 
       if (mm < 10) {mm = "0"+mm;}
         
       $("#cronomtero_busqueda_multi").html(mm+" : "+ss+" : "+cc);

       
     },10);

      
    
}
 