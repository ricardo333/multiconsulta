<div>
    <input type="hidden" id="txtIds"  name="txtIds" value="{{$upnp["identi"]}}" />
    <input type="hidden" id="txtRpt"  name="txtRpt" value="{{$upnp["respuesta"]}}" />
    <input type="hidden" id="txtWan"  name="txtWan" value="{{$upnp["canal"]}}" />

    <fieldset>
        <legend>UPnP</legend>
        <p>Activacion:
            <label><input type="radio" id="upnp" name="upnp" value="1" @if ($upnp["valor"]=='1') checked='checked' @endif /> ON</label>
            <label><input type="radio" id="upnp" name="upnp" value="0" @if ($upnp["valor"]=='0') checked='checked' @endif /> OFF</label>
            <button name="nomUpnp" id="btnUpnp">Aplicar</button>
        </p>
    </fieldset>
</div>

<div id="resultadoUpnp">
</div>

