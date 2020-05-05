<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PerfilRequest extends FormRequest
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

        $validar_update ='';
         
        if(isset($this->route('usuario')->id)){
            $validar_update=$this->route('usuario')->id>0 ?", ". $this->route('usuario')->id:"";
        }
        
        if(request()->isMethod('post')) {
          //  dd(request()->request->all());
            return [  
                'password'=>'required|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/'
            ];

        } 

        
    }

    public function messages()
    {
        return [
            'dni.max'  => 'DNI superó los 8 caracteres',
            'dni.required'  => 'DNI es requerido',
            'dni.unico_compuesto'  => 'DNI ya existente en los datos',
            'telefono.required'  => 'telefono es requerido',
            'telefono.max'  => 'telefono debe tener 9 digitos',
            'telefono.min'  => 'telefono debe tener 9 digitos',
            'email.unico_compuesto'  => 'CORREO ya existe en los datos'
        ];
    }

}
