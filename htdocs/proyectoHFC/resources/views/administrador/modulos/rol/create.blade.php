@extends('layouts.master')

@section('titulo_pagina_sistema', 'Roles - Creación')

@section('estilos')
    
@endsection
@section('scripts-header')
    
@endsection

@section('top-left-submenus')
    @parent
    {{-- Menu Top--}}
@endsection

@section('title-container')
     <h4 class="m-0 text-dark text-uppercase">Creación de Roles</h4> 
    
@endsection
@section('ruta-navegacion-container')
    @parent
     <li class="breadcrumb-item active"><a href="{{route('modulo.rol.index')}}"> Roles </a> </li>
     <li class="breadcrumb-item active">Creación</li>
@endsection

@section('aside-right')
    {{-- Aqui el aside del lado derecho, ingresar lo que sedea mostrar--}}
@endsection

@section('content')
    @parent
    
    <div class="row">
        <div class="col-12">
                <div class="card">
                    <div class="card-header px-2 py-1">
                        <a href="{{route('modulo.rol.index')}}" class="btn btn-sm btn-outline-success shadow-sm mx-1"><i class="fa fa-arrow-left"></i> Atras</a>
                    </div> 

                    <div class="card-body px-2 py-1"> 
                            <section id="form_store_load"></section>
                            <section class="form row my-2 mx-0" id="form_store_detail">
                              <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                    <label for="nombreStore" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Nombre: </label>
                                    <input type="text" name="nombreStore" id="nombreStore" class="col-sm-7 col-md-8 form-control form-control-sm shadow-sm text-uppercase validateText" autocomplete="off">
                              </div>
                              <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                    <label for="estadoStore" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Estado: </label>
                                    <select name="estadoStore" id="estadoStore" class="col-sm-7 col-md-8 form-control form-control-sm shadow-sm validateSelect" autocomplete="off">
                                            <option value="1" selected>Activo</option>
                                            <option value="0">Inactivo</option>
                                    </select>
                              </div>
                            @if (Auth::user()->tienePermisoEspecial())
                                <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                    <label for="especialStore" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Acceso Total: </label>
                                    <select name="especialStore" id="especialStore" class="col-sm-7  col-md-8 form-control form-control-sm shadow-sm validateSelect" autocomplete="off">
                                        <option value="NO" selected>NO</option>
                                        <option value="SI">SI</option>
                                    </select>
                                </div>
                                <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                    <label for="referenciaStore" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Rol Padre: </label>
                                    <select name="referenciaStore" id="referenciaStore" class="col-sm-7  col-md-8 form-control form-control-sm shadow-sm validateSelect" autocomplete="off">
                                            <option value="">SIN REFERENCIA</option>
                                        @forelse ($rolesDisponibles->getData()->response->data as $rol)
                                            <option value="{{$rol->identificador}}">{{$rol->rol}}</option>
                                        @empty
                                            
                                        @endforelse
                                        
                                    </select>
                                </div>
                            @endif

                            <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-12 col-lg-12">
                                    <label for="referenciaStore" class="col-form-label col-form-label-sm mb-0 px-0">Permisos del nuevo Rol: </label>
                                    <div class="col-12 p-0 errors" id="rpta_store_checked_permisos">

                                    </div>
                                    <div class="col-12 p-0" id="storeModulosAndPermisosList">
                                        
                                    </div> 
                            </div> 
                               
 
                              <div class="form-group row justify-content-center mx-0 px-2 col-12 errors_message" id="errors_store">
                                    
                              </div>

                              <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-12 col-lg-12 d-flex justify-content-center">
                                   <a href="javascript:void(0)" class="btn btn-sm btn-outline-primary shadow-sm" id="crearRol">Crear Rol</a>
                              </div>
                              
                            </section>
                    </div>
                </div>
        </div>
    </div>
@endsection

@section('scripts-footer')  
    @php
        $lista_modulos = $modulos->getData();
        $lista_permisos_rol = $permisosRol->getData(); 
        $lista_permisos_user = $permisosUser->getData(); 
    @endphp
    <script>
        var MODULOS = {!! json_encode($lista_modulos) !!};
        var PERMISOS_ROL = {!! json_encode($lista_permisos_rol) !!}; 
        var PERMISOS_USER = {!! json_encode($lista_permisos_user) !!}; 
        const MODULOS_AUTH = MODULOS
        const PERMISOS_ROL_AUTH = PERMISOS_ROL
        const PERMISOS_USER_AUTH = PERMISOS_USER
    </script>

    <script src="{{ asset('js/sistema/modulos/roles/store.min.js') }}"></script>
    
    @if (Auth::user()->tienePermisoEspecial())
        <script src="{{ asset('js/sistema/modulos/roles/store-admin.min.js') }}"></script>
    @endif
@endsection