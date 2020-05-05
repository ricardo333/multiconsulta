 
const peticiones = {}


peticiones.redirectTabs = function redirectTabs(identificador) {
    $('#tabsArbolDecisiones > .tab-pane').removeClass('show');
    $('#tabsArbolDecisiones > .tab-pane').removeClass('active');
    identificador.tab('show') 
}


peticiones.cargaDetallesRamas = function cargaDetallesRamas()
{
 
  $('#dataDecisionArbol').DataTable({
      "destroy": true,
      "processing": true, 
      "dom":'<"row mx-0"'
                        +'<"col-12 col-sm-6"l><"col-12 col-sm-6"f>>'
                    +'<"row position-relative"'
                        +'<"col-sm-12 px-0 table-responsive table-text-xs tableFixHead"t>'
                        +'r>'
                    +'<"row"'
                        +'<"col-12 col-sm-5"i><"col-12 col-sm-7"p>>',
      "language": {
          "info": "_TOTAL_ registros",
          "search": "Buscar",
          "paginate": {
              "next": "Siguiente",
              "previous": "Anterior",
          },
          "lengthMenu": 'Mostrar <select >'+
                      '<option value="15">15</option>'+
                      '<option value="50">50</option>'+
                      '<option value="100">100</option>'+
                      '<option value="-1">Todos</option>'+
                      '</select> registros',
          "loadingRecords": "<div id='carga_person'> <div class='loader'>Cargando...</div></div>",
          "processing": "<div id='carga_person'> <div class='loader'>Procesando...</div></div>",
          "emptyTable": "No hay datos disponibles",
          "zeroRecords": "No hay coincidencias", 
          "infoEmpty": "",
          "infoFiltered": ""
      }
  });

   
  let tablaHead = $('.tableFixHead').find('thead th')
  $('.tableFixHead').on('scroll', function() {
      // console.log("ejecutando"+this.scrollTop); 
      tablaHead.css('transform', 'translateY('+ this.scrollTop +'px)'); 
  }); 


}

peticiones.listaPasoRamasJson = function listaPasoRamasJson(paso,callBack)
{

    $.ajax({
      url:`/administrador/arbol-decision/paso-anterior/${paso}/show`,
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

peticiones.rearmandoDataRamasTabla = function rearmandoDataRamasTabla(data,tb)
{

    let tablaNewData = ``
    data.forEach(el => {
       tablaNewData += `<tr>
                           <td>${el.id}</td>
                           <td>${el.detalle}</td>
                           <td>
                                <img src="/images/upload/arbol-decisiones/${ el.img_total ? el.img_total : 'sinimagen.png' }" alt="" class="img-thumbnail-arbolPasos">
                           </td>
                           <td>
                                <img src="/images/upload/arbol-decisiones/${ el.img_negocios ? el.img_negocios : 'sinimagen.png' }" alt="" class="img-thumbnail-arbolPasos">
                           </td>
                           <td>
                                <img src="/images/upload/arbol-decisiones/${ el.img_masivo ? el.img_masivo : 'sinimagen.png' }" alt="" class="img-thumbnail-arbolPasos">
                           </td> 
                           <td>
                               <div class="d-flex justify-content-center">`

       if(PERMISO_EDIT){
           tablaNewData +=   `<a href="javascript:void(0)" class="btn btn-sm btn-outline-primary shadow-sm mx-1 editDecisionArbol" 
                                                   data-uno="${el.id}" data-dos="${el.detalle}" data-tres="${el.img_total}"
                                                   data-cuatro="${el.img_negocios}" data-cinco="${el.img_masivo}" data-seis="${tb}">
                                           <i class="icofont-edit-alt icofont-md"></i>
                                       </a>`
       }
       
       if(PERMISO_ESTRUCTURA){
           tablaNewData +=   `<a href="javascript:void(0)" class="btn btn-sm btn-outline-success shadow-sm mx-1 estructuraDecisionArbol" 
                                                   data-uno="${el.id}" data-dos="${el.detalle}"
                                                   data-tres="${tb}">
                                           <i class="icofont-tree icofont-md"></i>
                                       </a>`
       }
       
       tablaNewData +=         `</div>
                           </td> 
                       </tr>`
    });

    $("#cargaListDetalleRamas").html(tablaNewData);

}

 

peticiones.procesandoEstructurasArbol = function procesandoEstructurasArbol(tabla,identificador,detalle,callBack){

    $.ajax({
        url:`/administrador/arbol-decision/ramas/estructura`,
        method:"GET",
        async: true,
        data:{
            tabla,
            identificador,
            detalle
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

peticiones.storeRamaChild = function storeRamaChild(formData,callBack){

    $.ajax({
        url:`/administrador/arbol-decision/rama/store`,
        method:"POST",
        async: true,
        data:formData,
        cache: false, 
        contentType: false,
        processData: false,
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

peticiones.updateRamaEstructura = function updateRamaEstructura(formData,callBack){

    $.ajax({
        url:`/administrador/arbol-decision/rama/edit`,
        method:"POST",
        async: true,
        data:formData,
        cache: false, 
        contentType: false,
        processData: false,
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


peticiones.deleteRamaEstructura = function deleteRamaEstructura(eliminar,tbNamePage,callBack){

    $.ajax({
        url:`/administrador/arbol-decision/rama/delete`,
         method: "POST",
        data: { 
          "arrayDelete":eliminar,
          tbNamePage
        },
        dataType:"json",
        async: true,
        cache: false
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