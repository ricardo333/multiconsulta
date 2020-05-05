@extends('layouts.master')

@section('titulo_pagina_sistema', 'Monitor Averias')
 
@section('estilos') 
    <style>
        #mapa_content_monitor_averias {
                height: calc(100vh - 150px);
            }
        .content_filter_basic {
            border: 1px solid rgba(192, 200, 208, 0.84);
            border-radius: 5px;
        }
    </style>
@endsection

@section('scripts-header')
    <script>
        var GESTION_PERMISO = false
        var DIAGNOSTICOM_PERMISO = false
        var MAPA_PERMISO = false
    </script>
@endsection

@section('top-left-submenus')
    @parent
    {{-- Menu Top--}}
@endsection

@section('title-container')
     <select class="form-control form-control-sm shadow-sm text-success" id="display_filter_special">
         <option value="monitor_averias_hfc">Monitor Averias HFC / CATV</option>
         <option value="monitor_averias_gpon">Monitor Averias GPON</option>
     </select>  
@endsection
@section('ruta-navegacion-container')
    @parent
     <li class="breadcrumb-item active">Monitor Averias</li>
@endsection

@section('aside-right')
    {{-- Aqui el aside del lado derecho, ingresar lo que sedea mostrar--}}
@endsection

@section('content')
    @parent

    @include('administrador.modulos.monitorAverias.partials.descargasHfcModal')
    @include('administrador.modulos.monitorAverias.partials.descargasGponModal')
    @include('administrador.partials.gestionDetalleModal')
      
    <div class="row">

      <div class="tab-content w-100" id="tabsMonitorAveriasContent">
        <div class="tab-pane fade show   active" id="monitorAveriasHFCTab" role="tabpanel" aria-labelledby="monitorAveriasHFCTab-tab">
            <section  class="col-12 mx-0 px-0">
              <div class="card-header px-2 py-1"> 
                    <a href="{{route('administrador')}}" class="btn btn-sm btn-outline-success mx-1"><i class="fa fa-arrow-left"></i> Atras </a>
                    @if(Auth::user()->HasPermiso('submodulo.monitor-averias.gestion-masiva.store'))
                        <a href="{{route('submodulo.monitor-averias.gestion-masiva.view')}}" class="btn btn-sm btn-outline-success mx-1"> Gestión Masiva <i class="fa fa-arrow-right"></i></a>
                    @endif
                    <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
              </div>
              <div class="cad"> 
                <div id="errorExcel"></div>
                <div class="card-body position-relative" id="contenedor_monitorAveriasHfc_body">
                    <div class="h6 text-center d-block text-danger mb-3">Última actualización HFC <strong id="fecha_ultimo_maver_hfc">{{$fechaMaxRegistro}}</strong> </div>
                    <section class="row my-3 py-2 content_filter_basic" id="filtroContentHfc" style="display:none;">
                        <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                            <label for="" class="col-12 col-sm-3">Jefaturas:</label>
                            <select name="listaJefaturasHfc" id="listaJefaturasHfc" class="col-12 col-sm-9 form-control form-control-sm shadow-sm">
                                <option value="seleccionar">Sin Filtro</option>
                                 @forelse ($jefaturas as $jeft)
                                    <option value="{{$jeft->jefatura}}">{{$jeft->jefatura}}</option>
                                 @empty
                               
                                 @endforelse
                            </select>
                        </div>
                        <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                <label for="" class="col-12 col-sm-3">Estados:</label>
                                <select name="listaEstadosHfc" id="listaEstadosHfc" class="col-12 col-sm-9 form-control form-control-sm shadow-sm">
                                    <option value="seleccionar">Sin Filtro</option>
                                    @forelse ($estados as $est)
                                        <option value="{{$est->estado}}">{{$est->estado}}</option>
                                    @empty
                                        
                                    @endforelse
                                </select>
                        </div>
                        <div class="form-group row mx-0 mb-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 justify-content-center">
                                <a href="javascript:void(0)" class="btn btn-sm btn-primary shadow-sm w-25" id="filtroBasicoHfc">Filtrar</a>
                        </div>
                    </section>
                    <div class="content_table_list"> 
                        <table id="resultMonitoreoAveriasHfc" class="table table-hover table-bordered w-100 tableFixHead table-text-xs">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    @if(Auth::user()->HasPermiso('submodulo.monitor-averias.diagnostico-masivo.view'))
                                        <th>DM</th>
                                    @endif 
                                    <th>Jefatura</th>
                                    <th>NODO-TROBA</th>
                                    <th>Llamadas DMPE</th>
                                    <th>Averias</th>
                                    <th>Ultreq</th>
                                    <th>CodMAsiva</th>
                                    <th>Trabajo_Programado</th>
                                    <th>ESTADO_GESTION</th>
                                    @if(Auth::user()->HasPermiso('submodulo.monitor-averias.gestion-individual.store'))
                                        <th>Gestion</th>
                                    @endif 
                                </tr>
                            </thead>  
                        </table>
                    </div>
                </div>
              </div>
            </section> 
        </div> 
        <div class="tab-pane fade" id="monitorAveriasGPONTab" role="tabpanel" aria-labelledby="monitorAveriasGPONTab-tab">
            <section  class="col-12 mx-0 px-0">
              <div class="card-header px-2 py-1"> 
                    <a href="{{route('administrador')}}" class="btn btn-sm btn-outline-success mx-1"><i class="fa fa-arrow-left"></i> Atras</a>
                    <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
              </div>
              <div class="cad"> 
                <div id="errorExcel"></div>
                <div class="card-body position-relative" id="contenedor_monitorAveriasGpon_body">
                    <div class="h6 text-center d-block text-danger mb-3">Última actualización GPON <strong id="fecha_ultimo_maver_gpon">{{$fechaMaxRegistro}}</strong> </div>
                    <section class="row my-3 py-2 content_filter_basic" id="filtroContentGpon" style="display:none;">
                        <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                            <label for="" class="col-12 col-sm-3">Jefaturas:</label>
                            <select name="listaJefaturasGpon" id="listaJefaturasGpon" class="col-12 col-sm-9 form-control form-control-sm shadow-sm">
                                <option value="seleccionar">Sin Filtro</option>
                                 @forelse ($jefaturas as $jeft)
                                    <option value="{{$jeft->jefatura}}">{{$jeft->jefatura}}</option>
                                 @empty
                               
                                 @endforelse
                            </select>
                        </div>
                        <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                <label for="" class="col-12 col-sm-3">Estados:</label>
                                <select name="listaEstadosGpon" id="listaEstadosGpon" class="col-12 col-sm-9 form-control form-control-sm shadow-sm">
                                    <option value="seleccionar">Sin Filtro</option>
                                    @forelse ($estados as $est)
                                        <option value="{{$est->estado}}">{{$est->estado}}</option>
                                    @empty
                                        
                                    @endforelse
                                </select>
                        </div>
                        <div class="form-group row mx-0 mb-0 px-2 col-12 col-sm-12 col-md-12 col-lg-12 justify-content-center">
                            <a href="javascript:void(0)" class="btn btn-sm btn-primary shadow-sm w-25" id="filtroBasicoGpon">Filtrar</a>
                        </div>
                    </section>
                    <div class="content_table_list"> 
                        <table id="resultMonitoreoAveriasGpon" class="table table-hover table-bordered w-100 tableFixHead table-text-xs">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    @if(Auth::user()->HasPermiso('submodulo.monitor-averias.diagnostico-masivo.view'))
                                        <th>DM</th>
                                    @endif
                                    <th>Jefatura</th>
                                    <th>NODO-TROBA</th>
                                    <th>Llamadas DMPE</th>
                                    <th>Averias</th>
                                    <th>Ultreq</th>
                                    <th>CodMAsiva</th>
                                    <th>Trabajo_Programado</th>
                                    <th>ESTADO_GESTION</th>
                                    @if(Auth::user()->HasPermiso('submodulo.monitor-averias.gestion-individual.store'))
                                        <th>Gestion</th>
                                    @endif 
                                </tr>
                            </thead>  
                        </table>
                    </div>
                </div>
              </div>
            </section> 
        </div> 
        @if(Auth::user()->HasPermiso('submodulo.monitor-averias.diagnostico-masivo.view'))
            <div class="tab-pane fade " id="verDiagMasTab" role="tabpanel" aria-labelledby="verDiagMasTab-tab">
                <section  class="col-12 mx-0 px-0">
                        <div class="card">
                            <div class="card-header px-2 py-1">
                                <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_monitorAverias"><i class="fa fa-arrow-left"></i> Atras Monitor Averias</a>
                                <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                            </div>
                            <div class="card-body position-relative">
                                @include('administrador.partials.diagnosticoMasivo')
                            </div>
                        </div>
                </section>
            </div> 
            <script>
                    DIAGNOSTICOM_PERMISO = true
            </script>
            <script src="{{ url('/js/sistema/modulos/monitor-averias/diagnostico-masivo.min.js') }}"></script> 
        @endif
        @if(Auth::user()->HasPermiso('submodulo.monitor-averias.mapa.view'))
            <div class="tab-pane fade " id="verMapaTab" role="tabpanel" aria-labelledby="verMapaTab-tab">
                    <section  class="col-12 mx-0 px-0">
                            <div class="card">
                                <div class="card-header px-2 py-1">
                                    <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_monitorAverias"><i class="fa fa-arrow-left"></i> Atras Monitor Averias</a>
                                    <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                                </div>
                                <div class="card-body">
                                    <div id="mapa_content_monitor_averias"></div>
                                </div>
                            </div>
                    </section>
            </div>
            <div class="tab-pane fade " id="monitorAvEdificiosTab" role="tabpanel" aria-labelledby="monitorAvEdificiosTab-tab">
                <section  class="col-12 mx-0 px-0">
                    <div class="card">
                        <div class="card-header px-2 py-1">
                            <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_monitorAverias"><i class="fa fa-arrow-left"></i> Atras Monitor Averias</a>
                            <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_verMapaTab"><i class="fa fa-arrow-left"></i> Atras Mapa</a>
                            <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                        </div>
                        <div class="card-body position-relative">
                            <div class="h5 text-center d-block ">Detalle del edificio seleccionado (Centro de Control M1)</div>
                            <div class="content_table_list"> 
                                <table id="edificios_content_multiconsulta" class="table table-hover table-bordered w-100 tableFixHead">
                                        <thead>
                                            <tr> 
                                                <th>MACSTATE</th> 
                                                <th>USPWR</th>
                                                <th>USMER_SNR</th>
                                                <th>DSPWR</th>
                                                <th>DSMER_SNR</th>
                                                <th>IDCLIENTECRM</th>
                                                <th>NAMECLIENT</th>
                                                <th>DIRECCION</th>
                                                <th>AMPLIFICADOR</th>
                                                <th>TAP</th>
                                                <th>TELF1</th>
                                                <th>MACADDRESS</th>
                                                <th>SERVICEPACKAGE</th>
                                            </tr> 
                                        </thead>  
                                    </table>
                            </div>
                            
                        </div>
                    </div>
                </section>
            </div>
            <script>
                    MAPA_PERMISO = true
            </script>
            <script src="{{ url('/js/sistema/modulos/monitor-averias/mapa.min.js') }}"></script>
            <script src="{{ url('/js/sistema/modulos/monitor-averias/reporte-averias.min.js') }}"></script>
        @endif
        @if(Auth::user()->HasPermiso('submodulo.monitor-averias.gestion-individual.store'))
            <div class="tab-pane fade " id="gestionIndividualMonitorAveriasTab" role="tabpanel" aria-labelledby="gestionIndividualMonitorAveriasTab-tab">
                    <section  class="col-12 mx-0 px-0">
                            <div class="card">
                                <div class="card-header px-2 py-1">
                                    <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_monitorAverias"><i class="fa fa-arrow-left"></i> Atras Monitor Averias</a>
                                    <a href="javascript:void(0)" id="registrosGestiones"  class="btn btn-sm btn-outline-primary shadow-sm"><i class="fa fa-arrow-right"></i> Historial de gestiones</a>
                                    <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                                </div>
                                <div class="card-body">
                                    <h4 class="w-100 text-center text-uppercase font-weight-bold text-secondary">Registro de Gestión</h4>
                                      
                                            @include('administrador.partials.gestionTrobaForm')
                                             
                                </div>
                            </div>
                    </section>
            </div>
            <script>
                GESTION_PERMISO = true
            </script>
            <script src="{{ url('/js/sistema/modulos/monitor-averias/gestion-individual.min.js') }}"></script>
        @endif
        <div class="tab-pane fade " id="registrosGestionesTab" role="tabpanel" aria-labelledby="registrosGestionesTab-tab">
            <section  class="col-12 mx-0 px-0">
                    <div class="card">
                        <div class="card-header px-2 py-1">
                            <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_monitorAverias"><i class="fa fa-arrow-left"></i> Atras Monitor Averias</a>
                            <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                        </div>
                        <div class="card-body position-relative">
                            <h5 class="h5 text-center d-block ">Detalle Historial Gestión</h5>
                            <section class="row my-3 py-2 content_filter_basic" id="filtroContentHistorialGestion" style="display:none;">
                                <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                    <label for="" class="col-12 col-sm-3">Nodo:</label>
                                        <input type="text" id="nodoFilterHistoricoGestion" class="form-control form-control-sm shadow-sm">
                                </div>
                                <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                        <label for="" class="col-12 col-sm-3">Troba:</label>
                                        <input type="text" id="trobaFilterHistoricoGestion" class="form-control form-control-sm shadow-sm">
                                </div>
                                <div class="form-group row mx-0 px-2 col-12 col-sm-12 justify-content-center text-center text-danger" id="errors_filter_historico_gestion">
                                       
                                </div>
                                <div class="form-group row mx-0 mb-0 px-2 col-12 col-sm-12 col-md-12 col-lg-12 justify-content-center">
                                        <a href="javascript:void(0)" class="btn btn-sm btn-primary shadow-sm w-25" id="filtroBasicoHistoricoGestion">Filtrar</a>
                                </div>
                            </section> 
                            @include('administrador.partials.historialGestion')
                        </div>
                    </div>
            </section>
        </div>
        <div class="tab-pane fade" id="historicoNodoTrobaTab" role="tabpanel" aria-labelledby="historicoNodoTrobaTab-tab">
            <section  class="col-12 mx-0 px-0">
                <div class="card-header px-2 py-1"> 
                        <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_monitorAverias"><i class="fa fa-arrow-left"></i> Atras Monitor Averias</a>
                    <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                </div>
                <div class="cad"> 
                <div id="errorExcel"></div>
                <div class="card-body position-relative" id="contenedor_historicoNodoTroba_body">
                        @include('administrador.partials.historicoNodoTrobas')
                </div>
                </div>
            </section> 
        </div>  
      </div>
           
    </div>

    
