@extends('layouts.master')

@section('titulo_pagina_sistema', 'Roles - Edición')

@section('estilos')
    
@endsection
@section('scripts-header')
    
@endsection

@section('top-left-submenus')
    @parent
    {{-- Menu Top--}}
@endsection

@section('title-container')
     <h4 class="m-0 text-dark text-uppercase">Edición de Roles</h4> 
    
@endsection
@section('ruta-navegacion-container')
    @parent
     <li class="breadcrumb-item active"><a href="{{route('modulo.rol.index')}}"> Roles </a> </li>
     <li class="breadcrumb-item active">Edición</li>
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
                        <div id="form_update_load"></div>
                        <section class="form row my-2 mx-0" id="form_update_detail">
                            @php $rolUpdate = $rol->getData(); @endphp 
 
                            <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                <label for="nombreUpdate" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Nombre: </label>
                                <input type="text" name="nombreUpdate" id="nombreUpdate" value="{{ $rolUpdate->response->data->rol }}" class="col-sm-7 col-md-8 form-control form-control-sm shadow-sm  text-uppercase validateText" autocomplete="off">
                                <input type="hidden" name="idUpdate" id="idUpdate" value="{{ $rolUpdate->response->data->identificador }}" class="col-sm-7 col-md-8 form-control form-control-sm shadow-sm">
                            </div>
                            <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                <label for="estadoUpdate" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Estado: </label>
                                <select name="estadoUpdate" id="estadoUpdate" class="col-sm-7 col-md-8 form-control form-control-sm shadow-sm validateSelect" autocomplete="off">
                                    <option value="1" {{ $rolUpdate->response->data->estado == "Activo"? "selected" : "" }}>Activo</option>
                                    <option value="0" {{ $rolUpdate->response->data->estado == "Inactivo"? "selected" : "" }}>Inactivo</option>
                                </select>
                            </div>
                            @if (Auth::user()->tienePermisoEspecial())
                            <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                <label for="especialUpdate" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Acceso Total: </label>
                                <select name="especialUpdate" id="especialUpdate" class="col-sm-7  col-md-8 form-control form-control-sm shadow-sm validateSelect" autocomplete="off">
                                    <option value="NO" {{ $rolUpdate->response->data->esAdministrador == "false"? "selected" : "" }}>NO</option>
                                    <option value="SI" {{ $rolUpdate->response->data->esAdministrador == "true"? "selected" : "" }}>SI</option>
                                </select>
                            </div>
                            <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                <label for="referenciaUpdate" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Rol Padre: </label>
                                <select name="referenciaUpdate" id="referenciaUpdate" class="col-sm-7  col-md-8 form-control form-control-sm shadow-sm validateSelect" autocomplete="off">
                                    <option value="">SIN REFERENCIA</option>
                                    @forelse ($roles->getData()->response->data as $rol) 
                                        <option value="{{$rol->identificador}}" 
                                                {{$rol->identificador == $rolUpdate->response->data->rolPadre ? "selected" : ""}}>
                                            {{$rol->rol}}
                                        </option>
                                    @empty
                                        
                                    @endforelse
                                    
                                </select>
                            </div>
                            @endif

                            <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-12 col-lg-12">
                                <label for="referenciaUpdate" class="col-form-label col-form-label-sm mb-0 px-0">Permisos del Rol: </label>
                                <div class="col-12 p-0 errors" id="rpta_update_checked_permisos">

                                </div>
                                <div class="col-12 p-0" id="updateModulosAndPermisosList">
                                    
                                </div> 
                            </div> 
                              
                               
                            <div class="form-group row justify-content-center mx-0 px-2 col-12 errors_message" id="errors_update">
                                
                            </div>
                              <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-12 col-lg-12 d-flex justify-content-center">
                                   <a href="javascript:void(0)" class="btn btn-sm btn-outline-primary shadow-sm" id="actualizarRol">Actualizar Rol</a>
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
        $lista_permisos_rol = $permisos->getData(); 
        $permisos_Checked = $permisosChecked->getData(); 
       
    @endphp
    <script>
        var MODULOS = {!! json_encode($lista_modulos) !!};
        var PERMISOS_ROL = {!! json_encode($lista_permisos_rol) !!}; 
        var PERMISOS_USER = {!! json_encode($lista_permisos_rol) !!}; 
        var PERMISOS_CHECKED = {!! json_encode($permisos_Checked) !!}; 
        const MODULOS_AUTH = MODULOS
        const PERMISOS_ROL_AUTH = PERMISOS_ROL 
    </script>
 
    <script src="{{ asset('js/sistema/modulos/roles/edit.min.js') }}"></script>
    

    @if (Auth::user()->tienePermisoEspecial())
        <script src="{{ asset('js/sistema/modulos/roles/edit-admin.min.js') }}"></script>
    @endif
 
@endsection