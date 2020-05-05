@extends('layouts.master')

@section('titulo_pagina_sistema', 'Llamadas por Troba')
 
@section('estilos') 
    <style>
        #mapa_content_carga, #mapa_call_content_carga{
                height: calc(100vh - 150px);
            }
        .content_filter_basic {
            border: 1px solid rgba(192, 200, 208, 0.84);
            border-radius: 5px;
        }
        .width-100{
            width: 100%;
        }
    </style>
@endsection

@section('scripts-header')
    <script>
        var GESTION_PERMISO = false
        var DIAGNOSTICOM_PERMISO = false
        var MAPA_PERMISO = false
        var MAPA_PERMISO_CALL = false
        var VER_CRITICOS_PERMISO = false
        var VER_TRABPROGRAMADOS_PERMISO = false
        var REFRESH_PERMISO = false
    </script>
@endsection

@section('top-left-submenus')
    @parent
    {{-- Menu Top--}}
@endsection

@section('title-container')
    <h4 class="m-0 text-dark text-uppercase">MONITOREO DE LLAMADAS POR TROBA</h4> 
@endsection

@section('ruta-navegacion-container')
    @parent
     <li class="breadcrumb-item active">Llamadas por Trobas</li>
@endsection

@section('aside-right')
    {{-- Aqui el aside del lado derecho, ingresar lo que sedea mostrar--}}
@endsection

