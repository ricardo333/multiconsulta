@extends('layouts.master')

@section('titulo_pagina_sistema', 'Conteo de Modems')
 
@section('estilos') 
    <style>
        .dt-buttons {
            opacity: 0;
        }
        .enlace_desactivado {
            pointer-events: none;
            cursor: default;
        }
        .pre-estados-modems{
            position: absolute;
            z-index: 9;
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

@section('title-container')
    <h4 class="m-0 text-dark text-uppercase">MONITOR CCM1</h4> 
@endsection

@section('ruta-navegacion-container')
    @parent
     <li class="breadcrumb-item active">Monitor CCM1</li>
@endsection

@section('aside-right')
    {{-- Aqui el aside del lado derecho, ingresar lo que sedea mostrar--}}
@endsection

@section('content')

    <div class="row">

        <div class="tab-content w-100" id="tabsConteoModems">
            <div class="tab-pane conteoModems fade show   active" id="ConteoModemsTab" role="tabpanel" aria-labelledby="ConteoModemsTab-tab">
                <input type="hidden" value="ConteoModemsTab" id="input-ConteoModemsTab">
                <section  class="col-12 mx-0 px-0">
                    <div class="card-header px-2 py-1"> 
                        <a href="{{ route('administrador') }}" class="btn btn-sm btn-outline-success mx-1"><i class="fa fa-arrow-left"></i> Atras </a>
                        <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                    </div>
                    <div class="cad">
                        <div id="errorExcel"></div>
                        <div class="card-body position-relative" id="contenedor_conteoModems_body">
                            <div class="content_table_list">
                                <table id="resultConteoModems" class="table table-hover table-bordered w-100 tableFixHead table-text-xs">
                                    <thead>
                                        <tr> 
                                            <th>CMTS</th>
                                            <th>Interface</th>
                                            <th>Descripcion</th>
                                            <th>Sincronizados</th>
                                            <th>Offline</th>
                                            <th>Total</th>
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

    @if(Auth::user()->HasPermiso('submodulo.conteo-modems.refresh'))
        <script>
            REFRESH_PERMISO = true
        </script>
    @endif

    <script>

        var INTERVAL_LOAD = null
        var ESTA_ACTIVO_REFRESH = false
    
    </script>

    <script src="{{ url('/js/sistema/modulos/conteo-modems/index.min.js') }}"></script>
      
@endsection