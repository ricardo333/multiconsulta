@extends('layouts.master')

@section('titulo_pagina_sistema', 'Trabajos Programados')
 
@section('estilos')
    <style>
        .figura_aper_cierre_image > .img-apertura{
            width: auto;
            margin: auto;
            height: auto;
        }
        .imagenes_ttpp_aper_cierre {
            width: 55px;
            max-height: 44px;
            display: block;
            margin: auto;
            cursor: pointer;
        }
    </style>

@endsection
@section('scripts-header')
    <script>
        var GESTION_INDIV_PERMISO = false
        var REGISTRAR_TP_PERMISO = false
        var DESCARGAR_CLIENTES_PERMISO = false
        var APERTURAR_TP_PERMISO = false
        var CERRAR_TP_PERMISO = false
        var CANCELAR_TP_PERMISO = false
 
    </script>
    @php
        $APERTURAR_TP_PERMISO = false;
        $CERRAR_TP_PERMISO = false;
        $CANCELAR_TP_PERMISO = false;
        $APER_CERR_CANC_COLUMNA_PERMISO = false;
        $GESTION_INDIV_PERMISO = false;
        $DESCARGA_CLIENTES_PERMISO = false;
        $REGISTRAR_TP_PERMISO = false; 
 
    @endphp
@endsection

@section('top-left-submenus')
    @parent
    {{-- Menu Top--}}
@endsection

@section('title-container')
     <h4 class="m-0 text-dark text-uppercase">Trabajos Programados</h4> 
    
@endsection
@section('ruta-navegacion-container')
    @parent
     <li class="breadcrumb-item active">Trabajos Programados</li>
@endsection

@section('aside-right')
    {{-- Aqui el aside del lado derecho, ingresar lo que sedea mostrar--}}
@endsection

