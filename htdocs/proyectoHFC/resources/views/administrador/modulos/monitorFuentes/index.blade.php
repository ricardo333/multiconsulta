@extends('layouts.master')

@section('titulo_pagina_sistema', 'Monitor Fuentes')
 
@section('estilos')
    <style>
    #mapa_content_fuentes {
        height: calc(100vh - 150px);
    }
    .iframe_fuentes{
        width: 100%;
        height: calc(100vh - 160px);
    }
    </style>

@endsection
@section('scripts-header')
        <script>
            var DIAGNOSTICOM_PERMISO = false
            var MAPAFUENTES_PERMISO = false
            var GRAFICOFUENTES_PERMISO = false
            var DESCARGAR_ALERTAS_DOWN_PERMISO = false
            var DESCARGAR_HISTORICO_DOWN_PERMISO = false
            var DETALLE_MULTILINK_PERMISO = false
            var EDITAR_FUENTE_PERMISO = false
            var REFRESH_PERMISO = false
           
        </script>
@endsection
@php
    $DIAGNOSTICOM_PERMISO = false;
    $MAPAFUENTES_PERMISO = false;
    $GRAFICOFUENTES_PERMISO = false;
    $DESCARGAR_ALERTAS_DOWN_PERMISO = false;
    $DESCARGAR_HISTORICO_DOWN_PERMISO = false;
    $DETALLE_MULTILINK_PERMISO = false;
    $EDITAR_FUENTE_PERMISO = false;
    $REFRESH_PERMISO = false;
@endphp

@section('top-left-submenus')
    @parent
    {{-- Menu Top--}}
@endsection

@section('title-container')
     <h4 class="m-0 text-dark text-uppercase">Monitor Fuentes</h4> 
    
@endsection
@section('ruta-navegacion-container')
    @parent
     <li class="breadcrumb-item active">Monitor Fuentes</li>
@endsection

@section('aside-right')
    {{-- Aqui el aside del lado derecho, ingresar lo que sedea mostrar--}}
@endsection

