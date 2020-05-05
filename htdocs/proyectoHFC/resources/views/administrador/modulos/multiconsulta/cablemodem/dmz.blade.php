@if ($dmz["fabricante"]=='Askey')
<input type="hidden" id="txtWan" name="txtWan" value="{{$dmz["wan"]}}" />

<div>
    <fieldset>
        <legend>DMZ</legend>
        <p>Activacion:&nbsp;
            <label><input type="radio" id="radioDmz" name="radioDmz" value="1" @if ($dmz["valor"]=='on') checked='checked' @endif /> ON</label>
            <label><input type="radio" id="radioDmz" name="radioDmz" value="0" @if ($dmz["valor"]=='off') checked='checked' @endif /> OFF</label>
        </p>
    </fieldset>
</div>

<div id="config" @if ($dmz["valor"]=='on') style='display:block' @else style='display:none' @endif  >
    <fieldset>
        <legend>Configuración de IP</legend>
        <label>Public IP Address: </label>
        <label>{{$dmz["publica"]}}</label><br><br>
        
        <label>IP Address: </label>
        <label>{{$dmz["privada"]}}</label>
        <input type="text" id="DmzHostIP" value="{{$dmz["privadaip"]}}">
        <br><br>
    </fieldset>
</div>

<button name="nomDmz" id="btnDmz" >Aplicar</button>

<div id="resultadoDmz">
</div>

@endif

@if ($dmz["fabricante"]=='Hitron')
<input type="hidden" id="txtIds"  name="txtIds" value="{{$dmz["idsHitron"]}}" />
<input type="hidden" id="txtRpt"  name="txtRpt" value="{{$dmz["rptHitron"]}}" />
<input type="hidden" id="txtUpnp"  name="txtUpnp" value="{{$dmz["upnpHitron"]}}" />
<input type="hidden" id="txtWan"  name="txtWan" value="{{$dmz["wanHitron"]}}" />

<fieldset>
    <legend>DMZ</legend>
    <p>Activacion:&nbsp;
        <label><input type="radio" id="radioDmz" name="radioDmz" value="1" @if ($dmz["activacionDmzHitron"]=='1') checked='checked' @endif /> ON</label>
        <label><input type="radio" id="radioDmz" name="radioDmz" value="0" @if ($dmz["activacionDmzHitron"]=='0') checked='checked' @endif /> OFF</label>
    </p>
</fieldset>

<div id="config" @if ($dmz["activacionDmzHitron"]=='1') style='display:block' @else style='display:none' @endif >
<fieldset>
	<legend>Configuración de IP</legend>
	<label>Ingrese la IP Address del equipo: </label>
    <input type="text" name="ipDevice" id="ipDevice" maxlength="15" @if ($dmz["activacionDmzHitron"]=='1') value='{{$dmz["ipDmzWebHitron"]}}' @endif > <br><br>
</fieldset>
</div>

<button name="nomDmz" id="btnDmz" >Aplicar</button>

<div id="resultadoDmz">
</div>

@endif


@if ($dmz["fabricante"]=='Ubee')
<fieldset>
    <legend>DMZ</legend>
    <p>Activacion:&nbsp;
        <label><input type="radio" id="radioDmz" name="radioDmz" value="1" @if ($dmz["valorUbee"]=='on') checked='checked' @endif /> ON</label>
        <label><input type="radio" id="radioDmz" name="radioDmz" value="0" @if ($dmz["valorUbee"]=='off') checked='checked' @endif /> OFF</label>
    </p>
</fieldset>

<div id="config" @if ($dmz["valorUbee"]=='on') style='display:block' @else style='display:none' @endif >
<fieldset>
	<legend>Configuración de IP</legend>
	<label>IP Address: </label>
	<label>192.168.1.</label>
	<input type="text" name="ipDmz" id="ipDmz" maxlength="3" value="{{$dmz["ipUbee"]}}" ><br><br>
</fieldset>
</div>

<button name="nomDmz" id="btnDmz" >Aplicar</button>

<div id="resultadoDmz">
</div>

@endif


@if ($dmz["fabricante"]=='Castlenet')
<fieldset>
    <legend>DMZ</legend>
    <p>Activacion:&nbsp;
        <label><input type="radio" id="radioDmz" name="radioDmz" value="1" @if ($dmz["valorCastlenet"]=='on') checked='checked' @endif /> ON</label>
        <label><input type="radio" id="radioDmz" name="radioDmz" value="0" @if ($dmz["valorCastlenet"]=='off') checked='checked' @endif /> OFF</label>
    </p>
</fieldset>

<div id="config" @if ($dmz["valorCastlenet"]=='on') style='display:block' @else style='display:none' @endif >
<fieldset>
	<legend>Configuración de IP</legend>
	<label>IP Address: </label>
	<label>192.168.1.</label>
	<input type="text" name="ipDmz" id="ipDmz" maxlength="3" value="{{$dmz["ipCastlenet"]}}" ><br><br>
</fieldset>
</div>

<button name="nomDmz" id="btnDmz" >Aplicar</button>

<div id="resultadoDmz">
</div>

@endif



