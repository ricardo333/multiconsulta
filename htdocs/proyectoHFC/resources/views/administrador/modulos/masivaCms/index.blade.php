@extends('layouts.master')

@section('titulo_pagina_sistema', 'Masivas CMS')
 
@section('estilos') 
    <style>
        #mapa_content_masiva_cms {
                height: calc(100vh - 150px);
            }
        .content_filter_basic {
            border: 1px solid rgba(192, 200, 208, 0.84);
            border-radius: 5px;
        }
    </style>
@endsection

@section('scripts-header')
    <script>
        var GESTION_PERMISO = false
        var DIAGNOSTICOM_PERMISO = false
        var MAPA_PERMISO = false
        var VER_CRITICOS_PERMISO = false
        var VER_TRABPROGRAMADOS_PERMISO = false
        var ELIMINAR_MASIVA = false
    </script>
@endsection

@section('top-left-submenus')
    @parent
    {{-- Menu Top--}}
@endsection

@section('title-container')
     <h4 class="m-0 text-dark text-uppercase">SEGUIMIENTO DE MASIVAS PENDIENTES DE CMS</h4> 
    
@endsection


@section('ruta-navegacion-container')
    @parent
     <li class="breadcrumb-item active">Masivas CMS</li>
@endsection

@section('aside-right')
    {{-- Aqui el aside del lado derecho, ingresar lo que sedea mostrar--}}
@endsection

