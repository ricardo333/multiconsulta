<?php
namespace App\Functions;

use DB;
use App\Library\simple_html_dom;
use App\Functions\CablemodemFunctions;
  
class CablemodemStatusFunctions {

    public function statusPrincipal($codCliente,$ipaddress,$fabricante,$modelo)
    { 
        $status = array(
            "Upstream"=>[],
            "Downstream"=>[],
            "Correct"=>0,
            "UnCorrect"=>0
        );

        if ($fabricante=="Askey") { 
            if ($modelo=="TCG220-TdP") {
                $status = $this->obtenerStatusAskey3($codCliente,$ipaddress,$fabricante);
            }else{
                $status = $this->obtenerStatusAskey($codCliente,$ipaddress,$fabricante);
            }
        } elseif (substr($fabricante,0,3)=="Hit") {
            $status = $this->obtenerStatusHitron($codCliente,$ipaddress,$fabricante);
        } elseif ($fabricante=="Ubee") {
            $status = $this->obtenerStatusUbee($codCliente,$ipaddress,$fabricante);
        } elseif (substr($fabricante,0,5)=="SAGEM") {
            $status = $this->obtenerStatusSagem($codCliente,$ipaddress,$fabricante);
        } elseif (substr($fabricante,0,9)=="CastleNet" || substr($fabricante,0,6)=="Telefo") {
            $status = $this->obtenerStatusCastlenet($codCliente,$ipaddress,$fabricante);
        }
        
        return $status;

    }
 
    protected function obtenerStatusAskey($codCliente,$ipaddress,$fabricante){

        $login_Askey = array(
            "sessionKey" => "defined",
            "AskUsername" => "admin",
            "AskPassword" => $codCliente
        );

        $url_router="https://".$ipaddress."/goform/AskLogin";
        $url_upstream="https://".$ipaddress."/Upstream.asp";
        $url_downstream="https://".$ipaddress."/Downstream.asp";
        $url_logout="https://".$ipaddress."/login.asp";

        $ingresarCablemodem = new CablemodemFunctions;
        $ingreso = $ingresarCablemodem->getPageAskey1($url_router,$login_Askey);

        if($ingreso=="Error"){
            return "Error";
        } else {

            $obtStatusUp = $ingresarCablemodem->getPageAskey2($url_upstream);
            $obtStatusDown = $ingresarCablemodem->getPageAskey2($url_downstream);
            $logout = $ingresarCablemodem->getPageAskey2($url_logout);

            //Consulta a la pagina de datos de status de Upstream
            $html = new simple_html_dom();
            $html->load($obtStatusUp);	

            $a=$html->find('script',6); 

            if(is_null($a)==true){
                return "Error Codigo";
            } else {

                $arr=$a->innertext;
                $array1=explode('a_upstream = ',$arr);
                $array2=explode('  ',$array1[1]);

                $search1 = array("[","\"",",]];");
                $reemplazo1=str_replace($search1,"",$array2[0]);

                $search2 = array(",],");
                $reemplazo2=str_replace($search2,"=",$reemplazo1);

                $array3=preg_split('/=/',$reemplazo2);
                $array4=preg_split('/,/',$array3[0]);

                $upstream = array();
                $upcantidad = count($array3);
                $registroUP = 0;

                for ($i=0; $i < $upcantidad; $i++) { 
                    $separaColumnas=preg_split('/,/',$array3[$i]);
                    if($separaColumnas[1]<>0){
                        $registroUP += 1;
                        $upstream[] = array(
                            'Registro'=>$registroUP,
                            'Frecuencia'=>$separaColumnas[3],
                            'Power'=>$separaColumnas[7]
                        );
                    
                    }
                }

                //Consulta a la pagina de datos de status de Downstream
                $html2 = new simple_html_dom();
                $html2->load($obtStatusDown);

                $a2=$html2->find('script',6);
                $arr=$a2->innertext;
                $array10=explode('a_downstream = ',$arr);
                $array20=explode('  ',$array10[1]);

                $search10 = array("[","\"",",]];");
                $reemplazo10=str_replace($search10,"",$array20[0]);

                $search20 = array(",],");
                $reemplazo20=str_replace($search20,"=",$reemplazo10);

                $array30=preg_split('/=/',$reemplazo20);
                $array40=preg_split('/,/',$array30[0]);

                $downstream = array();
                $downcantidad = count($array30);
                $registroDown = 0;

                for ($i=0; $i < $downcantidad; $i++) { 
                    $separaColumnas2=preg_split('/,/',$array30[$i]);
                    if($separaColumnas2[1]<>0){
                        $registroDown += 1;
                        $downstream[] = array(
                            'Registro' => $registroDown,
                            'Channel' => $separaColumnas2[1],
                            'Lock' => $separaColumnas2[2],
                            'Frecuencia' => $separaColumnas2[3],
                            'Modulacion' => $separaColumnas2[4],
                            'None' => $separaColumnas2[5],
                            'SNR' => $separaColumnas2[6],
                            'Power' => $separaColumnas2[7],
                            'Correct' => $separaColumnas2[8],
                            'Uncorrect' => $separaColumnas2[9]
                        );
                        
                    }
                }

                $sumCorrect = array_sum(array_column($downstream, 'Correct'));
                $sumUnCorrect = array_sum(array_column($downstream, 'Uncorrect'));

                return array(
                    "Upstream" => $upstream,
                    "Downstream" => $downstream,
                    "Correct" => $sumCorrect,
                    "UnCorrect" => $sumUnCorrect
                );

            }

        }
        
    }


