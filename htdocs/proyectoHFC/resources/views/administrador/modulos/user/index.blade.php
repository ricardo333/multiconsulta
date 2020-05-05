@extends('layouts.master')

@section('titulo_pagina_sistema', 'Usuarios')

@section('estilos')


@endsection
@section('scripts-header')

@endsection

@section('top-left-submenus')
    @parent
    {{-- Menu Top--}}
@endsection

@section('title-container')
     <h4 class="m-0 text-dark text-uppercase">Usuarios</h4> 
    
@endsection
@section('ruta-navegacion-container')
    @parent
     <li class="breadcrumb-item active">Usuarios</li>
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
                        @if(Auth::user()->HasPermiso('submodulo.usuario.store'))
                            <a href="{{route('submodulo.usuario.store')}}" class="btn btn-sm btn-outline-primary shadow-sm mx-1" id="activeModalUserStore">Crear<i class="fa fa-user-plus" aria-hidden="true"></i></a>
                        @endif
                    </div> 
                    <div class="card-body">
                        <section class="content_table_list">
                            <table id="listUsersPrint" class="table table-hover table-bordered w-100 tableFixHead">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Nombre y Apellidos</th>
                                        <th>Usuario</th>
                                        <th>DNI</th>
                                        <th>Correo</th>  
                                        @if(  Auth::user()->HasPermiso('submodulo.usuario.show') || 
                                              Auth::user()->HasPermiso('submodulo.usuario.edit')  ||
                                              Auth::user()->HasPermiso('submodulo.usuario.delete')
                                            )
                                          <th>Acciones</th>
                                          <script> 
                                                var COLUMNS_LIST_USERS = [
                                                                                {data: 'id'},
                                                                                {data: null, render: function(data,type,row){
                                                                                    return `${row.nombre}, ${row.apellidos}`
                                                                                    }
                                                                                },
                                                                               // {data: 'apellidos'},
                                                                                {data: 'username'},
                                                                                {data: 'dni'},
                                                                                {data: 'email'},
                                                                                {data: 'btn'},
                                                                            ]
                                            </script>
                                        @else 

                                            <script> 
                                                var COLUMNS_LIST_USERS = [
                                                                                {data: 'id'},
                                                                                {data: null, render: function(data,type,row){
                                                                                    return `${row.nombre}, ${row.apellidos}`
                                                                                    }
                                                                                },
                                                                               // {data: 'apellidos'},
                                                                                {data: 'username'},
                                                                                {data: 'dni'},
                                                                                {data: 'email'}
                                                                            ]
                                            </script>

                                        @endif
                                        
                                    </tr>
                                </thead>
                            </table>
                        </section>
                    </div>
                </div>
        </div>
    </div>

    
@endsection

@section('scripts-footer')  
    <script src="{{ asset('js/sistema/modulos/users/index.min.js') }}"></script>
    @if(Auth::user()->HasPermiso('submodulo.usuario.delete'))
     <script src="{{ asset('js/sistema/modulos/users/delete.min.js') }}"></script>
    @endif
@endsection