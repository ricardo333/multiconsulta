@extends('layouts.master')

@section('titulo_pagina_sistema', 'Gestion Masiva - Creación')

@section('estilos')
    
@endsection
@section('scripts-header')
    
@endsection

@section('top-left-submenus')
    @parent
    {{-- Menu Top--}}
@endsection

@section('title-container')
     <h4 class="m-0 text-dark text-uppercase">Creación de Gestión Masiva</h4> 
    
@endsection
@section('ruta-navegacion-container')
    @parent 
     <li class="breadcrumb-item active">Gestión Masiva</li>
@endsection

@section('aside-right')
    {{-- Aqui el aside del lado derecho, ingresar lo que sedea mostrar--}}
@endsection

@section('content')
    @parent
   
    <div class="row">
        <div class="tab-content w-100" id="tabsGestionMasivasContent">
            <div class="tab-pane fade show   active" id="gestionMasivaMonitorAveriasTab" role="tabpanel" aria-labelledby="gestionMasivaMonitorAveriasTab-tab">
                <section  class="col-12 mx-0 px-0">
                        <div class="card">
                            <div class="card-header px-2 py-1">
                                <a href="javascript:void(0)" id="return_history"  class="btn btn-sm btn-outline-primary shadow-sm"><i class="fa fa-arrow-left"></i> Atras </a>
                                 
                            </div>
                            <div class="card-body">
                                <h4 class="w-100 text-center text-uppercase font-weight-bold text-secondary">Registro de Gestión Masiva</h4>
                               
                                        @include('administrador.partials.gestionMasivaForm')
                                        
                            </div>
                        </div>
                </section>
            </div>
             
        </div>
    </div>
@endsection




@section('scripts-footer') 
 
    <script src="{{ url('/js/sistema/modulos/gestion/gestion-masiva.min.js') }}"></script> 

@endsection