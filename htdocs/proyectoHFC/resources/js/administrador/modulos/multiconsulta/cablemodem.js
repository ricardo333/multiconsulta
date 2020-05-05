import errors from  "@/globalResources/errors.js"

$(function(){

    $("body").on("click","#detalle_cablemodem", function(){

        let codigocliente = $(this).data("cod")
        let ipaddress = $(this).data("ip")
        let mac = $(this).data("mac")
        let fabricante =$(this).data("fb")
        let modelo =$(this).data("mo")
        let firmware =$(this).data("firm")

        $('#show_cablemodem').modal('show')
        $('#mytabs a[href="#status"]').tab('show')

        status(codigocliente,ipaddress,mac,fabricante,modelo,firmware);

    });

    
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {

      let codigocliente = $("#detalle_cablemodem").data("cod")
      let ipaddress = $("#detalle_cablemodem").data("ip")
      let mac = $("#detalle_cablemodem").data("mac")
      let fabricante = $("#detalle_cablemodem").data("fb")
      let modelo = $("#detalle_cablemodem").data("mo")
      let firmware = $("#detalle_cablemodem").data("firm")

      let target = $(e.target).attr("href")
      
      if(target=="#status"){
        status(codigocliente,ipaddress,mac,fabricante,modelo,firmware);
      } else if(target=="#dhcp"){
        dhcp(codigocliente,ipaddress,mac,fabricante,modelo,firmware);
      } else if(target=="#wifi2"){
        wifivecino(codigocliente,ipaddress,fabricante,modelo,firmware);
      } else if(target=="#wifi"){
        wifi(codigocliente,ipaddress,fabricante,modelo,firmware);
      } else if(target=="#upnp"){
        upnp(codigocliente,ipaddress,fabricante,modelo,firmware);
      } else if(target=="#dmz"){
        dmz(codigocliente,ipaddress,fabricante,modelo,firmware);
      } else if(target=="#maping"){
        maping(codigocliente,ipaddress,fabricante,modelo,firmware);
      } else if(target=="#reset"){
        console.log(fabricante)
        if(fabricante=="Ubee"){
          document.getElementById('tabla_reset').rows[0].style.display = 'none';
        }else{
          document.getElementById('tabla_reset').rows[0].style.display = 'inline';
        }
      }

    });
    


    function status(codigocliente,ipaddress,mac,fabricante,modelo,firmware){

      $("#resultado_status").html(`<div id="carga_person">
                                  <div class="loader">Loading...</div>
                                </div>`) 

        $.ajax({
          url:`/administrador/multiconsulta/search/cablemodem`,
          method:"GET",
          async: true,
          data:{ 
              codigocliente,
              ipaddress,
              mac,
              fabricante,
              modelo,
              firmware
          },
         cache: false, 
         dataType: "json", 
        })

        .done(function(data){ 
          console.log("El resultado HTML",data);
          let status = JSON.parse(data.response.html)
          $("#resultado_status").html(status) 
        })
      
        .fail(function(jqXHR, textStatus, errorThrown){
           
            if(jqXHR.responseJSON){
                if(jqXHR.responseJSON.mensaje){
                    let erroresMensaje = jqXHR.responseJSON.mensaje  //captura objeto
                    let mensaje = errors.mensajeErrorJson(erroresMensaje)
                    $("#resultado_status").html(mensaje)
                    return false
                } 
            }
            if(jqXHR.status){
                let mensaje = errors.codigos(jqXHR.status)
                $("#body-errors-modal").html(mensaje)
                $('#errorsModal').modal('show')
                return false
            }

            $("#resultado_status").html("<div class='msg_request_error'>hubo un error en el servicio, intente nuevamente.</div>");
   
        });

    }


    function dhcp(codigocliente,ipaddress,mac,fabricante,modelo,firmware){

      $("#resultado_dhcp").html(`<div id="carga_person">
                                  <div class="loader">Loading...</div>
                                </div>`) 

        $.ajax({
          url:`/administrador/multiconsulta/search/cablemodem2`,
          method:"GET",
          async: true,
          data:{ 
              codigocliente,
              ipaddress,
              mac,
              fabricante,
              modelo,
              firmware
          },
         cache: false, 
         dataType: "json", 
        })

        .done(function(data){ 
          console.log("El resultado HTML",data);
          let dhcp = JSON.parse(data.response.html)
          $("#resultado_dhcp").html(dhcp) 
        })
      
        .fail(function(jqXHR, textStatus, errorThrown){
           
            if(jqXHR.responseJSON){
                if(jqXHR.responseJSON.mensaje){
                    let erroresMensaje = jqXHR.responseJSON.mensaje  //captura objeto
                    let mensaje = errors.mensajeErrorJson(erroresMensaje)
                    $("#resultado_dhcp").html(mensaje)
                    return false
                } 
            }
            if(jqXHR.status){
                let mensaje = errors.codigos(jqXHR.status)
                $("#body-errors-modal").html(mensaje)
                $('#errorsModal').modal('show')
                return false
            }

            $("#resultado_dhcp").html("<div class='msg_request_error'>hubo un error en el servicio, intente nuevamente.</div>");
   
        });

    }


    function wifivecino(codigocliente,ipaddress,fabricante,modelo,firmware){

      $("#resultado_wifi2").html(`<div id="carga_person">
                                  <div class="loader">Loading...</div>
                                </div>`) 

        $.ajax({
          url:`/administrador/multiconsulta/search/wifivecino`,
          method:"GET",
          async: true,
          data:{ 
              codigocliente,
              ipaddress,
              fabricante,
              modelo,
              firmware
          },
         cache: false, 
         dataType: "json", 
        })

        .done(function(data){ 
          console.log("El resultado HTML",data);
          let wifivecino = JSON.parse(data.response.html)
          $("#resultado_wifi2").html(wifivecino) 
        })
      
        .fail(function(jqXHR, textStatus, errorThrown){
           
            if(jqXHR.responseJSON){
                if(jqXHR.responseJSON.mensaje){
                    let erroresMensaje = jqXHR.responseJSON.mensaje  //captura objeto
                    let mensaje = errors.mensajeErrorJson(erroresMensaje)
                    $("#resultado_wifi2").html(mensaje)
                    return false
                } 
            }
            if(jqXHR.status){
                let mensaje = errors.codigos(jqXHR.status)
                $("#body-errors-modal").html(mensaje)
                $('#errorsModal').modal('show')
                return false
            }

            $("#resultado_wifi2").html("<div class='msg_request_error'>hubo un error en el servicio, intente nuevamente.</div>");
   
        });

    }


    function wifi(codigocliente,ipaddress,fabricante,modelo,firmware){

      $("#resultado_wifi").html(`<div id="carga_person">
                                  <div class="loader">Loading...</div>
                                </div>`) 

        $.ajax({
          url:`/administrador/multiconsulta/search/wifi`,
          method:"GET",
          async: true,
          data:{ 
              codigocliente,
              ipaddress,
              fabricante,
              modelo,
              firmware
          },
         cache: false, 
         dataType: "json", 
        })

        .done(function(data){ 
          console.log("El resultado HTML",data);
          let wifi = JSON.parse(data.response.html)
          $("#resultado_wifi").html(wifi) 
        })
      
        .fail(function(jqXHR, textStatus, errorThrown){
           
            if(jqXHR.responseJSON){
                if(jqXHR.responseJSON.mensaje){
                    let erroresMensaje = jqXHR.responseJSON.mensaje  //captura objeto
                    let mensaje = errors.mensajeErrorJson(erroresMensaje)
                    $("#resultado_wifi").html(mensaje)
                    return false
                } 
            }
            if(jqXHR.status){
                let mensaje = errors.codigos(jqXHR.status)
                $("#body-errors-modal").html(mensaje)
                $('#errorsModal').modal('show')
                return false
            }

            $("#resultado_wifi").html("<div class='msg_request_error'>hubo un error en el servicio, intente nuevamente.</div>");

              
        });

    }


    function upnp(codigocliente,ipaddress,fabricante,modelo,firmware){

      $("#resultado_Upnp").html(`<div id="carga_person">
                                  <div class="loader">Loading...</div>
                                </div>`) 

        $.ajax({
          url:`/administrador/multiconsulta/search/upnp`,
          method:"GET",
          async: true,
          data:{ 
              codigocliente,
              ipaddress,
              fabricante,
              modelo,
              firmware
          },
         cache: false, 
         dataType: "json", 
        })

        .done(function(data){ 
          console.log("El resultado HTML",data);
          let upnp = JSON.parse(data.response.html)
          $("#resultado_Upnp").html(upnp) 
        })
      
        .fail(function(jqXHR, textStatus, errorThrown){
           
            if(jqXHR.responseJSON){
                if(jqXHR.responseJSON.mensaje){
                    let erroresMensaje = jqXHR.responseJSON.mensaje  //captura objeto
                    let mensaje = errors.mensajeErrorJson(erroresMensaje)
                    $("#resultado_Upnp").html(mensaje)
                    return false
                } 
            }
            if(jqXHR.status){
                let mensaje = errors.codigos(jqXHR.status)
                $("#body-errors-modal").html(mensaje)
                $('#errorsModal').modal('show')
                return false
            }

            $("#resultado_Upnp").html("<div class='msg_request_error'>hubo un error en el servicio, intente nuevamente.</div>");
   
        });

    }


    function dmz(codigocliente,ipaddress,fabricante,modelo,firmware){

      $("#resultado_dmz").html(`<div id="carga_person">
                                  <div class="loader">Loading...</div>
                                </div>`) 

        $.ajax({
          url:`/administrador/multiconsulta/search/dmz`,
          method:"GET",
          async: true,
          data:{ 
              codigocliente,
              ipaddress,
              fabricante,
              modelo,
              firmware
          },
         cache: false, 
         dataType: "json", 
        })

        .done(function(data){ 
          console.log("El resultado HTML",data);
          let dmz = JSON.parse(data.response.html)
          $("#resultado_dmz").html(dmz) 
        })
      
        .fail(function(jqXHR, textStatus, errorThrown){
           
            if(jqXHR.responseJSON){
                if(jqXHR.responseJSON.mensaje){
                    let erroresMensaje = jqXHR.responseJSON.mensaje  //captura objeto
                    let mensaje = errors.mensajeErrorJson(erroresMensaje)
                    $("#resultado_dmz").html(mensaje)
                    return false
                } 
            }
            if(jqXHR.status){
                let mensaje = errors.codigos(jqXHR.status)
                $("#body-errors-modal").html(mensaje)
                $('#errorsModal').modal('show')
                return false
            }

            $("#resultado_dmz").html("<div class='msg_request_error'>hubo un error en el servicio, intente nuevamente.</div>");
   
        });

    }



    function maping(codigocliente,ipaddress,fabricante,modelo,firmware){

      $("#preloadMaping").html(`<div id="carga_person">
                                  <div class="loader">Loading...</div>
                                </div>`) 
      $("#resultado_maping").css({'display':'none'})

        $.ajax({
          url:`/administrador/multiconsulta/search/maping`,
          method:"GET",
          async: true,
          data:{ 
              codigocliente,
              ipaddress,
              fabricante,
              modelo,
              firmware
          },
         cache: false, 
         dataType: "json", 
        })

        
        .done(function(data){ 

          $("#preloadMaping").html("") 
          $("#resultado_maping").css({'display':'block'})

          $ ("#tabla0 tbody tr").remove();

          console.log("El resultado HTML",data);
          
          //$("#resultado_maping").html(maping) 

          let ipLan1 = data.response.ipLan1;
          let ipLan2 = data.response.ipLan2;
          let ipLan3 = data.response.ipLan3;
          let maping = data.response.maping;
          let contarRegistros = maping.length;

          console.log(maping);

          console.log("Cantidad: "+contarRegistros);

          if(fabricante=="Askey"){

            if(contarRegistros>0){
              var num;
              for (num=0; num < contarRegistros ; num++) { 
  
                let parametros = maping[num].split("|");
                console.log(parametros);
  
                let cantArray = parametros.length;
                if(cantArray==7){
                  let privatPort = parametros[3]+"-"+parametros[4];
                  let publiPort = parametros[5]+"-"+parametros[6];
                  var serviceName = parametros[0];
                  var lanIP = parametros[1];
                  var protocolo = parametros[2];
                  var privatePort = privatPort;
                  var publicPort = publiPort;

                  var fila="<tr><td>"+serviceName+"</td><td>"+lanIP+"</td><td>"+protocolo+"</td><td>"+privatePort+"</td><td>"+publicPort+"</td><td><input type='button' class='button-delete'></td></tr>";
   	
                  console.log(fila);
                  
                  var btn = document.createElement("TR");
                  btn.innerHTML=fila;
                  document.getElementById("tablita").appendChild(btn);

                }else{
                  var serviceName = parametros[0];
                  var lanIP = parametros[1];
                  var protocolo = parametros[2];
                  var privatePort = parametros[3];
                  var publicPort = parametros[4];

                  var fila="<tr><td>"+serviceName+"</td><td>"+lanIP+"</td><td>"+protocolo+"</td><td>"+privatePort+"</td><td>"+publicPort+"</td><td><input type='button' class='button-delete'></td></tr>";
             
                  console.log(fila);

                  var btn = document.createElement("TR");
                  btn.innerHTML=fila;
                  document.getElementById("tablita").appendChild(btn);
                }
              }
            }

          }


          if(fabricante.substr(0,3)=='Hit'){

            if(contarRegistros>0){
              var num;
              for (num=0; num < contarRegistros ; num++) { 
                //let parametros=explode(",", maping[num]);
                let parametros = maping[num].split(",");

                var serviceName = parametros[0];
                var lanIP = parametros[3];
          
                if(parametros[1]==1){
                  var protocolo ="TCP";
                }
                
                if (parametros[1]==2){
                  var protocolo ="UDP";
                }
                
                if (parametros[1]==3){
                  var protocolo ="TCP/UDP";
                }

                if(parametros[7]==parametros[8]){
                  var privatePort = parametros[6];
                  var publicPort = parametros[5];
                }else{
                  let publicPort1 = parametros[7]+"-"+parametros[8];
                  var privatePort = publicPort1;
                  var publicPort = publicPort1;
                }
        
                var fila="<tr><td>"+serviceName+"</td><td>"+lanIP+"</td><td>"+protocolo+"</td><td>"+privatePort+"</td><td>"+publicPort+"</td><td><input type='button' class='button-delete'></td></tr>";
             
                var btn = document.createElement("TR");
                btn.innerHTML=fila;
                document.getElementById("tablita").appendChild(btn);
              }
            }
          }


          if(fabricante=='Ubee' || fabricante.substr(0,9)=='CastleNet' || fabricante.substr(0,6)=='Telefo'){

            if(contarRegistros>0){
              var num;
              for (num=0; num < contarRegistros ; num++) {

                var serviceName = maping[num]["nombre"];
                var lanIP =maping[num]["ipPrivada"];
          
                if(maping[num]["protocolo"]=="BOTH"){
                  var protocolo ="TCP/UDP";
                }else{
                  var protocolo = maping[num]["protocolo"];
                }
          
                if(maping[num]["rangoPrivada1"]==maping[num]["rangoPrivada2"]){
                  var privatePort = maping[num]["rangoPrivada1"];
                  var publicPort = maping[num]["rangoPublica1"];
                }else{
                  let puertoPrivado = maping[num]["rangoPrivada1"]+"-"+maping[num]["rangoPrivada2"];
                  let puertoPublico = maping[num]["rangoPublica1"]+"-"+maping[num]["rangoPublica2"];
          
                  var privatePort = puertoPrivado;
                  var publicPort = puertoPublico;
                }
          
                var fila="<tr><td>"+serviceName+"</td><td>"+lanIP+"</td><td>"+protocolo+"</td><td>"+privatePort+"</td><td>"+publicPort+"</td><td><input type='button' class='button-delete'></td></tr>";
               
                var btn = document.createElement("TR");
                btn.innerHTML=fila;
                document.getElementById("tablita").appendChild(btn);
                
              }
            }

          }
          
        })
      
        .fail(function(jqXHR, textStatus, errorThrown){
           
            if(jqXHR.responseJSON){
                if(jqXHR.responseJSON.mensaje){
                    let erroresMensaje = jqXHR.responseJSON.mensaje  //captura objeto
                    let mensaje = errors.mensajeErrorJson(erroresMensaje)
                    $("#preloadMaping").html("") 
                    $("#resultado_maping").css({'display':'block'})
                    $("#resultado_maping").html(mensaje)
                    return false
                } 
            }
            if(jqXHR.status){
                let mensaje = errors.codigos(jqXHR.status)
                $("#body-errors-modal").html(mensaje)
                $('#errorsModal').modal('show')
                return false
            }

            $("#preloadMaping").html("") 
            $("#resultado_maping").css({'display':'block'})
            $("#resultado_maping").html("<div class='msg_request_error'>hubo un error en el servicio, intente nuevamente.</div>");
   
        });

    }



    ///////////////////////


    //$("#btnCambio").click(function(){
      $("body").on("click","#btnCambio", function(){

        let codigocliente = $("#detalle_cablemodem").data("cod")
        let ipaddress = $("#detalle_cablemodem").data("ip")
        let mac = $("#detalle_cablemodem").data("mac")
        let fabricante = $("#detalle_cablemodem").data("fb")
        let modelo = $("#detalle_cablemodem").data("mo")
        let firmware = $("#detalle_cablemodem").data("firm")

        //Valores iniciales
        let ssid1_original = $("#ssid1").attr('value');
        let interface1_original = $("#cmbInterface1 [selected]").attr('value');
        let channel1_original = $("#cmbChannel1 [selected]").attr('value');
        let bandwidth1_original = $("#cmbBandwidth1 [selected]").attr('value');
        let power1_original = $("#cmbPower1 [selected]").attr('value');
        let seguridad1_original = $("#cmbProtection1 [selected]").attr('value');
        let pass1_original = $("#Password1").attr('value');
        
        //Valores modificados
        let ssid1 = $("#ssid1").val().trim()
        let interface1 = $("#cmbInterface1").val()
        let channel1 = $("#cmbChannel1").val()
        let bandwidth1 = $("#cmbBandwidth1").val()
        let power1 = $("#cmbPower1").val()
        let seguridad1 = $("#cmbProtection1").val()
        let pass1 = $("#Password1").val()
        let ssid2 = $("#ssid2").val()

        console.log("Original:.........");
        console.log(ssid1_original);
        console.log(interface1_original);
        console.log(channel1_original);
        console.log(bandwidth1_original);
        console.log(power1_original);
        console.log(seguridad1_original);
        console.log(pass1_original);

        console.log("Modificado:.........");
        console.log(ssid1);
        console.log(interface1);
        console.log(channel1);
        console.log(bandwidth1);
        console.log(power1);
        console.log(seguridad1);
        console.log(pass1);

        $("#resultado").html(`<div id="carga_person">
                                  <div class="loader">Loading...</div>
                                </div>`)

        
        if (fabricante=='Askey') {
          if (typeof ssid2 === 'undefined' ) {

            $.ajax({
              url:`/administrador/multiconsulta/search/updatewifi`,
              method:"GET",
              async: true,
              data:{ 
                  codigocliente,ipaddress,mac,fabricante,modelo,firmware,ssid1_original,interface1_original,
                  channel1_original,bandwidth1_original,power1_original,seguridad1_original,pass1_original,
                  ssid1,interface1,channel1,bandwidth1,power1,seguridad1,pass1                           
              },
              cache: false, 
              dataType: "json", 
            })
    
            .done(function(data){ 
              console.log("El resultado HTML",data);
              $('#resultado').html(data.mensaje);
              })
                                    
              .fail(function(jqXHR, textStatus, errorThrown){
              console.log("Request failed: " ,textStatus ,jqXHR,errorThrown);
              console.log("Es falla");
              $('#resultado').html("No se puede acceder al Cable Modem....");
                                            
              });

            console.log("No hay 5G")
          } else {

            //Valores iniciales
            let ssid2_original = $("#ssid2").attr('value');
            let interface2_original = $("#cmbInterface2 [selected]").attr('value');
            let channel2_original = $("#cmbChannel2 [selected]").attr('selected');
            let bandwidth2_original = $("#cmbBandwidth2 [selected]").attr('value');
            let power2_original = $("#cmbPower2 [selected]").attr('value');
            let seguridad2_original = $("#cmbProtection2 [selected]").attr('value');
            let pass2_original = $("#Password2").attr('value');

            let ssid2 = $("#ssid2").val().trim()
            let interface2 = $("#cmbInterface2").val()
            let channel2 = $("#cmbChannel2").val()
            let bandwidth2 = $("#cmbBandwidth2").val()
            let power2 = $("#cmbPower2").val()
            let seguridad2 = $("#cmbProtection2").val()
            let pass2 = $("#Password2").val()

            $.ajax({
              url:`/administrador/multiconsulta/search/updatewifi5G`,
              method:"GET",
              async: true,
              data:{ 
                  codigocliente,ipaddress,mac,fabricante,modelo,firmware,ssid1_original,interface1_original,
                  channel1_original,bandwidth1_original,power1_original,seguridad1_original,pass1_original,
                  ssid1,interface1,channel1,bandwidth1,power1,seguridad1,pass1,ssid2_original,
                  interface2_original,channel2_original,bandwidth2_original,power2_original,seguridad2_original,
                  pass2_original,ssid2,interface2,channel2,bandwidth2,power2,seguridad2,pass2                      
              },
              cache: false, 
              dataType: "json", 
            })

            .done(function(data){ 
              console.log("El resultado HTML",data);
              $('#resultado').html(data.mensaje);
              })
                                    
              .fail(function(jqXHR, textStatus, errorThrown){
              console.log("Request failed: " ,textStatus ,jqXHR,errorThrown);
              console.log("Es falla");
              $('#resultado').html("No se puede acceder al Cable Modem....");
                                            
              });

            console.log("Hay 5G")
          }
        }

        
        if (fabricante.substr(0,3)=='Hit') {

          console.log("Dentro de Hitron")

          //Valores iniciales
          let ssid1_original = trim($("#ssid1Hitron").attr('value'));
          let interface1_original = $("#cmbInterfaceHitron [selected]").attr('value');
          let channel1_original = $("#cmbChannelHitron [selected]").attr('value');
          let bandwidth1_original = $("#cmbBandwidthHitron [selected]").attr('value');
          let seguridad1_original = $("#cmbProtection1Hitron [selected]").attr('value');
          let seguridad2_original = $("#cmbProtection2Hitron [selected]").attr('value');
          let pass1_original = $("#passwordHitron").attr('value');


          let ssid1 = $("#ssid1Hitron").val().trim()
          let ssid2 = $("#ssid2Hitron").val()
          let ssid3 = $("#ssid3Hitron").val()
          let ssid4 = $("#ssid4Hitron").val()
          let ssid5 = $("#ssid5Hitron").val()
          let ssid6 = $("#ssid6Hitron").val()
          let ssid7 = $("#ssid7Hitron").val()
          let ssid8 = $("#ssid8Hitron").val()
          let interface1 = $("#cmbInterfaceHitron").val()
          let channel = $("#cmbChannelHitron").val()
          let bandwidth = $("#cmbBandwidthHitron").val()
          let seguridad1 = $("#cmbProtection1Hitron").val()
          let seguridad2 = $("#cmbProtection2Hitron").val()
          let pass = $("#passwordHitron").val()

          $.ajax({
            url:`/administrador/multiconsulta/search/updatewifiHitron`,
            method:"GET",
            async: true,
            data:{ 
                codigocliente,ipaddress,mac,fabricante,modelo,firmware,ssid1_original,interface1_original,
                channel1_original,bandwidth1_original,seguridad1_original,seguridad2_original,
                pass1_original,ssid1,ssid2,ssid3,ssid4,ssid5,ssid6,ssid7,ssid8,interface1,channel,
                bandwidth,seguridad1,seguridad2,pass                      
            },
            cache: false, 
            dataType: "json", 
          })

            .done(function(data){ 
            console.log("El resultado HTML",data);
            $('#resultado').html(data.mensaje);
            })
                                  
            .fail(function(jqXHR, textStatus, errorThrown){
            console.log("Request failed: " ,textStatus ,jqXHR,errorThrown);
            console.log("Es falla");
            $('#resultado').html("No se puede acceder al Cable Modem....");
                                          
            });

        }

        

        if (fabricante=='Ubee') {

          //Valores iniciales
          let ssid1_original = $("#ssidUbee").attr('value');
          let interface1_original = $("#cmbInterfaceUbee [selected]").attr('value');
          let channel1_original = $("#cmbChannelUbee [selected]").attr('value');
          let seguridad1_original = $("#cmbProtection1Ubee [selected]").attr('value');
          let seguridad2_original = $("#cmbProtection2Ubee [selected]").attr('value');
          let seguridad3_original = $("#cmbProtection3Ubee [selected]").attr('value');
          let seguridad4_original = $("#cmbProtection4Ubee [selected]").attr('value');
          let seguridad5_original = $("#cmbProtection5Ubee [selected]").attr('value');
          let pass1_original = $("#passwordUbee").attr('value');

          let ssid = $("#ssidUbee").val().trim()
          let interface1 = $("#cmbInterfaceUbee :selected").val()
          let channel = $("#cmbChannelUbee").val()
          let seguridad1 = $("#cmbProtection1Ubee").val()
          let seguridad2 = $("#cmbProtection2Ubee").val()
          let seguridad3 = $("#cmbProtection3Ubee").val()
          let seguridad4 = $("#cmbProtection4Ubee").val()
          let seguridad5 = $("#cmbProtection5Ubee").val()
          let pass = $("#passwordUbee").val()

          $.ajax({
            url:`/administrador/multiconsulta/search/updatewifiUbee`,
            method:"GET",
            async: true,
            data:{ 
                codigocliente,ipaddress,mac,fabricante,modelo,firmware,ssid1_original,interface1_original,
                channel1_original,seguridad1_original,seguridad2_original,seguridad3_original,
                seguridad4_original,seguridad5_original,pass1_original,ssid,interface1,
                channel,seguridad1,seguridad2,seguridad3,seguridad4,seguridad5,pass                    
            },
            cache: false, 
            dataType: "json", 
          })

          .done(function(data){ 
            console.log("El resultado HTML",data);
            $('#resultado').html(data.mensaje);
            })
                                  
            .fail(function(jqXHR, textStatus, errorThrown){
            console.log("Request failed: " ,textStatus ,jqXHR,errorThrown);
            console.log("Es falla");
            $('#resultado').html("No se puede acceder al Cable Modem....");
                                          
            });

        }

        if (fabricante.substr(0,5)=='SAGEM') {

          //Valores iniciales
          let ssid1_original = $("#ssidSagem").attr('value');
          let channel1_original = $("#cmbChannelSagem [selected]").attr('value');
          let bandwidth1_original = $("#cmbBandwidthSagem [selected]").attr('value');
          let power1_original = $("#cmbPowerSagem [selected]").attr('value');
          let seguridad1_original = $("#cmbProtection1Sagem [selected]").attr('value');
          let seguridad2_original = $("#cmbProtection2Sagem [selected]").attr('value');
          let seguridad3_original = $("#cmbProtection3Sagem [selected]").attr('value');
          let seguridad4_original = $("#cmbProtection4Sagem [selected]").attr('value');
          let seguridad5_original = $("#cmbProtection5Sagem [selected]").attr('value');
          let pass1_original = $("#passwordSagem").attr('value');


          let ssid = $("#ssidSagem").val().trim()
          let channel = $("#cmbChannelSagem").val()
          let bandwidth = $("#cmbBandwidthSagem").val()
          let power = $("#cmbPowerSagem").val()
          let seguridad1 = $("#cmbProtection1Sagem").val()
          let seguridad2 = $("#cmbProtection2Sagem").val()
          let seguridad3 = $("#cmbProtection3Sagem").val()
          let seguridad4 = $("#cmbProtection4Sagem").val()
          let seguridad5 = $("#cmbProtection5Sagem").val()
          let pass = $("#passwordSagem").val()

          $.ajax({
            url:`/administrador/multiconsulta/search/updatewifiSagem`,
            method:"GET",
            async: true,
            data:{ 
                codigocliente,ipaddress,mac,fabricante,modelo,firmware,ssid1_original,channel1_original,
                bandwidth1_original,power1_original,seguridad1_original,seguridad2_original,
                seguridad3_original,seguridad4_original,seguridad5_original,pass1_original,ssid,channel,
                bandwidth,power,seguridad1,seguridad2,seguridad3,seguridad4,seguridad5,pass                    
            },
            cache: false, 
            dataType: "json", 
          })

          .done(function(data){ 
            console.log("El resultado HTML",data);
            $('#resultado').html(data.mensaje);
            })
                                  
            .fail(function(jqXHR, textStatus, errorThrown){
            console.log("Request failed: " ,textStatus ,jqXHR,errorThrown);
            console.log("Es falla");
            $('#resultado').html("No se puede acceder al Cable Modem....");
                                          
            });

        }

        if (fabricante.substr(0,9)=='CastleNet' || fabricante.substr(0,6)=='Telefo') {

          //Valores iniciales
          let ssid1_original = $("#ssidCastle").attr('value');
          let seguridad1_original = $("#cmbProtectionCastle [selected]").attr('value');
          let pass1_original = $("#passwordCastle").attr('value');
          
          let ssid = $("#ssidCastle").val().trim()
          let seguridad = $("#cmbProtectionCastle").val()
          let pass = $("#passwordCastle").val()

          $.ajax({
            url:`/administrador/multiconsulta/search/updatewifiCastlenet`,
            method:"GET",
            async: true,
            data:{ 
                codigocliente,ipaddress,mac,fabricante,modelo,firmware,ssid1_original,seguridad1_original,
                pass1_original,ssid,seguridad,pass                    
            },
            cache: false, 
            dataType: "json", 
          })

          .done(function(data){ 
            console.log("El resultado HTML",data);
            $('#resultado').html(data.mensaje);
            })
                                  
            .fail(function(jqXHR, textStatus, errorThrown){
            console.log("Request failed: " ,textStatus ,jqXHR,errorThrown);
            console.log("Es falla");
            $('#resultado').html("No se puede acceder al Cable Modem....");
                                          
            });

        }
        
    });



    $("body").on("click","#btnUpnp", function(){

        let codigocliente = $("#detalle_cablemodem").data("cod")
        let ipaddress = $("#detalle_cablemodem").data("ip")
        let fabricante = $("#detalle_cablemodem").data("fb")
        let modelo = $("#detalle_cablemodem").data("mo")
        let firmware = $("#detalle_cablemodem").data("firm")
        

        //captura de valores de formulario
        let identi = $("#txtIds").val()
        let respuesta = $("#txtRpt").val()
        let canal = $("#txtWan").val()
        let activacion = $("#upnp:checked").val()

        $("#resultadoUpnp").html(`<div id="carga_person">
                                  <div class="loader">Loading...</div>
                                </div>`)

        
        $.ajax({
          url:`/administrador/multiconsulta/search/updateupnp`,
          method:"GET",
          async: true,
          data:{ 
              codigocliente,ipaddress,fabricante,modelo,firmware,identi,respuesta,canal,activacion                    
          },
          cache: false, 
          dataType: "json", 
        })

        .done(function(data){ 
          console.log("El resultado HTML",data);
          $('#resultadoUpnp').html(data.mensaje);
          })
                                
          .fail(function(jqXHR, textStatus, errorThrown){
          console.log("Request failed: " ,textStatus ,jqXHR,errorThrown);
          console.log("Es falla");
          $('#resultadoUpnp').html("No se puede acceder al Cable Modem....");
                                        
          });

    });


    $("body").on("click","#radioDmz", function(){

      //let valor = $("#radioDmz").value
      let valor = $('input[name="radioDmz"]:checked').val();

      console.log("Haciendo click en radioButton "+valor)

      if(valor=="1"){
        document.getElementById("config").style.display = "block";
      }else{
        document.getElementById("config").style.display = "none";
      }

    });


    $("body").on("click","#btnDmz", function(){

        let codigocliente = $("#detalle_cablemodem").data("cod")
        let ipaddress = $("#detalle_cablemodem").data("ip")
        let fabricante = $("#detalle_cablemodem").data("fb")
        let modelo = $("#detalle_cablemodem").data("mo")
        let firmware = $("#detalle_cablemodem").data("firm")

        $("#resultadoDmz").html(`<div id="carga_person">
                                  <div class="loader">Loading...</div>
                                </div>`)

        if(fabricante=='Askey'){

        //captura de valores de formulario
        let wan = $("#txtWan").val()
        let ipDmz = $("#DmzHostIP").val()
        let activacion = $("#radioDmz:checked").val()

        $.ajax({
          url:`/administrador/multiconsulta/search/updatedmz`,
          method:"GET",
          async: true,
          data:{ 
              codigocliente,ipaddress,fabricante,modelo,firmware,wan,ipDmz,activacion                    
          },
          cache: false, 
          dataType: "json", 
        })

          .done(function(data){ 
          console.log("El resultado HTML",data);
          $('#resultadoDmz').html(data.mensaje);
          })
                                
          .fail(function(jqXHR, textStatus, errorThrown){
          console.log("Request failed: " ,textStatus ,jqXHR,errorThrown);
          console.log("Es falla");
          $('#resultadoDmz').html("No se puede acceder al Cable Modem....");
                                        
          });

        }

        if (fabricante.substr(0,3)=='Hit') {

          //captura de valores de formulario
          let id = $("#txtIds").val()
          let rpt = $("#txtRpt").val()
          let upnp = $("#txtUpnp").val()
          let wan = $("#txtWan").val()

          let ipDmz = $("#ipDevice").val();

          let arraySeparadorIp = ipDmz.split(".");

          let ipValor1 = arraySeparadorIp[0];
          let ipValor2 = arraySeparadorIp[1];
          let ipValor3 = arraySeparadorIp[2];
          let ipValor4 = arraySeparadorIp[3];

          let activacion = $("#radioDmz:checked").val();

          $.ajax({
            url:`/administrador/multiconsulta/search/updatedmzHitron`,
            method:"GET",
            async: true,
            data:{ 
                codigocliente,ipaddress,fabricante,modelo,firmware,id,rpt,upnp,wan,ipDmz,
                ipValor1,ipValor2,ipValor3,ipValor4,activacion                    
            },
            cache: false, 
            dataType: "json", 
          })
  
            .done(function(data){ 
            console.log("El resultado HTML",data);
            $('#resultadoDmz').html(data.mensaje);
            })
                                  
            .fail(function(jqXHR, textStatus, errorThrown){
            console.log("Request failed: " ,textStatus ,jqXHR,errorThrown);
            console.log("Es falla");
            $('#resultadoDmz').html("No se puede acceder al Cable Modem....");
                                          
            });

        }

        if (fabricante=='Ubee' || fabricante.substr(0,9)=='CastleNet' || fabricante.substr(0,6)=='Telefo') {

          //captura de valores de formulario
          let ipDmz = $("#ipDmz").val();
          let activacion = $("#radioDmz:checked").val();

          console.log(ipDmz)
          console.log(activacion)

          $.ajax({
            url:`/administrador/multiconsulta/search/updatedmzUbee`,
            method:"GET",
            async: true,
            data:{ 
                codigocliente,ipaddress,fabricante,modelo,firmware,ipDmz,activacion                    
            },
            cache: false, 
            dataType: "json", 
          })

          .done(function(data){ 
            console.log("El resultado HTML",data);
            $('#resultadoDmz').html(data.mensaje);
            })
                                  
            .fail(function(jqXHR, textStatus, errorThrown){
            console.log("Request failed: " ,textStatus ,jqXHR,errorThrown);
            console.log("Es falla");
            $('#resultadoDmz').html("No se puede acceder al Cable Modem....");
                                          
            });

        }

    });



    $("body").on("click","#btnDiagnostico", function(){

      let codigocliente = $("#detalle_cablemodem").data("cod")
      let ipaddress = $("#detalle_cablemodem").data("ip")
      let fabricante = $("#detalle_cablemodem").data("fb")
      let modelo = $("#detalle_cablemodem").data("mo")
      let firmware = $("#detalle_cablemodem").data("firm")
      
      //captura de valores de formulario
      let ipPing = $("#ipTest").val();

      console.log(ipPing);
      let longitud = ipPing.substring(0,9);
      
      if(fabricante.substr(0,9)=='CastleNet' || fabricante.substr(0,5)=='SAGEM' || fabricante.substr(0,6)=='Telefo'){
        if(longitud != "192.168.1"){
          alert("Solo se puede ingresar ips de la LAN.");
          return false;
        }
      }
      

      $("#resultado_diagnostico").html(`<div id="carga_person">
                                  <div class="loader">Loading...</div>
                                </div>`)

      $.ajax({
        url:`/administrador/multiconsulta/search/diagnostico`,
        method:"GET",
        async: true,
        data:{ 
            codigocliente,ipaddress,fabricante,modelo,firmware,ipPing                    
        },
        cache: false, 
        dataType: "json", 
      })

      .done(function(data){ 
        console.log("El resultado HTML",data);
        let diag = JSON.parse(data.response.html)
        $('#resultado_diagnostico').html(diag);
      })
                              
      .fail(function(jqXHR, textStatus, errorThrown){
        console.log("Request failed: " ,textStatus ,jqXHR,errorThrown);
        console.log("Es falla");
        $("#resultado_diagnostico").html(jqXHR.responseText)
        //$('#resultado_diagnostico').html("No se puede acceder al Cable Modem....");
      });

    });



    $("body").on("click","#btnResetSimple", function(){

      let codigocliente = $("#detalle_cablemodem").data("cod")

      let codigoservicio = $("#detalle_cablemodem").data("serv")
      let codigoproducto = $("#detalle_cablemodem").data("prod")
      let codigoventa = $("#detalle_cablemodem").data("vent")

      let ipaddress = $("#detalle_cablemodem").data("ip")
      let fabricante = $("#detalle_cablemodem").data("fb")
      let modelo = $("#detalle_cablemodem").data("mo")
      let firmware = $("#detalle_cablemodem").data("firm")
      
      //captura de valores de formulario
      let reset = "reset1"

      $("#resultado_reset").html(`<div id="carga_person">
                                  <div class="loader">Loading...</div>
                                </div>`)


        $.ajax({
          url:`/administrador/multiconsulta/search/reset`,
          method:"GET",
          async: true,
          data:{ 
              codigocliente,codigoservicio,codigoproducto,codigoventa,ipaddress,
              fabricante,modelo,firmware,reset                    
          },
          cache: false, 
          dataType: "json", 
        })

        .done(function(data){ 
          console.log("El resultado HTML",data);
          $('#resultado_reset').html(data.mensaje);
        })
                                
        .fail(function(jqXHR, textStatus, errorThrown){
          console.log("Request failed: " ,textStatus ,jqXHR,errorThrown);
          console.log("Es falla");
          //$("#resultado_reset").html(jqXHR.responseText)
          $('#resultado_reset').html("No se puede acceder al Cable Modem....");
        });

      

    });



    $("body").on("click","#btnResetFactory", function(){

      let codigocliente = $("#detalle_cablemodem").data("cod")
      let ipaddress = $("#detalle_cablemodem").data("ip")
      let fabricante = $("#detalle_cablemodem").data("fb")
      let modelo = $("#detalle_cablemodem").data("mo")
      let firmware = $("#detalle_cablemodem").data("firm")
      
      //captura de valores de formulario
      let reset = "reset2"

      $("#resultado_reset").html(`<div id="carga_person">
                                  <div class="loader">Loading...</div>
                                </div>`)

      $.ajax({
        url:`/administrador/multiconsulta/search/reset`,
        method:"GET",
        async: true,
        data:{ 
            codigocliente,ipaddress,fabricante,modelo,firmware,reset                    
        },
        cache: false, 
        dataType: "json", 
      })

      .done(function(data){ 
        console.log("El resultado HTML",data);
        $('#resultado_reset').html(data.mensaje);
        })
                              
        .fail(function(jqXHR, textStatus, errorThrown){
        console.log("Request failed: " ,textStatus ,jqXHR,errorThrown);
        console.log("Es falla");
        $('#resultado_reset').html("No se puede acceder al Cable Modem....");
                                      
        });


    });



    $("body").on('click', '.button-delete', function(){
      if (!$(this).hasClass('nodel')) {
        $(this).closest('tr').fadeOut(function() {
               $(this).remove();
           });
       }
    })


    $("body").on('click', '.port_window01', function(){

      document.getElementById("addLanIP0").disabled = true;
      document.getElementById("addLanIP1").disabled = true;
      document.getElementById("addLanIP2").disabled = true;
    
      $('#addServiceName').val("");
      $('#addLanIP3').val("");
      $('#publicSinglePort').val("");
      $('#lanSinglePort').val("");
      $('#publicRangeS').val("");
      $('#publicRangeE').val("");
      $('#privateRangeS').val("");
      $('#privateRangeE').val("");
    
      $('.popup_window1').fadeIn();
    })


    $("body").on("click","#btnCancel", function(){
      $('.popup_window1').fadeOut();
    })


    $("body").on("click","#rdPuerto", function(){

      let valor = $('input[name="puerto"]:checked').val();

      if(valor=="1"){
        document.getElementById("type_port").style.display = "block";
        document.getElementById("type_portRange").style.display = "none";
        }else{
        document.getElementById("type_port").style.display = "none";
        document.getElementById("type_portRange").style.display = "block";
        }
      
    })


    $("body").on("click","#btnSave", function(){

      var serviceName = document.getElementById("addServiceName").value;
      var lanIP1 = document.getElementById("addLanIP0").value;
      var lanIP2 = document.getElementById("addLanIP1").value;
      var lanIP3 = document.getElementById("addLanIP2").value;
      var lanIP4 = document.getElementById("addLanIP3").value;
      var lanIP = lanIP1 + "." + lanIP2 + "." + lanIP3 + "." + lanIP4;

      var combo = document.getElementById("addSelectProtocol");
      var protocolo = combo.options[combo.selectedIndex].text;

      var puerto = $('input[name="puerto"]:checked').val();

      if(puerto=="1"){
        //----------------------------Para puerto simple--------------------------------//
        var privatePort = document.getElementById("publicSinglePort").value;
        var publicPort = document.getElementById("lanSinglePort").value;
        //------------------------------------------------------------------------------//
      }else{
        //--------------------------Para puerto con rango-------------------------------//
        var privatePort1 = document.getElementById("publicRangeS").value;
        var privatePort2 = document.getElementById("publicRangeE").value;
        var privatePort = privatePort1 + "-" + privatePort2;
    
        var publicPort1 = document.getElementById("privateRangeS").value;
        var publicPort2 = document.getElementById("privateRangeE").value;
        var publicPort = publicPort1 + "-" + publicPort2;
    
        if(privatePort1>privatePort2){
          var error1 = "si";
          alert("El rango inicial no puede ser mayor que el final");
        }else{
          var error1 = "no";
        }
        //------------------------------------------------------------------------------//
    
      }

      if (lanIP4 == 1  || lanIP4 == 255){
        var error2 = "si";
        alert("El rango de LAN IP no puede ser 1 o 255");
      }else{
        var error2 = "no";
      }
      
      var fila="<tr><td>"+serviceName+"</td><td>"+lanIP+"</td><td>"+protocolo+"</td><td>"+privatePort+"</td><td>"+publicPort+"</td><td><input type='button' class='button-delete' ></td></tr>";
         
      if (error1=="no" || error2=="no") {
        var btn = document.createElement("TR");
        btn.innerHTML=fila;
        document.getElementById("tablita").appendChild(btn);
        $('.popup_window1').fadeOut();
      }
      

    })




    $("body").on("click","#btnGuardarMaping", function(){

      let codigocliente = $("#detalle_cablemodem").data("cod")
      let ipaddress = $("#detalle_cablemodem").data("ip")
      let fabricante = $("#detalle_cablemodem").data("fb")
      let modelo = $("#detalle_cablemodem").data("mo")
      let firmware = $("#detalle_cablemodem").data("firm")
      let mac = $("#detalle_cablemodem").data("mac")
      

      //captura de valores de formulario
      var myTableArray = [];

      $("table#tabla0 tr").each(function() {
        //var arrayOfThisRow = [];
        var tableData = $(this);
          if (tableData.length > 0) {
            tableData.each(function() { 
            let td = $(this).find('td');
            let filas = []
            td.each(function(){
            filas.push($(this).text())
            })
          myTableArray.push(filas);
          });
          }
      });
      
      myTableArray.splice(0,1);

      let maping = JSON.stringify(myTableArray)


      $("#resulMaping").html(`<div id="carga_person">
                                <div class="loader">Loading...</div>
                              </div>`)

          $.ajax({
            url:`/administrador/multiconsulta/search/updatemaping`,
            method:"GET",
            async: true,
            data:{ 
                codigocliente,ipaddress,mac,fabricante,modelo,firmware,maping                           
            },
            cache: false, 
            dataType: "json", 
          })
  
          .done(function(data){ 
            console.log("El resultado HTML",data);
            $('#resulMaping').html(data.mensaje);
            })
                                  
            .fail(function(jqXHR, textStatus, errorThrown){
            console.log("Request failed: " ,textStatus ,jqXHR,errorThrown);
            console.log("Es falla");
            $('#resulMaping').html("No se puede acceder al Cable Modem....");
                                          
            });

    });






    







})