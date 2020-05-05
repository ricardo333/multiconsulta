<?php

namespace App\Functions;

use DB; 
use App\Administrador\User;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserFunctions
{ 

    function getUltimosIntentosPorTiempo($usuario,$acceso_exitoso,$numero_max,$iempo)
    {
        /*$historial = DB::select(
                    "select * from 
                    zz_auditoria.log_acceso 
                    WHERE usuario = ? AND acceso_exitoso=? AND 
                    fecha >= DATE_SUB(NOW(), INTERVAL ? $iempo) ORDER BY fecha DESC", [$usuario,$acceso_exitoso,$numero_max]); */
        $historial = DB::select(
                    "select * from 
                    zz_new_system.ultimos_accesos 
                    WHERE usuario = ? AND acceso_exitoso=? AND 
                    fecha >= DATE_SUB(NOW(), INTERVAL ? $iempo) ORDER BY fecha DESC", [$usuario,$acceso_exitoso,$numero_max]); 

        return $historial;
    }
 

    public function registraIntentosUserLogin($usuario,$acceso_si_no)
    { 
       /* DB::insert("insert into 
                        zz_auditoria.log_acceso 
                        VALUES (null,?,?,NOW())", [$usuario,$acceso_si_no]); */
        DB::insert("insert into 
                        zz_new_system.ultimos_accesos 
                            VALUES (null,?,?,NOW())", [$usuario,$acceso_si_no]); 
    }

    public function limpiaIntentosUserLogin($usuario)
    {   
       /* DB::delete("delete from 
                            zz_auditoria.log_acceso 
                            WHERE usuario = ? and acceso_exitoso='NO'", [$usuario]); */
        DB::delete("delete from 
                            zz_new_system.ultimos_accesos 
                            WHERE usuario = ? and acceso_exitoso='NO'", [$usuario]); 
    }

    public function cantidadHistorialPasswordByIdUser($username){
        
        /*$cantidad_log_pass = DB::select(
                    "select COUNT(*) AS cantidad FROM 
                    zz_auditoria.log_password 
                    WHERE usuario=?", [$username]); */
        $cantidad_log_pass = DB::select(
                    "select COUNT(*) AS cantidad FROM 
                    zz_new_system.ultimos_passwords
                    WHERE usuario=?", [$username]); 
         
        return $cantidad_log_pass[0]->cantidad;
    }

    private function comparacionPasswordHistory($new_password,$username){
         
        /*$result_cantidad_historial_pass = DB::select(
                                "select * FROM 
                                zz_auditoria.log_password  
                                WHERE usuario=? ORDER BY fecha DESC", [$username]); */
        $result_cantidad_historial_pass = DB::select(
                                "select * FROM 
                                zz_new_system.ultimos_passwords  
                                WHERE usuario=? ORDER BY fecha DESC", [$username]); 

        
        $igualdad = false;
        
        foreach ($result_cantidad_historial_pass as $historial) {
               
             if($historial->pass_old == sha1($new_password)){ 
                $igualdad = true;
                return $igualdad;
              } 
        }
          
        return $igualdad; 
    }

    public function esValidoNuevoPassword(User $usuario,$newPassword)
    {
        if( trim($newPassword) == $usuario->nombre || 
            trim($newPassword) == $usuario->apellidos || 
            trim($newPassword) == $usuario->telefono || 
            trim($newPassword) == $usuario->username || 
            trim($newPassword) == $usuario->dni ){
                throw new HttpException(422,"La contraseña no puede coincidir con sus datos personales.");
        } 
        //dd($newPassword."-".strtolower($usuario->nombre));
        $cadena_password = strtolower($newPassword);
        $contiene_nombre = strpos($cadena_password, strtolower($usuario->nombre)); 
       
        $array_apellidos=explode(" ",strtolower($usuario->apellidos));
         
        if (count($array_apellidos) > 0) {
            for ($i=0; $i < count($array_apellidos); $i++) { 
                if ($array_apellidos[$i] != "") { // Si esta vacio por un espacio
                    $contiene_apellidos = strpos($cadena_password,$array_apellidos[$i]);  
                    if ($contiene_apellidos !== false) {
                        throw new HttpException(422,"No es seguro que su contraseña contenga datos personales");
                    } 
                }
               
            }
        }

        $contiene_telefono = strpos($cadena_password, $usuario->telefono); 
        $contiene_username= strpos($cadena_password, strtolower($usuario->username)); 
        $contiene_username_invertido = strpos($cadena_password, strtolower(strrev($usuario->username))); 
        $contiene_dni= strpos($cadena_password, $usuario->dni); 

        if ($contiene_nombre !== false) {
            throw new HttpException(422,"No es seguro que su contraseña contenga datos personales");
        }
        
        if ($contiene_telefono !== false) {
            throw new HttpException(422,"No es seguro que su contraseña contenga datos personales");
        }
        if ($contiene_username !== false || $contiene_username_invertido !== false) {
            throw new HttpException(422,"No es seguro que su contraseña contenga datos personales");
        }
        if ($contiene_dni !== false) {
            throw new HttpException(422,"No es seguro que su contraseña contenga datos personales");
        }

        $cantidad_historial = $this->cantidadHistorialPasswordByIdUser($usuario->username);
        
        if($cantidad_historial > 0){
            $verificar_igualdad_historial = $this->comparacionPasswordHistory($newPassword,$usuario->username);
            if($verificar_igualdad_historial){
                throw new HttpException(422,"Intente no utilizar una contraseña antigua.");
            } 
        }
 

    }

    public function CambioDePasswordBloqueadoPorTiempo($usuario,$tiempo)
    {
       
        $listaUltimoCambio = DB::select("select COUNT(*) AS cantidad 
                                         FROM zz_new_system.ultimos_passwords
                                         where usuario=? AND  fecha >= DATE_SUB(NOW(), 
                                         INTERVAL ? MINUTE )",[$usuario, $tiempo]);
        
       return $listaUltimoCambio[0]->cantidad > 0;
    
    }

    public function eliminarUltimoPasswordHistorial($username, $limit)
    { 
  
        /* DB::delete("delete FROM 
                    zz_auditoria.log_password 
                    WHERE usuario=? 
                    ORDER BY fecha ASC LIMIT ?", [$username,$limit]);*/
         DB::delete("delete FROM 
                    zz_new_system.ultimos_passwords
                    WHERE usuario=? 
                    ORDER BY fecha ASC LIMIT ?", [$username,$limit]);
    }

    public function registrarPasswordOld($newPassword,$username)
    {
        /*DB::insert(
            "insert into 
            zz_auditoria.log_password 
            VALUES (null,?,?,NOW())", [$username,sha1($newPassword)]);*/
        DB::insert(
            "insert into 
            zz_new_system.ultimos_passwords 
            VALUES (null,?,?,NOW())", [$username,sha1($newPassword)]);
    }

    public function get_ip_address() {
        $ip_keys = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR');
        foreach ($ip_keys as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    // trim for safety measures
                    $ip = trim($ip);
                    // attempt to validate IP
                    if ($this->validate_ip($ip)) {
                        return $ip;
                    }
                }
            }
        }
    return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : false;
    }
    
    protected function validate_ip($ip)
    {
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
            return false;
        }
        return true;
    }

    /*public function logActionUpdateByAdmin($username,$oldEmpresa,$oldRol,$newEmpresa,$newRol)
    {
        $usuarioAuth = Auth::user();
       
        $estacionUsuario = $this->get_ip_address();

        DB::insert(
            "insert into 
            zz_auditoria.log_actividad 
            VALUES (null,?,?,?,?,?,?,?,NOW(),?)", [
                $usuarioAuth->username,"update",
                $username,$oldEmpresa,$oldRol,
                $newEmpresa,$newRol,$estacionUsuario
                ]);
    } No Valido */

   /* public function logActionStoreByAdmin($username,$empresa,$rol)
    {
        $usuarioAuth = Auth::user();
       
        $estacionUsuario = $this->get_ip_address();

        DB::insert(
            "insert into 
            zz_auditoria.log_actividad 
            VALUES (null,?,?,?,?,?,'','',NOW(),?)", [
                $usuarioAuth->username,"store",
                $username,$empresa,$rol,$estacionUsuario
                ]);
    }*/

    public function ultimoAccesoUser($username,$limit=" ")
    {
        //Debe quedar con el log porque la otra tabla se limpia con el esado de usuario
        $ultimoAcceso = DB::select("select * from 
                    zz_auditoria.log_acceso
                    where 
                    usuario=? AND acceso_exitoso='SI' ORDER BY fecha DESC LIMIT 1 $limit",
                [$username]);
        /*$ultimoAcceso = DB::select("select * from 
                            zz_new_system.ultimos_accesos
                    where 
                    usuario=? AND acceso_exitoso='SI' ORDER BY fecha DESC LIMIT 1 $limit",
                [$username]);*/

        return $ultimoAcceso;
    }
    //Login de errores desde el penultimo acceso hasta e actual login
    public function loginFailsFromPenultimateSuccessToToday($username,$fechaUltimoLoginExitoso)
    {
         $ultimosErrores = DB::select("SELECT * FROM 
                                        zz_auditoria.log_acceso
                                        WHERE 
                                        usuario=? AND 
                                        acceso_exitoso='NO' AND 
                                        fecha > ?",
                                        [$username,$fechaUltimoLoginExitoso]);
        return $ultimosErrores;
    }
 
    public function getCantidadDiasUltimoCambioPassword($username)
    {
        /*$dias_ultimo_cambio = DB::select("select DATEDIFF(NOW(),fecha) AS diascambio 
                    FROM zz_auditoria.log_password 
                    WHERE  usuario=? ORDER BY fecha DESC LIMIT 1", [$username]);*/
        $dias_ultimo_cambio = DB::select("select DATEDIFF(NOW(),fecha) AS diascambio 
                    FROM zz_new_system.ultimos_passwords 
                    WHERE  usuario=? ORDER BY fecha DESC LIMIT 1", [$username]);
    
        return $dias_ultimo_cambio;
    }

    public function limpiarLogAccesosPorUsuario($username){
       /* DB::delete("delete FROM zz_auditoria.log_acceso 
                                            WHERE usuario = ? ", [$username]); */
        DB::delete("delete FROM zz_new_system.ultimos_accesos 
                                            WHERE usuario = ? ", [$username]); 
    }

    public function limpiarLogPasswordPorUsuario($username){
        /*DB::delete("delete FROM zz_auditoria.log_password 
                                            WHERE usuario = ? ", [$username]);*/ 
        DB::delete("delete FROM  zz_new_system.ultimos_passwords 
                                            WHERE usuario = ? ", [$username]); 
    }

    public function inactivarUsuario(User $usuario)
    {  
        $usuario->estado = User::ESTADO_INACTIVO;
        $usuario->save();
    }

    
    public function getOS($user_agent) { 
       
        $os_array =  array(
                        '/windows nt 10/i'      =>  'Windows 10',
                        '/windows nt 6.3/i'     =>  'Windows 8.1',
                        '/windows nt 6.2/i'     =>  'Windows 8',
                        '/windows nt 6.1/i'     =>  'Windows 7',
                        '/windows nt 6.0/i'     =>  'Windows Vista',
                        '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
                        '/windows nt 5.1/i'     =>  'Windows XP',
                        '/windows xp/i'         =>  'Windows XP',
                        '/windows nt 5.0/i'     =>  'Windows 2000',
                        '/windows me/i'         =>  'Windows ME',
                        '/win98/i'              =>  'Windows 98',
                        '/win95/i'              =>  'Windows 95',
                        '/win16/i'              =>  'Windows 3.11',
                        '/macintosh|mac os x/i' =>  'Mac OS X',
                        '/mac_powerpc/i'        =>  'Mac OS 9',
                        '/linux/i'              =>  'Linux',
                        '/ubuntu/i'             =>  'Ubuntu',
                        '/iphone/i'             =>  'iPhone',
                        '/ipod/i'               =>  'iPod',
                        '/ipad/i'               =>  'iPad',
                        '/android/i'            =>  'Android',
                        '/blackberry/i'         =>  'BlackBerry',
                        '/webos/i'              =>  'Mobile'
                      );
        //
        $os_platform = "SO sin identificar.";
        foreach ($os_array as $regex => $value) { 
            if (preg_match($regex, $user_agent)) {
                $os_platform = $value;
            }
        }
        return $os_platform;
    }

    function getBrowser($user_agent) { 
	//dd($user_agent);
        $browser_array = array(
                            '/msie/i'       =>  'Internet Explorer',
                            '/firefox/i'    =>  'Firefox',
                            '/safari/i'     =>  'Safari',
                            '/chrome/i'     =>  'Chrome',
                            '/edge/i'       =>  'Edge',
                            '/opera/i'      =>  'Opera',
                            '/netscape/i'   =>  'Netscape',
                            '/maxthon/i'    =>  'Maxthon',
                            '/konqueror/i'  =>  'Konqueror'
                           // '/mobile/i'     =>  'Handheld Browser'
                          );
        $browser = "Browser no identificado";
        foreach ($browser_array as $regex => $value) { 
            if (preg_match($regex, $user_agent)) {
                $browser = $value;
            }
        }
        return $browser;
    }
    
 
}