@endsection

@section('scripts-footer')   

    <script>
         
        const COLUMNS_DEFS_MONITOR_AVERIAS = 
        [
                {
                   'targets': '_all',
                   'createdCell':  function (td, cellData, rowData, row, col) {
                        // $(td).attr('id', 'cell-' + cellData); 
                         cantidad = 0
                         if (DIAGNOSTICOM_PERMISO) cantidad++

                         $(td).css({"background":`${rowData.background}`,"color":`${rowData.color}`});
                        if(col == 7 + cantidad ){
                            $(td).css({"min-width":"350px"});
                        }
                        if(col == 8 + cantidad ){//RxPwrdBmv 
                            if (rowData.estado != null) { 
                                $(td).css({"background":`${rowData.backgroundEstado}`,"color":`${rowData.colorTextEstado}`});
                            }else{ 
                                $(td).css({"background":`${rowData.backgroundSinEstado}`});
                            }
                            $(td).css({"min-width":"350px"}); 
                         }
                        /* if(col == 1){//USPwr
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
                         }*/
                        
                     
                   }
                },
                { 
                    "targets": '_all',
                    //"orderable" : false,
                    "searchable": false,
                        
                } 
        ]
 
        const BUTTONS_MONITOR_AVERIAS_HFC =
        [
            {
                text: 'DESCARGAS',
                className: 'btn btn-sm btn-success shadow-sm',
                titleAttr: 'DESCARGAS EN MONITOREO DE AVERÍAS HFC',
                action: function ( e, dt, node, config ) {
                    //alert( 'Button Opciones HFC' );
                    //console.log("opciones:", e, dt, node, config)
                    $("#descargasHfcModal").modal("show");
                }
            },
            {
                text: 'FILTROS',
                className: 'btn btn-sm btn-info shadow-sm',
                titleAttr: 'FILTROS EN MONITOREO DE AVERÍAS HFC',
                action: function ( e, dt, node, config ) {
                    //alert( 'Button Opciones' );
                    //console.log("opciones:", e, dt, node, config)
                    //console.log("Se deberias mostrar los filtros")
                    let filtroMonitorHfcGpon = $("#display_filter_special").val()
                    if (filtroMonitorHfcGpon == "monitor_averias_hfc") {
                        $("#filtroContentHfc").slideToggle()
                    }  
                    if (filtroMonitorHfcGpon == "monitor_averias_gpon") {
                        $("#filtroContentGpon").slideToggle()
                    }
                }
            }
        ]
        const BUTTONS_MONITOR_AVERIAS_GPON =
        [
            {
                text: 'DESCARGAS',
                className: 'btn btn-sm btn-success shadow-sm',
                titleAttr: 'DESCARGAS EN MONITOREO DE AVERÍAS GPON',
                action: function ( e, dt, node, config ) {
                    //alert( 'Button Opciones GPON' );
                    //console.log("opciones:", e, dt, node, config)
                    $("#descargasGponModal").modal("show");
                }
            },
            {
                text: 'FILTROS',
                className: 'btn btn-sm btn-info shadow-sm',
                titleAttr: 'FILTROS EN MONITOREO DE AVERÍAS GPON',
                action: function ( e, dt, node, config ) {
                    //alert( 'Button Opciones' );
                    //console.log("opciones:", e, dt, node, config)
                    //console.log("Se deberias mostrar los filtros")
                    let filtroMonitorHfcGpon = $("#display_filter_special").val()
                    if (filtroMonitorHfcGpon == "monitor_averias_hfc") {
                        $("#filtroContentHfc").slideToggle()
                    }  
                    if (filtroMonitorHfcGpon == "monitor_averias_gpon") {
                        $("#filtroContentGpon").slideToggle()
                    }
                }
            }
        ]
         
   </script>

    <script src="{{ url('/js/sistema/modulos/monitor-averias/index.min.js') }}"></script>
    <script src="{{ url('/js/sistema/modulos/monitor-averias/historial-gestion.min.js') }}"></script>
      
@endsection