@section('content')
    @parent

    @include('administrador.modulos.trabajosProgramados.partials.descargasTPModal')
    @include('administrador.modulos.trabajosProgramados.partials.imagenDetallesModal')

    @if(Auth::user()->HasPermiso('submodulo.trabajos-programados.aperturar'))
        <script>  APERTURAR_TP_PERMISO = true </script>
        @php 
            $APER_CERR_CANC_COLUMNA_PERMISO = true; 
            $APERTURAR_TP_PERMISO = true; 
        @endphp
    @endif
    @if(Auth::user()->HasPermiso('submodulo.trabajos-programados.cerrar'))
        <script>  CERRAR_TP_PERMISO = true </script>
        @php 
            if($APER_CERR_CANC_COLUMNA_PERMISO = true) $APER_CERR_CANC_COLUMNA_PERMISO = true; 
            $CERRAR_TP_PERMISO = true; 
        @endphp
    @endif
    @if(Auth::user()->HasPermiso('submodulo.trabajos-programados.cancelar'))
        <script>  CANCELAR_TP_PERMISO = true </script>
        @php 
            if($APER_CERR_CANC_COLUMNA_PERMISO = true) $APER_CERR_CANC_COLUMNA_PERMISO = true; 
            $CANCELAR_TP_PERMISO = true;
        @endphp
    @endif
    @if(Auth::user()->HasPermiso('submodulo.trabajos-programados.gestion-individual.store'))
        <script>  GESTION_INDIV_PERMISO = true </script>
        @php 
             $GESTION_INDIV_PERMISO = true; 
        @endphp
    @endif
    @if(Auth::user()->HasPermiso('submodulo.trabajos-programados.descargar-clientes'))
        <script>  DESCARGAR_CLIENTES_PERMISO = true </script>
        @php 
             $DESCARGA_CLIENTES_PERMISO = true; 
        @endphp
    @endif
    @if(Auth::user()->HasPermiso('submodulo.trabajos-programados.store'))
        <script>  REGISTRAR_TP_PERMISO = true </script>
        @php 
             $REGISTRAR_TP_PERMISO = true; 
        @endphp
    @endif

    
    <div class="row">

        <div class="tab-content w-100" id="tabsTrabajosPContent">

            <div class="tab-pane fade show   active" id="listaTrabajoPTab" role="tabpanel" aria-labelledby="listaTrabajoPTab-tab">
                <section  class="col-12 mx-0 px-0">
                    <div class="card-header px-2 py-1"> 
                        <a href="{{route('administrador')}}" class="btn btn-sm btn-outline-success mx-1"><i class="fa fa-arrow-left"></i> Atras</a>
                         @if ($REGISTRAR_TP_PERMISO)
                            <a href="javascript:void(0)" class="btn btn-sm btn-outline-success shadow-sm mx-1" id="redirectRegisterTP"> Registrar <i class="icofont-worker icofont-md"></i></a>
                         @endif
                         @if (Auth::user()->HasPermiso('submodulo.trabajos-programados.mantenimiento'))
                            <a href="javascript:void(0)" class="btn btn-sm btn-outline-success shadow-sm mx-1" id="redirectMantenimientoTP"> Mantenimiento <i class="icofont-worker icofont-md"></i></a>
                         @endif
                        <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                    </div>
                    <div class="card-body position-relative">   
                        <section class="row w-100 my-3 mx-0 py-2 content_filter_basic" id="contentZonasFiltro" style="display:none;">
                            <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                <label for="listaJefaturasTP" class="col-12 col-sm-3">Jefaturas:</label>
                                <select name="listaJefaturasTP" id="listaJefaturasTP" class="col-12 col-sm-9 form-control form-control-sm shadow-sm">
                                    <option value="seleccionar">Sin Filtro</option> 
                                    @forelse ($jefaturas as $jeft)
                                        <option value="{{$jeft->jefatura}}">{{$jeft->jefatura}}</option> 
                                    @empty
                                        
                                    @endforelse
                                </select>
                            </div> 
                            <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                    <label for="listaEstadosTP" class="col-12 col-sm-3">Estados:</label>
                                    <select name="listaEstadosTP" id="listaEstadosTP" class="col-12 col-sm-9 form-control form-control-sm shadow-sm">
                                        <option value="seleccionar">Sin Filtro</option>
                                        @forelse ($estados as $est)
                                            <option value="{{$est->ESTADO}}">{{$est->ESTADO}}</option>
                                        @empty
                                            
                                        @endforelse
                                    </select>
                            </div>
                            <div class="form-group row mx-0 mb-0 px-2 col-12 col-sm-12 col-md-12 col-lg-12 justify-content-center">
                                    <a href="javascript:void(0)" class="btn btn-sm btn-primary shadow-sm w-25" id="filtrobasicoTP">Filtrar</a>
                            </div>
                        </section>
                        <div class="content_table_list"> 
                            <table id="resultTrabajosProg" class="table table-hover table-bordered w-100 tableFixHead table-text-xs">
                                <thead>
                                    <tr>
                                        @if ($APER_CERR_CANC_COLUMNA_PERMISO || $GESTION_INDIV_PERMISO || $DESCARGA_CLIENTES_PERMISO)
                                            <th>ACCIONES</th>
                                        @endif 
                                        <th>ITEM</th>
                                        <th>CALLS</th>
                                        <th>AVERIAS</th>
                                        <th>TROBA</th>
                                        <th>JEFATURA</th>
                                        <th>AMP</th>
                                        <th>TIPODETRABAJO</th>
                                        <th>SUPERVISOR</th>
                                        <th>USER</th>
                                        <th>FINICIO</th>
                                        <th>CORTESN</th>
                                        <th>ESTADO</th>
                                        <th>FECHA</th>
                                        <th>HORA</th>
                                        <th>TRABAJO</th>
                                        <th>REMEDY</th>
                                        <th>TECNICO</th>
                                        <th>RPM</th>
                                        <th>CONTRATA</th>
                                        <th>OBSERVACIONES</th>
                                        <th>FECHA_REGISTRO</th>
                                        <th>FECHA_APERTURA</th>
                                        <th> IMAGEN_APERTURA </th>
						                <th> IMAGEN_CIERRE </th>
                                    </tr>
                                </thead>  
                            </table>
                        </div>
                    </div>
                </section> 
            </div> 
            @if($REGISTRAR_TP_PERMISO) 
                <div class="tab-pane fade " id="RegistroTPTab" role="tabpanel" aria-labelledby="RegistroTPTab-tab">
                    <section  class="col-12 mx-0 px-0">
                        <div class="card">
                            <div class="card-header px-2 py-1">
                                <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_trabajoPListTab"><i class="fa fa-arrow-left"></i> Atras Lista TP</a>
                                <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                            </div>
                            <div class="card-body">
                                <h5 class="w-100 d-block text-center">Registro de Trabajos Programados</h5>
                                <section id="preprocesoRegistroTP" ></section>
                                <section class="form row m-0" id="contentRegistroTP">
                                    {{-- --}}
                                        <div class="form-group row mx-0 px-2 col-12 col-sm-12">
                                            <label for="nodoPlanoLista" class="col-form-label col-form-label-sm mb-0 px-0">Nodo - Plano: </label>
                                        </div>
                                        <div class="form-group row col-12 mx-0 p-0 ">
                                            <div class="dual-list list-left col-md-5">
                                                <div class="well text-right">
                                                    <div class="input-group" style="line-height: normal;">
                                                        <button class="btn btn-sm btn-outline-primary shadow-sm"><i class="icofont-search-2 icofont-md"></i></button>
                                                        <input type="text" name="SearchDualList1" class="form-control  form-control-sm shadow-sm text-primary " placeholder="search" />
                                                    </div>
                                        
                                                    <select multiple="multiple" size="10" name="duallistbox_demo1" id="nodoPlanoLista" class="mdb-select md-form demo1 form-control form-control-sm shadow-sm" style="width: -webkit-fill-available;">
                                                        @forelse ($nodoTrobas as $nt)
                                                            <option value="{{$nt->nodo}}-{{$nt->plano}}">{{$nt->nodo}}-{{$nt->plano}}</option>
                                                        @empty
                                                        
                                                        @endforelse 
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="list-arrows col-md-2 text-center d-flex align-self-center flex-column justify-content-center align-items-center">
                                                
                                                <button id="btnRightTrobas" class="btn btn-sm btn-outline-success shadow-sm m-1">
                                                    <i class="icofont-rounded-right"></i>
                                                </button>
                                                <button id="btnLeftTrobas" class="btn btn-sm btn-outline-success shadow-sm m-1" >
                                                    <i class="icofont-rounded-left"></i>
                                                </button>
                                            
                                                
                                            </div>

                                            <div class="dual-list list-left col-md-5">
                                                <div class="well text-right">
                                                    <div class="input-group" style="line-height: normal;">
                                                        <button class="btn btn-sm btn-outline-primary shadow-sm"><i class="icofont-search-2 icofont-md"></i></button>
                                                        <input type="text" name="SearchDualList2" class="form-control form-control-sm shadow-sm text-primary" placeholder="search" />
                                                    </div>

                                                    <select multiple="multiple" size="10" name="duallistbox_demo2" id="nodoPlanoStore" class="demo2 form-control form-control-sm shadow-sm validateSelect" style="width: -webkit-fill-available;">
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row mx-0 px-2 col-12 col-sm-6 col-md-6 col-lg-6 ">
                                            <label for="amplificadorStore" class="">Amplificador:</label>
                                            <input type="text" name='amplificadorStore'  id="amplificadorStore" class="tooltip_jy form-control form-control-sm shadow-sm validateText" placeholder="Mas de 1 separar con comas(,)">
                                        </div>
                                        <div class="form-group row mx-0 px-2 col-12 col-sm-6 col-md-6 col-lg-6 ">
                                            <label for="tipoTrabajoStore" class="">Tipo de Trabajo:</label>
                                            <select name="tipoTrabajoStore" id="tipoTrabajoStore" class="form-control form-control-sm shadow-sm validateSelect"> 
                                                    @forelse ($tipoTrabajo as $tipot)
                                                    <option value="{{$tipot->id}}">{{$tipot->tipodetrabajo1}}</option>
                                                    @empty
                                                
                                                    @endforelse
                                            </select>
                                        </div>
                                        <div class="form-group row mx-0 px-2 col-12 col-sm-6 col-md-6 col-lg-6 ">
                                            <label for="remeryStore" class="">Remedy:</label>
                                            <input type="text" name='remeryStore'  id="remeryStore" class="tooltip_jy form-control form-control-sm shadow-sm validateText" placeholder="minimo 8 caracteres">
                                        </div>
                                        <div class="form-group row mx-0 px-2 col-12 col-sm-6 col-md-6 col-lg-6 ">
                                            <label for="supervisorTdpStore" class="">Supervisor TDP:</label>
                                            <div class="w-100" id="supervisorTdpContentStore">
                                                <select name="supervisorTdpStore" id="supervisorTdpStore" class="form-control form-control-sm shadow-sm validateSelect"> 
                                                        @forelse ($supervisorTDP as $suptdp)
                                                            <option value="{{$suptdp->id}}">{{$suptdp->supervisor}}</option>
                                                        @empty
                                                    
                                                        @endforelse
                                                </select>
                                            </div>
                                            
                                        </div>
                                        <div class="form-group row mx-0 px-2 col-12 col-sm-6 col-md-6 col-lg-6 ">
                                            <label for="fechaInicioStore" class="">Fecha de Inicio:</label>
                                            <div class="w-100" id="fechaInicioContentStore">
                                                {{--<span class="text-secondary font-italic text-sm">Se debe seleccionar el Tipo de Trabajo.</span>--}}
                                                <input id="fechaInicioStore" type="date" value="{{$fechaInicio}}" min="{{$fechaInicio}}" step="1" class="form-control form-control-sm shadow-sm validateText">
                                            </div>
                                                        
                                        </div> 
                                        <div class="form-group row mx-0 px-2 col-12 col-sm-6 col-md-6 col-lg-6 ">
                                            <label for="celularSupTdpStore" class="">Celular Sup. TDP:</label>
                                            <input type="text" name='celularSupTdpStore'  id="celularSupTdpStore" class="tooltip_jy form-control form-control-sm shadow-sm validateText" placeholder="minimo 10 caracteres">		
                                        </div> 
                                        <div class="form-group row mx-0 px-2 col-12 col-sm-6 col-md-6 col-lg-6 ">
                                            <label for="afectacionStore" class="">Afectación:</label>
                                            <select name="afectacionStore" id="afectacionStore" class="form-control form-control-sm shadow-sm validateSelect">  
                                                    <option value="TOTAL">TOTAL</option>
                                                    <option value="PARCIAL">PARCIAL</option> 
                                            </select>
                                        </div>
                                        <div class="form-group row mx-0 px-2 col-12 col-sm-6 col-md-6 col-lg-6 ">
                                            <label for="hInicioStore" class="">Hora de inicio:</label>
                                            <input type="time" name="hInicioStore" id="hInicioStore" min="00:00" max="23:00" step="3600" class="form-control form-control-sm shadow-sm validateText"> 
                                        </div> 
                                        <div class="form-group row mx-0 px-2 col-12 col-sm-6 col-md-6 col-lg-6 ">
                                            <label for="hTerminoStore" class="">Hora de termino:</label>
                                            <input type="time" name="hTerminoStore" id="hTerminoStore" min="00:00" max="23:00" step="3600" class="form-control form-control-sm shadow-sm validateText"> 
                                        </div> 
                                        <div class="form-group row mx-0 px-2 col-12 col-sm-6 col-md-6 col-lg-6 ">
                                            <label for="CORTESN" class="">Estado del Servicio:</label>
                                            <div class="w-100">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="CORTESN" id="conCorteStore" value="CON CORTE" checked>
                                                    <label class="form-check-label" for="conCorteStore">CON CORTE</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="CORTESN" id="sinCorteStore" value="SIN CORTE">
                                                    <label class="form-check-label" for="sinCorteStore">SIN CORTE</label>
                                                </div> 
                                            </div>
                                            
                                        </div> 
                                        <div class="form-group row mx-0 px-2 col-12">
                                            <label for="observacionStore">Observación Registro:</label>
                                            <textarea class="form-control form-control-sm shadow-sm validateText" id="observacionStore" rows="3" style="max-height:115px;min-height:115px;"></textarea>
                                        </div> 
                                        <div class="form-group row mx-0 px-2 col-12 justify-content-center text-danger" id="errorStoreTP">
                                            
                                        </div> 
                                        <div class="form-group row mx-0 px-2 col-12 justify-content-center">
                                            <a href="javascript:void(0)" class="btn btn-sm btn-outline-primary shadow-sm w-50" id="registrarTrabProg">Registrar</a>
                                        </div> 

                                    {{-- --}}
                                </section>
                            </div>
                        </div>
                    </section>
                </div> 
            @endif
            @if($CANCELAR_TP_PERMISO) 
                <div class="tab-pane fade " id="CancelartrabajoProgtab" role="tabpanel" aria-labelledby="CancelartrabajoProgtab-tab">
                    <section  class="col-12 mx-0 px-0">
                        <div class="card">
                            <div class="card-header px-2 py-1">
                                <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_trabajoPListTab"><i class="fa fa-arrow-left"></i> Atras Lista TP</a>
                                <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                            </div>
                            <div class="card-body">
                                <h5 class="w-100 d-block text-center mb-3 text-danger">Detalles del Trabajo Programado a Cancelar</h5> 
                                <section class="w-100" id="contentCancelarTP">
                                    
                                </section>
                            </div>
                        </div>
                    </section>
                </div> 
            @endif
            @if($APERTURAR_TP_PERMISO) 
                <div class="tab-pane fade " id="AperturarTrabajoProgtab" role="tabpanel" aria-labelledby="AperturarTrabajoProgtab-tab">
                    <section  class="col-12 mx-0 px-0">
                        <div class="card">
                            <div class="card-header px-2 py-1">
                                <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_trabajoPListTab"><i class="fa fa-arrow-left"></i> Atras Lista TP</a>
                                <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                            </div>
                            <div class="card-body">
                                <h5 class="w-100 d-block text-center mb-3 text-danger">Detalles del Trabajo Programado por Aperturar</h5> 
                                <section class="w-100" id="contentAperturarTP">
                                    {{-- --}}
                                        <div class="w-100" id="preloadAperturaTrabajoProg"></div>
                                        <div id="contentAcordionApertura">
                                            <div class="card">
                                                <div class="card-header p-1" id="cabezeraDetallePendienteTP">
                                                <h5 class="mb-0">
                                                    <button class="btn btn-link shadow-sm p-0 w-100 text-left collapsed" data-toggle="collapse" data-target="#collapseDetallePendienteTP" aria-expanded="false" aria-controls="collapseDetallePendienteTP">
                                                     Detalle Del T.P. Pendiente
                                                    </button>
                                                </h5>
                                                </div>
                                            
                                                <div id="collapseDetallePendienteTP" class="collapse " aria-labelledby="cabezeraDetallePendienteTP" data-parent="#contentAcordionApertura">
                                                <div class="card-body p-0 " id="contentDetalleTPApertura">
                                                   
                                                </div>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-header p-1" id="cabezeraRequeridoApertura">
                                                <h5 class="mb-0">
                                                    <button class="btn btn-link shadow-sm p-0 w-100 text-left" data-toggle="collapse" data-target="#collapseRequeridoApertura" aria-expanded="true" aria-controls="collapseRequeridoApertura">
                                                     Datos Requeridos para Apertura del T.P.
                                                    </button>
                                                </h5>
                                                </div>
                                                <div id="collapseRequeridoApertura" class="collapse show" aria-labelledby="cabezeraRequeridoApertura" data-parent="#contentAcordionApertura">
                                                <div class="card-body p-0 ">
                                                    <div id="preloadSendAperturaTP"></div>
                                                    <div class="w-100" id="contentFormAperturaSend">
                                                        {{-- --}}
                                                            <section class="form col-12 pt-2 form_apertura" id="form_apertura_send">  
                                                                    
                                                            </section>
                                                        {{-- --}}
                                                    </div>
                                                </div>
                                                </div>
                                            </div>
                                        </div>
                                    {{-- --}}
                                </section>
                            </div>
                        </div>
                    </section>
                </div> 
            @endif

            @if($APERTURAR_TP_PERMISO) 
                <div class="tab-pane fade " id="CerrarTrabajoProgtab" role="tabpanel" aria-labelledby="CerrarTrabajoProgtab-tab">
                    <section  class="col-12 mx-0 px-0">
                        <div class="card">
                            <div class="card-header px-2 py-1">
                                <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_trabajoPListTab"><i class="fa fa-arrow-left"></i> Atras Lista TP</a>
                                <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                            </div>
                            <div class="card-body">
                                <h5 class="w-100 d-block text-center mb-3 text-danger">Detalles del Trabajo Programado por Cerrar</h5> 
                                <section class="w-100" id="contentCerrarTP">
                                    {{-- --}}
                                        <div class="w-100" id="preloadCerrarTrabajoProg"></div>
                                        <div id="contentAcordionCerrarTP">
                                            <div class="card">
                                                <div class="card-header p-1" id="cabezeraDetTPCierre">
                                                <h5 class="mb-0">
                                                    <button class="btn btn-link shadow-sm p-0 w-100 text-left collapsed" data-toggle="collapse" data-target="#collapseDetTPCIerre" aria-expanded="false" aria-controls="collapseDetTPCIerre">
                                                     Detalle Del T.P. En Proceso
                                                    </button>
                                                </h5>
                                                </div>
                                            
                                                <div id="collapseDetTPCIerre" class="collapse " aria-labelledby="cabezeraDetTPCierre" data-parent="#contentAcordionCerrarTP">
                                                <div class="card-body p-0 " id="contentDetalleTPCierre">
                                                   
                                                </div>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-header p-1" id="cabezeraRequeridoCierre">
                                                <h5 class="mb-0">
                                                    <button class="btn btn-link shadow-sm p-0 w-100 text-left" data-toggle="collapse" data-target="#collapseRequeridoCierre" aria-expanded="true" aria-controls="collapseRequeridoCierre">
                                                     Datos Requeridos para Cerrar el T.P.
                                                    </button>
                                                </h5>
                                                </div>
                                                <div id="collapseRequeridoCierre" class="collapse show" aria-labelledby="cabezeraRequeridoCierre" data-parent="#contentAcordionCerrarTP">
                                                <div class="card-body p-0 ">
                                                    <div id="preloadSendCierreTP"></div>
                                                    <div class="w-100" id="contentFormCierreSend">
                                                        {{-- --}}
                                                            <section class="form col-12 pt-2 form_cierre" id="form_cierre_send">  
                                                                    
                                                            </section>
                                                        {{-- --}}
                                                    </div>
                                                </div>
                                                </div>
                                            </div>
                                        </div>
                                    {{-- --}}
                                </section>
                            </div>
                        </div>
                    </section>
                </div> 
            @endif

            
            @if($GESTION_INDIV_PERMISO)
                <div class="tab-pane fade " id="gestionIndividualTab" role="tabpanel" aria-labelledby="gestionIndividualTab-tab">
                        <section  class="col-12 mx-0 px-0">
                                <div class="card">
                                    <div class="card-header px-2 py-1">
                                        <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_trabajoPListTab"><i class="fa fa-arrow-left"></i> Atras Lista TP</a>
                                        <a href="javascript:void(0)" id="registrosGestiones"  class="btn btn-sm btn-outline-primary shadow-sm"><i class="fa fa-arrow-right"></i> Historial de gestiones</a>
                                        <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                                    </div>
                                    <div class="card-body">
                                        <h4 class="w-100 text-center text-uppercase font-weight-bold text-secondary">Registro de Gestión</h4>
                                                <input type="hidden" value="" class="validateText" id="IdTrabajoProgItemProcesar">
                                                @include('administrador.partials.gestionTrobaForm')
                                                
                                    </div>
                                </div>
                        </section>
                </div> 
                <script src="{{ url('/js/sistema/modulos/trabajos-programados/gestion-individual.min.js') }}"></script>

                <div class="tab-pane fade " id="registrosGestionesTab" role="tabpanel" aria-labelledby="registrosGestionesTab-tab">
                    <section  class="col-12 mx-0 px-0">
                            <div class="card">
                                <div class="card-header px-2 py-1">
                                    <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_trabajoPListTab"><i class="fa fa-arrow-left"></i> Atras Lista TP</a>
                                    <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                                </div>
                                <div class="card-body position-relative">
                                    <h5 class="h5 text-center d-block ">Detalle Historial Gestión</h5>
                                    <section class="row my-3 py-2 content_filter_basic" id="filtroContentHistorialGestion" style="display:none;">
                                        <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                            <label for="" class="col-12 col-sm-3">Nodo:</label>
                                                <input type="text" id="nodoFilterHistoricoGestion" class="form-control form-control-sm shadow-sm">
                                        </div>
                                        <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                                <label for="" class="col-12 col-sm-3">Troba:</label>
                                                <input type="text" id="trobaFilterHistoricoGestion" class="form-control form-control-sm shadow-sm">
                                        </div>
                                        <div class="form-group row mx-0 px-2 col-12 col-sm-12 justify-content-center text-center text-danger" id="errors_filter_historico_gestion">
                                            
                                        </div>
                                        <div class="form-group row mx-0 mb-0 px-2 col-12 col-sm-12 col-md-12 col-lg-12 justify-content-center">
                                                <a href="javascript:void(0)" class="btn btn-sm btn-primary shadow-sm w-25" id="filtroBasicoHistoricoGestion">Filtrar</a>
                                        </div>
                                    </section> 
                                    @include('administrador.partials.historialGestion')
                                </div>
                            </div>
                    </section>
                </div>
                <script src="{{ url('/js/sistema/modulos/trabajos-programados/historial-gestion.min.js') }}"></script>
            @endif

            @if (Auth::user()->HasPermiso('submodulo.trabajos-programados.mantenimiento'))
                <div class="tab-pane fade " id="mantenimientoTProgTab" role="tabpanel" aria-labelledby="mantenimientoTProgTab-tab">
                    <section  class="col-12 mx-0 px-0">
                            <div class="card">
                                <div class="card-header px-2 py-1">
                                    <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_trabajoPListTab"><i class="fa fa-arrow-left"></i> Atras Lista TP</a>
                                    <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                                </div>
                                <div class="card-body position-relative">
                                    <h5 class="h5 text-center d-block ">Mantenimiento de Trabajos Programados</h5>

                                    <div class="content_mantenimiento">
                                        <ul class="nav nav-tabs" id="myMantenimiento" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="nodotrobas-tab" data-toggle="tab" href="#nodotrobas" role="tab" aria-controls="nodotrobas" aria-selected="true">Nodo - Trobas</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="tipotrabajo-tab" data-toggle="tab" href="#tipotrabajo" role="tab" aria-controls="tipotrabajo" aria-selected="false">Tipo de Trabajo</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="registersuperviprog-tab" data-toggle="tab" href="#registersuperviprog" role="tab" aria-controls="registersuperviprog" aria-selected="false">Supervisor</a>
                                            </li>
                                            <li class="nav-item">
                                                    <a class="nav-link" id="vinculartrabajo-tab" data-toggle="tab" href="#vinculartrabajo" role="tab" aria-controls="vinculartrabajo" aria-selected="false">Vincular Trabajos</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="tab-content" id="myMantenimientoContent">
                                        <div class="tab-pane fade show active" id="nodotrobas" role="tabpanel" aria-labelledby="nodotrobas-tab">
                                                <div id="preloadMantenimientoNodoTroba"></div>    
                                                <div class="container mt-3" id="form_mantenimiento_nodos_trobas">
                                                    <div class="form-group">
                                                        <div class="row">
                                                                <div class="col-12 text-center" id="result_r_nodo_troba">
                            
                                                                </div>
                                                        </div>
                                                    </div>   
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label for="NEW_NODO">Ingresar Nuevo Nodo:</label>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <input type="text" name="NEW_NODO" id="NEW_NODO" value ="" maxlength="2" size="5" pattern="[A-Z0-9]+" title="Solo ingresar MAYUSCULAS y NUMEROS" class="form-control form-control-sm shadow-sm"/>  
                                                                <small id="NEW_NODO" class="form-text text-muted">* 2 caracteres</small>
                                                            </div>
                                                        </div>
                                                    </div>   
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label for="NEW_TROBA">Ingresar Nueva Troba: R</label>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <input type="TEXT" NAME="NEW_TROBA" id="NEW_TROBA" VALUE ="" MAXLENGTH="3" size="5" pattern="[0-9]+" title="Solo ingresar NUMEROS" class="form-control form-control-sm shadow-sm" />
                                                                <small id="NEW_NODO" class="form-text text-muted">*Minimo 3 caracteres</small>
                                                            </div>
                                                        </div>
                                                    </div>   
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-12 text-center">
                                                                    <a href="javascript: void(0)"  id="GUARDAR_NODO" class="btn btn-sm btn-outline-success shadow-sm">GUARDAR</a>
                                                            </div>
                                                        </div>
                                                    </div>  
                                                </div> 
                                        </div>
                                        <div class="tab-pane fade" id="tipotrabajo" role="tabpanel" aria-labelledby="tipotrabajo-tab"> 
                                            <div id="preloadMantenimientoTipoTrabajo"></div>    
                                            <div class="container mt-3" id="form_mantenimiento_tipo_trabajo">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-12 text-center" id="result_r_tipo_trabajos"></div>
                                                    </div>
                                                </div>  
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label for="NEW_TRABAJO"> Ingresar Nuevo Tipo de Trabajo:</label>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="text" name="NEW_TRABAJO" id="NEW_TRABAJO" value="" size="40" pattern="[A-Z0-9 _-]+" title="Solo ingresar MAYUSCULAS" class="form-control form-control-sm shadow-sm" > 
                                                        </div>
                                                    </div>
                                                </div>  
                                                <div class="form-group">
                                                    <div class="row"> 
                                                        <div class="col-12 text-center">  
                                                            <a href="javascript: void(0)" id="GUARDAR_TRABAJO" class="btn btn-sm btn-outline-success shadow-sm">GUARDAR</a> 
                                                        </div>
                                                    </div>
                                                </div>  
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="registersuperviprog" role="tabpanel" aria-labelledby="registersuperviprog-tab">
                                            <div id="preloadMantenimientoSupervidor"></div>   
                                            <div class="container mt-3" id="form_mantenimiento_supervisor">
                                                <div class="form-group">
                                                    <div class="row">
                                                            <div class="col-12" id="result_r_supervisor"></div>
                                                    </div>
                                                </div>  
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label for="new_supervisor">Ingresar Nuevo Supervisor:</label>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="text" name="NEW_SUPERVISOR" id="new_supervisor" value="" size="30" pattern="[A-Z ]+" title="Solo ingresar MAYUSCULAS" class="form-control form-control-sm shadow-sm" >
                                                        </div>
                                                    </div>
                                                </div>  
                                                <div class="form-group">
                                                    <div class="row">
                                                        
                                                        <div class="col-12 text-center">  
                                                            <a href="javascript: void(0)" id="GUARDAR_SUPERVISOR" class="btn btn-sm btn-outline-success shadow-sm">GUARDAR</a> 
                                                        </div>
                                                    </div>
                                                </div> 
                                                {{--<script src="js/mantenimiento_supervisor.js"></script>--}}
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="vinculartrabajo" role="tabpanel" aria-labelledby="vinculartrabajo-tab">
                                                <div id="preloadMantenimientoSupervidorTipoTrabajo"></div>   
                                                <div class="container mt-3 p-0" id="form_mantenimiento_supervisor_tipo_trabajo">
                                                    <div class="form-group">
                                                        <div class="row">
                                                           <div class="col-12" id="result_asignaciones_supervisor_tipo_trabajo"></div>
                                                        </div>
                                                    </div> 
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                    <label for="mnto_supervisor">SUPERVISOR: </label>
                                                            </div>
                                                            <div class="col-md-6">
                                                                    <select   name='mnto_supervisor' value ='' id="mnto_supervisor" class="form-control form-control-sm shadow-sm">
                                                                        <option value="seleccionar">Seleccionar</option>
                                                                            @foreach ($supervisoresGenerales as $super)
                                                                                <option value="{{ $super->id}}">{{ $super->supervisor1}}</option>
                                                                            @endforeach
                                                                    </select> 
                                                            </div>
                                                        </div>
                                                    </div> 
                                                    <div class="form-group row col-12 mx-0 p-0 ">
                                                        <div class="dual-list list-left col-md-5 px-0">
                                                            <div class="well text-right">
                                                                <div class="input-group" style="line-height: normal;">
                                                                    <button class="btn btn-sm btn-outline-primary shadow-sm"><i class="icofont-search-2 icofont-md"></i></button>
                                                                    <input type="text" name="SearchDualListTrabajos1" class="form-control  form-control-sm shadow-sm text-primary" placeholder="search" />
                                                                </div>
                                                    
                                                                <select multiple="multiple" size="10" name="duallistbox_trabajos1" id="tiposTrabajosNoAsig" class="mdb-select md-form demo1 form-control form-control-sm shadow-sm" style="width: -webkit-fill-available;">
                                                                   
                                                                </select>
                                                            </div>
                                                        </div>
        
                                                        <div class="list-arrows col-md-2 text-center d-flex align-self-center flex-column justify-content-center align-items-center">
                                                            
                                                            <button id="btnRightTrabajos" class="btn btn-sm btn-outline-success shadow-sm m-1">
                                                                <i class="icofont-rounded-right"></i>
                                                            </button>
                                                            <button id="btnLeftTrabajos" class="btn btn-sm btn-outline-success shadow-sm m-1" >
                                                                <i class="icofont-rounded-left"></i>
                                                            </button>
                                                        
                                                            
                                                        </div>
        
                                                        <div class="dual-list list-left col-md-5 px-0">
                                                            <div class="well text-right">
                                                                <div class="input-group" style="line-height: normal;">
                                                                    <button class="btn btn-sm btn-outline-primary shadow-sm"><i class="icofont-search-2 icofont-md"></i></button>
                                                                    <input type="text" name="SearchDualListTrabajo2" class="form-control form-control-sm shadow-sm text-primary" placeholder="search" />
                                                                </div>
        
                                                                <select multiple="multiple" size="10" name="duallistbox_Trabajos2" id="tiposTrabajosAsig" class="demo2 form-control form-control-sm shadow-sm" style="width: -webkit-fill-available;">
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-12" id="result_r_list_trb_by_spv"></div>
                                                            <div class="col-12 text-center">  
                                                                    
                                                                    <a href="javascript: void(0)" class="btn btn-sm btn-outline-success shadow-sm" id="actualizar_s_t_a">GUARDAR CAMBIOS</a>
                                                                        {{--<script src="js/actualizar_superv_trabajo.js"></script>--}}
                                                                    
                                                                    {{--<a href="javascript: void(0)" class="btn btn-sm btn-outline-success shadow-sm" id="detalle_trabajos_supvr_list">CARGAR TRABAJOS ASIGNADOS</a>
                                                                        <script src="js/delete_superv_trabajo.js"></script>--}}
                                                                     
                                                            </div>
                                                        </div>
                                                    </div> 
                                                    
                                                </div>
                                        </div>
                                    </div>
                                     
                                </div>
                            </div>
                    </section>
                </div>
                <script src="{{ url('/js/sistema/modulos/trabajos-programados/mantenimiento.min.js') }}"></script>
            @endif
           
            <div class="tab-pane fade " id="graficoLlamadasTab" role="tabpanel" aria-labelledby="graficoLlamadasTab-tab">
                <section  class="col-12 mx-0 px-0">
                        <div class="card">
                            <div class="card-header px-2 py-1">
                                <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_trabajoPListTab"><i class="fa fa-arrow-left"></i> Atras Lista TP</a>
                                <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                            </div>
                            <div class="card-body position-relative" id="contentGraficoLlamadas">
                                  
                            </div>
                        </div>
                </section>
            </div>
                
            
            
             
        </div>
           
    </div>

    
