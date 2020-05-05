@extends('layouts.master')

@section('titulo_pagina_sistema', 'Caidas')
 
@section('estilos') 
    <style>
        #mapa_content_carga {
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
        var VER_CRITICOS_PERMISO = false
        var VER_TRABPROGRAMADOS_PERMISO = false
        var REFRESH_PERMISO = false
    </script>
@endsection

@section('top-left-submenus')
    @parent
    {{-- Menu Top--}}
@endsection

@if (empty($motivo))
@section('title-container')
     <select class="form-control form-control-sm shadow-sm text-success" id="display_filter_special">
        @if(Auth::user()->HasPermiso('submodulo.caidas.caidas.view'))
        <option value="caidas_masivas">Caidas Masivas</option>
        @endif
        @if(Auth::user()->HasPermiso('submodulo.caidas.caidas-noc.view'))
        <option value="caidas_noc">Caidas NOC</option>
        @endif
        @if(Auth::user()->HasPermiso('submodulo.caidas.caidas-torre.view'))
        <option value="caidas_torre">Caidas Torre HFC</option>
        @endif
        @if(Auth::user()->HasPermiso('submodulo.caidas.caidas-amplificador.view'))
        <option value="caidas_amplificador">Caidas por Amplificador</option>
        @endif
     </select>  
@endsection
@endif


@section('ruta-navegacion-container')
    @parent
     <li class="breadcrumb-item active">Caidas</li>
@endsection

@section('aside-right')
    {{-- Aqui el aside del lado derecho, ingresar lo que sedea mostrar--}}
@endsection

