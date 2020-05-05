<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Validation\ValidationException;

class TransformInput
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $transformer)
    {
        //return $next($request);
        //dd("kklklkl");
        $transformedInput = [];
        $transformedFile = [];
         
        foreach ($request->request->all() as $input => $value) {
            $transformedInput[$transformer::originalAttribute($input)] = $value;
        }

        $request->replace($transformedInput);
        
        if(isset($request->files)){
            foreach ($request->files->all() as $input => $value) {
              $transformedFile[$transformer::originalAttribute($input)] = $value;
            }
            $request->files->replace($transformedFile);
        }

         
        
        $response = $next($request);
         

        if(isset($response->exception) && $response->exception instanceof ValidationException ){
             
            $data = $response->getData();
          
            
            if($data->error){
              
              $transformedErrors = [];
              //dd($data);
              foreach ($data->mensaje as $field => $error) {
                
                  $transformedField = $transformer::transformedAttribute($field);
                  
                  $transformedErrors[$transformedField] = str_replace($field, $transformedField, $error);
              }
              
              $data->mensaje = $transformedErrors;
              
              $response->setData($data);
             
            }

        }

        return $response;
        
 

    }
}
