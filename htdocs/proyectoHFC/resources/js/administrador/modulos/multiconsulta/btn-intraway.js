import peticiones from './peticiones.js'
import errors from  "@/globalResources/errors.js"

$(function(){
 
    $("body").on("click", "#detalle_intraway", function(){

        
        let codCliente = $(this).data("cod") 
        let servicio = $(this).data("serv") 
        let producto = $(this).data("prod") 
        let venta = $(this).data("vent") 
        
        peticiones.redirectTabs($('#multiIntrawayDataTab'))
 
        $("#datosIntraway").html(`<div id="carga_person">
                                            <div class="loader">Loading...</div>
                                        </div>`);
         $.ajax({
            url:"/administrador/multiconsulta/intraway/detalle",
            method:"get",
            async: true,
            data:{
                codCliente ,
                servicio ,
                producto ,
                venta           
            },
            dataType: "json", 
        })
        .done(function(data){ 
             
            //console.log("El resultado es:",data)  
            let intrawayCliente = JSON.parse(data.response.html)
             $("#datosIntraway").html(intrawayCliente)
             
        })
        .fail(function(jqXHR, textStatus){
             console.log( "Request failed: " + textStatus );

            if(jqXHR.responseJSON){
                if(jqXHR.responseJSON.mensaje){
                    let erroresMensaje = jqXHR.responseJSON.mensaje  //captura objeto
                    let mensaje = errors.mensajeErrorJson(erroresMensaje)
                    $("#datosIntraway").html(mensaje)
                    return false
                } 
            }
            if(jqXHR.status){
                let mensaje = errors.codigos(jqXHR.status)
                $("#body-errors-modal").html(mensaje)
                $('#errorsModal').modal('show')
                return false
            }
            $("#datosIntraway").html("hubo un problema en la red, intente nuevamente por favor.")
            
 
        }); 


    })

    $("body").on("click", "#verHistoricoConect", function(){
 
       // console.log("se buscara el hisorico conectividad...")

        let _this =$(this)
        let servicio = $(this).data("serv") 
        let producto = $(this).data("prod") 
        let venta = $(this).data("vent") 

        _this.css({'display':"none"})
        $("#resultHistoricoConect").html(`<div id="carga_person">
                                            <div class="loader">Loading...</div>
                                        </div>`);

        $.ajax({
            url:"/administrador/multiconsulta/intraway/historico-conectividad/detalle",
            method:"get",
            async: true,
            data:{
                servicio,
                producto,
                venta           
            },
            dataType: "json", 
        })
        .done(function(data){ 
            _this.css({'display':"inline-block"})
            //console.log("El resultado es:",data)  
            let hisoricoConectividad = JSON.parse(data.response.html)
            if (hisoricoConectividad.trim() == "") {
                $("#resultHistoricoConect").html(`<div class="col-12 mx-0 px-0 text-center">No se encontro histórico del cliente.</div>`)
                return false
            }
             $("#resultHistoricoConect").html(hisoricoConectividad)
             
        })
        .fail(function(jqXHR, textStatus){
            _this.css({'display':"inline-block"})
            $("#resultHistoricoConect").html("")
            // console.log( "Request failed: " + textStatus );
             //$("#resultHistoricoConect").html(`${jqXHR.responseText}`); 

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

            $("#body-errors-modal").html(erroresPeticion)
            $('#errorsModal').modal('show')
            return false
 
        }); 

    })
})