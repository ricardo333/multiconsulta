const diagnosticoMasivo = {}

diagnosticoMasivo.lista = function lista(tabla,url,parametros)
{

            tabla.DataTable({
                "destroy": true,
                "processing": true, 
                "serverSide": true,
                "dom":'<"row mx-0"'
                        +'<"col-12 col-sm-4"l><"col-12 col-sm-4"B><"col-12 col-sm-4"f>>'
                    +'<"row"'
                        +'<"col-sm-12 px-0 table-responsive table-text-xs tableFixHead"t>>'
                    +'<"row"'
                        +'<"col-12 col-sm-5"i><"col-12 col-sm-7"p>>'
                    +'r',
                //"dom":"Bfrtip",
                "buttons":[ 
                    {
                        tag:'button',
                        className: 'btn btn-sm btn-primary shadow-sm',
                        extend:    'csvHtml5',
                        text:      '<i class="fa fa-file-text-o"></i>',
                        titleAttr: 'CSV'
                    }
                ],
                "ajax": {  
                    'url':url,
                    "type": "GET", 
                    "data": function ( d ) { 
                            d.n = parametros.n;
                            d.t = parametros.t; 
                    },
                    'dataSrc': function(json){
                            //console.log("Termino la carga sinerror.. :",json)
                    
                                //return json
                                let result = json.data
                                
                               /* let inicioCount = parseInt(json.input.start)
                                //let endCount = parseInt(parseInt(json.input.start)+parseInt(json.input.length))

                                for (let index = 0; index < result.length ; index++) {
                                    inicioCount ++
                                    result[index].item = inicioCount 
                                }*/

                                // console.log("La data procesada final... es: ",result)

                                return result  
                            
                        
                    },
                    'error': function(jqXHR, textStatus)
                        { 
                            
                    // console.log( "Error: " ,jqXHR, textStatus); 
                        //$("#resultDiagnosticoMasivo").html(jqXHR.responseText);
                        $("#body-errors-modal").html(jqXHR.responseText)
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
                "columns": [
                    {data:'items'},
                    {data:'cmts'},
                    {data:'interface'},
                    {data:'macstate'},
                    {data:'USPwr'},
                    {data:'USMER_SNR'},
                    {data:'DSPwr'},
                    {data:'DSMER_SNR'},
                    {data:'averia'},
                    {data:'codctr'},
                    {data:'codedo'},
                    {data:'IDCLIENTECRM'},
                    {data:'nameclient'},
                    {data:'direccion'},
                    {data:'nodohfc'},
                    {data:'trobahfc'},
                    {data:'nodocms'},
                    {data:'trobacms'},
                    {data:'amplificador'},
                    {data:'tap'},
                    {data:'telf1'},
                    {data:'telf2'},
                    {data:'movil1'},
                    {data:'mac2'},
                    {data:'SERVICEPACKAGE'},
                    {data:'FECHAACTIVACION'},
                    {data:'estado_modem'},
                    {data:'SCOPESGROUP'},
                    {data:'estado'},
                    {data:'numcoo_x'},
                    {data:'numcoo_y'},
                ],
                'columnDefs': [
                    {
                        'targets': '_all',
                        'createdCell':  function (td, cellData, rowData, row, col) {
                                // $(td).attr('id', 'cell-' + cellData); 
                                
                                $(td).css({"background":`${rowData.estadoBackground}`,"color":`${rowData.estadoColor}`});

                                /*if(col == 1){//RxPwrdBmv 
                                    $(td).css({"color":`${rowData.coloresNivelesRX.estiloColorRxPwrdBmv}`,"background":`${rowData.coloresNivelesRX.estiloBackRxPwrdBmv}`});
                                }*/
                                if(col == 4){//USPwr
                                    $(td).css({"background":`${rowData.coloresNivelesRuido.DownPxBackground}`,"color":`${rowData.coloresNivelesRuido.DownPxColor}`});
                                }
                                if(col == 5){//USMER_SNR
                                    $(td).css({"background":`${rowData.coloresNivelesRuido.UpSnrBackground}`,"color":`${rowData.coloresNivelesRuido.UpSnrColor}`});
                                }
                                if(col == 6){//DSPwr
                                    $(td).css({"background":`${rowData.coloresNivelesRuido.DownPxBackground}`,"color":`${rowData.coloresNivelesRuido.DownPxColor}`});
                                }
                                if(col == 7){//DSMER_SNR
                                    $(td).css({"background":`${rowData.coloresNivelesRuido.DownSnrBackground}`,"color":`${rowData.coloresNivelesRuido.DownSnrColor}`});
                                }
                                if(col == 8 || col == 9 || col == 10){//AVERIAS
                                    if (rowData.averia != "" && rowData.averia != null) { 
                                    // console.log("los valores averia son:",rowData.averia)
                                        $(td).css({"background":`${rowData.averiasBackground}`,"color":`${rowData.averiasColor}`});
                                    }
                                }
                                
                                // console.log("los cells: ",td, cellData, rowData, row, col)
                        }
                    },
                    {
                        "targets": [ 0, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 14, 15, 16, 17, 19,20,21,22,23,24,25,26,27,28,29,30 ],
                        //"orderable" : false,
                        "searchable": false, 
                    }    
                ],
                "pageLength": 25,
                "language": {
                    "info": "_TOTAL_ registros",
                    "search": "Buscar",
                    "paginate": {
                        "next": "Siguiente",
                        "previous": "Anterior",
                    },
                    "lengthMenu": 'Mostrar <select >'+
                                '<option value="25">25</option>'+
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
                }/*,
                "initComplete": function () {
                    console.log("ya termino lac arga....",this)
                    
                }*/
            });



        // $("#resultDiagnosticoMasivo").parent().addClass("table-responsive tableFixHead") 
        tabla.css({"font-size":"11px"})

        let tablaHead = $('.tableFixHead').find('thead th')
        $('.tableFixHead').on('scroll', function() {
            // console.log("ejecutando"+this.scrollTop); 
            tablaHead.css('transform', 'translateY('+ this.scrollTop +'px)'); 
        }); 

}

export default diagnosticoMasivo