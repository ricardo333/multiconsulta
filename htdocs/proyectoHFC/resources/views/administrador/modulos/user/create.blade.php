@extends('layouts.master')

@section('titulo_pagina_sistema', 'Usuarios - Creación')

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
     <h4 class="m-0 text-dark text-uppercase">Creación de Usuario</h4> 
    
@endsection
@section('ruta-navegacion-container')
    @parent
     <li class="breadcrumb-item active"><a href="{{route('modulo.usuario.index')}}"> Usuarios </a> </li>
     <li class="breadcrumb-item active">Creación</li>
@endsection

@section('aside-right')
    {{-- Aqui el aside del lado derecho, ingresar lo que sedea mostrar--}}
@endsection

@section('content')
    @parent
    @include("administrador.modulos.user.partials.addPermisosModal")
  
    <div class="row">
        <div class="col-12">
                <div class="card">
                    <div class="card-header px-2 py-1">
                        <a href="{{route('modulo.usuario.index')}}" class="btn btn-sm btn-outline-success shadow-sm mx-1"><i class="fa fa-arrow-left"></i> Atras</a>
                    </div> 

                    <div class="card-body px-2 py-1"> 
                            <section id="form_store_load"></section>
                          <section class="form row my-2 mx-0" id="form_store_detail">
                              <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                    <label for="nombreStore" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Nombre: </label>
                                    <input type="text" name="nombreStore" id="nombreStore" class="col-sm-7 col-md-8 form-control form-control-sm shadow-sm validateText" autocomplete="off" >
                              </div>
                              <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                    <label for="apellidosStore" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Apellidos: </label>
                                    <input type="text" name="apellidosStore" id="apellidosStore" class="col-sm-7  col-md-8 form-control form-control-sm shadow-sm validateText" autocomplete="off">
                              </div>
                              <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                    <label for="documentoStore" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">DNI: </label>
                                    <input type="text" name="documentoStore" id="documentoStore" class="col-sm-7  col-md-8 form-control form-control-sm shadow-sm validateText" autocomplete="off">
                              </div>
                              <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                    <label for="celularStore" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Celular: </label>
                                    <input type="text" name="celularStore" id="celularStore" class="col-sm-7  col-md-8 form-control form-control-sm shadow-sm validateText" autocomplete="off">
                              </div>
                              <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                    <label for="correoStore" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Correo: </label>
                                    <input type="text" name="correoStore" id="correoStore" class="col-sm-7  col-md-8 form-control form-control-sm shadow-sm validateText" autocomplete="off">
                              </div>
  
                               
                               <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                    <label for="empresaStore" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Empresa: </label>
                                    @php
                                       $lista_empresa = $empresas->getData();
                                       $data_empresa = $lista_empresa->response->data;
                                    @endphp
                                    <select name="empresaStore" id="empresaStore" class="col-sm-7  col-md-8 form-control form-control-sm shadow-sm validateSelect" autocomplete="off">
                                            <option value="seleccionar">Seleccionar</option>
                                        @foreach ($data_empresa as $empresa)
                                            <option value="{{$empresa->identificador}}">{{$empresa->empresa}}</option>
                                        @endforeach
                                    </select>
                              </div>
                               <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                    <label for="rolStore" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Rol: </label>
                                    @php
                                        $lista_roles = $roles->getData();
                                        $data_rol = $lista_roles->response->data;
                                    @endphp
                                    <select name="rolStore" id="rolStore" class="col-sm-6  col-md-8 form-control form-control-sm shadow-sm validateSelect" autocomplete="off">
                                        <option value="seleccionar">Seleccionar</option>
                                        @foreach ($data_rol as $rol)
                                            <option value="{{$rol->identificador}}">{{$rol->rol}}</option>
                                        @endforeach
                                    </select>  
                              </div> 

                              <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6">
                                    <div class="input-group">  
                                            <label for="permisosUsuarioStore" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Permisos: </label>
                                        <span class="input-group-btn col-sm-6  col-md-8 p-0">
                                            <a href="javascript: void(0)" id="activarModalPermisos" class="btn btn-outline-success btn-sm shadow-sm w-100" >Agregar nuevos permisos <i class="fa fa-plus"></i></a>
                                        </span>
                                    </div> 
                              </div> 
   
                               
                              <div class="form-group row justify-content-center mx-0 px-2 col-12 errors_message" id="errors_store">
                                    
                              </div>

                              <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-12 col-lg-12 d-flex justify-content-center">
                                   <a href="javascript:void(0)" class="btn btn-sm btn-outline-primary shadow-sm" id="crearUsuario">Crear Usuario</a>
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
    @endphp
    <script>
        const MODULOS = {!! json_encode($lista_modulos) !!};
        const PERMISOS_ROL = {!! json_encode($lista_permisos_rol) !!};
        const PERMISOS_ESPECIALES = {!! json_encode($lista_permisos_especiales) !!};
        var INICIAR_PETICION_PERMISOS_CHECK = false;
    </script>
    <script src="{{ asset('js/sistema/modulos/users/store.min.js') }}"></script>
@endsection