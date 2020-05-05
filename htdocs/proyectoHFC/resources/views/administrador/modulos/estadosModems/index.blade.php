@extends('layouts.master')

@section('titulo_pagina_sistema', 'Estado de cable Modems')
 
@section('estilos') 
    <style>
        .dt-buttons {
            opacity: 0;
        }
        .enlace_desactivado {
            pointer-events: none;
            cursor: default;
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
    </style>
@endsection

@section('scripts-header')
    <script>
        var GESTION_PERMISO = false
        var DIAGNOSTICOM_PERMISO = false
        var MAPA_PERMISO = false
        var VER_CRITICOS_PERMISO = false
        var VER_TRABPROGRAMADOS_PERMISO = false
        var REFRESH_PERMISO = false
    </script>
@endsection

@section('top-left-submenus')
    @parent
    {{-- Menu Top--}}
@endsection

@section('title-container')
    <h4 class="m-0 text-dark text-uppercase">MONITOREO DE ESTADO DE CABLE MODEMS</h4> 
@endsection

@section('ruta-navegacion-container')
    @parent
     <li class="breadcrumb-item active">Estado de Modems</li>
@endsection

@section('aside-right')
    {{-- Aqui el aside del lado derecho, ingresar lo que sedea mostrar--}}
@endsection

@section('content')

    <div class="row">

        <div class="tab-content w-100" id="tabsEstadosModems">
            <div class="tab-pane listaLlamadas fade show   active" id="EstadosModemsTab" role="tabpanel" aria-labelledby="EstadosModemsTab-tab">
                <input type="hidden" value="EstadosModemsTab" id="input-EstadosModemsTab">
                <section  class="col-12 mx-0 px-0">
                    <div class="card-header px-2 py-1"> 
                        <a href="{{ route('administrador') }}" class="btn btn-sm btn-outline-success mx-1"><i class="fa fa-arrow-left"></i> Atras </a>
                        <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                    </div>
                    <div class="cad">
                        <div id="errorExcel"></div>
                        <div class="card-body position-relative" id="contenedor_caidasMasivas_body">
                            <div class="content_table_list">
                                <div id="preloadMaping" class="pre-load-estados-modems"> </div>
                                <table id="resultEstadosModems" class="table table-hover table-bordered w-100 tableFixHead table-text-xs">
                                    <thead>
                                        <tr> 
                                            <th>N°</th>
                                            <th>Id</th>
                                            <th>Tipo</th>
                                            <th>cmts</th>
                                            <th>
                                                <a href="javascript:void(0)" data-uno="init(r1)" class="text-white exportExcelEstadosModems">init(r1)</a>
                                            </th>
                                            <th>
                                                <a href="javascript:void(0)" data-uno="init(r2)" class="text-white exportExcelEstadosModems">init(r2)</a>
                                            </th>
                                            <th>
                                                <a href="javascript:void(0)" data-uno="init(rc)" class="text-white exportExcelEstadosModems">init(rc)</a>
                                            </th>
                                            <th>
                                                <a href="javascript:void(0)" data-uno="init(r)" class="text-white exportExcelEstadosModems">init(r)</a>
                                            </th>
                                            <th>
                                                <a href="javascript:void(0)" data-uno="sinippublica" class="text-white exportExcelEstadosModems">Sin Ip Publ.</a>
                                            </th>
                                            <th>
                                                <a href="javascript:void(0)" data-uno="init(d)" class="text-white exportExcelEstadosModems">init(d)</a>
                                            </th>
                                            <th>
                                                <a href="javascript:void(0)" data-uno="init(i)" class="text-white exportExcelEstadosModems">init(i)</a>
                                            </th>
                                            <th>
                                                <a href="javascript:void(0)" data-uno="init(o)" class="text-white exportExcelEstadosModems">init(o)</a>
                                            </th>
                                            <th>
                                                <a href="javascript:void(0)" data-uno="init(io)" class="text-white exportExcelEstadosModems">init(io)</a>
                                            </th>
                                            <th>
                                                <a href="javascript:void(0)" data-uno="init(t)" class="text-white exportExcelEstadosModems">init(t)</a>
                                            </th>
                                            <th>
                                                <a href="javascript:void(0)" data-uno="init(dr)" class="text-white exportExcelEstadosModems">init(dr)</a>
                                            </th>
                                            <th>
                                                <a href="javascript:void(0)" data-uno="cc-pending" class="text-white exportExcelEstadosModems">cc-pending</a>
                                            </th>
                                            <th>
                                                <a href="javascript:void(0)" data-uno="reject(na)" class="text-white exportExcelEstadosModems">reject(na)</a>
                                            </th>
                                            <th>
                                                <a href="javascript:void(0)" data-uno="p-online" class="text-white exportExcelEstadosModems">p-online</a>
                                            </th>
                                            <th>
                                                <a href="javascript:void(0)" data-uno="w-expire(pt)" class="text-white exportExcelEstadosModems">w-expire(pt)</a>
                                            </th>
                                            <th>
                                                <a href="javascript:void(0)" data-uno="online(pt)" class="text-white exportExcelEstadosModems">online(pt)</a>
                                            </th>
                                            <th>
                                                <a href="javascript:void(0)" data-uno="w-online(pt)" class="text-white exportExcelEstadosModems">w-online(pt)</a>
                                            </th>
                                            <th>
                                                <a href="javascript:void(0)" data-uno="online(d)" class="text-white exportExcelEstadosModems">online(d)</a>
                                            </th>
                                            <th>
                                                <a href="javascript:void(0)" data-uno="online" class="text-white exportExcelEstadosModems">online</a>
                                            </th>
                                            <th>offline</th>
                                            <th>total</th>
                                        </tr>
                                    </thead> 
                                    <tfoot>
                                        <tr class="text-center">
                                            <th style="background:{{ $colores->default->colores[0]->background }}; color:{{ $colores->default->colores[0]->color }}">Total</th>
                                            <th style="background:{{ $colores->default->colores[0]->background }}; color:{{ $colores->default->colores[0]->color }}"></th>
                                            <th style="background:{{ $colores->default->colores[0]->background }}; color:{{ $colores->default->colores[0]->color }}"></th>
                                            <th style="background:{{ $colores->default->colores[0]->background }}; color:{{ $colores->default->colores[0]->color }}"></th>
                                            <th style="background:{{ $total[0]->init_r1 > 50 ? $colores->init_r1->colores[0]->background : $colores->init_r1->colores[1]->background }}; color:{{ $total[0]->init_r1 > 200 ? $colores->init_r1->colores[0]->color : $colores->init_r1->colores[1]->color }}">{{ $total[0]->init_r1 }} </th>      
                                            <th style="background:{{ $total[0]->init_r2 > 50 ? $colores->init_r2->colores[0]->background : $colores->init_r2->colores[1]->background }}; color:{{ $total[0]->init_r2 > 200 ? $colores->init_r2->colores[0]->color : $colores->init_r2->colores[1]->color }}">{{ $total[0]->init_r2 }} </th>      
                                            <th style="background:{{ $total[0]->init_rc > 50 ? $colores->init_rc->colores[0]->background : $colores->init_rc->colores[1]->background }}; color:{{ $total[0]->init_rc > 200 ? $colores->init_rc->colores[0]->color : $colores->init_rc->colores[1]->color }}">{{ $total[0]->init_rc }} </th>      
                                            <th style="background:{{ $total[0]->init_r > 50 ? $colores->init_r->colores[0]->background : $colores->init_r->colores[1]->background }}; color:{{ $total[0]->init_r > 200 ? $colores->init_r->colores[0]->color : $colores->init_r->colores[1]->color }}">{{ $total[0]->init_r }} </th>      
                                            <th style="background:{{ $total[0]->sinippublica > 200 ? $colores->sinippublica->colores[0]->background : $colores->sinippublica->colores[1]->background }}; color:{{ $total[0]->sinippublica > 200 ? $colores->sinippublica->colores[0]->color : $colores->sinippublica->colores[1]->color }}">{{ $total[0]->sinippublica }} </th>      
                                            <th style="background:{{ $total[0]->init_d > 50 ? $colores->init_d->colores[0]->background : $colores->init_d->colores[1]->background }}; color:{{ $total[0]->init_d > 200 ? $colores->init_d->colores[0]->color : $colores->init_d->colores[1]->color }}">{{ $total[0]->init_d }} </th>      
                                            <th style="background:{{ $total[0]->init_i > 50 ? $colores->init_i->colores[0]->background : $colores->init_i->colores[1]->background }}; color:{{ $total[0]->init_i > 200 ? $colores->init_i->colores[0]->color : $colores->init_i->colores[1]->color }}">{{ $total[0]->init_i }} </th>      
                                            <th style="background:{{ $total[0]->init_o > 50 ? $colores->init_o->colores[0]->background : $colores->init_o->colores[1]->background }}; color:{{ $total[0]->init_o > 200 ? $colores->init_o->colores[0]->color : $colores->init_o->colores[1]->color }}">{{ $total[0]->init_o }} </th>      
                                            <th style="background:{{ $total[0]->init_io > 50 ? $colores->init_io->colores[0]->background : $colores->init_io->colores[1]->background }}; color:{{ $total[0]->init_io > 200 ? $colores->init_io->colores[0]->color : $colores->init_io->colores[1]->color }}">{{ $total[0]->init_io }} </th>      
                                            <th style="background:{{ $total[0]->init_t > 50 ? $colores->init_t->colores[0]->background : $colores->init_t->colores[1]->background }}; color:{{ $total[0]->init_t > 200 ? $colores->init_t->colores[0]->color : $colores->init_t->colores[1]->color }}">{{ $total[0]->init_t }} </th>      
                                            <th style="background:{{ $total[0]->init_dr > 50 ? $colores->init_dr->colores[0]->background : $colores->init_dr->colores[1]->background }}; color:{{ $total[0]->init_dr > 200 ? $colores->init_dr->colores[0]->color : $colores->init_dr->colores[1]->color }}">{{ $total[0]->init_dr }} </th>      
                                            <th style="background:{{ $colores->default->colores[0]->background }}; color:{{ $colores->default->colores[0]->color }}">{{ $total[0]->cc_pending }}</th>      
                                            <th style="background:{{ $colores->default->colores[0]->background }}; color:{{ $colores->default->colores[0]->color }}">{{ $total[0]->reject }}</th>
                                            <th style="background:{{ $total[0]->p_online > 50 ? $colores->p_online->colores[0]->background : $colores->p_online->colores[1]->background }}; color:{{ $total[0]->p_online > 200 ? $colores->p_online->colores[0]->color : $colores->p_online->colores[1]->color }}">{{ $total[0]->p_online }} </th>      
                                            <th style="background:{{ $colores->default->colores[0]->background }}; color:{{ $colores->default->colores[0]->color }}">{{ $total[0]->w_expire_pt }}</th>      
                                            <th style="background:{{ $colores->default->colores[0]->background }}; color:{{ $colores->default->colores[0]->color }}">{{ $total[0]->online_pt }}</th>      
                                            <th style="background:{{ $colores->default->colores[0]->background }}; color:{{ $colores->default->colores[0]->color }}">{{ $total[0]->w_online_pt }}</th>      
                                            <th style="background:{{ $colores->default->colores[0]->background }}; color:{{ $colores->default->colores[0]->color }}">{{ $total[0]->online_d }}</th>      
                                            <th style="background:{{ $colores->default->colores[0]->background }}; color:{{ $colores->default->colores[0]->color }}">{{ $total[0]->online }}</th>      
                                            <th style="background:{{ $colores->default->colores[0]->background }}; color:{{ $colores->default->colores[0]->color }}">{{ $total[0]->offline }}</th>      
                                            <th style="background:{{ $colores->default->colores[0]->background }}; color:{{ $colores->default->colores[0]->color }}">{{ $total[0]->total }}</th>      
                                        </tr>
                                    </tfoot>
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

    <script src="{{ url('/js/sistema/modulos/estados-modems/reporte-estados-modems.min.js') }}"></script>
    <script src="{{ url('/js/sistema/modulos/estados-modems/index.min.js') }}"></script>
      
@endsection