    protected function obtenerStatusAskey3($codCliente,$ipaddress,$fabricante){

        $login_Askey = array(
            "sessionKey" => "defined",
            "AskUsername" => "admin",
            "AskPassword" => $codCliente
        );

        $url_router="https://".$ipaddress."/goform/AskLogin";
        $url_upstream="https://".$ipaddress."/Upstream.asp";
        $url_downstream="https://".$ipaddress."/Downstream.asp";
        $url_logout="https://".$ipaddress."/login.asp";

        $ingresarCablemodem = new CablemodemFunctions;
        $ingreso = $ingresarCablemodem->getPageAskey1($url_router,$login_Askey);

        if($ingreso=="Error"){
            return "Error";
        } else {

            $obtStatusUp = $ingresarCablemodem->getPageAskey2($url_upstream);
            $obtStatusDown = $ingresarCablemodem->getPageAskey2($url_downstream);
            $logout = $ingresarCablemodem->getPageAskey2($url_logout);

            //Consulta a la pagina de datos de status de Upstream
            $html = new simple_html_dom();
            $html->load($obtStatusUp);	

            $html2 = new simple_html_dom();
            $html2->load($obtStatusDown);

            $datosUp = array();
            $datosDown = array();

            foreach($html->find('[class=table-col mycol05]') as $datossc1) {
                foreach($datossc1->find('span') as $a){
                    $datosUp[]=$a->innertext;
                }
            }

            foreach($html2->find('[class=table-col mycol05]') as $datossc2) {
                foreach($datossc2->find('span') as $b){
                    $datosDown[]=$b->innertext;
                }
            }
            
            $cantidad1 = count($datosUp);
            $cantidad2 = count($datosDown);
            
            $upstream = array();
            $downstream = array();

            $registro = 0;
 
            for ($i=8; $i < $cantidad1; $i+=7) {
                if($datosUp[$i+1]<>0){
                    $registro += 1;
                    $upstream[] = array(
                        'Registro'=>$registro,
                        'Frecuencia'=>$datosUp[$i+3],
                        'Power'=>$datosUp[$i+6]
                    );
                }
            }

            $registroDown = 0;
            
            for ($i=10; $i < $cantidad2; $i+=8) {
                if($datosDown[$i+1]<>0){
                    //$reg2 += 1;
                    $registroDown += 1;
                        $downstream[] = array(
                            'Registro' => $registroDown,
                            'Channel' => $datosDown[$i+1],
                            'Lock' => $datosDown[$i+2],
                            'Frecuencia' => $datosDown[$i+3],
                            'Modulacion' => "No Registra",
                            'None' => "No Registra",
                            'SNR' => $datosDown[$i+4],
                            'Power' => $datosDown[$i+5],
                            'Correct' => $datosDown[$i+6],
                            'Uncorrect' => $datosDown[$i+7]
                        );
                }
            }
            
            $sumCorrect = array_sum(array_column($downstream, 'Correct'));
            $sumUnCorrect = array_sum(array_column($downstream, 'Uncorrect'));

            return array(
                "Upstream" => $upstream,
                "Downstream" => $downstream,
                "Correct" => $sumCorrect,
                "UnCorrect" => $sumUnCorrect
            );

        }

    }

        
    