@section('content')
    @parent

    @include('administrador.partials.gestionDetalleModal')
    @include('administrador.modulos.llamadas.partials.descargasLlamadasModal')
    @include('administrador.modulos.llamadas.partials.trabajoPDetalleModal')

    <div class="row">

        <div class="tab-content w-100" id="tabsLlamadasContent">
            <div class="tab-pane listaLlamadas fade show   active" id="llamadasMasivasTab" role="tabpanel" aria-labelledby="llamadasMasivasTab-tab">
                <input type="hidden" value="llamadasMasivasTab" id="input-llamadasMasivasTab">
                <input type="hidden" value="{{ $nodo }}" id="nodoJefaturaLlamadas" name="nodo" />
                <section  class="col-12 mx-0 px-0">
                    <div class="card-header px-2 py-1"> 
                        @if ($grafica=="" && $nodo=="" && $grafica_acumulado_dia=="" && $grafica_llamadas_nodo_tp=="")
                            <a href="{{ route('administrador') }}" class="btn btn-sm btn-outline-success mx-1"><i class="fa fa-arrow-left"></i> Atras </a>
                        @endif
                        <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                        @if (isset($nodo) && $nodo!=="" && $grafica=="" && $grafica_acumulado_dia=="" && $grafica_llamadas_nodo_tp=="")
                            <a href="{{route('modulo.llamadas-nodo.index')}}" class="btn btn-sm btn-outline-success mx-1"><i class="fa fa-arrow-left"></i> Atras Llamadas Nodos</a>
                        @endif
                        @if (isset($grafica) && $grafica!=="")
                            <a href="{{route('modulo.grafica-llamadas-nodos.index')}}" class="btn btn-sm btn-outline-success mx-1"><i class="fa fa-arrow-left"></i> Atras Gráfica Llamadas Nodos</a>
                        @endif
                        @if (isset($grafica_acumulado_dia) && $grafica_acumulado_dia!=="")
                            <a href="{{route('modulo.grafica-llamadas-nodos-dia.index')}}" class="btn btn-sm btn-outline-success mx-1"><i class="fa fa-arrow-left"></i> Atras Gráfica Llamadas Nodos (Acumulado Día)</a>
                        @endif
                        @if (isset($grafica_llamadas_nodo_tp) && $grafica_llamadas_nodo_tp!=="")
                            <a href="{{route('modulo.trabajos-programados.index')}}" class="btn btn-sm btn-outline-success mx-1"><i class="fa fa-arrow-left"></i> Atras Trabajos Programados</a>
                        @endif
                        @if(Auth::user()->HasPermiso('submodulo.llamadas.gestion-masiva.store'))
                                <a href="{{route('submodulo.llamadas.gestion-masiva.view')}}" class="btn btn-sm btn-outline-success mx-1"> Gestión Masiva <i class="fa fa-arrow-right"></i></a>
                        @endif
                    </div>
                    <div class="cad">
                        <div id="errorExcel"></div>
                        <div class="card-body position-relative" id="contenedor_caidasMasivas_body">
                            <section class="row w-100 my-3 mx-0 py-2 content_filter_basic justify-content-center" id="filtroContentLlamadas" style="display:none">
                                <div class="form-group row mx-0 px-2 col-12 col-sm-6 col-md-4 col-lg-4">
                                    <label for="" class="col-12 col-sm-3">Jefaturas:</label>
                                    <select name="listajefaturaLlamadas" id="listajefaturaLlamadas" class="col-12 col-sm-9 form-control form-control-sm shadow-sm">
                                        <option value="">Sin Filtro</option>
                                            @forelse ($jefaturas as $jeft)
                                                <option value="{{$jeft->jefatura}}">{{$jeft->jefatura}}</option>
                                            @empty
                                            
                                            @endforelse
                                    </select>
                                </div>
                                <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                    <label for="" class="col-12 col-sm-3">Top:</label>
                                    <select name="listaTopLlamadas" id="listaTopLlamadas" class="col-12 col-sm-9 form-control form-control-sm shadow-sm">
                                        <option value="">Sin Filtro</option>
                                        <option value="100">Top 100</option>
                                        <option value="200">Top 200</option>
                                    </select>
                                </div>
                                <div class="form-group row mx-0 mb-0 px-2 col-12 col-sm-12 col-md-12 col-lg-12 justify-content-center"> 
                                        <a href="javascript:void(0)" class="btn btn-sm btn-primary shadow-sm w-25" id="filtroBasicoLlamadas">Filtrar</a>
                                </div>
                               
                            </section>

                            <div class="content_table_list"> 
                                <table id="resultLlamadaTrobas" class="table table-hover table-bordered w-100 tableFixHead table-text-xs">
                                    <thead>
                                        <tr>
                                            <th>Item</th>
                                            <th>Jefatura</th>
                                            <th>Nodo-Troba</th>
                                            <th>Mapa</th>
                                            <th>Top</th>
                                            <th>Llamadas DMPE</th>
                                            <th>Averias</th>
                                            <th>Ultima Llamada</th>
                                            <th>CodMasiva</th>
                                            <th>Dmpe</th>
                                            <th>User DMPE</th>
                                            @if(Auth::user()->HasPermiso('submodulo.llamadas.trabajos-programados.view'))
                                                <th>Trabajo Programado</th>
                                                <script>
                                                    VER_TRABPROGRAMADOS_PERMISO = true
                                                </script>
                                            @endif
                                            <th>ESTADO GESTION</th>
                                            @if(Auth::user()->HasPermiso('submodulo.llamadas.gestion-individual.store'))
                                                <th>Gestion</th>
                                                <script>
                                                    GESTION_PERMISO = true
                                                </script>
                                            @endif
                                        </tr>
                                    </thead>  
                                </table>
                            </div>
                        </div>
                    </div>
                </section> 
            </div>
            <!-- BLoque Diagnostico Masivo [DM] -->
            @if(Auth::user()->HasPermiso('submodulo.llamadas.diagnostico-masivo.view'))
                <div class="tab-pane fade" id="verDiagMasTab" role="tabpanel" aria-labelledby="verDiagMasTab-tab">
                    <section  class="col-12 mx-0 px-0">
                            <div class="card">
                                <div class="card-header px-2 py-1">
                                    <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_llamadas"><i class="fa fa-arrow-left"></i> Atras Llamadas</a>
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
                <script src="{{ url('/js/sistema/modulos/llamadas/diagnostico-masivo.min.js') }}"></script> 
            @endif
            <!-- BLoque Mapa -->
            @if(Auth::user()->HasPermiso('submodulo.llamadas.mapa.view'))
                <div class="tab-pane fade " id="verMapaTab" role="tabpanel" aria-labelledby="verMapaTab-tab">
                        <section  class="col-12 mx-0 px-0">
                                <div class="card">
                                    <div class="card-header px-2 py-1">
                                        <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_llamadas"><i class="fa fa-arrow-left"></i> Atras Llamadas</a>
                                        <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                                    </div>
                                    <div class="card-body">
                                        <div id="mapa_content_carga"></div>
                                    </div>
                                </div>
                        </section>
                </div>
                <script>
                        MAPA_PERMISO = true
                </script>
                <script src="{{ url('/js/sistema/modulos/llamadas/mapa.min.js') }}"></script>
            @endif
            <!-- BLoque Mapa Call -->
            @if(Auth::user()->HasPermiso('submodulo.llamadas.mapa-call.view'))
                <div class="tab-pane fade " id="verMapaCallTab" role="tabpanel" aria-labelledby="verMapaCallTab-tab">
                    <section  class="col-12 mx-0 px-0">
                            <div class="card">
                                <div class="card-header px-2 py-1">
                                    <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_llamadas"><i class="fa fa-arrow-left"></i> Atras Llamadas</a>
                                    <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                                </div>
                                <div class="card-body">
                                    <div id="mapa_call_content_carga"></div>
                                </div>
                            </div>
                    </section>
                </div>
                <script>
                        MAPA_PERMISO_CALL = true
                </script>
                <script src="{{ url('/js/sistema/modulos/llamadas/mapa-call.min.js') }}"></script>
            @endif
            <!-- BLoque Gestion -->
            @if(Auth::user()->HasPermiso('submodulo.llamadas.gestion-individual.store'))
                <div class="tab-pane fade " id="gestionIndividualTab" role="tabpanel" aria-labelledby="gestionIndividualTab-tab">
                        <section  class="col-12 mx-0 px-0">
                                <div class="card">
                                    <div class="card-header px-2 py-1">
                                        <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_llamadas"><i class="fa fa-arrow-left"></i> Atras Llamadas</a>
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
                <script src="{{ url('/js/sistema/modulos/llamadas/gestion-individual.min.js') }}"></script>
            @endif
            <div class="tab-pane fade " id="registrosGestionesTab" role="tabpanel" aria-labelledby="registrosGestionesTab-tab">
                <section  class="col-12 mx-0 px-0">
                        <div class="card">
                            <div class="card-header px-2 py-1">
                            <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_llamadas"><i class="fa fa-arrow-left"></i> Atras Llamadas</a>
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
        </div>
           
    </div>
