 
const peticiones = {}


peticiones.redirectTabs = function redirectTabs(identificador) {
    $('#tabsMultiContent > .tab-pane').removeClass('show');
    $('#tabsMultiContent > .tab-pane').removeClass('active');
    identificador.tab('show') 
}

peticiones.searchCountMulticonsulta = function searchCountMulticonsulta(tipo,valor,callBack)
{
  
  //search
    
  //console.log("la data que se enviara sera: ", tipo,valor)
 
  $.ajax({
    url:`/administrador/multiconsulta`,
    method:"POST",
    async: true,
    data:{
        type_data:tipo,
        text:valor
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

peticiones.armandoMultiplesResultadosNClientes = function armandoMultiplesResultadosNClientes(data){

    let idCliente =``
    let nombreCliente = ``
    let tabla_multiple = `<div class="w-100 result-form-multi">
                            <h3 class="text-center text-uppercase">Seleccionar Cliente</h3>
                            <div  class="div_busqueda table-responsive">
                                    <table class="table table-bordered table-hover tabla_multiple_data_cli" id="tablaresvarios">
                                        <thead>
                                            <tr>
                                                <th class="celda_titulo" >Mac Address</th>
                                                <th class="celda_titulo" >Service Package</th>
                                                <th class="celda_titulo" >CMTS</th>
                                                <th class="celda_titulo" >Interface</th>
                                                <th class="celda_titulo" >MaC State</th>
                                                <th class="celda_titulo" >Fabricante</th>
                                                <th class="celda_titulo" >Modelo</th>               
                                                <th class="celda_titulo" >Direccion</th>
                                            </tr>
                                        </thead>
                                        <tbody>`
                                        data.forEach(el => {
                                            tabla_multiple += `<tr>`
                                            if (el.MACADDRESS != null) {
                                                tabla_multiple += `<td class="celda2" >${el.MACADDRESS}</td>`
                                                tabla_multiple += `<td class="celda2" >${el.SERVICEPACKAGE}</td>`
                                                tabla_multiple += `<td class="celda2" >${el.cmts1}</td>`
                                                tabla_multiple += `<td class="celda2" >${el.interface}</td>`
                                                tabla_multiple += `<td class="celda2" >${el.MACState == null ? "" : el.MACState}</td>`
                                                tabla_multiple += `<td class="celda2" >${el.Fabricante == null ? "" : el.Fabricante}</td>`
                                                tabla_multiple += `<td class="celda2" >${el.Modelo == null ? "" : el.Modelo}</td>`
                                                tabla_multiple += `<td class="celda2" >${el.direccion == null ? "" : el.direccion}</td>`
                                            }
                                          
                                            tabla_multiple += `</tr>`
                                            if (el.IDCLIENTECRM != null && el.IDCLIENTECRM > 0) idCliente =  el.IDCLIENTECRM 
                                            if (el.Nombre != null && el.Nombre.length > 0 )  nombreCliente = el.Nombre
                                        }); 
    tabla_multiple += ` </tbody>
                                        
                                    
                                    </table>
                                    <div id="info_client_multiple">
                                    <span class="campo">Código:</span> <span class="result">${idCliente}</span>
                                    <span class="campo">Cliente:</span> <span class="result">${nombreCliente}</span>
                                    </div>
                            </div>
                        </div>`

    $("#multiple_result").html(tabla_multiple)
    $("#searchModal").modal("show")
}

peticiones.armandoMultiplesResultadosIntraway = function armandoMultiplesResultadosIntraway(data){

    
    let tabla_multiple = `<div class="w-100 result-form-multi">
                            <h3 class="text-center text-uppercase">Seleccionar Cliente</h3>
                            <div  class="div_busqueda table-responsive">
                                    <table class="table table-bordered table-hover tabla_multiple_data_cli" id="tablaresvarios">
                                        <thead>
                                            <tr>
                                                <th class="celda_titulo" >Mac Address</th>
                                                <th class="celda_titulo" >Service Package</th>
                                                <th class="celda_titulo" >CMTS</th>
                                                <th class="celda_titulo" >Interface</th> 
                                                <th class="celda_titulo" >MaC State</th>
                                                <th class="celda_titulo" >Estado IN</th>
                                                <th class="celda_titulo" >Fabricante</th>
                                                <th class="celda_titulo" >Modelo</th>               
                                                <th class="celda_titulo" >Direccion</th>
                                            </tr>
                                        </thead>
                                        <tbody>`
                                        data.report[0].Docsis.forEach(el => {
                                         
                                              tabla_multiple += `<tr>`
                                              if (el.Macaddress != null) {
                                                  tabla_multiple += `<td class="celda2" >${el.Macaddress}</td>`
                                                  tabla_multiple += `<td class="celda2" >${el.ServicePackage == null ? "" : el.ServicePackage}</td>`
                                                  tabla_multiple += `<td class="celda2" >${(el.multiconsulta)? el.multiconsulta.cmts1 : ""}</td>`
                                                  tabla_multiple += `<td class="celda2" >${(el.multiconsulta)? el.multiconsulta.interface : ""}</td>`
                                                  tabla_multiple += `<td class="celda2" >${(el.multiconsulta)? (el.multiconsulta.MACState == null ? "" : el.multiconsulta.MACState) : ""}</td>`
                                                  tabla_multiple += `<td class="celda2" >${(el.Activo == "SI")? "ACTIVO" : "INACTIVO"}</td>`
                                                  tabla_multiple += `<td class="celda2" >${(el.multiconsulta)? (el.multiconsulta.Fabricante == null ? "" : el.multiconsulta.Fabricante) : "" }</td>`
                                                  tabla_multiple += `<td class="celda2" >${(el.multiconsulta)? (el.multiconsulta.Modelo == null ? "" : el.multiconsulta.Modelo) : "" }</td>`
                                                  tabla_multiple += `<td class="celda2" >${(el.multiconsulta)? (el.multiconsulta.direccion == null ? "" : el.multiconsulta.direccion) : "" }</td>`
                                              }
                                            
                                              tabla_multiple += `</tr>` 
                                          
                                            
                                        }); 
    tabla_multiple += ` </tbody>
                                        
                                    
                                    </table>
                                    <div id="info_client_multiple">
                                    <span class="campo">Código:</span> <span class="result">${data.report[0].idClienteCRM}</span>
                                    <span class="campo">Cliente:</span> <span class="result">${data.report[0].Nombre}</span>
                                    </div>
                            </div>
                        </div>`

    $("#multiple_result").html(tabla_multiple)
    $("#searchModal").modal("show")
}

peticiones.cargaArbolDecisiones = function cargaArbolDecisiones(callBack)
{

    $.ajax({
        url:`/administrador/multiconsulta/arbol-decision`,
        method:"GET",
        async: true,
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

peticiones.cargaArbolDecisionesPorMensaje = function cargaArbolDecisionesPorMensaje(mensajeCliente,imagen,callBack){

    $.ajax({
      url:`/administrador/multiconsulta/arbol-decision/por-mensaje`,
      method:"GET",
      data: { 
        mensajeCliente,
        imagen
      },
      async: true,
      //cache: false, 
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

peticiones.cargaPeticionArbol = function cargaPeticionArbol(valorSelect,data1,imagen,callBack)
{

    $.ajax({
      url:`/administrador/multiconsulta/arbol-decision/paso/${data1}/detalles`,
      method:"GET",
      data: { 
       // codCliente,
        valorSelect,
       // data2,
        imagen
      },
      async: true,
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
peticiones.registrosPasosArbol = function registrosPasosArbol(pasos,marcaRapida,codCliente,callBack)
{

    $.ajax({
      url:`/administrador/multiconsulta/arbol-decision/registros`,
      method:"POST",
      data: { 
        "decisiones":pasos,
        "mrapida":marcaRapida,
        "codCliente":codCliente
      },
      async: true,
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