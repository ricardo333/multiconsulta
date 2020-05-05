@extends('layouts.master')

@section('titulo_pagina_sistema', 'Gestiòn de Cuarentenas')
 
@section('estilos')
  
@endsection
@section('scripts-header')
    <script>
        var EDITAR_CUARENTENA_PERMISO = false
        var ELIMINAR_CUARENTENA_PERMISO = false
    </script>
@endsection

@section('top-left-submenus')
    @parent
    {{-- Menu Top--}}
@endsection

@section('title-container')
     <h4 class="m-0 text-dark text-uppercase">Gestión Cuarentenas</h4> 
    
@endsection
@section('ruta-navegacion-container')
    @parent
     <li class="breadcrumb-item active">Gestión Cuarentenas</li>
@endsection

@section('aside-right')
    {{-- Aqui el aside del lado derecho, ingresar lo que sedea mostrar--}}
@endsection

@section('content')
    @parent
 
    @php
        $EDITAR_CUARENTENA_PERMISO = false;
        $ELIMINAR_CUARENTENA_PERMISO = false;
    @endphp

    @if(Auth::user()->HasPermiso('submodulo.gestion-cuarentena.edit'))
        <script> 
            EDITAR_CUARENTENA_PERMISO = true 
         </script>
        @php $EDITAR_CUARENTENA_PERMISO = true; @endphp
    @endif
    @if(Auth::user()->HasPermiso('Submodulo.gestion-cuarentena.delete'))
        <script> 
            ELIMINAR_CUARENTENA_PERMISO = true 
         </script>
        @php $ELIMINAR_CUARENTENA_PERMISO = true; @endphp
    @endif

   
    <div class="row">

        <div class="tab-content w-100" id="tabsGestionCuarentenasContent">
            <div class="tab-pane fade show   active" id="cuarentenaListaTab" role="tabpanel" aria-labelledby="cuarentenaListaTab-tab">
                <section  class="col-12 mx-0 px-0">
                    <div class="card-header px-2 py-1"> 
                            <a href="{{route('administrador')}}" class="btn btn-sm btn-outline-success mx-1"><i class="fa fa-arrow-left"></i> Atras</a>
                            @if(Auth::user()->HasPermiso('submodulo.gestion-cuarentena.store'))
                                <a href="javascript:void(0)" class="btn btn-sm btn-outline-success shadow-sm mx-1" id="redirectStoreCuarentenaTab">Crear</a>
                            @endif
                            <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                           
                    </div>
                    <div class="card"> 
                        <div class="card-body position-relative" id="contenedor_cuarentenas_lista_body">
                                <div class="h5 text-center d-block text-danger mb-3">Lista Gestión de Cuarentenas</div>
                                <section class="row my-3 py-2 content_filter_basic" id="filtroContentGCuarentenas" style="display: none;">
                                    <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                        <label for="" class="col-12 col-sm-3">Jefaturas:</label>
                                        <select name="listaJefaturasGCuarentenas" id="listaJefaturasGCuarentenas" class="col-12 col-sm-9 form-control form-control-sm shadow-sm">
                                            <option value="seleccionar">Sin Filtro</option>
                                            <option value="SIN-JEFATURA">SIN-JEFATURA</option>
                                              -@forelse ($jefaturas as $jeft)
                                                <option value="{{$jeft->jefatura}}">{{$jeft->jefatura}}</option>
                                             @empty
                                           
                                             @endforelse 
                                        </select>
                                    </div>
                                    <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                            <label for="" class="col-12 col-sm-3">Estados:</label>
                                            <select name="listaEstadosGCuarentenas" id="listaEstadosGCuarentenas" class="col-12 col-sm-9 form-control form-control-sm shadow-sm">
                                                <option value="Activo" selected>Activo</option>
                                                <option value="Inactivo">Inactivo</option> 
                                            </select>
                                    </div>
                                    <div class="form-group row mx-0 mb-0 px-2 col-12 col-sm-12 col-md-12 col-lg-12 justify-content-center">
                                            <a href="javascript:void(0)" class="btn btn-sm btn-primary shadow-sm w-25" id="filtroBasicoGCuarentenas">Filtrar</a>
                                    </div>
                                </section>
                                <div class="content_table_list"> 
                                    <table id="resultCuarentenasList" class="table table-hover table-bordered w-100 tableFixHead table-text-xs">
                                        <thead>
                                            <tr>
                                                <th>ID</th> 
                                                <th>nombre</th>
                                                {{--<th>nodo</th>
                                                <th>troba</th>--}}
                                                <th>jefatura</th>
                                                <th>N° Clientes</th>
                                                <th>N° Trobas</th>
                                                 <th>servicePackageCrmid</th>
                                                <th>scopesGroup</th>
                                                <th>Estado</th>
                                                <th>C. de Mando</th>
                                                <th>Tipo</th>
                                                <th>fechaInicio</th>
                                                <th>fechaFin</th>
                                                <th>fechaRegistro</th> 
                                                @if($EDITAR_CUARENTENA_PERMISO || $ELIMINAR_CUARENTENA_PERMISO)
                                                    <th>Acciones</th>
                                                 @endif 
                                            </tr>
                                        </thead>  
                                    </table>
                                </div>
                        </div>
                    </div>
                </section> 
            </div>
            <div class="tab-pane fade" id="cuarentenaClientesListTab" role="tabpanel" aria-labelledby="cuarentenaClientesListTab-tab">
                <section  class="col-12 mx-0 px-0">
                    <div class="card-header px-2 py-1"> 
                        <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_lista_cuarentenas_Tab"><i class="fa fa-arrow-left"></i> Atras Cuarentenas</a>
                            <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                           
                    </div>
                    <div class="card"> 
                        <div class="card-body position-relative" id="contenedor_cuarentenas_clientes_lista_body">
                                <div class="h5 text-center d-block text-danger mb-3">Lista Clientes  - <strong id="nombreCuarentenaClienteDetalle"></strong></div>
                               
                                <div class="content_table_list"> 
                                    <table id="resultCuarentenasClientesList" class="table table-hover table-bordered w-100 tableFixHead table-text-xs">
                                        <thead>
                                            <tr>
                                                <th>ID</th> 
                                                <th>Cod. Cliente</th>
                                                <th>Jefatura</th>
                                                <th>Nodo</th>
                                                <th>Troba</th>
                                                <th>servicePackageCrmid</th>
                                                <th>scopesGroup</th> 
                                            </tr>
                                        </thead>  
                                    </table>
                                </div>
                        </div>
                    </div>
                </section> 
            </div>
            <div class="tab-pane fade" id="cuarentenaTrobasListTab" role="tabpanel" aria-labelledby="cuarentenaTrobasListTab-tab">
                <section  class="col-12 mx-0 px-0">
                    <div class="card-header px-2 py-1"> 
                        <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_lista_cuarentenas_Tab"><i class="fa fa-arrow-left"></i> Atras Cuarentenas</a>
                            <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                           
                    </div>
                    <div class="card"> 
                        <div class="card-body position-relative" id="contenedor_cuarentenas_trobas_lista_body">
                                <div class="h5 text-center d-block text-danger mb-3">Lista (NODOS - TROBAS) - <strong id="nombreCuarentenaTrobaDetalle"></strong></div>
                               
                                <div class="content_table_list"> 
                                    <table id="resultCuarentenasTrobasList" class="table table-hover table-bordered w-100 tableFixHead table-text-xs">
                                        <thead>
                                            <tr>
                                                <th>ID</th> 
                                                <th>Nodo</th>
                                                <th>Troba</th>
                                            </tr>
                                        </thead>  
                                    </table>
                                </div>
                        </div>
                    </div>
                </section> 
            </div>
            @if(Auth::user()->HasPermiso('submodulo.gestion-cuarentena.store'))
                <div class="tab-pane fade" id="cuarentenaStoreTab" role="tabpanel" aria-labelledby="cuarentenaStoreTab-tab">
                    <section  class="col-12 mx-0 px-0">
                        <div class="card-header px-2 py-1"> 
                                <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_lista_cuarentenas_Tab"><i class="fa fa-arrow-left"></i> Atras Cuarentenas</a>
                                <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                        </div>
                        <div class="card"> 
                            <div class="card-body" id="contenedor_cuarentenas_store_body">
                                {{--<h4 class="text-center text-danger mb-3">Creando Cuarentenas</h4>--}}
                                <div id="contenido_tipo_cuarentena_filtro">
                                        <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 m-auto">
                                            <label for="tipoCuarentenaStore" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0 text-dange">Creando Cuarentenas : </label>
                                            <select name="tipoCuarentenaStore" id="tipoCuarentenaStore" class="col-sm-7  col-md-8 form-control form-control-sm shadow-sm validateSelect"> 
                                                    <option value="seleccionar" selected>Seleccionar</option> 
                                                    @foreach ($tipoCuarentenas as $tp)
                                                        <option value="{{$tp->nombre}}">{{$tp->nombre}}</option> 
                                                    @endforeach 
                                                    
                                            </select>
                                        </div>
                                </div>
                                <div id="preloadStoreCuarentenas"></div>
                                <div id="storeCuarentena" class="row m-0 p-0 d-none">
                                    {{-- --}}
                                        <div class="form-group w-100">
                                                <a href="javascript:void(0)" id="storeFileRedirectGCuarentena" 
                                                        class="btn btn-sm btn-outline-success shadow-sm">
                                                            Crear con Archivo 
                                                    <i class="fa fa-arrow-right"></i>
                                                </a>
                                        </div>
                                        <div class="form-group w-100">
                                            <div class="row">
                                               <div class="col-12" id="resultado_cuarentenas_store"></div>
                                            </div>
                                        </div>
                                        <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6">
                                            <label for="listadoStoreJefatura" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Lista de Jefatura: </label>
                                            <select name="listadoStoreJefatura" id="listadoStoreJefatura" class="col-sm-7  col-md-8 form-control form-control-sm shadow-sm validateSelect"> 
                                                    <option value="Seleccionar">Seleccionar</option> 
                                                    @forelse ($jefaturas as $jeft)
                                                        <option value="{{$jeft->jefatura}}">{{$jeft->jefatura}}</option>
                                                    @empty
                                                        
                                                    @endforelse
                                            </select>
                                        </div>
                                        <div class="form-group row mx-0 px-0 col-12 col-sm-12 col-md-6 col-lg-6">
                                            <label for="nombreCuarentenaStore" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Nombre Cuarentena </label>
                                            <input id="nombreCuarentenaStore" type="text"  class="col-sm-7 col-md-8 form-control form-control-sm shadow-sm validateText">
                                        </div>
                                        <div class="form-group w-100 px-2">
                                            <div class="row">
                                               <label for="" class="col-12">Seleccionar nodo - troba :</label>
                                            </div>
                                        </div>
                                        <div class="form-group row col-12 mx-2 p-0 ">
                                            <div class="dual-list list-left col-md-5 px-0">
                                                <div class="well text-right">
                                                    <div class="input-group" style="line-height: normal;">
                                                        <button class="btn btn-sm btn-outline-primary shadow-sm"><i class="icofont-search-2 icofont-md"></i></button>
                                                        <input type="text" name="SearchDualStoreNodoTroba1" class="form-control  form-control-sm shadow-sm text-primary" placeholder="search" />
                                                    </div>
                                        
                                                    <select multiple="multiple" size="10" name="duallistbox_storeNodoTroba1" id="listaNodoTrobaStore1" class="mdb-select md-form demo1 form-control form-control-sm shadow-sm" style="width: -webkit-fill-available;">
                                                       
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="list-arrows col-md-2 text-center d-flex align-self-center flex-column justify-content-center align-items-center">
                                                
                                                <button id="btnRightStoreTrobas" class="btn btn-sm btn-outline-success shadow-sm m-1">
                                                    <i class="icofont-rounded-right"></i>
                                                </button>
                                                <button id="btnLeftStoreTrobas" class="btn btn-sm btn-outline-success shadow-sm m-1" >
                                                    <i class="icofont-rounded-left"></i>
                                                </button> 
                                                
                                            </div>

                                            <div class="dual-list list-left col-md-5 px-0">
                                                <div class="well text-right">
                                                    <div class="input-group" style="line-height: normal;">
                                                        <button class="btn btn-sm btn-outline-primary shadow-sm"><i class="icofont-search-2 icofont-md"></i></button>
                                                        <input type="text" name="SearchDualStoreNodoTroba2" class="form-control form-control-sm shadow-sm text-primary" placeholder="search" />
                                                    </div>

                                                    <select multiple="multiple" size="10" name="duallistbox_storeNodoTroba2" id="listaNodoTrobaStore2" class="demo2 form-control form-control-sm shadow-sm" style="width: -webkit-fill-available;">
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        {{--<div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6">
                                            <label for="listadoStoreCmts" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Lista de CMTS: </label>
                                            <select name="listadoStoreCmts" id="listadoStoreCmts" class="col-sm-7  col-md-8 form-control form-control-sm shadow-sm validateSelect"> 
                                                    <option value="Seleccionar">Seleccionar</option> 
                                                    @forelse ($cmts as $cm)
                                                        <option value="{{$cm->cmts}}">{{$cm->cmts}}</option> 
                                                    @empty
                                                        
                                                    @endforelse
                                            </select>
                                        </div> 
                                        <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6">
                                            <label for="listadoStoreInterfaces" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Lista de Interfaces: </label>
                                            <select name="listadoStoreInterfaces" id="listadoStoreInterfaces" class="col-sm-7  col-md-8 form-control form-control-sm shadow-sm validateSelect"> 
                                                    <option value="Seleccionar">Seleccionar</option> 
                                                     @forelse ($interfaces as $inter)
                                                    <option value="{{$inter->interbus}}">{{$inter->interbus}}</option>
                                                    @empty
                                                        
                                                    @endforelse
                                            </select>
                                        </div>--}}
                                        
                                        <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6">
                                            <label for="ListaServicePackageStore" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Lista de Servicepackage: </label>
                                            <select name="ListaServicePackageStore" id="ListaServicePackageStore" class="col-sm-7  col-md-8 form-control form-control-sm shadow-sm validateSelect"> 
                                                    <option value="Seleccionar">Seleccionar</option> 
                                                    @forelse ($servicepackageCrmid as $serv)
                                                        <option value="{{$serv->SERVICEPACKAGECRMID}}">{{$serv->SERVICEPACKAGECRMID}}</option>
                                                    @empty
                                                        
                                                    @endforelse
                                            </select>
                                        </div>
                                        <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6">
                                            <label for="ListaScopeGroupStore" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Lista de ScopeGroup: </label>
                                            <select name="ListaScopeGroupStore" id="ListaScopeGroupStore" class="col-sm-7  col-md-8 form-control form-control-sm shadow-sm validateSelect"> 
                                                    <option value="Seleccionar">Seleccionar</option> 
                                                    @forelse ($scopeGroup as $scope)
                                                    <option value="{{$scope->SCOPESGROUP}}">{{$scope->SCOPESGROUP}}</option>
                                                    @empty
                                                        
                                                    @endforelse
                                            </select>
                                        </div>
                                        <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6">
                                            <label for="tipoCuarentenaStore" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Tipo Cuarentena: </label>
                                            <select name="tipoCuarentenaStore" id="tipoCuarentenaStore" class="col-sm-7  col-md-8 form-control form-control-sm shadow-sm validateSelect"> 
                                                    <option value="seleccionar" selected>Seleccionar</option> 
                                                    @foreach ($tipoCuarentenas as $tp)
                                                        <option value="{{$tp->nombre}}">{{$tp->nombre}}</option> 
                                                    @endforeach 
                                                    
                                            </select>
                                        </div>
                                        <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6">
                                            <label for="ListaEstadoStore" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Estado: </label>
                                            <select name="ListaEstadoStore" id="ListaEstadoStore" class="col-sm-7  col-md-8 form-control form-control-sm shadow-sm validateSelect"> 
                                                    <option value="Activo" selected>Activo</option> 
                                                    <option value="Inactivo">Inactivo</option> 
                                            </select>
                                        </div>
                                        <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6">
                                            <label for="ListapublicadoStore" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Cuadro de Mando: </label>
                                            <select name="ListapublicadoStore" id="ListapublicadoStore" class="col-sm-7  col-md-8 form-control form-control-sm shadow-sm validateSelect"> 
                                                    <option value="Activo" selected>Si</option> 
                                                    <option value="Inactivo">No</option>  
                                            </select>
                                        </div>
                                        <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6">
                                            <label for="fechaInicioStore" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Fecha Inicio: </label>
                                            <input id="fechaInicioStore" type="date" value="{{$fechaInicio}}" min="{{$fechaInicio}}" step="1" class="col-sm-7  col-md-8 form-control form-control-sm shadow-sm validateText">
                                        </div>
                                        <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6">
                                            <label for="fechaFinStore" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Fecha Fin: </label>
                                            <input id="fechaFinStore" type="date" value="{{$fechaInicio}}" min="{{$fechaInicio}}" step="1" class="col-sm-7  col-md-8 form-control form-control-sm shadow-sm validateText">
                                        </div>
                                        <div class="form-group row mx-0 px-2 col-12 text-center justify-content-center">
                                            <a href="javascript:void(0)" class="btn btn-sm btn-outline-success shadow-sm" id="registrarCuarentenaSend">Registrar Cuarentena</a>
                                        </div>
                                    {{--- --}}
                                </div>
                            </div>
                        </div>
                    </section> 
                </div>
                <div class="tab-pane fade" id="cuarentenaStoreFileTab" role="tabpanel" aria-labelledby="cuarentenaStoreFileTab-tab">
                    <section  class="col-12 mx-0 px-0">
                        <div class="card-header px-2 py-1"> 
                                <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_lista_cuarentenas_Tab"><i class="fa fa-arrow-left"></i> Atras Cuarentenas</a>
                                <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_store_cuarentenas_Tab"><i class="fa fa-arrow-left"></i> Registro Cuarentenas</a>
                                <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                        </div>
                        <div class="card"> 
                            <div class="card-body" id="contenedor_cuarentenas_store_file_body">
                                <h4 class="text-center text-danger mb-3">Creando Cuarentena <strong id="tipoCuarentenaTextoActivo"></strong> por TXT</h4>
                                <div id="preloadStoreCuarentenasFile"></div>
                                <div id="storeCuarentenaFile" class="row m-0 p-0">
                                    {{-- --}}
                                        <div class="form-group w-100">
                                            <div class="row">
                                               <div class="col-12" id="resultado_cuarentenas_store_file"></div>
                                            </div>
                                        </div>
                                        <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6">
                                                <label for="fileLoadStoreCuarentena" class="col-sm-5 col-md-4 mb-0  py-0 px-1 col-form-label col-form-label-sm btn btn-outline-info btn-sm d-flex 
                                                                                        align-items-center justify-content-center">
                                                        <i class="icofont-cloud-upload icofont-2x"></i> Subir
                                                </label> 
                                                <input type="file"  id="fileLoadStoreCuarentena" class="d-none validateFile"> 
                                                <div class="col-sm-7 col-md-8 form-control form-control-sm shadow-sm" id="nameFileValidate"></div>
                                        </div> 
                                        <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6">
                                            <label for="nombreCuarentenaStoreFile" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Nombre Cuarentena </label>
                                            <input id="nombreCuarentenaStoreFile" type="text"  class="col-sm-7 col-md-8 form-control form-control-sm shadow-sm validateText">
                                        </div> 
                                        {{--<div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6">
                                            <label for="tipoCuarentenaStoreFile" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Tipo Cuarentena: </label>
                                            <select name="tipoCuarentenaStoreFile" id="tipoCuarentenaStoreFile" class="col-sm-7  col-md-8 form-control form-control-sm shadow-sm validateSelect"> 
                                                     <option value="seleccionar" selected>Seleccionar</option>  
                                                    @foreach ($tipoCuarentenas as $tp)
                                                        <option value="{{$tp->nombre}}">{{$tp->nombre}}</option> 
                                                    @endforeach 
                                                    
                                            </select>
                                        </div>--}}
                                        <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6">
                                            <label for="ListaEstadoStoreFile" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Estado: </label>
                                            <select name="ListaEstadoStoreFile" id="ListaEstadoStoreFile" class="col-sm-7  col-md-8 form-control form-control-sm shadow-sm validateSelect"> 
                                                    <option value="Activo" selected>Activo</option> 
                                                    <option value="Inactivo">Inactivo</option> 
                                            </select>
                                        </div>
                                        <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6">
                                            <label for="ListapublicadoStoreFile" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Cuadro de mando: </label>
                                            <select name="ListapublicadoStoreFile" id="ListapublicadoStoreFile" class="col-sm-7  col-md-8 form-control form-control-sm shadow-sm validateSelect"> 
                                                    <option value="Activo" selected>Si</option> 
                                                    <option value="Inactivo">No</option>  
                                            </select>
                                        </div>
                                        <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6">
                                            <label for="fechaInicioStoreFile" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Fecha Inicio: </label>
                                            <input id="fechaInicioStoreFile" type="date" value="{{$fechaInicio}}" min="{{$fechaInicio}}" step="1" class="col-sm-7  col-md-8 form-control form-control-sm shadow-sm validateText">
                                             
                                        </div>
                                        <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6">
                                            <label for="fechaFinStoreFile" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Fecha Fin: </label>
                                            <input id="fechaFinStoreFile" type="date" value="{{$fechaInicio}}" min="{{$fechaInicio}}" step="1" class="col-sm-7  col-md-8 form-control form-control-sm shadow-sm validateText">
                                             
                                        </div>
                                        <div class="form-group row mx-0 px-2 col-12 text-center justify-content-center">
                                            <a href="javascript:void(0)" class="btn btn-sm btn-outline-success shadow-sm" id="registrarCuarentenaFileSend">Registrar Cuarentena</a>
                                        </div>
                                    {{--- --}}
                                </div>
                            </div>
                        </div>
                    </section> 
                </div>
                <script>
                    var LISTA_TROBAS = []
                    var FECHA_INICIO_STORE = `{{$fechaInicio}}`
                    
                </script>
                <script src="{{ url('/js/sistema/modulos/gestion-cuarentenas/store.min.js') }}"></script>
            @endif

            @if ($EDITAR_CUARENTENA_PERMISO)
            <div class="tab-pane fade" id="cuarentenaEditTab" role="tabpanel" aria-labelledby="cuarentenaEditTab-tab">
                    <section  class="col-12 mx-0 px-0">
                        <div class="card-header px-2 py-1"> 
                                <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_lista_cuarentenas_Tab"><i class="fa fa-arrow-left"></i> Atras Cuarentenas</a>
                                <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                        </div>
                        <div class="card"> 
                            <div class="card-body" id="contenedor_cuarentenas_edit_body">
                                <h4 class="text-center text-danger mb-3">Editando Cuarentenas</h4>
                                <div id="preloadEditCuarentenas"></div>
                                <div id="EditCuarentena" class="row m-0 p-0">
                                     {{-- --}}
                                        <div class="form-group w-100">
                                            <div class="row">
                                               <div class="col-12" id="resultado_cuarentenas_edit"></div>
                                            </div>
                                        </div>
                                        <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6">
                                            <label for="listadoEditJefatura" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Lista de Jefatura: </label>
                                            <select name="listadoEditJefatura" id="listadoEditJefatura" class="col-sm-7  col-md-8 form-control form-control-sm shadow-sm validateSelect"> 
                                                    <option value="Seleccionar">Seleccionar</option> 
                                                    @forelse ($jefaturas as $jeft)
                                                        <option value="{{$jeft->jefatura}}">{{$jeft->jefatura}}</option>
                                                    @empty
                                                        
                                                    @endforelse
                                            </select>
                                        </div>
                                        <div class="form-group row mx-0 px-0 col-12 col-sm-12 col-md-6 col-lg-6">
                                            <label for="nombreCuarentenaEdit" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Nombre Cuarentena </label>
                                            <input id="nombreCuarentenaEdit" type="text"  class="col-sm-7 col-md-8 form-control form-control-sm shadow-sm validateText">
                                        </div>
                                        {{--<div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6">
                                            <label for="ListaNodoTrobaEdit" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Lista de Nodo Trobas: </label>
                                            <select name="ListaNodoTrobaEdit" id="ListaNodoTrobaEdit" class="col-sm-7  col-md-8 form-control form-control-sm shadow-sm validateSelect"> 
                                                    <option value="Seleccionar">Seleccionar</option> 
                                                   
                                            </select>
                                        </div>--}}
                                        <div class="form-group w-100 px-2">
                                            <div class="row">
                                               <label for="" class="col-12">Seleccionar nodo - troba :</label>
                                            </div>
                                        </div>
                                        <div class="form-group row col-12 mx-2 p-0 ">
                                            <div class="dual-list list-left col-md-5 px-0">
                                                <div class="well text-right">
                                                    <div class="input-group" style="line-height: normal;">
                                                        <button class="btn btn-sm btn-outline-primary shadow-sm"><i class="icofont-search-2 icofont-md"></i></button>
                                                        <input type="text" name="SearchDualEditNodoTroba1" class="form-control  form-control-sm shadow-sm text-primary" placeholder="search" />
                                                    </div>
                                        
                                                    <select multiple="multiple" size="10" name="duallistbox_editNodoTroba1" id="listaNodoTrobaEdit1" class="mdb-select md-form demo1 form-control form-control-sm shadow-sm" style="width: -webkit-fill-available;">
                                                       
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="list-arrows col-md-2 text-center d-flex align-self-center flex-column justify-content-center align-items-center">
                                                
                                                <button id="btnRightEditTrobas" class="btn btn-sm btn-outline-success shadow-sm m-1">
                                                    <i class="icofont-rounded-right"></i>
                                                </button>
                                                <button id="btnLeftEditTrobas" class="btn btn-sm btn-outline-success shadow-sm m-1" >
                                                    <i class="icofont-rounded-left"></i>
                                                </button> 
                                                
                                            </div>

                                            <div class="dual-list list-left col-md-5 px-0">
                                                <div class="well text-right">
                                                    <div class="input-group" style="line-height: normal;">
                                                        <button class="btn btn-sm btn-outline-primary shadow-sm"><i class="icofont-search-2 icofont-md"></i></button>
                                                        <input type="text" name="SearchDualEditNodoTroba2" class="form-control form-control-sm shadow-sm text-primary" placeholder="search" />
                                                    </div>

                                                    <select multiple="multiple" size="10" name="duallistbox_editNodoTroba2" id="listaNodoTrobaEdit2" class="demo2 form-control form-control-sm shadow-sm" style="width: -webkit-fill-available;">
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6">
                                            <label for="ListaServicePackageEdit" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Lista de Servicepackage: </label>
                                            <select name="ListaServicePackageEdit" id="ListaServicePackageEdit" class="col-sm-7  col-md-8 form-control form-control-sm shadow-sm validateSelect"> 
                                                    <option value="Seleccionar">Seleccionar</option> 
                                                    @forelse ($servicepackageCrmid as $serv)
                                                        <option value="{{$serv->SERVICEPACKAGECRMID}}">{{$serv->SERVICEPACKAGECRMID}}</option>
                                                    @empty
                                                        
                                                    @endforelse
                                            </select>
                                        </div>
                                        <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6">
                                            <label for="ListaScopeGroupEdit" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Lista de ScopeGroup: </label>
                                            <select name="ListaScopeGroupEdit" id="ListaScopeGroupEdit" class="col-sm-7  col-md-8 form-control form-control-sm shadow-sm validateSelect"> 
                                                    <option value="Seleccionar">Seleccionar</option> 
                                                    @forelse ($scopeGroup as $scope)
                                                        <option value="{{$scope->SCOPESGROUP}}">{{$scope->SCOPESGROUP}}</option>
                                                    @empty
                                                        
                                                    @endforelse
                                            </select>
                                        </div>
                                        <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6">
                                            <label for="tipoCuarentenaEdit" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Tipo Cuarentena: </label>
                                            <select name="tipoCuarentenaEdit" id="tipoCuarentenaEdit" class="col-sm-7  col-md-8 form-control form-control-sm shadow-sm validateSelect"> 
                                                    <option value="seleccionar" selected>Seleccionar</option> 
                                                    @foreach ($tipoCuarentenas as $tp)
                                                        <option value="{{$tp->nombre}}">{{$tp->nombre}}</option> 
                                                    @endforeach 
                                                    
                                            </select>
                                        </div> 
                                        <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6">
                                            <label for="ListaEstadoEdit" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Estado: </label>
                                            <select name="ListaEstadoEdit" id="ListaEstadoEdit" class="col-sm-7  col-md-8 form-control form-control-sm shadow-sm validateSelect"> 
                                                    <option value="Activo" selected>Activo</option> 
                                                    <option value="Inactivo">Inactivo</option> 
                                            </select>
                                        </div>
                                        <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6">
                                            <label for="ListapublicadoEdit" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Cuadro de Mando: </label>
                                            <select name="ListapublicadoEdit" id="ListapublicadoEdit" class="col-sm-7  col-md-8 form-control form-control-sm shadow-sm validateSelect"> 
                                                    <option value="Activo" selected>Si</option> 
                                                    <option value="Inactivo">No</option>  
                                            </select>
                                        </div>
                                        <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6">
                                            <label for="fechaInicioEdit" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Fecha Inicio: </label>
                                            <input id="fechaInicioEdit" type="date" value="" min="" step="1" class="col-sm-7  col-md-8 form-control form-control-sm shadow-sm validateText">
                                                
                                        </div>
                                        <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6">
                                            <label for="fechaFinEdit" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Fecha Fin: </label>
                                            <input id="fechaFinEdit" type="date" value="" min="" step="1" class="col-sm-7  col-md-8 form-control form-control-sm shadow-sm validateText">
                                                
                                        </div>
                                        <div class="form-group row mx-0 px-2 col-12 text-center justify-content-center">
                                            <a href="javascript:void(0)" class="btn btn-sm btn-outline-success shadow-sm" data-uno="" id="actualizarCuarentenaSend">Actualizar Cuarentena</a>
                                        </div>
                                     {{-- --}}
                                </div>
                            </div>
                        </div>
                    </section> 
                </div>
                <script>
                    var LISTA_TROBAS_EDIT = []
                </script>
                <script src="{{ url('/js/sistema/modulos/gestion-cuarentenas/edit.min.js') }}"></script>
            @endif
        </div>
           
    </div>

    
@endsection

@section('scripts-footer')   

    <script src="{{ url('/js/sistema/modulos/gestion-cuarentenas/index.min.js') }}"></script>

    @if ($ELIMINAR_CUARENTENA_PERMISO)
    <script src="{{ url('/js/sistema/modulos/gestion-cuarentenas/delete.min.js') }}"></script>
    @endif
     
@endsection