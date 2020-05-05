@php
    $CM_CONFIG_WIFI_UPDATE = false;

    if(Auth::user()->HasPermiso('submodulo.multiconsulta.cm.config-wifi.update') || Auth::user()->HasPermiso('submodulo.averias-coe.cm.config-wifi.update')){

        $CM_CONFIG_WIFI_UPDATE = true;
    }
 
@endphp

@if ($wifi1["fabricante"]=='Askey')
<div>
    <form id="formulario_obtener" autocomplete="off">
        <div id="tablas" class="d-flex justify-content-between">    
            <div>
                <table>
                    <tr>
                        <td class="header">NETWORK 1</td>
                    </tr>

                    <tr>
                        <td>SSID: </td>
                        <td><input type="text" id="ssid1" value="{{$wifi1["ssid"]}}" /></td>
                    </tr>

                    <tr>
                        <td>Interface Type: </td>
                        <td>
                        <select name='cmbInterface1' id='cmbInterface1'>
                            <option value='1' @if ($wifi1["interface"]=='1') selected @endif >‎802.11 b/g</option>
                            <option value='2' @if ($wifi1["interface"]=='2') selected @endif >‎802.11 b/g/n</option>
                            <option value='3' @if ($wifi1["interface"]=='3') selected @endif >‎802.11 n only</option>
                        </select>
                        </td>
                    </tr>

                    <tr>
                        <td>Channel: </td>
                        <td>
                        <select name='cmbChannel1' id='cmbChannel1'>
                            <option value='0' @if ($wifi1["channel"]=='0') selected @endif >‎‎Auto</option>
                            <option value='1' @if ($wifi1["channel"]=='1') selected @endif >1</option>
                            <option value='2' @if ($wifi1["channel"]=='2') selected @endif >2</option>
                            <option value='3' @if ($wifi1["channel"]=='3') selected @endif >3</option>
                            <option value='4' @if ($wifi1["channel"]=='4') selected @endif >4</option>
                            <option value='5' @if ($wifi1["channel"]=='5') selected @endif >5</option>
                            <option value='6' @if ($wifi1["channel"]=='6') selected @endif >6</option>
                            <option value='7' @if ($wifi1["channel"]=='7') selected @endif >7</option>
                            <option value='8' @if ($wifi1["channel"]=='8') selected @endif >8</option>
                            <option value='9' @if ($wifi1["channel"]=='9') selected @endif >9</option>
                            <option value='10' @if ($wifi1["channel"]=='10') selected @endif >10</option>
                            <option value='11' @if ($wifi1["channel"]=='11') selected @endif >11</option>
                        </select>
                        </td>
                    </tr>

                    <tr>
                        <td>Bandwidth: </td>
                        <td>
                        <select name='cmbBandwidth1' id='cmbBandwidth1'>
                            <option value='20' @if ($wifi1["bandwidth"]=='20') selected @endif >‎‎20 MHz</option>
                            <option value='40' @if ($wifi1["bandwidth"]=='40') selected @endif >20/40 MHz</option>
                        </select>
                        </td>
                    </tr>
                
                    <tr>
                        <td>Power: </td>
                        <td>
                        <select name='cmbPower1' id='cmbPower1'>
                            <option value='100' @if ($wifi1["power"]=='100') selected @endif >‎‎100%</option>
                            <option value='75' @if ($wifi1["power"]=='75') selected @endif >‎75%</option>
                            <option value='50' @if ($wifi1["power"]=='50') selected @endif >50%</option>
                            <option value='25' @if ($wifi1["power"]=='25') selected @endif >25%</option>
                        </select>
                        </td>
                    </tr>
                
                    <tr>
                        <td>WIFI Protection: </td>
                        <td>
                        <select name='cmbProtection1' id='cmbProtection1'>
                            <option value='off' @if ($wifi1["seguridad"]=='off') selected @endif >OFF</option>
                            <option value='wep64' @if ($wifi1["seguridad"]=='wep64') selected @endif >WEP-64</option>
                            <option value='wpa-tkip' @if ($wifi1["seguridad"]=='wpa-tkip') selected @endif >WPA/TKIP</option>
                            <option value='wpa-tkip-aes' @if ($wifi1["seguridad"]=='wpa-tkip-aes') selected @endif >WPA/TKIP+AES</option>
                            <option value='wpa2-aes' @if ($wifi1["seguridad"]=='wpa2-aes') selected @endif >WPA2/AES</option>
                            <option value='wpa2-tkip-aes' @if ($wifi1["seguridad"]=='wpa2-tkip-aes') selected @endif >WPA2/TKIP+AES</option>
                            <option value='wpa%2Bwpa2' @if ($wifi1["seguridad"]=='wpa+wpa2') selected @endif >WPA+WPA2/TKIP+AES</option>
                        </select>
                        </td>
                    </tr>
                
                    <tr>
                        <td>Password: </td>
                        @if($CM_CONFIG_WIFI_UPDATE)
                        <td><input type='text' id='Password1' value="{{$wifi1["password"]}}"  /></td>
                        @else 
                        <td><input type='password' id='Password1' value="{{$wifi1["password"]}}" /></td>
                        @endif
                    </tr>
                
                </table>
                
            </div>

            @if ($wifi2["ssid"]<>'')
            <div @if ($wifi2["ssid"]<>'') visibility: visible @else visibility: hidden @endif >

                <table>
                
                    <tr>
                        <td class="header">NETWORK 2</td>
                    </tr>
                    
                    <tr>
                        <td>SSID: </td>
                        <td><input type="text" id="ssid2" value="{{$wifi2["ssid"]}}" /></td>
                    </tr>

                    <tr>
                        <td>Interface Type: </td>
                        <td>
                            <select name='cmbInterface2' id='cmbInterface2'>
                                <option value='6' @if ($wifi2["interface"]=='6') selected @endif >‎‎802.11 a</option>
                                <option value='7' @if ($wifi2["interface"]=='7') selected @endif >‎‎802.11 a/n</option>
                                <option value='8' @if ($wifi2["interface"]=='8') selected @endif >‎‎802.11 a/n/ac</option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td>Channel: </td>
                        <td>
                            <select name='cmbChannel2' id='cmbChannel2'>
                                <option value='0' @if ($wifi2["channel"]=='0') selected @endif >‎‎Auto</option>
                                <option value='36' @if ($wifi2["channel"]=='36') selected @endif >36</option>
                                <option value='40' @if ($wifi2["channel"]=='40') selected @endif >40</option>
                                <option value='44' @if ($wifi2["channel"]=='44') selected @endif >44</option>
                                <option value='48' @if ($wifi2["channel"]=='48') selected @endif >48</option>
                                <option value='52' @if ($wifi2["channel"]=='52') selected @endif >52</option>
                                <option value='56' @if ($wifi2["channel"]=='56') selected @endif >56</option>
                                <option value='60' @if ($wifi2["channel"]=='60') selected @endif >60</option>
                                <option value='64' @if ($wifi2["channel"]=='64') selected @endif >64</option>
                                <option value='100' @if ($wifi2["channel"]=='100') selected @endif >100</option>
                                <option value='104' @if ($wifi2["channel"]=='104') selected @endif >104</option>
                                <option value='108' @if ($wifi2["channel"]=='108') selected @endif >108</option>
                                <option value='112' @if ($wifi2["channel"]=='112') selected @endif >112</option>
                                <option value='132' @if ($wifi2["channel"]=='132') selected @endif >132</option>
                                <option value='136' @if ($wifi2["channel"]=='136') selected @endif >136</option>
                                <option value='149' @if ($wifi2["channel"]=='149') selected @endif >149</option>
                                <option value='153' @if ($wifi2["channel"]=='153') selected @endif >153</option>
                                <option value='157' @if ($wifi2["channel"]=='157') selected @endif >157</option>
                                <option value='161' @if ($wifi2["channel"]=='161') selected @endif >161</option>
                            </select>
                        </td>
                    </tr>
                        
                    <tr>
                        <td>Bandwidth: </td>
                        <td>
                            <select name='cmbBandwidth2' id='cmbBandwidth2'>
                                <option value='20' @if ($wifi2["bandwidth"]=='20') selected @endif  >‎‎20 MHz</option>
                                <option value='40' @if ($wifi2["bandwidth"]=='40') selected @endif  >20/40 MHz</option>
                                <option value='80' @if ($wifi2["bandwidth"]=='80') selected @endif  >‎‎20/40/80 MHz</option>
                            </select>
                        </td>
                    </tr>
                        
                    <tr>
                        <td>Power: </td>
                        <td>
                            <select name='cmbPower2' id='cmbPower2'>
                                <option value='9' @if ($wifi2["power"]=='9') selected @endif >9</option>
                                <option value='‎10' @if ($wifi2["power"]=='10') selected @endif >‎10</option>
                                <option value='11' @if ($wifi2["power"]=='11') selected @endif >11</option>
                                <option value='12' @if ($wifi2["power"]=='12') selected @endif >12</option>
                                <option value='13' @if ($wifi2["power"]=='13') selected @endif >13</option>
                                <option value='14' @if ($wifi2["power"]=='14') selected @endif >14</option>
                                <option value='15' @if ($wifi2["power"]=='15') selected @endif >15</option>
                                <option value='16' @if ($wifi2["power"]=='16') selected @endif >16</option>
                                <option value='17' @if ($wifi2["power"]=='17') selected @endif >17</option>
                                <option value='18' @if ($wifi2["power"]=='18') selected @endif >18</option>
                                <option value='19' @if ($wifi2["power"]=='19') selected @endif >19</option>
                                <option value='20' @if ($wifi2["power"]=='20') selected @endif >20</option>
                                <option value='21' @if ($wifi2["power"]=='21') selected @endif >21</option>
                                <option value='22' @if ($wifi2["power"]=='22') selected @endif >22</option>
                                <option value='23' @if ($wifi2["power"]=='23') selected @endif >23</option>
                                <option value='24' @if ($wifi2["power"]=='24') selected @endif >24</option>
                                <option value='25' @if ($wifi2["power"]=='25') selected @endif >25</option>
                            </select>
                        </td>
                    </tr>
                        
                    <tr>
                        <td>WIFI Protection: </td>
                        <td>
                            <select name='cmbProtection2' id='cmbProtection2'>
                                <option value='off' @if ($wifi2["seguridad"]=='off') selected @endif >OFF</option>
                                <option value='wpa2-aes' @if ($wifi2["seguridad"]=='wpa2-aes') selected @endif >WPA2/AES</option>
                            </select>
                        </td>
                    </tr>
                        
                    <tr>
                        <td>Password: </td>
                        @if($CM_CONFIG_WIFI_UPDATE)
                        <td><input type='text' id='Password2' value="{{$wifi2["password"]}}" /></td>
                        @else 
                        <td><input type='password' id='Password2' value="{{$wifi2["password"]}}" /></td>
                        @endif
                    </tr>
                
                </table>
            </div>
            @endif

        </div>

        @if($CM_CONFIG_WIFI_UPDATE)
        <div id="botoncambio" class="text-center" >
            <input type='button' id="btnCambio" value='ACEPTAR' />
        </div>
        @endif

        <div id="resultado">
        </div>
 
    </form>
