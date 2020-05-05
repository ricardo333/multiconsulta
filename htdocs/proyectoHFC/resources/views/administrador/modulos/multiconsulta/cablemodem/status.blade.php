
<table class="table table-bordered" id="table1">
    <thead>
    <tr>
        <th scope="col">N° Registro</th>
        <th scope="col">Frecuencia</th>
        <th scope="col">Power</th>
    </tr>
    </thead>
    <tbody>
         
    @foreach ($upstream as $up)
        <tr>
            <th scope="row">{{$up["Registro"]}}</th>
            <td>{{$up["Frecuencia"]}}</td>
            @if ($up["Power"]<36 or $up["Power"]>56)
                <td style="color:red">{{$up["Power"]}}</td>
            @else
                <td style="color:green">{{$up["Power"]}}</td>
            @endif
        </tr>
    @endforeach
    </tbody>
</table>


<table class="table table-bordered" id="table2">
        <thead>
        <tr>
            <th scope="col">N° Registro</th>
            <th scope="col">Frecuencia</th>
            <th scope="col">SNR</th>
            <th scope="col">Power</th>
        </tr>
        <thead>
        <tbody>
        @foreach ($downstream as $down)
        <tr>
            <th scope="row">{{$down["Registro"]}}</th>
            <td>{{$down["Frecuencia"]}}</td>

            @if ($down["SNR"]<29)
                <td style="color:red">{{$down["SNR"]}}</td>
            @else
                <td style="color:green">{{$down["SNR"]}}</td>
            @endif

            @if ($down["Power"]<-5 or $down["Power"]>10)
                <td style="color:red">{{$down["Power"]}}</td>
            @else
                <td style="color:green">{{$down["Power"]}}</td>
            @endif
        </tr>
        @endforeach

        <tr><td>Total de Correct: </td><td>{{$correct}}</td></tr>
        <tr><td>Total de UnCorrect: </td><td>{{$uncorrect}}</td></tr>
        </tbody>
</table>

