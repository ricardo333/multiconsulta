<div class="col-md-12 result-form-multi  p-0 mt-2 mx-auto">

    @forelse ($resultadoMulti as $item)

        @if ($item->obsoleto == "SI" && $item->rol<> "CALL")
            <div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                CAMBIO DE MODEM POR OBSOLESCENCIA 
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button></div>
        @endif
        @if ((int)$item->esTrabProg == 1 && $item->mensajeDigital <> "")
            @if ((int)$item->num_masiva > 0)
                <div class="container text-center font-weight-bold alert alert-danger fade show" role="alert" style="background:{{$item->averiasBackground}}; color:{{$item->averiasColor}}"> 
                        Averia Num: {{$item->num_masiva}}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div> 
            @else
                
            @endif
        @endif
        @if ((int)$item->esMasiva == 1 && $item->resultadoAlerta <> "")
            <div class="container text-center font-weight-bold alert alert-danger fade show" role="alert" style="background:{{$item->averiasBackground}}; color:{{$item->averiasColor}}"> 
                {!!$item->resultadoAlerta!!} 
                {{--<a href="javascript:void(0)" class="btn btn-sm shadow-sm text-danger text-decoration-none" data-dismiss="alert" aria-label="Close">X</a>--}}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
            </div>
        @endif

        <div class="container text-center font-weight-bold"> 
                <a href="javascript: void(0)" target='blank' id="btnconsultascall" class="btnconsultascall btn btn-outline-secondary btn-sm shadow-sm"  data-toggle="modal"  data-target="#show_consultascall"> 
                    Consultas en Call día de hoy: {{ $item->cantidadConsultas }}
                </a>
                
        </div>
        <div class="info_search ">
            <div class="details_result_info text-center"> 
                @if($item->naked == 'INTERNET NAKED ' || $item->naked == 'VOIP')
                    <span class="campo text-danger mr-1 ml-1">VOIP NAKED</span>
                @endif 
            <span class="campo text-danger mr-1 ml-1">{{$item->msjSegmento}}  {{$item->msjNegocio}}</span>
            </div>
        </div>
        <div class="table-responsive tabla-multiconsulta-content">
                <table class="table tabla-multiconsulta">
                        <tr>
                            <td> IdServicio:</td>
                            <td> {{ $item->idservicio }} </td>
                            <td> IdProductoCM:</td>
                            <td> {{ $item->idproducto }} </td>
                            <td> IdVenta:</td>
                            <td> {{ $item->idventa }} </td>
                            <td> IdProductoMTA:</td>
                            <td> {{ $item->idproductomta }} </td>
                        </tr>
                        <tr>
                            <td> Cod. Cliente:</td>
                            <td> {{$item->IDCLIENTECRM}} </td>
                            <td> Nombre Cliente:</td>
                            <td> {{$item->Nombre}} </td>
                            <td> Telefono 1:</td>
                            <td> {{$item->telf1}} </td>
                            <td> Telefono 2:</td>
                            <td> {{$item->telf2}} </td>
                        </tr>
                        <tr>
                            <td> Nodo Troba:</td>
                            <td> {{$item->NODO}}-{{ $item->TROBA }} </td>
                            <td> Interface:</td>
                                    <td class="celda_res"> 
                                        @if($item->playa  <> '')
                                            <b>PLAYAS</b > 
                                        @endif
                                        <a href="javascript:void(0)" id="verhistoricoRuidoInterfaz" class="text-primary" 
                                            data-uno="{{$item->cmts}}{{$item->interface}}">
                                                {{$item->interface}} 
                                        </a>
                                    </td> 
                            <td> CMTS:</td>
                            <td> {{$item->cmts}} </td>
                           {{-- <td> TelefonoHFC:</td>
                            <td> {{$item->telefonohfc}} </td> --}}
                            <td>Telf-Contacto:</td>
                            <td> 
                                {{ $item->tmtelef1 == 0 ? "" : $item->tmtelef1}}<br>
                                {{ $item->tmtelef2 == 0 ? "" : $item->tmtelef2}}<br>                               
                                {{ $item->tmtelef3 == 0 ? "" : $item->tmtelef3}}<br>
                                <a href="javascript:void(0)" id="storeUpdateATelefonos" 
                                    data-uno="{{$item->tmtelef1}}" data-dos="{{$item->tmtelef2}}" 
                                    data-tres="{{$item->tmtelef3}}" data-cuatro="{{$item->IDCLIENTECRM}}">
                                    <img src="{{asset('/images/icons/addtelf.png')}}" alt="Telefono" title="Telefono">
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td> Paquete Activo:</td>
                            <td> {{$item->SERVICEPACKAGE}} </td>
                            <td> IP Address:</td>
                            <td>  {{$item->IPAddress}} </a></td>
                            <td> Mac Address:</td>
                            <td> {{$item->MACADDRESS}} </td>
                            <td> Bonding:</td>
                            <td>{{$item->bondingCli}}</td> 
                        </tr>
                        <tr>
                            <td> SNR_DOWN:</td>
                                
                            <td style="background:{{ $item->coloresNivelesRuido['DownSnrBackground'] }}; color:{{ $item->coloresNivelesRuido['DownSnrColor']}}"> 
                                    @if ($item->MACState == "online" && ( (double) $item->nivelesRuido['downSnr'] > 0)) 
                                        {{ (double) $item->nivelesRuido['downSnr'] }}
                                    @endif
                            </td>
                            <td> POWER_DOWN:</td>
                            <td style="background:{{$item->coloresNivelesRuido['DownPxBackground']}}; color:{{ $item->coloresNivelesRuido['DownPxColor']}}">
                                @if ($item->MACState == "online" && ( (double) $item->nivelesRuido['downPx'] > 0 || (double) $item->nivelesRuido['downPx'] < 0))
                                    {{$item->nivelesRuido['downPx']}}
                                @endif 
                            </td> 
                                
                            <td> SNR_UP:</td>
                            <td  style="background:{{$item->coloresNivelesRuido['UpSnrBackground']}}; color:{{ $item->coloresNivelesRuido['UpSnrColor']}}">
                                
                                @if($item->MACState == "online" && ( (double) $item->nivelesRuido['upSnr'] > 0))
                                        {{$item->nivelesRuido['upSnr'] * 1}}
                                @endif
                                
                            </td>
                            <td> POWER_UP:</td>
                            <td  style="background:{{ $item->coloresNivelesRuido['UpPxBackground'] }}; color:{{ $item->coloresNivelesRuido['UpPxColor']}}">
                                @if ($item->MACState == "online" && ( (double) $item->nivelesRuido['upPx'] > 0))
                                    {{$item->nivelesRuido['upPx'] * 1}}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Macstate:</td>
                            <td style="background:{{$item->MacStateBackground}}; color:{{$item->MacStateColor}}">{{ $item->MACState  }}</td>
                            <td>Fabricante:</td>
                            <td>{{ $item->Fabricante }}</td>
                            <td>Modelo:</td>
                            <td>{{ $item->Modelo }}</td>
                            <td>Firmware:</td>
                            <td>{{ $item->Version_firmware }}</td>
                        </tr>
                        <tr>
                            <td>Docsis:</td>
                            <td>
                                @if($item->docsis=='DOCSIS2')
                                    {{$item->docsis}}{{$item->cambiarModem}}
                                @else
                                    {{$item->docsis}}
                                @endif
                            </td>
                            <td>Voip:</td>
                            <td colspan="3" style="background-color:{{$item->voipBackground}}; color:{{$item->voipColor}}">
                                {{$item->voip}} {{$item->telefonohfc}}
                            </td> 
                            @if ($item->scopesgroup == "CPE-CGNAT")
                                <td>ISP CPE</td>
                                <td class="text-center font-weight-bold" style="background:{{$item->scopesgroupBackground}}; color: {{$item->scopesgroupColor}}">CGNAT</td>
                            @endif 
                        </tr>
                      
                        <tr>
                            @if (trim($item->otrasAverias) <> "")
                                <td >Mensajes al operador:</td>
                                <td colspan="3" class="text-center font-weight-bold" style="background:{{$item->averiasBackground}}; color:{{$item->averiasColor}}">
                                    <div class="d-block latidos">{!!$item->otrasAverias!!}</div>
                                </td>
                            @endif
                            @if (trim($item->msjPlazoAtencion) <> "")
                                
                                <td >Atención Técnica:</td>
                                <td colspan="7" class="text-center font-weight-bold" style="background:{{$item->plazoAtencionBackground}}; color:{{$item->plazoAtencionColor}}">{{$item->msjPlazoAtencion}}</td>
                            
                            @endif

                        </tr>
                        @if (trim($item->msjOperador) <> "")
                        <tr>
                                <td >Mensaje Operador:</td>
                                <td colspan="7" class="text-center font-weight-bold" style="background:{{$item->msjOperadorBackground}}; color:{{$item->msjOperadorColor}}">{{$item->msjOperador}}</td>
                        </tr>
                        @endif
                        <tr>
                            <td colspan="8" style="Background:#fff;" class="text-center text-primary">
                                @if(Auth::user()->HasPermiso('submodulo.multiconsulta.mapa.view')) 
                                    <a href="javascript: void(0)" class="btn btn-outline-primary btn-sm shadow-sm" 
                                        id="detalle_mapa" 
                                        data-n="{{$item->NODO}}" data-t="{{$item->TROBA}}" data-cod="{{$item->IDCLIENTECRM}}">
                                        Ver mapa <i class="fa fa-map-marker" aria-hidden="true"></i>
                                    </a>
                                    <script src="{{asset('js/sistema/modulos/multiconsulta/mapa.min.js')}}"></script>
                                @endif
                                @if(Auth::user()->HasPermiso('submodulo.multiconsulta.intraway.view')) 
                                    <a href="javascript: void(0)" class="btn btn-outline-primary btn-sm shadow-sm" 
                                        id="detalle_intraway" 
                                        data-cod="{{$item->IDCLIENTECRM}}" data-serv="{{$item->idservicio}}"
                                        data-prod="{{$item->idproducto}}" data-vent="{{$item->idventa}}">
                                        Datos Intraway <i class="fa fa-server"></i>
                                    </a>   
                                @endif 
                                @if ($item->MACState == "online")
                                    @if ($item->Fabricante=="Askey" || $item->Fabricante=="Ubee" || substr($item->Fabricante,0,3)=="Hit" || substr($item->Fabricante,0,9)=="CastleNet" || substr($item->Fabricante,0,5)=="SAGEM" || substr($item->Fabricante,0,6)=="Telefo")
                                        {{--Boton Cablemodem--}}
                                        @if(Auth::user()->HasPermiso('submodulo.multiconsulta.cm.view')) 
                                            <a href="javascript: void(0)" class="btn btn-outline-primary btn-sm shadow-sm show_cablemodem" 
                                                data-toggle="modal" id="detalle_cablemodem" data-target="#show_cablemodem"
                                                data-cod="{{$item->IDCLIENTECRM}}" data-serv="{{$item->idservicio}}" 
                                                data-prod="{{$item->idproducto}}" data-vent="{{$item->idventa}}" 
                                                data-ip="{{$item->IPAddress}}" data-mac="{{$item->MACADDRESS}}" data-fb="{{$item->Fabricante}}" 
                                                data-mo="{{$item->Modelo}}" data-firm="{{$item->Version_firmware}}">
                                                Cable Modem <i class="fas fa-map-marked-alt"></i>
                                            </a>
                                        @endif
                                    @endif
                                @endif 

                                @if(Auth::user()->HasPermiso('submodulo.multiconsulta.diagnostico-masivo.view')) 
                                    @if ($item->NODO <> "" && $item->TROBA<> "")
                                        <a href="javascript: void(0)" class="btn btn-outline-primary btn-sm shadow-sm" 
                                            id="diagnostico_masivo" 
                                            data-n="{{$item->NODO}}" data-t="{{$item->TROBA}}">
                                            Diagnostico Masivo <i class="icofont-repair"></i>
                                        </a> 
                                    @endif
                                @endif
                                @if(Auth::user()->HasPermiso('submodulo.multiconsulta.agenda.view'))
                                    @if ($item->verAgenda == 1)
                                        <a href="javascript: void(0)" class="btn btn-outline-primary btn-sm shadow-sm" 
                                            id="preAgendaMulti" 
                                                data-uno="{{$item->IDCLIENTECRM}}" data-dos="2" data-tres="{{$item->NODO}}">
                                            Agenda <i class="icofont-calendar"></i>
                                        </a> 
                                    @endif
                                @endif
                                @if(Auth::user()->HasPermiso('submodulo.multiconsulta.historico-masivo-trobas.view')) 
                                    <a href="javascript: void(0)" class="btn btn-outline-primary btn-sm shadow-sm" 
                                        id="historico_niv_trobas" 
                                        data-uno="{{$item->cmts}}" data-dos="{{$item->interface}}" data-tres="{{$item->NODO}}-{{$item->TROBA}}">
                                        H. Niv. Trobas <i class="icofont-chart-histogram"></i>
                                    </a> 
                                @endif  
                                @if(Auth::user()->HasPermiso('submodulo.multiconsulta.historico-caidas-trobas.view'))   
                                    <a href="javascript: void(0)" class="btn btn-outline-primary btn-sm shadow-sm" 
                                        id="historico_caidas_trobas" 
                                        data-uno="{{$item->cmts}}" data-dos="{{$item->interface}}" data-tres="{{$item->NODO}}-{{$item->TROBA}}">
                                        H. Caidas. Trobas <i class="icofont-chart-histogram"></i>
                                    </a> 
                                @endif    
                                  
                                 
                            </td>
                        </tr>
                         
                </table>
        </div>

         {{-- Botones --}}

        <div class="row justify-content-center my-2 mx-auto px-0">
            @if(Auth::user()->HasPermiso('submodulo.multiconsulta.grafico-trafico-down.view')) 
                <a href="javascript:void(0)" id="graficoDownstream" class="d-inline-block mx-1" data-uno="{{$item->cmts}}" data-dos="{{$item->interface}}">
                    <img src="{{ asset('/images/icons/multiconsulta/saturacion.png') }}" alt="Trafico Down" title="Trafico Down">
                </a>
            @endif 
            @if($item->MACState == "online")
                @if(Auth::user()->HasPermiso('submodulo.multiconsulta.reset-cm-reaprovisionamiento.view')) 
                    <a href="javascript:void(0)" id="resetCmReaprovisionamiento" class="d-inline-block mx-1" data-uno="{{$item->IDCLIENTECRM}}" 
                            data-dos="{{$item->idservicio}}" data-tres="{{$item->idproducto}}" data-cuatro="{{$item->idventa}}">
                        <img src="{{ asset('/images/icons/multiconsulta/resetCablemodem1.png') }}" alt="Reset Modem" title="Reset Modem">
                    </a>
                    <script src="{{asset('js/sistema/modulos/multiconsulta/reset-cm-reaprovisionamiento.min.js')}}"></script>
                @endif 
            @endif
           {{-- @if(Auth::user()->HasPermiso('submodulo.multiconsulta.reset-decos.view')) 
                <a href="javascript:void(0)" id="resetDecos" class="d-inline-block mx-1" data-uno="{{$item->IDCLIENTECRM}}">
                    <img src="{{ asset('/images/icons/multiconsulta/decoder.png') }}" alt="Reset Decoder" title="Reset Decoder">
                </a> 
            @endif --}}
             
            @if(Auth::user()->HasPermiso('submodulo.multiconsulta.velocidad-cm.view')) 
                <a href="javascript:void(0)" id="cambiarVelocidad" class="d-inline-block mx-1" data-uno="{{$item->MACADDRESS}}"
                        data-dos="{{$item->SERVICEPACKAGECRMID}}">
                    <img src="{{ asset('/images/icons/multiconsulta/velocidad.png') }}" alt="Activar" title="Activar">
                </a> 
            @endif 
             
            @if ($item->estadoserv == "Inactivo")
                @if(Auth::user()->HasPermiso('submodulo.multiconsulta.activar-cm.view')) 
                    <a href="javascript:void(0)" id="activarCM" class="d-inline-block mx-1" data-uno="{{$item->estadoserv}}"
                            data-dos="{{$item->MACADDRESS}}">
                        <img src="{{ asset('/images/icons/multiconsulta/activar.png') }}" alt="Activar" title="Activar">
                    </a> 
                @endif 
            @endif

            @if ($item->scopesgroup == "CPE") 
                @if(Auth::user()->HasPermiso('submodulo.multiconsulta.scopegroup-cm.view')) 
                    <a href="javascript:void(0)" id="scopesGroupCM" class="d-inline-block mx-1" data-uno="{{$item->MACADDRESS}}"
                       data-dos="{{$item->scopesgroup}}">
                        <img src="{{ asset('/images/icons/multiconsulta/ccgnat1.png') }}" alt="CGNAT" title="CGNAT">
                    </a> 
                @endif  
            @else 

                @if(Auth::user()->HasPermiso('submodulo.multiconsulta.scopegroup-cm.view')) 
                    <a href="javascript:void(0)" id="scopesGroupCM" class="d-inline-block mx-1" data-uno="{{$item->MACADDRESS}}"
                             data-dos="{{$item->scopesgroup}}">
                        <img src="{{ asset('/images/icons/multiconsulta/ccpe1.png') }}" alt="CPE" title="CPE">
                    </a>
                @endif  
                
            @endif

            @if(Auth::user()->HasPermiso('submodulo.multiconsulta.arbol-decisiones.view')) 
                <a href="javascript:void(0)" id="arbolDecisiones" class="d-inline-block mx-1" data-uno="{{$item->imgArbol}}"
                            data-dos="{{$item->IDCLIENTECRM}}" data-tres="{{$item->MACState}}" data-cuatro="{{$item->resultadoAlerta}}"
                            data-cinco="inactivo">
                    <img src="{{ asset('/images/icons/multiconsulta/arbol.png') }}" alt="Arbol Decisiones" title="Arbol Decisiones">
                </a> 
            @endif  
             
        </div>


        {{-- Grafico --}}
        
        <div class="row m-auto text-center">
            @if ($item->tipoGrafico=='Cliente')
                <img src="{{ asset('/images/multiconsulta/fondomulti_cliente.png') }}" class="img-fluid m-auto" alt="Grafico Averias" title="Grafico Averias">
            @endif
            @if ($item->tipoGrafico=='Amplificador')
                <img src="{{ asset('/images/multiconsulta/fondomulti_amplif.png') }}" class="img-fluid m-auto" alt="Grafico Averias" title="Grafico Averias">
            @endif
            @if ($item->tipoGrafico=='Troba')
                <img src="{{ asset('/images/multiconsulta/fondomulti_troba.png') }}" class="img-fluid m-auto" alt="Grafico Averias" title="Grafico Averias">
            @endif
            @if ($item->tipoGrafico=='Intraway')
                <img src="{{ asset('/images/multiconsulta/fondomulti_intraway.png') }}" class="img-fluid m-auto" alt="Grafico Averias" title="Grafico Averias">
            @endif
            @if ($item->tipoGrafico=='OK')
                <img src="{{ asset('/images/multiconsulta/fondomulti_ok.png') }}" class="img-fluid m-auto" alt="Grafico Averias" title="Grafico Averias">
            @endif
        </div>

        @if ($item->publica != "no")
            <div class="col-12">
                <div class="h5  text-center text-primary">ASIGNACION IPS DEL CABLE MODEM</div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover w-75 m-auto">
                        <tr>
                            <td class="bg-light">MAC CPE</td>
                            <td>{{$item->macx}}</td>
                        </tr>
                        <tr>
                            <td class="bg-light">IP  CPE</td>
                            <td>{{$item->publica}}</td>
                        </tr>
                        <tr>
                            <td class="bg-light">MAC MTA</td>
                            <td>{{$item->macmta}}</td>
                        </tr>
                        <tr>
                            <td class="bg-light">IP  MTA</td>
                            <td>{{$item->ipmta}}</td>
                        </tr>
                    </table>
                </div>
            </div>
        @endif
        


    @empty
        
    @endforelse
 
</div>