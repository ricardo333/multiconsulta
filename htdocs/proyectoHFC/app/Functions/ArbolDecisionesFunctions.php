<?php

namespace App\Functions;

use DB; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ArbolDecisionesFunctions
{ 

    function getListTable($tabla)
    { 
        //$consulta = DB::connection('arbol_decisiones')->select("select * from arboldecisiones.$tabla");
        $consulta = DB::select("select * from arboldecisiones.$tabla");
          
        return array(
            "listado"=>$consulta,
            "cantidad"=>count($consulta)
        );

    }

    function getTablaDecisionesGeneral()
    { 
        //$consulta= DB::connection('arbol_decisiones')->select("select * from arboldecisiones.pasosArbol"); 
        $consulta= DB::select("select * from arboldecisiones.pasosArbol"); 
         
        return array(
            "listado"=>$consulta,
            "cantidad"=>count($consulta)
        ); 
    }

    function executeQueryGeneral($constructorQuery){
       
        //$consulta= DB::connection('arbol_decisiones')->select($constructorQuery); 
        $consulta= DB::select($constructorQuery); 
       
        return array(
            "cantidad"=>count($consulta),
            "data"=>$consulta
        );
    }

    function getTablaDecisionesGeneralPorPasoAnterior($tabla,$columna,$valorSelect){
       
        $it3="select * from arboldecisiones.$tabla where $columna=$valorSelect group by id";  
       // $consulta= DB::connection('arbol_decisiones')->select($it3); 
        $consulta= DB::select($it3); 
 
        return $consulta;
    }

    function getTablaPorNombre($nombreTabla)
    {

        
        $resultadoQuery = DB::select("select * from arboldecisiones.pasosArbol where nombre='$nombreTabla'");

        if(count($resultadoQuery) == 0){
            throw new HttpException(409,"No se encontrarón datos del paso que desea registrar, intente nuevamente actualizado la web.");
        }
        return $resultadoQuery[0];

        /*$result=mysqli_query($link,$query) or die(json_encode(["error"=>true,"mensaje"=>"Se generó un error en consulta de la BD, intente nuevamente."]));
        $result2=mysqli_fetch_array($result);
        $identificador=$result2["id"];
        $tabla=$result2["nombre"];
        $detalle=$result2["detalle"];
        $pasoAnterior=$result2["pasoAnterior"];
        $tablaSiguiente=$result2["tablaSiguiente"];
        $tablaAnterior=$result2["tablaAnterior"];
        $posicionTable=$result2["posicion"];
        mysqli_close($link);
  
        return array(
            "identificador"=>$identificador,
            "tabla"=>$tabla,
            "detalle"=>$detalle,
            "pasoAnterior"=>$pasoAnterior,
            "tablaSiguiente"=>$tablaSiguiente,
            "tablaAnterior"=>$tablaAnterior,
            "posicion"=>$posicionTable
        );*/

    }

    function storeNewTableTree($posicion,$tAnterior){
      
        try {
            DB::beginTransaction();

            #INICIO
                $error =0;

                $newPosicion = (int)$posicion + 1;

                if($newPosicion >= 0 && $newPosicion < 10){
                    $newPosicion = "0".$newPosicion;
                }
                if($posicion >= 0 && $posicion < 10){
                    $posicion = "0".$posicion;
                }

                $nombre = "paso$newPosicion";
                $detalle = "Paso N° ".(int)$newPosicion;
                $posicionRegister = (int)$newPosicion;
                $pasoAnterior = "paso".(int)$posicion;
                $tablaSiguiente = "paso$newPosicion";
                $tablaAnterior = "$tAnterior";

                $queryStoreTable = "CREATE TABLE arboldecisiones.$nombre (
                                    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
                                    detalle VARCHAR(200) NULL,
                                    $pasoAnterior int(11) NULL,
                                    img_total VARCHAR(100) NULL,
                                    img_negocios VARCHAR(100) NULL,
                                    img_masivo VARCHAR(100) NULL
                                    )";
 
                DB::statement($queryStoreTable);

                $queryPasoArbol = "insert into arboldecisiones.pasosArbol values(0,'$nombre','$detalle','$posicionRegister','$pasoAnterior',null,'$tablaAnterior')";
                $executepasoarbol = DB::insert($queryPasoArbol);

                $queryUpdate = "UPDATE arboldecisiones.pasosArbol SET tablaSiguiente='$nombre' WHERE nombre='paso$posicion'";
                $updateResult = DB::update($queryUpdate);


            #END
            DB::commit();
        } catch(QueryException $ex){ 
            // dd($ex->getMessage()); 
            DB::rollback();
            throw new HttpException(409,"Hubo un conflicto con la creación de pasos, intente nuevamente!.");
            
        }catch(\Exception $e){
            // dd($e->getMessage()); 
            DB::rollback();
            throw new HttpException(402,"Hubo un error inesperado!, intente nuevamente!."); 
        }
           
        
    }

    function arbolStoreDecision($tabla,$detail,$column,$valueColumn,$imagenTotal,$imagenNegocio,$imagenMasiva)
    {
        $camposImg = "";
        $valuesImg = "";

        if($imagenTotal != "" ){
            $camposImg .= ",img_total"; 
            $valuesImg .= ",'$imagenTotal'"; 
        }
        if($imagenNegocio != "" ){
            $camposImg .= ",img_negocios"; 
            $valuesImg .= ",'$imagenNegocio'"; 
        }
        if($imagenMasiva != "" ){
            $camposImg .= ",img_masivo"; 
            $valuesImg .= ",'$imagenMasiva'"; 
        }

        try {
            //insert_get_id -> usando Table
                if (isset($valueColumn)) {
                    DB::insert("insert ignore arboldecisiones.$tabla (id,detalle,$column $camposImg) 
                            values (null,'$detail',$valueColumn $valuesImg)");
                }else{
                    DB::insert("insert ignore arboldecisiones.$tabla (id,detalle $camposImg) 
                            values (null,'$detail' $valuesImg)");
                }
             
             $id_tabla_trabajo = DB::getPdo()->lastInsertId();
            //$id_tabla_trabajo = mysqli_insert_id($link);
             
        } catch(QueryException $ex){ 
            // dd($ex->getMessage()); 
            DB::rollback();
            throw new HttpException(409,"Se genero un error en el servidor con la BD al registrar, intentar nuevamente.");
            
        }catch(\Exception $e){
            // dd($e->getMessage()); 
            DB::rollback();
            throw new HttpException(402,"Hubo un error inesperado!, intente nuevamente!."); 
        }
  
        return $id_tabla_trabajo;

    }

    function updateArbolDecision($tabla,$idEdit,$newtext,$img_total,$img_negocio,$img_masiva)
    {
         
        $anidaImganeQuery = "";
        
        if($img_total != ""){

            $anidaImganeQuery .= " ,img_total='$img_total' ";

        }
        if($img_negocio != ""){

            $anidaImganeQuery .= " ,img_negocios='$img_negocio' ";
        }
        if($img_masiva != ""){
 
            $anidaImganeQuery .= " ,img_masivo='$img_masiva' ";
        }

        try {
            $updateText=DB::update("update arboldecisiones.$tabla set detalle='$newtext' $anidaImganeQuery where id=$idEdit"); 
        } catch(QueryException $ex){ 
            // dd($ex->getMessage()); 
            DB::rollback();
            throw new HttpException(409,"hubo un error en la actualización, intente nuevamente.");
            
        }catch(\Exception $e){
            // dd($e->getMessage()); 
            DB::rollback();
            throw new HttpException(402,"hubo un error en la actualización, intente nuevamente."); 
        }
  
    }

    function deletesChildsArrayTree($datosEliminar){
         
        try {
            DB::beginTransaction();
            
            #INICIO
                $success = 1;
  
                for ($i=0;  $i < count($datosEliminar) ; $i++) { 
                    
                    $tabla = $datosEliminar[$i]["paso"];
                    $identificador = $datosEliminar[$i]["id"];
                    $queryDelete = DB::delete("delete from arboldecisiones.$tabla where id=?",[$identificador]);
                    
                    if($queryDelete != 1){
                        $success = 0;
                        break;
                    }
                }

                if ($success == 1) {
                    DB::commit();
                }else{
                    DB::rollback();
                }

            #END
          
        } catch(QueryException $ex){ 
            // dd($ex->getMessage()); 
            DB::rollback();
            throw new HttpException(409,"Hubo un conflicto con la eliminación de ramas, intente nuevamente!.");
            
        }catch(\Exception $e){
            // dd($e->getMessage()); 
            DB::rollback();
            throw new HttpException(402,"Hubo un error inesperado!, intente nuevamente!."); 
        }

         
        return $success;
         
    }


    #INICIA MULTICONSULTA ARBOL


    function getListTablaPorMensaje($mensaje)
    { 
        $consulta=DB::select("SELECT * FROM arboldecisiones.mensajeArbol WHERE mensaje =SUBSTR('$mensaje',1,35) LIMIT 1");  
        
        return $consulta;

    }
     
    function getMarcacionRapida()
    {
        $dataActual = DB::select("SELECT * FROM arboldecisiones.marcaRapida where estado=1");

        return $dataActual;
    }

    function getImage($tabla,$valorSelect,$campoImagen)
    {
          
        $img=DB::select("select $campoImagen from arboldecisiones.$tabla where id=$valorSelect and $campoImagen is not null"); 
	  
        return $img;

    }

    public function getSelects($tabla,$columna,$valorSelect)
    {
     
        try {
            if($tabla == "paso00"){ 
                $it3="select id,detalle from arboldecisiones.$tabla group by id"; 
                 
            }else{
                $it3="select id,detalle from arboldecisiones.$tabla where $columna=$valorSelect group by id"; 
            }
            
            $consultaSelects = DB::select($it3);
        } catch(QueryException $ex){ 
            // dd($ex->getMessage()); 
          
            throw new HttpException(409,"Hubo un conflicto con la BD, intente nuevamente!.");
            
        }catch(\Exception $e){
            // dd($e->getMessage()); 
          
            throw new HttpException(402,"Hubo un error inesperado!, intente nuevamente!."); 
        }
       

        return $consultaSelects;
    
    } 

    function registrandoDecisionesArbol($decisiones,$marcaRapida,$codCliente,$usuario)
    {

        $columnas = "";
        $valorColumnas = "";
        if (isset($decisiones)) {
           
            for ($i=0; $i < count($decisiones); $i++) { 
                   
                $columnas.= ", paso$i";
                $valorColumnas.=  ', '.$decisiones[$i]["valorSelect"];
            }

        }

        $marcaRapida = isset($marcaRapida) ? $marcaRapida : 'null';
 
       //$query = "insert into arboldecisiones.decisiones (iddecision,idclientecrm,usuario,fechahora,solucion $columnas) 
       //            values (null,$codCliente,'$usuario',now(),$marcaRapida $valorColumnas)";
       //dd($query);

         try {
             DB::insert("insert into arboldecisiones.decisiones (iddecision,idclientecrm,usuario,fechahora,solucion $columnas) 
                            values (null,$codCliente,'$usuario',now(),$marcaRapida $valorColumnas)");

         } catch(QueryException $ex){ 
            // dd($ex->getMessage()); 
          
            throw new HttpException(409,"Hubo un conflicto con la BD al registrar sus decisiones, intente nuevamente!.");
            
        }catch(\Exception $e){
            // dd($e->getMessage()); 
          
            throw new HttpException(402,"Hubo un error inesperado!, intente nuevamente!."); 
        }
       
      

        
 
       

       
    }


}

 