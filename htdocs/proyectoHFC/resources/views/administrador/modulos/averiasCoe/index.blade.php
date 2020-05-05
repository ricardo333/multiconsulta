@extends('layouts.master')

@section('titulo_pagina_sistema', 'Averias COE')
 
@section('estilos') 
    <link rel="stylesheet" href="{{ url('/css/cablemodems/maping.css')}}">

    <style>
        .port_window01 {
            width: 41px;
            height: 28px;
            border: 0 none transparent;
            cursor: pointer;
            padding: 10px 10px;
            background-image: url('/images/cablemodems/button_add.png');
        }

        .button-delete {
            width: 25px;
            height: 25px;
            margin-top: 0px;
            cursor: pointer;
            background-image: url('/images/cablemodems/button_delete.png');
        }

        /**/
        th.agenda_dias {
                text-align: center;
                font-size: 10px;
                background: #8e1818;
                color: #fff;
            }
        th.agenda_horario {
            text-align: center;
            background: #000000;
            color: #fff;
        }

        /**/

        .tableFixHead tbody tr td:nth-child(1){
            background: #fff;
        }

    </style>
@endsection

@section('scripts-header')
    <script>
        var GESTION_PERMISO = false
        var DIAGNOSTICOM_PERMISO = false 
        var CAMBIARSCOPEGROUP_PERMISO = false 
        var REFRESHIW_PERMISO = false
        //cm 
        var VERCM_PERMISO = false 
        var CM_ESTADO_PERMISO = false 
        var CM_DHCP_PERMISO = false 
        var CM_WIFI_VECINOS_PERMISO = false 
        var CM_DIAGNOSTICO_PERMISO = false 
        var CM_CONFIG_WIFI_VIEW_PERMISO = false 
        var CM_UPNP_PERMISO = false 
        var CM_DMZ_PERMISO = false 
        var CM_PORTMAPING_PERMISO = false 
        var CM_RESET_SCRAPING_VIEW_PERMISO = false 
        var CM_RESET_SCRAP_SIMPLE_PERMISO = false 
        var CM_RESET_SCRAP_FABRICA_PERMISO = false 
     
       var AGENDA_PERMISO = false 

       var GESTION_INDIV_PERMISO = false
    </script>
@endsection

@section('top-left-submenus')
    @parent
    {{-- Menu Top--}}
@endsection
 
@section('title-container')
    <h4 class="m-0 text-dark text-uppercase">Averias COE</h4> 
@endsection
   
@section('ruta-navegacion-container')
    @parent
     <li class="breadcrumb-item active">Averias COE</li>
@endsection

@section('aside-right')
    {{-- Aqui el aside del lado derecho, ingresar lo que sedea mostrar--}}
@endsection

@php
    $CAMBIARSCOPEGROUP_PERMISO = false;
    $REFRESHIW_PERMISO = false;
    #CM
    $VERCM_PERMISO = false;
    $CM_ESTADO_PERMISO = false;
    $CM_DHCP_PERMISO = false;
    $CM_WIFI_VECINOS_PERMISO = false;
    $CM_DIAGNOSTICO_PERMISO = false;
    $CM_CONFIG_WIFI_VIEW_PERMISO = false;
    $CM_UPNP_PERMISO = false;
    $CM_DMZ_PERMISO = false;
    $CM_PORTMAPING_PERMISO = false;
    $CM_RESET_SCRAPING_VIEW_PERMISO = false;
    $CM_RESET_SCRAP_SIMPLE_PERMISO = false;
    $CM_RESET_SCRAP_FABRICA_PERMISO = false;

    $AGENDA_PERMISO = false;

    $GESTION_INDIV_PERMISO = false;
    //
@endphp

