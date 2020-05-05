@extends('layouts.master')

@section('titulo_pagina_sistema', 'Alertas Gráficas de Llamadas x Nodos')
 
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
        .pre-load-flex{
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
        .contenido-border .highcharts-container{
            outline: .1rem solid #dfdfdf;
        }
        .margin-bottom-2{
            margin-bottom: 2rem;
        }
        .center-display{
            display: inline-block;
            text-align: center;
            width: 100%;
        }
        .pre-estados-modems{
            position: absolute;
            z-index: 9;
        }
        .pre-load-estados-modems{
            display: flex;
            align-items: center;
            justify-content: center;
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

@section('title-container')
    <h4 class="m-0 text-dark text-uppercase">GRÁFICAS MONITOR DE AVERÍAS POR NODO</h4> 
@endsection

@section('ruta-navegacion-container')
    @parent
     <li class="breadcrumb-item active">Gráficas Monitor de Averías por Nodo</li>
@endsection

@section('aside-right')
    {{-- Aqui el aside del lado derecho, ingresar lo que sedea mostrar--}}
@endsection

@section('content')
    @parent
      
    <div class="row">
        <div id="preloadCharger" class="width-100"></div>
        <div class="tab-content w-100" id="tabsGraficaVisorAveriasContent">
            <div class="tab-pane fade show   active" id="graficaVisorAveriasTab" role="tabpanel" aria-labelledby="graficaVisorAveriasTab-tab">
                <section  class="col-12 mx-0 px-0">
                    <div class="card-header px-2 py-1">
                        <a href="{{route('administrador')}}" class="btn btn-sm btn-outline-success mx-1"><i class="fa fa-arrow-left"></i> Atras </a>
                        <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                    </div>
                    <div class="cad">
                        <div class="card-body position-relative" id="contenedor_grafica_visor_averias_body">
                            <div class="content_table_list">
                                <section class="row my-3 py-2 content_filter_basic" id="contentFiltroGraficaVisorAverias" style="display:none">
                                            <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                                <label for="" class="col-12 col-sm-3">Jefaturas:</label>
                                                <select name="jefatura" id="jefatura" class="col-12 col-sm-9 form-control form-control-sm shadow-sm">
                                                    <option value="">Todos</option>
                                                        @forelse ($jefaturas as $jeft)
                                                            <option value="{{$jeft->jefatura}}">{{$jeft->jefatura}}</option>
                                                        @empty
                                                            
                                                        @endforelse
                                                </select>
                                            </div>
                                            <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                                <label for="" class="col-12 col-sm-3">Nodos:</label>  
                                                <select name="nodo" id="nodo" class="col-12 col-sm-9 form-control form-control-sm shadow-sm">
                                                    <option value="">Todos</option>
                                                        @forelse ($nodos as $listaNodos)
                                                            <option value="{{$listaNodos->nodo}}">{{$listaNodos->nodo}}</option>
                                                        @empty
                                                            
                                                        @endforelse
                                                </select>
                                            </div>
                                            <div class="form-group row mx-0 mb-0 px-2 col-12 col-sm-12 col-md-12 col-lg-12 justify-content-center">
                                                <a href="javascript:void(0)" class="btn btn-sm btn-primary shadow-sm w-25" id="filtroGraficaVisorAverias">Filtrar</a>
                                            </div>
                                </section>
                                <div class="buttom-flex-end" id="averiasJefTabFiltro">
                                    <div class="btn-group">
                                        <button class="btn btn-secondary btn-margin-right btn-sm btn-info filtro-grafica-visor-averias" tabindex="0" aria-controls="resultSaturacionDown" type="button" title="FILTROS EN GRAFICA VISOR AVERIAS">
                                            <span>FILTROS</span>
                                        </button> 
                                    </div>
                                </div>
                                <section  class="col-12 mx-0 px-0">
                                    <div class="card">
                                        <div id="preloadExcel" class="pre-load-estados-modems"> </div>
                                        <div id="preloadGraph" class="pre-load-flex"></div>
                                        <div class="card-body"> 
                                            <div id="contencionGraficaVisorAverias" class="row text-sm"></div>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                </section> 
            </div>
        </div>
           
    </div>

@endsection

@section('scripts-footer')   

    @if(Auth::user()->HasPermiso('submodulo.grafica-llamadas-nodos-dia.refresh'))
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

    <script src="{{ url('/js/sistema/modulos/grafica-visor-averias/reporte-averias.min.js') }}"></script>
    <script src="{{ url('/js/sistema/modulos/grafica-visor-averias/edit.min.js') }}"></script>
    <script src="{{ url('/js/sistema/modulos/grafica-visor-averias/index.min.js') }}"></script>
      
@endsection