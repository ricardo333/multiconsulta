@extends('layouts.master')

@section('titulo_pagina_sistema', 'Seguridad')

@section('estilos')


@endsection
@section('scripts-header')

@endsection

@section('top-left-submenus')
    @parent
    {{-- Menu Top--}}
@endsection

@section('title-container')
     <h4 class="m-0 text-dark text-uppercase">Seguridad</h4> 
    
@endsection
@section('ruta-navegacion-container')
    @parent
     <li class="breadcrumb-item active">Seguridad</li>
@endsection

@section('aside-right')
    {{-- Aqui el aside del lado derecho, ingresar lo que desea mostrar--}}
@endsection

@section('content')
    @parent
   
    <div class="row">
        <div class="col-12">
                <div class="card">
                    <div class="card-header px-2 py-1">
                        <a href="{{route('administrador')}}" class="btn btn-sm btn-outline-success mx-1 shadow-sm"><i class="fa fa-arrow-left"></i> Atras</a>
                         
                    </div> 
                    <div class="card-body px-2 py-1">
                            <div id="form_update_load"></div>
                       <section  class="form row my-2 mx-0 justify-content-center" id="form_update_detail">
                           @foreach ($seguridad as $seg)
                            <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-10 col-lg-8 ">
                                    <label for="parametersSeguridadUpdate{{$seg->id}}" class="col-sm-6 col-md-6 col-lg-8 col-form-label col-form-label-sm mb-0 px-0">{{ $seg->description }}: </label>
                                    <div class="input-group col-sm-6 col-md-6 col-lg-4 p-0">  
                                            <input type="number" id="parametersSeguridadUpdate{{$seg->id}}" value="{{ $seg->period }}" class="form-control form-control-sm shadow-sm  text-uppercase validateText">
                                        <div class="input-group-prepend">
                                            <small class="form-control form-control-sm text-black-50 text-uppercase">{{ $seg->time }}</small>
                                            <a href="javascript:void(0)" class="btn btn-sm  btn-outline-success shadow-sm updateSeguridadBtn" data-id="{{ $seg->id }}" data-texto="{{ $seg->description }}"><i class="fa fa-refresh" aria-hidden="true"></i></a>
                                        </div>
                                    </div>  
                                </div>
                           @endforeach
                              
                            <div class="form-group row justify-content-center mx-0 px-2 col-12 errors_message" id="errors_update">
                                
                            </div>
                             
                       </section>
                    </div>
                </div>
        </div>
    </div>

    
@endsection

@section('scripts-footer')  
    <script src="{{ asset('js/sistema/modulos/seguridad/index.min.js') }}"></script>
    
@endsection