@endsection

@section('scripts-footer')  

    <script src="{{ url('/library/Highcharts/code/highcharts.js')}}"></script>
    <script src="{{ url('/library/Highcharts/code/modules/export-data.js')}}"></script>
 
    <script src="{{ url('/js/sistema/modulos/trabajos-programados/index.min.js') }}"></script>

    @if($CANCELAR_TP_PERMISO) 
        <script src="{{ url('/js/sistema/modulos/trabajos-programados/cancelar.min.js') }}"></script>
    @endif
    @if($APERTURAR_TP_PERMISO) 
        <script src="{{ url('/js/sistema/modulos/trabajos-programados/aperturar.min.js') }}"></script>
    @endif
    @if($CERRAR_TP_PERMISO) 
        <script src="{{ url('/js/sistema/modulos/trabajos-programados/cerrar.min.js') }}"></script>
    @endif
    @if($DESCARGA_CLIENTES_PERMISO) 
        <script src="{{ url('/js/sistema/modulos/trabajos-programados/descargar-clientes.min.js') }}"></script>
    @endif

    

    @if($REGISTRAR_TP_PERMISO)
        <script>
             
                var FECHA_INICIO = `{{$fechaInicio}}`;
                var DATA_NODO_PLANOS = [];

                const FECHA_INICIO_INICIAL = `{{$fechaInicio}}`;
                const SUPERVISORES_LISTA_INICIAL = <?php echo $supervisorTDPJson; ?>;
  
                let nodoTrovasJson = <?php echo $nodoTrobasJson; ?>;
                nodoTrovasJson.forEach(el => {
                    DATA_NODO_PLANOS.push(`${el.nodo}-${el.plano}`);
                });

                const HISTORICO_NODO_PLANOS = DATA_NODO_PLANOS;
   
        </script>
        <script src="{{ url('/js/sistema/modulos/trabajos-programados/store.min.js') }}"></script>
    @endif

    
     
    
    

@endsection