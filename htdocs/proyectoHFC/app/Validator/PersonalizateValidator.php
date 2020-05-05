<?php
namespace App\Validator;

use Illuminate\Validation\Validator;
use DB;
use Request;

class PersonalizateValidator extends Validator
{
    // Laravel usa esta convención para buscar reglas de 
    //validación, esta función se activará
    // para  unico_compuesto

    public function validateUnicoCompuesto($attribute, $value, $parameters, $validator)
    {
        // al extender puedes usar métodos protegidos como este
        // $this->requireParameterCount(2, $parameters, 'unico_compuesto' );
            
            // same logic from my last response
            // dd($parameters); ->son todos los attributos enviados a la function para evaluar despues del : dos puntos
            //dd($attribute ); -> es el nombre del attributo dobre el cual esta la function de validación
            //dd($value); -> es el valor del campo del attributo dobre el cual esta la function de validación
           
            // data being validated
            $data = $validator->getData(); //datos a validar
            //dd($data); 
            

            // remove whitespaces
            $parameters = array_map('trim', $parameters );//parametros quitando espacios en blanco
           
            //obtenemos el primer parametro enviado asumiendo que es el nombre de la tabla
            $table = array_shift($parameters);
           
             // remove last parameter to check for except condition
            $lastParameter = array_pop($parameters);//ultimo parametro osea el id de la tabla
         

            
            // start building the query
            //DB::connection('mysql')->table($table)->select(DB::connection('mysql')->raw(1));
            $query = DB::connection('mysql')->table($table)->select(DB::connection('mysql')->raw(1));
            //$query = DB::table($table)->select(DB::raw(1));
            
            //dd($query->first());
            //1:1

             // add the field being validated as a condition
            // IMPORTANT: skipping it for improved consistency, see
            // note in the function's comment
            // $query->where( $attribute, $value );
            
            // iterates over the parameters and add as where clauses
            while ($field = array_shift( $parameters )) {
              
                $query->where( $field, array_get($data, $field) );
                 
            }
            

           // check $lastParameter for except condition. Uses a regular
                // expression to check if $lastParameter contains only numbers
                // or an equal sign
                 
                if (preg_match( '/^(?:\d+|.+?=.+)$/', $lastParameter )) {
                
                    if (preg_match( '/^\d+$/', $lastParameter )) {
                        //$rpta = "es numero y se usca id";
                        // only numbers, assume primary key is called 'id' rewrite $lastParameter
                        $lastParameter = sprintf( '%s.id = %s', $table, $lastParameter );
                    }

                     // negate condition
                     $exceptField = sprintf( '(NOT %s)', $lastParameter );
                      

                     $query->whereRaw($exceptField);

                } else {
                      // is not except condition, add as a normal where
                      $query->where( $lastParameter, array_get( $data, $lastParameter ) );
                    
                }
 
                $result = $query->first();
                 
                  

                return empty( $result ); // true if no result was found
    }


    // you can add other validations to this class, the next one will validate a another_rule validation
    public function validateIdBd( $attribute, $value, $parameters ,$validator )
    {
        //obtenemos el primer parametro enviado asumiendo que es el nombre de la tabla
        $table = array_shift($parameters);

        $id_value = $value; //valor enviado 

        $query = DB::connection('mysql')->table($table)->where('id',$value)->get();
        //$query = DB::table($table)->where('id',$value)->get();
        
        $result = $query->first();
 
        return !empty($result); 
        
    }

    public function validateArraysIdBd( $attribute, $value, $parameters ,$validator )
    {
        
        //obtenemos el primer parametro enviado asumiendo que es el nombre de la tabla
        //automaticamente por ser array recorre cada valor en la tabla, no es necesario
          //->el foreach
        $table = array_shift($parameters);
        $cantidad_valores = count($value); 
        $items = DB::connection('mysql')->table($table)->whereIn('id',$value)->get();
       // $items = DB::table($table)->whereIn('id',$value)->get();
         
        return $cantidad_valores == count($items);
      
    }

    public function validateCampoBdNull( $attribute, $value, $parameters ,$validator ){
      
      $table = array_shift($parameters); //primer parametro 
      $lastParameter = array_pop($parameters); //ultimo parametro
     
      $items = DB::connection('mysql')->table($table)->where('id',$value)->where($lastParameter,null)->get();

     /* $items = DB::table($table)->where('id',$value)
                                ->where($lastParameter,null)->get();*/
      
      $result = $items->first();

      return !empty($result); 
    }
 
}
