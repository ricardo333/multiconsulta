@extends('layouts.master')

@section('titulo_pagina_sistema', 'Monitor Performance')
 
@section('estilos') 
    <style>
        
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
     <select class="form-control form-control-sm shadow-sm text-success" id="display_filter_special">
        <option value="monitor_apache">Monitor Apache</option>
        <option value="monitor_guardian">Monitor Tablas Principales</option>
        <option value="monitor_bdWeb">Monitor SQL Web</option>
        <option value="monitor_bdProcesos">Monitor SQL Procesos</option>
        <option value="monitor_bdColector">Monitor SQL Colector</option>
     </select>  
@endsection


@section('ruta-navegacion-container')
    @parent
     <li class="breadcrumb-item active">Monitor Performance</li>
@endsection

@section('aside-right')
    {{-- Aqui el aside del lado derecho, ingresar lo que sedea mostrar--}}
@endsection

@section('content')
    @parent

      
    <div class="row">
        
        <div class="tab-content w-100" id="tabsPerformanceContent">

            <div class="tab-pane fade show moduloPerformance active" id="monitorPerformanceApacheTab" role="tabpanel" aria-labelledby="monitorPerformanceApacheTab-tab">
                <section  class="col-12 mx-0 px-0">
                    <div class="card-header px-2 py-1">
                        <a href="{{route('administrador')}}" class="btn btn-sm btn-outline-success mx-1"><i class="fa fa-arrow-left"></i> Atras </a>
                        <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                    </div>
                    <div class="cad"> 
                        <div id="errorExcel"></div>
                        <div class="card-body position-relative" id="contenedor_seguimiento_llamadas_body">
                            <div class="h5 text-center d-block text-danger mb-3" id="fechaApache"></div>
                            <div class="content_table_list">
                                <section  class="col-12 mx-0 px-0">
                                    <div class="card">
                                        <div id="preloadGraph" class="pre-load-flex"></div>
                                        <div class="card-body"> 
                                            <div id="monitorApacheGrafico" class="row text-sm"></div>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                </section> 
            </div>



            <div class="tab-pane moduloPerformance fade show   active" id="monitorPerformanceGuardianTab" role="tabpanel" aria-labelledby="monitorPerformanceGuardianTab-tab">
                <section  class="col-12 mx-0 px-0">
                <div class="card-header px-2 py-1">
                    <a href="{{route('administrador')}}" class="btn btn-sm btn-outline-success mx-1"><i class="fa fa-arrow-left"></i> Atras </a>
                    <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                </div>
                <div class="cad"> 
                    <div id="errorExcel"></div>
                    <div class="card-body position-relative" id="contenedor_performance_body">
                        
                        <div class="h5 text-center d-block text-danger mb-3" id="fechaGuardian"></div>
                        <div class="content_table_list"> 
                            <table id="resultPerformanceGuardian" class="table table-hover table-bordered w-100 tableFixHead table-text-xs">
                                <thead>
                                    <tr>                                      
                                        <th>ID</th>
                                        <th>TABLA</th>
                                        <th>CANT. REGISTROS</th>
                                        <th>FECHA ACTUALIZACION</th>
                                    </tr>
                                </thead>  
                            </table>
                        </div>
                    </div>
                </div>
                </section> 
            </div> 



            <div class="tab-pane moduloPerformance fade show   active" id="monitorPerformanceSQLTab" role="tabpanel" aria-labelledby="monitorPerformanceSQLTab-tab">
                <section  class="col-12 mx-0 px-0">
                <div class="card-header px-2 py-1">
                    <a href="{{route('administrador')}}" class="btn btn-sm btn-outline-success mx-1"><i class="fa fa-arrow-left"></i> Atras </a>
                    <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                </div>
                <div class="cad"> 
                    <div id="errorExcel"></div>
                    <div class="card-body position-relative" id="contenedor_performance_body">
                        
                        <div class="h5 text-center d-block text-danger mb-3" id="fechaSQL"></div>
                        <div class="content_table_list"> 
                            <table id="resultPerformanceSQL" class="table table-hover table-bordered w-100 tableFixHead table-text-xs">
                                <thead>
                                    <tr>
                                        <th>IT</th>                                      
                                        <th>ID</th>
                                        <th>DB</th>
                                        <th>COMMAND</th>
                                        <th>TIME</th>
                                        <th>STATE</th>
                                        <th>INFO</th>
                                        <th>MEMORY_USED</th>
                                        <th>KILL</th>
                                    </tr>
                                </thead>  
                            </table>
                        </div>
                    </div>
                </div>
                </section> 
            </div>
            
 
        </div>
           
    </div>

    
@endsection

@section('scripts-footer')   

    {{--@if(Auth::user()->HasPermiso('submodulo.caidas.refresh'))--}}
        <script>
            REFRESH_PERMISO = true
        </script>
    {{--@endif--}}
 
    <script>

            var INTERVAL_LOAD = null
           
            const BUTTONS_CAIDAS_MASIVAS = []
    
            var ESTA_ACTIVO_REFRESH = true
             
       </script>


    <script src="{{ url('/library/Highcharts/code/highcharts.js')}}"></script>
    <script src="{{ url('/library/Highcharts/code/modules/export-data.js')}}"></script>

    <script src="{{ url('/js/sistema/modulos/monitor-performance/index.min.js') }}"></script>
    <script src="{{ url('/js/sistema/modulos/monitor-performance/kill-process.min.js') }}"></script>
      
@endsection