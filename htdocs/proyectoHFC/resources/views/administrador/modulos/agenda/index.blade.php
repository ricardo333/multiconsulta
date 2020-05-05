@extends('layouts.master')

@section('titulo_pagina_sistema', 'Agenda')
 
@section('estilos') 
     
@endsection

@section('scripts-header')
    <script>
        var GESTION_PERMISO = false
        var REFRESH_PERMISO = false
    </script>
@endsection
@php
        $GESTION_PERMISO = false;
        $REFRESH_PERMISO = false;
@endphp

@section('top-left-submenus')
    @parent
    {{-- Menu Top--}}
@endsection
 
@section('title-container')
        <h4 class="m-0 text-dark text-uppercase">Agenda</h4>  
@endsection
  
@section('ruta-navegacion-container')
    @parent
     <li class="breadcrumb-item active">Agenda</li>
@endsection

@section('aside-right')
    {{-- Aqui el aside del lado derecho, ingresar lo que sedea mostrar--}}
@endsection

@section('content')
    @parent

    @include('administrador.modulos.agenda.partials.descargarAgendasModal')

    @if(Auth::user()->HasPermiso('submodulo.agendas.gestion.store'))
        @php $GESTION_PERMISO = true; @endphp
        <script>GESTION_PERMISO = true</script>
    @endif
     
 
    <div class="row">
        
 
        <div class="tab-content w-100" id="tabsAgendasContent">
           
            <div class="tab-pane listaAgendas fade show active" id="AgendasListadoTab" role="tabpanel" aria-labelledby="AgendasListadoTab-tab">
                <section  class="col-12 mx-0 px-0">
                <div class="card-header px-2 py-1"> 
                        <a href="{{route('administrador')}}" class="btn btn-sm btn-outline-success mx-1"><i class="fa fa-arrow-left"></i> Atras </a>
                        <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                </div>
                <div class="cad"> 
                    
                    <div class="card-body position-relative" id="contenedor_caidasMasivas_body">
                        <div class="h5 text-center d-block text-danger mb-3">Lista de Agendas</div>
                        <section class="row my-3 py-2 content_filter_basic" id="filtroContentAgenda" style="display:none;">
                            <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                <label for="filtroCodClienteBasic" class="col-12 col-sm-4">Cod. Cliente:</label>
                                <input type="text" name="filtroCodClienteBasic" id="filtroCodClienteBasic" class="col-12 col-sm-8 form-control form-control-sm shadow-sm">
                            </div>
                            <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                    <label for="filtroEstadoBasic" class="col-12 col-sm-3">Estados:</label>
                                    <select name="filtroEstadoBasic" id="filtroEstadoBasic" class="col-12 col-sm-9 form-control form-control-sm shadow-sm">
                                        <option value="seleccionar">Seleccionar</option>
                                        @forelse ($estados as $est)
                                            <option value="{{$est->estado}}">{{$est->estado}}</option>
                                        @empty
                                            
                                        @endforelse
                                    </select>
                            </div>
                            <div class="form-group row mx-0 mb-0 px-2 col-12 col-sm-12   justify-content-center">
                                    <a href="javascript:void(0)" class="btn btn-sm btn-primary shadow-sm w-25" id="filtroBasicoAgenda">Filtrar</a>
                            </div>
                        </section>
                          
                        <div class="content_table_list"> 
                            <table id="resultAgendasLista" class="table table-hover table-bordered w-100 tableFixHead table-text-xs">
                                <thead>
                                    <tr>
                                            <th>Item</th>
                                            <th>Codcli</th>
                                            <th>Servicio</th>
                                            <th>Nodo</th>
                                            <th>Telefono1</th>
                                            <th>Telefono2</th>
                                            <th>Nombre</th>
                                            <th>Codreq</th>
                                            <th>Fecha</th>
                                            <th>Turno</th>
                                            <th>Tipo Agenda</th>
                                            <th>Estado</th>
                                            <th>Quiebre</th>
                                            <th>Fecreg Agenda</th>
                                            <th>Observaciones</th>
                                            @if($GESTION_PERMISO)
                                                <th>Gestion</th> 
                                            @endif  
                                    </tr>
                                </thead>  
                            </table>
                        </div>
                    </div>
                </div>
                </section> 
            </div> 
            @if($GESTION_PERMISO)
                <div class="tab-pane fade " id="gestionIndividualTab" role="tabpanel" aria-labelledby="gestionIndividualTab-tab">
                        <section  class="col-12 mx-0 px-0">
                                <div class="card">
                                    <div class="card-header px-2 py-1">
                                        <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_agenda"><i class="fa fa-arrow-left"></i> Atras Lista Agenda</a>
                                        <span id="content_btn_dinamic_historico"></span>
                                        <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                                    </div>
                                    <div class="card-body">
                                        <h4 class="w-100 text-center text-uppercase font-weight-bold text-secondary">Registro de Gestión</h4>
                                        <div id="preloadGestionAgenda"></div>
                                        <div class="form row m-0" id="contentFormAgenda">
                                            <div class="form-group col-12 my-2 justify-content-center text-center text-danger" id="resultFormSendAgenda"> 
                                            </div>
                                            <div class="form-group col-12 col-sm-6 row mx-0 p-0">
                                                <label for="estadoGestionAgendaStore" class="col-12 col-sm-4">Estado:</label>
                                                <select name="estadoGestionAgendaStore" id="estadoGestionAgendaStore" class="form-control form-control-sm shadow-sm col-12 col-sm-8">
                                                    <option value="seleccionar">seleccionar</option>
                                                    @foreach ($estadosGestionAgenda as $item)
                                                        <option value="{{$item->estado}}">{{$item->estado}}</option>
                                                    @endforeach
                                                </select> 
                                            </div>
                                            <div class="form-group col-12 col-sm-6 row mx-0 p-0">
                                                <label for="quiebreGestionAgendaStore" class="col-12 col-sm-4">Quiebre:</label>
                                                <select name="quiebreGestionAgendaStore" id="quiebreGestionAgendaStore" class="form-control form-control-sm shadow-sm col-12 col-sm-8">
                                                    <option value="seleccionar">seleccionar</option>
                                                    @foreach ($quiebres as $item)
                                                        <option value="{{$item->quiebre}}">{{$item->quiebre}}</option>
                                                    @endforeach
                                                </select> 
                                            </div>
                                            <div class="form-group col-12 row mx-0 p-0">
                                                <label for="observacionesGestionAgendaStore" class="col-12">Observaciones:</label>
                                                <textarea name="observacionesGestionAgendaStore" id="observacionesGestionAgendaStore" cols="30" rows="10" style="max-height:130px;" class="form-control form-control-sm shadow-sm col-12"></textarea>
                                            </div>
                                            <div class="form-group col-12 row text-center justify-content-center">
                                                <a href="javascript:void(0)" class="btn btn-sm btn-outline-success shadow-sm" id="storeSendAgendaGestion">Registrar</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </section>
                </div>  
                <div class="tab-pane fade " id="historialGestionIndividualTab" role="tabpanel" aria-labelledby="historialGestionIndividualTab-tab">
                        <section  class="col-12 mx-0 px-0">
                                <div class="card">
                                    <div class="card-header px-2 py-1">
                                        <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm return_agenda"><i class="fa fa-arrow-left"></i> Atras Lista Agenda</a>
                                        
                                        <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                                    </div>
                                    <div class="card-body">
                                        <h4 class="w-100 text-center text-uppercase font-weight-bold text-secondary">Historico de movimientos de esta agenda</h4>
                                        
                                        <div class="content_table_list"> 
                                            <table id="resultHistorialAgendaCli" class="table table-hover table-bordered w-100 tableFixHead table-text-xs">
                                                <thead>
                                                    <tr>
                                                            <th>ID</th>
                                                            <th>ESTADO</th>
                                                            <th>QUIEBRE</th>
                                                            <th>COMENTARIO</th>
                                                            <th>USUARIO</th>
                                                            <th>FECHAMOV</th> 
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

    @if(Auth::user()->HasPermiso('submodulo.agendas.refresh'))
        <script>
            REFRESH_PERMISO = true
        </script>
    @endif

    <script>
        var ESTA_ACTIVO_REFRESH = false
        var INTERVAL_LOAD = null
    </script>

  
    <script src="{{ url('/js/sistema/modulos/agenda/index.min.js') }}"></script>

    @if($GESTION_PERMISO)

        <script src="{{ url('/js/sistema/modulos/agenda/gestion.min.js') }}"></script>
         
    @endif  
    
      
@endsection