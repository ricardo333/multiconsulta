import errors from  "@/globalResources/errors.js"

$(function(){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
 
    $("body").on("click","#resetDecos", function(){
         
        let codCliente = $(this).data("uno")

        $("#resetDecosResult").html(`<div id="carga_person">
                                    <div class="loader">Loading...</div>
                                </div>`); 
        
        $("#resetDecosModal").modal("show")

        $.ajax({
            url:"/administrador/multiconsulta/reset-decos/detalle",
            method:"get",
            data:{
                codCliente
            },
            dataType: "json", 
        })
        .done(function(data){

            //console.log("El resultado es:",data)  
            let decos = JSON.parse(data.response.html) 
            $("#resetDecosResult").html(decos); 
 
        })
        .fail(function(jqXHR, textStatus){
             
           // console.log( "Error: " + jqXHR, textStatus); 
           // $("#resetDecosResult").html(jqXHR.responseText); 
 
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

           $("#resetDecosResult").html(erroresPeticion); 
           return false
           
           

        }); 

    })

    //Reset Decos

    $("body").on("click",".resetopentrama", function(){
 
        $(this).children('img').addClass("girador");
        let _this = $(this);
             
        let codsrv = $(this).data("uno")
        let codmat = $(this).data("dos")
        let numser =  $(this).data("tres")
        let cliente =  $(this).data("cuatro")

           
          
        $.ajax({
            url:`/administrador/multiconsulta/reset-decos/trama`,
            method:"post",
            data:{ 
                cliente,
                codsrv,
                numser,
               codmat
            },
            dataType: "json",
            }).done(function(data){
            
                $(_this).children('img').removeClass("girador");
                // console.log(data)
               
                $("#result_decos_send").html(`<div class="container text-center font-weight-bold alert alert-success fade show" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            ${data.mensaje}</div>`)

            
            }).fail(function(jqXHR, textStatus){ 
                console.log( "Request failed: " , jqXHR, textStatus);
                $(_this).children('img').removeClass("girador");
               // $("#result_decos_send").html(jqXHR.responseText); 

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


               $("#result_decos_send").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            ${erroresPeticion}</div>`)
                return false
 
                
            });
    })

    $("body").on("click","#resetallopentrama", function(){

        
        let codCliente = $(this).data("uno")
        let codsrv = $(this).data("dos")

        $("#preloadResetDecos").html(`<div id="carga_person">
                                        <div class="loader">Loading...</div>
                                    </div>`);
        $("#resetDecosResult").addClass("d-none");

        $.ajax({
            url:`/administrador/multiconsulta/reset-decos/tramas`,
            method:"post",
            data:{ 
                codCliente,
                codsrv
             },
            dataType: "json",
        }).done(function(data){
            $("#preloadResetDecos").html("")
            $("#resetDecosResult").removeClass("d-none");
            //console.log("el resultado es: ",data)
            let resultado = data.response.data
            let resultadoList = `<ul class="list-unstyled">`
            resultado.forEach(el => {
                resultadoList += `<li>${el}</li>`
            });
            resultadoList += `</ul>`
 
            $("#result_decos_send").html(`<div class="container text-center font-weight-bold alert alert-success fade show" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            ${resultadoList}</div>`)
            
        }).fail(function(jqXHR, textStatus){ 

            $("#preloadResetDecos").html("")
            $("#resetDecosResult").removeClass("d-none");

            //console.log("Request failed: " , jqXHR, textStatus);
          
            // $("#result_decos_send").html(jqXHR.responseText); 

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

            $("#result_decos_send").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        ${erroresPeticion}</div>`) 
            return false
  
        });

    })
})