<?php 

namespace App\Functions;
use DB; 
use App\Administrador\ParametroColores;
use Illuminate\Database\QueryException;
  
class EstadosModemsFunctions {

    function getListaEstadosModems()
    {
        try {
            $estados = DB::select("SELECT b.id AS identidad,b.tipo,a.cmts,a.init_r1,a.init_r2,a.init_rc,a.init_r,a.sinippublica,a.init_d,a.init_i,a.init_o,a.init_io,a.init_t,a.init_dr,a.cc_pending,a.reject,a.p_online,a.w_expire_pt,a.online_pt,a.w_online_pt,a.online_d,a.online,a.offline,a.total
            FROM ccm1.Status_cablemodems a INNER JOIN ccm1.cmts_ip b ON a.cmts=b.cmts ORDER BY tipo DESC,cmts ASC");
        } catch(QueryException $ex){ 
             //dd($ex->getMessage());  
            //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return "error";
            
        }catch(\Exception $e){
            //dd($e->getMessage());  
            //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return "error";
        } 
        
        return $estados;
    }

    function getTotalEstadosModems()
    {
        try {
            $totales = DB::select("SELECT SUM(bpi_wait) AS bpi_wait,
                                    SUM(cc_pending) AS cc_pending,
                                    SUM(init_o) AS init_o,
                                    SUM(init_i) AS init_i,
                                    SUM(init_io) AS init_io,
                                    SUM(init_dr) AS init_dr,
                                    SUM(sinippublica) AS sinippublica,
                                    SUM(init_d) AS init_d,
                                    SUM(init_r) AS init_r,
                                    SUM(init_r1) AS init_r1,
                                    SUM(init_r2) AS init_r2,
                                    SUM(init_rc) AS init_rc,
                                    SUM(init_t) AS init_t,
                                    SUM(reject) AS reject,
                                    SUM(p_online) AS p_online,
                                    SUM(w_expire_pt) AS w_expire_pt,
                                    SUM(wonlineBpiSucc) AS wonlineBpiSucc,
                                    SUM(online_pt) AS online_pt,
                                    SUM(w_online_pt) AS w_online_pt,
                                    SUM(online_d) AS online_d,
                                    SUM(online) AS online,
                                    SUM(offline) AS offline,
                                    SUM(total) AS total,fecha_hora 
                                    FROM ccm1.Status_cablemodems");
        } catch(QueryException $ex){ 
             //dd($ex->getMessage());  
            //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return "error";
            
        }catch(\Exception $e){
            //dd($e->getMessage());  
            //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return "error";
        } 
        
        return $totales;
    }

    
    function getParametroColoresEstadosModems()
    {

        try {
           #INICIO

                $parametrosColores = ParametroColores::getEstadosModemsParametros();
                $colores = $parametrosColores->COLORES;
                return $colores;
           #END
        } catch(QueryException $ex){ 
            //dd($ex->getMessage());  
            //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return "error";
            
        }catch(\Exception $e){
            //dd($e->getMessage());  
            //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return "error";
        } 
 

    }

    function getProcesarEstadosModems($estadosmodems)
    {

        try {
           #INICIO

                $parametrosColores = ParametroColores::getEstadosModemsParametros();
                //dd($parametrosColores);
                $colores = $parametrosColores->COLORES;
                //dd($estadosmodems);
                $cantidadEstadosmodems = count($estadosmodems);
                $acumulandoRespuestaEstadosmodems = array();
                $contadorId = 0;
        
                for ($i=0; $i < $cantidadEstadosmodems ; $i++) {

                    $estadosmodems[$i]->id = $contadorId + 1;
                    $estadosmodems[$i]->identidad = $estadosmodems[$i]->identidad;
                    $acumulandoRespuestaEstadosmodems[] = $this->procesoEstadosModemsGeneral($estadosmodems[$i],$colores);
                    $contadorId++;
                }
                
                return $acumulandoRespuestaEstadosmodems;
           #END
        } catch(QueryException $ex){ 
            //dd($ex->getMessage());  
            //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return "error";
            
        }catch(\Exception $e){
            //dd($e->getMessage());  
            //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return "error";
        } 
 

    }

    private function procesoEstadosModemsGeneral($estadosmodems,$colores)
    { 

        // Estructura de Colores

        $porcCaido = ($estadosmodems->offline / $estadosmodems->total)*100;
        if ($porcCaido > 60 )
        {
            $backgroundPorcCaido=$colores->porcCaido->colores[0]->background;
            $colorPorcCaido=$colores->porcCaido->colores[0]->color;
        }else{
            $backgroundPorcCaido=$colores->porcCaido->colores[1]->background;
            $colorPorcCaido=$colores->porcCaido->colores[1]->color;
        }

        $estadosmodems->backgroundPorcCaido = $backgroundPorcCaido;
        $estadosmodems->colorPorcCaido = $colorPorcCaido;

        if ($estadosmodems->init_r1 > 5 )
        {
            $backgroundInit_r1=$colores->init_r1->colores[0]->background;
            $colorInit_r1=$colores->init_r1->colores[0]->color;
        }else{
            $backgroundInit_r1=$colores->init_r1->colores[1]->background;
            $colorInit_r1=$colores->init_r1->colores[1]->color;
        }

        $estadosmodems->backgroundInit_r1 = $backgroundInit_r1;
        $estadosmodems->colorInit_r1 = $colorInit_r1;

        if ($estadosmodems->init_r2 > 5 )
        {
            $backgroundInit_r2=$colores->init_r2->colores[0]->background;
            $colorInit_r2=$colores->init_r2->colores[0]->color;
        }else{
            $backgroundInit_r2=$colores->init_r2->colores[1]->background;
            $colorInit_r2=$colores->init_r2->colores[1]->color;
        }

        $estadosmodems->backgroundInit_r2 = $backgroundInit_r2;
        $estadosmodems->colorInit_r2 = $colorInit_r2;

        if ($estadosmodems->init_rc > 5 )
        {
            $backgroundInit_rc=$colores->init_rc->colores[0]->background;
            $colorInit_rc=$colores->init_rc->colores[0]->color;
        }else{
            $backgroundInit_rc=$colores->init_rc->colores[1]->background;
            $colorInit_rc=$colores->init_rc->colores[1]->color;
        }

        $estadosmodems->backgroundInit_rc = $backgroundInit_rc;
        $estadosmodems->colorInit_rc = $colorInit_rc;

        if ($estadosmodems->init_r > 5 )
        {
            $backgroundInit_r=$colores->init_r->colores[0]->background;
            $colorInit_r=$colores->init_r->colores[0]->color;
        }else{
            $backgroundInit_r=$colores->init_r->colores[1]->background;
            $colorInit_r=$colores->init_r->colores[1]->color;
        }

        $estadosmodems->backgroundInit_r = $backgroundInit_r;
        $estadosmodems->colorInit_r = $colorInit_r;
     
        if ( ($estadosmodems->sinippublica > (0.005*($estadosmodems->total - $estadosmodems->offline))) && ($estadosmodems->total > 5000) || ($estadosmodems->sinippublica > 25) )
        {
            $backgroundSinippublica=$colores->sinippublica->colores[0]->background;
            $colorSinippublica=$colores->sinippublica->colores[0]->color;
        }else{
            $backgroundSinippublica=$colores->sinippublica->colores[1]->background;
            $colorSinippublica=$colores->sinippublica->colores[1]->color;
        }

        $estadosmodems->backgroundSinippublica = $backgroundSinippublica;
        $estadosmodems->colorSinippublica = $colorSinippublica;

        if ($estadosmodems->init_d > 5 )
        {
            $backgroundInit_d=$colores->init_d->colores[0]->background;
            $colorInit_d=$colores->init_d->colores[0]->color;
        }else{
            $backgroundInit_d=$colores->init_d->colores[1]->background;
            $colorInit_d=$colores->init_d->colores[1]->color;
        }

        $estadosmodems->backgroundInit_d = $backgroundInit_d;
        $estadosmodems->colorInit_d = $colorInit_d;

        if ($estadosmodems->init_i > 5 )
        {
            $backgroundInit_i=$colores->init_i->colores[0]->background;
            $colorInit_i=$colores->init_i->colores[0]->color;
        }else{
            $backgroundInit_i=$colores->init_i->colores[1]->background;
            $colorInit_i=$colores->init_i->colores[1]->color;
        }

        $estadosmodems->backgroundInit_i = $backgroundInit_i;
        $estadosmodems->colorInit_i = $colorInit_i;

        if ($estadosmodems->init_o > 5 )
        {
            $backgroundInit_o=$colores->init_o->colores[0]->background;
            $colorInit_o=$colores->init_o->colores[0]->color;
        }else{
            $backgroundInit_o=$colores->init_o->colores[1]->background;
            $colorInit_o=$colores->init_o->colores[1]->color;
        }

        $estadosmodems->backgroundInit_o = $backgroundInit_o;
        $estadosmodems->colorInit_o = $colorInit_o;

        if ($estadosmodems->init_io > 5 )
        {
            $backgroundInit_io=$colores->init_io->colores[0]->background;
            $colorInit_io=$colores->init_io->colores[0]->color;
        }else{
            $backgroundInit_io=$colores->init_io->colores[1]->background;
            $colorInit_io=$colores->init_io->colores[1]->color;
        }

        $estadosmodems->backgroundInit_io = $backgroundInit_io;
        $estadosmodems->colorInit_io = $colorInit_io;

        if ($estadosmodems->init_t > 5 )
        {
            $backgroundInit_t=$colores->init_t->colores[0]->background;
            $colorInit_t=$colores->init_t->colores[0]->color;
        }else{
            $backgroundInit_t=$colores->init_t->colores[1]->background;
            $colorInit_t=$colores->init_t->colores[1]->color;
        }

        $estadosmodems->backgroundInit_t = $backgroundInit_t;
        $estadosmodems->colorInit_t = $colorInit_t;

        if ($estadosmodems->init_dr > 5 )
        {
            $backgroundInit_dr=$colores->init_dr->colores[0]->background;
            $colorInit_dr=$colores->init_dr->colores[0]->color;
        }else{
            $backgroundInit_dr=$colores->init_dr->colores[1]->background;
            $colorInit_dr=$colores->init_dr->colores[1]->color;
        }

        $estadosmodems->backgroundInit_dr = $backgroundInit_dr;
        $estadosmodems->colorInit_dr = $colorInit_dr;

        if ($estadosmodems->p_online > 5 )
        {
            $backgroundP_online=$colores->p_online->colores[0]->background;
            $colorP_online=$colores->p_online->colores[0]->color;
        }else{
            $backgroundP_online=$colores->p_online->colores[1]->background;
            $colorP_online=$colores->p_online->colores[1]->color;
        }

        $estadosmodems->backgroundP_online = $backgroundP_online;
        $estadosmodems->colorP_online = $colorP_online;

        $estadosmodems->fondo = $colores->default->colores[0]->background;
        $estadosmodems->letra = $colores->default->colores[0]->color;

        return $estadosmodems;
                    
    }

}