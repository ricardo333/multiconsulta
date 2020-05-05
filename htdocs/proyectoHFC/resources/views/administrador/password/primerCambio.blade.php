<!DOCTYPE html>
<html lang="en" class="h-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Primer Cambio de Password</title>
    <link href=" {{ mix('css/bootstrap.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/icofont.min.css') }}"> 
</head>
<style>
    form#content_change_password {
       
        border-radius: 17px;
        
    }
    #politicas_password_change {
        font-size: 0.7em;
    }
    section#contenid_general_cambio {
        background: url(/images/portada_password_cambio.jpg);
        background-repeat: no-repeat;
        background-size: cover;
        background-position: center;
    }
    .card-body{
        background: #f7f7f7;
        color: #2d2d2c;
    }
    div#respuesta-server-login {
        font-size: .8em;
    }
</style>
<body class="h-100">
    <section class="container-fluid h-100 d-flex flex-wrap justify-content-center align-items-center" id="contenid_general_cambio">
        <div class="card col-7 p-0">
            <div class="card-header">
                    <h3 class="w-100 text-center text-uppercase font-weight-bold mb-2">Cambio de contraseña</h3>
            </div>
            <div class="card-body py-1"> 
                <div class="row">
                        <form method="POST"  class="form col-12  px-5 py-3" action="{{ route('password.usuario.update',['usuario'=>Auth()->user()->id]) }}" id="content_change_password">
                                @csrf 
                            <div class="form-group mb-0">
                                <label for="username" class="col-form-label font-weight-bold">Usuario:</label>
                                {{ Auth()->user()->username }}  
                            </div>
                            <div class="form-group mb-0">
                                <label for="password" class="col-form-label font-weight-bold pb-0 ">Nueva Contraseña:</label>
                                <div class="input-group w-100 p-0">  
                                    <input type="password" name="password" id="passwordCambio" class="form-control form-control-sm shadow-sm @error('password') is-invalid @enderror" value="{{ old('password') }}">  
                                    <span class="input-group-btn">
                                        <a href="javascript: void(0)" id="verPasswordUser" class="btn btn-success btn-sm shadow-sm w-100 h-100 d-flex align-items-center icofont-eye"></a>
                                    </span>
                                </div>  
                            </div> 
                            <div class="form-group">
                                <div class="text-center text-danger mt-2" id="respuesta-server-login">
                                    @error('password')
                                    <span class="details_erros" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    @error('changePassword')
                                        <span class="details_erros" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                        
                                </div>
                            </div>
                            <div class="form-group d-flex justify-content-center">
                                <input type="submit" class="btn btn-sm btn-success shadow-sm w-50" value="Cambiar Password">
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12" id="politicas_password_change">
                                        Recuerde tener en cuenta el siguiente formato:  
                                        <ul id="display_politica_password">
                                            <li class="items">longitud mínima de 8 caracteres.</li>
                                            <li class="items">Contar con almenos una letra mayuscula y minuscula.</li>
                                            <li class="items">Contar con almenos un numero.</li>
                                            <li class="items">Contar con un caracter especial. Ejemplo: #?!@$%^&amp;*-</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </form>
                </div>
            </div>
        </div>
    </section>
</body>
<script src="/js/jquery-3.4.1.min.js"></script>
<script> 
    $(function(){

        $("#verPasswordUser").click(function(){
            
            if($(this).hasClass("icofont-eye-blocked")){
                $(this).removeClass("icofont-eye-blocked")
                $(this).addClass("icofont-eye")
                $("#passwordCambio").prop('type','password');
            }else{
                $(this).removeClass("icofont-eye")
                $(this).addClass("icofont-eye-blocked")
                $("#passwordCambio").prop('type','text'); 
            }
        })
    })
</script>
</html>