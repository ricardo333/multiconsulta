const paginacionGlobal = {}


paginacionGlobal.cargaPaginacion = function cargaPaginacion(cantPaginas, paginaActual, showDatail)
{
  
  let pagination = `<ul class=" pagination justify-content-center pagination-sm">`
  /*console.log(paginaActual > 1, "eval");
  return false;*/
  if(paginaActual > 1){
    
    pagination += `<li class="page-item"><a href="javascript: void(0)" class="page-link shadow-sm page-link-filter" data-paginate="1">primero</a></li>`
    pagination += `<li class="page-item"><a href="javascript: void(0)" class="page-link shadow-sm page-link-filter" data-paginate="${parseInt(paginaActual) - 1}"> < </a></li>`
  }

    let indexPage = paginaActual
    let limit_cant = parseInt(indexPage) + parseInt(2)
    let inicioUltimaspaginas = parseInt(cantPaginas) - parseInt(3)
    

    while (indexPage <= cantPaginas) {
      //console.log("page es:",indexPage)//13 - 14 - 15
      //console.log("el limitante es:",limit_cant)//15
      //console.log("Inicio ultima pagina es :",inicioUltimaspaginas)//14 siempre

      pagination += `<li class="page-item`
        if( indexPage == paginaActual) pagination +=` active `
      pagination += `"><a href="javascript: void(0)" class="page-link shadow-sm page-link-filter" data-paginate="${indexPage}">${indexPage}</a></li>`
     
      if(indexPage == limit_cant){

        if (indexPage < inicioUltimaspaginas) {

            if( parseInt(parseInt(inicioUltimaspaginas) - parseInt(indexPage) ) >= 1){
              pagination += `<li class="page-item"><span class="page-link shadow-sm">...</span></li>`
              indexPage = inicioUltimaspaginas
            }else{
              indexPage = inicioUltimaspaginas
            }  
          }
        
        
      }
        
        indexPage++ 

    }
    
      if(paginaActual < cantPaginas) {
        pagination += `<li class="page-item"><a href="javascript: void(0)" class="page-link page-link-filter" data-paginate="${parseInt(paginaActual) + 1}"> > </a></li>`
      }

        pagination += `<li class="page-item"><a href="javascript: void(0)" class="page-link page-link-filter" data-paginate="${cantPaginas}">último</a></li>`
    

      pagination +=`</ul>`

    //$("#result_page_list").html(pagination)
      showDatail.html(pagination)

}

export default paginacionGlobal
