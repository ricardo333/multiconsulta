import errors from  "@/globalResources/errors.js"
  
const peticiones = {}

peticiones.redirectTabs = function redirectTabs(identificador) {
    $('#tabsAveriasCoeContent > .tab-pane').removeClass('show');
    $('#tabsAveriasCoeContent > .tab-pane').removeClass('active');
    identificador.tab('show')  
}

peticiones.cargandoPeticionPrincipal = function   cargandoPeticionPrincipal()
{
  
    let jefatura =  $("#listaJefaturaCOEFilter").val()
    let estado =    $("#listaEstadosGestionFilter").val()
    let troba =     $("#listaTrobasFilter").val()

    let columnasAveriasCoe = peticiones.columnasCoe()
    
    let filtros = {
        jefatura,
        estado,
        troba
    }

    peticiones.listaMonitorFuentes(columnasAveriasCoe,filtros)
     
}

peticiones.columnasCoe = function columnasCoe()
{ 
    let columnasAveriasCoe = [ 
        {data: null, render: function(data,type,row){

                let DM = ``
                let SCOPEGROUP = ``
                let REFRESH_REAPROV = ``
                let VER_CM = ``
                let AGENDA_BTN = ``
                let CHECK_GESTION_MASIVA = ``
                let btn_total = ``
                
                if (DIAGNOSTICOM_PERMISO) {
                    DM = `<a href="javascript:void(0)" class="verDiagnosticoMasivo" data-uno="${row.nodohfc}" data-dos="${row.trobahfc}">
                        <img src="/images/icons/dm_oe.png" class="icon_coe_averias" width="20" height="15">
                    </a>`
                }
                
                if (CAMBIARSCOPEGROUP_PERMISO) {
                    if (row.scopesgroup == "CPE") {
                        SCOPEGROUP = ` <a href="javascript:void(0)" class=" scopesGroupCM" data-uno="${row.macaddress}"
                                        data-dos="${row.scopesgroup}">
                                        <img src="/images/icons/multiconsulta/ccgnat1.png" alt="CGNAT" title="CGNAT">
                                    </a> `
                    }else {
                        SCOPEGROUP = `  <a href="javascript:void(0)" class=" scopesGroupCM" data-uno="${row.macaddress}"
                                                data-dos="${row.scopesgroup}">
                                            <img src="/images/icons/multiconsulta/ccpe1.png" alt="CPE" title="CPE">
                                        </a> `
                    }
                    
                }

                if (REFRESHIW_PERMISO) {
                    if (row.MACState == "online") {
                        REFRESH_REAPROV = ` <a href="javascript:void(0)"  class=" resetCmReaprovisionamiento" data-uno="${row.codcli}" 
                                                    data-dos="${row.idservicio}" data-tres="${row.idproducto}" data-cuatro="${row.idventa}">
                                                    <img src="/images/icons/reset_cm_coe.png" width="50" height="20" class="icon_coe_averias" alt="Reset Modem" title="Reset Modem"> 
                                                     
                                            </a> `
                    }  
                }

                if (VERCM_PERMISO) {
                    if (row.MACState == "online") {
                        if (row.Fabricante != null) {
                            if (row.Fabricante=="Askey" || row.Fabricante=="Ubee" ||  row.Fabricante.substring(0,3)=="Hit" ||  row.Fabricante.substring(0,9)=="CastleNet" ||  row.Fabricante.substring(0,5)=="SAGEM" ||  row.Fabricante.substring(0,6)=="Telefo") {
                                VER_CM = `<a href="javascript: void(0)" class="show_cablemodem detalle_cablemodem" 
                                            data-toggle="modal"  data-target="#show_cablemodem"
                                            data-cod="${row.codcli}" data-serv="${row.idservicio}" 
                                            data-prod="${row.idproducto}" data-vent="${row.idventa}" 
                                            data-ip="${row.IPAddress}" data-mac="${row.macaddress}" data-fb="${row.Fabricante}" 
                                            data-mo="${row.Modelo}" data-firm="${row.Version_firmware}">
                                            <img src="/images/icons/cm_icono_coe.png" class="icon_coe_averias" width="20" height="25">
                                        </a>`
                            }
                        }
                         
                    }  
                } 
                
                if (AGENDA_PERMISO) {
                    if(parseInt(row.msjtot) == 1){
                        AGENDA_BTN = `<a href="javascript: void(0)" class="btn btn-outline-primary btn-sm m-1 shadow-sm preAgendaMulti" 
                                        data-uno="${row.codcli}" data-dos="2" data-tres="${row.nodohfc}">
                                    Agenda <i class="icofont-calendar"></i>
                                </a> `
                    } 
                }

                if (GESTION_INDIV_PERMISO) {
                    CHECK_GESTION_MASIVA = `<input type="checkbox" class="btnGestionMasiva my-1  w-100 mx-auto d-none" name="btnGestionMasiva"
                                                value="${row.codcli}" 
                                                data-uno="${row.codcli}" 
                                                data-tres="${row.codreq}"
                                                data-cuatro="${row.nodohfc}"
                                                data-cinco="${row.trobahfc}"
                                                data-seis="${row.macaddress}"
                                                data-siete="${row.codsrv}"
                                            >`
                         
                    }
 
                btn_total = DM + SCOPEGROUP + REFRESH_REAPROV + VER_CM + AGENDA_BTN + CHECK_GESTION_MASIVA

                return btn_total
            }
        },
        {data: 'item'},
        {data: 'zonal'},//zonal
        {data: 'codreq'},//codreq
        {data: 'codcli'},//codcli
        {data: 'tip_ing'},//tip_ing
        {data: 'estadomdm'},//estadomdm
        {data: 'area'},//area
        {data: 'nodocms'},//nodocms
        {data: 'trobacms'},//trobacms
        {data: 'nodohfc'},//nodohfc
        {data: 'trobahfc'},//trobahfc
        {data: 'amplificador'},//amplificador
        {data: null, render: function(data,type,row){
            if (parseInt(row.callDmpeTotal) > 0) {
                return `<button class="llamadasDMPEUltimosDias" data-uno="${row.nodohfc}" data-dos="${row.trobahfc}">${row.callDmpeTotal}</button>`
            }else{
                return ""
            }

            }
        },//callDmpeTotal
        {data: null, render: function(data,type,row){
               if (parseInt(row.averiasTotal) > 0) {
                    return `<button class="averiasTotalUltimosDias" data-uno="${row.nodohfc}" data-dos="${row.trobahfc}">${row.averiasTotal}</button>`
               }else{
                   return ""
               }
            }
        },//averiasTotal
        {data: 'codctr'},//codctr
        {data: 'desnomctr'},//desnomctr
        {data: 'cmts'},//cmts
        {data: null, render: function(data,type,row){
            if (row.cmts  != null && row.interface != null) {
                    return `<button class="verhistoricoRuidoInterfaz" data-uno="${row.cmts + row.interface}">${row.interface}</button>`    
            }else{
                return ""
            }
        }//verhistoricoRuidoInterfaz
        },//Interface
        {data: 'scopesgroup'},//scopesgroup
        {data: 'masiva'},//masiva
        {data: 'macaddress'},//macaddress
        {data: 'fecreg'},//fecreg
        {data: 'codctr_final'},//codctr_final
        {data: 'area_final'},//area_final
        {data: 'ultimagestion'},//ultimagestion
        {data: 'TipoRuido'},//TipoRuido
        {data: 'observacionescms'},//observacionescms
        {data: 'motivotransferencia'},//motivotransferencia
        {data: 'telef1'},//telef1
        {data: 'telef2'},//telef2
        {data: 'telef3'},//telef3
        {data: 'MACState'},//MACState
        {data: 'USPwr'},//USPwr
        {data: 'USMER_SNR'},//USMER_SNR
        {data: 'DSPwr'},//DSPwr
        {data: 'DSMER_SNR'},//DSMER_SNR
        {data: 'codsrv'},//codsrv
        {data: 'EstadoDelCaso'}//EstadoDelCaso 
    ]

    
    if (GESTION_INDIV_PERMISO) {
        columnasAveriasCoe.push( 
            {data: null, render: function(data,type,row){
                return `<a href="javascript:void(0)" class="btn btn-sm gestionIndividualCOE" data-uno="${row.codreq}"><i class="icofont-list icofont-2x"></i></a>`
            }}//Edicion
        )
    } 
    
    return columnasAveriasCoe

}

