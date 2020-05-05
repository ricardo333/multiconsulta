@extends('layouts.master')

@section('titulo_pagina_sistema', 'Mapa Llamadas Perú')
 
@section('estilos')
    <style>
    #content_mapa_call_peru {
        height: calc(100vh - 150px);
    }
     
    </style>

@endsection
@section('scripts-header')
        
@endsection
 

@section('top-left-submenus')
    @parent
    {{-- Menu Top--}}
@endsection

@section('title-container')
     <h4 class="m-0 text-dark text-uppercase">Mapa Llamadas Perú</h4> 
    
@endsection
@section('ruta-navegacion-container')
    @parent
     <li class="breadcrumb-item active">Mapa Llamadas Perú</li>
@endsection

@section('aside-right')
    {{-- Aqui el aside del lado derecho, ingresar lo que sedea mostrar--}}
@endsection

@section('content')
    @parent

    @include('administrador.modulos.mapaLlamadasPeru.partials.filtro')  

    @if(Auth::user()->HasPermiso('submodulo.mapa-llamadas-peru.refresh'))
        <script> REFRESH_PERMISO = true;</script>
        @php
            $REFRESH_PERMISO = true;
        @endphp
    @endif 

  
     
    <div class="row">

        <div class="tab-content w-100" id="tabsMapaCallPeruContent">
            <div class="tab-pane listaMapaCallPeruTotal fade show   active" id="mapaCallPeruGrafTab" role="tabpanel" aria-labelledby="mapaCallPeruGrafTab-tab">
                <section  class="col-12 mx-0 px-0">
                    <div class="card-header px-2 py-1"> 
                            <a href="{{route('administrador')}}" class="btn btn-sm btn-outline-success mx-1"><i class="fa fa-arrow-left"></i> Atras</a>
                            <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                    </div>
                    <div class="card">
                            <div class="card-body position-relative" id="contenedor_mapa_call_peru_lista_body">
                                    
                                    <section id="content_mapa_call_peru"> 
                                        
                                    </section>
                            </div>
                    </div>
                </section> 
            </div>
            <div class="tab-pane  fade " id="graficoHistoricoNivelesTab" role="tabpanel" aria-labelledby="graficoHistoricoNivelesTab-tab">
                <section  class="col-12 mx-0 px-0">
                    <div class="card-header px-2 py-1"> 
                            <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_mapaCallPTab"><i class="fa fa-arrow-left"></i> Atras Mapa</a>
                            <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                    </div>
                    <div class="card">
                            <div class="card-body position-relative" id="contenedor_grafico_hist_niveles_body">
                                    
                                    <section id="content_grafico_niveles_por_puerto"> 
                                        
                                    </section>
                            </div>
                    </div>
                </section> 
            </div>
        </div>
           
    </div>

    
@endsection

@section('scripts-footer')   

    <script>
        var INTERVAL_LOAD = null
        var ESTA_ACTIVO_REFRESH = false 
    </script>
 
    <script src="{{ url('/library/Highcharts/code/highcharts.js')}}"></script>
    <script src="{{ url('/library/Highcharts/code/modules/export-data.js')}}"></script>

    <script src="{{ url('/js/sistema/modulos/mapa-llamadas-peru/index.min.js') }}"></script>
   
@endsection