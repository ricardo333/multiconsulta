<div class="col-md-12 result-form-multi  p-0 mt-2 mx-auto">
     @if (count($catv) > 0)
        <div class="text-center text-danger font-weight-bold mb-2">DATOS CATV</div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover w-auto m-auto text-sm"> 
                    <tr>
                        <td class="bg-primary">ULT. REQ.:</td>
                        <td>{{$catv[0]->codreq}}</td>
                        <td class="bg-primary">EL DIA:</td>
                        <td>{{$catv[0]->fecha_liquidacion}}</td> 
                    </tr> 
                    <tr>
                        <td class="bg-primary">TPO_REQ:</td>
                        <td colspan="3">{{$catv[0]->codigo_tipo_req}} {{$catv[0]->codigo_motivo_req}} : {{$catv[0]->des_motivo}}</td>
                    </tr>
                    <tr>
                        <td colspan="4" class="bg-primary ">
                                Si el cliente reclama por Inst. de deco o Ctrl Rmto - Generar Rutina
                        </td>
                    </tr> 
                    <tr>
                       <td colspan="4" class="text-center">
                            @if(Auth::user()->HasPermiso('submodulo.multiconsulta.arbol-decisiones.view')) 
                                <a href="javascript:void(0)" id="arbolDecisiones" class="d-inline-block mx-1" data-uno="{{$catv[0]->imgArbol}}"
                                            data-dos="{{$catv[0]->codigo_del_cliente}}" data-tres="" data-cuatro=""
                                            data-cinco="inactivo">
                                    <img src="{{ asset('/images/icons/multiconsulta/arbol.png') }}" alt="Arbol Decisiones" title="Arbol Decisiones">
                                </a> 
                            @endif  
                       </td>
                    </tr>
            </table> 
        </div>
     @endif
</div>