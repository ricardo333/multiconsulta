 
@php
     //dd(isset($cliente[0]));
@endphp
@if (isset($cliente[0]))
    <div class="table-responsive table-result-intraway-client" id="table-intraway-client-info">
        <table class="table table-bordered table-hover"> 
            <tr>
                <td>Cod. Cliente:</td>
                <td>{{$cliente[0]["idClienteCRM"]}}</td>
                <td>Nombre:</td>
                <td>{{$cliente[0]["Nombre"]}}</td>
            </tr>
        </table>
    </div>
@endif

@if (isset($cliente[0]["Docsis"]))
    @if (count($cliente[0]["Docsis"]) > 0)
        @forelse ($cliente[0]["Docsis"] as $docsis)
            <div class="table-responsive table-result-intraway-client" id="table-intraway-client-details">
                <table class="table table-bordered table-hover">
                
                    <tr>
                        <td colspan="7" class="text-uppercase font-weight-bold">{{$docsis["msgActivo"]}}</td>
                    </tr>
                    
                    <tr> 
                        <td rowspan="2"><img src="{{asset('/images/icons/intraway/cablemodem3.png')}}" alt='cablemodem' title='cablemodem' width="32" class="img" /></td>
                        <td>ID<br/> Servicio</td>
                        <td>Id Producto - Venta</td>
                        <td>Mac Address</td>
                        <td>Service Package</td>
                        <td>CM</td>
                        <td>CPE</td>
                    </tr>
                    <tr>
                        <td>{{$docsis["idServicio"]}}</td>
                        <td>{{$docsis["idDocsis"]}}</td>
                        <td>{{$docsis["Macaddress"]}}</td>
                        <td>{{$docsis["ServicePackage"]}}</td>
                        <td>{{$docsis["ispCM"]}}</td>
                        <td>{{$docsis["ispCPE"]}}</td>
                    </tr> 
                    
                    
                    @if (isset($cliente[0]["PacketCable"]))
                        @if (count($cliente[0]["PacketCable"]) > 1)
                            @forelse ($cliente[0]["PacketCable"] as $packets)
                                @php
                                    $endPoints = $packets["endPoints"];

                                    if ($docsis["idServicio"]=="1" ) {
                                        if ($packets["idVentaPadre"]==$docsis["idVenta"]) { 
                                            $idPacket = $packets["idProducto"];
                                        } 
                                    }
                                    else if ($docsis["idServicio"]=="2" ) {
                                        //echo $packets["idProductoPadre"]." ".$docsis["idProducto"];
                                        if ($packets["idProductoPadre"]==$docsis["idProducto"]) { 
                                            if ($packets["idProducto"]=='0')
                                                $idPacket = $packets["idVenta"];
                                            else
                                                $idPacket = $packets["idProducto"];
                                        } 
                                    }
                                @endphp
                                <tr>
                                    <td rowspan="2"><img src="{{asset('/images/icons/intraway/mta1.png')}}" alt='mta' title='mta' width="32" class="img"/></td>
                                    <td>ID<br/> Servicio</td>
                                    <td>Id Producto/Venta</td>
                                    <td>MacAddress</td>
                                    <td>Profile</td>
                                    <td colspan="2">Mta-Model</td>
                                </tr>
                                <tr>
                                    <td>{{$packets["idServicio"]}}</td>
                                    <td>{{$idPacket}}</td>
                                    <td>{{$packets["Macaddress"]}}</td>
                                    <td>{{$packets["mtaProfile"]}}</td>
                                    <td colspan="2">{{$packets["mtaModel"]}}</td>
                                </tr> 
                                @if (isset($endPoints[0])) 
                                    @php
                                        if ($endPoints[0]["idProducto"]=='0') {
                                            $idProductoEndpoint = $endPoints[0]["idVenta"];
                                        }
                                        else {
                                            $idProductoEndpoint = $endPoints[0]["idProducto"];
                                        }
                                    @endphp
                                    <tr>
                                        <td rowspan="2"> <img src="{{asset('/images/icons/intraway/phone2.png')}}" alt='endpoint' title='endpoint' width="32" class="img"/> </td>
                                        <td>Id<br/> Servicio</td>
                                        <td>Id Producto - Venta</td>
                                        <td>Telefono</td>
                                        <td>Profile</td>
                                        <td>Home Exchange</td>
                                        <td>Fecha Alta</td>
                                    </tr>
                                    <tr>
                                        <td>{{$endPoints[0]["idServicio"]}}</td>
                                        <td>{{$idProductoEndpoint}}</td>
                                        <td>{{$endPoints[0]["TN"]}}</td>
                                        <td>{{$endPoints[0]["epProfile"]}}</td>
                                        <td>{{$endPoints[0]["epHomeExchange"]}}</td>
                                        <td>{{$endPoints[0]["FechaAlta"]}}</td>	
                                    </tr>
                                @endif
                            
                            
                            @empty 
                            @endforelse 
                        @endif
                    @endif
                
                </table>
            </div>
        @empty
        <div class="col-12 text-center d-block">
            No se encontro cliente en intraway.
        </div>
        @endforelse
    @endif
  
@endif
 
{{-- Peticion  histórico de conectividad--}}
 
@if ($servicio != 0)
    <div class="col-12 d-block text-center"> 
        <a href="javascript:void(0)" class="btn btn-sm btn-outline-success shadow-sm" 
                data-serv="{{$servicio}}"
                data-prod="{{$producto}}" data-vent="{{$venta}}"
            id="verHistoricoConect"
            >
            Historico de Conectividad
        </a>
    </div> 
    <div class="col-12 mt-4 px-0" id="resultHistoricoConect">

    </div>
@endif
