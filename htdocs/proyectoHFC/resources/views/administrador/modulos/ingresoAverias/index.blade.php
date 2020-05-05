@extends('layouts.master')

@section('titulo_pagina_sistema', 'Ingreso de Averías')
 
@section('estilos') 
    <style>
        .dt-buttons {
            opacity: 0;
        }
        .content_filter_basic {
            border: 1px solid rgba(192, 200, 208, 0.84);
            border-radius: 5px;
        }
        .margin-right-1{
            margin-right: 1rem;
        }
        .margin-0{
            margin-bottom: 0 !important;
        }
        .margin-padding-5{
            margin-bottom: .5rem;
            padding-top: .5rem;
        }
        .flex-align-justify-center{
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .pre-load-ingresos-averias{
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .pre-load-reporte-averias{
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .pre-reporte-averias{
            position: absolute;
            z-index: 9;
        }
        .width-100{
            width: 100%;
        }
        .preloadCharger{
            position: fixed;
            width: 100%;
            height: 100vh;
            display: flex;
            top: 0;
            align-items: center;
            justify-content: center;
            z-index: 9;
        }
        .buttom-flex-end{
            display: flex;
            justify-content: flex-end;
            padding: 1em;
        }
        .btn-margin-right{
            margin-right: .5em;
        }
        .margin-left-auto{
            margin-left: auto;
        }
        .margin-right-auto{
            margin-right: auto;
        }
    </style>
@endsection

@section('scripts-header')
    <script>
        var REFRESH_PERMISO = false
    </script>
@endsection

@section('top-left-submenus')
    @parent
    {{-- Menu Top--}}
@endsection

@if (isset($motivo) && $motivo=="cuadroMando")
@section('title-container')
<select class="form-control form-control-sm shadow-sm text-success" id="display_filter_special">
    <option value="averiasJefTab">Averías por Jefaturas</option>
</select>
@endsection
@else
@section('title-container')
        <select class="form-control form-control-sm shadow-sm text-success" id="display_filter_special">
            <option value="averiasJefTab">Averías por Jefaturas</option>
            <option value="averiasMotTab">Averías por Motivos</option>
        </select>  
@endsection
@endif

@section('ruta-navegacion-container')
    @parent
     <li class="breadcrumb-item active">Ingreso Averías</li>
@endsection

@section('aside-right')
    {{-- Aqui el aside del lado derecho, ingresar lo que sedea mostrar--}}
@endsection

@section('content')
    @parent

    @include('administrador.modulos.ingresoAverias.partials.descargasModalJefatura')
      
    <div class="row">
        <div id="preloadCharger" class="width-100"></div>
        <div class="tab-content w-100" id="tabsIngresoAveriasContent">
            <div class="tab-pane listaIngresoAveriasJefaturas fade show   active" id="averiasJefTab" role="tabpanel" aria-labelledby="averiasJefTab-tab">
                <section  class="col-12 mx-0 px-0">
                    <div class="card-header px-2 py-1">
                        @if (isset($motivo) && $motivo=="cuadroMando")
                            <a href="{{route('modulo.cuadro-mando.index')}}" class="btn btn-sm btn-outline-success mx-1"><i class="fa fa-arrow-left"></i> Atras Cuadro Mando</a>
                        @else
                            <a href="{{route('administrador')}}" class="btn btn-sm btn-outline-success mx-1"><i class="fa fa-arrow-left"></i> Atras </a>
                        @endif
                        
                        <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                    </div>
                    <div class="cad"> 
                        <div id="errorExcel"></div>
                        <div class="card-body position-relative" id="contenedor_ingresoAverias_body">
                            <input type="hidden" name="motivoCuadroMando" id="motivoCuadroMando" value="{{ (isset($motivo) && $motivo=="cuadroMando")? $motivo:'' }}" />
                            <div class="content_table_list">
                                <section class="row my-3 py-2 content_filter_basic" id="filtroAveriasJefaturas" style="display:none">
                                        <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                            <label for="" class="col-12 col-sm-3">Jefaturas:</label>
                                            <select name="jefaturaIngresoAverias" id="jefaturaIngresoAverias" class="col-12 col-sm-9 form-control form-control-sm shadow-sm">
                                                <option value="">Todos</option>
                                                    @forelse ($jefaturas as $jeft)
                                                        <option value="{{$jeft->jefatura}}">{{$jeft->jefatura}}</option>
                                                    @empty
                                                        
                                                    @endforelse
                                            </select>
                                        </div>
                                        <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                            <label for="" class="col-12 col-sm-3">Trobas:</label>  
                                            <select name="trobaIngresoAverias" id="trobaIngresoAverias" class="col-12 col-sm-9 form-control form-control-sm shadow-sm">
                                                <option value="">Todos</option>
                                                    @forelse ($trobas as $listaTrob)
                                                        <option value="{{$listaTrob->clave}}">{{$listaTrob->clave}}</option>
                                                    @empty
                                                        
                                                    @endforelse
                                            </select>
                                        </div>
                                        <div class="form-group row mx-0 mb-0 px-2 col-12 col-sm-12 col-md-12 col-lg-12 justify-content-center">
                                            <a href="javascript:void(0)" class="btn btn-sm btn-primary shadow-sm w-25" id="filtroIngresoAveriaJefatura">Filtrar</a>
                                        </div>
                                </section>
                                <div class="buttom-flex-end" id="averiasJefTabFiltro">
                                    <div class="btn-group">
                                        <button class="btn btn-secondary btn-margin-right btn-sm btn-info filtro-ingreso-averias-jefaturas averias-jefatura-filtro" tabindex="0" aria-controls="resultSaturacionDown" type="button" title="FILTROS EN SATURACION DOWN">
                                            <span>FILTROS</span>
                                        </button> 
                                    </div>
                                    <div class="btn-group">
                                        <button class="btn btn-secondary btn-sm btn-success shadow-sm modal-ingreso-averias-jefaturas averias-jefatura-descargar" tabindex="0" aria-controls="resultSaturacionDown" type="button" title="FILTROS EN SATURACION DOWN">
                                            <span>DESCARGAR</span>
                                        </button> 
                                    </div>
                                </div>
                                <section  class="col-12 mx-0 px-0">
                                    <div class="card">
                                        <div id="preloadGraphJef" class="pre-load-ingresos-averias"> </div>
                                        <div class="card-body"> 
                                            <div id="averiasJefaturaGrafico" class="row text-sm"></div>
                                        </div>
                                        <p class="text-center" id="averiasJefaturaGraficoPie"></p>
                                    </div>
                                    <div id="averiasJefaturaDetalle"></div>
                                </section>
                            </div>
                        </div>
                    </div>
                </section> 
            </div>  
            <div class="tab-pane listaIngresoAveriasMotivos fade" id="averiasMotTab" role="tabpanel" aria-labelledby="averiasMotTab-tab">
                <section  class="col-12 mx-0 px-0">
                <div class="card-header px-2 py-1"> 
                        <a href="{{route('administrador')}}" class="btn btn-sm btn-outline-success mx-1"><i class="fa fa-arrow-left"></i> Atras </a>
                        <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                </div>
                <div class="cad"> 
                    <div id="errorExcel"></div>
                    <div class="card-body position-relative" id="contenedor_averiasMot_body">
                        <div class="content_table_list">
                                <section class="row my-3 py-2 content_filter_basic" id="filtroAveriasMotivos" style="display:none">
                                        <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                            <label for="" class="col-12 col-sm-3">Jefaturas:</label>
                                            <select name="jefaturaIngresoAverias" id="jefaturaIngresoAverias" class="col-12 col-sm-9 form-control form-control-sm shadow-sm">
                                                <option value="">Todos</option>
                                                    @forelse ($jefaturas as $listaJef)
                                                        <option value="{{$listaJef->jefatura}}">{{$listaJef->jefatura}}</option>
                                                    @empty
                                                        
                                                    @endforelse
                                            </select>
                                        </div>
                                        <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                            <label for="" class="col-12 col-sm-3">Trobas:</label>  
                                            <select name="trobaIngresoAverias" id="trobaIngresoAverias" class="col-12 col-sm-9 form-control form-control-sm shadow-sm">
                                                <option value="">Todos</option>
                                                    @forelse ($trobas as $listaTrob)
                                                        <option value="{{$listaTrob->clave}}">{{$listaTrob->clave}}</option>
                                                    @empty
                                                        
                                                    @endforelse
                                            </select>
                                        </div>
                                        <div class="form-group row mx-0 mb-0 px-2 col-12 col-sm-12 col-md-12 col-lg-12 justify-content-center">
                                            <a href="javascript:void(0)" class="btn btn-sm btn-primary shadow-sm w-25" id="filtroIngresoAveriaJefaturaMotivos">Filtrar</a>
                                        </div>
                                </section>
                                <div class="buttom-flex-end">
                                    <div class="btn-group">
                                        <button class="btn btn-secondary btn-margin-right btn-sm btn-info filtro-ingreso-averias-motivos averias-jefatura-filtro" tabindex="0" aria-controls="resultSaturacionDown" type="button" title="FILTROS EN SATURACION DOWN">
                                            <span>FILTROS</span>
                                        </button> 
                                    </div>
                                </div>
                                <div class="card">
                                    <div id="preloadGraph" class="pre-load-ingresos-averias"> </div>
                                    <div class="card-body"> 
                                        <div id="averiasMotivosGrafico" class="row text-sm"></div>
                                        <div id="averiasMotivosDetalle"></div>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
                </section> 
            </div>
        </div>
           
    </div>


@endsection

@section('scripts-footer')   

    @if(Auth::user()->HasPermiso('submodulo.ingreso-averias.refresh'))
        <script>
            REFRESH_PERMISO = true
        </script>
    @endif

    <script>
        
        var INTERVAL_LOAD = null
        var ESTA_ACTIVO_REFRESH = false
         
    </script>

    <script src="{{ url('/library/Highcharts/code/highcharts.js')}}"></script>
    <script src="{{ url('/library/Highcharts/code/modules/export-data.js')}}"></script>

    <script src="{{ url('/js/sistema/modulos/ingreso-averias/reporte-ingreso-averias.min.js') }}"></script>
    <script src="{{ url('/js/sistema/modulos/ingreso-averias/edit.min.js') }}"></script>
    <script src="{{ url('/js/sistema/modulos/ingreso-averias/index.min.js') }}"></script>
      
@endsection