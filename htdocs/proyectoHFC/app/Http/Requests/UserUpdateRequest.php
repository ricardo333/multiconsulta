<?php

namespace App\Http\Requests;
use App\Administrador\User;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
       // dd(request()->all());
        $validar_update ='';
         
        if(isset($this->route('usuario')->id)){
            $validar_update=$this->route('usuario')->id>0 ?", ". $this->route('usuario')->id:"";
        }
       
        //if(request()->isMethod('post')) {
            
            return [ 
                'nombre' => 'required|max:100|',
                'apellidos' => 'required|max:100',
                'dni' => 'required|max:8|unico_compuesto:users,dni,deleted_at'.$validar_update,
                'telefono' => 'required|max:9|min:9',
                'email' => 'required|email|unico_compuesto:users,email,deleted_at'.$validar_update,
                'estado' => 'required|in:'.User::ESTADO_ACTIVO.','.User::ESTADO_INACTIVO,
                'password'=>'nullable|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/',
            ];
    
        //}
          
    }

    public function messages()
    {
        return [
            'dni.unico_compuesto'  => 'Ya existe otro usuario con los mismos datos',
            'email.unico_compuesto'  => 'Ya existe otro usuario con los mismos datos'
        ];
    }
}
