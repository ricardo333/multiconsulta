import peticiones from './peticiones.js'
import errors from  "@/globalResources/errors.js"

$(function(){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(".return_mapaFuentesTab").click(function(){
        peticiones.redirectTabs($('#mapaFuentesTab')) 
    })


    $("body").on("click",".verMapaFuentes", function(){

        let nodo = $(this).data("uno")
        let troba = $(this).data("dos")
        //let cmts = $(this).data("tres")
        //let interface = $(this).data("cuatro")
         let idclientecrm = $(this).data("cinco")
 
        peticiones.redirectTabs($('#mapaFuentesTab')) 

        $("#mapa_content_fuentes").html(`<div id="carga_person">
                                                    <div class="loader">Loading...</div>
                                                </div>`);
        
         $.ajax({
            url:"/administrador/monitor-fuentes/mapa-fuentes",
            method:"get",
            data:{
                n:nodo,
                t:troba,
                id:idclientecrm
            },
            dataType: "json", 
        })
        .done(function(data){
           // console.log("El resultado es:",data)  
            let mapa = JSON.parse(data.response.html)
             $("#mapa_content_fuentes").html(mapa)
        })
        .fail(function(jqXHR, textStatus){
             //console.log( "Error: " + jqXHR, textStatus); 
             // $("#mapa_content_fuentes").html(jqXHR.responseText)
             //   return false
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

            $("#mapa_content_fuentes").html(erroresPeticion)

            return false
 
        }); 
 
    })

    //Edificios
    $("body").on("click",".show_edificio_details", function(){ 

        //console.log("debe mostrar los edificios...")
        peticiones.redirectTabs($('#DetalleEdificiosTab'))

        let des_dtt = $(this).data("desdtt")
        let des_via = $(this).data("nomvia")
        let des_puer = $(this).data("numpuer")
 
       
          $('#edificios_content_multiconsulta').DataTable({
                "destroy": true,
                "processing": true, 
                "serverSide": true,
                "dom":'<"row mx-0"'
                            +'<"col-12 col-sm-6"l><"col-12 col-sm-6"f>>'
                        +'<"row position-relative"'
                            +'<"col-sm-12 px-0 table-responsive table-text-xs tableFixHead"t>'
                            +'r>'
                        +'<"row"'
                            +'<"col-12 col-sm-5"i><"col-12 col-sm-7"p>>',
                "ajax": {  
                    'url':'/administrador/monitor-fuentes/mapa-fuentes/edificios/detalle',
                    "type": "GET", 
                    "data": function ( d ) {
                        
                            d.nom_via = des_via;
                            d.desdtt = des_dtt;
                            d.num_puer = des_puer;
                    },
                    'error': function(jqXHR, textStatus)
                        { 
                            
                          console.log( "Error: " ,jqXHR, textStatus); 

                             $("#body-errors-modal").html(jqXHR.responseText)
                             $('#errorsModal').modal('show') 
                                if(jqXHR.status){
                                    if (jqXHR.status == 401) {
                                        location.reload();
                                        return false
                                    } 
                                
                                 // peticiones.redirectTabs($('#mapaFuentesTab')) 
                                    return false
                                } 
                               // peticiones.redirectTabs($('#mapaFuentesTab')) 
                            
                                return false 
                        }
                }, 
                "columns": [
                    {data: 'macstate'},
                    //{data: 'RxPwrdBmv'},
                    {data: 'USPwr'},
                    {data: 'USMER_SNR'},
                    {data: 'DSPwr'},
                    {data: 'DSMER_SNR'},
                    {data: 'IDCLIENTECRM'},
                    {data: 'nameclient'},
                    {data: 'direccion'},
                    {data: 'amplificador'},
                    {data: 'tap'},
                    {data: 'telf1'},
                    {data: 'MACADDRESS'},
                    {data: 'SERVICEPACKAGE'},
                    
                ],
                'columnDefs': [
                    {
                       'targets': '_all',
                       'createdCell':  function (td, cellData, rowData, row, col) {
                             // $(td).attr('id', 'cell-' + cellData); 
                             
                             $(td).css({"background":`${rowData.estilosText.background}`,"color":`${rowData.estilosText.color}`});

                             /*if(col == 1){//RxPwrdBmv 
                                $(td).css({"color":`${rowData.coloresNivelesRX.estiloColorRxPwrdBmv}`,"background":`${rowData.coloresNivelesRX.estiloBackRxPwrdBmv}`});
                             }*/
                             if(col == 1){//USPwr
                                $(td).css({"background":`${rowData.coloresNivelesRuido.UpPxBackground}`,"color":`${rowData.coloresNivelesRuido.UpPxColor}`});
                             }
                             if(col == 2){//USMER_SNR
                                $(td).css({"background":`${rowData.coloresNivelesRuido.UpSnrBackground}`,"color":`${rowData.coloresNivelesRuido.UpSnrColor}`});
                             }
                             if(col == 3){//DSPwr
                                $(td).css({"background":`${rowData.coloresNivelesRuido.DownPxBackground}`,"color":`${rowData.coloresNivelesRuido.DownPxColor}`});
                             }
                             if(col == 4){//DSMER_SNR
                                $(td).css({"background":`${rowData.coloresNivelesRuido.DownSnrBackground}`,"color":`${rowData.coloresNivelesRuido.DownSnrColor}`});
                             }
                            
                             // console.log("los cells: ",td, cellData, rowData, row, col)
                       }
                    }   
                ],
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


         $("#edificios_content_multiconsulta").parent().addClass("table-responsive tableFixHead") 
          
         let tablaHead = $('.tableFixHead').find('thead th')
         $('.tableFixHead').on('scroll', function() {
           // console.log("ejecutando"+this.scrollTop); 
            tablaHead.css('transform', 'translateY('+ this.scrollTop +'px)'); 
        }); 
         
        
    })


})