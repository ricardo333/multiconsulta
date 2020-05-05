<link rel="stylesheet" href="{{ url('/library/leaflet/leaflet.css')}}" /> 
 
<style>
    #mapid {
        width: 100%;
        /*height: 580px;*/
        height: 100%;
        position: relative;
        box-shadow: 5px 5px 5px #888;
        }
        .leaflet-control-attribution{
            display: none;
        }
        .info.legend {
            background: rgba(255, 255, 255, 0.88);
            padding: 5px;
            display: flex;
            flex-direction: column;
            justify-content: left;
            font-size: 12px;
            line-height: 15px;
        }
        .legend .img_legend {
            width: 13px;
            position: relative;
            top: 1px;
        }
    </style>
 

<section class="resultado_modal_general h-100">
     
   {{-- <section class="leyenda_mapa table-responsive" >
        <table class="table table-bordered table-hover">
            <tr>
                <td><img src="{{ url('/images/icons/maps/verde.png') }}"></td><td>Servicio Ok</td>
                <td><img src="{{ url('/images/icons/maps/ambar.png') }}"></td><td>Problemas de RF</td>
                <td><img src="{{ url('/images/icons/maps/rojo.png') }}"></td><td>Modem Offline</td>
                <td><img src="{{ url('/images/icons/maps/gris.png') }}"></td><td>Con Servicio Diagn. Incierto</td>
            </tr>
        </table>
    </section>--}}
    <div id="mapid"></div>
</section>
@php
    $js_array= json_encode($arrResultado);
    $js_tabs = json_encode($arrTap);
    $arrAmplif = json_encode($arrAmplif);
    $arrTroba = json_encode($arrTroba);
@endphp 
 
