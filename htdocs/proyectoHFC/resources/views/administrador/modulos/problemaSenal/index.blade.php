@extends('layouts.master')

@section('titulo_pagina_sistema', 'Problemas Señal')
 
@section('estilos') 
    <style>
        #mapa_content_problema_senal {
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
        var VER_TRABPROGRAMADOS_PERMISO = false
    </script>
@endsection

@section('top-left-submenus')
    @parent
    {{-- Menu Top--}}
@endsection

@section('title-container')
     <h4 class="m-0 text-dark text-uppercase">SEGUIMIENTO DE ALERTAS</h4> 
    
@endsection


@section('ruta-navegacion-container')
    @parent
     <li class="breadcrumb-item active">Problemas Señal RF</li>
@endsection

@section('aside-right')
    {{-- Aqui el aside del lado derecho, ingresar lo que sedea mostrar--}}
@endsection

@section('content')
    @parent

    @include('administrador.modulos.problemaSenal.partials.descargasModal')
    @include('administrador.modulos.problemaSenal.partials.trabajoPDetalleModal')
    @include('administrador.partials.gestionDetalleModal')
      
    <div class="row">

        <div class="tab-content w-100" id="tabsProblemaSenalContent">
            <div class="tab-pane fade show   active" id="problemaSenalTab" role="tabpanel" aria-labelledby="problemaSenalTab-tab">
                <section  class="col-12 mx-0 px-0">
                    <div class="card-header px-2 py-1"> 
                            <a href="{{route('administrador')}}" class="btn btn-sm btn-outline-success mx-1"><i class="fa fa-arrow-left"></i> Atras </a>
                            <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                    </div>
                    <div class="cad"> 
                        <div id="errorExcel"></div>
                        <div class="card-body position-relative" id="contenedor_problemaSenal_body">
                            <div class="h6 text-center d-block text-danger mb-3">(Parametros RF)</div>
                            <section class="row my-3 py-2 content_filter_basic" id="filtroContentProblemas" style="display:none;">
                                <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                    <label for="" class="col-12 col-sm-3">Jefaturas:</label>
                                    <select name="listaJefaturasProblemas" id="listaJefaturasProblemas" class="col-12 col-sm-9 form-control form-control-sm shadow-sm">
                                        <option value="seleccionar">Sin Filtro</option>
                                        @forelse ($jefaturas as $jeft)
                                            <option value="{{$jeft->jefatura}}">{{$jeft->jefatura}}</option>
                                        @empty
                               
                                        @endforelse
                                    </select>
                                </div>
                                <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                    <label for="" class="col-12 col-sm-3">Estados:</label>
                                    <select name="listaEstadosProblemas" id="listaEstadosProblemas" class="col-12 col-sm-9 form-control form-control-sm shadow-sm">
                                        <option value="seleccionar">Sin Filtro</option>
                                        @forelse ($estados as $est)
                                            <option value="{{$est->estado}}">{{$est->estado}}</option>
                                        @empty
                                        
                                        @endforelse
                                    </select>
                                </div>
                                <div class="form-group row mx-0 mb-0 px-2 col-12 col-sm-12 col-md-12 col-lg-12 justify-content-center">
                                    <a href="javascript:void(0)" class="btn btn-sm btn-primary shadow-sm w-25" id="filtroBasicoProblemas">Filtrar</a>
                                </div>
                            </section>
                            <div class="content_table_list"> 
                                <table id="resultProblemaSenal" class="table table-hover table-bordered w-100 tableFixHead table-text-xs">
                                    <thead>
                                        <tr>
                                            <th>Item</th>
                                            <th>Jefatura</th>
                                            @if(Auth::user()->HasPermiso('submodulo.problema-senal.criticas.view'))
                                                <th>Critica?</th>
                                            @endif
                                            <th>Nodo</th>
                                            <th>Troba</th>
                                            @if(Auth::user()->HasPermiso('submodulo.problema-senal.trabajos-programados.view'))
                                                <th>T.Prog</th>
                                                <script>
                                                    VER_TRABPROGRAMADOS_PERMISO = true
                                                </script>
                                            @endif 
                                            <th>Averias M1/CATV</th>
                                            <th>CodMasiva</th>
                                            <th>Fecha_Ini</th>
                                            <th>Fecha_fin</th>
                                            <th>Tiempo</th>
                                            <th>RxPwrdBmv 5dBmv en CMTS</th>
                                            <th>PWR_UP 35> o <57</th>
                                            <th>SNR_UP</th>
                                            <th>PWR_DN</th>
                                            <th>SNR_DN</th>
                                            <th>Cant_Clientes</th>
                                            <th>C.Caidas</th>
                                            <th>N.Bornes</th>
                                            <th>ESTADO_GESTION</th>
                                            @if(Auth::user()->HasPermiso('submodulo.problema-senal.gestion-individual.store'))
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
            @if(Auth::user()->HasPermiso('submodulo.problema-senal.diagnostico-masivo.view'))
                <div class="tab-pane fade " id="verDiagMasTab" role="tabpanel" aria-labelledby="verDiagMasTab-tab">
                    <section  class="col-12 mx-0 px-0">
                            <div class="card">
                                <div class="card-header px-2 py-1">
                                    <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_problemaSenal"><i class="fa fa-arrow-left"></i> Atras Problema Señal</a>
                                    <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                                </div>
                                <div class="card-body">
                                    @include('administrador.partials.diagnosticoMasivo')
                                </div>
                            </div>
                    </section>
                </div> 
                <script>
                        DIAGNOSTICOM_PERMISO = true
                </script>
                <script src="{{ url('/js/sistema/modulos/problema-senal/diagnostico-masivo.min.js') }}"></script> 
            @endif



            @if(Auth::user()->HasPermiso('submodulo.problema-senal.criticas.view'))
                <div class="tab-pane fade" id="listaCriticosNodoTrobaTab" role="tabpanel" aria-labelledby="listaCriticosNodoTrobaTab-tab">
                    <section  class="col-12 mx-0 px-0">
                        <div class="card-header px-2 py-1"> 
                                <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_problemaSenal"><i class="fa fa-arrow-left"></i> Atras Problema Señal</a>
                            <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                        </div>
                        <div class="cad"> 
                        <div id="errorExcel"></div>
                        <div class="card-body position-relative" id="contenedor_listaCriticos_body">
                            <div class="h6 text-center d-block text-danger mb-3">Clientes Críticos</div> 
                            <div class="content_table_list"> 
                                <table id="resultListaClientesCriticos" class="table table-hover table-bordered w-100 tableFixHead table-text-xs">
                                    <thead>
                                        <tr>
                                            <th>IDCLIENTECRM</th>
                                            <th>idempresacrm</th>
                                            <th>NAMECLIENT</th>
                                            <th>NODO</th>
                                            <th>TROBA</th>
                                            <th>amplificador</th>
                                            <th>tap</th>
                                            <th>telf1</th>
                                            <th>telf2</th>
                                            <th>movil1</th>
                                            <th>MACADDRESS</th>
                                            <th>cmts</th>
                                            <th>f_v</th>
                                            <th>entidad</th>
                                        </tr>
                                    </thead>  
                                </table>
                            </div>
                        </div>
                        </div>
                    </section> 
                </div>
                <script> 
                    VER_CRITICOS_PERMISO = true
                </script> 
                <script src="{{ url('/js/sistema/modulos/problema-senal/clientes-criticos.min.js') }}"></script>
            @endif
 
            @if(Auth::user()->HasPermiso('submodulo.problema-senal.mapa.view'))
                <div class="tab-pane fade " id="verMapaTab" role="tabpanel" aria-labelledby="verMapaTab-tab">
                    <section  class="col-12 mx-0 px-0">
                        <div class="card">
                            <div class="card-header px-2 py-1">
                                <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_problemaSenal"><i class="fa fa-arrow-left"></i> Atras Problema Señal</a>
                                <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                            </div>
                            <div class="card-body">
                                <div id="mapa_content_problema_senal"></div>
                            </div>
                        </div>
                    </section>
                </div>
                <div class="tab-pane fade " id="problemaAvEdificiosTab" role="tabpanel" aria-labelledby="problemaAvEdificiosTab-tab">
                    <section  class="col-12 mx-0 px-0">
                        <div class="card">
                            <div class="card-header px-2 py-1">
                                <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_problemaSenal"><i class="fa fa-arrow-left"></i> Atras Monitor Averias</a>
                                <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_verMapaTab"><i class="fa fa-arrow-left"></i> Atras Mapa</a>
                                <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                            </div>
                            <div class="card-body">
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
                <script src="{{ url('/js/sistema/modulos/problema-senal/mapa.min.js') }}"></script>
                <script src="{{ url('/js/sistema/modulos/problema-senal/reporte-senal.min.js') }}"></script>
            @endif

            
            @if(Auth::user()->HasPermiso('submodulo.problema-senal.gestion-individual.store'))
                <div class="tab-pane fade " id="gestionIndividualProblemaSenalTab" role="tabpanel" aria-labelledby="gestionIndividualProblemaSenalTab-tab">
                    <section  class="col-12 mx-0 px-0">
                        <div class="card">
                            <div class="card-header px-2 py-1">
                                <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_problemaSenal"><i class="fa fa-arrow-left"></i> Atras Problemas Señal</a>
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
                <script src="{{ url('/js/sistema/modulos/problema-senal/gestion-individual.min.js') }}"></script>
            @endif
            <div class="tab-pane fade " id="registrosGestionesTab" role="tabpanel" aria-labelledby="registrosGestionesTab-tab">
                <section  class="col-12 mx-0 px-0">
                    <div class="card">
                        <div class="card-header px-2 py-1">
                            <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_problemaSenal"><i class="fa fa-arrow-left"></i> Atras Problema Señal</a>
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
                        <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_problemaSenal"><i class="fa fa-arrow-left"></i> Atras Problema Señal</a>
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

