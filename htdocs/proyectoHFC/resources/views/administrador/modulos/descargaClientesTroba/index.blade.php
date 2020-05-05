@extends('layouts.master')

@section('titulo_pagina_sistema', 'Descarga Clientes Troba')
 
@section('estilos') 
    <style>
        #mapa_content_carga {
                height: calc(100vh - 150px);
            }
        
    </style>
@endsection

@section('scripts-header')
        <script> 
            var DATA_INTERFACES = [] 
        </script>
        <script src="{{ url('/js/sistema/modulos/descarga-clientes-troba/interfaces.min.js') }}"></script> 
@endsection

@section('top-left-submenus')
    @parent
    {{-- Menu Top--}}
@endsection

@section('title-container')
    <h4 class="m-0 text-dark text-uppercase">Descarga de Clientes por Troba</h4> 
@endsection
@section('ruta-navegacion-container')
    @parent
     <li class="breadcrumb-item active">D. Clientes Troba</li>
@endsection

@section('aside-right')
    {{-- Aqui el aside del lado derecho, ingresar lo que sedea mostrar--}}
@endsection

@section('content')
    @parent
 
    @include('administrador.modulos.descargaClientesTroba.partials.cantidadTrobasPuertoModal')  
 
      
    <div class="row">
 
        <div class="tab-content w-100" id="tabsDescargaClientesTrobaContent"> 
            <div class="tab-pane fade show active" id="contentFiltrosTrobasClientTab" role="tabpanel" aria-labelledby="contentFiltrosTrobasClientTab-tab">
                   
                    <section  class="col-12 mx-0 px-0">
                            <div class="card">
                                <div class="card-header px-2 py-1">
                                  <a href="{{route('administrador')}}" class="btn btn-sm btn-outline-success mx-1"><i class="fa fa-arrow-left"></i> Atras </a>
                                    <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                                </div>
                                <div class="card-body">
                                    <h4 class="w-100 text-center text-uppercase font-weight-bold text-secondary">Detalle de Clientes</h4>
                                    <div id="respuestaFiltroClienteTroba"> 
                                    </div>
                                    <div id="preloadFiltrosGenerales" class="w-100 ">
                                            <div id="carga_person">
                                                    <div class="loader">Loading...</div>
                                            </div>
                                    </div>
                                    <div id="contentFiltroClientTroba" class="w-100 text-sm row m-0 d-none">  
                                            <div class="form-group row mx-0 px-2 col-12 col-sm-12">
                                                <label for="interfacesLista" class="col-form-label col-form-label-sm mb-0 px-0">Elija Interface(s) a Filtrar (Puede seleccionar uno o mas puertos): </label>
                                            </div> 

                                            <div class="form-group row col-12 mx-0 p-0 ">
                                                <div class="dual-list list-left col-md-5">
                                                    <div class="well text-right">
                                                        <div class="input-group" style="line-height: normal;">
                                                            <button class="btn btn-sm btn-outline-primary shadow-sm"><i class="icofont-search-2 icofont-md"></i></button>
                                                            <input type="text" name="SearchDualList1" class="form-control  form-control-sm shadow-sm text-primary" placeholder="search" />
                                                        </div>
                                            
                                                        <select multiple="multiple" size="10" name="duallistbox_demo1" id="interfacesLista" class="mdb-select md-form demo1 form-control form-control-sm shadow-sm" style="width: -webkit-fill-available;">
                                                            {{-- @forelse ($interfaces as $inter)
                                                                <option value="{{$inter->cmts}}{{$inter->interface}}">{{$inter->cmts}}{{$inter->interface}}</option>
                                                                <option value="{{$inter->interbus}}">{{$inter->interbus}}</option>
                                                            @empty
                                                            
                                                            @endforelse--}} 
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

                                                        <select multiple="multiple" size="10" name="duallistbox_demo2" id="interfaces" class="demo2 form-control form-control-sm shadow-sm" style="width: -webkit-fill-available;">
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>  
                                            <div class="form-group row mx-0 px-2 col-12 col-sm-6 col-md-6 col-lg-6 ">
                                                <label for="" class="">Trobas:</label>
                                                <select name="listaTrobas" id="listaTrobas" class="form-control form-control-sm shadow-sm">
                                                    <option value="seleccionar">Sin Filtro</option>
                                                        @forelse ($trobas as $trob)
                                                        <option value="{{$trob->nodotroba}}">{{$trob->nodotroba}}</option>
                                                        @empty
                                                    
                                                        @endforelse
                                                </select>
                                            </div>
                                            <div class="form-group row mx-0 px-2 col-12 col-sm-6 col-md-6 col-lg-6 ">
                                                <label for="" class="">Niveles por Puerto:</label>
                                                <select name="listaNivelesPuerto" id="listaNivelesPuerto" class="form-control form-control-sm shadow-sm">
                                                    <option value="seleccionar">Sin Filtro</option>
                                                        @forelse ($nivelesPuerto as $nivp)
                                                        <option value="{{$nivp->cmts}}{{$nivp->interface}}">{{$nivp->puerto}}</option>
                                                        @empty
                                                    
                                                        @endforelse
                                                </select>
                                            </div> 
                                            <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-12 col-lg-12 justify-content-center">
                                                <a href="javascript:void(0)" id="filtroPrincipalProceso" class="btn btn-sm btn-success shadow-sm w-50">Filtrar</a>
                                            </div>     
                                    </div>
                                </div>
                            </div>
                    </section>
            </div>
            <div class="tab-pane fade " id="resultadoFiltroResultPrincipalTab" role="tabpanel" aria-labelledby="resultadoFiltroResultPrincipalTab-tab">
                    <section  class="col-12 mx-0 px-0">
                            <div class="card">
                                <div class="card-header px-2 py-1">
                                    <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_troba_clientes"><i class="fa fa-arrow-left"></i> Atras Clientes por Troba</a>
                                    <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                                </div>
                                <div class="card-body">
                                    <h4 class="w-100 text-center text-uppercase font-weight-bold text-secondary mb-3">Resultado Filtro de Clientes</h4>
                                    <div id="resultadoContentFiltroCliente" class="row text-sm"> 

                                    </div>
                                </div>
                            </div>
                    </section>
            </div>
            <div class="tab-pane fade " id="verDiagMasTab" role="tabpanel" aria-labelledby="verDiagMasTab-tab">
                <section  class="col-12 mx-0 px-0">
                        <div class="card">
                            <div class="card-header px-2 py-1">
                                <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_troba_clientes"><i class="fa fa-arrow-left"></i> Atras Clientes por Troba</a>
                                <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_resultado_filtros"><i class="fa fa-arrow-left"></i> Atras Resultado Filtro</a>
                                <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                            </div>
                            <div class="card-body position-relative">
                                @include('administrador.partials.diagnosticoMasivo')
                            </div>
                        </div>
                </section>
            </div> 
            <div class="tab-pane fade" id="historicoNodoTrobaTab" role="tabpanel" aria-labelledby="historicoNodoTrobaTab-tab">
                <section  class="col-12 mx-0 px-0">
                    <div class="card-header px-2 py-1"> 
                            <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_troba_clientes"><i class="fa fa-arrow-left"></i> Atras Clientes por Troba</a>
                            <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_resultado_filtros"><i class="fa fa-arrow-left"></i> Atras Resultado Filtro</a>
                        <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                    </div>
                    <div class="cad"> 
                    <div id="errorExcel"></div>
                    <div class="card-body position-relative" id="contenedor_historicoNodoTroba_body">
                            @include('administrador.partials.historicoNodoTrobas')
                    </div>
                    </div>
                </section> 
            </div>
            <div class="tab-pane fade " id="verMapaTab" role="tabpanel" aria-labelledby="verMapaTab-tab">
                    <section  class="col-12 mx-0 px-0">
                            <div class="card">
                                <div class="card-header px-2 py-1">
                                    <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_troba_clientes"><i class="fa fa-arrow-left"></i> Atras Caidas</a>
                                    <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_resultado_filtros"><i class="fa fa-arrow-left"></i> Atras Resultado Filtro</a>
                                    <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                                </div>
                                <div class="card-body">
                                    <div id="mapa_content_carga"></div>
                                </div>
                            </div>
                    </section>
            </div>
            <div class="tab-pane fade " id="detalleEdificiosTab" role="tabpanel" aria-labelledby="detalleEdificiosTab-tab">
                <section  class="col-12 mx-0 px-0">
                    <div class="card">
                        <div class="card-header px-2 py-1">
                            <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_troba_clientes"><i class="fa fa-arrow-left"></i> Atras Caidas</a>
                            <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_verMapaTab"><i class="fa fa-arrow-left"></i> Atras Mapa</a>
                            <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                        </div>
                        <div class="card-body position-relative">
                            <div class="h5 text-center d-block ">Detalle del edificio seleccionado (Centro de Control M1)</div>
                            <div class="content_table_list"> 
                                <table id="edificios_content_general" class="table table-hover table-bordered w-100 tableFixHead">
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
            <div class="tab-pane fade " id="listaPromedioNivelesCmtsPuerto" role="tabpanel" aria-labelledby="listaPromedioNivelesCmtsPuerto-tab">
                    <section  class="col-12 mx-0 px-0">
                            <div class="card">
                                <div class="card-header px-2 py-1">
                                    <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_troba_clientes"><i class="fa fa-arrow-left"></i> Atras Clientes por Troba</a>
                                    <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_resultado_filtros"><i class="fa fa-arrow-left"></i> Atras Resultado Filtro</a>
                                    <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                                </div>
                                <div class="card-body position-relative">
                                    <h4 class="w-100 text-center text-uppercase font-weight-bold text-secondary">Promedio de Niveles CMTS por Puerto</h4>
                                   
                                        <div class="content_table_list"> 
                                            <table id="resultPromedioNivelesPuerto" class="table table-hover table-bordered w-100 tableFixHead table-text-xs ">
                                                <thead>
                                                    <tr>  
                                                        <th>CMTS</th>
                                                        <th>INTERFACE</th>
                                                        <th>NODO_TROBA</th>
                                                        <th>UP_MAX</th> 
                                                        <th>UP_PROM</th>
                                                        <th>UP_MIN</th>
                                                        <th>DN_MAX</th>
                                                        <th>DN_PROM</th>
                                                        <th>DN_MIN</th>
                                                        <th>SNR_UP</th>
                                                        <th>FECHA_HORA</th>
                                                    </tr> 
                                                </thead>  
                                            </table>
                                        </div>
                                     
                                </div>
                            </div>
                    </section>
            </div>
            <div class="tab-pane fade " id="listaHistoricoNivelesCmtsPuerto" role="tabpanel" aria-labelledby="listaHistoricoNivelesCmtsPuerto-tab">
                    <section  class="col-12 mx-0 px-0">
                            <div class="card">
                                <div class="card-header px-2 py-1">
                                    <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_troba_clientes"><i class="fa fa-arrow-left"></i> Atras Clientes por Troba</a>
                                    <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_resultado_filtros"><i class="fa fa-arrow-left"></i> Atras Resultado Filtro</a>
                                    <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                                </div>
                                <div class="card-body position-relative">
                                    <h4 class="w-100 text-center text-uppercase font-weight-bold text-secondary">Historico de Niveles CMTS por Puerto</h4>
                                   
                                        <div class="content_table_list"> 
                                            <table id="resultHistoricoNivelesPuerto" class="table table-hover table-bordered w-100 tableFixHead table-text-xs ">
                                                <thead>
                                                    <tr>  
                                                        <th>CMTS</th>
                                                        <th>INTERFACE</th>
                                                        <th>NODO_TROBA</th>
                                                        <th>UP_MAX</th> 
                                                        <th>UP_PROM</th>
                                                        <th>UP_MIN</th>
                                                        <th>DN_MAX</th>
                                                        <th>DN_PROM</th>
                                                        <th>DN_MIN</th>
                                                        <th>SNR_UP</th>
                                                        <th>SNR_DOWN</th>
                                                        <th>FECHA_HORA</th>
                                                    </tr> 
                                                </thead>  
                                            </table>
                                        </div>
                                     
                                </div>
                            </div>
                    </section>
            </div>
        </div>
           
    </div>

    
@endsection

@section('scripts-footer')   
 
    <script src="{{ url('/js/sistema/modulos/descarga-clientes-troba/index.min.js') }}"></script> 
      
@endsection