
<!DOCTYPE html>
<html  lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-100">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

   <!-- CSRF Token -->
   <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- Config::get('session.lifetime') //para ver la session actual si func--}}
   <title>{{ config('app.name', 'Sistema') }} | @yield('titulo_pagina_sistema') | {{  Config::get('session.lifetime') }}</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href=" {{ mix('css/app.css') }}" rel="stylesheet">
  <!-- Font Awesome -->  
  <link rel="stylesheet" href="{{ asset('css/icofont.min.css') }}"> 

  @yield('estilos')
  <script src="{{ mix('js/app.js') }}"></script>
  @yield('scripts-header')

</head>
    <body class="hold-transition sidebar-mini h-100" id="aplicacion_content">
        <div class="wrapper h-100">

            @include('layouts.partials.navbar')
            
            @include('layouts.partials.aside-left',["menus"=>$menus]) {{-- Aside --}}

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                    <div class="col-sm-6">
                        @yield('title-container')
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            @section('ruta-navegacion-container') 
                                <li class="breadcrumb-item"><a href="{{route('administrador')}}">Dashboard</a></li>
                            @show
                        
                        </ol>
                    </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
                </div>
                <!-- /.content-header -->

                <!-- Main content -->
                <section class="content" id="app">
                <div class="container-fluid"> 
                    @section('content')
                        <!-- Modales -->
                        @include("errors.modalErrors")
                        @include("errors.modalReload")
                        @include("success.successModal")
                        @include("success.confirmDeleteModal")
                        @include("success.detallesModal")
                        @include("success.modalSuccessReload")
                      <!-- Fin modales-->
                    @show
                </div>
                </section>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->
            {{-- @include('layouts.partials.footer') --}}

            <!-- Control Sidebar -->
            {{--<aside class="control-sidebar control-sidebar-light">
                @yield('aside-right')
            </aside> --}}
            <!-- /.control-sidebar -->
        
        
        </div>
        <!-- ./wrapper -->
    
       
    @yield('scripts-footer')

    </body>
</html>