@section('content')
    @parent

    @include('administrador.modulos.monitorFuentes.partials.descargas')
 

    @if(Auth::user()->HasPermiso('submodulo.monitor-fuentes.diagnostico-masivo.view'))
        <script> DIAGNOSTICOM_PERMISO = true;</script>
        @php
            $DIAGNOSTICOM_PERMISO = true;
        @endphp                                      
    @endif 
    @if(Auth::user()->HasPermiso('submodulo.monitor-fuentes.mapa-fuentes.view'))
        <script> MAPAFUENTES_PERMISO = true;</script>
        @php
            $MAPAFUENTES_PERMISO = true;
        @endphp
    @endif 
    @if(Auth::user()->HasPermiso('submodulo.monitor-fuentes.grafico-fuentes.view'))
        <script> GRAFICOFUENTES_PERMISO = true;</script>
        @php
            $GRAFICOFUENTES_PERMISO = true;
        @endphp
    @endif 
    @if(Auth::user()->HasPermiso('submodulo.monitor-fuentes.download.alertas-down.view'))
        <script> DESCARGAR_ALERTAS_DOWN_PERMISO = true;</script>
        @php
            $DESCARGAR_ALERTAS_DOWN_PERMISO = true;
        @endphp
    @endif 
    @if(Auth::user()->HasPermiso('submodulo.monitor-fuentes.download.historico-down.view'))
        <script> DESCARGAR_HISTORICO_DOWN_PERMISO = true;</script>
        @php
            $DESCARGAR_HISTORICO_DOWN_PERMISO = true;
        @endphp
    @endif 
    @if(Auth::user()->HasPermiso('submodulo.monitor-fuentes.multilink.detalles'))
        <script> DETALLE_MULTILINK_PERMISO = true;</script>
        @php
            $DETALLE_MULTILINK_PERMISO = true;
        @endphp
    @endif 
    @if(Auth::user()->HasPermiso('submodulo.monitor-fuentes.editar'))
        <script> EDITAR_FUENTE_PERMISO = true;</script>
        @php
            $EDITAR_FUENTE_PERMISO = true;
        @endphp
    @endif 
    @if(Auth::user()->HasPermiso('submodulo.monitor-fuentes.refresh'))
        <script> REFRESH_PERMISO = true;</script>
        @php
            $REFRESH_PERMISO = true;
        @endphp
    @endif 

     
    <div class="row">

        <div class="tab-content w-100" id="tabsMonitorFuentesContent">
            <div class="tab-pane listaMonitorFuentesTotal fade show   active" id="monitorFuentesListTab" role="tabpanel" aria-labelledby="monitorFuentesListTab-tab">
                <section  class="col-12 mx-0 px-0">
                <div class="card-header px-2 py-1"> 
                        <a href="{{route('administrador')}}" class="btn btn-sm btn-outline-success mx-1"><i class="fa fa-arrow-left"></i> Atras</a>
                        <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                </div>
                <div class="card">
                        <div class="card-body position-relative" id="contenedor_mFuentes_lista_body">
                                <section class="row my-3 py-2 content_filter_basic" id="filtroContentMFuentes" style="display:none;">
                                
                                    <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                        <label for="" class="col-12 col-sm-3">Nodos:</label>
                                        <select name="listaNodosMFFilter" id="listaNodosMFFilter" class="col-12 col-sm-9 form-control form-control-sm shadow-sm">
                                            <option value="seleccionar">Sin Filtro</option>
                                            @forelse ($nodos as $nodo)
                                                <option value="{{$nodo->nodo}}">{{$nodo->nodo}}</option>
                                            @empty
                                        
                                            @endforelse
                                        </select>
                                    </div>
                                    <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                            <label for="" class="col-12 col-sm-3">Estados:</label>
                                            <select name="listaEstadosMFilter" id="listaEstadosMFilter" class="col-12 col-sm-9 form-control form-control-sm shadow-sm">
                                                <option value="seleccionar">Sin Filtro</option>
                                                <option value="SIN-ESTADO">SIN-ESTADO</option>
                                                @forelse ($estados as $est)
                                                    <option value="{{$est->estado}}">{{$est->estado}}</option>
                                                @empty
                                                    
                                                @endforelse
                                            </select>
                                    </div>
                                    <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                        <label for="" class="col-12 col-sm-3">Marca:</label>
                                        <select name="listaTipoBateriaMFFilter" id="listaTipoBateriaMFFilter" class="col-12 col-sm-9 form-control form-control-sm shadow-sm">
                                            <option value="seleccionar">Sin Filtro</option>
                                            <option value="ALPHA">ALPHA</option>
                                            <option value="MULTILINK">MULTILINK</option>
                                        </select>
                                    </div>
                                    <div class="form-group row mx-0 mb-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 justify-content-center align-items-center"> 
                                            <a href="javascript:void(0)" class="btn btn-sm btn-success shadow-sm col-12 col-sm-4 m-1" id="filtroBasicoMFuentes">Filtrar</a>
                                    </div>
                                </section>
                                <section class="content_table_list"> 
                                    <table id="resultMFuentesList" class="table table-hover table-bordered w-100 tableFixHead table-text-xs">
                                        <thead>
                                            <tr>
                                                <th>Item</th>
                                                <th>Estado Bateria</th>
                                                @if($DIAGNOSTICOM_PERMISO)
                                                    <th>DM</th>  
                                                @endif 
                                                <th>Nodo-Troba</th>
                                                <th>Cli</th>
                                                <th>Off</th>
                                                <th>Direccion</th>
                                                <th>Macaddress</th>
                                                <th>IPaddress</th>
                                                <th>Volt-Ent</th>
                                                <th>Volt_Sal</th>
                                                <th>Corr_Sal</th>
                                                <th>Bateria</th>
                                                <th>FechaHora</th>
                                                <th>Bat?</th>
                                                <th>Gestion</th>
                                                <th>SNMP</th>
                                                @if ($EDITAR_FUENTE_PERMISO)
                                                    <th>Edicion</th>
                                                @endif
                                                
                                            </tr>
                                        </thead>  
                                    </table>
                                </section>
                        </div>
                </div>
                </section> 
            </div>
            @if($GRAFICOFUENTES_PERMISO)
                <div class="tab-pane fade " id="graficoFuentesPoderTab" role="tabpanel" aria-labelledby="graficoFuentesPoderTab-tab">
                    <section  class="col-12 mx-0 px-0">
                        <div class="card">
                            <div class="card-header px-2 py-1">
                                <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_monitorFuentesTab"><i class="fa fa-arrow-left"></i> Atras Fuentes</a>
                                <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                            </div>
                            <div class="card-body">
                                <div id="resultGraficoDownFuentes"></div>
                            </div>
                        </div>
                    </section>
                </div> 
            
            @endif

            @if ($MAPAFUENTES_PERMISO)
                <div class="tab-pane fade " id="mapaFuentesTab" role="tabpanel" aria-labelledby="mapaFuentesTab-tab">
                    <section  class="col-12 mx-0 px-0">
                        <div class="card">
                            <div class="card-header px-2 py-1">
                                <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_monitorFuentesTab"><i class="fa fa-arrow-left"></i> Atras Fuentes</a>
                                <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                            </div>
                            <div class="card-body">
                                <div id="mapa_content_fuentes"></div>
                            </div>
                        </div>
                    </section>
                </div>
                <div class="tab-pane fade " id="DetalleEdificiosTab" role="tabpanel" aria-labelledby="DetalleEdificiosTab-tab">
                    <section  class="col-12 mx-0 px-0">
                        <div class="card">
                            <div class="card-header px-2 py-1">
                                <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_monitorFuentesTab"><i class="fa fa-arrow-left"></i> Atras Fuentes</a>
                                <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_mapaFuentesTab"><i class="fa fa-arrow-left"></i> Atras Mapa</a>
                                <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                            </div>
                            <div class="card-body">
                                <div class="h5 text-center d-block ">Detalle del edificio seleccionado (Centro de Control M1)</div>
                                <div class="content_table_list"> 
                                    <table id="edificios_content_multiconsulta" class="table table-hover table-bordered w-100 tableFixHead">
                                            <thead>
                                                <tr> 
                                                    <th>MACSTATE</th> 
                                                    <th>USPWR</th>
                                                    <th>USMER_SNR</th>
                                                    <th>DSPWR</th>
                                                    <th>DSMER_SNR</th>
                                                    <th>IDCLIENTECRM</th>
                                                    <th>NAMECLIENT</th>
                                                    <th>DIRECCION</th>
                                                    <th>AMPLIFICADOR</th>
                                                    <th>TAP</th>
                                                    <th>TELF1</th>
                                                    <th>MACADDRESS</th>
                                                    <th>SERVICEPACKAGE</th>
                                                </tr> 
                                            </thead>  
                                        </table>
                                </div>
                                
                            </div>
                        </div>
                    </section>
                </div>
            @endif

            @if($DIAGNOSTICOM_PERMISO)
                <div class="tab-pane fade" id="verDiagMasTab" role="tabpanel" aria-labelledby="verDiagMasTab-tab">
                    <section  class="col-12 mx-0 px-0">
                            <div class="card">
                                <div class="card-header px-2 py-1">
                                    <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_monitorFuentesTab"><i class="fa fa-arrow-left"></i> Atras Fuentes</a>
                                    <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                                </div>
                                <div class="card-body position-relative">
                                    @include('administrador.partials.diagnosticoMasivo')
                                </div>
                            </div>
                    </section>
                </div>  
                <script src="{{ url('/js/sistema/modulos/monitor-fuentes/diagnostico-masivo.min.js') }}"></script> 
            @endif
            @if($EDITAR_FUENTE_PERMISO)
                <div class="tab-pane fade" id="edicionFuenteTab" role="tabpanel" aria-labelledby="edicionFuenteTab-tab">
                    <section  class="col-12 mx-0 px-0">
                            <div class="card">
                                <div class="card-header px-2 py-1">
                                    <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_monitorFuentesTab"><i class="fa fa-arrow-left"></i> Atras Fuentes</a>
                                    <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                                </div>
                                <div class="card-body position-relative">
                                    <h4>Edición de Fuente</h4>
                                    <div id="form_update_load"></div>
                                    <section class="form row my-2 mx-0" id="form_update_detail">
                                        <div class="form-group row justify-content-center mx-0 px-2 col-12 errors_message" id="errors_Update">
                            
                                        </div>
                                        <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                            <label for="nodoUpdateFuente" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Nodo: </label>
                                            <input type="text" name="nodoUpdateFuente" id="nodoUpdateFuente" value="" class="col-sm-7 col-md-8 form-control form-control-sm shadow-sm validateText">
                                        </div>
                                        <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                            <label for="trobaUpdateFuente" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Troba: </label>
                                            <input type="text" name="trobaUpdateFuente" id="trobaUpdateFuente" value="" class="col-sm-7  col-md-8 form-control form-control-sm shadow-sm validateText">
                                        </div>
                                        <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                            <label for="macUpdateFuente" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Mac: </label>
                                            <input type="text" name="macUpdateFuente" id="macUpdateFuente" value="" class="col-sm-7 col-md-8 form-control form-control-sm shadow-sm validateText">
                                        </div>
                                        <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                            <label for="zonalUpdateFuente" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Zonal: </label>
                                            <input type="text" name="zonalUpdateFuente" id="zonalUpdateFuente" value="" class="col-sm-7  col-md-8 form-control form-control-sm shadow-sm validateText">
                                        </div>
                                        <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                            <label for="distritoUpdateFuente" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Distrito: </label>
                                            <input type="text" name="distritoUpdateFuente" id="distritoUpdateFuente" value="" class="col-sm-7 col-md-8 form-control form-control-sm shadow-sm validateText">
                                        </div>
                                        <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                            <label for="direccionUpdateFuente" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Direccion: </label>
                                            <input type="text" name="direccionUpdateFuente" id="direccionUpdateFuente" value="" class="col-sm-7 col-md-8 form-control form-control-sm shadow-sm validateText">
                                        </div>
                                        <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                            <label for="latitudXUpdateFuente" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Latitud(X): </label>
                                            <input type="text" name="latitudXUpdateFuente" id="latitudXUpdateFuente" value="" class="col-sm-7  col-md-8 form-control form-control-sm shadow-sm validateText">
                                        </div>
                                        <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                            <label for="latitudYUpdateFuente" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Latitud(Y): </label>
                                            <input type="text" name="latitudYUpdateFuente" id="latitudYUpdateFuente" value="" class="col-sm-7 col-md-8 form-control form-control-sm shadow-sm validateText">
                                        </div>
                                        <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                            <label for="marcaTobaUpdateFuente" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Marca Troba: </label>
                                            <input type="text" name="marcaTobaUpdateFuente" id="marcaTobaUpdateFuente" value="" class="col-sm-7  col-md-8 form-control form-control-sm shadow-sm validateText">
                                        </div>
                                        <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                            <label for="respaldoUpdateFuente" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Respaldo: </label>
                                            <input type="text" name="respaldoUpdateFuente" id="respaldoUpdateFuente" value="" class="col-sm-7  col-md-8 form-control form-control-sm shadow-sm validateText">
                                        </div>
                                        <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                            <label for="descripcionUpdateFuente" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Descripcion: </label>
                                            <input type="text" name="descripcionUpdateFuente" id="descripcionUpdateFuente" value="" class="col-sm-7  col-md-8 form-control form-control-sm shadow-sm validateText">
                                        </div>
                                        <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                            <label for="tieneBateriaUpdateFuente" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Tiene Bateria?: </label>
                                            <input type="text" name="tieneBateriaUpdateFuente" id="tieneBateriaUpdateFuente" value="" class="col-sm-7  col-md-8 form-control form-control-sm shadow-sm validateText">
                                        </div>
                                        <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                            <label for="segundaFuenteUpdateFuente" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Segunda Fuente: </label>
                                            <input type="text" name="segundaFuenteUpdateFuente" id="segundaFuenteUpdateFuente" value="" class="col-sm-7  col-md-8 form-control form-control-sm shadow-sm validateText">
                                        </div>
                                        <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-12 col-lg-12 d-flex justify-content-center">
                                            <a href="javascript:void(0)" class="btn btn-sm btn-outline-primary shadow-sm" id="actualizarFuente">Actualizar</a>
                                        </div>
                                    </section>
                                </div>
                            </div>
                    </section>
                </div>  
                <script src="{{ url('/js/sistema/modulos/monitor-fuentes/edit.min.js') }}"></script> 
            @endif

            @if($DETALLE_MULTILINK_PERMISO)  
                <div class="tab-pane fade" id="verMultilinkGraficoTab" role="tabpanel" aria-labelledby="verMultilinkGraficoTab-tab">
                    <section  class="col-12 mx-0 px-0">
                            <div class="card">
                                <div class="card-header px-2 py-1">
                                    <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_monitorFuentesTab"><i class="fa fa-arrow-left"></i> Atras Fuentes</a>
                                    <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                                </div>
                                <div class="card-body position-relative">
                                     <div id="graficoResultadoMultilink"></div>
                                    
                                </div>
                            </div>
                    </section>
                </div>
                <script src="{{ url('/js/sistema/modulos/monitor-fuentes/multilink.min.js') }}"></script>
            @endif
            
            
        </div>
           
    </div>

    
