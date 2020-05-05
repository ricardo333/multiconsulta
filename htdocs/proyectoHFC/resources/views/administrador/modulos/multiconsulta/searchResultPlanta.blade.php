<div class="col-md-12 result-form-multi  p-0 mt-2 mx-auto">
        @if (count($planta) > 0)
           <div class="text-center text-danger font-weight-bold mb-2 text-uppercase">{{$planta[0]->titulo}}</div>
           <div class="table-responsive">
               <table class="table table-bordered table-hover w-auto m-auto text-sm"> 
                   <thead>
                       <tr>
                           <th>Oficina</th>
                           <th>Cliente  </th>
                           <th>Nombre</th>
                           <th>Nodo</th>
                           <th>Troba</th>
                           <th>Distrito</th>
                       </tr>
                   </thead>
                   <tbody>
                       <tr>
                           <td>{{$planta[0]->ofi_cli}}</td>
                           <td>{{$planta[0]->cliente}}</td>
                           <td>{{$planta[0]->nomcli}}</td>
                           <td>{{$planta[0]->NODO}}</td>
                           <td>{{$planta[0]->plano}}</td>
                           <td>{{$planta[0]->desdtt}}</td>
                       </tr>
                   </tbody> 
               </table> 
           </div>
        @endif
   </div>
<div class="col-md-12 result-form-multi  p-0 mt-2 mx-auto">
        @if (count($planta) > 0)
         
           <div class="table-responsive">
               <table class="table table-bordered table-hover w-auto m-auto text-sm"> 
                   <thead>
                       <tr>
                            <th style="color:{{$planta[0]->colorDigi}};background:{{$planta[0]->backgroundDigi}}">Dato</th>
                            <th style="color:{{$planta[0]->colorDigi}};background:{{$planta[0]->backgroundDigi}}">Ultimo Requerimiento </th>
                       </tr>
                   </thead>
                   <tbody>
                       <tr>
                           <td style="color:{{$planta[0]->colorDigi}};background:{{$planta[0]->backgroundDigi}}">{{$planta[0]->mensajeDigital}}</td> 
                       </tr>
                       <tr>
                           <td >{{$planta[0]->mensajeMasiva}}</td> 
                           <td >{{$planta[0]->mensajeNumeroMasiva}}</td> 
                       </tr> 
                   </tbody> 
               </table> 
           </div>
           <div class="row justify-content-center mx-0 m-auto px-0">
                @if(Auth::user()->HasPermiso('submodulo.multiconsulta.arbol-decisiones.view')) 
                    <a href="javascript:void(0)" id="arbolDecisiones" class="d-inline-block mx-1" data-uno="{{$planta[0]->imgArbol}}"
                                data-dos="{{$planta[0]->cliente}}" data-tres="" data-cuatro=""
                                data-cinco="inactivo">
                        <img src="{{ asset('/images/icons/multiconsulta/arbol.png') }}" alt="Arbol Decisiones" title="Arbol Decisiones">
                    </a> 
                @endif 
           </div>
        @endif
   </div>