@section('content')
    @parent
    
    
    @include('administrador.modulos.masivaCms.partials.trabajoPDetalleModal')
    @include('administrador.modulos.masivaCms.partials.eliminarMasivaModal')
    @include('administrador.modulos.masivaCms.partials.descargasModal')
    @include('administrador.partials.gestionDetalleModal')
      
    <div class="row">

        <div class="tab-content w-100" id="tabsMasivaCmsContent">
            <div class="tab-pane fade show   active" id="masivaCmsTab" role="tabpanel" aria-labelledby="masivaCmsTab-tab">
                <section  class="col-12 mx-0 px-0">
                    <div class="card-header px-2 py-1">
                        @if (isset($motivo) && $motivo=="cuadroMando")
                            <a href="{{route('modulo.cuadro-mando.index')}}" class="btn btn-sm btn-outline-success mx-1"><i class="fa fa-arrow-left"></i> Atras Cuadro Mando</a>
                            <input type="hidden" id="var_cuadroMando">
                        @else
                            <a href="{{route('administrador')}}" class="btn btn-sm btn-outline-success mx-1"><i class="fa fa-arrow-left"></i> Atras </a>
                            @if(Auth::user()->HasPermiso('submodulo.masiva-cms.gestion-masiva.store'))
                                <a href="{{route('submodulo.masiva-cms.gestion-masiva.view')}}" class="btn btn-sm btn-outline-success mx-1"> Gestión Masiva <i class="fa fa-arrow-right"></i></a>
                            @endif
                            @if(Auth::user()->HasPermiso('submodulo.masiva-cms.cargar-masiva.view'))
                                <a href="{{route('submodulo.masiva-cms.cargar-masiva.view')}}" class="btn btn-sm btn-outline-success mx-1"> Subir Masiva <i class="fa fa-arrow-right"></i></a>
                            @endif
                        @endif
                        
                        <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                    </div>
                    <div class="cad">
                        <div id="errorExcel"></div>
                        <div class="card-body position-relative" id="contenedor_masivaCms_body">
                            <div class="h6 text-center d-block text-danger mb-3">(Masivas Declaradas)</div>
                            <section class="row my-3 py-2 content_filter_basic" id="filtroContentMasivas" style="display:none;">
                                <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                    @if (isset($motivo) && $motivo=="cuadroMando")
                                        <input type="hidden" id="filtroCuadroMando" value="{{$nodo}}">
                                    @endif
                                    <label for="" class="col-12 col-sm-3">Jefaturas:</label>
                                    <select name="listaJefaturasMasivas" id="listaJefaturasMasivas" class="col-12 col-sm-9 form-control form-control-sm shadow-sm">
                                        <option value="seleccionar">Sin Filtro</option>
                                        @forelse ($jefaturas as $jeft)
                                            <option value="{{$jeft->jefatura}}">{{$jeft->jefatura}}</option>
                                        @empty
                                       
                                        @endforelse
                                    </select>
                                </div>
                                <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                    <label for="" class="col-12 col-sm-3">Estados:</label>
                                    <select name="listaEstadosMasivas" id="listaEstadosMasivas" class="col-12 col-sm-9 form-control form-control-sm shadow-sm">
                                        <option value="seleccionar">Sin Filtro</option>
                                        @forelse ($estados as $est)
                                            <option value="{{$est->estado}}">{{$est->estado}}</option>
                                        @empty
                                                
                                        @endforelse
                                    </select>
                                </div>
                                <div class="form-group row mx-0 mb-0 px-2 col-12 col-sm-12 col-md-12 col-lg-12 justify-content-center">
                                    <a href="javascript:void(0)" class="btn btn-sm btn-primary shadow-sm w-25" id="filtroBasicoMasivas">Filtrar</a>
                                </div>
                            </section>
                            <div class="content_table_list"> 
                                <table id="resultMasivaCms" class="table table-hover table-bordered w-100 tableFixHead table-text-xs">
                                    <thead>
                                        <tr>
                                            <th>Item</th>
                                            <th>Jefatura</th>
                                            <th>Critica?</th>
                                            <th>Nodo_Troba</th>
                                            <th>T.Prog</th>
                                            <th>Aver/Call</th>
                                            <th>TicketDMPE</th>
                                            <th>Clientes</th>
                                            <th>Umbral</th>
                                            <th>Offline >80</th>
                                            <th>Remedy</th>
                                            <th>CodMasiva</th>
                                            <th>Fecha_Ini</th>
                                            <th>Tiempo/Dias</th>
                                            <th>ESTADO_GESTION</th>
                                            @if(Auth::user()->HasPermiso('submodulo.masiva-cms.gestion-individual.store'))
                                                <th>Gestion</th>
                                            @endif
                                            <th>Energia</th>
                                            @if(Auth::user()->HasPermiso('submodulo.masiva-cms.gestion-masiva.delete'))
                                                <th>Elimina?</th>
                                                <script>
                                                    ELIMINAR_MASIVA = true
                                                </script>
                                            @endif
                                        </tr>
                                    </thead>  
                                </table>
                            </div>
                        </div>
                    </div>
                </section> 
            </div>
            @if(Auth::user()->HasPermiso('submodulo.masiva-cms.diagnostico-masivo.view'))
                <div class="tab-pane fade " id="verDiagMasTab" role="tabpanel" aria-labelledby="verDiagMasTab-tab">
                    <section  class="col-12 mx-0 px-0">
                        <div class="card">
                            <div class="card-header px-2 py-1">
                                <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_masivaCms"><i class="fa fa-arrow-left"></i> Atras Masiva CMS</a>
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
                <script src="{{ url('/js/sistema/modulos/masiva-cms/diagnostico-masivo.min.js') }}"></script> 
            @endif



            @if(Auth::user()->HasPermiso('submodulo.masiva-cms.criticas.view'))
                <div class="tab-pane fade" id="listaCriticosNodoTrobaTab" role="tabpanel" aria-labelledby="listaCriticosNodoTrobaTab-tab">
                    <section  class="col-12 mx-0 px-0">
                        <div class="card-header px-2 py-1"> 
                                <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_masivaCms"><i class="fa fa-arrow-left"></i> Atras Masiva CMS</a>
                            <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                        </div>
                        <div class="cad"> 
                        <div id="errorExcel"></div>
                        <div class="card-body position-relative" id="contenedor_listaCriticos_body">
                            <div class="h6 text-center d-block text-danger mb-3">Clientes Críticos</div> 
                            <div class="content_table_list"> 
                                <table id="resultListaClientesCriticos" class="table table-hover table-bordered w-100 tableFixHead table-text-xs">
                                    <thead>
                                        <tr>
                                            <th>IDCLIENTECRM</th>
                                            <th>idempresacrm</th>
                                            <th>NAMECLIENT</th>
                                            <th>NODO</th>
                                            <th>TROBA</th>
                                            <th>amplificador</th>
                                            <th>tap</th>
                                            <th>telf1</th>
                                            <th>telf2</th>
                                            <th>movil1</th>
                                            <th>MACADDRESS</th>
                                            <th>cmts</th>
                                            <th>f_v</th>
                                            <th>entidad</th>
                                        </tr>
                                    </thead>  
                                </table>
                            </div>
                        </div>
                        </div>
                    </section> 
                </div>
                <script> 
                    VER_CRITICOS_PERMISO = true
                </script> 
                <script src="{{ url('/js/sistema/modulos/masiva-cms/clientes-criticos.min.js') }}"></script>
            @endif




            @if(Auth::user()->HasPermiso('submodulo.masiva-cms.mapa.view'))
                <div class="tab-pane fade " id="verMapaTab" role="tabpanel" aria-labelledby="verMapaTab-tab">
                    <section  class="col-12 mx-0 px-0">
                        <div class="card">
                            <div class="card-header px-2 py-1">
                                <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_masivaCms"><i class="fa fa-arrow-left"></i> Atras Masiva CMS</a>
                                <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                            </div>
                            <div class="card-body">
                                <div id="mapa_content_masiva_cms"></div>
                            </div>
                        </div>
                    </section>
                </div>
                <div class="tab-pane fade " id="masivaCmsEdificiosTab" role="tabpanel" aria-labelledby="monitorAvEdificiosTab-tab">
                    <section  class="col-12 mx-0 px-0">
                        <div class="card">
                            <div class="card-header px-2 py-1">
                                <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_masivaCms"><i class="fa fa-arrow-left"></i> Atras Masiva CMS</a>
                                <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_verMapaTab"><i class="fa fa-arrow-left"></i> Atras Mapa</a>
                                <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                            </div>
                            <div class="card-body position-relative">
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
                <script>
                    MAPA_PERMISO = true
                </script>
                <script src="{{ url('/js/sistema/modulos/masiva-cms/mapa.min.js') }}"></script>
            @endif



            @if(Auth::user()->HasPermiso('submodulo.masiva-cms.gestion-individual.store'))
                <div class="tab-pane fade " id="gestionIndividualMasivaCmsTab" role="tabpanel" aria-labelledby="gestionIndividualMasivaCmsTab-tab">
                    <section  class="col-12 mx-0 px-0">
                        <div class="card">
                            <div class="card-header px-2 py-1">
                                <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_masivaCms"><i class="fa fa-arrow-left"></i> Atras Masiva CMS</a>
                                <a href="javascript:void(0)" id="registrosGestiones"  class="btn btn-sm btn-outline-primary shadow-sm"><i class="fa fa-arrow-right"></i> Historial de gestiones</a>
                                <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                            </div>
                            <div class="card-body">
                                <h4 class="w-100 text-center text-uppercase font-weight-bold text-secondary">Registro de Gestión</h4>
                        
                                    @include('administrador.partials.gestionTrobaForm')
                                                
                            </div>
                        </div>
                    </section>
                </div>
                <script>
                    GESTION_PERMISO = true
                </script>
                <script src="{{ url('/js/sistema/modulos/masiva-cms/gestion-individual.min.js') }}"></script>
            @endif
            <div class="tab-pane fade " id="registrosGestionesTab" role="tabpanel" aria-labelledby="registrosGestionesTab-tab">
                <section  class="col-12 mx-0 px-0">
                    <div class="card">
                        <div class="card-header px-2 py-1">
                            <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_masivaCms"><i class="fa fa-arrow-left"></i> Atras Masiva CMS</a>
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
            <div class="tab-pane fade" id="historicoNodoTrobaTab" role="tabpanel" aria-labelledby="historicoNodoTrobaTab-tab">
                <section  class="col-12 mx-0 px-0">
                    <div class="card-header px-2 py-1"> 
                        <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_masivaCms"><i class="fa fa-arrow-left"></i> Atras Masiva CMS</a>
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
            
            
            @if(Auth::user()->HasPermiso('submodulo.masiva-cms.trabajos-programados.view'))
                <script>
                    VER_TRABPROGRAMADOS_PERMISO = true
                </script>
            @endif
            

            
            @if(Auth::user()->HasPermiso('submodulo.masiva-cms.gestion-masiva.delete'))
                <script>
                    ELIMINAR_MASIVA = true
                </script>
            @endif
            













        </div> 
    </div>