@endsection

@section('scripts-footer')   

    <script>
        var INTERVAL_LOAD = null
        var ESTA_ACTIVO_REFRESH = false 
    </script>

    <script src="{{ url('/library/Highcharts/code/highcharts.js')}}"></script>
    <script src="{{ url('/library/Highcharts/code/modules/export-data.js')}}"></script>

    <script src="{{ url('/js/sistema/modulos/monitor-fuentes/index.min.js') }}"></script>
 
    @if($GRAFICOFUENTES_PERMISO)  
        <script src="{{ url('/js/sistema/modulos/monitor-fuentes/grafico-fuentes.min.js') }}"></script>
    @endif

    @if($MAPAFUENTES_PERMISO)  
        <script src="{{ url('/js/sistema/modulos/monitor-fuentes/mapa-fuentes.min.js') }}"></script>
    @endif

    @if($DESCARGAR_ALERTAS_DOWN_PERMISO)  
        <script src="{{ url('/js/sistema/modulos/monitor-fuentes/alertas-down.min.js') }}"></script>
    @endif

    @if($DESCARGAR_HISTORICO_DOWN_PERMISO)  
        <script src="{{ url('/js/sistema/modulos/monitor-fuentes/historico-down.min.js') }}"></script>
    @endif

   

    

  
    
    

@endsection