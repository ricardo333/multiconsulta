<section class="text-center">
        <h3 class="text-center">Gestión de Decodificadores</h3>
        <div id="result_decos_send"></div>
        <div class="table-responsive tabla_result_decode_final"> 
            <table class="table table-bordered table-hover text-center w-auto m-auto">
                <thead>
                    <tr>
                        <th>OFIC</th>
                        <th>CODSRV</th>
                        <th>CASID</th>
                        <th>SERIE</th>
                        <th>TARJETA</th>
                        <th>EDO</th>
                        <th>ORDEN</th>
                        <th>Adquirido Como:</th>
                        <th>REFRESH</th>
                    </tr>
                </thead> 
                <tbody>
                    @forelse ($decos_cablemodems as $dec2)
                        <tr>
                            <td>{{$dec2->CODOFICADM}}</td>
                            <td>{{$dec2->CODSRV}}</td>
                            <td>{{$dec2->CASID}}</td>
                            <td>{{$dec2->SERIE}}</td>
                            <td>{{$dec2->SERIETARJ}}</td>
                            <td>{{$dec2->EDOCOMPXSR}}</td>
                            <td>{{$dec2->SECUENCIA}}</td>
                            <td>{{$dec2->tipoadqui}}</td>
                            <td>
                                <a href="javascript:void(0)" class="resetopentrama" data-uno="{{$dec2->CODSRV}}" 
                                        data-dos="{{$dec2->codmat}}" data-tres="{{$dec2->numser}}" data-cuatro="{{$codCliente}}">
                                    <img src="{{ url('/images/icons/multiconsulta/resetDecos.png') }}" alt="reset deco" title="reset decoder"/>
                                </a>
                                
                            </td>
                            @php
                                $codsrv=$dec2->CODSRV;
                            @endphp
                            
                        </tr> 
                    @empty 
                        <tr>
                            <td colspan="9"></td>
                        </tr>
                    @endforelse
                </tbody> 
             
            </table>
           
        </div>
            <a href="javascript:void(0)" id="resetallopentrama" data-uno="{{$codCliente}}" data-dos="{{$codsrv}}">
                <img src="{{ url('/images/icons/multiconsulta/resetAllDecodificadores.png') }}" alt="reset all deco" title="reset all decoder"/>
            </a>
 
</section>