peticiones.listaMonitorFuentes = function listaMonitorFuentes(columnasAveriasCoe,filtros)
{

        $("#resultAveriasCOEMasivas").DataTable({
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
            "buttons": [
                        {
                            text: 'FILTROS',
                            className: 'btn btn-sm btn-info shadow-sm',
                            titleAttr: 'FILTROS AVERIAS COE',
                            action: function ( e, dt, node, config ) {
                                $("#filtroContentCOE").slideToggle()
                            }
                        }
                    ],
            "ajax": {  
                    'url':`/administrador/averias-coe/lista`,
                    "type": "post", 
                    "data": function ( d ) {
                            d.jefatura = filtros.jefatura;
                            d.estado = filtros.estado; 
                            d.troba = filtros.troba; 
                    },
                    'dataSrc': function(json){
                            // console.log("Termino la carga sinerror.. :",json)
                    
                                //return json
                                let result = json.data
                                  
                                 let inicioCount = parseInt(json.input.start)
                                //let endCount = parseInt(parseInt(json.input.start)+parseInt(json.input.length))

                                for (let index = 0; index < result.length ; index++) {
                                    inicioCount ++
                                    result[index].item = inicioCount 
                                }

                                // console.log("La data procesada final... es: ",result)

                                return result  
                            
                        
                    },
                    'error': function(jqXHR, textStatus, errorThrown)
                    {  

                           /* if (REFRESH_PERMISO) {
                                    ESTA_ACTIVO_REFRESH = true
                                    peticiones.resetInterval()
                            }*/

                            console.log( "Error: " ,jqXHR, textStatus, errorThrown); 
                        
                            //alert("Se generó un error con la petición, Se intentará traer nuevamente.")
                        

                           //$("#body-errors-modal").html(jqXHR.responseText)
                           //$('#errorsModal').modal('show')
                           //return false

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
            "columns": columnasAveriasCoe,
            'columnDefs': [ 
                    {
                        'targets': '_all',
                        'createdCell':  function (td, cellData, rowData, row, col) { 
                            
                        // $(td).css({"background":`${rowData.background}`,"color":`${rowData.colorText}`}); 
                            ///$(td).addClass("text-center")
                            //console.log("los cells: ",td, cellData, rowData, row, col)

                           /* let count = 0

                            if (DIAGNOSTICOM_PERMISO)   count ++ 

                            if (col == count+8) { //InputVoltagefinalcolorF
                                    $(td).css({"background":`${rowData.InputVoltagefinalcolorF.background}`,"color":`${rowData.InputVoltagefinalcolorF.color}`});      
                            }
                            if (col == count+9) { //OutputVoltagefinalcolorF
                                    $(td).css({"background":`${rowData.OutputVoltagefinalcolorF.background}`,"color":`${rowData.OutputVoltagefinalcolorF.color}`});      
                            }
                            if (col == count+10) { //OutputCurrentfinalcolorF
                                    $(td).css({"background":`${rowData.OutputCurrentfinalcolorF.background}`,"color":`${rowData.OutputCurrentfinalcolorF.color}`});      
                            }
                            if (col == count+11) { //TotalStringVoltagefinalcolorF
                                    $(td).css({"background":`${rowData.TotalStringVoltagefinalcolorF.background}`,"color":`${rowData.TotalStringVoltagefinalcolorF.color}`});      
                            }*/

                        
                        }
                    } ,
                    {
                        
                        "targets": '_all',
                        "orderable" : false,
                        "searchable": false,
                            
                    } 
            ] ,
            "initComplete": function(){
                // console.log("Termino la carga completa")
               /* if (REFRESH_PERMISO) {
                        ESTA_ACTIVO_REFRESH = true
                        peticiones.resetInterval()
                }*/

                $("#resultAveriasCOEMasivas").parent().addClass("table-responsive tableFixHead") 
                // $("#filtroContentHFC").removeClass("d-none")

                let tablaHead = $('.tableFixHead').find('thead th')
                let primera_col = $('.tableFixHead tbody tr td:nth-child(1)')

                $('.tableFixHead').on('scroll', function() {
                   // console.log("ejecutando left scroll"+this.scrollLeft); 
                    primera_col.css({'transform':'translateX('+ this.scrollLeft +'px)'});
                    tablaHead.css('transform', 'translateY('+ this.scrollTop +'px)'); 
                });

                $("#activarGestionMasiva").removeClass("d-none")
                    
            },
            "pageLength": 50,
            "language": {
                        "info": "_TOTAL_ registros",
                        "search": "Buscar",
                        "paginate": {
                                "next": "Siguiente",
                                "previous": "Anterior",
                        },
                        "lengthMenu": 'Mostrar <select >'+
                        '<option value="50">50</option>'+
                        '<option value="100">100</option>'+
                        '<option value="300">300</option>'+
                        '<option value="500">500</option>'+
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
   

}


export default peticiones


