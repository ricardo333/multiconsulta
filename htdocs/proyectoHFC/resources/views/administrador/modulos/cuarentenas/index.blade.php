@extends('layouts.master')

@section('titulo_pagina_sistema', 'Modulo de Cuarentenas')
 
@section('estilos')
  
@endsection

@section('scripts-header')
        
@endsection

@section('top-left-submenus')
    @parent
    {{-- Menu Top--}}
@endsection

@section('title-container')
     {{--<h4 class="m-0 text-dark text-uppercase">Cuarentenas</h4> --}}
    @if (isset($motivo))
        <select class="form-control form-control-sm shadow-sm text-success" id="display_filter_special">
            @foreach ($nombres as $nb)
                <option value="{{$nb->id}}" data-uno="{{$nb->tipo}}">{{$nb->nombre}} ({{$nb->tipo}})</option>
            @endforeach
        </select>
    @else
        <select class="form-control form-control-sm shadow-sm text-success" id="display_filter_special">
            {{--<option value="cuarentenas_averias">Seleccionar</option>--}}
            @foreach ($nombres as $nb)
                <option value="{{$nb->id}}" data-uno="{{$nb->tipo}}">{{$nb->nombre}} ({{$nb->tipo}})</option>
            @endforeach
        </select>
    @endif

    
    
@endsection
@section('ruta-navegacion-container')
    @parent
     <li class="breadcrumb-item active">Cuarentenas</li>
@endsection

@section('aside-right')
    {{-- Aqui el aside del lado derecho, ingresar lo que sedea mostrar--}}
@endsection