    protected function obtenerStatusHitron($codCliente,$ipaddress,$fabricante){

        $loginHitron = array(
            "user" => "admin",
            "pws" => $codCliente
        );

        $url_router="https://".$ipaddress."/goform/login";
        $url_status="https://".$ipaddress."/admin/cable-status.asp";
        $url_logout="https://".$ipaddress."/login.asp";

        $ingresarCablemodem = new CablemodemFunctions;
        $ingreso = $ingresarCablemodem->getPageHitron1($url_router,$loginHitron);

        if($ingreso=="Error"){
            return "Error";
        }else {

        $obtStatus = $ingresarCablemodem->getPageHitron2($url_status);
        $logout = $ingresarCablemodem->getPageHitron2($url_logout);

        $html = new simple_html_dom();
        $html->load($obtStatus);

        $a=$html->find('script',5);
        $arr=$a->innertext;

        $array1=explode('<span ',$arr);
        $array2=preg_split('/[{(\;:=+)}]/',$array1[0]);

        $buscar_upChanel=array_search(' var CmUpstreamChannelIdBase ',$array2);
        $buscar_upFrecuency=array_search(' var CmUpstreamFrequencyBase ',$array2);
        $buscar_upPower=array_search(' var CmUpstreamChannelPowerBase ',$array2);
        $buscar_downFrecuency=array_search(' var CmDownstreamFrequencyBase ',$array2);
        $buscar_downPower=array_search(' var CmDownstreamChannelPowerdBmVBase ',$array2);
        $buscar_sownSnr=array_search(' var CmDownstreamSnrBase ',$array2);
        $buscar_downCorrect=array_search(' var CmDownstreamCorrectedsBase ',$array2);
        $buscar_downUncorrect=array_search(' var CmDownstreamUncorrectablesBase ',$array2);

        $obt_chanel=$buscar_upChanel+1;
        $obt_upfrecuency=$buscar_upFrecuency+1;
        $obt_uppower=$buscar_upPower+1;
        $obt_frecuency=$buscar_downFrecuency+1;
        $obt_power=$buscar_downPower+1;
        $obt_snr=$buscar_sownSnr+1;
        $obt_downCorrect=$buscar_downCorrect+1;
        $obt_downUncorrect=$buscar_downUncorrect+1;

        $chanel=$array2[$obt_chanel];
        $frecuencia1=$array2[$obt_upfrecuency];
        $power1=$array2[$obt_uppower];
        $frecuencia2=$array2[$obt_frecuency];
        $power2=$array2[$obt_power];
        $snr2=$array2[$obt_snr];
        $downCorrect2=$array2[$obt_downCorrect];
        $downUncorrect2=$array2[$obt_downUncorrect];

        $search1 = array(" ","\"");

        $reemp1=str_replace($search1,"",$chanel);
        $reemp2=str_replace($search1,"",$frecuencia1);
        $reemp3=str_replace($search1,"",$power1);
        $reemp4=str_replace($search1,"",$frecuencia2);
        $reemp5=str_replace($search1,"",$power2);
        $reemp6=str_replace($search1,"",$snr2);
        $reemp7=str_replace($search1,"",$downCorrect2);
        $reemp8=str_replace($search1,"",$downUncorrect2);

        $reemplazo1=str_replace('|','=',$reemp1);
        $reemplazo2=str_replace('|','=',$reemp2);
        $reemplazo3=str_replace('|','=',$reemp3);
        $reemplazo4=str_replace('|','=',$reemp4);
        $reemplazo5=str_replace('|','=',$reemp5);
        $reemplazo6=str_replace('|','=',$reemp6);
        $reemplazo7=str_replace('|','=',$reemp7);
        $reemplazo8=str_replace('|','=',$reemp8);

        $upChanel=preg_split('/=/',$reemplazo1);
        $upFrecuencia=preg_split('/=/',$reemplazo2);
        $upPower=preg_split('/=/',$reemplazo3);
        $downFrecuencia=preg_split('/=/',$reemplazo4);
        $downPower=preg_split('/=/',$reemplazo5);
        $downSnr=preg_split('/=/',$reemplazo6);
        $downCorrect=preg_split('/=/',$reemplazo7);
        $downUncorrect=preg_split('/=/',$reemplazo8);

        $arrayAux1 = array();
        foreach ($upFrecuencia as $valor){
            if($valor != null && !empty($valor)){
                array_push($arrayAux1, $valor);
            }
        }

        $arrayAux2 = array();
        foreach ($downFrecuencia as $valor){
            if($valor != null && !empty($valor)){
                array_push($arrayAux2, $valor);
            }
        }

        $upFrecuencia = $arrayAux1;
        $downFrecuencia = $arrayAux2;

        $upstream = array();

        $registro = 0;

        $cantidad1 = count($upFrecuencia);

        for ($i=0; $i < $cantidad1; $i++) { 
            if ($upChanel[$i]<>0) {
                $registro += 1;
                $upstream[] = array(
                    'Registro'=>$registro,
                    'Frecuencia'=>$upFrecuencia[$i],
                    'Power'=>$upPower[$i]
                );
                
            }
        }

        $downstream = array();

        $cantidad2 = count($downFrecuencia);

        for ($i=0; $i < $cantidad2; $i++) { 
            $downstream[] = array( 
                'Registro' => $i+1,
                'Frecuencia' => $downFrecuencia[$i],
                'SNR' => $downSnr[$i],
                'Power' => $downPower[$i],
                'Power3' => $downCorrect[$i]
            );
        }

        $sumCorrect = array_sum($downCorrect);
        $sumUnCorrect = array_sum($downUncorrect);

        return array(
            "Upstream" => $upstream,
            "Downstream" => $downstream,
            "Correct" => $sumCorrect,
            "UnCorrect" => $sumUnCorrect
        );

    }

    }
 
