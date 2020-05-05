@extends('layouts.master')

@section('titulo_pagina_sistema', 'Mensajes al Operador')
 
@section('estilos')
     <style>
       #nameFileValidate {
          margin-top: -13px;
          font-size: 11px;
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
     <h4 class="m-0 text-dark text-uppercase">Mensajes al Operador</h4> 
    
@endsection
@section('ruta-navegacion-container')
    @parent
     <li class="breadcrumb-item active">Mensajes al Operador</li>
@endsection

@section('aside-right')
    {{-- Aqui el aside del lado derecho, ingresar lo que sedea mostrar--}}
@endsection

@section('content')
    @parent

     
    <div class="row">

      <div class="tab-content w-100" id="tabsMensajesOperador">
        <div class="tab-pane fade show   active" id="mensajesOperadorTab" role="tabpanel" aria-labelledby="mensajesOperadorTab-tab">
            <section  class="col-12 mx-0 px-0">
              <div class="card-header px-2 py-1"> 
                    <a href="{{route('administrador')}}" class="btn btn-sm btn-outline-success mx-1"><i class="fa fa-arrow-left"></i> Atras</a>
                    <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
              </div>
              <div class="cad">
                <div class="card-body" id="contenedor_mensajesOperador_body">
                    <section class="col-12 row mx-0 px-1" id="subirFile_load">
                        <div class="col-10 content-form-subirFile m-auto">
                          <article id="form_subirMensajesOperador">
                            <div class="form-group">
                                <div class="input-group justify-content-center">
                                  <label for="fileLoadFile" class="col-form-label col-form-label-sm btn btn-outline-info btn-sm d-flex 
                                                                          align-items-center justify-content-center py-0 px-1">
                                          <i class="icofont-cloud-upload icofont-2x"></i> Seleccionar Archivo 
                                  </label>  
                                  <input type="file" accept=".txt, .csv" id="fileLoadFile" class="d-none validateFile"> 
                                  
                                  <a href="javascript: void(0)" id="subirArchivo" class="btn btn-sm btn-success shadow-sm">SubirArchivo</a>
                                  
                                </div>
                            </div>
                          </article>
                        </div>
                        <div class="col-12 text-center mb-2" id="nameFileValidate"></div>
                      </section> 
                      <section class="col-12 row mx-0" id="mensajeSubirArchivo"></section>
                </div>
              </div>
            </section> 
        </div>
         
      </div>
           
    </div>

    
@endsection

@section('scripts-footer')   

    <script src="{{ url('/js/sistema/modulos/mensajes-operador/index.min.js') }}"></script>
    <script src="{{ url('/js/sistema/modulos/mensajes-operador/subir-file.min.js') }}"></script>

@endsection