@endsection

@section('scripts-footer')   

    @if(Auth::user()->HasPermiso('submodulo.llamadas.refresh'))
        <script>
            REFRESH_PERMISO = true
        </script>
    @endif
    
    <script>

        var INTERVAL_LOAD = null
        var ESTADO_GRAFICO =  "{!! $grafica !!}";
        var ESTADO_NODO =  "{!! $nodo !!}";

        const BUTTONS_CAIDAS_MASIVAS =
        [
            {
                text: 'DESCARGAS',
                className: 'btn btn-sm btn-success shadow-sm',
                titleAttr: 'DESCARGAS EN LLAMADAS',
                action: function ( e, dt, node, config ) {
                    //alert( 'Button Opciones GPON' );
                    //console.log("opciones:", e, dt, node, config)
                    $("#descargasLlamadasModal").modal("show");
                }
            },
            {
                text: 'FILTROS',
                className: 'btn btn-sm btn-info shadow-sm',
                titleAttr: 'FILTROS EN LLAMADAS',
                action: function ( e, dt, node, config ) {
                    //alert( 'Button Opciones' );
                    //console.log("opciones:", e, dt, node, config)
                    //console.log("Se deberias mostrar los filtros")
                    $("#filtroContentLlamadas").slideToggle()
                }
            }
        ]
        
        if(ESTADO_GRAFICO !=='' || ESTADO_NODO !=='' ) {
            BUTTONS_CAIDAS_MASIVAS.pop();
        } 
        
        var ESTA_ACTIVO_REFRESH = false
    
   </script>

   @if(Auth::user()->HasPermiso('submodulo.llamadas.trabajos-programados.view'))
        <script src="{{ url('/js/sistema/modulos/llamadas/trabajos-programados.min.js') }}"></script>
    @endif

   <script src="{{ url('/js/sistema/modulos/llamadas/index.min.js') }}"></script>
   <script src="{{ url('/js/sistema/modulos/llamadas/historial-gestion.min.js') }}"></script>
   <script src="{{ url('/js/sistema/modulos/llamadas/reporte-llamadas.min.js') }}"></script>
      
@endsection