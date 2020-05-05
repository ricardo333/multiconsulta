<?php

namespace App\Http\Controllers\Modulos\Auth;

use Carbon\Carbon;
use UserFunctions;
use App\Library\CryptoAes;
use App\Administrador\User;
use Illuminate\Http\Request;
use App\Administrador\Parametro;
use App\Functions\LogsFunctions;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
 
    public function index(Request $request){
	
        $userFunctions = new UserFunctions;
$browser = '';
	
        #obteniendo el 'user_agent'  CLIENT
            if ( isset( $_SERVER ) ) {
                $user_agent = $_SERVER['HTTP_USER_AGENT'];
            } else {
                global $HTTP_SERVER_VARS;
                if ( isset( $HTTP_SERVER_VARS ) ) {
                    $user_agent = $HTTP_SERVER_VARS['HTTP_USER_AGENT'];
                } else {
                    global $HTTP_USER_AGENT;
                    $user_agent = $HTTP_USER_AGENT;
                }
            }
            $browser = $userFunctions->getBrowser($user_agent);
        #END

		
        if ($browser != "Chrome" && $browser != "Firefox"){
         
            return view('errors.navegadores',["browser"=>$browser]);
        }
             

        return view('auth.login',["browser"=>$browser]);
    }

    public function login(Request $request){
 
        $rule_captcha = ['captcha' => 'required|captcha'];
        $validator_captcha = validator()->make(request()->all(), $rule_captcha);

        if ($validator_captcha->fails()) {
            if($request['captcha']==''){
                return back()
                ->withErrors(['auth'=>"Ingresar captcha !."])
                ->withInput(request(['captcha']));
            }else{
                return back()
                ->withErrors(['auth'=>"Captcha invalido !."])
                ->withInput(request(['captcha']));
            }
        }

        $rules = [
            'ByCript' => 'required'
        ];
         
        $credentials = $this->validate($request,$rules);
         // dd($request->all());
        $cryptAesLib = new CryptoAes;
        $dataBycripDeco = json_decode($request->ByCript);
        //dd($dataBycripDeco);
        $passDataRequ = $cryptAesLib->cryptoJsAesDecrypt($request->_token, $dataBycripDeco->pass);
        $userDataRequ = $cryptAesLib->cryptoJsAesDecrypt($request->_token, $dataBycripDeco->us);
        
        $request->username = $userDataRequ;
        $request->password = $passDataRequ;
        //dd("Paso validación ahora los request son: ",$request->username);
       // dd($request->all());
        $rules = [
            'username' => 'required',
            'password' => 'required'
        ];
         //Nueva validacion de usuario y password descencriptado
        Validator::make( array(
                                "username"=>$request->username,
                                "password"=>$request->password
                            ), [
                                "username" => "required",
                                "password" => "required"
                            ])->validate(); 
                 
        $credentials = array(
            "username"=>$request->username,
            "password"=>$request->password
        );

         
         
        $userFunctions = new UserFunctions;
        $logsFunctions = new LogsFunctions;
 
        #CANTIDAD DE INTENTOS VALIDACION
            //$cantidad_max_intentos = 5;
            //$intentos_max_minutos = 30;
            //$cantidad_max_intentos = Parametro::find(3)->period;
            $cantidad_max_intentos = Parametro::getIntentosMaximosLogin();
            $intentos_max_minutos = Parametro::getMinutosReactivacionLogin();
           
            //$queryUltimosMinutos = $intentos_max_minutos." MINUTE";
            $ultimosIntentos = $userFunctions->getUltimosIntentosPorTiempo($request->username,"NO",$intentos_max_minutos,"MINUTE");
            
            $cantidad_intentos = count($ultimosIntentos);
           
            if($cantidad_intentos >= $cantidad_max_intentos){
 
                //traer el tiempo que queda restante
                $quedan_minutos_reintentar = $intentos_max_minutos;

                if ($cantidad_intentos > 0) {
                    
                   $fechaBDIntentosUltimo = Carbon::create($ultimosIntentos[0]->fecha);
                   $ultimo_acceso_fallido_minutos_pasados = $fechaBDIntentosUltimo->diffInMinutes(Carbon::now());
                   $quedan_minutos_reintentar = (int) $intentos_max_minutos - $ultimo_acceso_fallido_minutos_pasados;
                }
                   
                return back()
                ->withErrors(['auth'=>"Superaste el numero de intentos para acceder al sistema, intenta dentro de $quedan_minutos_reintentar minutos nuevamente!."])
                ->withInput(request(['username']));
            }
        #FIN CANTIDAD INTENTOS

        config(['session.lifetime' => 5]);
       
        #VALIDANDO CREDENCIALES DE LOGIN
            if(Auth::attempt($credentials)){ //valida credenciales usuario y password
                 
                $userAuth = Auth::user();
                $usuarioUpdate = User::find($userAuth->id);

                //Valido si usuario está activo
                if ($userAuth->estado == User::ESTADO_INACTIVO) { //usuario inactivo
                    Auth::logout();
                    $userFunctions->registraIntentosUserLogin($request->username,"NO");//registra intento fallido
                    $logsFunctions->registroLog($logsFunctions::LOG_ACCESO,array( "usuario"=>$request->username,"acceso_exitoso"=>"NO"));
                    
                    return back()
                    ->withErrors(['auth'=>"Credenciales incorrectas, verifique con el administrador!."])
                    ->withInput(request(['username']));
                }
                //Valido si el estado del usuario está activo
                 
                if ($userAuth->role->estado == 0) {
                    Auth::logout();
                    $userFunctions->registraIntentosUserLogin($request->username,"NO");//registra intento fallido
                    $logsFunctions->registroLog($logsFunctions::LOG_ACCESO,array( "usuario"=>$request->username,"acceso_exitoso"=>"NO"));
                    return back()
                    ->withErrors(['auth'=>"Credenciales incorrectas, verifique con el administrador!."])
                    ->withInput(request(['username']));
                } 
                
                  
                //Valido que no haya cambiado su password en el tiempo determinado caso contrario bloquear
                $dias_ultimo_cambio_pass = $userFunctions->getCantidadDiasUltimoCambioPassword($request->username);
                
                if (count($dias_ultimo_cambio_pass) > 0) { // si tiene un último cambio 

                    if ((int)$dias_ultimo_cambio_pass[0]->diascambio > Parametro::getDiasCambioPassword()) { //supero los numeros de dias en cambio de password
                        //se desactiva su cuenta 
                        $userFunctions->inactivarUsuario($usuarioUpdate);
                         
                        Auth::logout();
    
                        return back()
                        ->withErrors(['auth'=>$request->username." no actualizaste tu contraseña hace mas de ".Parametro::getDiasCambioPassword()." días. Tu cuenta está desactivada."])
                        ->withInput(request(['username'])); 
                    }
                }
                

                //Valido si su suenta estaba inactiva mucho tiempo segun la configuracion del sistema 
                $ultimoLoginCorrecto = $userFunctions->ultimoAccesoUser($request->username);
                
                if (count($ultimoLoginCorrecto) > 0) { // ya tiene inicios de sessiones antiguas
                     //validar cuanto tiempo de inactividad tiene
                    $fecha_ultima_session = Carbon::create($ultimoLoginCorrecto[0]->fecha);
                    
                    $diferencia_dias_ultimo_login = $fecha_ultima_session->diffInDays(Carbon::now());
                    
                    $maximos_dias_inactividad_sesion = Parametro::getDiasInactividadCuenta();

                    if ($diferencia_dias_ultimo_login > $maximos_dias_inactividad_sesion) {
                        $userFunctions->inactivarUsuario($usuarioUpdate);
                        Auth::logout();

                        return back()
                        ->withErrors(['auth'=>"No inició sessión en más de $maximos_dias_inactividad_sesion días. Su cuenta está desactivada."])
                        ->withInput(request(['username'])); 
                    }
                      
                }
                //si su estado está activo, cambio password con tiempo y su ultima sesion no paso los dias de inactividad
                $userFunctions->limpiaIntentosUserLogin($request->username); //limpia los accessos errados
                $userFunctions->registraIntentosUserLogin($request->username,"SI");//registra acceso correcto
                //Registro de LOG con login CORRECTO
                $logsFunctions->registroLog($logsFunctions::LOG_ACCESO,array( "usuario"=>$request->username,"acceso_exitoso"=>"SI"));
 
                //Luego de las validaciones y registros se procede con registro de una sesion unica
                $request->session()->regenerate(); //se genera una por el usuario auth

                $sesion_previa = $userAuth->session_id;
                
                if ($sesion_previa) { 
                    Session::getHandler()->destroy($sesion_previa);//destruye la session de todo los regitros de laravel
                }

                
 
                $userAuth->session_id = Session::getId(); //asignamos la sesion unica al usuario auth
                $userAuth->save();//lo guarda en la BD
                 
                    
                return redirect()->route('administrador');
                
    
            }else{
                $userFunctions->registraIntentosUserLogin($request->username,"NO");//registra intento 
                //Registro de LOG con login fallido
                $logsFunctions->registroLog($logsFunctions::LOG_ACCESO,array( "usuario"=>$request->username,"acceso_exitoso"=>"NO"));
    
                $intentos_maximos_disponibles = $cantidad_max_intentos - ($cantidad_intentos + 1);
                $texto_plural_singular = $intentos_maximos_disponibles > 1 ? "intentos" : "intento";
                
                if ($intentos_maximos_disponibles == 0) {
                    return back()
                    ->withErrors(['auth'=>"Superaste el numero de intentos para acceder al sistema, intenta dentro de $intentos_max_minutos minutos nuevamente!."])
                    ->withInput(request(['username']));
                }
                    return back()
                    ->withErrors(['auth'=>"Las credenciales son incorrectas, intente nuevamente, recuerde solo tiene $intentos_maximos_disponibles $texto_plural_singular !."])
                    ->withInput(request(['username']));
                 
            }
        #FIN VALIDACION CREDENCIALES 
  
        /*return back()
                ->withErrors(['auth'=>trans('auth.failed'),"username"=>"problemas con el usuario"])
                ->withInput(request(['username','auth']));*/
        
    }

    public function logout()
    {
        $userAuth = Auth::user();

        $sesion_previa = $userAuth->session_id;
        Session::getHandler()->destroy($sesion_previa);//destruye la session de todo los regitros de laravel
          
        $userAuth->session_id = null; //asignamos la sesion unica al usuario auth
        $userAuth->save();//lo guarda en la BD

        Auth::logout();
        return redirect('/');
        //cierra sesion y borra el campo sessionId del usuario
    }

  
}
