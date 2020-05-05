<div class="col-md-12 result-form-multi  p-0 mt-2 mx-auto">
        @if (count($adsl) > 0)
        <div class="text-center text-danger font-weight-bold mb-2">Presentando Datos de ADSL</div>
           <div class="table-responsive">
               <table class="table table-bordered table-hover w-auto m-auto text-sm"> 
                   <thead>
                        <tr style="background:orange">
                            <th>ZONAL</th>
                            <th>TIPO_ADSL</th>
                            <th>INSCRIPCION</th>
                            <th>CLIENTE</th>
                            <th>MDF</th>
                            <th>CABEC</th>
                            <th>TIPO</th>
                            <th>PRODUCTO COMERCIAL</th>
                        </tr>
                   </thead>
                   <tbody>
                        <tr>
                                <td> {{ $adsl[0]->ZONAL}}</td>
                                <td> {{ $adsl[0]->SUB_NEG}}</td>
                                <td> {{ $adsl[0]->INSCRIPCIO}}</td>
                                <td> {{ $adsl[0]->APPATER }} {{ $adsl[0]->APMATER }} {{ $adsl[0]->NOMBRE }}</td>
                                <td> {{ $adsl[0]->MDF}}</td>
                                <td> {{ $adsl[0]->CABEC}}</td>
                                <td> {{ $adsl[0]->TIPO}}</td>
                                <td> {{ $adsl[0]->PRODCOMER}}</td>
                        </tr>
                   </tbody> 
               </table> 
           </div>
           <div class="row justify-content-center mx-0 m-auto px-0">
                @if(Auth::user()->HasPermiso('submodulo.multiconsulta.arbol-decisiones.view')) 
                    <a href="javascript:void(0)" id="arbolDecisiones" class="d-inline-block mx-1" data-uno="{{$adsl[0]->imgArbol}}"
                                data-dos="{{$adsl[0]->FEACTS}}" data-tres="" data-cuatro=""
                                data-cinco="inactivo">
                        <img src="{{ asset('/images/icons/multiconsulta/arbol.png') }}" alt="Arbol Decisiones" title="Arbol Decisiones">
                    </a> 
                @endif 
           </div>
        @endif
   </div>