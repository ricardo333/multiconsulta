@extends('layouts.master')

@section('titulo_pagina_sistema', 'Contención Llamadas')
 
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
    <h4 class="m-0 text-dark text-uppercase">CONTENCIÓN LLAMADAS</h4> 
@endsection

@section('ruta-navegacion-container')
    @parent
     <li class="breadcrumb-item active">Contención Llamadas</li>
@endsection

@section('aside-right')
    {{-- Aqui el aside del lado derecho, ingresar lo que sedea mostrar--}}
@endsection

@section('content')
    @parent

    @include('administrador.modulos.contencionLlamadas.partials.descargasModalContencionLlamadas')
      
    <div class="row">
        <div id="preloadCharger" class="width-100"></div>
        <div class="tab-content w-100" id="tabsContencionLlamadasContent">
            <div class="tab-pane fade show   active" id="contencionLlamadasTab" role="tabpanel" aria-labelledby="contencionLlamadasTab-tab">
                <section  class="col-12 mx-0 px-0">
                    <div class="card-header px-2 py-1">
                        <a href="{{route('administrador')}}" class="btn btn-sm btn-outline-success mx-1"><i class="fa fa-arrow-left"></i> Atras </a>
                        <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                    </div>
                    <div class="cad"> 
                        <div id="errorExcel"></div>
                        <div class="card-body position-relative" id="contenedor_contencion_llamadas_body">
                            <div class="content_table_list">
                                <div class="buttom-flex-end" id="contencionLlamadasTabFiltro">
                                    <div class="btn-group">
                                        <button class="btn btn-secondary btn-sm btn-success shadow-sm modal-contencion-llamadas-descargar" tabindex="0" aria-controls="resultContencionLlamadas" type="button" title="DESCARGAR CONTENCION LLAMADAS">
                                            <span>DESCARGAR</span>
                                        </button> 
                                    </div>
                                </div>
                                <section  class="col-12 mx-0 px-0">
                                    <div class="card">
                                        <div id="preloadGraph" class="pre-load-flex"></div>
                                        <div class="card-body"> 
                                            <div id="contencionLlamadasGrafico" class="row text-sm"></div>
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

    @if(Auth::user()->HasPermiso('submodulo.contencion-llamadas.refresh'))
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

    <script src="{{ url('/js/sistema/modulos/contencion-llamadas/reporte-contencion-llamadas.min.js') }}"></script>
    <script src="{{ url('/js/sistema/modulos/contencion-llamadas/index.min.js') }}"></script>
      
@endsection