</div>
@endif

@if ($wifi1["fabricante"]=='Hitron')
<div>
    <form id="formulario_obtener" autocomplete="off">
        <div id="tablas">  
            <table>
                <tr>
                    <td class="header">NETWORK 1</td>
                </tr>
                        
                <tr>
                    <td>SSID: </td>
                    <td><input type="text" id="ssid1Hitron" value="{{$wifi1["ssid1_hitron"]}}" /></td>
                    <td><input type='hidden' id="ssid2Hitron" value="{{$wifi1["ssid2_hitron"]}}" /></td>
                    <td><input type='hidden' id="ssid3Hitron" value="{{$wifi1["ssid3_hitron"]}}" /></td>
                    <td><input type='hidden' id="ssid4Hitron" value="{{$wifi1["ssid4_hitron"]}}" /></td>
                    <td><input type='hidden' id="ssid5Hitron" value="{{$wifi1["ssid5_hitron"]}}" /></td>
                    <td><input type='hidden' id="ssid6Hitron" value="{{$wifi1["ssid6_hitron"]}}" /></td>
                    <td><input type='hidden' id="ssid7Hitron" value="{{$wifi1["ssid7_hitron"]}}" /></td>
                    <td><input type='hidden' id="ssid8Hitron" value="{{$wifi1["ssid8_hitron"]}}" /></td>
                </tr>  

                <tr>
                    <td>Interface Type: </td>
                    <td>
                        <select name='cmbInterface1' id='cmbInterfaceHitron'>
                            <option value='0' @if ($wifi1["interface_hitron"]=='0') selected @endif >‎11B/G Mixed</option>
                            <option value='1' @if ($wifi1["interface_hitron"]=='1') selected @endif >‎11B Only</option>
                            <option value='4' @if ($wifi1["interface_hitron"]=='4') selected @endif >‎11G Only</option>
                            <option value='6' @if ($wifi1["interface_hitron"]=='6') selected @endif >‎11N Only</option>
                            <option value='7' @if ($wifi1["interface_hitron"]=='7') selected @endif >‎11G/N Mixed</option>
                            <option value='9' @if ($wifi1["interface_hitron"]=='9') selected @endif >‎11B/G/N Mixed</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>Channel: </td>
                    <td>
                        <select name='cmbChannel1' id='cmbChannelHitron'>
                            <option value='0' @if ($wifi1["channel_hitron"]=='0') selected @endif >‎‎Auto</option>
                            <option value='1' @if ($wifi1["channel_hitron"]=='1') selected @endif >1</option>
                            <option value='2' @if ($wifi1["channel_hitron"]=='2') selected @endif >2</option>
                            <option value='3' @if ($wifi1["channel_hitron"]=='3') selected @endif >3</option>
                            <option value='4' @if ($wifi1["channel_hitron"]=='4') selected @endif >4</option>
                            <option value='5' @if ($wifi1["channel_hitron"]=='5') selected @endif >5</option>
                            <option value='6' @if ($wifi1["channel_hitron"]=='6') selected @endif >6</option>
                            <option value='7' @if ($wifi1["channel_hitron"]=='7') selected @endif >7</option>
                            <option value='8' @if ($wifi1["channel_hitron"]=='8') selected @endif >8</option>
                            <option value='9' @if ($wifi1["channel_hitron"]=='9') selected @endif >9</option>
                            <option value='10' @if ($wifi1["channel_hitron"]=='10') selected @endif >10</option>
                            <option value='11' @if ($wifi1["channel_hitron"]=='11') selected @endif >11</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>Bandwidth: </td>
                    <td>
                        <select name='cmbBandwidth1' id='cmbBandwidthHitron'>
                            <option value='0' @if ($wifi1["bandwidth_hitron"]=='0') selected @endif >‎‎20 MHz</option>
                            <option value='1' @if ($wifi1["bandwidth_hitron"]=='1') selected @endif >‎20/40 MHz</option>
                        </select>
                    </td>
                </tr>
                        
                <tr>
                    <td>WIFI Protection - WPA Mode: </td>
                    <td>
                        <select name='cmbProtection1' id='cmbProtection1Hitron'>
                            <option value='4' @if ($wifi1["seguridad1_hitron"]=='4') selected @endif >WPA-PSK</option>
                            <option value='5' @if ($wifi1["seguridad1_hitron"]=='5') selected @endif >WPA2-PSK</option>
                            <option value='6' @if ($wifi1["seguridad1_hitron"]=='6') selected @endif >Auto (WPA-PSK or WPA2-PSK)</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>WIFI Protection - Type: </td>
                    <td>
                        <select name='cmbProtection2' id='cmbProtection2Hitron'>
                            <option value='2' @if ($wifi1["seguridad2_hitron"]=='2') selected @endif >TKIP</option>
                            <option value='3' @if ($wifi1["seguridad2_hitron"]=='3') selected @endif >AES</option>
                            <option value='4' @if ($wifi1["seguridad2_hitron"]=='4') selected @endif >TKIP and AES</option>
                        </select>
                    </td>
                </tr>
                        
                <tr>
                    <td>Password: </td>
                    @if($CM_CONFIG_WIFI_UPDATE)
                    <td><input type='text' id='passwordHitron' value="{{$wifi1["password_hitron"]}}" /></td>
                    @else 
                    <td><input type='password' id='passwordHitron' value="{{$wifi1["password_hitron"]}}" /></td>
                    @endif
                </tr>

            </table>
        </div>

        @if($CM_CONFIG_WIFI_UPDATE)
        <div id="botoncambio" class="text-center" >
            <input type='button' id="btnCambio" value='ACEPTAR' />
        </div>
        @endif

        <div id="resultado">
        </div>

    </form>