@section('content')
    @parent

    
    @include('administrador.partials.gestionDetalleModal')
    @include('administrador.modulos.caidas.partials.descargasCaidasModal')
    @include('administrador.modulos.caidas.partials.trabajoPDetalleModal')
      
    <div class="row">
        
 
        <div class="tab-content w-100" id="tabsCaidasContent">
            @if (isset($motivo) && $motivo=="cuadroMando")
                <input type="hidden" id="filtroCuadroMando" value="{{$nodo}}">
            @endif



            <div class="tab-pane listaCaidas fade show   active" id="caidasMasivasTab" role="tabpanel" aria-labelledby="caidasMasivasTab-tab">
                <section  class="col-12 mx-0 px-0">
                <div class="card-header px-2 py-1">
                    @if (isset($motivo) && $motivo=="cuadroMando")
                        <a href="{{route('modulo.cuadro-mando.index')}}" class="btn btn-sm btn-outline-success mx-1"><i class="fa fa-arrow-left"></i> Atras Cuadro Mando</a>
                    @else
                        <a href="{{route('administrador')}}" class="btn btn-sm btn-outline-success mx-1"><i class="fa fa-arrow-left"></i> Atras </a>
                        @if(Auth::user()->HasPermiso('submodulo.caidas.gestion-masiva.store'))
                            <a href="{{route('submodulo.caidas.gestion-masiva.view')}}" class="btn btn-sm btn-outline-success mx-1"> Gestión Masiva <i class="fa fa-arrow-right"></i></a>
                        @endif 
                    @endif 
                        <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                </div>
                <div class="cad"> 
                    <div id="errorExcel"></div>
                    <div class="card-body position-relative" id="contenedor_caidasMasivas_body">
                        <div class="h5 text-center d-block text-danger mb-3">Caidas Masivas de Modems</div>

                        @include('administrador.modulos.caidas.partials.filtrosTop',["filtro"=>"MASIVA"])
                        
                        <div class="content_table_list"> 
                            <table id="resultCaidasMasivas" class="table table-hover table-bordered w-100 tableFixHead table-text-xs">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        @if(Auth::user()->HasPermiso('submodulo.caidas.diagnostico-masivo.view'))
                                            <th>DM</th>
                                        @endif 
                                        <th>Jefatura</th>
                                        @if(Auth::user()->HasPermiso('submodulo.caidas.criticas.view'))
                                            <th>Critica?</th> 
                                        @endif
                                    
                                        <th>NODO-TROBA</th>
                                        @if(Auth::user()->HasPermiso('submodulo.caidas.trabajos-programados.view'))
                                        <th>T. Programados</th>
                                        <script>
                                            VER_TRABPROGRAMADOS_PERMISO = true
                                        </script>
                                        @endif
                                       
                                        <th>Aver/Llam</th>
                                        <th>Clientes</th>
                                        <th>Offline >80</th>
                                        <th>Remedy</th>
                                        <th>CodMasiva</th>
                                        <th>Consultas M1</th>
                                        <th>Fecha_Ini</th>
                                        <th>Fecha_fin</th>
                                        <th>Tiempo</th>
                                        <th>ESTADO_GESTION</th>
                                        @if(Auth::user()->HasPermiso('submodulo.caidas.gestion-individual.store'))
                                            <th>Gestion</th>
                                        @endif 
                                            <th>Energia</th>
                                    </tr>
                                </thead>  
                            </table>
                        </div>
                    </div>
                </div>
                </section> 
            </div>  
            <div class="tab-pane listaCaidas fade" id="caidasNocTab" role="tabpanel" aria-labelledby="caidasNocTab-tab">
                <section  class="col-12 mx-0 px-0">
                <div class="card-header px-2 py-1"> 
                        <a href="{{route('administrador')}}" class="btn btn-sm btn-outline-success mx-1"><i class="fa fa-arrow-left"></i> Atras </a>
                        @if(Auth::user()->HasPermiso('submodulo.caidas.gestion-masiva.store'))
                            <a href="{{route('submodulo.caidas.gestion-masiva.view')}}" class="btn btn-sm btn-outline-success mx-1"> Gestión Masiva <i class="fa fa-arrow-right"></i></a>
                        @endif 
                        <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                </div>
                <div class="cad"> 
                    <div id="errorExcel"></div>
                    <div class="card-body position-relative" id="contenedor_caidasNoc_body">
                        <div class="h5 text-center d-block text-danger mb-3">Caidas Masivas de Modems (NOC)</div>

                        @include('administrador.modulos.caidas.partials.filtrosTop',["filtro"=>"NOC"])
                        
                        <div class="content_table_list"> 
                            <table id="resultCaidasNoc" class="table table-hover table-bordered w-100 tableFixHead table-text-xs">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        @if(Auth::user()->HasPermiso('submodulo.caidas.diagnostico-masivo.view'))
                                            <th>DM</th>
                                        @endif 
                                        <th>Jefatura</th>
                                        @if(Auth::user()->HasPermiso('submodulo.caidas.criticas.view'))
                                            <th>Critica?</th> 
                                        @endif
                                    
                                        <th>NODO-TROBA</th>
                                        @if(Auth::user()->HasPermiso('submodulo.caidas.trabajos-programados.view'))
                                        <th>T. Programados</th>
                                        <script>
                                            VER_TRABPROGRAMADOS_PERMISO = true
                                        </script>
                                        @endif
                                       
                                        <th>Averias</th>
                                        <th>Clientes</th>
                                        <th>Offline >80</th>
                                        <th>Remedy</th>
                                        <th>CodMasiva</th>
                                        <th>Consultas M1</th>
                                        <th>Fecha_Ini</th>
                                        <th>Fecha_fin</th>
                                        <th>Tiempo</th>
                                        <th>ESTADO_GESTION</th>
                                        @if(Auth::user()->HasPermiso('submodulo.caidas.gestion-individual.store'))
                                            <th>Gestion</th>
                                        @endif 
                                            <th>Energia</th>
                                    </tr>
                                </thead>  
                            </table>
                        </div>
                    </div>
                </div>
                </section> 
            </div>  
            <div class="tab-pane listaCaidas fade" id="caidasTorreHfcTab" role="tabpanel" aria-labelledby="caidasTorreHfcTab-tab">
                <section  class="col-12 mx-0 px-0">
                <div class="card-header px-2 py-1"> 
                        <a href="{{route('administrador')}}" class="btn btn-sm btn-outline-success mx-1"><i class="fa fa-arrow-left"></i> Atras </a>
                        @if(Auth::user()->HasPermiso('submodulo.caidas.gestion-masiva.store'))
                            <a href="{{route('submodulo.caidas.gestion-masiva.view')}}" class="btn btn-sm btn-outline-success mx-1"> Gestión Masiva <i class="fa fa-arrow-right"></i></a>
                        @endif 
                        <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                </div>
                <div class="cad"> 
                    <div id="errorExcel"></div>
                    <div class="card-body position-relative" id="contenedor_caidasTorreHfc_body">
                        <div class="h5 text-center d-block text-danger mb-3">Caidas Masivas de Modems (TORRE HFC )</div>

                        @include('administrador.modulos.caidas.partials.filtrosTop',["filtro"=>"HFC"])
                        
                        <div class="content_table_list"> 
                            <table id="resultCaidasTorreHfc" class="table table-hover table-bordered w-100 tableFixHead table-text-xs">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        @if(Auth::user()->HasPermiso('submodulo.caidas.diagnostico-masivo.view'))
                                            <th>DM</th>
                                        @endif 
                                        <th>Jefatura</th>
                                        @if(Auth::user()->HasPermiso('submodulo.caidas.criticas.view'))
                                            <th>Critica?</th> 
                                        @endif
                                    
                                        <th>NODO-TROBA</th>
                                        @if(Auth::user()->HasPermiso('submodulo.caidas.trabajos-programados.view'))
                                        <th>T. Programados</th>
                                        <script>
                                            VER_TRABPROGRAMADOS_PERMISO = true
                                        </script>
                                        @endif
                                       
                                        <th>Averias</th>
                                        <th>Clientes</th>
                                        <th>Offline >80</th>
                                        <th>Remedy</th>
                                        <th>CodMasiva</th>
                                        <th>Consultas M1</th>
                                        <th>Fecha_Ini</th>
                                        <th>Fecha_fin</th>
                                        <th>Tiempo</th>
                                        <th>ESTADO_GESTION</th>
                                        @if(Auth::user()->HasPermiso('submodulo.caidas.gestion-individual.store'))
                                            <th>Gestion</th>
                                        @endif 
                                            <th>Energia</th>
                                    </tr>
                                </thead>  
                            </table>
                        </div>
                    </div>
                </div>
                </section> 
            </div>  
            <div class="tab-pane listaCaidas fade" id="caidasAmplificadorTab" role="tabpanel" aria-labelledby="caidasAmplificadorTab-tab">
                <section  class="col-12 mx-0 px-0">
                <div class="card-header px-2 py-1"> 
                    @if (isset($motivo) && $motivo=="cuadroMando")
                        <a href="{{route('modulo.cuadro-mando.index')}}" class="btn btn-sm btn-outline-success mx-1"><i class="fa fa-arrow-left"></i> Atras Cuadro Mando</a>
                    @else
                        <a href="{{route('administrador')}}" class="btn btn-sm btn-outline-success mx-1"><i class="fa fa-arrow-left"></i> Atras </a>
                        @if(Auth::user()->HasPermiso('submodulo.caidas.gestion-masiva.store'))
                            <a href="{{route('submodulo.caidas.gestion-masiva.view')}}" class="btn btn-sm btn-outline-success mx-1"> Gestión Masiva <i class="fa fa-arrow-right"></i></a>
                        @endif 
                    @endif
                        <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                </div>
                <div class="cad"> 
                    <div id="errorExcel"></div>
                    <div class="card-body position-relative" id="contenedor_caidasAmplificador_body">
                        <div class="h5 text-center d-block text-danger mb-3">Caidas Masivas de Modems (AMPLIFICADOR)</div>

                        @include('administrador.modulos.caidas.partials.filtrosTop',["filtro"=>"AMPLIFICADOR"])
                        
                        <div class="content_table_list"> 
                            <table id="resultCaidasAmplificador" class="table table-hover table-bordered w-100 tableFixHead table-text-xs">
                                <thead>
                                    <tr>
                                        <th>Item</th> 
                                        @if(Auth::user()->HasPermiso('submodulo.caidas.diagnostico-masivo.view'))
                                            <th>DM</th>
                                        @endif 
                                        <th>Jefatura</th>
                                        @if(Auth::user()->HasPermiso('submodulo.caidas.criticas.view'))
                                            <th>Critica?</th> 
                                        @endif
                                        <th>NODO-TROBA</th>
                                        <th>AMPLIFICADOR</th>
                                        @if(Auth::user()->HasPermiso('submodulo.caidas.trabajos-programados.view'))
                                            <th>T. Programados</th>
                                            <script>
                                                VER_TRABPROGRAMADOS_PERMISO = true
                                            </script>
                                        @endif 
                                        <th>Averias</th>
                                        <th>Clientes</th>
                                        <th>Offline >80</th>
                                        <th>Remedy</th>
                                        <th>CodMasiva</th>
                                        <th>Consultas Llamadas</th>
                                        <th>Fecha_Ini</th>
                                        <th>Fecha_fin</th>
                                        <th>Tiempo</th>
                                        <th>ESTADO_GESTION</th>
                                        @if(Auth::user()->HasPermiso('submodulo.caidas.gestion-individual.store'))
                                            <th>Gestion</th>
                                        @endif 
                                        <th>Energia</th>
                                    </tr>
                                </thead>  
                            </table>
                        </div>
                    </div>
                </div>
                </section> 
            </div>  
            @if(Auth::user()->HasPermiso('submodulo.caidas.diagnostico-masivo.view'))
                <div class="tab-pane fade" id="verDiagMasTab" role="tabpanel" aria-labelledby="verDiagMasTab-tab">
                    <section  class="col-12 mx-0 px-0">
                            <div class="card">
                                <div class="card-header px-2 py-1">
                                    <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_caidas"><i class="fa fa-arrow-left"></i> Atras Caidas</a>
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
                <script src="{{ url('/js/sistema/modulos/caidas/diagnostico-masivo.min.js') }}"></script> 
            @endif
            @if(Auth::user()->HasPermiso('submodulo.caidas.mapa.view'))
                <div class="tab-pane fade " id="verMapaTab" role="tabpanel" aria-labelledby="verMapaTab-tab">
                        <section  class="col-12 mx-0 px-0">
                                <div class="card">
                                    <div class="card-header px-2 py-1">
                                        <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_caidas"><i class="fa fa-arrow-left"></i> Atras Caidas</a>
                                        <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                                    </div>
                                    <div class="card-body">
                                        <div id="mapa_content_carga"></div>
                                    </div>
                                </div>
                        </section>
                </div>
                <div class="tab-pane fade " id="detalleEdificiosTab" role="tabpanel" aria-labelledby="detalleEdificiosTab-tab">
                    <section  class="col-12 mx-0 px-0">
                        <div class="card">
                            <div class="card-header px-2 py-1">
                                <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_caidas"><i class="fa fa-arrow-left"></i> Atras Caidas</a>
                                <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_verMapaTab"><i class="fa fa-arrow-left"></i> Atras Mapa</a>
                                <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                            </div>
                            <div class="card-body position-relative">
                                <div class="h5 text-center d-block ">Detalle del edificio seleccionado (Centro de Control M1)</div>
                                <div class="content_table_list"> 
                                    <table id="edificios_content_general" class="table table-hover table-bordered w-100 tableFixHead">
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
                <script src="{{ url('/js/sistema/modulos/caidas/mapa.min.js') }}"></script>
                <script src="{{ url('/js/sistema/modulos/caidas/reporte-caidas.min.js') }}"></script>
            @endif
            @if(Auth::user()->HasPermiso('submodulo.caidas.gestion-individual.store'))
                <div class="tab-pane fade " id="gestionIndividualTab" role="tabpanel" aria-labelledby="gestionIndividualTab-tab">
                        <section  class="col-12 mx-0 px-0">
                                <div class="card">
                                    <div class="card-header px-2 py-1">
                                        <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_caidas"><i class="fa fa-arrow-left"></i> Atras Caidas</a>
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
                <script src="{{ url('/js/sistema/modulos/caidas/gestion-individual.min.js') }}"></script>
            @endif
            <div class="tab-pane fade " id="registrosGestionesTab" role="tabpanel" aria-labelledby="registrosGestionesTab-tab">
                <section  class="col-12 mx-0 px-0">
                        <div class="card">
                            <div class="card-header px-2 py-1">
                                <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_caidas"><i class="fa fa-arrow-left"></i> Atras Caidas</a>
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
            @if(Auth::user()->HasPermiso('submodulo.caidas.criticas.view'))
                <div class="tab-pane fade" id="listaCriticosNodoTrobaTab" role="tabpanel" aria-labelledby="listaCriticosNodoTrobaTab-tab">
                    <section  class="col-12 mx-0 px-0">
                        <div class="card-header px-2 py-1"> 
                                <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_caidas"><i class="fa fa-arrow-left"></i> Atras Caidas</a>
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
                <script src="{{ url('/js/sistema/modulos/caidas/clientes-criticos.min.js') }}"></script>
            @endif
            
            <div class="tab-pane fade " id="otroTab" role="tabpanel" aria-labelledby="otroTab-tab">
                    <section  class="col-12 mx-0 px-0">
                            <div class="card">
                                <div class="card-header px-2 py-1">
                                    <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_caidas"><i class="fa fa-arrow-left"></i> Atras Caidas</a>
                                    <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                                </div>
                                <div class="card-body">
                                    <h4 class="w-100 text-center text-uppercase font-weight-bold text-secondary">Titulo</h4>
                                    <div id="preloadfffffff"></div>
                                    <div id="resultfffffff" class="row text-sm"> 
                                    </div>
                                </div>
                            </div>
                    </section>
            </div>
        </div>
           
    </div>

    
