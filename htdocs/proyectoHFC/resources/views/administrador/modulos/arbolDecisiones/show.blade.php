@extends('layouts.master')

@section('titulo_pagina_sistema', 'Arbol de Decisiones - Detalle')

@section('estilos')
    <link rel="stylesheet" href="{{ url('/css/modulos/arbol-decisiones-detalles.css')}}">
@endsection
@section('scripts-header')
 
@endsection

@section('top-left-submenus')
    @parent
    {{-- Menu Top--}}
@endsection

@section('title-container')
     <h4 class="m-0 text-dark text-uppercase">Detalle de Arbol de Decisiones</h4> 
    
@endsection
@section('ruta-navegacion-container')
    @parent
     <li class="breadcrumb-item active"><a href="{{route('modulo.arbol-decision.index')}}"> Arbol Decisiones </a> </li>
     <li class="breadcrumb-item active">Detalle</li>
@endsection

@section('aside-right')
    {{-- Aqui el aside del lado derecho, ingresar lo que sedea mostrar--}}
@endsection
 
@section('content')
    @parent
     
    @include('administrador.modulos.arbolDecisiones.partials.editarDecisionEstructuraModal')
    @include('administrador.modulos.arbolDecisiones.partials.storeEstructuraModal')
     
    <div class="row">
        <div class="tab-content w-100" id="tabsArbolDecisiones">
            <div class="tab-pane fade show   active" id="listaDecisionArbolTab" role="tabpanel" aria-labelledby="listaDecisionArbolTab-tab">
                <div class="col-12 mx-0 px-0">
                        <div class="card">
                            <div class="card-header px-2 py-1">
                                <a href="{{route('modulo.arbol-decision.index')}}" class="btn btn-sm btn-outline-success mx-1"><i class="fa fa-arrow-left"></i> Atras</a>
                                @if( Auth::user()->HasPermiso('submodulo.arbol-decision.rama.store')) 
                                    <a href="javascript:void(0)" class="btn btn-sm btn-outline-primary shadow-sm" id="openFormatStoreBrotherRama"
                                        data-uno="{{$nombreTabla}}" data-dos="{{$tablaAnterior}}" data-tres="{{$paso}}">
                                        Crear  <i class="icofont-ui-add"></i>
                                    </a>
                                @endif
                                <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                            </div> 
                            <div class="card-body px-2 py-1"> 
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered w-auto m-auto" id="dataDecisionArbol">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>DETAlLE</th>
                                                <th>IMAGEN TOTAL</th>
                                                <th>IMAGEN NEGOCIO</th>
                                                <th>IMAGEN MASIVO</th>
                                                <th>ACCIÓN</th>
                                            </tr>
                                        </thead>
                                        <tbody id="cargaListDetalleRamas">
                                            @if ($cantidad == 0)
                                                <tr>
                                                    <td colspan="6">
                                                        No se encontrarón datos en el este paso del arbol de decisiones.
                                                    </td>
                                                </tr>
                                            @else
                                                @foreach ($list as $item)
                                                    <tr>
                                                        <td>{{$item->id}}</td>
                                                        <td>{{$item->detalle}}</td>
                                                        <td>
                                                            <img src="/images/upload/arbol-decisiones/{{isset($item->img_total)?$item->img_total : 'sinimagen.png'}}" alt="" class="img-thumbnail-arbolPasos">
                                                        </td>
                                                        <td>
                                                            <img src="/images/upload/arbol-decisiones/{{isset($item->img_negocios)?$item->img_negocios : 'sinimagen.png'}}" alt="" class="img-thumbnail-arbolPasos">
                                                        </td>
                                                        <td>
                                                            <img src="/images/upload/arbol-decisiones/{{isset($item->img_masivo)?$item->img_masivo : 'sinimagen.png'}}" alt="" class="img-thumbnail-arbolPasos">
                                                        </td> 
                                                        <td>
                                                            <div class="d-flex justify-content-center">
                                                                @if( Auth::user()->HasPermiso('submodulo.arbol-decision.edit'))
                                                                    <a href="javascript:void(0)" class="btn btn-sm btn-outline-primary shadow-sm mx-1 editDecisionArbol" 
                                                                                data-uno="{{$item->id}}" data-dos="{{$item->detalle}}" data-tres="{{$item->img_total}}"
                                                                                data-cuatro="{{$item->img_negocios}}" data-cinco="{{$item->img_masivo}}" data-seis="{{$nombreTabla}}">
                                                                        <i class="icofont-edit-alt icofont-md"></i>
                                                                    </a>
                                                                @endif
                                                                @if( Auth::user()->HasPermiso('submodulo.arbol-decision.rama.estructura')) 
                                                                    <a href="javascript:void(0)" class="btn btn-sm btn-outline-success shadow-sm mx-1 estructuraDecisionArbol" 
                                                                                data-uno="{{$item->id}}" data-dos="{{$item->detalle}}"
                                                                                data-tres="{{$nombreTabla}}">
                                                                        <i class="icofont-tree icofont-md"></i>
                                                                    </a>
                                                                @endif
                                                            </div>
                                                        </td> 
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
            
            <div class="tab-pane fade" id="estructuraDecisionesTab" role="tabpanel" aria-labelledby="estructuraDecisionesTab-tab">
                <div class="col-12 mx-0 px-0">
                    <div class="card">
                        <div class="card-header px-2 py-1">
                                <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_listaDecisionesTab"><i class="fa fa-arrow-left"></i> Atras</a>
                                <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                        </div>
                        <div class="card-body px-2 py-1">
                            <div class="col-12 mx-0 px-0 m-auto" id="preloadEstructuraRamas"></div>
                            <div id="resultadoEstructuraCompleta"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="otro" role="tabpanel" aria-labelledby="otro-tab">
                <div class="col-12 mx-0 px-0">
                    <div class="card">
                        <div class="card-header px-2 py-1">
                                <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_listaDecisionesTab"><i class="fa fa-arrow-left"></i> Atras</a>
                                <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                        </div>
                        <div class="card-body px-2 py-1">

                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </div>
@endsection
 

@section('scripts-footer') 

        <script>
            var PERMISO_EDIT = false
            var PERMISO_CREATE = false
            var PERMISO_DELETE = false
            var PERMISO_ESTRUCTURA = false
        </script>

        <script src="{{ asset('js/sistema/modulos/arbol-decisiones/show.min.js') }}"></script>
 
        @if( Auth::user()->HasPermiso('submodulo.arbol-decision.rama.estructura')) 
            <script>
                var PERMISO_ESTRUCTURA = true
            </script>
        @endif

        @if( Auth::user()->HasPermiso('submodulo.arbol-decision.rama.edit'))
            <script src="{{ asset('js/sistema/modulos/arbol-decisiones/edit.min.js') }}"></script>
            <script>
                var PERMISO_EDIT = true
            </script>
        @endif 
        @if( Auth::user()->HasPermiso('submodulo.arbol-decision.rama.delete')) 
            <script>
                var PERMISO_DELETE = true
            </script>
            <script src="{{ asset('js/sistema/modulos/arbol-decisiones/delete.min.js') }}"></script>
        @endif
        @if( Auth::user()->HasPermiso('submodulo.arbol-decision.rama.store')) 
            <script>
                var PERMISO_CREATE = true
            </script>
             <script src="{{ asset('js/sistema/modulos/arbol-decisiones/store.min.js') }}"></script>
        @endif
        @if( Auth::user()->HasPermiso('submodulo.arbol-decision.rama.estructura')) 
            <script src="{{ asset('js/sistema/modulos/arbol-decisiones/estructura.min.js') }}"></script>
        @endif

         

@endsection