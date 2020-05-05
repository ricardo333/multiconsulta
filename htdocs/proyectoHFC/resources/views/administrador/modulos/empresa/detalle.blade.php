@extends('layouts.master')

@section('titulo_pagina_sistema', 'Empresa - Detalle')

@section('estilos')
    
@endsection
@section('scripts-header')
    
@endsection

@section('top-left-submenus')
    @parent
    {{-- Menu Top--}}
@endsection

@section('title-container')
     <h4 class="m-0 text-dark text-uppercase">Detalle de Empresa</h4> 
    
@endsection
@section('ruta-navegacion-container')
    @parent
     <li class="breadcrumb-item active"><a href="{{route('modulo.empresa.index')}}"> Empresa </a> </li>
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
                        <a href="{{route('modulo.empresa.index')}}" class="btn btn-sm btn-outline-success mx-1"><i class="fa fa-arrow-left"></i> Atras</a>
                    </div> 
                    <div class="card-body px-2 py-1"> 
                          <form class="form row my-2 mx-0">
                               @php $empresaDetalle = $empresa->getData(); @endphp 
 
                              <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                    <label for="nombreDetalle" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Nombre: </label>
                                    <div class="form-control form-control-sm col-12 col-sm-12 col-md-6 col-lg-6" id="nombreDetalle">
                                          {{$empresaDetalle->response->data->empresa}}
                                    </div>
                              </div>
                               
                               
                                
                              @if (Auth::user()->HasPermiso('submodulo.empresa.edit'))
                                    <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-12 col-lg-12 d-flex justify-content-center">
                                          <a href="{{ route('submodulo.empresa.edit', $empresaDetalle->response->data->identificador) }}" class="btn btn-outline-success btn-sm shadow-sm p-1 accionEmpresaEdit" >Editar<i class="fa fa-pencil icon-accion"></i></a>
                                    </div>
                                @endif
                              
                              
                          </form>
                    </div>
                </div>
        </div>
    </div>
@endsection

@section('scripts-footer') 
        <script src="{{ asset('js/sistema/modulos/empresas/show.min.js') }}"></script>
@endsection