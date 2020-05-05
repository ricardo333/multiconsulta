@extends('layouts.master')

@section('titulo_pagina_sistema', 'Cuadro Mando')
 
@section('estilos') 
    <style>
        .content_filter_basic {
            border: 1px solid rgba(192, 200, 208, 0.84);
            border-radius: 5px;
        }

        .buttom-flex{
            display: flex;
            justify-content: flex-end;
            padding: 1em;
        }

    </style>
@endsection

@section('scripts-header')
    <script>
        var REFRESH_PERMISO = false
        var ESTA_ACTIVO_REFRESH = false
    </script>
@endsection

@section('top-left-submenus')
    @parent
    {{-- Menu Top--}}
@endsection

@section('title-container')
     <h4 class="m-0 text-dark text-uppercase">Cuadro de Mando HFC</h4> 
    
@endsection

@section('ruta-navegacion-container')
    @parent
     <li class="breadcrumb-item active">Cuadro de Mando HFC</li>
@endsection

@section('aside-right')
    {{-- Aqui el aside del lado derecho, ingresar lo que sedea mostrar--}}
@endsection

@section('content')
    @parent

    @include('administrador.modulos.ingresoAverias.partials.descargasModalJefatura')

    <div class="row">
 
        <div class="tab-content w-100" id="tabsCuadroMandoContent">
            <div class="tab-pane listaCuadroMando fade show   active" id="cuadroMandoTab" role="tabpanel" aria-labelledby="cuadroMandoTab-tab">
                <section  class="col-12 mx-0 px-0">
                <div class="card-header px-2 py-1"> 
                    <a href="{{route('administrador')}}" class="btn btn-sm btn-outline-success mx-1"><i class="fa fa-arrow-left"></i> Atras </a>
                    <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                </div>
                <div class="cad"> 
                    <div class="card-body position-relative" id="contenedor_caidasMasivas_body">
                            
                        <section class="row my-3 py-2 content_filter_basic" id="filtroContentMasivas" style="display:none;">
                            <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                <label for="" class="col-12 col-sm-3">Categorias:</label>
                                <select name="listaJefaturasMasivas" id="listaCategoriasDashboard" class="col-12 col-sm-9 form-control form-control-sm shadow-sm">
                                    <option value="seleccionar">Sin Filtro</option>
                                        @forelse ($categorias as $categ)
                                            <option value="{{$categ->categoria}}">{{$categ->categoria}}</option>
                                        @empty
                                           
                                        @endforelse
                                </select>
                            </div>
                            <div class="form-group row mx-0 mb-0 px-2 col-12 col-sm-12 col-md-12 col-lg-12 justify-content-center">
                                <a href="javascript:void(0)" class="btn btn-sm btn-primary shadow-sm w-25" id="filtroCuadroMando">Filtrar</a>
                            </div>
                        </section>
                        
                        <div class="content_table_list"> 
                            <table id="resultCuadroMando" class="table table-hover table-bordered w-100 tableFixHead table-text-xs">
                                <thead>
                                    <tr>
                                        <th>Detalle</th>
                                        <th>Cantidad</th>
                                        <th>Clientes/Afectados</th>
                                        <th>Link</th>
                                    </tr>
                                </thead>  
                            </table>
                        </div>
                    </div>
                </div>
                </section> 
            </div>  


            <div class="tab-pane fade" id="verContenedorModulo" role="tabpanel" aria-labelledby="verContenedorModulo-tab">
                
            </div>

            <div class="tab-pane fade " id="verContenedorModulo" role="tabpanel" aria-labelledby="verContenedorModulo-tab">
                    <section  class="col-12 mx-0 px-0">
                            <div class="card">
                                <div class="card-body">
                                    <div id="modulo_content_carga"></div>
                                </div>
                            </div>
                    </section>
            </div>

            
        </div>
           
    </div>
    
@endsection

@section('scripts-footer')  

    @if(Auth::user()->HasPermiso('submodulo.cuadro-mando.refresh'))
        <script>
            REFRESH_PERMISO = true

            ESTA_ACTIVO_REFRESH = true
        </script>
    @endif

    <script>

        var INTERVAL_LOAD = null

        const BUTTONS_CUADRO_MANDO =
        [
            {
                    text: 'FILTROS',
                    className: 'btn btn-sm btn-info shadow-sm',
                    titleAttr: 'FILTROS EN CUADRO MANDO',
                    action: function ( e, dt, node, config ) {
                        //alert( 'Button Opciones' );
                        //console.log("opciones:", e, dt, node, config)
                        //console.log("Se deberias mostrar los filtros")
                        $(".content_filter_basic").slideToggle()
                    }
                }
            
        ]

    </script>


    <script src="{{ url('/js/sistema/modulos/cuadro-mando/index.min.js') }}"></script>
    <script src="{{ url('/js/sistema/modulos/cuadro-mando/consulta-modulo.min.js') }}"></script>

    <script src="{{ url('/js/sistema/modulos/ingreso-averias/reporte-ingreso-averias.min.js') }}"></script>
    <script src="{{ url('/js/sistema/modulos/ingreso-averias/index.min.js') }}"></script>
      
@endsection