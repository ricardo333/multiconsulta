import errors from '@/globalResources/errors'


$(function(){

    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

   load_modulos()

   const filter_modulo = document.getElementById('filter_modulos')
   filter_modulo.addEventListener('keydown', e => {
      if(e.keyCode == 13){ 
        load_modulos() 
      } 
      
    })

 

})

function load_modulos()
{
  document.getElementById("listModulos").innerHTML = `<div id="carga_person">
                                                        <div class="loader">Loading...</div>
                                                      </div>` 

  let filter = $("#filter_modulos").val()
    $.ajax({
        url:`/administrador/lista`,
        method:"get",
        data:{nombre:filter},
        dataType: "json", 
      })
      .done(function(data){
       // console.log(data) 

        if(data.error){
          $("#body-reload-modal").html(`
            <p>Hubo un error al cargar los modulos, se intentará nuevamente!</p>
          `)
          $("#reloadModal").modal("show")
          return false
        }
        //console.log("la ruta es: ",data.response.data.length)
        if(data.response.data.length == 0){ 
         // console.log("esta ingresando por vacio")
          $("#body-errors-modal").html(`<p>No hay modulos disponibles asignados para su rol</p>`)
          $("#errorsModal").modal("show")
        }
        
        let lista_modulos = data.response.data
        let estructura = ``
        lista_modulos.forEach(el => {
          //col-6 col-sm-4 col-md-3 col-lg-3
          estructura += `<div class="col-6 col-sm-3 col-md-2 px-1 mb-2">
                            <a href="${el.url}" class="text-decoration-none">
                              <div class="card h-100">
                                <div class="content-img-mod text-center d-flex"><img class="card-img-top img-modulo-general" src="${el.imagen}" alt="Modulos publicos list"></div>
                                <div class="card-body p-1 text-center text_decoration_none d-flex align-items-center justify-content-center content-text-mod">
                                  <div class="font-weight-bold text-uppercase text_modulo_publico text-center">${el.nombre}</div>
                                </div>
                              </div>
                              </a>
                              </div> `
                              //  <h6 class="font-weight-bold text-uppercase text_modulo_publico text-center">${el.nombre}</h6>
        });
        $("#listModulos").html(estructura)
      })
      .fail(function(jqXHR, textStatus){
        console.log("error",jqXHR, textStatus)
        $("#listModulos").html("") 
        /*if(jqXHR.responseJSON){
          let errors = jqXHR.responseJSON.message  //captura objeto
          //recorreo objeto como array
          let mensaje_error = errors.mensajeErrorJson(errors) 
          $("#body-reload-modal").html(`<p>${mensaje_error}.</p>`)
          $("#reloadModal").modal("show") 
          return false;
        }*/
        if(jqXHR.status){  
          let mensaje_error = errors.codigos(jqXHR.status)
          $("#body-reload-modal").html(`<p>${mensaje_error}</p>`)
          $("#reloadModal").modal("show")
          return false 
        }
        $("#body-reload-modal").html(`<p>Falla inesperada con la petición. Intente nuevamente.</p>`)
        $("#reloadModal").modal("show") 
      })
}
 