@section('content')
    @parent

    @include('administrador.modulos.cuarentenas.partials.descargasCuarentenasModal')
 
    <div class="row">

        <div class="tab-content w-100" id="tabsCuarentenasContent">
            <div class="tab-pane fade show   active" id="cuarentenaListaTab" role="tabpanel" aria-labelledby="cuarentenaListaTab-tab">
                <section  class="col-12 mx-0 px-0">
                    <div class="card-header px-2 py-1"> 
                        @if (isset($motivo) && $motivo=="cuadroMando")
                            <a href="{{route('modulo.cuadro-mando.index')}}" class="btn btn-sm btn-outline-success mx-1"><i class="fa fa-arrow-left"></i> Atras Cuadro Mando</a>
                            <input type="hidden" id="filtroCuadroMando" value="{{$nodo}}">
                        @else
                            <a href="{{route('administrador')}}" class="btn btn-sm btn-outline-success mx-1"><i class="fa fa-arrow-left"></i> Atras</a>
                        @endif
                        
                        <form action="" method="post" action="{{ route('modulo.cuarentenas.index') }}" class="d-none">
                                @csrf 
                                <input type="submit" value="15" name="codmotv">
                        </form>


                        <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                    </div>
                    <div class="card"> 
                        <div class="card-body position-relative" id="contenedor_cuarentenas_lista_body">
                                <div class="h5 text-center d-block text-danger mb-3">Lista de Cuarentenas</div>
                                <section class="row my-3 py-2 content_filter_basic" id="filtroContentCuarentenas" style="display:none;">
                                    <input type="hidden" name="averiaspFiltro" id="averiaspFiltro" class="col-12 col-sm-9 form-control form-control-sm shadow-sm"
                                            value ="{{isset($averiasp) ? $averiasp : ''}}">
                                    <input type="hidden" name="codigoMotvFiltro" id="codigoMotvFiltro" class="col-12 col-sm-9 form-control form-control-sm shadow-sm"
                                            value ="{{isset($codmotv) ? $codmotv : ''}}">
                                    <input type="hidden" name="tipoEstadoFiltro" id="tipoEstadoFiltro" class="col-12 col-sm-9 form-control form-control-sm shadow-sm"
                                            value ="{{isset($tipoEstado) ? $tipoEstado : ''}}">
                                    <input type="hidden" name="segunColorFiltro" id="segunColorFiltro" class="col-12 col-sm-9 form-control form-control-sm shadow-sm"
                                            value ="{{isset($segunColor) ? $segunColor : ''}}">
                                    <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                        <label for="" class="col-12 col-sm-3">Jefaturas:</label>
                                        <select name="listaJefaturasCuarentenas" id="listaJefaturasCuarentenas" class="col-12 col-sm-9 form-control form-control-sm shadow-sm">
                                            <option value="seleccionar">Sin Filtro</option>
                                            <option value="SIN-JEFATURA">SIN-JEFATURA</option>
                                             @forelse ($jefaturas as $jeft)
                                                <option value="{{$jeft->jefatura}}">{{$jeft->jefatura}}</option>
                                             @empty
                                           
                                             @endforelse
                                        </select>
                                    </div>
                                    <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="SI" id="reiteradasFilter">
                                            <label class="form-check-label" for="reiteradasFilter">
                                                Con Averia Reiterada Pendiente
                                            </label>
                                        </div> 
                                       
                                    </div>
                                    
                                    <div class="form-group row mx-0 mb-0 px-2 col-12 col-sm-12 col-md-12 col-lg-12 justify-content-center">
                                            {{--<a href="javascript:void(0)" class="btn btn-sm btn-warning shadow-sm col-12 col-sm-4 m-1" id="limpiarFiltro">Limpiar Filtro</a>--}}
                                            <a href="javascript:void(0)" class="btn btn-sm btn-success shadow-sm col-12 col-sm-4 m-1" id="filtroBasicoCuarentena">Filtrar</a>
                                    </div>

                                </section>
                                <div class="content_table_list"> 
                                    <table id="resultCuarentenasList" class="table table-hover table-bordered w-100 tableFixHead table-text-xs">
                                        <thead>
                                            <tr>
                                                <th>Item</th>
                                                <th>Jefatura</th>
                                                <th>IdCliente</th>
                                                <th>Situación</th>
                                                <th style="min-width:100px;">Cliente</th>
                                                <th>Codreq</th>
                                                <th>Tecnico</th>
                                                <th>Fec_liq</th>
                                                <th>Reiterada</th>
                                                <th>Cmts</th>
                                                <th>Interface</th>
                                                <th>Nodo</th>
                                                <th>Troba</th>
                                                <th>State</th>
                                                <th>PwUP</th>
                                                <th>SnrUP</th>
                                                <th>PwDN</th>
                                                <th>SnrDN</th>
                                                <th>RxPwr</th>
                                                <th>Modem</th>
                                                <th style="min-width:80px;">ESTADO_NIVELES</th>
                                                <th>ESTADO_GESTION</th>
                                                <th>Gestion</th>
                                            </tr>
                                        </thead>  
                                    </table>
                                </div>
                        </div>
                    </div>
                </section> 
            </div>

            @if(Auth::user()->HasPermiso('submodulo.cuarentenas.gestion-individual.store'))
                <div class="tab-pane fade " id="gestionIndividualTab" role="tabpanel" aria-labelledby="gestionIndividualTab-tab">
                        <section  class="col-12 mx-0 px-0">
                                <div class="card">
                                    <div class="card-header px-2 py-1">
                                        <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_cuarentenas"><i class="fa fa-arrow-left"></i> Atras Cuarentenas</a>
                                        <a href="javascript:void(0)" id="registrosGestionesCuarentenas"  class="btn btn-sm btn-outline-primary shadow-sm"><i class="fa fa-arrow-right"></i> Historial de gestiones</a>
                                        <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                                    </div>
                                    <div class="card-body">
                                        <h4 class="w-100 text-center text-uppercase font-weight-bold text-secondary">Registro de Gestión</h4>
                                        <div id="preloadCuarentenaGestionInd"></div>
                                        <div id="storeCuarentenaGestionIndividual" class="row m-0 p-0"> 
                                            <div class="form-group row mx-0 px-2 col-12 col-sm-12" id="resultadoStoreGestionCuarentena">
                                                 
                                            </div>
                                            <div class="form-group row mx-0 px-2 col-12 col-sm-12">
                                                <label for="listaTipoAveriaStore" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Tipo Averia: </label>
                                                <select name="listaTipoAveriaStore" id="listaTipoAveriaStore" class="col-sm-7  col-md-8 form-control form-control-sm shadow-sm validateSelect"> 
                                                        <option value="seleccionar" selected>Seleccionar</option> 
                                                        <option value="PUNTUAL" >PUNTUAL</option> 
                                                        <option value="MASIVA" >MASIVA</option> 
                                                        <option value="APAGA MODEM" >APAGA MODEM</option> 
                                                        <option value="NO DESEA ATENCION" >NO DESEA ATENCIÓN</option> 
                                                        <option value="TRATAMIENTO COMERCIAL" >TRATAMIENTO COMERCIAL</option> 
                                                        <option value="INUBICABLE" >INUBICABLE</option> 
                                                        
                                                </select>
                                            </div>
                                            <div class="form-group row mx-0 px-2 col-12 col-sm-12">
                                                    <label for="observacionesStore" class="col-form-label col-form-label-sm mb-0 px-0">Obervaciones: </label>
                                                    <textarea name="observacionesStore" id="observacionesStore" cols="30" rows="10" class="form-control form-control-sm shadow-sm validateText"></textarea>
                                            </div>
                                            <div class="form-group row mx-0 px-2 col-12 col-sm-12 justify-content-center">
                                                   <a href="javascript:void(0)" class="btn btn-sm btn-outline-primary shadow-sm" id="registrarGestionInd">Enviar</a>
                                            </div>
                                        </div>
                                                
                                    </div>
                                </div>
                        </section>
                </div>
                <div class="tab-pane fade " id="historicoGestionIndividualTab" role="tabpanel" aria-labelledby="historicoGestionIndividualTab-tab">
                        <section  class="col-12 mx-0 px-0">
                                <div class="card">
                                    <div class="card-header px-2 py-1">
                                        <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_cuarentenas"><i class="fa fa-arrow-left"></i> Atras Cuarentenas</a>
                                        <a href="javascript:void(0)" id="returnStoreGestionesCuarentenas"  class="btn btn-sm btn-outline-primary shadow-sm"><i class="fa fa-arrow-left"></i> Atras registro gestion</a>
                                        <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                                    </div>
                                    <div class="card-body">
                                        <h4 class="w-100 text-center text-uppercase font-weight-bold text-secondary">Historico de gestión Cuarentena</h4>
                                        <div class="content_table_list"> 
                                            <table id="resultHistoricoGestionCuarentena" class="table table-hover table-bordered w-100 tableFixHead table-text-xs">
                                                <thead>
                                                    <tr>
                                                        <th>FechaHora</th>
                                                        <th>IdCliente</th>
                                                        <th>Observaciones</th>
                                                        <th>Tipo Averia</th>
                                                        <th>Usuario</th>
                                                    </tr>
                                                </thead>  
                                            </table>
                                        </div>
                                    </div>
                                </div>
                        </section>
                </div>
                <script>
                    GESTION_PERMISO = true
                </script>
                <script src="{{ url('/js/sistema/modulos/cuarentenas/gestion-individual.min.js') }}"></script>
            @endif
            
        </div>
           
    </div>

    
@endsection

@section('scripts-footer')   
 
    <script src="{{ url('/js/sistema/modulos/cuarentenas/index.min.js') }}"></script>

  
     
@endsection