@extends('layouts.master')

@section('titulo_pagina_sistema', 'Validación Servicios')
 
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
     <h4 class="m-0 text-dark text-uppercase">Validación Servicios</h4> 
    
@endsection
@section('ruta-navegacion-container')
    @parent
     <li class="breadcrumb-item active">Validación Servicios</li>
@endsection

@section('aside-right')
    {{-- Aqui el aside del lado derecho, ingresar lo que sedea mostrar--}}
@endsection

@section('content')
    @parent

     
    <div class="row">

      <div class="tab-content w-100" id="tabsValidacionServicios">
        <div class="tab-pane fade show   active" id="validacionServicioTab" role="tabpanel" aria-labelledby="validacionServicioTab-tab">
            <section  class="col-12 mx-0 px-0">
              <div class="card-header px-2 py-1"> 
                    <a href="{{route('administrador')}}" class="btn btn-sm btn-outline-success mx-1"><i class="fa fa-arrow-left"></i> Atras</a>
                    <a href="javascript:void(0)"  class="btn btn-sm btn-outline-primary shadow-sm float-right maxi_tab"><i class="icofont-maximize"></i></a>
              </div>
              <div class="cad">
                <div id="preload_servicioValida"></div>
                <div class="card-body" id="contenedor_validacionServicio_body">
                    <section class="col-12 row mx-0 px-1" id="validacionServicio_load">
                      <div class="col-10 content-form-servicioValida m-auto">
                        <article id="form_validacionServicio">
                          <div class="form-group">
                              <div class="input-group justify-content-center">
                                  <div class="input-group-btn">
                                      <select name="type_validacionServ" id="type_validacionServ" class="form-control form-control-sm shadow-sm validateSelect">
                                        <option value="seleccionar">Tipo Validación</option>
                                        <option value="1">Cod Cliente CMS</option>
                                        <option value="2">Mac Address</option> 
                                      </select>    
                                  </div>
                                {{--<input type="text" id="text_m" name="text_m" class="form-control form-control-sm shadow-sm">
                                <a href="javascript:void(0)" class="btn btn-outline-primary btn-sm shadow-sm" id="selectArchivoBtn">Seleccionar Archivo</a> --}}
                                <label for="fileLoadValidaServicio" class="col-form-label col-form-label-sm btn btn-outline-info btn-sm d-flex 
                                                                        align-items-center justify-content-center py-0 px-1">
                                        <i class="icofont-cloud-upload icofont-2x"></i> Seleccionar Archivo 
                                </label>  
                                <input type="file"  id="fileLoadValidaServicio" class="d-none validateFile"> 
                                
                                <a href="javascript: void(0)" id="subirArchivoVal" class="btn btn-sm btn-success shadow-sm">SubirArchivo</a>
                                
                              </div>
                          </div>
                        </article>
                      </div>
                      <div class="col-12 text-center mb-2" id="nameFileValidate"></div>
                    </section> 
                    <section class="col-12 row mx-0" id="rpta_validacionServ"> 
                    </section>
                    <section class="col-12 row mx-0 d-none" id="buttons_validacionesServ"> 
                        <div class="w-100">
                            <div class="w-100 text-center">
                                <a href="javascript:void(0)" id="procesarDataValidacion" class="btn btn-sm btn-outline-success shadow-sm m-1">Procesar Data de todas maneras.</a>
                                <a href="javascript:void(0)" id="reProcesarValidacion" class="btn btn-sm btn-outline-success shadow-sm m-1">Cancelar proceso.</a>
                            </div>
                        </div>
                    </section>
                </div>
              </div>
            </section> 
        </div>
         
      </div>
           
    </div>

    
@endsection

@section('scripts-footer')   

    <script src="{{ url('/js/sistema/modulos/gestion/validacion-servicio/index.min.js') }}"></script>
    

    
    

@endsection