<!--------------------------------------------------------------------------------->

<!--------------------------------------------------------------------------------->



@section('scripts-footer')  

    <script>
          
        const COLUMNS_DEFS_MONITOR_AVERIAS = 
        [
            {
                'targets': '_all',
                'createdCell':  function (td, cellData, rowData, row, col) {

                    cantidad = 0
                    if (DIAGNOSTICOM_PERMISO) cantidad++

                    $(td).css({"background":`lightblue`,"color":`red`});
                        
                    if(col == 11){//RxPwrdBmv
                        $(td).css({"background":`${rowData.backgroundRxPwrdBmv}`,"color":`${rowData.colorRxPwrdBmv}`});
                    }
                    if(col == 12){//PWR_UP
                        $(td).css({"background":`${rowData.backgroundPwrUp}`,"color":`${rowData.colorPwrUp}`});
                    }
                    if(col == 13){//SNR_UP
                        $(td).css({"background":`${rowData.backgroundSnrUp}`,"color":`${rowData.colorSnrUp}`});
                    }
                    if(col == 14){//PWR_DN
                        $(td).css({"background":`${rowData.backgroundPwrDn}`,"color":`${rowData.colorPwrDn}`});
                    }
                    if(col == 15){//SNR_DN
                        $(td).css({"background":`${rowData.backgroundSnrDn}`,"color":`${rowData.colorSnrDn}`});
                    }

                    if(col == 19 + cantidad){
                        if (rowData.estado != null) { 
                            $(td).css({"background":`${rowData.backgroundEstado}`});
                        }else{ 
                            $(td).css({"background":`${rowData.backgroundSinEstado}`});
                        }
                    }

                        //$(td).css({"background":`lightblue`});
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
                /*
                action: function ( e, dt, node, config ) {
                    //alert( 'Button Opciones HFC' );
                    //console.log("opciones:", e, dt, node, config)
                    $("#descargasHfcModal").modal("show");
                }
                */
            },
            {
                text: 'FILTROS',
                className: 'btn btn-sm btn-info shadow-sm',
                titleAttr: 'FILTROS EN MONITOREO DE AVERÍAS HFC',
                action: function ( e, dt, node, config ) {
                    $("#filtroContentProblemas").slideToggle()
                    //alert( 'Button Opciones' );
                    //console.log("opciones:", e, dt, node, config)
                    //console.log("Se deberias mostrar los filtros")
                    /*
                    let filtroMonitorHfcGpon = $("#display_filter_special").val()
                    if (filtroMonitorHfcGpon == "monitor_averias_hfc") {
                        $("#filtroContentHfc").slideToggle()
                    }  
                    if (filtroMonitorHfcGpon == "monitor_averias_gpon") {
                        $("#filtroContentGpon").slideToggle()
                    }
                    */
                }
                
            }
        ]

    </script>

    @if(Auth::user()->HasPermiso('submodulo.problema-senal.trabajos-programados.view'))
        <script src="{{ url('/js/sistema/modulos/problema-senal/trabajos-programados.min.js') }}"></script>
    @endif

    <script src="{{ url('/js/sistema/modulos/problema-senal/index.min.js') }}"></script>
    <script src="{{ url('/js/sistema/modulos/problema-senal/historial-gestion.min.js') }}"></script>
    <script src="{{ url('/js/sistema/modulos/problema-senal/clientes-criticos.min.js') }}"></script>

@endsection

