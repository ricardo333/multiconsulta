<!DOCTYPE html>
<html  lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1"> 
	<title>404</title>

	<!-- Google font 
	<link href="https://fonts.googleapis.com/css?family=Cabin:400,700" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Montserrat:900" rel="stylesheet">-->

    <!-- Custom stlylesheet --> 
    <style>
         @font-face {
            font-family: 'Cabin';
            src: url('/css/fuentes/Cabin-Regular.ttf');
        }
        
        @font-face {
            font-family: 'Montserrat';
            src: url('/css/fuentes/Montserrat-Black.ttf');
        }
    </style>
    <link rel="stylesheet" type="text/css" href="{{ url('/css/page-error-404.css') }}">
	 
</head>

<body>
	<div id="notfound">
		<div class="notfound">
			<div class="notfound-404">
				<h3>Página no encontrada</h3>
				<h1><span>4</span><span>0</span><span>4</span></h1>
			</div>
			<h2>{{$mensaje}}</h2>
		</div>
	</div>

</body>

</html>
