@extends('layouts.master')

@section('titulo_pagina_sistema', 'Dashboard')

@section('estilos')
    
@endsection
@section('scripts-header')
    
@endsection

@section('top-left-submenus')
    @parent 
   {{-- <li class="nav-item d-none d-sm-inline-block">
        <a href="javascript:void(0)" class="nav-link">Home</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
        <a href="javascript:void(0)" class="nav-link">Contact</a>
    </li>--}}
@endsection

@section('title-container')
    {{--<h3 class="m-0 text-dark">Dashboard</h3>--}}
    <input type="text" id="filter_modulos" name="filter_modulos" class="form-control form-control-sm shadow-sm">
@endsection
@section('ruta-navegacion-container')
    @parent
    {{--<li class="breadcrumb-item active">Usuarios</li>--}}
@endsection

@section('aside-right')
    {{-- Aqui el aside del lado derecho, ingresar lo que sedea mostrar--}}
     
@endsection

@section('content')
    @parent
    @if (isset($anuncio))
        @include("administrador.globals.modals.anuncioPassword")
    @endif

    <div class="row col-12 px-0 mx-auto" id="listModulos">
        
    </div>
 
@endsection

@section('scripts-footer')
    <script src="{{asset('js/sistema/administrador/index.js')}}"></script>
    @if (isset($anuncio))
        <script>
            $(function(){
                $("#anuncioPassword").modal("show")
            })
        </script>
    @endif
   
@endsection