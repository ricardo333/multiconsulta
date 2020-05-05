<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
       <!-- CSRF Token -->
   <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name', 'Laravel') }}</title> 

    <link rel="stylesheet" type="text/css" href="{{ url('/css/login.css') }}">
    <script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
    <script src="{{ mix('js/app.js') }}"></script>
    {{-- La libreria es cryptojs-aes-php-master :  --}}
    <script src="{{ asset('library/crypt/aes.js') }}"></script>
    <script src="{{ asset('library/crypt/aes-json-format.js') }}"></script>

</head>
<body>
    <section role="main" class="container-fluid contenedor_login">
        @yield('content_form_login')
    </section>
    
    @yield('scripts-footer')
</body>
</html>