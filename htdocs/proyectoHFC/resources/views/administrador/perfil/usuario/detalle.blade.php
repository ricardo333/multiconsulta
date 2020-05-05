@extends('layouts.master')

@php $usDetalle = $usuario->getData()->response->data; @endphp 

@section('titulo_pagina_sistema', 'Perfil')

@section('estilos')


@endsection
@section('scripts-header')

@endsection

@section('top-left-submenus')
    @parent
    {{-- Menu Top--}}
@endsection

@section('title-container')

     <h4 class="m-0 text-dark text-uppercase">Perfil {{$usDetalle->usuario}}</h4> 
    
@endsection
@section('ruta-navegacion-container')
    @parent
    <li class="breadcrumb-item active">Perfil</li>
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
                        <a href="{{route('administrador')}}" class="btn btn-sm btn-outline-success mx-1 shadow-sm"><i class="fa fa-arrow-left"></i> Atras</a>
                    </div> 
                    <div class="card-body">
                        {{-- Tabs --}}
                        <ul class="nav nav-tabs" id="perfil" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="detalle-perfil" data-toggle="tab" href="#detalleP" role="tab" aria-controls="detalleP" aria-selected="true">Detalle</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="detalle-login-fails" data-toggle="tab" href="#detalleLoginFailsP" role="tab" aria-controls="detalleLoginFailsP" aria-selected="true">Ultimos Accesos Fallidos</a>
                            </li>
                            @if ( ($usDetalle->identificador == Auth()->user()->id ) || Auth()->user()->tienePermisoEspecial())
                                <li class="nav-item">
                                    <a class="nav-link" id="edit-perfil" data-toggle="tab" href="#editP" role="tab" aria-controls="editP" aria-selected="false">Actualizar Datos</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="password-perfil" data-toggle="tab" href="#passwordP" role="tab" aria-controls="passwordP" aria-selected="false">Cambiar Contraseña</a>
                                </li>
                            @endif
                           
                            
                        </ul>
                        <div class="tab-content" id="perfilContent">
                            <div class="tab-pane fade" id="detalleLoginFailsP" role="tabpanel" aria-labelledby="detalle-login-fails">
                                <div class="card-body">
                                    <div class="form">
                                        @if (isset($ultimosErroresLogin) && count($ultimosErroresLogin) > 0)
                                            @foreach ($ultimosErroresLogin as $logins)
                                                <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                                    <div class="col-sm-7 col-md-8 form-control form-control-sm">{{ date("d-m-Y H:i:s", strtotime($logins->fecha))}}</div>
                                                </div>
                                            @endforeach
                                        @else
                                            Sin accesos errados desde su último login.  
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade show active" id="detalleP" role="tabpanel" aria-labelledby="detalle-perfil">
                                <div class="card-body">
                                    <section class="form row my-2 mx-0">
                                            
                
                                            <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                                <label for="nombreDetalle" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Nombre: </label>
                                                <div class="col-sm-7 col-md-8 form-control form-control-sm">{{$usDetalle->nombre}}</div>
                                            </div>
                                            <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                                    <label for="apellidosDetalle" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Apellidos: </label>
                                                    <div class="col-sm-7 col-md-8 form-control form-control-sm">{{$usDetalle->apellidos}}</div>
                                            </div>
                                            <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                                    <label for="documentoDetalle" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">DNI: </label>
                                                    <div class="col-sm-7 col-md-8 form-control form-control-sm">{{$usDetalle->documento}}</div>
                                            </div>
                                            <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                                    <label for="celularDetalle" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Celular: </label>
                                                    <div class="col-sm-7 col-md-8 form-control form-control-sm">{{$usDetalle->celular}}</div>
                                            </div>
                                            <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                                    <label for="correoDetalle" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Correo: </label>
                                                    <div class="col-sm-7 col-md-8 form-control form-control-sm">{{$usDetalle->correo}}</div>
                                            </div>
                 
                                            <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                                    <label for="empresaDetalle" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Empresa: </label>
                                                    <div class="col-sm-7 col-md-8 form-control form-control-sm">{{$usDetalle->empresa}}</div>
                                            </div>
                                            <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                                    <label for="rolDetalle" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Rol: </label>
                                                    <div class="col-sm-7 col-md-8 form-control form-control-sm">{{$usDetalle->rol}}</div>
                                            </div> 
                
                                            <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                                    <label for="usuarioDetalle" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Usuario: </label>
                                                    <div class="col-sm-7 col-md-8 form-control form-control-sm">{{$usDetalle->usuario}}</div>
                                            </div>

                                            <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                                    <label for="accesoUltimoDetalle" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Último Acceso Exitoso: </label>
                                                    <div class="col-sm-7 col-md-8 form-control form-control-sm">{{ date("d-m-Y H:i:s", strtotime($ultimoAcceso))}}</div>
                                                    
                                            </div>
                                              
                                    </section>
                                </div>
                            </div>
                            @if ( ($usDetalle->identificador == Auth()->user()->id ) || Auth()->user()->tienePermisoEspecial())
                                <div class="tab-pane fade" id="editP" role="tabpanel" aria-labelledby="edit-perfil">
                                    <div class="card-body">
                                        <div id="form_update_load"></div>
                                        <section class="form row my-2 mx-0" id="form_update_detail"> 
                                            <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                                    <label for="nombreUpdate" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Nombre: </label>
                                                    <div class="col-sm-7 col-md-8 form-control form-control-sm text-black-50">{{$usDetalle->nombre}}</div>
                                                    <input type="hidden" name="idUpdate" id="idUpdate" value="{{ $usDetalle->identificador }}" class="col-sm-7 col-md-8 form-control form-control-sm shadow-sm">
                                            </div>
                                            <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                                    <label for="apellidosUpdate" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Apellidos: </label>
                                                    <div class="col-sm-7 col-md-8 form-control form-control-sm text-black-50">{{$usDetalle->apellidos}}</div>
                                            </div>
                                            <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                                    <label for="documentoUpdate" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">DNI: </label>
                                                    <input type="text" name="documentoUpdate" id="documentoUpdate" value="{{ $usDetalle->documento }}" class="col-sm-7  col-md-8 form-control form-control-sm shadow-sm validateText">
                                            </div>
                                            <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                                    <label for="celularUpdate" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Celular: </label>
                                                    <input type="text" name="celularUpdate" id="celularUpdate" value="{{ $usDetalle->celular }}" class="col-sm-7  col-md-8 form-control form-control-sm shadow-sm validateText">
                                            </div>
                                            <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                                    <label for="correoUpdate" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Correo: </label>
                                                    <input type="text" name="correoUpdate" id="correoUpdate" value="{{ $usDetalle->correo }}" class="col-sm-7  col-md-8 form-control form-control-sm shadow-sm validateText">
                                            </div>
                     
                                            <div class="form-group row justify-content-center mx-0 px-2 col-12 errors_message" id="errors_update">
                                                
                                            </div>
                                            <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-12 col-lg-12 d-flex justify-content-center">
                                                <a href="javascript:void(0)" class="btn btn-sm btn-outline-primary shadow-sm" id="actualizarPerfil">Actualizar</a>
                                            </div>
                                            
                                        </section>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="passwordP" role="tabpanel" aria-labelledby="password-perfil">
                                   <div class="card-body">
                                       <div id="form_updatePassword_load"></div>
                                       <section class="row justify-content-center" id="form_updatePassword_detail">
                                            <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-8 col-lg-8 ">
                                                <label for="usuarioUpdate" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Usuario: </label>
                                                <div class="col-sm-7 col-md-8 font-weight-bold form-control form-control-sm text-black-50">{{ $usDetalle->usuario }}</div>
                                            </div>
                                            <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-8 col-lg-8 ">
                                                <label for="claveUpdate" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Nueva contraseña: </label>    
                                                <div class="input-group col-sm-7  col-md-8 p-0">  
                                                    <input type="password" name="claveUpdate" id="claveUpdate" class="form-control form-control-sm shadow-sm validateText">
                                                    <span class="input-group-btn">
                                                        <a href="javascript: void(0)" id="verPasswordUser" class="btn btn-outline-success btn-sm shadow-sm w-100" ><i class="fa fa-eye" aria-hidden="true"></i></a>
                                                    </span>
                                                </div>  
                                            </div>
                                            <div class="form-group row justify-content-center mx-0 px-2 col-12 errors_message" id="errors_update_password">
                                            </div>
        
                                            <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-12 col-lg-12 d-flex justify-content-center">
                                                <a href="javascript:void(0)" class="btn btn-sm btn-outline-success shadow-sm" id="actualizarPassword">Actualizar Contraseña</a>
                                            </div>
                                            <div class="col-md-12 small-box">
                                                <span class="d-block small font-weight-bold text-primary">
                                                    Recuerde tener en cuenta el siguiente formato:  
                                                </span>
                                                    <ul class="small text-secondary" id="display_politica_password">
                                                        <li class="items">longitud mínima de 8 caracteres.</li>
                                                        <li class="items">Contar con almenos una letra mayuscula y minuscula.</li>
                                                        <li class="items">Contar con almenos un numero.</li>
                                                        <li class="items">Contar con un caracter especial. Ejemplo: #?!@$%^&amp;*-</li>
                                                    </ul>
                                                
                                            </div>
                                       </section> 
                                   </div>
                                </div>
                            @endif
                            
                        </div>
                        {{-- End Tabs--}}
                    </div>
                </div>
        </div>
    </div>

    
@endsection

@section('scripts-footer')  

    @if (($usDetalle->identificador == Auth()->user()->id ) || Auth()->user()->tienePermisoEspecial())
        <script src="{{ asset('js/sistema/perfil/perfil.min.js') }}"></script>
    @endif
    
    
@endsection