<div>
@endif


@if ($wifi1["fabricante"]=='Ubee')
<div>
    <form id="formulario_obtener" autocomplete="off">
        <div id="tablas">
            <table>
                <tr>
                    <td class="header">NETWORK 1</td>
                </tr>
        
                <tr>
                    <td>SSID: </td>
                    <td><input type="text" id="ssidUbee" value="{{$wifi1["ssid_ubee"]}}" /></td>
                </tr>
        
                <tr>
                    <td>Interface Type: </td>
                    <td>
                    <select name='cmbInterfaceUbee' id='cmbInterfaceUbee'>
                        <option value='0' @if ($wifi1["interface_ubee"]=='0') selected @endif >‎bgn-mode Mixed</option>
                        <option value='1' @if ($wifi1["interface_ubee"]=='1') selected @endif >‎n-mode Only</option>
                        <option value='2' @if ($wifi1["interface_ubee"]=='2') selected @endif >‎bg-mode Mixed</option>
                        <option value='3' @if ($wifi1["interface_ubee"]=='3') selected @endif >‎g-mode Only</option>
                        <option value='5' @if ($wifi1["interface_ubee"]=='5') selected @endif >‎802.11b Only</option>
                    </select>
                    </td>
                </tr>
        
                <tr>
                    <td>Channel: </td>
                    <td>
                    <select name='cmbChannel1' id='cmbChannelUbee'>
                        <option value='0' @if ($wifi1["channel_ubee"]=='0') selected @endif >‎‎Auto</option>
                        <option value='1' @if ($wifi1["channel_ubee"]=='1') selected @endif >1</option>
                        <option value='2' @if ($wifi1["channel_ubee"]=='2') selected @endif >2</option>
                        <option value='3' @if ($wifi1["channel_ubee"]=='3') selected @endif >3</option>
                        <option value='4' @if ($wifi1["channel_ubee"]=='4') selected @endif >4</option>
                        <option value='5' @if ($wifi1["channel_ubee"]=='5') selected @endif >5</option>
                        <option value='6' @if ($wifi1["channel_ubee"]=='6') selected @endif >6</option>
                        <option value='7' @if ($wifi1["channel_ubee"]=='7') selected @endif >7</option>
                        <option value='8' @if ($wifi1["channel_ubee"]=='8') selected @endif >8</option>
                        <option value='9' @if ($wifi1["channel_ubee"]=='9') selected @endif >9</option>
                        <option value='10' @if ($wifi1["channel_ubee"]=='10') selected @endif >10</option>
                        <option value='11' @if ($wifi1["channel_ubee"]=='11') selected @endif >11</option>
                    </select>
                    </td>
                </tr>
    
                <tr>
                    <td class="header">WIFI Protection:</td>
                </tr>
        
                <tr>
                    <td>WPA: </td>
                    <td>
                    <select name='cmbProtection1' id='cmbProtection1Ubee'>
                        <option value='0' @if ($wifi1["seguridad1_ubee"]=='0') selected @endif >Disabled</option>
                        <option value='1' @if ($wifi1["seguridad1_ubee"]=='1') selected @endif >Enabled</option>
                    </select>
                    </td>
                </tr>
        
                <tr>
                    <td>WPA-PSK: </td>
                    <td>
                    <select name='cmbProtection2' id='cmbProtection2Ubee'>
                        <option value='0' @if ($wifi1["seguridad2_ubee"]=='0') selected @endif >Disabled</option>
                        <option value='1' @if ($wifi1["seguridad2_ubee"]=='1') selected @endif >Enabled</option>
                    </select>
                    </td>
                </tr>
                
                <tr>
                    <td>WPA2: </td>
                    <td>
                    <select name='cmbProtection3' id='cmbProtection3Ubee'>
                        <option value='0' @if ($wifi1["seguridad3_ubee"]=='0') selected @endif >Disabled</option>
                        <option value='1' @if ($wifi1["seguridad3_ubee"]=='1') selected @endif >Enabled</option>
                    </select>
                    </td>
                </tr>
        
                <tr>
                    <td>WPA2-PSK: </td>
                    <td>
                    <select name='cmbProtection4' id='cmbProtection4Ubee'>
                        <option value='0' @if ($wifi1["seguridad4_ubee"]=='0') selected @endif >Disabled</option>
                        <option value='1' @if ($wifi1["seguridad4_ubee"]=='1') selected @endif >Enabled</option>
                    </select>
                    </td>
                </tr>
                
                <tr>
                    <td>Encriptacion: </td>
                    <td>
                    <select name='cmbProtection5' id='cmbProtection5Ubee'>
                        <option value='2' @if ($wifi1["seguridad5_ubee"]=='2') selected @endif >AES</option>
                        <option value='3' @if ($wifi1["seguridad5_ubee"]=='3') selected @endif >TKIP+AES</option>
                    </select>
                    </td>
                </tr>
        
                <tr>
                    <td>Password: </td>
                    @if($CM_CONFIG_WIFI_UPDATE)
                    <td><input type='text' id='passwordUbee' value="{{$wifi1["password_ubee"]}}" /></td>
                    @else 
                    <td><input type='password' id='passwordUbee' value="{{$wifi1["password_ubee"]}}" /></td>
                    @endif
                </tr>
        
            </table>
        </div>

        @if($CM_CONFIG_WIFI_UPDATE)
        <div id="botoncambio" class="text-center" >
            <input type='button' id="btnCambio" value='ACEPTAR' />
        </div>
        @endif

        <div id="resultado">
        </div>

    </form>
