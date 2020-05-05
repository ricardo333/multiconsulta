<link rel="stylesheet" href="{{ url('/library/leaflet/leaflet.css')}}" /> 
 
<style>
    #mapaCall {
        width: 100%;
        /*height: 580px; */
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
      
    <div id="mapaCall"></div>
</section>
@php
    $arrayDataMapaCall = json_encode($arrResultado); 
   
@endphp 
 
<script src="{{ url('/library/leaflet/leaflet.js')}}"></script>
<script>
    cargando_mapa_call();

    function cargando_mapa_call()
    { 
        //variables necesarias 
        var dataMapaCall = <?php echo $arrayDataMapaCall; ?>; //actuaciones
        var promCallX = `{{$promedioX}}`;
        var promCallY = `{{$promedioY}}`; 
 
        var markerLocationMapCall = new L.LatLng(promCallY, promCallX);
        var myMapCall = L.map('mapaCall').setView(markerLocationMapCall, 16);
 
        // UBICACION w
        var markerMapaCall = new Array();
        var imageMapaCallX;
    
        let logitud_data_mapa_call =  dataMapaCall.length;
        if(logitud_data_mapa_call>0){
            for(var i = 0; i < logitud_data_mapa_call; i++){ //START CLIENTE
                //console.log(actuaciones[i]['IDCLIENTECRM']);
                
            // Colores de Niveles
                let color_call= "black";
                    
                let contentString = "<table>";
                
                contentString = contentString+"<tr><td style='font-size: 11px; font-weight: bold;'>Cliente : </td><td style='font-size: 11px; font-weight: bold;'> "+dataMapaCall[i]["cliente"]+" "+dataMapaCall[i]["nombre"]+"</td> </tr>  ";
                //contentString = contentString+"<tr><td style='font-size: 11px;font-weight: bold;'>RxPwrd:</td><td style='font-size: 11px;font-weight: bold;color:"+dataMapaCall[i]["RxPwrdBmvBackground"]+";'>"+dataMapaCall[i]["RxPwrdBmv"]+"</td></tr>";
                contentString = contentString+"<tr><td style='font-size: 11px;font-weight: bold;'>Servicio:</td><td style='font-size: 11px;font-weight: bold;color:"+color_call+";'>"+dataMapaCall[i]["direcinst"]+"</td></tr>";
                contentString = contentString+"<tr><td style='font-size: 11px;font-weight: bold;'>Direccion:</td><td style='font-size: 11px;font-weight: bold;color:"+color_call+";'>"+dataMapaCall[i]["tiptec"]+"</td></tr>";
                contentString = contentString+"<tr><td style='font-size: 11px;font-weight: bold;'>Tipo Servicio:</td><td style='font-size: 11px;font-weight: bold;color:"+color_call+";'>"+dataMapaCall[i]["servicio"]+"</td></tr>";
                contentString = contentString+"<tr><td style='font-size: 11px;font-weight: bold;'>Amplificador:</td><td style='font-size: 11px;font-weight: bold;color:"+color_call+";'>"+dataMapaCall[i]["codlex"]+"</td></tr>";
                contentString = contentString+"<tr><td style='font-size: 11px;font-weight: bold;'>Tap:</td><td style='font-size: 11px;font-weight: bold;color:"+color_call+";'>"+dataMapaCall[i]["codtap"]+"</td></tr>";
                contentString = contentString+"</table></div>";

                imageMapaCallX = `{{ url('/images/icons/maps/punto.png')}}`;
                let IconSizePer = [6, 6]

            

                let cliente= L.icon({
                        iconUrl: imageMapaCallX,
                        iconSize: IconSizePer // size of the icon
                    }); 
                
                let marcadorcliente = L.marker([dataMapaCall[i]["coordY"], dataMapaCall[i]["coordX"]], {icon: cliente});
                markerMapaCall.push(marcadorcliente);
                marcadorcliente.bindPopup(contentString);
                myMapCall.addLayer(marcadorcliente);
                     
            }
        }//END FOR CLIENTE

        
        L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        //attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://cloudmade.com">CloudMade</a>',
        maxZoom: 19
        }).addTo(myMapCall); 

    
    }
    
</script>
      
 