    protected function obtenerStatusUbee($codCliente,$ipaddress,$fabricante){
        
        $login_Ubee = array(
            "loginUsername" => "admin",
            "loginPassword" => $codCliente
        );

        $url_router="http://".$ipaddress."/goform/login";
        $url_status="http://".$ipaddress."/RgConnect.asp";
        $url_logout="https://".$ipaddress."/login.asp";

        $ingresarCablemodem = new CablemodemFunctions;
        $ingreso = $ingresarCablemodem->getPageUbee1($url_router,$login_Ubee);

        if($ingreso=="Error"){
            return "Error";
        }else {

        $obtStatus = $ingresarCablemodem->getPageUbee2($url_status);
        $logout = $ingresarCablemodem->getPageUbee2($url_logout);

        $html = new simple_html_dom();
        $html->load($obtStatus);

        $dataTabla1 = array();
        $dataTabla2 = array();
        
        $tablaup = $html->find('table', 6);

        foreach($tablaup->find('tr') as $row1) {
            $rowData1 = array();
            foreach($row1->find('td') as $cell1) {
                $rowData1[] = $cell1->innertext;
            }
            $dataTabla1[] = $rowData1;
        }

        $tabladown = $html->find('table', 4);

        foreach($tabladown->find('tr') as $row2) {
            $rowData2 = array();
            foreach($row2->find('td') as $cell2) {
                $rowData2[] = $cell2->innertext;
            }
            $dataTabla2[] = $rowData2;
        }

        $upstream = array();
        $downstream = array();
        $registro1 = 0;
        $registro2 = 0;

        $cantidad1 = count($dataTabla1);
        $cantidad2 = count($dataTabla2);


        for ($i=0; $i < $cantidad1; $i++) { 
            if (count($dataTabla1[$i])>0){
                if ($dataTabla1[$i][2]<>"Unknown" and $dataTabla1[$i][2]<>"") {
                    $registro1 += 1;
                    $upstream[] = array(
                        'Registro' => $registro1,
                        'Frecuencia' => $dataTabla1[$i][5],
                        'Power' => $dataTabla1[$i][6]
                    );
                   
                }
            }
        }

        for ($i=0; $i < $cantidad2; $i++) { 
            if (count($dataTabla2[$i])>0){
                if ($dataTabla2[$i][2]<>"") {
                    $registro2 += 1;
                    $downstream[] = array(
                        'Registro' => $registro2,
                        'Frecuencia' => $dataTabla2[$i][4],
                        'Power' => $dataTabla2[$i][5],
                        'SNR' => $dataTabla2[$i][6],
                        'Correctable' => $dataTabla2[$i][7],
                        'Uncorrectable' => $dataTabla2[$i][8]
                    );
                }
            }
        }

        $totalCorrectable = 0;
        foreach ($downstream as $item) {
            $totalCorrectable += $item['Correctable'];
        }

        $totalUncorrectable = 0;
        foreach ($downstream as $item) {
            $totalUncorrectable += $item['Uncorrectable'];
        }

        return array(
            "Upstream" => $upstream,
            "Downstream" => $downstream,
            "Correct" => $totalCorrectable,
            "UnCorrect" => $totalUncorrectable
        );

    }

    }


