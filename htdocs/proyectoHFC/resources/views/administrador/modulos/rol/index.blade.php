@extends('layouts.master')

@section('titulo_pagina_sistema', 'Roles')

@section('estilos')


@endsection
@section('scripts-header')

@endsection

@section('top-left-submenus')
    @parent
    {{-- Menu Top--}}
@endsection

@section('title-container')
     <h4 class="m-0 text-dark text-uppercase">Roles</h4> 
    
@endsection
@section('ruta-navegacion-container')
    @parent
     <li class="breadcrumb-item active">Roles</li>
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
                        @if(Auth::user()->HasPermiso('submodulo.rol.store'))
                            <a href="{{route('submodulo.rol.store')}}" class="btn btn-sm btn-outline-primary shadow-sm mx-1" id="activeModalRoleStore">Crear <i class="fa fa-plus-square" aria-hidden="true"></i> </a>
                        @endif
                        
                    </div> 
                    <div class="card-body">
                        <section class="content_table_list">
                            <table id="listRolesPrint" class="table table-hover table-bordered w-100 tableFixHead">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Nombre</th>
                                        <th>Estado</th>
                                        @if(  Auth::user()->HasPermiso('submodulo.rol.show') || 
                                              Auth::user()->HasPermiso('submodulo.rol.edit')  ||
                                              Auth::user()->HasPermiso('submodulo.rol.delete')
                                            )
                                          <th>Acciones</th>
                                          <script> 
                                                var COLUMNS_ROLES = [
                                                                        {data: 'id'},
                                                                        {data: 'nombre'},
                                                                        {data: 'estado',
                                                                            render: function(data,type,row){
                                                                                //console.log("la data de estado es: ",data,type,row)
                                                                                if (row.estado==1) {
                                                                                    return "Activo"
                                                                                }
                                                                                return "Inactivo"
                                                                            }
                                                                        },
                                                                        {data: 'btn'}
                                                                    ]
                                            </script>
                                        @else 

                                            <script> 
                                                var COLUMNS_ROLES = [
                                                                        {data: 'id'},
                                                                        {data: 'estado',
                                                                            render: function(data,type,row){
                                                                                //console.log("la data de estado es: ",data,type,row)
                                                                                if (row.estado==1) {
                                                                                    return "Activo"
                                                                                }
                                                                                return "Inactivo"
                                                                            }
                                                                        },
                                                                        {data: 'estado'}
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
    <script src="{{ asset('js/sistema/modulos/roles/index.min.js') }}"></script>
    @if(Auth::user()->HasPermiso('submodulo.rol.delete'))
     <script src="{{ asset('js/sistema/modulos/roles/delete.min.js') }}"></script>
    @endif
@endsection