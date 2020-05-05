import errors from  "@/globalResources/errors.js"

const peticiones = {}

peticiones.armandoColumnasUno = function armandoColumnasUno()
{
        let columnasContent =  [ {data: 'id'}  ]
        columnasContent.push({data: 'identidad'})
        columnasContent.push({data: 'tipo'})
        columnasContent.push({data: 'cmts'})
        columnasContent.push({data: 'init_r1'})
        columnasContent.push({data: 'init_r2'})
        columnasContent.push({data: 'init_rc'})
        columnasContent.push({data: 'init_r'})
        columnasContent.push({data: 'sinippublica'})
        columnasContent.push({data: 'init_d'})
        columnasContent.push({data: 'init_i'})
        columnasContent.push({data: 'init_o'})
        columnasContent.push({data: 'init_io'})
        columnasContent.push({data: 'init_t'})
        columnasContent.push({data: 'init_dr'})
        columnasContent.push({data: 'cc_pending'})
        columnasContent.push({data: 'reject'})
        columnasContent.push({data: 'p_online'})
        columnasContent.push({data: 'w_expire_pt'})
        columnasContent.push({data: 'online_pt'})
        columnasContent.push({data: 'w_online_pt'})
        columnasContent.push({data: 'online_d'})
        columnasContent.push({data: 'online'})
        columnasContent.push({data: 'offline'})
        columnasContent.push({data: 'total'})
        return columnasContent
}

function procesandoResultadoLista(result)
{

        for (let i = 0; i < result.length ; i++) {
                
                result[i].sinippublica = `<span class="font-weight-bold">${result[i].sinippublica}</span>`
                result[i].init_d = `<span class="font-weight-bold">${result[i].init_d}</span>`
                result[i].init_i = `<span class="font-weight-bold">${result[i].init_i}</span>`
                result[i].init_o = `<span class="font-weight-bold">${result[i].init_o}</span>`
                result[i].init_io = `<span class="font-weight-bold">${result[i].init_io}</span>`
                result[i].init_t = `<span class="font-weight-bold">${result[i].init_t}</span>`
                result[i].init_dr = `<span class="font-weight-bold">${result[i].init_dr}</span>`

        }
        
        return result
}

