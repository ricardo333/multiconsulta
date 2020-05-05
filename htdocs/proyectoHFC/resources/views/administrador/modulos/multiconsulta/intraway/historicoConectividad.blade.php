@if (count($intraway["cmLeases"]) > 0)
    <div class="table-responsive">
        <table class="table table-hover table-bordered w-100 text-center">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>IP Privada CM</th>
                    <th>MAC Address</th>
                </tr>
            </thead>
            <tbody> 
                @foreach ($intraway["cmLeases"] as $rowCM) 
                    <tr>
                        <td>{{$rowCM["fecha"]}}</td>
                        <td>{{$rowCM["ip"]}}</td>
                        <td>{{$rowCM["macaddress"]}}</td>
                    </tr>  
                    
                @endforeach
            </tbody>  
        </table>
    </div>
@endif

@if (count($intraway["cpeLeasesHistory"]) > 0)
    <div class="table-responsive">
        <table class="table table-hover table-bordered w-100 text-center">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>IP Pública</th>
                    <th>MAC Address</th>
                </tr>
            </thead>
            <tbody> 
                @foreach ($intraway["cpeLeasesHistory"] as $rowCPE) 
                    <tr>
                        <td>{{$rowCPE["fecha"]}}</td>
                        <td>{{$rowCPE["ip"]}}</td>
                        <td>{{$rowCPE["macaddress"]}}</td>
                    </tr>   
                @endforeach
            </tbody>  
        </table>
    </div>
@endif

