<!DOCTYPE html>
<html  lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1"> 
	<title>500</title>
 
    <style>
         @font-face {
            font-family: 'Roboto';
            src: url('/css/fuentes/Roboto-Regular.ttf');
        }
        
    </style>
    <link rel="stylesheet" type="text/css" href="{{ url('/css/page-error-405.css') }}">
	 
</head>

<body>

    <div class="message">
        <h1>500</h1>
        <h3>Server Error</h3>
        <h2>{{$mensaje}}</h2>
        <!-- use window.history.back(); to go back -->
        <button id="reloadPage">Recargar</button>
        <a href="{{route('administrador')}}">Regresar Página principal</a>
    </div>

</body>
<script>
    let reload = document.getElementById("reloadPage")
    reload.addEventListener("click", function(){
         location.reload()
    })
</script>

</html>
