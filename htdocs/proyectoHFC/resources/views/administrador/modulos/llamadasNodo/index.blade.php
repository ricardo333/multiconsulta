@extends('layouts.master')

@section('titulo_pagina_sistema', 'Monitoreo de llamadas por Nodo')
 
@section('estilos') 
    <style>
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
        .width-100{
            width: 100%;
        }
        .enlace_desactivado {
            pointer-events: none;
            cursor: default;
        }
        .pre-load-estados-modems{
            display: flex;
            align-items: center;
            justify-content: center;
            
        }
        .pre-estados-modems{
            position: absolute;
            z-index: 999;
        }
        .formato-link{
            font-weight: 100;
            font-size: 0.7rem;
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
    <h4 class="m-0 text-dark text-uppercase">MONITOREO DE LLAMADAS POR NODO</h4> 
@endsection

@section('ruta-navegacion-container')
    @parent
     <li class="breadcrumb-item active">Llamadas por Nodo</li>
@endsection

@section('aside-right')
    {{-- Aqui el aside del lado derecho, ingresar lo que sedea mostrar--}}
@endsection

@section('content')
    @parent

    @include('administrador.modulos.llamadasNodo.partials.descargasLlamadasNodoModal')

    <div class="row">

        <div class="tab-content w-100" id="tabsLlamadasNodoContent">
            <div class="tab-pane fade show   active" id="llamadasNodoTab" role="tabpanel" aria-labelledby="llamadasNodoTab-tab">
                <input type="hidden" value="llamadasNodoTab" id="input-llamadasNodoTab">
                <section  class="col-12 mx-0 px-0">
                    <div class="card-header px-2 py-1"> 
                        <a href="{{ route('administrador') }}" class="btn btn-sm btn-outline-success mx-1"><i class="fa fa-arrow-left"></i> Atras </a>
                        <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                    </div>
                    <div class="cad">
                        <div id="errorExcel"></div>
                        <div class="card-body position-relative" id="contenedor_llamadasNodo_body">
                           
                            <section class="row w-100 my-3 mx-0 py-2 content_filter_basic justify-content-center" id="filtroContentLlamadasNodo" style="display:none">
                                <div class="form-group row mx-0 py-2 col-12 col-sm-8 text-center col-md-6 col-lg-6 justify-content-center margin-0">
                                    <label for="" class="col-sm-12 col-md-6 col-lg-6">Jefaturas:</label>
                                    <select name="listajefatura" id="listajefatura" class="col-sm-8 col-md-6 col-lg-6 form-control form-control-sm shadow-sm">
                                        <option value="">Sin Filtro</option>
                                            @forelse ($jefaturas as $jeft)
                                                <option value="{{$jeft->jefatura}}">{{$jeft->jefatura}}</option>
                                            @empty
                                            
                                            @endforelse
                                    </select>
                                </div>
                                <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 justify-content-center margin-padding-5">
                                    <a href="javascript:void(0)" class="btn btn-sm btn-primary shadow-sm w-25 margin-right-1 flex-align-justify-center" id="filtroLlamadasNodo">Filtrar</a>
                                </div>
                               
                            </section>

                            <div class="content_table_list"> 
                                <div id="preloadMaping" class="pre-load-estados-modems preloadMaping"></div>
                                <table id="resultLlamadasNodo" class="table table-hover table-bordered w-100 tableFixHead table-text-xs">
                                    <thead>
                                        <tr>
                                            <th>Jefatura</th>
                                            <th>Nodo</th>
                                            <th>Llamadas DMPE</th>
                                            <th>Trobas</th>
                                            <th>Prom x Troba</th>
                                            <th>Averias</th>
                                            <th>Ultima Llamada</th>
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

    @if(Auth::user()->HasPermiso('submodulo.llamadas-nodo.refresh'))
        <script>
            REFRESH_PERMISO = true
        </script>
    @endif
    
    <script>

        var INTERVAL_LOAD = null

        const BUTTONS_LLAMADAS_NODO =
        [
            {
                text: 'DESCARGAS',
                className: 'btn btn-sm btn-success shadow-sm',
                titleAttr: 'DESCARGAS EN LLAMADAS NODO',
                action: function ( e, dt, node, config ) {
                    //alert( 'Button Opciones GPON' );
                    //console.log("opciones:", e, dt, node, config)
                    $("#descargasLlamadasNodoModal").modal("show");
                }
            },
            {
                text: 'FILTROS',
                className: 'btn btn-sm btn-info shadow-sm',
                titleAttr: 'FILTROS EN LLAMADAS NODO',
                action: function ( e, dt, node, config ) {
                    //alert( 'Button Opciones' );
                    //console.log("opciones:", e, dt, node, config)
                    //console.log("Se deberias mostrar los filtros")
                    $("#filtroContentLlamadasNodo").slideToggle()
                }
            }
        ]

        var ESTA_ACTIVO_REFRESH = false
    
   </script>

   <script src="{{ url('/js/sistema/modulos/llamadas-nodos/index.min.js') }}"></script>
   <script src="{{ url('/js/sistema/modulos/llamadas-nodos/reporte-llamadas-nodo.min.js') }}"></script>
      
@endsection