@extends('layouts.master')

@section('titulo_pagina_sistema', 'Diagnostico Outside')
 
@section('estilos')
    <style>

        #menuDiagnostico {
            display: flex;
            align-items: baseline;
            place-content: space-between;
            padding: 2px 20px;
        }

        #mapaOutside {
            height: calc(100vh - 150px);
        }

        #textDistancia {
            max-width: 100px;
        }

        #selectDistancia {
            max-width: 380px;
            display: flex;
            padding: 4px;
            align-items: baseline;

        }

        #listaDistanciaClientes {
            max-width: 110px;
        }

        #diagnostico {
            max-width: 140px;
        }
    
    
    </style>

@endsection
@section('scripts-header')
        
@endsection
 

@section('top-left-submenus')
    @parent
    {{-- Menu Top--}}
@endsection

@section('title-container')
     <h4 class="m-0 text-dark text-uppercase">Diagnostico Outside</h4> 
    
@endsection
@section('ruta-navegacion-container')
    @parent
     <li class="breadcrumb-item active">Diagnostico Outside</li>
@endsection

@section('aside-right')
    {{-- Aqui el aside del lado derecho, ingresar lo que sedea mostrar--}}
@endsection

@section('content')



    <div class="row">

        <div class="tab-content w-100" id="tabsDiagnosticoOutsideContent">
            <div class="tab-pane listaDiagnosticoOutside fade show   active" id="mapaOutsideTab" role="tabpanel" aria-labelledby="mapaOutsideTab-tab">
                <section  class="col-12 mx-0 px-0">
                    <div class="card-header px-2 py-1"> 
                            <a href="{{route('administrador')}}" class="btn btn-sm btn-outline-success mx-1"><i class="fa fa-arrow-left"></i> Atras</a>
                            <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
                    </div>
                </section>

                
                <section  class="col-12 mx-0 px-0">
                <div class="card">

                    <input type="hidden" id="latitud">
                    <input type="hidden" id="longitud">

                    <div id="menuDiagnostico">
                        <div id="selectDistancia">
                            <label id="textDistancia" class="col-12 col-sm-3">Distancia:</label>
                            <select name="listaDistanciaClientes" id="listaDistanciaClientes" class="col-12 col-sm-9 form-control form-control-sm shadow-sm">
                                <option value="70">70 metros</option>
                                <option value="120">120 metros</option>
                                <option value="200">200 metros</option>
                            </select>
                        </div>
                        <div id="diagnostico">
                            <input type="button" id="diagnosticar" value="Consultar">
                        </div>
                    </div>

                    <div class="card-body">
                        <div id="mapaOutside"></div>
                    </div>

                </div>
                </section>

            </div>
        </div>
           
    </div>



@endsection

@section('scripts-footer')

    <script src="{{ url('/js/sistema/modulos/diagnostico-outside/index.min.js') }}"></script>
   
@endsection