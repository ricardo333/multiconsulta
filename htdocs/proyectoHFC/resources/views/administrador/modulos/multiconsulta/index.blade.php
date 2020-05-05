@extends('layouts.master')

@section('titulo_pagina_sistema', 'Multiconsulta')
 
@section('estilos')
    <link rel="stylesheet" href="{{ url('/css/modulos/multiconsulta.css')}}">
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

    </style>

@endsection
@section('scripts-header')
    <script>
        var ARBOL_DESCISIONES_IS_ACTIVE = false;
    </script>
@endsection

@section('top-left-submenus')
    @parent
    {{-- Menu Top--}}
@endsection

@section('title-container')
     <h4 class="m-0 text-dark text-uppercase">Multiconsulta</h4> 
    
@endsection
@section('ruta-navegacion-container')
    @parent
     <li class="breadcrumb-item active">Multiconsulta</li>
@endsection

@section('aside-right')
    {{-- Aqui el aside del lado derecho, ingresar lo que sedea mostrar--}}
@endsection

@section('content')
    @parent

    @include('administrador.modulos.multiconsulta.partials.searchModal')  

    @if(Auth::user()->HasPermiso('submodulo.multiconsulta.cm.view'))
        @include('administrador.modulos.multiconsulta.partials.cablemodemModal')
        <script src="{{asset('js/sistema/modulos/multiconsulta/cablemodem.min.js')}}"></script>
    @endif
    
    @if(Auth::user()->HasPermiso('submodulo.multiconsulta.reset-decos.view')) 
        @include('administrador.modulos.multiconsulta.partials.resetDecosModal')
    @endif
    @if(Auth::user()->HasPermiso('submodulo.multiconsulta.velocidad-cm.view')) 
        @include('administrador.modulos.multiconsulta.partials.velocidadModal')
    @endif
    @if(Auth::user()->HasPermiso('submodulo.multiconsulta.activar-cm.view'))
        @include('administrador.modulos.multiconsulta.partials.activarCmModal')
    @endif
    @if(Auth::user()->HasPermiso('submodulo.multiconsulta.scopegroup-cm.view'))
        @include('administrador.partials.scopesGroupModal')
        <script src="{{asset('js/sistema/modulos/multiconsulta/scopesgroup-cm.min.js')}}"></script>
    @endif

    @include('administrador.modulos.multiconsulta.partials.telefonoModal')

    <div class="row">

      <div class="tab-content w-100" id="tabsMultiContent">
        <div class="tab-pane fade show   active" id="multiconsultaTab" role="tabpanel" aria-labelledby="multiconsultaTab-tab">
            <section  class="col-12 mx-0 px-0">
              <div class="card-header px-2 py-1"> 
                    <a href="{{route('administrador')}}" class="btn btn-sm btn-outline-success mx-1"><i class="fa fa-arrow-left"></i> Atras</a>
                    <span id="aditional_arbol_decisiones_return"></span>
                    <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
              </div>
              <div class="cad">
                <div id="preload_multi"></div>
                <div class="card-body" id="contenedor_multiconsulta_body">
                    <section class="col-12 row mx-0 px-1" id="multiconsulta_search">
                      <div class="col-10 content-form-multi m-auto">
                        <article id="form_multiconsulta">
                          <div class="form-group">
                              <div class="input-group">
                                  <div class="input-group-btn">
                                      <select name="type_m" id="type_m" class="form-control form-control-sm shadow-sm">
                                        {{--<option value="seleccionar">Tipo de busqueda</option>--}}
                                        <option value="1">Cod Cliente CMS</option>
                                        <option value="2">Mac Address</option>
                                        <option value="3">Telefono TFA/CEL</option>
                                        <option value="4">Telefono HFC</option>
                                        <option value="5">DNI</option>
                                        <option value="6">RUC</option>
                                      </select>    
                                  </div>
                                <input type="text" id="text_m" name="text_m" class="form-control form-control-sm shadow-sm" autocomplete="off">
                                <span class="input-group-btn">
                                  <a href="javascript: void(0)" id="search_m" class="btn btn-sm btn-light shadow-sm">Buscar</a>
                                </span>
                              </div>
                          </div>
                        </article>
                      </div>
                    </section>
                    <section class="col-12 row mx-0 text-center text-primary d-block" id="cronomtero_busqueda_multi">
                        00 : 00 : 00 
                    </section>
                    <section class="col-12 row mx-0" id="rpta_multiconsulta"> 
                    </section>
                </div>
              </div>
            </section> 
        </div>
        @if(Auth::user()->HasPermiso('submodulo.multiconsulta.mapa.view')) 
            <div class="tab-pane fade " id="multiMapTab" role="tabpanel" aria-labelledby="multiMapTab-tab">
                <section  class="col-12 mx-0 px-0">
                    <div class="card">
                        <div class="card-header px-2 py-1">
                            <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_multiconsultaTab"><i class="fa fa-arrow-left"></i> Atras</a>
                            <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                        </div>
                        <div class="card-body">
                            <div id="mapa_content_multiconsulta"></div>
                        </div>
                    </div>
                </section>
            </div>
        
            <div class="tab-pane fade " id="multiEdificiosTab" role="tabpanel" aria-labelledby="multiEdificiosTab-tab">
                <section  class="col-12 mx-0 px-0">
                    <div class="card">
                        <div class="card-header px-2 py-1">
                            <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_multiconsultaTab"><i class="fa fa-arrow-left"></i> Atras Multiconsulta</a>
                            <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_multiMapTab"><i class="fa fa-arrow-left"></i> Atras Mapa</a>
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
        @if(Auth::user()->HasPermiso('submodulo.multiconsulta.intraway.view')) 
            <div class="tab-pane fade " id="multiIntrawayDataTab" role="tabpanel" aria-labelledby="multiIntrawayDataTab-tab">
                <section  class="col-12 mx-0 px-0">
                    <div class="card">
                        <div class="card-header px-2 py-1">
                            <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_multiconsultaTab"><i class="fa fa-arrow-left"></i> Atras Multiconsulta</a>
                            <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                        </div>
                        <div class="card-body">
                            <div id="datosIntraway"></div>
                        </div>
                    </div>
                </section>
            </div>
        @endif
        @if(Auth::user()->HasPermiso('submodulo.multiconsulta.diagnostico-masivo.view')) 
            <div class="tab-pane fade " id="diagnosticoMasivoTab" role="tabpanel" aria-labelledby="diagnosticoMasivoTab-tab">
                <section  class="col-12 mx-0 px-0">
                    <div class="card">
                        <div class="card-header px-2 py-1">
                            <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_multiconsultaTab"><i class="fa fa-arrow-left"></i> Atras Multiconsulta</a>
                            <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                        </div>
                        <div class="card-body position-relative">
                            @include('administrador.partials.diagnosticoMasivo')
                        </div>
                    </div>
                </section>
            </div>
            <script src="{{asset('js/sistema/modulos/multiconsulta/diagnostico-masivo.min.js')}}"></script>
        @endif
        @if(Auth::user()->HasPermiso('submodulo.multiconsulta.grafico-trafico-down.view'))
            <div class="tab-pane fade " id="graficoSaturacionDownsTab" role="tabpanel" aria-labelledby="graficoSaturacionDownsTab-tab">
                <section  class="col-12 mx-0 px-0">
                    <div class="card">
                        <div class="card-header px-2 py-1">
                            <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_multiconsultaTab"><i class="fa fa-arrow-left"></i> Atras Multiconsulta</a>
                            <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                        </div>
                        <div class="card-body">
                            <div id="resultGraficoDownSaturados"></div>
                        </div>
                    </div>
                </section>
            </div> 
            <script src="{{asset('js/sistema/modulos/multiconsulta/grafico-saturacion-downstream.min.js')}}"></script>
        @endif
        @if(Auth::user()->HasPermiso('submodulo.multiconsulta.arbol-decisiones.view'))
            <div class="tab-pane fade " id="arboldeDecisionesMultiTab" role="tabpanel" aria-labelledby="arboldeDecisionesMultiTab-tab">
                <section  class="col-12 mx-0 px-0">
                    <div class="card">
                        <div class="card-header px-2 py-1">
                            <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_multiconsultaTab"><i class="fa fa-arrow-left"></i> Atras Multiconsulta</a>
                            <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                        </div>
                        <div class="card-body">
                            <h4 class="w-100 text-center text-uppercase font-weight-bold text-secondary">Arbol de Decisiones</h4>
                            <div id="preloadArbolDecisionesActions"></div>
                            <div id="resultArbolDecisioneMulti" class="row text-sm"> 
                                    <div class="col-12 form-row p-0 mx-0 mb-2"> 
                                            <div class="w-100 mt-2 text-center">
                                               <label class="content-form-radio m-0 font-weight-light"><input type='radio' name='solucion' value ='1'> Cliente Indica que recupero Servicio</label>
                                               <label class="content-form-radio m-0 font-weight-light"> <input type='radio' name='solucion' value ='2'> Cliente corta llamada</label>
                                               <label class="content-form-radio m-0 font-weight-light"> <input type='radio' name='solucion' value ='3'> Cliente exige averia</label>
                                               <label class="content-form-radio m-0 font-weight-light"> <input type='radio' name='solucion' value ='4'> Averia Pendiente</label>
                                            </div>
                                    </div>
                                    <div class="col-md-5 p-1">
                                        <h6 class="text-center">Estimado usuario, Es obligatorio seguir el árbol hasta el final:</h6>
                                    </div>
                                    <div class="col-md-7 p-1">
                                            <h6 class="text-center">Aqui las recomendaciones para hacer una buena gestión al Cliente:</h6>
                                    </div>
                                    <div class="col-md-5 p-1 mt-2">
                                        <div class="form" id="form-decisiones"> 

                                        </div>
                                    </div>
                                    <div class="col-md-7 p-1 mt-2">
                                           
                                    </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        @endif
        <div class="tab-pane fade " id="historicoNivTrobasTab" role="tabpanel" aria-labelledby="historicoNivTrobasTab-tab">
                <section  class="col-12 mx-0 px-0">
                        <div class="card">
                            <div class="card-header px-2 py-1">
                                <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_multiconsultaTab"><i class="fa fa-arrow-left"></i> Atras Multiconsulta</a>
                                <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                            </div>
                            <div class="card-body"> 
                                <div id="resultHistoricoNivTrobas" class="row text-sm"> 
                                </div>
                            </div>
                        </div>
                </section>
        </div>
        <div class="tab-pane fade " id="historicoCaidaTrobasTab" role="tabpanel" aria-labelledby="historicoCaidaTrobasTab-tab">
                <section  class="col-12 mx-0 px-0">
                        <div class="card">
                            <div class="card-header px-2 py-1">
                                <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_multiconsultaTab"><i class="fa fa-arrow-left"></i> Atras Multiconsulta</a>
                                <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                            </div>
                            <div class="card-body">
                                <div id="resultHistoricoCaidasTrobas" class="row text-sm"> 
                                </div>
                            </div>
                        </div>
                </section>
        </div>
        @if(Auth::user()->HasPermiso('submodulo.multiconsulta.agenda.view'))
            <div class="tab-pane fade " id="preAgendaTab" role="tabpanel" aria-labelledby="preAgendaTab-tab">
                    <section  class="col-12 mx-0 px-0">
                            <div class="card">
                                <div class="card-header px-2 py-1">
                                    <a href="javascript:void(0)" id="return_agenda_to_multiconsultaTab" class="btn btn-sm btn-outline-primary shadow-sm"><i class="fa fa-arrow-left"></i> Atras Multiconsulta</a>
                                    <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
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
            <script src="{{asset('js/sistema/modulos/multiconsulta/agenda.min.js')}}"></script>
        @endif

        <div class="tab-pane fade " id="historicoRuidoInterfazTab" role="tabpanel" aria-labelledby="historicoRuidoInterfazTab-tab">
                <section  class="col-12 mx-0 px-0">
                        <div class="card">
                            <div class="card-header px-2 py-1">
                                    <a href="javascript:void(0)" class="btn btn-sm btn-outline-primary shadow-sm return_multiconsultaTab"><i class="fa fa-arrow-left"></i> Atras Multiconsulta</a>
                                <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                            </div>
                            <div class="card-body"> 
                                @include('administrador.partials.historicoRuidoInterfaz')
                            </div>
                        </div>
                </section>
        </div>

      </div>
           
    </div>

    
