import errors from  "@/globalResources/errors.js"
import columnas from  "@/globalResources/tablas/columnas.js"

const peticiones = {}


peticiones.redirectTabs = function redirectTabs(identificador) {
    $('#tabsCuadroMandoContent > .tab-pane').removeClass('show');
    $('#tabsCuadroMandoContent > .tab-pane').removeClass('active');
    identificador.tab('show')
}


peticiones.armandoColumnasCmts = function armandoColumnasCmts()
{
    let columnasContent =  [ {data: 'tipo'}  ]
    columnasContent.push({data: 'cant'})
    columnasContent.push({data: 'clientes'})
    columnasContent.push({data: 'nodo'})
    return columnasContent
}

function procesandoResultadoLista(result)
{   

    let tok = $('meta[name="csrf-token"]').attr('content')
    for (let i = 0; i < result.length ; i++) {

        result[i].tipo = `<span class="font-weight-bold">${result[i].tipo}</span>`

        result[i].cant = `<span class="font-weight-bold">${result[i].cant}</span>`

        result[i].clientes = `<span class="font-weight-bold">${result[i].clientes}</span>`

        result[i].nodo = `<form method="post" action="${result[i].url}">  
                            <input type="hidden" name="_token" value="${tok}">
                            <input type="hidden" name="motivo" value="${result[i].motivo}">
                            <input type="hidden" name="nodo" value="${result[i].nodo}">
                            <input type="image" src="/images/icons/link.png" alt="Submit" width=15 height=15 border=0>
                        </form>`

        /*
        <form method="post" action="/administrador/ingreso-averias">  
            <input type="hidden" name="_token" value="${tok}">
            <input type="hidden" name="motivo" value="cuadroMando">
            <input type="image" src="/images/icons/link.png" alt="Submit" width=15 height=15 border=0>
        </form>
        */

        }

        return result

}


peticiones.cargaCmtsLista = function cargaCmtsLista(COLUMNS_CAIDAS,BUTTONS_CUADRO_MANDO,tabla)
{

    let categoria = $("#listaCategoriasDashboard").val()

    console.log(categoria)
 
//$("#display_filter_special").prop("disabled", true); 




tabla.DataTable({
        "destroy": true,
        "processing": true,
        "serverSide": true,
        "dom":'<"row mx-0"'
                +'<"col-12 col-sm-6"l><"col-12 col-sm-6 text-right"B>>'
                +'<"row"'
                +'<"col-sm-12 px-0 table-responsive tableFixHead"t>>'
                +'<"row"'
                +'<"col-12 col-sm-5"i>>'
                +'r',
        "buttons": BUTTONS_CUADRO_MANDO,
        "ajax": {  
                'url':'/administrador/cuadro-mando/lista',
                "type": "GET", 
                "data": function ( d ) {
                       d.filtroCategoria = categoria;
                },
                'dataSrc': function(json){
                        //return json
                        let result = json.data
                        //  console.log("El result es: ",result)
                        let dataProcesada = procesandoResultadoLista(result)
                        return dataProcesada
                },
                'error': function(jqXHR, textStatus, errorThrown)
                {  
                        /*
                        if (REFRESH_PERMISO) {
                                ESTA_ACTIVO_REFRESH = true
                                peticiones.resetInterval()
                        }
                        */
                        $('#errorsModal').modal('show') 
                        if(jqXHR.status){
                                if (jqXHR.status == 401) {
                                        location.reload();
                                        return false
                                } 
                            // peticiones.redirectTabs($('#multiMapTab')) 
                            return false
                        } 
                        // peticiones.redirectTabs($('#multiMapTab')) 
                        return false 
                }
        }, 
        "columns": COLUMNS_CAIDAS,
        'columnDefs': [
                
                {
                   'targets': '_all',
                   'createdCell':  function (td, cellData, rowData, row, col) { 
                        
                        $(td).css({"background":`${rowData.color}`,"color":`white`});
                        $(td).addClass("text-center")

                   }
                },

                {
                     
                    "targets": '_all',
                    "orderable" : false,  //Quito el ordenamiento de la cabecera cuando se hace click
                    "searchable": false,
                        
                } 
        ] ,
        
        "initComplete": function(){
                /*
                if (REFRESH_PERMISO) {
                        ESTA_ACTIVO_REFRESH = true
                        peticiones.resetInterval()
                        
                }
                */
        },
        
        "pageLength": 150,
        "language": {
                    //"info": "_TOTAL_ registros",
                    "info": " ",
                    "search": "Buscar",
                    
                    "paginate":

                    {   "next": false,
                        "previous": false,
                        "active": false,
                            //"next": "Siguiente",
                            //"previous": "Anterior",
                    },
                    
                    "lengthMenu": " ",
                    "loadingRecords": "<div id='carga_person'> <div class='loader'>Cargando...</div></div>",
                    "processing": "<div id='carga_person'> <div class='loader'>Procesando...</div></div>",
                    "emptyTable": "No hay datos disponibles",
                    "zeroRecords": "No hay coincidencias", 
                    "infoEmpty": "",
                    "infoFiltered": ""
        }
        
    });
    
    tabla.parent().addClass("table-responsive tableFixHead") 
    // $("#filtroContentHFC").removeClass("d-none")

    let tablaHead = $('.tableFixHead').find('thead th')
    $('.tableFixHead').on('scroll', function() {
    // console.log("ejecutando"+this.scrollTop); 
    tablaHead.css('transform', 'translateY('+ this.scrollTop +'px)'); 
    });


}


//----------------------------------------//

//$("#registrarGestionInd").click(function(){
peticiones.enrutamiento = function enrutamiento(urlModulo,motivo)
{ 
    
        $.ajax({
            url:urlModulo,
            method:"post",
            data:{
                motivo
            },
            //dataType: "json", 
        })
        .done(function(data){
            
            
            console.log("El resultado HTML:",data);
            
            //$("#modulo_content_carga").load.data
            //$("#modulo_content_carga").html(data)
            //let interfaz = data.response
            //$("#modulo_content_carga").html(interfaz)
            //$("#modulo_content_carga").html(data.view)

            //$("#modulo_content_carga").append(data)
            //window.location.href = urlModulo;
            //let status = data.html;
            //$("#modulo_content_carga").html(status)
            window.location.replace(data);
            
            /*
            console.log("El resultado HTML",data);
            $('#modulo_content_carga').html(data);
            */

            /*
            console.log("El resultado del modulo es: ",data)
            //let mapa = JSON.parse(data.response.html)
            let mapa = data.response.html
            //contenedor.html(mapa)
            $("#modulo_content_carga").html(mapa)
            //console.log("El resultado de Store gestion c es: ",data)
            */
            
      
        })
        .fail(function(jqXHR, textStatus){
          
            /*
            $("#storeCuarentenaGestionIndividual").removeClass("d-none")
            $("#resultadoStoreGestionCuarentena").html("")
            $("#preloadCuarentenaGestionInd").html("") 
            */
    
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
    
            $("#resultadoStoreGestionCuarentena").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                        ${erroresPeticion}</div>`)
    
             
            return false
     
        }) 
     
    
}

//-----------------------------------------//

export default peticiones