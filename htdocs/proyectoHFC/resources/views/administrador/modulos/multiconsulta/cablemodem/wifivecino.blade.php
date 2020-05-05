<table class="table table-bordered" id="table1">
        <thead>
        <tr>
            <th>Network Name</th>
            <th>MAC Address</th>
            <th>Channel</th>
            <th>Bandwidth</th>
            <th>RSSI</th>
            <th>Seguridad</th>
        </tr>
        </thead>
        <tbody>
            @foreach ($wifivecino as $vecino)
                <tr>
                    <td>{{$vecino["Network"]}}</td>
                    <td>{{$vecino["Mac"]}}</td>
                    <td>{{$vecino["Chanel"]}}</td>
                    <td>{{$vecino["Bandwidth"]}}</td>
                    <td>{{$vecino["RSSI"]}}</td>
                    <td>{{$vecino["Seguridad"]}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    


    