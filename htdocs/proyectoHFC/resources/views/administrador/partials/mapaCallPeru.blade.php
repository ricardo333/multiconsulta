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
            width: 9px;
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
        var dataMapaCall = <?php echo $arrayDataMapaCall; ?>; //dataMapaCall
        var promCallX = `{{$promedioX}}`;
        var promCallY = `{{$promedioY}}`; 
        var clienteActivo = `{{$cliente}}`;
        var vjefatura = `{{$vjefatura}}`;
       //console.log("La data es: ",dataMapaCall)
       //console.log("La clienteActivo es: ",clienteActivo)
       //console.log("La vjefatura es: ",vjefatura)
       //console.log("los promedios wx e Y son: ",promCallX,promCallY)
 
        var markerLocationMapCall = new L.LatLng(promCallY, promCallX);
        var myMapCall = L.map('mapaCall').setView(markerLocationMapCall, vjefatura);
 
        // UBICACION w
        var markerMapaCall = new Array();
        var imageMapaCallX;
    
        let logitud_data_mapa_call =  dataMapaCall.length;
        if(logitud_data_mapa_call>0){
            for(var i = 0; i < logitud_data_mapa_call; i++){ //START CLIENTE
                //console.log(dataMapaCall[i]['IDCLIENTECRM']);
                
            // Colores de Niveles
                let color_call= "black";
                    
                let contentString = "<table>";
                
                contentString = contentString+"<tr><td style='font-size: 11px; font-weight: bold;'>Cliente : </td><td style='font-size: 11px; font-weight: bold;'> "+dataMapaCall[i]["cliente"]+" "+dataMapaCall[i]["nombre"]+"</td> </tr>  ";
                //contentString = contentString+"<tr><td style='font-size: 11px;font-weight: bold;'>RxPwrd:</td><td style='font-size: 11px;font-weight: bold;color:"+dataMapaCall[i]["RxPwrdBmvBackground"]+";'>"+dataMapaCall[i]["RxPwrdBmv"]+"</td></tr>";
                contentString = contentString+"<tr><td style='font-size: 11px;font-weight: bold;'>Servicio:</td><td style='font-size: 11px;font-weight: bold;color:"+color_call+";'>"+dataMapaCall[i]["servicio"]+"</td></tr>";
                contentString = contentString+"<tr><td style='font-size: 11px;font-weight: bold;'>Nodo:</td><td style='font-size: 11px;font-weight: bold;color:"+color_call+";'>"+dataMapaCall[i]["nodo"]+"</td></tr>";
                contentString = contentString+"<tr><td style='font-size: 11px;font-weight: bold;'>Troba:</td><td style='font-size: 11px;font-weight: bold;color:"+color_call+";'>"+dataMapaCall[i]["troba"]+"</td></tr>";
                contentString = contentString+"<tr><td style='font-size: 11px;font-weight: bold;'>Dirección:</td><td style='font-size: 11px;font-weight: bold;color:"+color_call+";'>"+dataMapaCall[i]["direc_inst"]+"</td></tr>";
                //contentString = contentString+"<tr><td style='font-size: 11px;font-weight: bold;'>Direccion:</td><td style='font-size: 11px;font-weight: bold;color:"+color_call+";'>"+dataMapaCall[i]["tiptec"]+"</td></tr>";
                contentString = contentString+"<tr><td style='font-size: 11px;font-weight: bold;'>Tipo Servicio:</td><td style='font-size: 11px;font-weight: bold;color:"+color_call+";'>"+dataMapaCall[i]["tiptec"]+"</td></tr>";
                contentString = contentString+"<tr><td style='font-size: 11px;font-weight: bold;'>Amplificador:</td><td style='font-size: 11px;font-weight: bold;color:"+color_call+";'>"+dataMapaCall[i]["codlex"]+"</td></tr>";
                contentString = contentString+"<tr><td style='font-size: 11px;font-weight: bold;'>Tap:</td><td style='font-size: 11px;font-weight: bold;color:"+color_call+";'>"+dataMapaCall[i]["codtap"]+"</td></tr>";

                if (dataMapaCall[i]["coloresNivelesActivo"]) {
                    contentString = contentString+"<tr><td style='font-size: 11px;font-weight: bold;'>SnrDN:</td><td style='font-size: 11px;font-weight: bold;color:"+dataMapaCall[i]["coloresNivelesRuido"].DownSnrBackground+";'>"+dataMapaCall[i]["SnrDN"]+"</td></tr>";
                    contentString = contentString+"<tr><td style='font-size: 11px;font-weight: bold;'>DSPwr:</td><td style='font-size: 11px;font-weight: bold;color:"+dataMapaCall[i]["coloresNivelesRuido"].DownPxBackground+";'>"+dataMapaCall[i]["DSPwr"]+"</td></tr>";
                    contentString = contentString+"<tr><td style='font-size: 11px;font-weight: bold;'>SnrUP:</td><td style='font-size: 11px;font-weight: bold;color:"+dataMapaCall[i]["coloresNivelesRuido"].UpSnrBackground+";'>"+dataMapaCall[i]["SnrUP"]+"</td></tr>";
                    contentString = contentString+"<tr><td style='font-size: 11px;font-weight: bold;'>USPwr:</td><td style='font-size: 11px;font-weight: bold;color:"+dataMapaCall[i]["coloresNivelesRuido"].UpPxBackground+";'>"+dataMapaCall[i]["USPwr"]+"</td></tr>";
                }

                if(parseInt(dataMapaCall[i]["cant_reit"])>0){
					contentString = contentString+"<tr><td style='font-size: 11px;font-weight: bold; color: red;'>C.Dias/C.Llam:</td><td style='font-size:11px;font-weight:  bold;color: red;'>"+dataMapaCall[i]["cant_reit"]+"/"+dataMapaCall[i]["tdia"]+" </td></tr>";
				}
				if(dataMapaCall[i]["interface"] != null){
                    if (dataMapaCall[i]["cmts"] != "" && dataMapaCall[i]["cmts"] != null && dataMapaCall[i]["interface"] != "" && dataMapaCall[i]["interface"] != "") {
                        contentString = contentString+"<tr><td colspan=2 style='font-size: 11px;font-weight: bold; color: blue;'><a href='javascript:void(0)' id='verHistoricoNivelesPorPuerto' data-uno='"+dataMapaCall[i]["cmts"]+dataMapaCall[i]["interface"]+"'>Historico de niveles</a></td></tr>";
                    }
				}else{
					 contentString = contentString+"<tr><td colspan=2 style='font-size: 11px;font-weight: bold; color: blue;'>Por favor revise en CMS el historial de averias de este Cliente</td></tr>";
				}

                contentString = contentString+"</table>";

                let imageMapaCallX=`{{ url('/images/icons/maps')}}/`+dataMapaCall[i]["color"];

               // imageMapaCallX = `{{ url('/images/icons/maps/punto.png')}}`;
                let IconSizePer = [6, 6]

            

                let cliente= L.icon({
                        iconUrl: imageMapaCallX,
                      //  iconSize: IconSizePer // size of the icon
                    }); 
                
                let marcadorcliente = L.marker([dataMapaCall[i]["coordY"], dataMapaCall[i]["coordX"]], {icon: cliente});
                markerMapaCall.push(marcadorcliente);
                marcadorcliente.bindPopup(contentString);
                myMapCall.addLayer(marcadorcliente);
                     
            }
        }//END FOR CLIENTE

        
        L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        //attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://cloudmade.com">CloudMade</a>',
        maxZoom: 100
        }).addTo(myMapCall); 

        var legend = L.control({position: 'bottomright'}); 
       
        legend.onAdd = function (myMapCall) {
            let div = L.DomUtil.create('div','info legend'); 
            div.innerHTML = `<span><img class="img_legend" src="{{ url('/images/icons/maps/puntorojo.png') }}"> Masiva</span>`; 
            div.innerHTML += `<span><img class="img_legend" src="{{ url('/images/icons/maps/puntoazul.png') }}"> Catv/Gpon</span>`; 
            div.innerHTML += `<span><img class="img_legend" src="{{ url('/images/icons/maps/puntoverde.png') }}"> Online</span>`; 
            div.innerHTML += `<span><img class="img_legend" src="{{ url('/images/icons/maps/puntomorado.png') }}"> Off</span>`; 
            div.innerHTML += `<span><img class="img_legend" src="{{ url('/images/icons/maps/puntoambar.png') }}"> Pext</span>`; 
            div.innerHTML += `<span><img class="img_legend" src="{{ url('/images/icons/maps/puntorosado.png') }}"> RdC</span>`; 
            div.innerHTML += `<span><img class="img_legend" src="{{ url('/images/icons/maps/call_reiterada.png') }}"> Reit.15 dias</span>`; 
            return div;
        };
        legend.addTo(myMapCall);

        var legendFilter = L.control({position: 'topright'}); 
       
        legendFilter.onAdd = function (myMapCall) {
            let divFiltro = L.DomUtil.create('div','Filtros'); 
            divFiltro.innerHTML = `<span><a href="javascript:void(0)" class="btn btn-sm btn-outline-primary shadow-sm" id="activarFiltroMapaCallPeru">Filtrar <i class="icofont-filter icofont-md"></i></a></span>`; 
            //div.innerHTML += `<span><img class="img_legend" src="{{ url('/images/icons/maps/puntoazul.png') }}"> Catv/Gpon</span>`; 
            //div.innerHTML += `<span><img class="img_legend" src="{{ url('/images/icons/maps/puntoverde.png') }}"> Online</span>`; 
            //div.innerHTML += `<span><img class="img_legend" src="{{ url('/images/icons/maps/puntomorado.png') }}"> Off</span>`; 
            //div.innerHTML += `<span><img class="img_legend" src="{{ url('/images/icons/maps/puntoambar.png') }}"> Pext</span>`; 
            //div.innerHTML += `<span><img class="img_legend" src="{{ url('/images/icons/maps/puntorosado.png') }}"> RdC</span>`; 
            //div.innerHTML += `<span><img class="img_legend" src="{{ url('/images/icons/maps/call_reiterada.png') }}"> Reit.15 dias</span>`; 
            return divFiltro;
        };
        legendFilter.addTo(myMapCall);

    
    }
    
</script>
      
 