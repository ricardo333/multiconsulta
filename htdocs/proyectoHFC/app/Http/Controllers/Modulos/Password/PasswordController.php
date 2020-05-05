<?php

namespace App\Http\Controllers\Modulos\Password;

use App\Administrador\User;
use Illuminate\Http\Request;
use App\Functions\LogsFunctions;
use App\Functions\UserFunctions;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;

class PasswordController extends Controller
{
    public function primerCambio(){

       $userAuth =  Auth::user();
        $usernameAuth = $userAuth->username;
 
        $userFunctions = new UserFunctions;
        $cantidadPasswordLog = $userFunctions->cantidadHistorialPasswordByIdUser($usernameAuth);
        
        if((int)$cantidadPasswordLog > 0){
          return redirect()->route('administrador');
        }else{
          return view('administrador.password.primerCambio');
        } 

         
       
    }

    public function update(Request $request, User $usuario)
    {
        $rules = [
            'password'=>'required|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/'
        ];

        
    
        $this->validate($request,$rules);

        $userFunctions = new UserFunctions;
        $logsFunctions = new LogsFunctions;

        try {
          $userFunctions->esValidoNuevoPassword($usuario,$request->password);//valida password
        } catch (\Exception $exception) {
          //dd($exception->getMessage()); 
          return back()
          ->withErrors(['changePassword'=>$exception->getMessage()])
          ->withInput(request(['password']));
        }

       ;
 
        try {
            DB::beginTransaction();
              #begin Transaction Update User
                 
                $usuario->password = bcrypt($request->password);
                
                $usuario->save();

                $userFunctions->registrarPasswordOld($request->password,$usuario->username);
                $logsFunctions->registroLog($logsFunctions::LOG_PASSWORD,array(
                    "usuario"=>$usuario->username,
                    "rol"=>$usuario->role->nombre,
                    "newPassword"=>$request->password
                ));
 
              #End Begin Transaction update User
            DB::commit();
    
        }catch(QueryException $ex){ 
           // dd($ex->getMessage()); 
            DB::rollback();
            return back()
                    ->withErrors(['changePassword'=>"Hubo un problema en la actualización, intente nuevamente!."])
                    ->withInput(request(['password']));
        }catch(\Exception $e){
           // dd($e->getMessage()); 
            DB::rollback();
            return back()
                    ->withErrors(['changePassword'=>"Hubo un error inesperado!, intente nuevamente!."])
                    ->withInput(request(['password'])); 
        }

        return redirect()->route('administrador');
    }
}
