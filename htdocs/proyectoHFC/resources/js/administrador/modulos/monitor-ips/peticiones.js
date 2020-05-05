import errors from  "@/globalResources/errors.js"

const peticiones = {}

peticiones.redirectTabs = function redirectTabs(identificador) {
        $('#tabsMonitorIPS > .tab-pane').removeClass('show');
        $('#tabsMonitorIPS > .tab-pane').removeClass('active');
        identificador.tab('show')
}

peticiones.resetInterval = function resetInterval(){
        if (INTERVAL_LOAD != null) {
                clearInterval(INTERVAL_LOAD)
                //console.log("Se limpio el interval y se debe iniciar nuevamente...")
                INTERVAL_LOAD = setInterval(() => {
                        if (ESTA_ACTIVO_REFRESH) {
                            if ($( ".MonitorIPS" ).hasClass( "active" )) {
                                peticiones.cargandoPeticionPrincipal()
                            } 
                        }
                
                }, 180000);
        }
}

peticiones.armandoColumnasUno = function armandoColumnasUno()
{
        let columnasContent =  [ {data: 'cmts'}  ]
        columnasContent.push({data: 'scopesgroup'})
        columnasContent.push({data: 'total'})
        columnasContent.push({data: 'used'})
        columnasContent.push({data: 'available'})
        columnasContent.push({data: 'porc'})
        return columnasContent
}

function procesandoResultadoLista(result)
{   
    
    for (let i = 0; i < result.length ; i++) {
        result[i].porc = Math.round(`${result[i].porc}`* 100) / 100 

        //como se ve en la bd: "D-CMTS La Victoria</br><font size=1 color = black>LA VICTORIA1</font>";
        //como viene: 
           //D-CMTS La Victoria&lt;/br&gt;&lt;font size=1 color = black&gt;LA VICTORIA1&lt;/font&gt;
           //LIM_RETCMTS4_CBR8&lt;/br&gt;&lt;font size=1 color = black&gt;&lt;/br&gt;&lt;font size=1  color = black&gt;Nuevo&lt;/font&gt;

        var cmts_texto = result[i].cmts.split('&lt;/br')
        var cmts_texto_titulo = cmts_texto[1].replace(/[^\w\s]/g, '')
        var cmts_texto_titulo = cmts_texto_titulo.replace(/gtltfont size1 color  blackgt/gi, '')
        var cmts_texto_titulo = cmts_texto_titulo.replace(/ltfontgt/gi, '')
        if(cmts_texto[2]){
                var cmts_texto_sub_titulo = cmts_texto[2].replace(/[^\w\s]/g, '')
                var cmts_texto_sub_titulo = cmts_texto_sub_titulo.replace(/gtltfont size1  color  blackgt/gi, '')
                var cmts_texto_sub_titulo = cmts_texto_sub_titulo.replace(/ltfontgt/gi, '')
        }else{
                cmts_texto_sub_titulo = ''
        }
        //console.log( cmts_texto )  
        //console.log( cmts_texto[0] +'---'+cmts_texto_titulo+'---'+cmts_texto_sub_titulo ) 

        result[i].cmts = `<span class="font-weight-bold" style="color: ${result[i].color}">${cmts_texto[0]}</span><br><span class="font-weight-bold" style="color:black">${cmts_texto_titulo} ${cmts_texto_sub_titulo}</span>`
        result[i].scopesgroup = `<span class="font-weight-bold" style="color: ${result[i].color}">${result[i].scopesgroup}</span>`
        result[i].total = `<span class="font-weight-bold" style="color: ${result[i].color}">${result[i].total}</span>`
        result[i].used = `<span class="font-weight-bold" style="color: ${result[i].color}">${result[i].used}</span>`
        result[i].available = `<span class="font-weight-bold" style="color: ${result[i].color}">${result[i].available}</span>`
        result[i].porc = `<span class="font-weight-bold" style="color: ${result[i].color}">${result[i].porc} %</span>`
    }
    
    return result
}

peticiones.getDataRequiredFilterConteoModems = function getDataRequiredFilterConteoModems(tipo)
{
        let data = {}
        data.parametros = {}
        data.parametros.nodo = "" 
        data.parametros.tipoCaidas = tipo
  
        data.redirect = $('#MonitorIPSTab')
        data.tabla = $("#resultMonitorIPS")
        data.columnasCaidas = peticiones.armandoColumnasUno()
  
        return data
}

peticiones.cargaMonitorIPSLista = function cargaMonitorIPSLista(COLUMNS_CAIDAS,tabla){
     
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
                "ajax": {  
                        'url':'/administrador/monitor-ips/lista',
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
                                if (REFRESH_PERMISO) {
                                        ESTA_ACTIVO_REFRESH = true
                                        peticiones.resetInterval()
                                }
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
                                //console.log( 'entro: '+`${rowData.fondo}`+`${rowData.letra}`)
                                //dd(rowData.backgroundInit_r1);
                                $(td).css({"background":`${rowData.fondo}`,"color":`${rowData.letra}`});
                                $(td).addClass("text-center")
                                
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
                "initComplete": function(){
                        //console.log("Termino la carga completa estoy en initComplete peticiones.js line 147")
                        //$("#display_filter_special").prop("disabled", false);
                        if (REFRESH_PERMISO) {
                                //console.log("entre a REFRESH_PERMISO")
                                ESTA_ACTIVO_REFRESH = true
                                peticiones.resetInterval()
                                
                        }
                     
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

peticiones.cargandoPeticionPrincipal = function cargandoPeticionPrincipal()
{
        let valorFiltroEspecial = $("#input-MonitorIPSTab").val();
        //console.log(valorFiltroEspecial);
        let params = peticiones.getDataRequiredFilterConteoModems(valorFiltroEspecial);
        //peticiones.redirectTabs(params.redirect)
        //$(".content_filter_basic").css({"display":"none"})
        peticiones.cargaMonitorIPSLista(params.columnasCaidas,params.tabla)
}

export default peticiones