@endsection

@section('scripts-footer')   



    @if(Auth::user()->HasPermiso('submodulo.caidas.refresh'))
        <script>
            REFRESH_PERMISO = true
        </script>
    @endif
 

    @if (isset($motivo) && $motivo=="cuadroMando")
    <script>

            var INTERVAL_LOAD = null
           
            const BUTTONS_CAIDAS_MASIVAS =
            [
                {
                    text: 'DESCARGAS',
                    className: 'btn btn-sm btn-success shadow-sm',
                    titleAttr: 'DESCARGAS EN CAIDAS',
                    action: function ( e, dt, node, config ) {
                        //alert( 'Button Opciones GPON' );
                        //console.log("opciones:", e, dt, node, config)
                        $("#descargasCaidasModal").modal("show");
                    }
                }
            ]
    
            var ESTA_ACTIVO_REFRESH = false
        
    </script> 
    @else
    <script>

            var INTERVAL_LOAD = null
           
            const BUTTONS_CAIDAS_MASIVAS =
            [
                {
                    text: 'DESCARGAS',
                    className: 'btn btn-sm btn-success shadow-sm',
                    titleAttr: 'DESCARGAS EN CAIDAS',
                    action: function ( e, dt, node, config ) {
                        //alert( 'Button Opciones GPON' );
                        //console.log("opciones:", e, dt, node, config)
                        $("#descargasCaidasModal").modal("show");
                    }
                },
                {
                    text: 'FILTROS',
                    className: 'btn btn-sm btn-info shadow-sm',
                    titleAttr: 'FILTROS EN CAIDAS',
                    action: function ( e, dt, node, config ) {
                        //alert( 'Button Opciones' );
                        //console.log("opciones:", e, dt, node, config)
                        //console.log("Se deberias mostrar los filtros")
                        $(".content_filter_basic").slideToggle()
                    }
                }
            ]
    
            var ESTA_ACTIVO_REFRESH = false
             
       </script>
    @endif


    @if(Auth::user()->HasPermiso('submodulo.caidas.trabajos-programados.view'))
        <script src="{{ url('/js/sistema/modulos/caidas/trabajos-programados.min.js') }}"></script>
    @endif

    <script src="{{ url('/js/sistema/modulos/caidas/index.min.js') }}"></script>
    <script src="{{ url('/js/sistema/modulos/caidas/historial-gestion.min.js') }}"></script>
      
@endsection