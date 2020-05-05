@extends('layouts.master')

@section('titulo_pagina_sistema', 'Extraccion de Reportes')
 
@section('estilos') 
    <style>
        #mapa_content_carga {
                height: calc(100vh - 150px);
            }
        .content_filter_basic {
            border: 1px solid rgba(192, 200, 208, 0.84);
            border-radius: 5px;
        }
    </style>
@endsection

@section('top-left-submenus')
    @parent
    {{-- Menu Top--}}
@endsection

@section('title-container')
     <h4 class="m-0 text-dark text-uppercase">Descarga de reportes de los CMTS</h4> 
    
@endsection

@section('ruta-navegacion-container')
    @parent
     <li class="breadcrumb-item active">Descarga CMTS</li>
@endsection

@section('aside-right')
    {{-- Aqui el aside del lado derecho, ingresar lo que sedea mostrar--}}
@endsection


@section('content')
    @parent
      
    <div class="row">
 
        <div class="tab-content w-100" id="tabsDescargaCmts">

            <div class="tab-pane listaCaidas fade show   active" id="descargaCmtsTab" role="tabpanel" aria-labelledby="descargaCmtsTab-tab">
                <section  class="col-12 mx-0 px-0">
                <div class="card-header px-2 py-1"> 
                        <a href="{{route('administrador')}}" class="btn btn-sm btn-outline-success mx-1"><i class="fa fa-arrow-left"></i> Atras </a>
                        <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                </div>
                <div class="cad"> 
                    <div id="errorExcel"></div>
                    <div class="card-body position-relative" id="contenedor_descargaCmts_body">
                        
                        <div class="content_table_list"> 
                            <table id="resultDescargaCmts" class="table table-hover table-bordered w-100 tableFixHead table-text-xs">
                                <thead>
                                    <tr>
                                        <th>Tipo</th>
                                        <th>Show Cable Modem Sum<br>Resumen de Cable modems x CTMS</th> 
                                        <th>Ultima Actualizacion</th>
                                        <th>Show Cable Modem phy<br>Niveles RF x Cable Modem</th> 
                                        <th>Ultima Actualizacion</th>
                                        <th>Show Cable Modem<br>Estado de sincronismo x Cable Modem</th>
                                        <th>Ultima Actualizacion</th>
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

        var INTERVAL_LOAD = null

        var ESTA_ACTIVO_REFRESH = false

        const BUTTONS_DESCARGAS_CMTS =
        [
            /*
            {
                text: 'DESCARGAS',
                className: 'btn btn-sm btn-success shadow-sm',
                titleAttr: 'DESCARGAS EN CAIDAS',
                action: function ( e, dt, node, config ) {
                    //alert( 'Button Opciones GPON' );
                    //console.log("opciones:", e, dt, node, config)
                    $("#descargasCaidasModal").modal("show");
                }
            }
            */
        ]

    </script>

    <!--
    <script>
        REFRESH_PERMISO = true
    </script>
    

    <script>

        var INTERVAL_LOAD = null
        var ESTA_ACTIVO_REFRESH = false
    
    </script>
    -->

    <script src="{{ url('/js/sistema/modulos/descarga-cmts/index.min.js') }}"></script>
    <script src="{{ url('/js/sistema/modulos/descarga-cmts/descarga.min.js') }}"></script>
      
@endsection

