<!DOCTYPE html>
<html  lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1"> 
	<title>Navegador no Compatible con la Aplicación</title>
 
    <style>
         @font-face {
            font-family: 'Roboto';
            src: url('/css/fuentes/Roboto-Regular.ttf');
        }
        
    </style>
    <link rel="stylesheet" type="text/css" href="{{ url('/css/page-error-navegador.css') }}">
	 
</head>

<body>

    <div class="message">
        <h1>Navegador no Compatible con la Aplicación</h1><br>
        <h3> Esta aplicación funciona mejor en los navegadores Chrome o Firefox.</h3>
        <!-- use window.history.back(); to go back -->
        
    </div>

</body>
  
</html>