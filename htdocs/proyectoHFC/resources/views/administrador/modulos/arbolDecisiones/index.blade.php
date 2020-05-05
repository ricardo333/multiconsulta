@extends('layouts.master')

@section('titulo_pagina_sistema', 'Arbol de Decisiones')
 
@section('estilos')
    
@endsection
@section('scripts-header')

@endsection

@section('top-left-submenus')
    @parent
    {{-- Menu Top--}}
@endsection

@section('title-container')
     <h4 class="m-0 text-dark text-uppercase">Arbol Decisiones</h4> 
    
@endsection
@section('ruta-navegacion-container')
    @parent
     <li class="breadcrumb-item active">Arbol Decisiones</li>
@endsection

@section('aside-right')
    {{-- Aqui el aside del lado derecho, ingresar lo que sedea mostrar--}}
@endsection

@section('content')
    @parent

     
    <div class="row">
    
        <section  class="col-12 mx-0 px-0">
            <div class="card-header px-2 py-1"> 
                <a href="{{route('administrador')}}" class="btn btn-sm btn-outline-success mx-1"><i class="fa fa-arrow-left"></i> Atras</a>
            </div>
            <div class="cad"> 
                <div class="card-body" id="contenedor_arbolDecisiones_body">
                        <div class="h5 text-center d-block ">Detalle de Pasos Generales</div>
                        <div class="content_table_list"> 
                            <table id="resultListaArbolDecisiones" class="table table-hover table-bordered w-100 tableFixHead">
                                <thead>
                                    <tr>  
                                        <th>ID</th>
                                        <th>Detalle</th> 
                                        @if(  Auth::user()->HasPermiso('submodulo.arbol-decision.pasos.show') )
                                            <th>Acciones</th>
                                            <script> 
                                                var COLUMNS_LIST_PASOS = [
                                                                            {data:'id'},
                                                                            {data:'detalle'},
                                                                            {data:'btn'}
                                                                        ]
                                            </script>
                                        @else 
                                            <script> 
                                                var COLUMNS_LIST_PASOS = [
                                                                            {data:'id'},
                                                                            {data:'detalle'}
                                                                        ]
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

    
@endsection

@section('scripts-footer')  
    <script src="{{ url('/js/sistema/modulos/arbol-decisiones/index.min.js') }}"></script>
@endsection