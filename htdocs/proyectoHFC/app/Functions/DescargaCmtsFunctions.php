<?php 

namespace App\Functions;
use DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\QueryException;
use App\Functions\ConexionSshFunctions;

ini_set('max_execution_time', 800);
  
class DescargaCmtsFunctions {

    function getListaDescargaCmts()
    {
        try {
            $cmts = DB::select("select nombre, tipo, marca from ccm1.cmts_ip where marca<>'' order by nombre asc");
        } catch(QueryException $ex){ 
            //dd($ex->getMessage());  
            //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return "error";
            
        }catch(\Exception $e){
            //dd($e->getMessage());  
            //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return "error";
        } 
        
        return $cmts;
    }


    function getProcesarDescargaCmts($listacmts)
    {
        try {
            #INICIO
            //CONEXION SSH PARA OBTENER LISTADO DE ARCHIVOS DEL SERVER 207 DE LOS CMTS
            $sshConexiones = new ConexionSshFunctions;

            $conexiones = $sshConexiones->server207_conexion(); 
            
            $con_user = $conexiones["user"];
            $con_pass = $conexiones["pass"];
            $con_ip = $conexiones["ip"];
            $con_puerto = $conexiones["puerto"];

            /*
            $text_ssh = "sshpass -p "."'$con_pass'"." ssh -p ".$con_puerto." -o StrictHostKeyChecking=no ".$con_user."@".$con_ip;
            $text_comand = "\"ls -l /tftpboot/ --time-style=full-iso | awk '{print \$6,\$7,\$9}' > /tftpboot/archivos_cmts.txt\"";
            $ssh_exec = $text_ssh." ".$text_comand;
            exec($ssh_exec);
            */

            $ssh_exec = "sshpass -p "."'$con_pass'"." ssh -p ".$con_puerto." -o StrictHostKeyChecking=no ".$con_user."@".$con_ip." 'ls -l --time-style=+\"%Y-%m-%d %H:%M:%S\" /tftpboot/ > /tftpboot/archivos_cmts.txt'";
            exec($ssh_exec);

            $cmts = array();
            $file_cmts = '/tftpboot/archivos_cmts.txt';
            $contents = Storage::disk('sftp')->get($file_cmts);

            $arrayRegistros = explode("\n",$contents);

            //dd($arrayRegistros);

            for ($i=1; $i < count($arrayRegistros) ; $i++) {
                $regist = $i-1;
                $cmts[$regist] = explode(" ",$arrayRegistros[$i]);
            }

            $cantidad = count($cmts);

            for ($i=0; $i < $cantidad; $i++) {
                foreach ($cmts[$i] as $key => $value) {
                    if ($value=='' or $value==' ') {
                        unset($cmts[$i][$key]);
                    }
                }
            }

            $cmts=array_map('array_values', $cmts); //Array donde esta informacion de los archivos
            
            //dd($cmts);

            //DATOS OBTENIDOS DE LOS CMTS DE LA BASE DE DATOS
            $cantidadCmts = count($listacmts);
            $respuestaDescargaCmts = array();

            for ($i=0; $i < $cantidadCmts ; $i++) {
            
                $nombreCmts = $listacmts[$i]->nombre;
                $tipo = $listacmts[$i]->tipo;
                $marca = $listacmts[$i]->marca;

                $archivoSum = "scm_sum_".$nombreCmts.".txt";
                $archivoPhy = "scm_phy_".$nombreCmts.".txt";
                $archivoScm = "scmoffline_".$nombreCmts.".txt";


                $buscar_FechaSum = array_search($archivoSum, array_column($cmts,'7'));
                if ($buscar_FechaSum==false) {
                    $timeSum = "No disponible";
                } else {
                    $timeSum = $cmts[$buscar_FechaSum][5]." ".$cmts[$buscar_FechaSum][6];
                }

                $buscar_FechaPhy = array_search($archivoPhy, array_column($cmts,'7'));
                if ($buscar_FechaPhy==false) {
                    $timePhy = "No disponible";
                } else {
                    $timePhy = $cmts[$buscar_FechaPhy][5]." ".$cmts[$buscar_FechaPhy][6];
                }
                
                $buscar_FechaScm = array_search($archivoScm, array_column($cmts,'7'));
                if ($buscar_FechaScm==false) {
                    $timeScm = "No disponible";
                } else {
                    $timeScm = $cmts[$buscar_FechaScm][5]." ".$cmts[$buscar_FechaScm][6];
                }
                
                $respuestaDescargaCmts[$i]['archivo'] = $tipo."-".$marca;
                $respuestaDescargaCmts[$i]['nombre'] = $nombreCmts;
                $respuestaDescargaCmts[$i]['archivo_sum'] = $archivoSum;
                $respuestaDescargaCmts[$i]['time_sum'] = $timeSum;
                $respuestaDescargaCmts[$i]['archivo_phy'] = $archivoPhy;
                $respuestaDescargaCmts[$i]['time_phy'] = $timePhy;
                $respuestaDescargaCmts[$i]['archivo_scm'] = $archivoScm;
                $respuestaDescargaCmts[$i]['time_scm'] = $timeScm;

            }

            $ssh_exec = "sshpass -p "."'$con_pass'"." ssh -p ".$con_puerto." -o StrictHostKeyChecking=no ".$con_user."@".$con_ip." 'rm /tftpboot/archivos_cmts.txt'";
            exec($ssh_exec);

            //dd($respuestaDescargaCmts);
            return $respuestaDescargaCmts;

        #END
        } catch(QueryException $ex){ 
            //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return "error";
        }catch(\Exception $e){
            //dd($e->getMessage());  
            //throw new HttpException(409,"Se generó un conflicto con los datos, intente dentro de un minuto por favor.");
            return "error";
        } 


        /*
                $cantidadCmts = count($listacmts);
                $respuestaDescargaCmts = array();
                //$contadorId = 0;
        
                for ($i=0; $i < $cantidadCmts ; $i++) {

                    $nombreCmts = $listacmts[$i]->nombre;
                    $tipo = $listacmts[$i]->tipo;
                    $marca = $listacmts[$i]->marca;

                    $archivoSum = "scm_sum_".$nombreCmts.".txt";
                    $archivoPhy = "scm_phy_".$nombreCmts.".txt";
                    $archivoScm = "scmoffline_".$nombreCmts.".txt";

                    $obtSum = '/tftpboot/'.$archivoSum;
                    $existsSum = Storage::disk('sftp')->exists($obtSum);

                    if($existsSum==true){
                        $timeSum = Storage::disk('sftp')->lastModified($obtSum);
                        $timeSum = date('Y-m-d H:i:s',$timeSum);
                    }else {
                        $timeSum = "1969-12-31 19:00:00";
                    }

                    $obtPhy = '/tftpboot/'.$archivoPhy;
                    $existsPhy = Storage::disk('sftp')->exists($obtPhy);

                    if($existsPhy==true){
                        $timePhy = Storage::disk('sftp')->lastModified($obtPhy);
                        $timePhy = date('Y-m-d H:i:s',$timePhy);
                    }else {
                        $timePhy = "1969-12-31 19:00:00";
                    }

                    $obtScm = '/tftpboot/'.$archivoScm;
                    $existsScm = Storage::disk('sftp')->exists($obtScm);

                    if($existsScm==true){
                        $timeScm = Storage::disk('sftp')->lastModified($obtScm);
                        $timeScm = date('Y-m-d H:i:s',$timeScm);
                    }else {
                        $timeScm = "1969-12-31 19:00:00";
                    }
                    
                    $respuestaDescargaCmts[$i]['archivo'] = $tipo."-".$marca;
                    $respuestaDescargaCmts[$i]['nombre'] = $nombreCmts;
                    $respuestaDescargaCmts[$i]['archivo_sum'] = $archivoSum;
                    $respuestaDescargaCmts[$i]['time_sum'] = $timeSum;
                    $respuestaDescargaCmts[$i]['archivo_phy'] = $archivoPhy;
                    $respuestaDescargaCmts[$i]['time_phy'] = $timePhy;
                    $respuestaDescargaCmts[$i]['archivo_scm'] = $archivoScm;
                    $respuestaDescargaCmts[$i]['time_scm'] = $timeScm;
                    //dd($respuestaDescargaCmts);

                }

                //dd($respuestaDescargaCmts);
                return $respuestaDescargaCmts;
           #END
        } 
        */
 

    }



}