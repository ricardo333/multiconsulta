@extends('layouts.master')

@section('titulo_pagina_sistema', 'Edición de Puertos')
 
@section('estilos') 
    <style>
        .content_filter_basic {
            border: 1px solid rgba(192, 200, 208, 0.84);
            border-radius: 5px;
        }
        .margin-right-1{
            margin-right: 1rem;
        }
        .margin-0{
            margin-bottom: 0 !important;
        }
        .margin-padding-5{
            margin-bottom: .5rem;
            padding-top: .5rem;
        }
        .flex-align-justify-center{
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .pre-load-estados-modems{
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .pre-estados-modems{
            position: absolute;
            z-index: 9;
        }
        .flex-align-justify-center{
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .width-70{
            width: 70%;
            max-width: 70%;
        }
        .margin-right-15{
            margin-right: 15px;
        }
    </style>
@endsection

@section('top-left-submenus')
    @parent
    {{-- Menu Top--}}
@endsection

@section('title-container')
    <h4 class="m-0 text-dark text-uppercase">EDICIÓN DE PUERTOS</h4> 
@endsection

@section('ruta-navegacion-container')
    @parent
     <li class="breadcrumb-item active">Edición de Puertos</li>
@endsection

@section('aside-right')
    {{-- Aqui el aside del lado derecho, ingresar lo que sedea mostrar--}}
@endsection

@section('content')
    @parent

    @include('administrador.modulos.etiquetadoPuertos.partials.mensajeEtiquetaPuertosModal')

    <div class="row">

        <div class="tab-content w-100" id="tabsEtiquetadoPuertos">
            <div class="tab-pane etiquetadoPuertos fade show   active" id="etiquetadoPuertosTab" role="tabpanel" aria-labelledby="etiquetadoPuertosTab-tab">
                <input type="hidden" value="etiquetadoPuertosTab" id="input-etiquetadoPuertosTab">
                <section  class="col-12 mx-0 px-0">
                    <div class="card-header px-2 py-1"> 
                        <a href="{{ route('administrador') }}" class="btn btn-sm btn-outline-success mx-1"><i class="fa fa-arrow-left"></i> Atras</a>
                        <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                    </div>
                    <div class="cad">
                        <div class="card-body position-relative" id="contenedor_etiquetadoPuertos_body">
                            <section class="row w-100 my-3 mx-0 py-3 content_filter_basic justify-content-center" id="filtroContentEtiquetadoPuertos" style="display:none">
                                <div class="form-group row mx-0 py-2 col-12 col-sm-8 text-center col-md-6 col-lg-6 justify-content-center margin-0">
                                    <label for="" class="col-sm-12 col-md-6 col-lg-6">CMTS:</label>
                                    <select name="listaCmtsEtiquetadoPuertos" id="listaCmtsEtiquetadoPuertos" class="col-sm-8 col-md-6 col-lg-6 form-control form-control-sm shadow-sm">
                                        <option value="">Sin Filtro</option>
                                            @forelse ($listaCmts as $lista)
                                                <option value="{{$lista->cmts}}">{{$lista->cmts}}</option>
                                            @empty
                                                    
                                            @endforelse
                                    </select>
                                </div>
                                <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 justify-content-center margin-padding-5">
                                    <a href="javascript:void(0)" class="btn btn-sm btn-primary shadow-sm w-25 margin-right-1 flex-align-justify-center" id="filtroEtiquetadoPuertos">Filtrar</a>
                                </div>
                            </section>

                            <div class="content_table_list"> 
                                <div id="preloadMaping" class="pre-load-estados-modems"> </div>
                                <table id="resultEtiquetadoPuertos" class="table table-hover table-bordered w-100 tableFixHead table-text-xs">
                                    <thead>
                                        <tr>
                                            <th>CMTS</th>
                                            <th>Interface</th>
                                            <th>Descripcion</th>
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

    <script>

        function alfanumerico(event) {
            
            var regex = new RegExp("^[a-zA-Z0-9() ]+$");
            var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
            if (!regex.test(key)) {
                event.preventDefault();
                return false;
            }

        }

        const BUTTONS_ETIQUETA_PUERTOS =
        [
            {
                text: 'FILTROS',
                className: 'btn btn-sm btn-info shadow-sm',
                titleAttr: 'FILTROS EN ETIQUETADO DE PUERTOS',
                action: function ( e, dt, node, config ) {
                    $("#filtroContentEtiquetadoPuertos").slideToggle()
                }
            }
        ]
    
   </script>

    <script src="{{ url('/js/sistema/modulos/etiquetado-puertos/index.min.js') }}"></script>
    <script src="{{ url('/js/sistema/modulos/etiquetado-puertos/actualizar.min.js') }}"></script>
      
@endsection