    protected function obtenerStatusSagem($codCliente,$ipaddress,$fabricante){

        $login_Sagem = array(
            "loginUsername" => "root",
            "loginPassword" => $codCliente
        );

        $url_router="https://".$ipaddress."/goform/login";
        $url_status="https://".$ipaddress."/RgConnect.asp";
        $url_logout="https://".$ipaddress."/login.asp";

        $ingresarCablemodem = new CablemodemFunctions;
        $ingreso = $ingresarCablemodem->getPageSagem1($url_router,$login_Sagem);

        if($ingreso=="Error"){
            return "Error";
        }else {

        $obtStatus = $ingresarCablemodem->getPageSagem2($url_status);
        $logout = $ingresarCablemodem->getPageSagem2($url_logout);

        $html = new simple_html_dom();
        $html->load($obtStatus);

        $dataTabla1 = array();
        $dataTabla2 = array();
        
        $tablaup = $html->find('table', 3);

        foreach($tablaup->find('tr') as $row1) {
            $rowData1 = array();
            foreach($row1->find('td') as $cell1) {
                $rowData1[] = $cell1->innertext;
            }
            $dataTabla1[] = $rowData1;
        }

        $tabladown = $html->find('table', 1);

        foreach($tabladown->find('tr') as $row2) {
            $rowData2 = array();
            foreach($row2->find('td') as $cell2) {
                $rowData2[] = $cell2->innertext;
            }
            $dataTabla2[] = $rowData2;
        }

        $upstream = array();
        $downstream = array();
        $registro1 = 0;
        $registro2 = 0;

        $cantidad1 = count($dataTabla1);
        $cantidad2 = count($dataTabla2);

         ;
        for ($i=0; $i < $cantidad1; $i++) { 
            if (count($dataTabla1[$i])>0){
                if ($dataTabla1[$i][2]<>"Unknown" and $dataTabla1[$i][2]<>"") {
                    $registro1 += 1;
                    $upstream[] = array(
                            'Registro'=>$registro1,
                            'Frecuencia'=>$dataTabla1[$i][5],
                            'Power'=>$dataTabla1[$i][6]);
                }
            }
        }

        for ($i=0; $i < $cantidad2; $i++) { 
            if (count($dataTabla2[$i])>0){
                if ($dataTabla2[$i][2]<>"") {
                    $registro2 += 1;
                    $downstream[] = array(
                        'Registro'=>$registro2,
                        'Frecuencia'=>$dataTabla2[$i][4],
                        'Power'=>$dataTabla2[$i][5],
                        'SNR'=>$dataTabla2[$i][6],
                        'Correctable'=>$dataTabla2[$i][7],
                        'Uncorrectable'=>$dataTabla2[$i][8]
                    ); 
                }
            }
        }
         
        $totalCorrectable = 0;
        foreach ($downstream as $item) {
            $totalCorrectable += $item['Correctable'];
        }

        $totalUncorrectable = 0;
        foreach ($downstream as $item) {
            $totalUncorrectable += $item['Uncorrectable'];
        }

        return array(
            "Upstream" => $upstream,
            "Downstream" => $downstream,
            "Correct" => $totalCorrectable,
            "UnCorrect" => $totalUncorrectable
        );

    }

    }


