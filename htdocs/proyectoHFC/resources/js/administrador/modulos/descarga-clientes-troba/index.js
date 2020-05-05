import peticiones from './peticiones.js'
import diagnosticoMasivo from  "@/globalResources/modulos/diagnostico-masivo.js"
import historicoNodoTroba from  "@/globalResources/modulos/historico-nodo-trobas.js"
import mapa from  "@/globalResources/modulos/mapa.js"

  
 
$(function(){
 
    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

     
    $('[name="SearchDualList1"]').keyup(function (e) {
        var code = e.keyCode || e.which;
         
        //if (code == '9') return;
        //if (code == '27') $(this).val(null);
        //var $rows = $(this).closest('.dual-list').find('#interfacesLista option');
        
        if (code == 13) {
            $(this).prop("disabled",true)

            let palabraBusca = $(this).val() 
            if (palabraBusca.trim() != "") {
               
                $("#interfacesLista").html(``) 
               
                DATA_INTERFACES.forEach(el => { 
                    if (el.toLowerCase().indexOf(palabraBusca.toLowerCase()) != -1) {
                        $("#interfacesLista").append(`<option value="${el}">${el}</option>`)
                    } 
                })  

            }
            $(this).prop("disabled",false)
        } 

        if ($(this).val() == "" && code != 13) {
            $(this).prop("disabled",true) 
            //$(this).prop("disabled",true)
           // document.getElementById().disabled = true
            $("#interfacesLista").html(``) 
            DATA_INTERFACES.forEach(el => { 
                $("#interfacesLista").append(`<option value="${el}">${el}</option>`) 
            }) 
            $(this).prop("disabled",false)
        }

        $(this).focus()
       
        
    });


    $('[name="SearchDualList2"]').keyup(function (e) {
        var code = e.keyCode || e.which;
       
        if (code == '9') return;
        if (code == '27') $(this).val(null);
        var $rows = $(this).closest('.dual-list').find('#interfaces option');
        var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
        $rows.show().filter(function () {
            var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
            return !~text.indexOf(val);
        }).hide();
    });


    $("#btnLeftTrobas").click(function(){
        let datos1 = document.getElementById("interfacesLista");
        let datos2 = document.getElementById("interfaces");
        let collection = datos2.selectedOptions;
        let cantidad = collection.length;

        for (let i = 0; i < cantidad; i++) {
            let valor = collection[i].text;
            let option = document.createElement('option');
            option.text = collection[i].text;
            datos1.add(option);
        }
            
        $.each($('[name="duallistbox_demo2"] option:selected'), function( index, value ) { 
            DATA_INTERFACES.push(value.value)
            $(this).remove();
        }); 
    });
 
    $("#btnRightTrobas").click(function(){
        let datos1 = document.getElementById("interfacesLista");
        let datos2 = document.getElementById("interfaces");
        let collection = datos1.selectedOptions;
        let cantidad = collection.length;

        for (let i = 0; i < cantidad; i++) {
            let valor = collection[i].text;
            let option = document.createElement('option');
            option.text = collection[i].text;
            datos2.add(option);
        }
            
        $.each($('[name="duallistbox_demo1"] option:selected'), function( index, value ) { 
            let nuevoArrayInterfaces = DATA_INTERFACES.filter(palabra => {  
                    if (palabra.toLowerCase() != value.value.toLowerCase() ) {
                        return palabra
                    }
                });
            DATA_INTERFACES = nuevoArrayInterfaces

            $(this).remove();
        });
    });

    $(".return_resultado_filtros").click(function(){
        peticiones.redirectTabs($("#resultadoFiltroResultPrincipalTab"))
    })
    $(".return_troba_clientes").click(function(){
        peticiones.redirectTabs($("#contentFiltrosTrobasClientTab"))
        $("#resultadoContentFiltroCliente").html("")
    })

     //Maximizar

     $(".maxi_tab").click(function(){
       
        $("#tabsDescargaClientesTrobaContent").toggleClass("fullscreen");
  
        if ($("#tabsDescargaClientesTrobaContent").hasClass("fullscreen")) {
         // console.log("tiene la clase full ")
           $(".maxi_tab").html('<i class="icofont-close-line-squared-alt"></i>') 
        }else{
          //console.log("no tiene la clase full ")
          $(".maxi_tab").html('<i class="icofont-maximize"></i>')  
        } 
   
    })

    


    $("#filtroPrincipalProceso").click(function(){

        //-----Obtener Valores de Interfaces Seleccionadas-----//
        var cantidad = document.getElementById("interfaces").options.length;
        var valores = [];
        for (let i = 0; i < cantidad; i++) {
            var selectValue = document.getElementById("interfaces").options[i].value;
            valores[i] = selectValue;
        }
         
        let  interfaces = valores
        let  trobas = $("#listaTrobas").val()
        let  nivelesPorPuerto = $("#listaNivelesPuerto").val()
        
        //console.log("las interfaces a enviar son:",interfaces)

        $("#respuestaFiltroClienteTroba").html(`<div id="carga_person">
                                                <div class="loader">Loading...</div>
                                        </div>`)
        $("#contentFiltroClientTroba").addClass("d-none")

        $.ajax({
            url:`/administrador/descarga-clientes-troba/filtro`,
            method:"get",
            data:{
                interfaces,
                trobas,
                nivelesPorPuerto
            },
            dataType: "json", 
        })
        .done(function(data){

            $("#respuestaFiltroClienteTroba").html(``)
            $("#contentFiltroClientTroba").removeClass("d-none")

            //console.log(data) 

            let resultado = data.response

            let estructuraPrincipal = ``

            

            if (resultado.hayFiltroInterfaces) {

                let estructuraFiltroInterfaces = `<div class="col-md-6">`

                let interfaces = resultado.dataInterfaces.interfaces
                let dataInterfaces = resultado.dataInterfaces.data
                
                estructuraFiltroInterfaces += `<div class="card p-2">
                                                <h6 class="text-center text-sm font-weight-bold">
                                                Resultado de Interfaces: <span style="font-size:10px;">(${interfaces})</span>
                                            </h6>`
 
                let descargaTrobaPuertos = `<button  data-uno="${interfaces}"class="btn btn-outline-primary btn-sm shadow-sm my-1 mx-auto w-75" id="descargarTrobaPuertos">Descargar Troba Puertos <i class="icofont-cloud-download icofont-lg"></i></button >`
                let cantidadTrobasPorPuerto = `<a href="javascript:void(0)" data-uno="${dataInterfaces}" class="btn btn-sm btn-outline-info shadow-sm my-1 mx-auto w-75" id="verListaCantidadTrobasPorPuerto">Lista Cantidad Trobas por Puerto <i class="icofont-hand-drawn-right icofont-lg"></i></a>`
               
                
                estructuraFiltroInterfaces += descargaTrobaPuertos
                estructuraFiltroInterfaces += cantidadTrobasPorPuerto
               // estructuraFiltroInterfaces += listaTrobasPuertosCantidad

                estructuraFiltroInterfaces += `</div>`
                estructuraFiltroInterfaces += `</div>`

                estructuraPrincipal += estructuraFiltroInterfaces
            }
 
            

            if (resultado.hayFiltroTrobas) {

                let estructuraFiltroTroba = `<div class="col-md-6">`

                let nodo = resultado.dataTroba.nodo
                let troba = resultado.dataTroba.troba

                

                estructuraFiltroTroba += `<div class="card p-2">
                                            <h6 class="text-center text-sm font-weight-bold">
                                                Resultado de Nodo - Troba (${nodo} - ${troba})
                                            </h6>`

                let descargaTroba = `<button  data-uno="${nodo}" data-dos="${troba}" class="btn btn-outline-primary btn-sm shadow-sm my-1 mx-auto w-75" id="descargarTroba">Descargar Troba <i class="icofont-cloud-download icofont-lg"></i></button >`
                let DiagnosticoMasivo = `<a href="javascript:void(0)" data-uno="${nodo}" data-dos="${troba}" class="btn btn-outline-danger btn-sm shadow-sm my-1 mx-auto w-75" id="verDiagnosticoMasivo">Diagnostico masivo <i class="icofont-hand-drawn-right icofont-lg"></i></a>`
                let historicoNivelesTroba = `<a href="javascript:void(0)" data-uno="${nodo}" data-dos="${troba}" class="btn btn-outline-info btn-sm shadow-sm my-1 mx-auto w-75" id="verHistoricoNodoTroba">Histórico Niveles de Trobas <i class="icofont-hand-drawn-right icofont-lg"></i></a>`
                let mapa = `<a href="javascript:void(0)" data-uno="${nodo}" data-dos="${troba}"   class="btn btn-outline-success btn-sm shadow-sm my-1 mx-auto w-75" id="verMapa" alt="Ver Mapa" title="Ver Mapa">
                                        Ver Mapa <i class="icofont-google-map icofont-lg"></i>
                            </a>`

                estructuraFiltroTroba += descargaTroba
                estructuraFiltroTroba += DiagnosticoMasivo
                estructuraFiltroTroba += historicoNivelesTroba
                estructuraFiltroTroba += mapa
                estructuraFiltroTroba += `</div>`
                estructuraFiltroTroba += `</div>`

                estructuraPrincipal += estructuraFiltroTroba

            }

            

           

            if (resultado.hayFiltroNiveles) {

                let estructuraFiltroNiveles = `<div class="col-md-6">`

                let puerto = resultado.dataNiveles.puerto

                estructuraFiltroNiveles += `<div class="card p-2">
                                        <h6 class="text-center text-sm font-weight-bold">
                                            Puerto : ${puerto}
                                        </h6>`

                let listaNivelesPuerto = `<a href="javascript:void(0)" data-uno="${puerto}" class="btn btn-outline-success btn-sm shadow-sm my-1 mx-auto w-75" id="verPromediosNivelesCmtsPorPuerto"> Promedios Niveles CMTS por Puerto <i class="icofont-hand-drawn-right icofont-lg"></i></a>`

                estructuraFiltroNiveles += listaNivelesPuerto
                estructuraFiltroNiveles += `</div>`
                estructuraFiltroNiveles += `</div>`

                estructuraPrincipal += estructuraFiltroNiveles

            }
  
            peticiones.redirectTabs($("#resultadoFiltroResultPrincipalTab"))
            $("#resultadoContentFiltroCliente").html(estructuraPrincipal)
              
        })
        .fail(function(jqXHR, textStatus){
      
            $("#respuestaFiltroClienteTroba").html(``)
            $("#contentFiltroClientTroba").removeClass("d-none")

              //console.log( "Error: " ,jqXHR, textStatus);
              //console.log( "Request failed: " ,jqXHR.responseJSON.mensaje);
              // $("#respuestaFiltroClienteTroba").html(jqXHR.responseText)
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

            $("#respuestaFiltroClienteTroba").html(erroresPeticion)
            return false
            
                
        }) 

    })

    //Lista Cantidad Trobas por Puerto

    $("body").on("click","#verListaCantidadTrobasPorPuerto", function(){
        //$("#listaCantidadTrobPuerto").modal("show")
        let interfaces = $(this).data("uno")
        peticiones.cantidadTrobasPorInterfaces(interfaces)
    })

    //Descargar Troba por puerto

    $("body").on("click","#descargarTrobaPuertos", function(){

        let interfaces = $(this).data("uno")
       
        let _this = $(this)

        _this.prop('disabled',true)
        _this.html(`Descargar Troba Puertos <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        <span class="sr-only">Descargando..</span>`)
        
        $.ajax({
            xhrFields: { responseType: 'blob', },
            url: "/administrador/descarga-clientes-troba/excel/clientes-troba-puertos",
            method: 'get',
            data: {
                interfaces
            },
            cache: false, 
            })
            .done(function(result){

                _this.prop('disabled',false)
                _this.html(`Descargar Troba <i class="icofont-cloud-download icofont-lg"></i>`)

                
               var blob = new Blob([result], {
                    type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                });
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = 'trobas_por_puertos.xlsx';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            })

            .fail(function(xhr, jqXHR, textStatus) { 
                _this.prop('disabled',false)
                _this.html(`Descargar Troba Puertos <i class="icofont-cloud-download icofont-lg"></i>`)
                
                var errorMessage1 = xhr.status + ': ' + "Hubo un error en los datos, intente en un minuto por favor."

                $("#body-errors-modal").html(`<div class="w-100 text-center text-danger">${errorMessage1}</div>`)
                $('#errorsModal').modal('show') 
                 
                return false
                
            });
    })

    //Descargar Troba

    $("body").on("click","#descargarTroba", function(){

        let nodo = $(this).data("uno")
        let troba = $(this).data("dos")

        let _this = $(this)

        _this.prop('disabled',true)
        _this.html(` Descargar Troba <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        <span class="sr-only">Descargando..</span>`)
        
        $.ajax({
            xhrFields: { responseType: 'blob', },
            url: "/administrador/descarga-clientes-troba/excel/clientes-troba",
            method: 'get',
            data: {
                nodo,
                troba
            },
            cache: false, 
            })
            .done(function(result){

                _this.prop('disabled',false)
                _this.html(`Descargar Troba <i class="icofont-cloud-download icofont-lg"></i>`)
                
                var blob = new Blob([result], {
                    type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                });
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = 'clientes_por_troba.xlsx';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            })

            .fail(function(xhr, jqXHR, textStatus) { 
                _this.prop('disabled',false)
                _this.html(`Descargar Troba <i class="icofont-cloud-download icofont-lg"></i>`)
                
                var errorMessage1 = xhr.status + ': ' + "Hubo un error en los datos, intente en un minuto por favor."

                $("#body-errors-modal").html(`<div class="w-100 text-center text-danger">${errorMessage1}</div>`)
                $('#errorsModal').modal('show')

                return false
                
            });
    })

    //DM
    $("body").on("click","#verDiagnosticoMasivo", function(){
       // console.log("Ver diagnostico masivo")
        let n = $(this).data("uno")
        let t = $(this).data("dos")

        peticiones.redirectTabs($('#verDiagMasTab')) 

        let parametros = {
            'n':n,
            't':t
        }

        diagnosticoMasivo.lista($('#resultDiagnosticoMasivo'),'/administrador/descarga-clientes-troba/diagnostico-masivo/view',parametros)
    })

    //Historico Trobas

    $("body").on("click", "#verHistoricoNodoTroba", function(){
 
        let nodo = $(this).data("uno")
        let troba = $(this).data("dos")

        let parametros = {
            'nodo':nodo,
            'troba':troba
        }
        
        peticiones.redirectTabs($('#historicoNodoTrobaTab'))  
        $("#historico_nodo_troba_actual").html(`${nodo} - ${troba}`)

        historicoNodoTroba.verHistorialNodoTroba($("#resultHistoricoNodoTroba"),'/administrador/descarga-clientes-troba/historico/nodo-troba',parametros,false)

    })

    //Mapa
    $(".return_verMapaTab").click(function(){
        peticiones.redirectTabs($('#verMapaTab')) 
     })
     
    $("body").on("click","#verMapa", function(){
          
        let n = $(this).data("uno")
        let t = $(this).data("dos")
        let id = 0

        let parametros = {
             n, t, id
        }
        //console.log("parametros a enviar del mapa son: ",parametros)
 
        peticiones.redirectTabs($('#verMapaTab')) 

        mapa.vistaGeneral($("#mapa_content_carga"),"/administrador/descarga-clientes-troba/mapa/view",parametros)
  
    })


    //Edificios
    $("body").on("click",".show_edificio_details", function(){ 

       // console.log("debe mostrar los edificios...")
        peticiones.redirectTabs($('#detalleEdificiosTab'))

        let des_dtt = $(this).data("desdtt")
        let des_via = $(this).data("nomvia")
        let des_puer = $(this).data("numpuer")

        let parametros = {
            'des_dtt':des_dtt,
            'des_via':des_via,
            'des_puer':des_puer
        }

        mapa.detallesEdificios($('#edificios_content_general'),'/administrador/descarga-clientes-troba/mapa/edificios/view',parametros)
   
        
    })

    //Promedio Niveles CMTS por puerto
    $("body").on("click","#verPromediosNivelesCmtsPorPuerto", function(){
        peticiones.redirectTabs($("#listaPromedioNivelesCmtsPuerto"))
        let puerto = $(this).data("uno")
         
        peticiones.cargaPromedioNivelesCmtsPorPuerto($("#resultPromedioNivelesPuerto"),"/administrador/descarga-clientes-troba/puerto/promedio-niveles-cmts",puerto)

    })

    //Historico de niveles CMTS por puerto
    $("body").on("click",".verHistoricoNivelesCmtsPorPuerto", function(){
        peticiones.redirectTabs($("#listaHistoricoNivelesCmtsPuerto"))
        let puerto = $(this).data("uno")

        peticiones.cargaHistoricoNivelesCmtsPorPuerto($("#resultHistoricoNivelesPuerto"),"/administrador/descarga-clientes-troba/puerto/historico-niveles-cmts",puerto)
    })

    //Cablemodem SNR
    $("body").on("click",".verSnrCablemodem", function(){
        let puerto =  $(this).data("uno")

        let _this = $(this)

        let textoDesc = $(this).text()

        _this.prop('disabled',true)
        _this.html(` ${textoDesc} <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        <span class="sr-only">Descargando..</span>`)
        


        $.ajax({
            xhrFields: { responseType: 'blob', },
            url: "/administrador/descarga-clientes-troba/excel/puerto/srn-cablemodem",
            method: 'get',
            data: {puerto},
            cache: false,
        })
        .done(function(result){

            _this.prop('disabled',false)
            _this.html(` ${textoDesc}`)
            
          
            var blob = new Blob([result], {
                type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            });
            var link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = 'cablemodem_snr.xlsx';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        })

        .fail(function(xhr, jqXHR, textStatus) {

            _this.prop('disabled',false)
            _this.html(` ${textoDesc}`)
           
       
            //console.log(xhr)
            //console.log(jqXHR)
            //console.log(textStatus)
            var errorMessage1 = "Hubo un error en los datos, intente en un minuto por favor."

            $("#body-errors-modal").html(`<div class="w-100 text-center text-danger">${errorMessage1}</div>`)
            $('#errorsModal').modal('show')
            return false
        });
    })
    



})