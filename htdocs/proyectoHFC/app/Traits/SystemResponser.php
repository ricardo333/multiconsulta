<?php 

namespace App\Traits;

use DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model; 
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;
 
Trait SystemResponser {
 
    private function successResponse($data,$permisos = null,$code){
        return response()->json(["error"=>false,"response"=>$data,"permisos"=>$permisos],$code);
    }

    private function errorResponse($message,$code){
        return response()->json(["error"=>true,"mensaje"=>$message,"code"=>$code],$code);
    }

    private function jsonDataPersonalizate($data,$code){
        return response()->json(["error"=>false,"response"=>$data,"code"=>$code],$code);
    }

    private function filterData(Collection $collection, $transformer)
    {
      //dd(request()->all());
       foreach ( request()->all() as $query => $value) {
        
          $attribute = $transformer::originalAttribute($query);
        // echo "el atributo original es:".$attribute." y el valor es: ".$value;
         // dd($collection->where($attribute,$value));
          if(isset($attribute,$value)) {
            ////$collection = $collection->where($attribute,$value);
            $collection = $collection->reject(function($element) use ($attribute, $value) {
                return stripos($element->$attribute, $value) === false;
            });
          }
       } 
       
       return $collection;
    }

    private function sortData(Collection $collection, $transformer)
    {
      if(request()->filled('sort')){
        $attribute = $transformer::originalAttribute(request()->sort);

        $collection = $collection->sortBy->{$attribute};
        
      }
      return $collection;
    }

    private function paginate(Collection $collection)
    {
        
      $rules = [
        'paginate' => 'integer|min:2|max:100'
      ];

      Validator::validate(request()->all(), $rules);

      $page = LengthAwarePaginator::resolveCurrentPage();
       
      $perPage = 15;
      if (request()->filled('paginate')) {
        $perPage = (int) request()->paginate;
      }
       
      $results = $collection->slice(($page - 1) * $perPage, $perPage)->values();

      $paginated = new LengthAwarePaginator($results, $collection->count(), $perPage, $page, [
        'path' => LengthAwarePaginator::resolveCurrentPath(),
      ]);

      $paginated->appends(request()->all());

      return $paginated;
    }

    private function transformData($data, $transformer)
    {
        $transformation = fractal($data, new $transformer);

        return $transformation->toArray();
    }


    protected function showContJsonAll(Collection $collection, 
                                      $filter = false, 
                                      $sort = false, 
                                      $paginacion = false, 
                                      $permisos = false, 
                                      $code = 200){
       
        if($collection->isEmpty())
        {
            return $this->successResponse(["data"=>$collection],null, $code);
        }
       
        $transformer = $collection->first()->transformer; //utilizamos el transformer de cada modelo
        $permisosModel = $permisos ? $collection->first()::permisosGenerales(Auth::user()) : null;
         
        //filtrar datos
        if ($filter) $collection = $this->filterData($collection, $transformer);
        
        //ordenando datos
        if ($sort) $collection = $this->sortData($collection, $transformer);
        
        //paginacion de datos
        if ($paginacion) $collection = $this->paginate($collection);
       
        $collection = $this->transformData($collection, $transformer);

        
        
        return $this->successResponse($collection, $permisosModel, $code);
    }

    protected function showModJsonOne(Model $instance, $permiso = false, $code = 200){

        $transformer = $instance->transformer;
        $permisosModel = $permiso ? $instance::permisosGenerales(Auth::user()) : null;//permisos modelo
        $instance = $this->transformData($instance, $transformer);

        return $this->successResponse($instance,$permisosModel,$code);
    }

    protected function showModJsonAll(Model $instance,
                                      $filter = false, $filterController = false,
                                      $sort = false, $sortController = false,
                                      $paginacionController = false,
                                      $permisos = false, 
                                      $code = 200){

      if($instance->get()->isEmpty()){ 
        //throw new HttpException(422,"0 resultados");
        return $this->successResponse(["data"=>null],null,$code);
      } 

      //Filtrar Modelo
      if($filter) $instance = $instance->filterData();
      //Ordernar Modelo
      if($sort) $instance = $instance->sortData();
       
     //
      $collection = $instance->get();
       
      return $this->showContJsonAll($collection,$filterController,$sortController,$paginacionController,$permisos,$code);

    }

    protected function errorMessage($message,$code){
        return $this->errorResponse($message,$code);
    }

    protected function resultData(array $data, $code = 200){
        return $this->jsonDataPersonalizate($data,$code);
    }
 
    protected function mensajeSuccess($message){
      return response()->json(["error"=>false,"mensaje"=>$message]);
    }

    protected function errorDataTable($message,$code){
      return response()->json(array(
                            "error"=>true,
                            "draw"=>0,
                            "recordsTotal"=>0,
                            "recordsFiltered"=>0,
                            "data"=>0,
                            "mensaje"=>$message
                        ),$code);
    }
   
}