@extends('layouts.master')

@section('titulo_pagina_sistema', 'Usuarios - Detalle')

@section('estilos')
    
@endsection
@section('scripts-header')
    
@endsection

@section('top-left-submenus')
    @parent
    {{-- Menu Top--}}
@endsection

@section('title-container')
     <h4 class="m-0 text-dark text-uppercase">Detalle de Usuario</h4> 
    
@endsection
@section('ruta-navegacion-container')
    @parent
     <li class="breadcrumb-item active"><a href="{{route('modulo.usuario.index')}}"> Usuarios </a> </li>
     <li class="breadcrumb-item active">Detalle</li>
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
                        <a href="{{route('modulo.usuario.index')}}" class="btn btn-sm btn-outline-success mx-1"><i class="fa fa-arrow-left"></i> Atras</a>
                    </div> 
                    <div class="card-body px-2 py-1"> 
                        <section class="form row my-2 mx-0">
                               @php $usDetalle = $usuario->getData(); @endphp 
 
                              <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                    <label for="nombreUpdate" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Nombre: </label>
                                    {{$usDetalle->response->data->nombre}}
                              </div>
                              <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                    <label for="apellidosUpdate" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Apellidos: </label>
                                    {{$usDetalle->response->data->apellidos}}
                              </div>
                              <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                    <label for="documentoUpdate" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">DNI: </label>
                                    {{$usDetalle->response->data->documento}}
                              </div>
                              <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                    <label for="celularUpdate" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Celular: </label>
                                    {{$usDetalle->response->data->celular}}
                              </div>
                              <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                    <label for="correoUpdate" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Correo: </label>
                                    {{ $usDetalle->response->data->correo }}
                              </div>

                              <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                    <label for="estadoUpdate" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Estado: </label>
                                    {{$usDetalle->response->data->estado == "A" ? "Activo" : "Inactivo"}}
                              </div>
                               
                               <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                    <label for="empresaUpdate" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Empresa: </label>
                                     {{$usDetalle->response->data->empresa}}
                              </div>
                               <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                    <label for="rolUpdate" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Rol: </label>
                                    {{$usDetalle->response->data->rol}} 
                              </div> 

                              <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                    <label for="usuarioUpdate" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Usuario: </label>
                                    {{$usDetalle->response->data->usuario}}
                              </div>
                              <div class="form-group row mx-0 px-2 col-12">
                                    <label for="permisosUpdate" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Permisos: </label>
                                    <section class="col-12 card">
                                         <div class="card-body">
                                                @php $permisosGenerales = $permisos->getData()->response->data;@endphp 
                                                @forelse ($permisosGenerales as $permiso)
                                                            <span class="items_permisos_details">{{$permiso->descripcion}}</span>
                                                @empty
                                                      
                                                @endforelse
                                         </div>
                                    </section>   
                                    
                              </div>
                                
                              @if (Auth::user()->HasPermiso('submodulo.usuario.edit'))
                                    <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-12 col-lg-12 d-flex justify-content-center">
                                          <a href="{{ route('submodulo.usuario.edit', $usDetalle->response->data->identificador) }}" class="btn btn-outline-success btn-sm shadow-sm p-1 accionUsuarioEdit" >Editar<i class="fa fa-pencil icon-accion"></i></a>
                                    </div>
                                @endif
                              
                              
                        </section>
                    </div>
                </div>
        </div>
    </div>
@endsection

@section('scripts-footer') 
        <script src="{{ asset('js/sistema/modulos/users/show.min.js') }}"></script>
@endsection