@endsection

@section('scripts-footer')   

    <script src="{{ url('/js/sistema/modulos/multiconsulta/index.min.js') }}"></script>
    <script src="{{ url('/library/Highcharts/code/highcharts.js')}}"></script>
    <script src="{{ url('/library/Highcharts/code/modules/export-data.js')}}"></script>

    @if(Auth::user()->HasPermiso('submodulo.multiconsulta.intraway.view'))  
        <script src="{{asset('js/sistema/modulos/multiconsulta/btn-intraway.min.js')}}"></script>
    @endif 

    @if(Auth::user()->HasPermiso('submodulo.multiconsulta.arbol-decisiones.view'))
        <script>
                var ARBOL_DECISIONES_ACUMULADOR = []
                var ARBOL_DECISIONES_MARCARAPIDA = []
        </script>
        <script src="{{asset('js/sistema/modulos/multiconsulta/arbol-decisiones.min.js')}}"></script>
    @endif

    @if(Auth::user()->HasPermiso('submodulo.multiconsulta.historico-masivo-trobas.view')) 
        <script src="{{ url('/js/sistema/modulos/multiconsulta/historico-niveles-trobas.min.js') }}"></script>
    @endif  
    @if(Auth::user()->HasPermiso('submodulo.multiconsulta.historico-caidas-trobas.view'))   
        <script src="{{ url('/js/sistema/modulos/multiconsulta/historico-caidas-trobas.min.js') }}"></script>
    @endif  
 

@endsection