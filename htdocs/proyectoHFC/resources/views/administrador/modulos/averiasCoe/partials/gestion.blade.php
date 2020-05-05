<h4 class="w-100 text-center text-danger mb-3">Procesar Gestión</h4>
<div id="preloadSendGestion" class="w-100">  
</div> 
<div id="contentListClientes" class="table-responsive mb-3 col-12  m-auto px-0">
    <table class="table table-hover table-bordered w-100 o">
        <thead>
            <tr class="bg-primary text-light text-center">
                <th class="p-1">Codigo Cliente</th>
                <th class="p-1">Codigo Requerimiento</th>
                <th class="p-1">Mac</th>
                <th class="p-1">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($clientes as $clt)
                <tr class="text-center">
                    <td class="p-1">{{$clt->codcli}}</td>
                    <td class="p-1">{{$clt->codreq}}</td>
                    <td class="p-1">{{$clt->macaddress}}</td>
                    <td class="p-1">
                        <a href="javascript:void(0)" class="btn btn-sm btn-outline-success shadow-sm verHistoricoGestionCOE" data-uno="{{$clt->codcli}}">
                            Ver Histórico
                        </a>
                    </td>
                </tr>
            @endforeach 
        </tbody>
    </table>
</div>
<div class="form col-12  m-auto p-3 border border-primary rounded shadow" id="content-form">
    <div class="form-group row mb-1 m-0" id="errorGestionProcess">
        
    </div>
    <div class="form-group row mb-1 m-0">
        <label for="" class="col-sm-4">Segunda Linea</label>
        <select name="segundaLineaSelect" id="segundaLineaSelect" class="form-control form-control-sm shadow-sm col-sm-8"> 
            <option value="seleccionar">Seleccionar</option>
            <option value="CON TRATAMIENTO">Con Tratamiento</option>
            <option value="SIN TRATAMIENTO">Sin Tratamiento</option>
        </select>
    </div>
    <div class="form-group row mb-1 m-0">
        <label for="" class="col-sm-4">Resultado</label>
        <select name="resultadoSegundaLinea" id="resultadoSegundaLinea" class="form-control form-control-sm shadow-sm col-sm-8">
        </select>
    </div>
    <div class="form-group row mb-1 m-0">
        <label for="" class="col-sm-4">Detalle Resultado</label>
        <select name="detalleResultado" id="detalleResultado" class="form-control form-control-sm shadow-sm col-sm-8">
        </select>
    </div>
    <div class="form-group row mb-1 m-0">
        <label for="" class="col-sm-4">Persona de Contacto</label>
        <input type="text" name="personaContacto" id="personaContacto" class="form-control form-control-sm shadow-sm col-sm-8">
    </div>
    <div class="form-group row mb-1 m-0">
        <label for="" class="col-sm-4">Número de Contacto</label>
        <input type="text" name="numeroContacto" id="numeroContacto" class="form-control form-control-sm shadow-sm col-sm-8">
    </div>
    <div class="form-group row mb-1 m-0">
        <label for="" class="col-sm-4">Observación del Resultado:</label>
        <textarea name="observacionResultado" id="observacionResultado" cols="30" rows="10" class="form-control form-control-sm shadow-sm col-sm-8"></textarea>
    </div>
    <div class="form-group row mb-1 m-0">
        <label for="" class="col-sm-4">Estado del Caso:</label>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="EstadoDelCaso" id="PENDIENTE" value="PENDIENTE">
            <label class="form-check-label" for="PENDIENTE">PENDIENTE</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="EstadoDelCaso" id="CERRADO" value="CERRADO">
            <label class="form-check-label" for="CERRADO">CERRADO</label>
        </div> 
        
    </div>
    <div class="form-group row mb-1 m-0">
        <label for="" class="col-sm-4">Resultado de Visita:</label>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="ResultadoVisita" id="EFECTIVA" value="EFECTIVA">
            <label class="form-check-label" for="EFECTIVA">EFECTIVA</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="ResultadoVisita" id="INEFECTIVA" value="INEFECTIVA">
            <label class="form-check-label" for="INEFECTIVA">INEFECTIVA</label>
        </div>  
    </div>
    <div class="form-group row mb-1 m-0">
        <label for="" class="col-sm-4">Observación de Visita Técnica</label>
        <textarea name="observacionVisitaTecnica" id="observacionVisitaTecnica" cols="30" rows="10" class="form-control form-control-sm shadow-sm col-sm-8"></textarea>
    </div>
    <div class="form-group row my-3 m-0 justify-content-center">
        <a href="javascript:void(0)" class="btn btn-sm btn-primary shadow-sm w-50" id="sendGestionIndividual">Enviar</a>
    </div>
</div> 
 