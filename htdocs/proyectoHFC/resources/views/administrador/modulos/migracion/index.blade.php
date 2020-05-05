@extends('layouts.master')

@section('titulo_pagina_sistema', 'Migracion')
 
@section('estilos') 
    <style>
        .content_filter_basic {
            border: 1px solid rgba(192, 200, 208, 0.84);
            border-radius: 5px;
        }

        .buttom-flex{
            display: flex;
            justify-content: flex-end;
            padding: 1em;
        }

        #content {
            align-items: center;

        }

    </style>
@endsection

@section('scripts-header')
    <script>
        
    </script>
@endsection

@section('top-left-submenus')
    @parent
    {{-- Menu Top--}}
@endsection

@section('title-container')
     <h4 class="m-0 text-dark text-uppercase">Migracion de Usuarios</h4> 
    
@endsection

@section('ruta-navegacion-container')
    @parent
     <li class="breadcrumb-item active">Migracion de Usuarios</li>
@endsection

@section('aside-right')
    {{-- Aqui el aside del lado derecho, ingresar lo que sedea mostrar--}}
@endsection

@section('content')
    @parent

    <div class="row">

        <input type="hidden" id="controlMigracion" value="{{$control}}">

        @if ($control=="0")
            <div id="content">
                <input type="button" id="migrar" value="Iniciar">
            </div>
        @endif
        
        <div id="result">
            <div id="precarga"></div>

            <div id="resultado_migracion"></div>
        </div>
        
           
    </div>
    
@endsection

@section('scripts-footer')  

    <script src="{{ url('/js/sistema/modulos/migracion/index.min.js') }}"></script>
      
@endsection