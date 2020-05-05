@extends('layouts.master')

@section('titulo_pagina_sistema', 'Usuarios - Edición')

@section('estilos')
<style>
        .colores_leyend {
            position: relative;
            display: inline-block;
            width: 10px;
            height: 10px;
            margin: 0 5px;
        }
        .permisosRolBack{
            background: #53d55a;
        }
        .permisosRolColor{
            color: #53d55a;
        }
        .permisosEspecialesBack{
            background: #000;
        }
        .permisosEspecialesColor{
            color: #000;
        }
    </style>
@endsection
@section('scripts-header')
    
@endsection

@section('top-left-submenus')
    @parent
    {{-- Menu Top--}}
@endsection

@section('title-container')
     <h4 class="m-0 text-dark text-uppercase">Edición de Usuario</h4> 
    
@endsection
@section('ruta-navegacion-container')
    @parent
     <li class="breadcrumb-item active"><a href="{{route('modulo.usuario.index')}}"> Usuarios </a> </li>
     <li class="breadcrumb-item active">Edición</li>
@endsection

@section('aside-right')
    {{-- Aqui el aside del lado derecho, ingresar lo que sedea mostrar--}}
@endsection
 
@section('content')
    @parent
    @include("administrador.modulos.user.partials.editPermisosModal")
  
    <div class="row">
        <div class="col-12">
                <div class="card">
                    <div class="card-header px-2 py-1">
                        <a href="{{route('modulo.usuario.index')}}" class="btn btn-sm btn-outline-success shadow-sm mx-1"><i class="fa fa-arrow-left"></i> Atras</a>
                    </div> 
                    <div class="card-body px-2 py-1"> 
                        <div id="form_update_load"></div>
                        <section class="form row my-2 mx-0" id="form_update_detail">
                               @php $usDetalle = $usuario->getData(); @endphp 
 
                              <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                    <label for="nombreUpdate" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Nombre: </label>
                                    <input type="text" name="nombreUpdate" id="nombreUpdate" value="{{ $usDetalle->response->data->nombre }}" class="col-sm-7 col-md-8 form-control form-control-sm shadow-sm validateText" autocomplete="off">
                                    <input type="hidden" name="idUpdate" id="idUpdate" value="{{ $usDetalle->response->data->identificador }}" class="col-sm-7 col-md-8 form-control form-control-sm shadow-sm">
                              </div>
                              <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                    <label for="apellidosUpdate" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Apellidos: </label>
                                    <input type="text" name="apellidosUpdate" id="apellidosUpdate" value="{{ $usDetalle->response->data->apellidos }}" class="col-sm-7  col-md-8 form-control form-control-sm shadow-sm validateText" autocomplete="off">
                              </div>
                              <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                    <label for="documentoUpdate" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">DNI: </label>
                                    <input type="text" name="documentoUpdate" id="documentoUpdate" value="{{ $usDetalle->response->data->documento }}" class="col-sm-7  col-md-8 form-control form-control-sm shadow-sm validateText" autocomplete="off">
                              </div>
                              <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                    <label for="celularUpdate" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Celular: </label>
                                    <input type="text" name="celularUpdate" id="celularUpdate" value="{{ $usDetalle->response->data->celular }}" class="col-sm-7  col-md-8 form-control form-control-sm shadow-sm validateText" autocomplete="off">
                              </div>
                              <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                    <label for="correoUpdate" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Correo: </label>
                                    <input type="text" name="correoUpdate" id="correoUpdate" value="{{ $usDetalle->response->data->correo }}" class="col-sm-7  col-md-8 form-control form-control-sm shadow-sm validateText" autocomplete="off">
                              </div>

                              <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                    <label for="estadoUpdate" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Estado: </label>
                                    <select name="estadoUpdate" id="estadoUpdate" class="col-sm-7  col-md-8 form-control form-control-sm shadow-sm" autocomplete="off">
                                        <option value="A" {{ ($usDetalle->response->data->estado) == "A" ? 'selected' : '' }}>Activo</option>
                                        <option value="I" {{ ($usDetalle->response->data->estado) == "I" ? 'selected' : '' }}>Inactivo</option>
                                    </select> 
                              </div>
                               
                               <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                    <label for="empresaUpdate" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Empresa: </label>
                                    @php
                                       $lista_empresa = $empresas->getData();
                                       $data_empresa = $lista_empresa->response->data;
                                    @endphp
                                    <select name="empresaUpdate" id="empresaUpdate" class="col-sm-7  col-md-8 form-control form-control-sm shadow-sm validateSelect" autocomplete="off">
                                            <option value="seleccionar">Seleccionar</option>
                                        @foreach ($data_empresa as $empresa)
                                            <option value="{{$empresa->identificador}}" 
                                                {{ ($empresa->identificador == $usDetalle->response->data->idenfiticadorEmpresa) ? 'selected' : ''}} >
                                                {{$empresa->empresa}}
                                            </option>
                                        @endforeach
                                    </select>
                              </div>
                               <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                    <label for="rolUpdate" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Rol: </label>
                                    @php
                                        $lista_roles = $roles->getData();
                                        $data_rol = $lista_roles->response->data;
                                    @endphp
                                    <select name="rolUpdate" id="rolUpdate" class="col-sm-6  col-md-8 form-control form-control-sm shadow-sm validateSelect" autocomplete="off">
                                        <option value="seleccionar">Seleccionar</option>
                                        @foreach ($data_rol as $rol)
                                            <option value="{{$rol->identificador}}" 
                                                    {{ ($rol->identificador == $usDetalle->response->data->identificadorRol) ? 'selected' : ''}}>
                                                    {{$rol->rol}}
                                            </option>
                                        @endforeach
                                    </select>  
                              </div> 

                              <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                    <label for="usuarioUpdate" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Usuario: </label>
                                    <div class="col-sm-7 col-md-8 font-weight-bold p-0">{{ $usDetalle->response->data->usuario }}</div>
                              </div>
                              <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                    <label for="claveUpdate" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Contraseña: </label>    
                                    <div class="input-group col-sm-7  col-md-8 p-0">  
                                        <input type="password" name="claveUpdate" id="claveUpdate" class="form-control form-control-sm shadow-sm validateText">
                                        <span class="input-group-btn">
                                            <a href="javascript: void(0)" id="verPasswordUser" class="btn btn-outline-success btn-sm shadow-sm w-100" ><i class="fa fa-eye" aria-hidden="true"></i></a>
                                        </span>
                                    </div> 
                                   
                                    
                              </div>
                              
                              <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6">
                                    <div class="input-group">  
                                            <label for="permisosUsuarioUpdate" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Permisos: </label>
                                        <span class="input-group-btn col-sm-6  col-md-8 p-0">
                                            <a href="javascript: void(0)" id="verPermisosUsuario" class="btn btn-outline-success btn-sm shadow-sm w-100" >Agregar nuevos permisos <i class="fa fa-plus"></i></a>
                                        </span>
                                    </div> 
                              </div> 
    
                            <div class="form-group row justify-content-center mx-0 px-2 col-12 errors_message" id="errors_Update">
                                
                            </div>
                              <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-12 col-lg-12 d-flex justify-content-center">
                                   <a href="javascript:void(0)" class="btn btn-sm btn-outline-primary shadow-sm" id="actualizarUsuario">Actualizar Usuario</a>
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
        $lista_permisos_especiales = $permisosEspeciales->getData();
        $lista_permisos_checked_rol = $permisosCheckedRol->getData();
        $lista_permisos_bloqueados_rol = $permisosBloqueados->getData();
        $lista_permisos_checked_user = $permisosCheckedUser->getData();
    @endphp
    <script>
        const MODULOS = {!! json_encode($lista_modulos) !!};
        const PERMISOS_ROL = {!! json_encode($lista_permisos_rol) !!};
        const PERMISOS_ESPECIALES = {!! json_encode($lista_permisos_especiales) !!};
        var PERMISOS_CHECKED_ROL = {!! json_encode($lista_permisos_checked_rol) !!};
        var PERMISOS_BLOQUEADOS_ROL = {!! json_encode($lista_permisos_bloqueados_rol) !!};
        var PERMISOS_CHECKED_USER = {!! json_encode($lista_permisos_checked_user) !!};
        var INICIAR_PETICION_PERMISOS_CHECK = false;
    </script>
    @if (Auth::user()->HasPermiso('submodulo.usuario.edit'))
        <script src="{{ asset('js/sistema/modulos/users/edit.min.js') }}"></script>
    @endif
 
@endsection