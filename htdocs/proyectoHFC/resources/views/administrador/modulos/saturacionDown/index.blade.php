@extends('layouts.master')

@section('titulo_pagina_sistema', 'Saturación Down')
 
@section('estilos') 
    <style>
        #mapa_content_carga, #mapa_call_content_carga{
                height: calc(100vh - 150px);
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
        .pre-load-saturacion-down{
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
@endsection

@section('scripts-header')
    <script>
        var GESTION_PERMISO = false
        var DIAGNOSTICOM_PERMISO = false
        var VER_CRITICOS_PERMISO = false
        var REFRESH_PERMISO = false
    </script>
@endsection

@section('top-left-submenus')
    @parent
    {{-- Menu Top--}}
@endsection

@section('title-container')
    <h4 class="m-0 text-dark text-uppercase">MONITOREO DE PUERTOS DOWN SATURADOS</h4> 
@endsection

@section('ruta-navegacion-container')
    @parent
     <li class="breadcrumb-item active">Saturación Down</li>
@endsection

@section('aside-right')
    {{-- Aqui el aside del lado derecho, ingresar lo que sedea mostrar--}}
@endsection

@section('content')
    @parent

    <div class="row">

        <div class="tab-content w-100" id="tabsSaturacionDown">
            <div class="tab-pane saturacionDown fade show   active" id="saturacionDownTab" role="tabpanel" aria-labelledby="saturacionDownTab-tab">
                <input type="hidden" value="saturacionDownTab" id="input-saturacionDownTab">
                <section  class="col-12 mx-0 px-0">
                    <div class="card-header px-2 py-1"> 
                        @if (isset($motivo) && $motivo=="cuadroMando")
                            <a href="{{route('modulo.cuadro-mando.index')}}" class="btn btn-sm btn-outline-success mx-1"><i class="fa fa-arrow-left"></i> Atras Cuadro Mando</a>
                        @else
                            <a href="{{ route('administrador') }}" class="btn btn-sm btn-outline-success mx-1"><i class="fa fa-arrow-left"></i> Atras</a>
                        @endif
                        <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                    </div>
                    <div class="cad">
                        <div class="card-body position-relative" id="contenedor_saturacionDown_body">
                            <section class="row w-100 my-3 mx-0 py-3 content_filter_basic justify-content-center" id="filtroContentSaturacionDown" style="display:none">
                                <div class="form-group row mx-0 py-2 col-12 col-sm-8 text-center col-md-6 col-lg-6 justify-content-center margin-0">
                                    @if (isset($motivo) && $motivo=="cuadroMando")
                                        <input type="hidden" id="filtroCuadroMando" value="{{$nodo}}">
                                    @endif
                                    <label for="" class="col-sm-12 col-md-6 col-lg-6">Elija una CMTS:</label>
                                    <select name="listaPuertosSaturacionDown" id="listaPuertosSaturacionDown" class="col-sm-8 col-md-6 col-lg-6 form-control form-control-sm shadow-sm">
                                        <option value="">Sin Filtro</option>
                                            @forelse ($saturacionDown as $satdown)
                                                <option value="{{$satdown->cmts}}">{{$satdown->cmts}}</option>
                                            @empty
                                                    
                                            @endforelse
                                    </select>
                                </div>
                                <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 justify-content-center margin-padding-5">
                                    <a href="javascript:void(0)" class="btn btn-sm btn-primary shadow-sm w-25 margin-right-1 flex-align-justify-center" id="filtroSaturacionDown">Filtrar</a>
                                </div>
                            </section>

                            <div class="content_table_list"> 
                                <table id="resultSaturacionDown" class="table table-hover table-bordered w-100 tableFixHead table-text-xs">
                                    <thead>
                                        <tr>
                                            <th>Item</th>
                                            <th>Cmts</th>
                                            <th>Down</th>
                                            <th>Portadoras</br>(en uso)</th>
                                            <th>Rango</th>
                                            <th>Fecha-Ini</th>
                                            <th>Impacto</br>NoClientes</th>
                                            <th>Trobas comprometidas</th>
                                            <th>Archivo</th>
                                            <th>Link</th>
                                        </tr>
                                    </thead>  
                                </table>
                            </div>
                        </div>
                    </div>
                </section> 
            </div>
            <div class="tab-pane fade " id="graficoSaturacionDownsTab" role="tabpanel" aria-labelledby="graficoSaturacionDownsTab-tab">
                    <section  class="col-12 mx-0 px-0">
                        <div class="card">
                            <div class="card-header px-2 py-1">
                                <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_saturacion_down"><i class="fa fa-arrow-left"></i> Atras Saturación Down</a>
                                <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                            </div>
                            <div class="card-body">
                                <div id="resultGraficoDown"></div>
                            </div>
                        </div>
                    </section>
            </div>
            <script src="{{ url('/js/sistema/modulos/saturacion-down/grafico.min.js') }}"></script>
            
        </div>
           
    </div>
@endsection

@section('scripts-footer')   

    @if(Auth::user()->HasPermiso('submodulo.saturacion-down.refresh'))
        <script>
            REFRESH_PERMISO = true
        </script>
    @endif
    


    @if (isset($motivo) && $motivo=="cuadroMando")
    <script>

        var INTERVAL_LOAD = null

        const BUTTONS_CAIDAS_MASIVAS =
        []

        var ESTA_ACTIVO_REFRESH = false

    </script>
    @else
    <script>

        var INTERVAL_LOAD = null

        const BUTTONS_CAIDAS_MASIVAS =
        [
            {
                text: 'FILTROS',
                className: 'btn btn-sm btn-info shadow-sm',
                titleAttr: 'FILTROS EN SATURACION DOWN',
                action: function ( e, dt, node, config ) {
                    $("#filtroContentSaturacionDown").slideToggle()
                }
            }
        ]

        var ESTA_ACTIVO_REFRESH = false

   </script>
   @endif








    <script src="{{ url('/library/Highcharts/code/highcharts.js')}}"></script>
    <script src="{{ url('/library/Highcharts/code/modules/export-data.js')}}"></script>

    <script src="{{ url('/js/sistema/modulos/saturacion-down/index.min.js') }}"></script>
    <script src="{{ url('/js/sistema/modulos/saturacion-down/descarga.min.js') }}"></script>
      
@endsection