@section('content')
    @parent

    @if (Auth::user()->HasPermiso('submodulo.averias-coe.scopegroup.update'))
        @php $CAMBIARSCOPEGROUP_PERMISO = true;  @endphp
        <script>  CAMBIARSCOPEGROUP_PERMISO = true  </script>
        @include('administrador.partials.scopesGroupModal')
        <script src="{{asset('js/sistema/modulos/averias-coe/scopesgroup-cm.min.js')}}"></script>
    @endif

    @if (Auth::user()->HasPermiso('submodulo.averias-coe.reset-cm-iw.update'))
        @php $REFRESHIW_PERMISO = true;  @endphp
        <script>  REFRESHIW_PERMISO = true  </script> 
        <script src="{{asset('js/sistema/modulos/averias-coe/reset-cm-reaprovisionamiento.min.js')}}"></script>
    @endif

    @if (Auth::user()->HasPermiso('submodulo.averias-coe.cm.view'))
        @php $VERCM_PERMISO = true;  @endphp
        <script>  VERCM_PERMISO = true  </script> 
        {{-- Permisos CM --}}
            @if (Auth::user()->HasPermiso('submodulo.averias-coe.cm.estado.view'))
                @php $CM_ESTADO_PERMISO = true;  @endphp
                <script>  CM_ESTADO_PERMISO = true  </script> 
            @endif
            @if (Auth::user()->HasPermiso('submodulo.averias-coe.cm.dhcp.view'))
                @php $CM_DHCP_PERMISO = true;  @endphp
                <script>  CM_DHCP_PERMISO = true  </script> 
            @endif
            @if (Auth::user()->HasPermiso('submodulo.averias-coe.cm.wifi-vecinos.view'))
                @php $CM_WIFI_VECINOS_PERMISO = true;  @endphp
                <script>  CM_WIFI_VECINOS_PERMISO = true  </script> 
            @endif
            @if (Auth::user()->HasPermiso('submodulo.averias-coe.cm.diagnostico.view'))
                @php $CM_DIAGNOSTICO_PERMISO = true;  @endphp
                <script>  CM_DIAGNOSTICO_PERMISO = true  </script> 
            @endif
            @if (Auth::user()->HasPermiso('submodulo.averias-coe.cm.config-wifi.view'))
                @php $CM_CONFIG_WIFI_VIEW_PERMISO = true;  @endphp
                <script>  CM_CONFIG_WIFI_VIEW_PERMISO = true  </script> 
            @endif
            @if (Auth::user()->HasPermiso('submodulo.averias-coe.cm.upnp.view'))
                @php $CM_UPNP_PERMISO = true;  @endphp
                <script>  CM_UPNP_PERMISO = true  </script> 
            @endif
            @if (Auth::user()->HasPermiso('submodulo.averias-oe.cm.dmz.view'))
                @php $CM_DMZ_PERMISO = true;  @endphp
                <script>  CM_DMZ_PERMISO = true  </script> 
            @endif
            @if (Auth::user()->HasPermiso('submodulo.averias-coe.cm.port-maping.view'))
                @php $CM_PORTMAPING_PERMISO = true;  @endphp
                <script>  CM_PORTMAPING_PERMISO = true  </script> 
            @endif
            @if (Auth::user()->HasPermiso('submdoulo.averias-coe.cm.reset-scraping.view'))
                @php $CM_RESET_SCRAPING_VIEW_PERMISO = true;  @endphp
                <script>  CM_RESET_SCRAPING_VIEW_PERMISO = true  </script> 
                {{-- RESETS SCRAPING --}}
                @if (Auth::user()->HasPermiso('submodulo.averias-coe.cm.reset-scraping.simple.view'))
                    @php $CM_RESET_SCRAP_SIMPLE_PERMISO = true;  @endphp
                    <script>  CM_RESET_SCRAP_SIMPLE_PERMISO = true  </script> 
                @endif
                @if (Auth::user()->HasPermiso('submodulo.averias-coe.cm.reset-scraping.fabrica.view'))
                    @php $CM_RESET_SCRAP_FABRICA_PERMISO = true;  @endphp
                    <script>  CM_RESET_SCRAP_FABRICA_PERMISO = true  </script> 
                @endif

                {{-- END --}}

            @endif
        {{-- END --}} 

        @include('administrador.modulos.averiasCoe.partials.cablemodemModal')
        <script src="{{asset('js/sistema/modulos/averias-coe/cablemodem.min.js')}}"></script>
    @endif

    @if (Auth::user()->HasPermiso('submodulo.averias-coe.agenda.view'))
        @php $AGENDA_PERMISO = true;  @endphp
        <script>  AGENDA_PERMISO = true  </script>  
    @endif

    @if (Auth::user()->HasPermiso('submodulo.averias-coe.gestion.view'))
        @php $GESTION_INDIV_PERMISO = true;  @endphp
        <script>  GESTION_INDIV_PERMISO = true  </script>  
    @endif
 
    <div class="row">
        
 
        <div class="tab-content w-100" id="tabsAveriasCoeContent">
           
            <div class="tab-pane listaaveriasCoe fade show   active" id="averiasCoeTab" role="tabpanel" aria-labelledby="averiasCoeTab-tab">
                <section  class="col-12 mx-0 px-0">
                <div class="card-header px-2 py-1">
                    
                    <a href="{{route('administrador')}}" class="btn btn-sm btn-outline-success mx-1"><i class="fa fa-arrow-left"></i> Atras </a>
                    @if($GESTION_INDIV_PERMISO)
                        <button class="btn btn-sm btn-outline-secondary shadow-sm m-1 d-none inactive" id="activarGestionMasiva">Gestión Masiva</button>
                        <button class="btn btn-sm shadow-sm m-1 d-none" id="procesarGestionMasivaSend">Procesar Gestiones</button>
                    @endif 
                    
                        <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                </div>
                <div class="cad"> 
                    <div id="errorExcel"></div>
                    <div class="card-body position-relative" id="contenedor_averiasCoe_body">
                        <div class="h5 text-center d-block text-danger mb-3">Averias COE</div>

                        <section class="row my-3 py-2 content_filter_basic" id="filtroContentCOE" style="display:none;">
                            <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                <label for="" class="col-12 col-sm-3">Jefaturas:</label>
                                <select name="listaJefaturaCOEFilter" id="listaJefaturaCOEFilter" class="col-12 col-sm-9 form-control form-control-sm shadow-sm">
                                    <option value="seleccionar">Sin Filtro</option>
                                    @forelse ($jefaturas as $jeft)
                                        <option value="{{$jeft->jefatura}}">{{$jeft->jefatura}}</option>
                                    @empty
                                
                                    @endforelse  
                                </select>
                            </div>
                            <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                    <label for="" class="col-12 col-sm-3">Estados Gestion:</label>
                                    <select name="listaEstadosGestionFilter" id="listaEstadosGestionFilter" class="col-12 col-sm-9 form-control form-control-sm shadow-sm">
                                        <option value="seleccionar">Sin Filtro</option>
                                        <option value="SIN_ESTADO">SIN ESTADO</option>
                                        <option value="PENDIENTE">PENDIENTE</option>
                                        <option value="CERRADO">CERRADO</option>
                                    </select>
                            </div>
                            <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                    <label for="" class="col-12 col-sm-3">Trobas:</label>
                                    <select name="listaTrobasFilter" id="listaTrobasFilter" class="col-12 col-sm-9 form-control form-control-sm shadow-sm">
                                        <option value="seleccionar">Sin Filtro</option>
                                        @forelse ($trobas as $est)
                                                <option value="{{$est->nodo}}_{{$est->troba}}">{{$est->clave}}</option>
                                        @empty
                                            
                                        @endforelse  
                                </select>
                            </div>
                            <div class="form-group row mx-0 mb-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 justify-content-center h-100">
                                <a href="javascript:void(0)" class="btn btn-sm btn-primary shadow-sm w-50 " id="filtroBasicoCOE">Filtrar</a>
                            </div>
                        </section>
                        
                        <div class="content_table_list"> 
                            <table id="resultAveriasCOEMasivas" class="table table-hover table-bordered w-100 tableFixHead table-text-xs">
                                <thead>
                                    <tr>   
                                        <th> Opciones </th> 
                                        <th> Item </th>
                                        <th> zonal </th>
                                        <th> codreq </th>
                                        <th> codcli </th>
                                        <th> tip_ing </th>
                                        <th> estadomdm </th> 
                                        <th> area </th> 
                                        <th> nodocms </th> 
                                        <th> trobacms </th> 
                                        <th> nodohfc </th> 
                                        <th> trobahfc </th> 
                                        <th> amplificador </th> 
                                        <th> Llamadas DMPE Últimos 7 dias </th> 
                                        <th> Averias Últimos 7 dias </th> 
                                        <th> codctr </th> 
                                        <th> desnomctr </th> 
                                        <th> cmts </th> 
                                        <th> interface </th> 
                                        <th> scopesgroup </th> 
                                        <th> masiva </th> 
                                        <th> macaddress </th> 
                                        <th> fecreg </th> 
                                        <th> codctr_final </th> 
                                        <th> area_final </th> 
                                        <th> ultimagestion </th> 
                                        <th> TipoRuido </th> 
                                        <th> observacionescms </th> 
                                        <th> motivotransferencia </th> 
                                        <th> telef1 </th> 
                                        <th> telef2 </th> 
                                        <th> telef3 </th> 
                                        <th> MACState </th> 
                                        <th> USPwr </th> 
                                        <th> USMER_SNR </th> 
                                        <th> DSPwr </th> 
                                        <th> DSMER_SNR </th> 
                                        <th> codsrv </th> 
                                        <th> Estado Gestion </th>
                                        @if ($GESTION_INDIV_PERMISO)   
                                            <th> Gestión </th>
                                        @endif
                                    </tr>
                                </thead>  
                            </table>
                        </div>
                    </div>
                </div>
                </section> 
            </div>  
            @if(Auth::user()->HasPermiso('submodulo.monitor-averias.diagnostico-masivo.view'))
                <div class="tab-pane fade " id="verDiagMasTab" role="tabpanel" aria-labelledby="verDiagMasTab-tab">
                    <section  class="col-12 mx-0 px-0">
                            <div class="card">
                                <div class="card-header px-2 py-1">
                                    <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_averias_coe"><i class="fa fa-arrow-left"></i> Atras Averia COE</a>
                                    <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                                </div>
                                <div class="card-body position-relative">
                                    @include('administrador.partials.diagnosticoMasivo')
                                </div>
                            </div>
                    </section>
                </div> 
                <script>
                        DIAGNOSTICOM_PERMISO = true
                </script>
                <script src="{{ url('/js/sistema/modulos/averias-coe/diagnostico-masivo.min.js') }}"></script> 
            @endif
            @if($AGENDA_PERMISO)
                <div class="tab-pane fade " id="preAgendaTab" role="tabpanel" aria-labelledby="preAgendaTab-tab">
                        <section  class="col-12 mx-0 px-0">
                                <div class="card">
                                    <div class="card-header px-2 py-1">
                                        <a href="javascript:void(0)" id="return_agenda_to_averias_coe_Tab" class="btn btn-sm btn-outline-primary shadow-sm"><i class="fa fa-arrow-left"></i> Atras Averia COE</a>
                                        <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                                        <input type="hidden" id="detalleAgendaSeleccionadaCoe" name="detalleAgendaSeleccionadaCoe"  data-uno="" data-dos="" data-tres="">
                                    </div>
                                    <div class="card-body" id="card-body-agenda-reservacion"> 
                                        <div id="preLoadAgendaSend"></div>
                                        <div id="resultPreAgendaContent" class="row text-sm"> 
                                            
                                        </div> 
                                        <div id="resultAgendaGrafico" class="row"> 
                                            
                                        </div> 

                                    </div>
                                </div>
                        </section>
                </div>
                <script src="{{asset('js/sistema/modulos/averias-coe/agenda.min.js')}}"></script>
            @endif
              
            <div class="tab-pane fade " id="historicoRuidoInterfazTab" role="tabpanel" aria-labelledby="historicoRuidoInterfazTab-tab">
                    <section  class="col-12 mx-0 px-0">
                            <div class="card">
                                <div class="card-header px-2 py-1">
                                    <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_averias_coe"><i class="fa fa-arrow-left"></i> Atras Averias COE</a>
                                    <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                                </div>
                                <div class="card-body"> 
                                    @include('administrador.partials.historicoRuidoInterfaz')
                                </div>
                            </div>
                    </section>
            </div>

            @if ($GESTION_INDIV_PERMISO)   
                <div class="tab-pane fade " id="gestionCoeTab" role="tabpanel" aria-labelledby="gestionCoeTab-tab">
                        <section  class="col-12 mx-0 px-0">
                                <div class="card">
                                    <div class="card-header px-2 py-1">
                                        <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_averias_coe"><i class="fa fa-arrow-left"></i> Atras Averias COE</a>
                                        <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                                    </div>
                                    <div class="card-body" id="printFormGestionCOE"> 
                                          
                                    </div>
                                </div>
                        </section>
                </div>
                <div class="tab-pane fade " id="historicoAveriasCOETab" role="tabpanel" aria-labelledby="historicoAveriasCOETab-tab">
                        <section  class="col-12 mx-0 px-0">
                                <div class="card">
                                    <div class="card-header px-2 py-1">
                                        <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_gestion_coe"><i class="fa fa-arrow-left"></i> Atras Gestion</a>
                                        <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                                    </div>
                                    <div class="card-body"> 
                                        <h4 class="w-100 text-center text-uppercase font-weight-bold text-secondary">Historico Gestión</h4>
                                        <div class="content_table_list"> 
                                            <table id="resultHistoricoGestion" class="table table-hover table-bordered w-100 tableFixHead">
                                                <thead>
                                                    <tr>  
                                                        <th>nodo</th>
                                                        <th>troba</th>
                                                        <th>codigoCliente</th>
                                                        <th>mac</th>
                                                        <th>codigoServicio</th>
                                                        <th>codigoRequerimiento</th>
                                                        <th>usuario</th>
                                                        <th>Segunda Linea</th>
                                                        <th>Resultado Segunda Linea</th>
                                                        <th>Detalle Resultado</th>
                                                        <th>estadoDelCaso</th>
                                                        <th>fechaRegistro</th>
                                                    </tr> 
                                                </thead>  
                                            </table>
                                        </div>
                                    </div>
                                </div>
                        </section>
                </div>
            @endif

            
            
        </div>
           
    </div>

    
@endsection

@section('scripts-footer')   
 
    <script src="{{ url('/js/sistema/modulos/averias-coe/index.min.js') }}"></script>
    
    @if ($GESTION_INDIV_PERMISO)
        <script>
            var CLIENTES_GESTION = []
        </script>
        <script src="{{ url('/js/sistema/modulos/averias-coe/gestion.min.js') }}"></script>
    @endif
     
@endsection