peticiones.cargaEstadosModemsLista = function cargaEstadosModemsLista(COLUMNS_CAIDAS,tabla){
     
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
                        +'<"col-12 col-sm-5"i><"col-12 col-sm-7"p>>'
                        +'r',
                "buttons": false,
                //"bPaginate": false, //Si agrego se elimina la paginación
                "ajax": {  
                        'url':'/administrador/estados-modems/lista',
                        "type": "GET", 
                        "data": function ( d ) {
                               // d.filtroJefatura = parametersDataEstadosModems.jefatura;
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
                                //console.log( "Entre a Error: " ,jqXHR, textStatus, errorThrown); 
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
                           //"targets": [ 0 ],             //Le indico que la columna 0 no sea visible con false
                           //"visible": false,
                           'targets': '_all',
                           'createdCell':  function (td, cellData, rowData, row, col) { 
                                //console.log( 'entro: '+`${rowData.fondo}`+`${rowData.letra}`)
                                //dd(rowData.backgroundInit_r1);
                                $(td).css({"background":`${rowData.fondo}`,"color":`${rowData.letra}`});
                                $(td).addClass("text-center")
                              
                                if(col == 1 || col == 2 || col == 3){//porcCaido
                                   $(td).css({"background":`${rowData.backgroundPorcCaido}`,"color":`${rowData.colorPorcCaido}`});
                                }
                                if(col == 4){//init_r1
                                   $(td).css({"background":`${rowData.backgroundInit_r1}`,"color":`${rowData.colorInit_r1}`});
                                }
                                if(col == 5){//init_r2
                                   $(td).css({"background":`${rowData.backgroundInit_r2}`,"color":`${rowData.colorInit_r2}`});
                                }
                                if(col == 6){//init_rc
                                   $(td).css({"background":`${rowData.backgroundInit_rc}`,"color":`${rowData.colorInit_rc}`});
                                }
                                if(col == 7){//init_r
                                   $(td).css({"background":`${rowData.backgroundInit_r}`,"color":`${rowData.colorInit_r}`});
                                }
                                if(col == 8){//sinippublica
                                   $(td).css({"background":`${rowData.backgroundSinippublica}`,"color":`${rowData.colorSinippublica}`});
                                }
                                if(col == 9){//init_d
                                   $(td).css({"background":`${rowData.backgroundInit_d}`,"color":`${rowData.colorInit_d}`});
                                }
                                if(col == 10){//init_i
                                   $(td).css({"background":`${rowData.backgroundInit_i}`,"color":`${rowData.colorInit_i}`});
                                }
                                if(col == 11){//init_o
                                   $(td).css({"background":`${rowData.backgroundInit_o}`,"color":`${rowData.colorInit_o}`});
                                }
                                if(col == 12){//init_io
                                   $(td).css({"background":`${rowData.backgroundInit_io}`,"color":`${rowData.colorInit_io}`});
                                }
                                if(col == 13){//init_t
                                   $(td).css({"background":`${rowData.backgroundInit_t}`,"color":`${rowData.colorInit_t}`});
                                }
                                if(col == 14){//init_dr
                                   $(td).css({"background":`${rowData.backgroundInit_dr}`,"color":`${rowData.colorInit_dr}`});
                                }
                                if(col == 17){//p_online
                                   $(td).css({"background":`${rowData.backgroundP_online}`,"color":`${rowData.colorP_online}`});
                                }
                                
                                 //console.log("los cells: ",td, cellData, rowData, row, col)
                                 let count = 0
                                 if (DIAGNOSTICOM_PERMISO)   count ++ 
                                 if (VER_CRITICOS_PERMISO)   count ++ 

                                 if (col == count+6) {
                                        if (rowData.estado != "LEVANTO") {
                                                if (parseFloat(rowData.divisionOffline) > 0.3) { 
                                                        $(td).css({"background":`${rowData.letra}`,"color":`${rowData.fondo}`});  
                                                } 
                                        }
                                        
                                 }

                           }
                        },
                        {
                             
                            "targets": '_all',
                            "orderable" : false,  //Quito el ordenamiento de la cabecera cuando se hace click
                            "searchable": false,
                                
                        } 
                ] ,
                /*
                "footerCallback": function ( row, data, start, end, display ) {
                        
                        var api = this.api(), data;
             
                        // converting to interger to find total
                        var intVal = function ( i ) {
                            return typeof i === 'string' ?
                                i.replace(/[\$,]/g, '')*1 :
                                typeof i === 'number' ?
                                    i : 0;
                        };
             
                        // computing column Total the complete result 
                        var init_r1_Total = api
                            .column(4)
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );

                        var init_r2_Total = api
                            .column(5)
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );

                        var init_rc_Total = api
                            .column(6)
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );

                        var init_r_Total = api
                            .column(7)
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );

                        var sinippublica_Total = api
                            .column(8)
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );

                        var init_d_Total = api
                            .column(9)
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );

                        var init_i_Total = api
                            .column(10)
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );

                        var init_o_Total = api
                            .column(11)
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );

                        var init_io_Total = api
                            .column(12)
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );

                        var init_t_Total = api
                            .column(13)
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );

                        var init_dr_Total = api
                            .column(14)
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );

                        var cc_pending_Total = api
                            .column(15)
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );

                        var reject_Total = api
                            .column(16)
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );

                        var p_online_Total = api
                            .column(17)
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );

                        var w_expire_Total = api
                            .column(18)
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );

                        var online_pt_Total = api
                            .column(19)
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );

                        var w_online_Total = api
                            .column(20)
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );

                        var online_d_Total = api
                            .column(21)
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );

                        var online_Total = api
                            .column(22)
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );

                        var offline_Total = api
                            .column(23)
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );

                        var Total = api
                            .column(24)
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );  
                                            
                        // Update footer by showing the total with the reference of the column index

                        $( api.column(0).footer() ).html('Total');
                        $( api.column(4).footer() ).html(init_r1_Total);
                        $( api.column(5).footer() ).html(init_r2_Total);
                        $( api.column(6).footer() ).html(init_rc_Total);
                        $( api.column(7).footer() ).html(init_r_Total);
                        $( api.column(8).footer() ).html(sinippublica_Total);
                        $( api.column(9).footer() ).html(init_d_Total);
                        $( api.column(10).footer() ).html(init_i_Total);
                        $( api.column(11).footer() ).html(init_o_Total);
                        $( api.column(12).footer() ).html(init_io_Total);
                        $( api.column(13).footer() ).html(init_t_Total);
                        $( api.column(14).footer() ).html(init_dr_Total);
                        $( api.column(15).footer() ).html(cc_pending_Total);
                        $( api.column(16).footer() ).html(reject_Total);
                        $( api.column(17).footer() ).html(p_online_Total);
                        $( api.column(18).footer() ).html(w_expire_Total);
                        $( api.column(19).footer() ).html(online_pt_Total);
                        $( api.column(20).footer() ).html(w_online_Total);
                        $( api.column(21).footer() ).html(online_d_Total);
                        $( api.column(22).footer() ).html(online_Total);
                        $( api.column(23).footer() ).html(offline_Total);
                        $( api.column(24).footer() ).html(Total);
                },
                */
                "initComplete": function(){
                        //console.log("Termino la carga completa estoy en initComplete")
                        //$("#display_filter_special").prop("disabled", false);
                     
                },
                "pageLength": 100,
                "language": {
                            "info": "_TOTAL_ registros",
                            "search": "Buscar",
                            "paginate": {
                                    "next": "Siguiente",
                                    "previous": "Anterior",
                            },
                            "lengthMenu": 'Mostrar <select >'+
                            '<option value="100">100</option>'+
                            '<option value="300">300</option>'+
                            '<option value="500">500</option>'+
                            '<option value="700">700</option>'+
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
            
            tabla.parent().addClass("table-responsive tableFixHead") 
            // $("#filtroContentHFC").removeClass("d-none")

            let tablaHead = $('.tableFixHead').find('thead th')
            $('.tableFixHead').on('scroll', function() {
            // console.log("ejecutando"+this.scrollTop); 
            tablaHead.css('transform', 'translateY('+ this.scrollTop +'px)'); 
            });

}

export default peticiones
