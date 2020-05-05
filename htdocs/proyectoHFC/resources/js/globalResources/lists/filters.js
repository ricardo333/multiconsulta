import paginacionGlobal from '@/globalResources/lists/paginacionGlobal.js'

const filters = {}

filters.loadListGeneral = function loadListGeneral(dataEnvio,ruta,PersonalityPeticion,method,callBack)
{
  let envio = dataEnvio
  let route = ruta
  let metodo = method
  //search
    
  if (parseInt(PersonalityPeticion.pageFilter) > 0) {
    route = `${ruta}?page=${PersonalityPeticion.pageFilter}`
  }
  
  console.log("la data que se enviara sera: ", envio)
  console.log("la ruta final es: ", route)
  $.ajax({
    url:route,
    async: true,
    method:metodo,
    data:envio,
    cache: false, 
    dataType: "json", 
  })
  .done(function(data){ 
   // console.log("la data list a cargar es:",data)
 
    return callBack(data);
     
  })
  .fail(function(jqXHR, textStatus, errorThrown){
      console.log( "Request failed: " ,textStatus ,jqXHR,errorThrown);
       
      return callBack({
        "error":"failed",
        "jqXHR":jqXHR,
        "textStatus":textStatus,
        "errorThrown":errorThrown,
      });
      
  }); 

}

filters.partialsTableList = function partialsTableList(data, PersonalityTableList, printSections)
{
 //console.log("la data recibida es:",data)
  let count = parseInt(parseInt(data.current_page) - parseInt(1) ) * parseInt(data.per_page) + parseInt(1)
  let from = count
  let hasta = parseInt(from) + parseInt(data.count -1)
  let total_resultado = data.total 
  //console.log("el hasta es:",hasta)
  //console.log("el total es:",total_resultado)
  //paginacion
  //console.log("la cantidad de pagina son:",data.total_pages, data.total_pages > 1)

  let paginaActualDowloadExcel = 1

  if(data.total_pages > 1){

    paginaActualDowloadExcel = (parseInt(data.current_page) > 1) ? data.current_page : 1

    let pagina_actual = data.current_page
    
    //carga lista footer links filters
    if(PersonalityTableList.SectionFooterLinkNumberFilter){
       paginacionGlobal.cargaPaginacion(data.total_pages,pagina_actual,printSections.SectionFooterLinkNumberFilter) 
    }

    //Carga number filter table
    if(PersonalityTableList.SectionNumberPageFilter){
      printSections.SectionNumberPageFilter.html(`
            <div class="input-group">
              <input type="text" id="paginateData" class="form-control form-control-sm shadow-sm" placeholder="[15 - 50]" 
              value="${parseInt(data.per_page) <= 15 ? '' : data.per_page}"
              >
              <span class="input-group-btn">
                <a href="javascript: void(0)" id="paginarResult" class="btn btn-outline-primary btn-sm shadow-sm" >Por Página</a>
              </span>
            </div>
        `)
    }
      
  }else{
    //Limpia lista footer links filters
      if(PersonalityTableList.SectionFooterLinkNumberFilter){
        printSections.SectionFooterLinkNumberFilter.html('')
      }
 
  }

  if(data.total >= 1){

    //Carga export Excel filter table
      if(PersonalityTableList.SectionExcelExportFilter){
        printSections.SectionExcelExportFilter.html(`
              <a href="javascript:void(0)" class="btn btn-outline-success btn-sm shadow-sm" id="exportExcelData"  data-paginate="${paginaActualDowloadExcel}">
                <i class="fas fa-file-excel"></i>
              </a>
        `)
      }
    
    //Carga info table filters
      if(PersonalityTableList.SectionInfoPageFilter){
       
        printSections.SectionInfoPageFilter.html(`
            <span class="font-weight-bolder mx-1">${from}</span> hasta 
            <span class="font-weight-bolder mx-1">${hasta}</span> de 
            <span class="font-weight-bolder mx-1">${data.total}</span>
        `)
      }
 
     
  }else{

    //Limia export Excel filter table
      if(PersonalityTableList.SectionExcelExportFilter){
        printSections.SectionExcelExportFilter.html(``)
      }
    
    //Limia info table filters
      if(PersonalityTableList.SectionInfoPageFilter){
        printSections.SectionInfoPageFilter.html(``)
      }

  } 

}

export default filters