    function obtenerStatusCastlenet($codCliente,$ipaddress,$fabricante){

        $login = 'admin';

        $url_router="http://".$ipaddress;
        $url_status="http://".$ipaddress."/RgConnect.asp";

        $ingresarCablemodem = new CablemodemFunctions;
        $obtStatus = $ingresarCablemodem->getPageCastlenet1($url_status,$login,$codCliente);

        if($obtStatus=="Error"){
            return "Error";
        }else {

        $html = new simple_html_dom();
        $html->load($obtStatus);

        $dataTabla1 = array();
        $dataTabla2 = array();
        
        $tablaup = $html->find('table', 3);

        foreach($tablaup->find('tr') as $row1) {
            $rowData1 = array();
            foreach($row1->find('td') as $cell1) {
                $rowData1[] = $cell1->innertext;
            }
            $dataTabla1[] = $rowData1;
        }

        $tabladown = $html->find('table', 1);

        foreach($tabladown->find('tr') as $row2) {
            $rowData2 = array();
            foreach($row2->find('td') as $cell2) {
                $rowData2[] = $cell2->innertext;
            }
            $dataTabla2[] = $rowData2;
        }

        $upstream = array();
        $downstream = array();
        $registro1 = 0;
        $registro2 = 0;

        $cantidad1 = count($dataTabla1);
        $cantidad2 = count($dataTabla2);


        for ($i=0; $i < $cantidad1; $i++) { 
            if (count($dataTabla1[$i])>0){
                if ($dataTabla1[$i][2]<>"Unknown" and $dataTabla1[$i][2]<>"") {
                    $registro1 += 1;
                    $upstream[] = array(
                        'Registro'  => $registro1,
                        'Frecuencia'  => $dataTabla1[$i][5],
                        'Power'  => $dataTabla1[$i][6]
                    );
                    
                }
            }
        }

        for ($i=0; $i < $cantidad2; $i++) { 
            if (count($dataTabla2[$i])>0){
                if ($dataTabla2[$i][2]<>"") {
                    $registro2 += 1;
                    $downstream[] = array(
                        'Registro' => $registro2,
                        'Frecuencia' => $dataTabla2[$i][4],
                        'Power' => $dataTabla2[$i][5],
                        'SNR' => $dataTabla2[$i][6],
                        'Correctable' => $dataTabla2[$i][7],
                        'Uncorrectable' => $dataTabla2[$i][8]
                    );
                }
            }
        }

        $totalCorrectable = 0;
        foreach ($downstream as $item) {
            $totalCorrectable += $item['Correctable'];
        }

        $totalUncorrectable = 0;
        foreach ($downstream as $item) {
            $totalUncorrectable += $item['Uncorrectable'];
        }

        return array(
            "Upstream" => $upstream,
            "Downstream" => $downstream,
            "Correct" => $totalCorrectable,
            "UnCorrect" => $totalUncorrectable
        );

    }

    }


   
 



}

?>