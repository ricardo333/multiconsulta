$(function(){

    //setTimeout(function(){ alert("Hello"); }, 3000); //refresh 3 segundos

    principal()

    function principal(){

        let control = $("#controlMigracion").val();

        if(control=="1"){
            migrar();
        }

    }


    function migrar(){

        $("#precarga").html(`<div id="carga_person" class="pre-estados-modems">
                                <div class="loader">Loading...</div>
                              </div>`);

        $.ajax({
            url:`/migracion/proceso`,
            method:"GET",
            async: true,
            data:{},
            cache: false, 
            dataType: "json",
          })
  
          
          .done(function(data){ 
            $("#precarga").html("");
            console.log("El resultado HTML",data);
            let status = JSON.parse(data.response.html)
            $("#resultado_migracion").html(status) 
          })
        
          .fail(function(jqXHR, textStatus, errorThrown){
            $("#precarga").html("");
            let mensaje = "Tiempo de ejecucion excedido, no se ha terminado de migrar. Actualizar la pagina para seguir el proceso...."
            $("#resultado_migracion").html(mensaje)
            
        });

    }

    $("#migrar").click(function(){
        var opcion = confirm("Desea iniciar proceso de migración?");

        if (opcion == true) {
            migrar();
        }
    })

})