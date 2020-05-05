 
<div class="modal fade" id="show_cablemodem" tabindex="-1" role="dialog" aria-labelledby="showCablemodemTitle" aria-hidden="true">
    <!--<div class="modal-dialog modal-dialog-centered modal-lg" role="document">-->
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header px-2 py-1">
          <h5 class="modal-title" id="showCablemodemTitle">Resultados Múlti</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body modal-height-cablemodem">
          <div class="row">
            
            <div class="container">

                <ul class="nav nav-tabs" id="mytabs">
                    @if ($CM_ESTADO_PERMISO)
                        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#status" data-target="#tabStatus">Status</a></li>
                    @endif
                    @if ($CM_DHCP_PERMISO)
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#dhcp" data-target="#tabDhcp">DHCP</a></li>
                    @endif
                    @if ($CM_WIFI_VECINOS_PERMISO)
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#wifi2" data-target="#tabWifi2">WIFI Vecinos</a></li>
                    @endif
                    @if ($CM_DIAGNOSTICO_PERMISO)
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#diagnos" data-target="#tabDiagnostico">Diagnostico</a></li>
                    @endif 
                    @if($CM_CONFIG_WIFI_VIEW_PERMISO) 
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#wifi" data-target="#tabWifi">Wifi</a></li>
                    @endif 
                    @if($CM_UPNP_PERMISO) 
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#upnp" data-target="#tabUpnp">UPnP</a></li>
                    @endif 
                    @if($CM_DMZ_PERMISO) 
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#dmz" data-target="#tabDmz">Dmz</a></li>
                    @endif 
                    @if($CM_PORTMAPING_PERMISO) 
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#maping" data-target="#tabMaping">PortMaping</a></li>
                    @endif 
                    @if($CM_RESET_SCRAPING_VIEW_PERMISO) 
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#reset" data-target="#tabReset">Reset</a></li>
                    @endif 
                     
                </ul>
            
                <div class="tab-content">
            
                    @if ($CM_ESTADO_PERMISO)
                        <div id="tabStatus" class="tab-pane active">
                            <div id="resultado_status" style='display:show;'>
                            </div>
                        </div>
                    @endif

                    @if ($CM_DHCP_PERMISO)
                        <div id="tabDhcp" class="tab-pane">
                            <div id="resultado_dhcp" style='display:show;'>
                            </div>
                        </div>
                    @endif

                    @if ($CM_WIFI_VECINOS_PERMISO)
                        <div id="tabWifi2" class="tab-pane">
                            <div id="resultado_wifi2" style='display:show;'>
                            </div>
                        </div>
                    @endif

                    @if ($CM_DIAGNOSTICO_PERMISO)
                        <div id="tabDiagnostico" class="tab-pane">
                            <div id="formulario">
                                <form id="formulario_obtener">
                                    <label>Direccion de ip:  </label>
                                    <input type="text" id="ipTest"/>
                                    <br>
                                    <input type="button" id="btnDiagnostico" value="PROBAR TEST"/>
                                </form>
                            </div>
    
                            <div id="resultado_diagnostico" style='display:show;'>
                            </div>
                        </div>
                    @endif
                    
                    @if($CM_CONFIG_WIFI_VIEW_PERMISO)
                        <div id="tabWifi" class="tab-pane">
                            <div id="resultado_wifi" style='display:show;'>
                            </div>
                        </div>
                    @endif

                    @if($CM_UPNP_PERMISO) 
                        <div id="tabUpnp" class="tab-pane">
                            <div id="resultado_Upnp" style='display:show;'>
                            </div>
                        </div>
                    @endif 
                    @if($CM_DMZ_PERMISO) 
                        <div id="tabDmz" class="tab-pane">
                            <div id="resultado_dmz" style='display:show;'>
                            </div>
                        </div>
                    @endif 
                    @if($CM_PORTMAPING_PERMISO) 
                        {{-- /////////////////////////////////////////////////////////// --}}
                            <div id="tabMaping" class="tab-pane">
                                    <div id="preloadMaping"></div>
                                    <div id="resultado_maping"> 
                                            <input type="hidden" id="txtIp" name="txtIp" />
            
                                            <table class="table table-bordered" id="tabla0">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">Service Name</th>
                                                            <th scope="col">LAN IP</th>
                                                            <th scope="col">Protocolo</th>
                                                            <th scope="col">Private Port</th>
                                                            <th scope="col">Public Port</th>
                                                            <th scope="col"> </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tablita">
                                                    </tbody>
                                            </table>
            
                                            <div id="boton1">
                                                <input type="button" id="forwarding" class="port_window01" />
                                            </div>
                                                    
                                            <div id="boton1">
                                                <input type="button" id="btnGuardarMaping" class="btnGuardar" value="GUARDAR" />
                                            </div>
            
                                    </div>
                                    <div id="resulMaping">
                                    </div>
                                    <div class="popup_window1" style="display:none;">
                                            <p class="popupTittle"><span>Editar Puerto</span></p>
                                        
                                            <div class="popupRow">
                                                <div class="popupLeft">
                                                    <span>Service Name: </span>
                                                </div>
                                                <div class="popupRight">
                                                    <input type="text"  id="addServiceName" maxlength="15">
                                                </div>
                                            </div>
                                            
                                            <div class="popupRow">
                                                <div class="popupLeft">
                                                    <span>LAN IP: </span>
                                                </div>
                                                <div class="popupRight">
                                                    <input type="text" class="max3" id="addLanIP0" maxlength="3" value="192" > .
                                                    <input type="text" class="max3" id="addLanIP1" maxlength="3" value="168" > .
                                                    <input type="text" class="max3" id="addLanIP2" maxlength="3" value="1" > .
                                                    <input type="text" class="max3" id="addLanIP3" maxlength="3" >
                                                </div>
                                            </div>
                                        
                                            <div class="popupRow">
                                                <div class="popupLeft">
                                                    <span>Protocolo: </span>
                                                </div>
                                                <div class="popupRight">
                                                    <select id="addSelectProtocol">
                                                        <option>TCP</option>
                                                        <option>UDP</option>
                                                        <option>TCP/UDP</option>
                                                    </select>
                                                </div>
                                            </div>
                                        
                                            <div class="popupRow">
                                                <div class="popupLeft">
                                                    <span>Tipo: </span>
                                                </div>
                                                <div class="popupRight">
                                                    <label class="container"><input type="radio" id="rdPuerto" name="puerto" value="1" />Port</label>
                                                    <label class="container"><input type="radio" id="rdPuerto" name="puerto" value="2" />Port Range</label>
                                                </div>
                                            </div>
                                        
                                            <div id="type_port" class="popupPuerto1" style="display:block">
                                                <div class="popupRow">
                                                    <div class="popupLeft">
                                                        <span>Public Port: </span>
                                                    </div>
                                                    <div class="popupRight">
                                                        <input type="text" id="publicSinglePort" class="max5" maxlength="5" >
                                                    </div>
                                                </div>
                                            
                                                <div class="popupRow">
                                                    <div class="popupLeft">
                                                        <span>Private Port: </span>
                                                    </div>
                                                    <div class="popupRight">
                                                        <input type="text" id="lanSinglePort" class="max5" maxlength="5" >
                                                    </div>
                                                </div>
                                            </div>
                                        
                                            <div id="type_portRange" class="popupPuerto2" style="display:none">
                                                <div class="popupRow">
                                                    <div class="popupLeft">
                                                        <span>Public Port Range: </span>
                                                    </div>
                                                    <div class="popupRight">
                                                        <input type="text" id="publicRangeS" class="max5" maxlength="5" > .
                                                        <input type="text" id="publicRangeE" class="max5" maxlength="5" >
                                                    </div>
                                                </div>
                                        
                                                <div class="popupRow">
                                                    <div class="popupLeft">
                                                        <span>Private Port Range: </span>
                                                    </div>
                                                    <div class="popupRight">
                                                        <input type="text" id="privateRangeS" class="max5" maxlength="5" > .
                                                        <input type="text" id="privateRangeE" class="max5" maxlength="5" >
                                                    </div>
                                                </div>
                                            </div>
                                        
                                            <div class="apply-cancel">
                                                <div class="popupRight">
                                                    <input type="button" id="btnCancel" class="button-cancel" value="Cancel" >
                                                </div>
                                                <div class="popupRight">
                                                    <input type="button" id="btnSave" class="button-apply" value="Save" >
                                                </div>
                                            </div>
                                        
                                    </div>
                                    
                                       
                            </div>
                        {{-- /////////////////////////////////////////////////////////// --}}
                    @endif 

                    @if($CM_RESET_SCRAPING_VIEW_PERMISO) 
                        <div id="tabReset" class="tab-pane">
                            <table id="tabla_reset">
                                @if ($CM_RESET_SCRAP_SIMPLE_PERMISO)
                                    <tr>
                                        <td class="description">Restart Simple de Modem: </td>
                                        <td><input class="btn btn-primary" type="button" id="btnResetSimple" value="RESET" /></td>
                                    </tr>
                                @endif
                                @if ($CM_RESET_SCRAP_FABRICA_PERMISO)
                                    <tr>
                                        <td class="description">Reset de Fabrica: </td>
                                        <td><input class="btn btn-primary" type="button" id="btnResetFactory" value="RESET" /></td>
                                    </tr>
                                @endif
                                 
                            </table>
                            <div id="resultado_reset">
                            </div>
                        </div>
                    @endif 
                      
                </div>
                
            </div>

          </div>
        </div> 
      </div>
    </div>
  </div>

  