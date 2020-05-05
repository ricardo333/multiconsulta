<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmpresaRequest extends FormRequest
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
         
        if(isset($this->route('empresa')->id)){
            $validar_update=$this->route('empresa')->id>0 ?", ". $this->route('empresa')->id:"";
        }
 
        return [
            'nombre'=>'required|max:50|unico_compuesto:empresas,nombre,deleted_at'.$validar_update,
        ];
 
    }

    public function messages()
    {
        return [
            'nombre.max'  => 'Nombre supero los 50 caracteres',
            'nombre.required'  => 'Nombre es requerido',
            'nombre.unico_compuesto'  => 'Nombre ya existente en los datos'
        ];
    }

}
