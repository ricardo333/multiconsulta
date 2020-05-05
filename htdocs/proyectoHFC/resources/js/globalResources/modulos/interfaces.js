import errors from  "@/globalResources/errors.js"

const interfaces = {}

interfaces.historicoRuido = function historicoRuido(inter,route)
{
    $("#resultHistoricoRuidoInterfaz").DataTable({
        "destroy": true,
        "processing": true, 
        "serverSide": true,
        "dom":'<"row mx-0"'
                +'<"col-12 col-sm-6"l><"col-12 col-sm-6"f>>'
            +'<"row"'
                +'<"col-sm-12 px-0 table-responsive table-text-xs tableFixHead"t>>'
            +'<"row"'
                +'<"col-12 col-sm-5"i><"col-12 col-sm-7"p>>'
            +'r',
        "ajax": {  
            'url':route,
            "type": "GET", 
            "data": function ( d ) { 
                    d.interface = inter; 
            },
            'dataSrc': function(json){
                     console.log("Termino la carga interfacese... :",json)
            
                        //return json
                        let result = json.data
                        let parametrosRF = json.niveles
                        let coloresRF = JSON.parse(json.niveles.colores).colores

                        console.log("Los colores RF son: ",coloresRF)
                          
                        
                        for (let index = 0; index < result.length ; index++) {

                             let contador = index + 1
                             result[index].item = contador

                            let backgrounGeneral = ""
                            let colorGeneral = "" 
                            if ( result[index].powerup_prom < parametrosRF.power_up_min  || result[index].powerup_prom > parametrosRF.power_up_max || 
                                result[index].powerds_prom < parametrosRF.power_down_min || result[index].powerds_prom > parametrosRF.power_down_max || 
                                result[index].snr_avg < parametrosRF.snr_up_min )
                            {
                                backgrounGeneral= coloresRF[0].background
                                colorGeneral=coloresRF[0].color
                            }else {
                                backgrounGeneral=coloresRF[1].background
                                colorGeneral=coloresRF[1].color
                            }  
                            result[index].backgrounGeneral = backgrounGeneral
                            result[index].colorGeneral = colorGeneral

                            let backgrounPowerUpProm = ""
                            let colorPowerUpProm = "" 
                            if (result[index].powerup_prom < parametrosRF.power_up_min || result[index].powerup_prom > parametrosRF.power_up_max )
                            {
                                backgrounPowerUpProm = coloresRF[2].background 
                                colorPowerUpProm = coloresRF[2].color 
                            } else {
                                backgrounPowerUpProm =  coloresRF[0].background 
                                colorPowerUpProm =  coloresRF[0].color 
                            } 
                            result[index].backgrounPowerUpProm = backgrounPowerUpProm
                            result[index].colorPowerUpProm = colorPowerUpProm

                            let backgroundPowerDwProm = ""
                            let ColorPowerDwProm = ""
                            let backgroundSnrAvg = ""
                            let colorSnrAvg = ""
                            let backgroundFechaHora = ""
                            let colorFechaHora = ""
                            if (result[index].powerup_prom <  parametrosRF.power_up_min || result[index].powerup_prom > parametrosRF.power_up_max || 
                                result[index].powerds_prom < parametrosRF.power_down_min || result[index].powerds_prom > parametrosRF.power_down_max || 
                                result[index].snr_avg < parametrosRF.snr_up_min || result[index].snr_down < parametrosRF.snr_down_min )
                            {
                                backgroundPowerDwProm =  coloresRF[0].background
                                ColorPowerDwProm = coloresRF[0].color
                                backgroundSnrAvg =  coloresRF[0].background
                                colorSnrAvg = coloresRF[0].color
                                backgroundFechaHora =  coloresRF[0].background
                                colorFechaHora = coloresRF[0].color
                            } else {
                                backgroundPowerDwProm = coloresRF[1].background
                                ColorPowerDwProm = coloresRF[1].color
                                backgroundSnrAvg = coloresRF[1].background
                                colorSnrAvg = coloresRF[1].color
                                backgroundFechaHora = coloresRF[1].background
                                colorFechaHora = coloresRF[1].color
                            }
                            if (result[index].powerds_prom < parametrosRF.power_down_min || result[index].powerds_prom > parametrosRF.power_down_max )
                            {
                                backgroundPowerDwProm = coloresRF[2].background
                                ColorPowerDwProm = coloresRF[2].color
                            } else {
                                backgroundPowerDwProm =  coloresRF[0].background
                                ColorPowerDwProm = coloresRF[0].color
                            }
                            result[index].backgroundPowerDwProm = backgroundPowerDwProm
                            result[index].ColorPowerDwProm = ColorPowerDwProm
 
                            if (result[index].snr_avg < parametrosRF.snr_down_min)
                            {
                                backgroundSnrAvg = coloresRF[2].background
                                colorSnrAvg = coloresRF[2].color
                            } else {
                                backgroundSnrAvg =  coloresRF[0].background
                                colorSnrAvg = coloresRF[0].color
                            }
                            result[index].backgroundSnrAvg = backgroundSnrAvg
                            result[index].colorSnrAvg = colorSnrAvg

                            let backgroundSnrDown = ""
                            let colorSnrDown = ""

                            if (parametrosRF.snr_down < parametrosRF.snr_down_min && parametrosRF.snr_down > parametrosRF.snr_down_max)
                            {
                                backgroundSnrDown = coloresRF[2].background 
                                colorSnrDown = coloresRF[2].color
                            } else {
                                backgroundSnrDown =  coloresRF[0].background 
                                colorSnrDown = coloresRF[0].color
                            }
                            result[index].backgroundSnrDown = backgroundSnrDown
                            result[index].colorSnrDown = colorSnrDown
                            result[index].backgroundFechaHora = backgroundFechaHora
                            result[index].colorFechaHora = colorFechaHora
                         
                        }

                        // console.log("La data procesada final... es: ",result)

                        return result  
                    
                
            },
            'error': function(jqXHR, textStatus, errorThrown)
                { 
                    
                    // console.log( "Error: " ,jqXHR, textStatus); 
                        
                    //$("#body-errors-modal").html(jqXHR.responseText)
                 
                    let erroresPeticion =""
                                
                    if(jqXHR.status){
                            let mensaje = errors.codigos(jqXHR.status)
                            erroresPeticion = mensaje
                    }
                    if(jqXHR.responseJSON){
                            if(jqXHR.responseJSON.mensaje){
                                    let erroresMensaje = jqXHR.responseJSON.mensaje  //captura objeto
                                    let mensaje = errors.mensajeErrorJson(erroresMensaje)
                                    erroresPeticion += "<br> "+mensaje 
                            } 
                    }
                    erroresPeticion = (erroresPeticion.trim() == 0) ? "hubo un error en el servicio, intente nuevamente." : erroresPeticion
            
                    $("#body-errors-modal").html(`<div class="text-danger">${erroresPeticion}</div>`)
                    $('#errorsModal').modal('show')
                    return false
                }
        },
        "columns": [
            {data:'cmts'},
            {data:'Interface'},
            {data:'description'},
            {data:'powerup_prom'},
            {data:'powerds_prom'},
            {data:'snr_avg'},
            {data:'snr_down'},
            {data:'fecha_hora'}
        ],
        'columnDefs': [ 
                {
                    'targets': '_all',
                    'createdCell':  function (td, cellData, rowData, row, col) { 
                       
                      $(td).css({"background":`${rowData.backgrounGeneral}`,"color":`${rowData.colorGeneral}`}); 
                     
                       // console.log("los cells: ",td, cellData, rowData, row, col)
 
                        if (col == 3) { //powerup_prom 
                            $(td).css({"background":`${rowData.backgrounPowerUpProm}`,"color":`${rowData.colorPowerUpProm}`});      
                        }
                        if (col == 4) { //powerds_prom
                                $(td).css({"background":`${rowData.backgroundPowerDwProm}`,"color":`${rowData.ColorPowerDwProm}`});      
                        }
                        if (col == 5) { //snr_avg
                                $(td).css({"background":`${rowData.backgroundSnrAvg}`,"color":`${rowData.colorSnrAvg}`});      
                        }
                        if (col == 6) { //snr_down
                                $(td).css({"background":`${rowData.backgroundSnrDown}`,"color":`${rowData.colorSnrDown}`});      
                        } 
                        if (col == 7) { //fecha_hora
                                $(td).css({"background":`${rowData.backgroundFechaHora}`,"color":`${rowData.colorFechaHora}`});      
                        } 

                    
                    }
                } ,
                {
                    
                    "targets": '_all',
                    "orderable" : false,
                    "searchable": false,
                        
                } 
        ] ,
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
    $("#resultHistoricoRuidoInterfaz").css({"font-size":"11px"})

    let tablaHead = $('.tableFixHead').find('thead th')
    $('.tableFixHead').on('scroll', function() {
        // console.log("ejecutando"+this.scrollTop); 
        tablaHead.css('transform', 'translateY('+ this.scrollTop +'px)'); 
    }); 
}

export default interfaces