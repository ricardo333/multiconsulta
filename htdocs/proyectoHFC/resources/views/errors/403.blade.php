<!DOCTYPE html>
<html  lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1"> 
	<title>403</title>
 
    <style>
         @font-face {
            font-family: 'Roboto';
            src: url('/css/fuentes/Roboto-Regular.ttf');
        }
        
    </style>
    <link rel="stylesheet" type="text/css" href="{{ url('/css/page-error-403.css') }}">
	 
</head>

<body class="bg-dark text-white py-5">
    <div class="container py-5">
         <div class="row">
              <div class="col-md-2 text-center">
                   <p class="text-warning"><i class="fa fa-exclamation-triangle fa-5x "></i><br/>Codigo : 403</p>
              </div>
              <div class="col-md-10">
                   <h3>Lo siento, hay un problema!</h3>
                   <p>Usted no tiene los permisos necesarios para poder ingresar a esta sección.<br/>Por favor regrese hacia la página anterior para continuar.</p>
                   <a class="btn btn-outline-warning shadow-lg" href="javascript:history.back()">Regresar Página Anterior</a>
              </div>
         </div>
    </div>
 
</body>
 

</html>