@endsection


@section('scripts-footer')  

<script>

        const COLUMNS_DEFS_MONITOR_AVERIAS = 
            [
                {
                    'targets': '_all',
                    'createdCell':  function (td, cellData, rowData, row, col) {

                        $(td).css({"background":`${rowData.background}`,"color":`${rowData.color}`});
                        
                        if(col == 9){//Offline
                            $(td).css({"background":`${rowData.backgroundOff}`,"color":`${rowData.colorOff}`});
                        }
                        
                    }
                },
                    { 
                        "targets": '_all',
                        //"orderable" : false,
                        "searchable": false,
                    } 
                
            ]

</script>



@if (isset($motivo) && $motivo=="cuadroMando")
<script>
    
    const BUTTONS_MONITOR_AVERIAS_HFC =
        [
            {
                text: 'DESCARGAS',
                className: 'btn btn-sm btn-success shadow-sm',
                titleAttr: 'DESCARGAS EN MONITOREO DE AVERÍAS HFC',
                action: function ( e, dt, node, config ) {
                    let descrip = "dashboard"
                    $('#descargasModal input[name=motivo]').val(descrip);
                    $("#descargasModal").modal("show");
                }
            }
        ]

</script>
@else
<script>
    
    const BUTTONS_MONITOR_AVERIAS_HFC =
        [
            {
                text: 'FILTROS',
                className: 'btn btn-sm btn-info shadow-sm',
                titleAttr: 'FILTROS EN MONITOREO DE AVERÍAS HFC',
                action: function ( e, dt, node, config ) {
                    $("#filtroContentMasivas").slideToggle()
                }
            },
            {
                text: 'DESCARGAS',
                className: 'btn btn-sm btn-success shadow-sm',
                titleAttr: 'DESCARGAS EN MONITOREO DE AVERÍAS HFC',
                action: function ( e, dt, node, config ) {
                    $("#descargasModal").modal("show");
                }
            }
        ]

</script>
@endif
    

    <script src="{{ url('/js/sistema/modulos/masiva-cms/reporte-masiva.min.js') }}"></script>

    @if(Auth::user()->HasPermiso('submodulo.masiva-cms.trabajos-programados.view'))
        <script src="{{ url('/js/sistema/modulos/masiva-cms/trabajos-programados.min.js') }}"></script>
    @endif

    @if(Auth::user()->HasPermiso('submodulo.masiva-cms.gestion-masiva.delete'))
        <script src="{{ url('/js/sistema/modulos/masiva-cms/eliminar-masiva.min.js') }}"></script>
    @endif

    <script src="{{ url('/js/sistema/modulos/masiva-cms/index.min.js') }}"></script>
    <script src="{{ url('/js/sistema/modulos/masiva-cms/historial-gestion.min.js') }}"></script>
    <script src="{{ url('/js/sistema/modulos/masiva-cms/clientes-criticos.min.js') }}"></script>

@endsection