</div>
@endif



@if ($wifi1["fabricante"]=='Sagem')
<div>
    <form id="formulario_obtener" autocomplete="off">
        <div id="tablas">
            <table>
                <tr>
                    <td class="header">NETWORK 1</td>
                </tr>
                        
                <tr>
                    <td>SSID: </td>
                    <td><input type="text" id="ssidSagem" value="{{$wifi1["ssid_sagem"]}}" /></td>
                </tr>
                        
                <tr>
                    <td>Channel: </td>
                    <td>
                        <select name='cmbChannel1' id='cmbChannelSagem'>
                            <option value='0' @if ($wifi1["channel_sagem"]=='0') selected @endif >‎‎Auto</option>
                            <option value='1' @if ($wifi1["channel_sagem"]=='1') selected @endif >1</option>
                            <option value='2' @if ($wifi1["channel_sagem"]=='2') selected @endif >2</option>
                            <option value='3' @if ($wifi1["channel_sagem"]=='3') selected @endif >3</option>
                            <option value='4' @if ($wifi1["channel_sagem"]=='4') selected @endif >4</option>
                            <option value='5' @if ($wifi1["channel_sagem"]=='5') selected @endif >5</option>
                            <option value='6' @if ($wifi1["channel_sagem"]=='6') selected @endif >6</option>
                            <option value='7' @if ($wifi1["channel_sagem"]=='7') selected @endif >7</option>
                            <option value='8' @if ($wifi1["channel_sagem"]=='8') selected @endif >8</option>
                            <option value='9' @if ($wifi1["channel_sagem"]=='9') selected @endif >9</option>
                            <option value='10' @if ($wifi1["channel_sagem"]=='10') selected @endif >10</option>
                            <option value='11' @if ($wifi1["channel_sagem"]=='11') selected @endif >11</option>
                        </select>
                    </td>
                </tr>
                                
                <tr>
                    <td>Bandwidth: </td>
                    <td>
                        <select name='cmbBandwidth1' id='cmbBandwidthSagem'>
                            <option value='20' @if ($wifi1["bandwidth_sagem"]=='20') selected @endif >‎‎20 MHz</option>
                        </select>
                    </td>
                </tr>
                                
                <tr>
                    <td>Power: </td>
                    <td>
                        <select name='cmbPower1' id='cmbPowerSagem'>
                            <option value='100' @if ($wifi1["power_sagem"]=='100') selected @endif >‎‎100%</option>
                            <option value='75' @if ($wifi1["power_sagem"]=='75') selected @endif >‎75%</option>
                            <option value='50' @if ($wifi1["power_sagem"]=='50') selected @endif >50%</option>
                            <option value='25' @if ($wifi1["power_sagem"]=='25') selected @endif >25%</option>
                        </select>
                    </td>
                </tr>
                                
                <tr>
                    <td class="header">WIFI Protection:</td>
                </tr>
                        
                <tr>
                    <td>WPA: </td>
                    <td>
                        <select name='cmbProtection1' id='cmbProtection1Sagem'>
                            <option value='0' @if ($wifi1["seguridad1_sagem"]=='0') selected @endif >Disabled</option>
                            <option value='1' @if ($wifi1["seguridad1_sagem"]=='1') selected @endif >Enabled</option>
                        </select>
                    </td>
                </tr>
                        
                <tr>
                    <td>WPA-PSK: </td>
                    <td>
                        <select name='cmbProtection2' id='cmbProtection2Sagem'>
                            <option value='0' @if ($wifi1["seguridad2_sagem"]=='0') selected @endif >Disabled</option>
                            <option value='1' @if ($wifi1["seguridad2_sagem"]=='1') selected @endif >Enabled</option>
                        </select>
                    </td>
                </tr>
                        
                <tr>
                    <td>WPA2: </td>
                    <td>
                        <select name='cmbProtection3' id='cmbProtection3Sagem'>
                            <option value='0' @if ($wifi1["seguridad3_sagem"]=='0') selected @endif >Disabled</option>
                            <option value='1' @if ($wifi1["seguridad3_sagem"]=='1') selected @endif >Enabled</option>
                        </select>
                    </td>
                </tr>
                        
                <tr>
                    <td>WPA2-PSK: </td>
                    <td>
                        <select name='cmbProtection4' id='cmbProtection4Sagem'>
                            <option value='0' @if ($wifi1["seguridad4_sagem"]=='0') selected @endif >Disabled</option>
                            <option value='1' @if ($wifi1["seguridad4_sagem"]=='1') selected @endif >Enabled</option>
                        </select>
                    </td>
                </tr>
                        
                <tr>
                    <td>Encriptacion: </td>
                    <td>
                        <select name='cmbProtection5' id='cmbProtection5Sagem'>
                            <option value='2' @if ($wifi1["seguridad5_sagem"]=='0') selected @endif >AES</option>
                            <option value='3' @if ($wifi1["seguridad5_sagem"]=='1') selected @endif >TKIP+AES</option>
                        </select>
                    </td>
                </tr>
                        
                <tr>
                    <td>Password: </td>
                    @if($CM_CONFIG_WIFI_UPDATE)
                    <td><input type='text' id='passwordSagem' value="{{$wifi1["password_sagem"]}}" /></td>
                    @else 
                    <td><input type='password' id='passwordSagem' value="{{$wifi1["password_sagem"]}}" /></td>
                    @endif
                </tr>
                        
            </table>
        </div>

        @if($CM_CONFIG_WIFI_UPDATE)
        <div id="botoncambio" class="text-center" >
            <input type='button' id="btnCambio" value='ACEPTAR' />
        </div>
        @endif

        <div id="resultado">
        </div>

    </form>