<script src="{{ url('/library/leaflet/leaflet.js')}}"></script>
<script>
    cargando_mapa();

    function cargando_mapa()
    { 
        //variables necesarias 
        var actuaciones = <?php echo $js_array; ?>;
        var idclientecrm = `{{$idclientecrm}}`;
        var promedioX = `{{$promedioX}}`;
        var promedioY = `{{$promedioY}}`; 

    var tap = <?php echo $js_tabs; ?>;
    var amplif = <?php echo $arrAmplif; ?>; 
    var troba = <?php echo $arrTroba; ?>; 

    var markerLocation = new L.LatLng(promedioY, promedioX);
    var mymap = L.map('mapid').setView(markerLocation, 16);

     

    // UBICACION w
    var marker = new Array();
    var imageX;

    var desdtt;
    var nom_via;
    var num_puer;
    //console.log("la cantidad de for es: ", actuaciones.length);
    let logitud_actuaciones =  actuaciones.length;
     if(logitud_actuaciones>0){
        for(var i = 0; i < logitud_actuaciones; i++){ //START CLIENTE
            //console.log(actuaciones[i]['IDCLIENTECRM']);
            
            desdtt = actuaciones[i]["desdtt"]
            nom_via = actuaciones[i]["nom_via"]
            num_puer = actuaciones[i]["num_puer"]

            if (actuaciones[i]["tipoed"]=='EDIFICIO'){
                //link="<a href='/cmts/edificio.php?desdtt="+desdtt+"&nom_via="+nom_via+"&num_puer="+num_puer+"' target='_blank'>Ver detalle de edificio </a></br>";
                link=`<a href="javascript:void(0)" class="btn btn-sm btn-outline-primary shadow-sm w-100 show_edificio_details"
                        data-desdtt="${desdtt}" data-nomvia="${nom_via}" data-numpuer="${num_puer}"
                        >Ver detalle de edificio </a></br>`;
                //link=""
            }else{ link="" }

            // Colores de Niveles
            //let color_RxPwrdBmv = (actuaciones[i]["RxPwrdBmv"]>5 || actuaciones[i]["RxPwrdBmv"]<4)?	"red" : "green"
           // let color_USPwr = (actuaciones[i]["USPwr"]<36 || actuaciones[i]["USPwr"]>56) ? "red" : "green"
           // let color_USMER_SNR = (actuaciones[i]["USMER_SNR"]<27) ? "red" : "green"
           // let color_DSPwr = (actuaciones[i]["DSPwr"]<-10 || actuaciones[i]["DSPwr"]>11) ? "red" : "green" 
           // let color_DSMER_SNR = (actuaciones[i]["DSMER_SNR"]<30) ?  "red" :  "green"

           // console.log("niveles señal son:",actuaciones[i]["coloresNivelesRuido"]);

            let contentString = "<div class='w-100'>"+link+"<table>";
            
            contentString = contentString+"<tr><td style='font-size: 11px; font-weight: bold;'>Cliente : </td><td style='font-size: 11px; font-weight: bold;'> "+actuaciones[i]["IDCLIENTECRM"]+" "+actuaciones[i]["nameclient"]+"</td> </tr>  ";
            //contentString = contentString+"<tr><td style='font-size: 11px;font-weight: bold;'>RxPwrd:</td><td style='font-size: 11px;font-weight: bold;color:"+actuaciones[i]["RxPwrdBmvBackground"]+";'>"+actuaciones[i]["RxPwrdBmv"]+"</td></tr>";
            contentString = contentString+"<tr><td style='font-size: 11px;font-weight: bold;'>PwrUP:</td><td style='font-size: 11px;font-weight: bold;color:"+actuaciones[i]["coloresNivelesRuido"]["UpPxColor"]+";background:"+actuaciones[i]["coloresNivelesRuido"]["UpPxBackground"]+"'>"+actuaciones[i]["USPwr"]+"</td></tr>";
            contentString = contentString+"<tr><td style='font-size: 11px;font-weight: bold;'>SnrUP:</td><td style='font-size: 11px;font-weight: bold;color:"+actuaciones[i]["coloresNivelesRuido"]["UpSnrColor"]+";background:"+actuaciones[i]["coloresNivelesRuido"]["UpSnrBackground"]+"'>"+actuaciones[i]["USMER_SNR"]+" </td></tr>";
            contentString = contentString+"<tr><td style='font-size: 11px;font-weight: bold;'>PwrDN:</td><td style='font-size: 11px;font-weight: bold;color:"+actuaciones[i]["coloresNivelesRuido"]["DownPxColor"]+";background:"+actuaciones[i]["coloresNivelesRuido"]["DownPxBackground"]+"'>"+actuaciones[i]["DSPwr"]+" </td></tr>";
            contentString = contentString+"<tr><td style='font-size: 11px;font-weight: bold;'>SnrDN:</td><td style='font-size: 11px;font-weight: bold;color:"+actuaciones[i]["coloresNivelesRuido"]["DownSnrColor"]+";background:"+actuaciones[i]["coloresNivelesRuido"]["DownSnrBackground"]+"'>"+actuaciones[i]["DSMER_SNR"]+" </td></tr>";
            contentString = contentString+"<tr><td style='font-size: 11px;font-weight: bold;'>Direc:</td><td>"+actuaciones[i]["direccion"]+" </td></tr>";
            contentString = contentString+"<tr><td style='font-size: 11px;font-weight: bold;'>Nodo:</td><td >"+actuaciones[i]["NODO"]+"</td></tr>";
            contentString = contentString+"<tr><td style='font-size: 11px;font-weight: bold;'>Troba:</td><td >"+actuaciones[i]["TROBA"]+"</td></tr>";
            contentString = contentString+"<tr><td style='font-size: 11px;font-weight: bold;'>Amplificador:</td><td>"+actuaciones[i]["amplificador"]+" </td></tr>";
            contentString = contentString+"<tr><td style='font-size: 11px;font-weight: bold;'>Tap:</td><td>"+actuaciones[i]["tap"]+" </td></tr>";
            contentString = contentString+"<tr><td style='font-size: 11px;font-weight: bold;'>Telf1:</td><td>"+actuaciones[i]["telf1"]+" </td></tr>";
            contentString = contentString+"<tr><td style='font-size: 11px;font-weight: bold;'>Telf2:</td><td>"+actuaciones[i]["telf2"]+" </td></tr>";
            contentString = contentString+"<tr><td style='font-size: 11px;font-weight: bold;'>Movil1:</td><td>"+actuaciones[i]["movil1"]+" </td></tr>";
            contentString = contentString+"<tr><td style='font-size: 11px;font-weight: bold;'>Macaddress:</td><td>"+actuaciones[i]["MACADDRESS"]+" </td></tr>";
            contentString = contentString+"<tr><td style='font-size: 11px;font-weight: bold;'>Velocidad:</td><td>"+actuaciones[i]["SERVICEPACKAGE"]+" </td></tr>"
            contentString = contentString+"</table></div>";

            let IconSizePer = [13, 13]
            if (parseInt(actuaciones[i]["estado"])== 0 && actuaciones[i]["tipoed"]=='CASA') {
                imageX = `{{ url('/images/icons/maps/rojo.png')}}`;
                IconSizePer = [9, 9]
            }
            if (parseInt(actuaciones[i]["estado"])==1 && actuaciones[i]["tipoed"]=='CASA'){
                imageX = `{{ url('/images/icons/maps/verde.png')}}`;
                IconSizePer = [9, 9]
            }
            if (parseInt(actuaciones[i]["estado"])==2 && actuaciones[i]["tipoed"]=='CASA'){
                imageX = `{{ url('/images/icons/maps/ambar.png')}}`;
                IconSizePer = [9, 9]
            }
            if (parseInt(actuaciones[i]["estado"])==3 && actuaciones[i]["tipoed"]=='CASA'){
                imageX = `{{ url('/images/icons/maps/gris.png')}}`
                IconSizePer = [9, 9]
            }
            if (parseInt(actuaciones[i]["estado"])==0 && actuaciones[i]["tipoed"]=='EDIFICIO'){
                imageX = `{{ url('/images/icons/maps/edificio_rojo.png')}}`
                IconSizePer = [16, 16]
            }
            if (parseInt(actuaciones[i]["estado"])==1 && actuaciones[i]["tipoed"]=='EDIFICIO'){
                imageX = `{{ url('/images/icons/maps/edificio_verde.png')}}`
                IconSizePer = [16, 16]
            }
            if (parseInt(actuaciones[i]["estado"])==2 && actuaciones[i]["tipoed"]=='EDIFICIO'){
                imageX = `{{ url('/images/icons/maps/edificio_ambar.png')}}`
                IconSizePer = [16, 16]
            }
            if (parseInt(actuaciones[i]["estado"])==3 && actuaciones[i]["tipoed"]=='EDIFICIO'){
                imageX = `{{ url('/images/icons/maps/edificio_gris.png')}}`
                IconSizePer = [16, 16]
            }
            if (actuaciones[i]["IDCLIENTECRM"]==idclientecrm){
                imageX = `{{ url('/images/icons/maps/cliente.png')}}`;
                IconSizePer = [15, 15] 
            }

                let cliente= L.icon({
                        iconUrl: imageX,
                        iconSize: IconSizePer // size of the icon
                    }); 
                
                let marcadorcliente = L.marker([actuaciones[i]["y"], actuaciones[i]["x"]], {icon: cliente});
                marker.push(marcadorcliente);
                marcadorcliente.bindPopup(contentString);
                mymap.addLayer(marcadorcliente);
                
            // L.marker([actuaciones[i]["y"], actuaciones[i]["x"]], {icon: cliente}).addTo(mymap);
            

            
        }
     }//END FOR CLIENTE

     //console.log("el marker esta con la data de: ", marker);
     //- UBICACION TAP
     //var amplifx;
     //var tapx;
     //var direccionx;

     let logitud_tap = tap.length
     if(logitud_tap>0){
        for (var i = 0; i < logitud_tap ; i++) { // START FOR TAP
            //amplifx = tap[i]["amplificador"];
            //tapx = tap[i]["tap"];
            //direccionx = tap[i]["amplificador"];

            // Colores de Niveles
            let contentStringtap = "";
            contentStringtap = "<div style='width: 350px'><table>";
            contentStringtap = contentStringtap+"<tr><td style='font-size: 11px;font-weight: bold;'>Direc:</td><td>"+tap[i]["direccion"]+" </td></tr>";
            contentStringtap = contentStringtap+"<tr><td style='font-size: 11px;font-weight: bold;'>Nodo:</td><td >"+tap[i]["nodo"]+"</td></tr>";
            contentStringtap = contentStringtap+"<tr><td style='font-size: 11px;font-weight: bold;'>Troba:</td><td >"+tap[i]["troba"]+"</td></tr>";
            contentStringtap = contentStringtap+"<tr><td style='font-size: 11px;font-weight: bold;'>Amplificador:</td><td>"+tap[i]["amplificador"]+" </td></tr>";
            contentStringtap = contentStringtap+"<tr><td style='font-size: 11px;font-weight: bold;'>Tap:</td><td>"+tap[i]["tap"]+" </td></tr>"
            contentStringtap = contentStringtap+"</table></div>";

            imageX = `{{ url('/images/icons/maps/tapx.ico')}}`;
            let tapimage= L.icon({
                        iconUrl: imageX,
                        iconSize: [13, 13] // size of the icon
                    });
            //console.log("el tap x es:",tap[i]["tap_x"]);
            let marcadortap = L.marker([tap[i]["tap_y"], tap[i]["tap_x"]], {icon: tapimage});
            marker.push(marcadortap);
            marcadortap.bindPopup(contentStringtap);
            mymap.addLayer(marcadortap);
        
        }
    }//END TAP FOR
     //// Fin TAP
     //console.log("el marker esta con la data de: ", marker);

     //-Inicio Amplificador
     let logitud_amplificador = amplif.length;
     if(logitud_amplificador>0){
        for (var i = 0; i < logitud_amplificador; i++) {
            // Colores de Niveles
            let contentStringamplif = "";
            contentStringamplif = "<div style='width: 350px'><table>";
            contentStringamplif = contentStringamplif+"<tr><td style='font-size: 11px;font-weight: bold;'>Nodo:</td><td >"+amplif[i]["nodo"]+"</td></tr>";
            contentStringamplif = contentStringamplif+"<tr><td style='font-size: 11px;font-weight: bold;'>Troba:</td><td >"+amplif[i]["troba"]+"</td></tr>";
            contentStringamplif = contentStringamplif+"<tr><td style='font-size: 11px;font-weight: bold;'>Amplificador:</td><td>"+amplif[i]["amplificador"]+" </td></tr>"
            contentStringamplif = contentStringamplif+"</table></div>";

            imageX = `{{ url('/images/icons/maps/amplificador.ico')}}`;
            let amplificadorimage= L.icon({
                        iconUrl: imageX,
                        iconSize: [16, 16] // size of the icon
                    });
            //console.log("el tap x es:",tap[i]["tap_x"]);
            let marcadoramplif = L.marker([amplif[i]["amplif_y"], amplif[i]["amplif_x"]], {icon: amplificadorimage});
            marker.push(marcadoramplif);
            marcadoramplif.bindPopup(contentStringamplif);
            mymap.addLayer(marcadoramplif);
        } 
     }
     //// Fin Amplificador

     //UBICACIÖN TROBA
     if(troba.length > 0){
        let contentStringtroba = "";
        contentStringtroba = "<div style='width: 350px'><table>";
        contentStringtroba = contentStringtroba+"<tr><td style='font-size: 11px;font-weight: bold;'>Nodo:</td><td >"+troba[0]["nodo"]+"</td></tr>";
        contentStringtroba = contentStringtroba+"<tr><td style='font-size: 11px;font-weight: bold;'>Troba:</td><td >"+troba[0]["troba"]+"</td></tr>"
        contentStringtroba = contentStringtroba+"</table></div>";
        
        imageX = `{{ url('/images/icons/maps/fuente.png')}}`;
        let trobaimage= L.icon({
                        iconUrl: imageX,
                        iconSize: [30, 30] // size of the icon
                    });
        let marcadortroba = L.marker([troba[0]["troba_y"], troba[0]["troba_x"]], {icon: trobaimage});
        marker.push(marcadortroba);
        marcadortroba.bindPopup(contentStringtroba);
        mymap.addLayer(marcadortroba);
     }
    

      
    L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    //attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://cloudmade.com">CloudMade</a>',
    maxZoom: 19
    }).addTo(mymap); 

    var legend = L.control({position: 'bottomright'}); 
       
    legend.onAdd = function (mymap) {
        let div = L.DomUtil.create('div','info legend'); 
        div.innerHTML = `<span><img class="img_legend" src="{{ url('/images/icons/maps/verde.png') }}"> Servicio Ok</span>`; 
        div.innerHTML += `<span><img class="img_legend" src="{{ url('/images/icons/maps/ambar.png') }}"> Problemas de RF</span>`; 
        div.innerHTML += `<span><img class="img_legend" src="{{ url('/images/icons/maps/rojo.png') }}"> Modem Offline</span>`; 
        div.innerHTML += `<span><img class="img_legend" src="{{ url('/images/icons/maps/gris.png') }}"> Con Servicio Diagn. Incierto</span>`; 
        return div;
      };
      legend.addTo(mymap);

    }
    
</script>
      
 