
<table class="table table-bordered" id='table1'>
    <thead>
    <tr>
    <th scope="col">Host Name</th>
    <th scope="col">Interface</th>
    <th scope="col">MAC Address</th>
    <th scope="col">IP Address</th>
    <th scope="col">Niveles</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($ethernet as $ethe)
        <tr>
            <td>{{$ethe["host"]}}</td>
            <td>{{$ethe["interface"]}}</td>
            <td>{{$ethe["mac"]}}</td>
            <td>{{$ethe["ipaddress"]}}</td>
            <td></td>
        </tr>
    @endforeach
    @foreach ($wifi as $wi)
        <tr>
            <td>{{$wi["host"]}}</td>
            <td>{{$wi["interface"]}}</td>
            <td>{{$wi["mac"]}}</td>
            <td>{{$wi["ipaddress"]}}</td>
            <td style="color:{{$wi["color"]}}">{{$wi["nivel"]}}</td>
        </tr>
    @endforeach
    </tbody>
</table>