</div>
@endif


@if ($wifi1["fabricante"]=='Castlenet')
<div>    
    <form id="formulario_obtener" autocomplete="off">
        <div id="tablas">
            <table>
                <tr>
                    <td class="header">NETWORK 1</td>
                </tr>
                        
                <tr>
                    <td>SSID: </td>
                    <td><input type="text" id="ssidCastle" value="{{$wifi1["ssid_castle"]}}" /></td>
                </tr>        
                        
                <tr>
                    <td class="header">WIFI Protection:</td>
                </tr>        
                        
                <tr>
                    <td>Encriptacion: </td>
                    <td>
                        <select name='cmbProtection_castle' id='cmbProtectionCastle'>
                            <option value='2' @if ($wifi1["seguridad1_castle"]=='2') selected @endif >AES</option>
                            <option value='3' @if ($wifi1["seguridad1_castle"]=='3') selected @endif >TKIP+AES</option>
                        </select>
                    </td>
                </tr>
                        
                <tr>
                    <td>Password: </td>
                    @if($CM_CONFIG_WIFI_UPDATE)
                    <td><input type='text' id='passwordCastle' value="{{$wifi1["password_castle"]}}" /></td>
                    @else 
                    <td><input type='password' id='passwordCastle' value="{{$wifi1["password_castle"]}}" /></td>
                    @endif
                </tr>        
            </table>
        </div>

        @if($CM_CONFIG_WIFI_UPDATE)
        <div id="botoncambio" class="text-center" >
            <input type='button' id="btnCambio" value='ACEPTAR' />
        </div>
        @endif

        <div id="resultado">
        </div>

    </form>
</div>
@endif

