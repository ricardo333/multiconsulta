import errors from  "@/globalResources/errors.js"
import graficaLlamadasNodos from  "@/globalResources/modulos/grafico-nodos-lineal.js"

const peticiones = {}

peticiones.resetInterval = function resetInterval(){
        if (INTERVAL_LOAD != null) {
                clearInterval(INTERVAL_LOAD)
                INTERVAL_LOAD = setInterval(() => { 
                        if (ESTA_ACTIVO_REFRESH) { 
                              $("#preloadCharger").html("");
                              peticiones.cargandoPeticionPrincipal()
                        }
                
                }, 60000);
        }
}

peticiones.getListaNodos = function getListaNodos(jefatura,nodo,callBack)
{

    $.ajax({
        url:`/administrador/grafica-llamadas-nodos/lista-nodos-graficas`,
        method:"get",
        async: true,
        data: {
          jefatura,
          nodo
        },
        cache: false, 
        dataType: "json", 
      })
      .done(function(data){ 
        //console.log("callbak antes del envio:",data)
        return callBack(data);
      })
      .fail(function(jqXHR, textStatus, errorThrown){
         // console.log( "Request failed: " ,textStatus ,jqXHR,errorThrown);
         
          return callBack({
            "error":"failed",
            "jqXHR":jqXHR,
            "textStatus":textStatus,
            "errorThrown":errorThrown,
          });
          
      }); 

}

peticiones.cargandoPeticionPrincipal = function cargandoPeticionPrincipal()
{
        
        $("#contencionGraficaLlamadasNodos").html("");	
        $("#preloadGraph").html(`<div id="carga_person" class="pre-estados-modems">
                                <div class="loader">Loading...</div>
                              </div>`);

        let jefatura = $("#jefaturaLlamadasNodos").val()
        let nodo = ($("#nodoLlamadasNodos").val()!='')? $("#nodoLlamadasNodos").val():'';
        
        peticiones.getListaNodos(jefatura,nodo,function(res){ 

          let clock = new Date() 
          let hour =   clock.getHours() 
          let minutes = clock.getMinutes() 
          let seconds = clock.getSeconds() 
          let print_clock = hour + ":" + minutes + ":" + seconds 
          
          if (REFRESH_PERMISO) {
            ESTA_ACTIVO_REFRESH = true
            peticiones.resetInterval()
          }
          
          if (Object.keys(res.response.nodos).length === 0) {
            
            $("#preloadGraph").html("");
            //$("#averiasJefTabFiltro").html("");
            $("#contencionGraficaLlamadasNodos").html(`<div class="width-100"><h2 class="text-center text-primary"><b>Estimado usuario</b></h2>
                                <h3 class="text-center text-danger"><b>No tenemos nodos que superen los umbrales de llamadas ${print_clock}</b></h3></div>`);

          }else{

            res.response.nodos.forEach(el => {

                  let grafico = graficaLlamadasNodos.grafico(el.nodo)
                
            }) 

          }
          

        })

}
 
peticiones.cargaNodosProjefatura = function cargaNodosProjefatura(jefatura,callBack)
{

    $.ajax({
        url:`/administrador/grafica-llamadas-nodos/jefatura-nodos`,
        method:"get",
        async: true,
        data:{
            jefatura
        },
       cache: false, 
       dataType: "json", 
      })
      .done(function(data){ 
        //console.log("callbak antes del envio:",data)
        return callBack(data);
      })
      .fail(function(jqXHR, textStatus, errorThrown){
         // console.log( "Request failed: " ,textStatus ,jqXHR,errorThrown);
           
          return callBack({
            "error":"failed",
            "jqXHR":jqXHR,
            "textStatus":textStatus,
            "errorThrown":errorThrown,
          });
          
      }); 
 

}

export default peticiones
