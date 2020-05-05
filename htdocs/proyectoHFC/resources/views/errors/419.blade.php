<!DOCTYPE html>
<html  lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1"> 
	<title>419</title>
 
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
        <h1>419</h1>
        <h3>Lo sentimos, su sesión no está vigente.</h3>
        <!-- use window.history.back(); to go back -->
        <a href="{{route('administrador')}}">Ir al inicio</a>
    </div>

</body>
<script>
    let reload = document.getElementById("reloadPage")
    reload.addEventListener("click", function(){
         location.reload()
    })
</script>

</html>