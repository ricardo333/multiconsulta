@extends('layouts.loginTemplate')

@section('content_form_login')
<div class="row">
    <div class="d-none d-sm-none d-md-block  col-md-7 col-lg-8  content-image-login-sis">
    <img src="{{ url('/images/portada_login.jpg') }}" class="img-content-login"/>
    </div>
    <div class="col-sm-12 col-md-5 col-lg-4 content-form-login-sis d-flex align-items-end">
        <div class="cuadro-content-login">
            
            <h2 class="text-center">SISTEMA DE CONTROL HFC</h2>
            <p class="txt-bn-login text-center">Bienvenido al <br/>  CENTRO DE CONTROL PRINCIPAL</p>
            <i class="linea-login"></i>
            <p class="txt-bn-login ingresa-txt text-center"><strong>INGRESA AL SISTEMA</strong></p>
            <div id="reloadLogin"></div>
            <form method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}" id="form_login" autocomplete="off" class="form " name="formulario">
                    @csrf 
                    
                    <div class="form-group"> 
                        <label for="username" class="col-form-label">Usuario</label>
                        <input id="ByCript" type="text" class="d-none" name="ByCript"> 
                        <input id="username"  type="text" class="form-control @error('username') is-invalid @enderror" {{--value="{{ old('username')--}} }}" name="username" autofocus>
                            @error('username')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            
                    </div>
  
                    <div class="form-group position-relative">
                        <label for="password" class="col-form-label">Password</label>
                        <input id="passwordText" type="text"  data-uno="" class="form-control @error('password') is-invalid @enderror" name="passwordText">
                        <input id="password" type="text"  class="form-control-password " name="password">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div> 

                    <div class="form-group row captcha">
                        <span class="col-6 col-sm-6 col-md-6">{!! captcha_img('match') !!}</span>
                        <div class="col-6 col-sm-6 col-md-6 text-right">
                            <button type="button" class="btn btn-primary captcha-refresh">{{ __('Refrescar') }}</button>
                        </div>
                    </div>

                    <div class="form-group"> 
                        <input type="text" id="captcha" name="captcha" class="form-control @error('captcha') is-invalid @enderror">
                            @error('captcha')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                    </div>
                    
                    <div class="form-group">
                        <div id="respuesta-server-login">
                            @error('auth')
                                <span class="details_erros" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                             
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn  btn-sistema" id="ingresar">
                            {{ __('Ingresar') }}
                        </button>
                    </div>
                  
		    @if (isset($browser))
                        @if ($browser != "Chrome" && $browser != "Firefox")
                            <div class="form-group">
                                <div class="container text-center font-weight-bold alert alert-warning fade show" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    Esta aplicación funciona mejor con los navegadores Chrome o Firefox </div>
                            </div>
                        @endif
                    @endif


                    <div class="form-group texto-form-login">
                        <a class="btn btn-link" href="javascript:void(0)">
                                {{ __('Olvido su contraseña?') }}
                        </a> 
                    </div>
            </form>
        </div>
    </div>
</div>
 
@endsection

@section('scripts-footer')  
    <script>

       
        jQuery.noConflict();

        (function($) {
            document.querySelector('.captcha-refresh').addEventListener('click', e => {
                    //Bring the new captcha image
                    $.ajax({
                        method:"get",
                        url: `/captcha-refresh`
                    })
                    .done(function(data){
                        $('.captcha span').html(data);
                    });
            });
            $("#form_login").submit(function(){
                
                $("#form_login").addClass("d-none")
                $("#reloadLogin").html(`<div id="carga_person">
                                        <div class="loader">Loading...</div>
                                    </div>`)
                   
                let usuario = $("#username").val()
                let password = $("#password").val()
 
                let key = $('input[name="_token"]').val() 
                

                let us = CryptoJS.AES.encrypt(JSON.stringify(usuario), key, {format: CryptoJSAesJson}).toString();
                let pass = CryptoJS.AES.encrypt(JSON.stringify(password), key, {format: CryptoJSAesJson}).toString();

                let dataSend = {
                    "us":us,
                    "pass":pass
                } 

                $("#ByCript").val(JSON.stringify(dataSend))

                document.getElementById('username').value = '';
                document.getElementById('password').value = '';

                let detroyPasswordText = document.getElementById("passwordText")
                let detroyUsername= document.getElementById("username")
                let detroyPassword = document.getElementById("password")

                detroyPasswordText.parentNode.removeChild(detroyPasswordText); 
                detroyUsername.parentNode.removeChild(detroyUsername); 
                detroyPassword.parentNode.removeChild(detroyPassword); 

                //$("#username").val(JSON.parse(us).iv)
                //$("#password").val(JSON.parse(pass).iv)
 
            })

            var myInputPass = document.getElementById('password');
            var textoPasswordGeneral = ""
            myInputPass.onpaste = function(e) {
                e.preventDefault(); 
                insertarErrors("Esta acción está prohibida")
            }
            
            myInputPass.oncopy = function(e) {
                e.preventDefault();
                insertarErrors("Esta acción está prohibida")
            }

            function insertarErrors(message)
            {
                let errorDiv = document.getElementById("respuesta-server-login")
                errorDiv.innerHTML = `<div class="form-group">
                                        <div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            ${message}</div>
                                    </div>  `
            }

          
             $("#passwordText").focus(function(){ 
             
                $(this).addClass("border border-primary")

		
                //$("#password").focus().val("").val(valorPass)
		$("#password").focus()
		// document.getElementById("username").value ="focus"
               /* $(this).keydown(function(event ){
                   
                    var key = event.keyCode || event.which;  
                        
                })*/
                 
             })

             $("#password").keyup(function(){
                var value = $(this).val();
		 //document.getElementById("username").value = value
		console.log("el valor es: " , value)
            
		//let valorPass = value
                //$("#passwordText").focus().val("").val(valorPass) 
                document.getElementById("passwordText").value = value
		
                setTimeout(() => {
                    let passSimluador = ""
                    for (let index = 0; index < value.length; index++) {
                        passSimluador += "*" 
                    }
                    document.getElementById("passwordText").value = passSimluador		    
                }, 400);
             })

            $("#password").blur(function(){
                $("#passwordText").removeClass("border border-primary")
            });

           

        })(jQuery);

        
        
        

    </script>
    
@endsection
