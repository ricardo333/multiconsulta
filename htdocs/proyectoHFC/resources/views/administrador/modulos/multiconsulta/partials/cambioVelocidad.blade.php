<section class="content_result w-100 h-100 d-flex justify-content-center">
        <article class="form" id="form_nvelocidad">
            <div class="form_group text-center" id="rpta_formVelocidad_send">
            
            </div>
            <div class="form-group">
                <label for="nvel" >Nueva Velocidad:</label>
                <select name="nueva_velocidad" id="nvel" class="form-control form-control-sm shadow-sm">
                    @forelse ($velocidades as $nv)
                        <option value="{{$nv->vf}}">{{$nv->vf}}</option>
                    @empty
                        
                    @endforelse
                </select>
            </div>
            <div class="form-group">
                <label for="f_inicio">Fecha de inicio</label>
                <input type="date" name="fech_inicio" id="f_inicio" step="1" 
                        min="{{$fecha_actual}}"  value="{{$fecha_actual}}" class="form-control form-control-sm shadow-sm">
            </div>
            <div class="form-group">
                <label for="dias">Tiempo Días</label>
                <select name="tiempo_dias" id="dias">
                    <option value="7">1 Semana</option>
                    <option value="14">2 Semana</option>
                    <option value="21">3 Semanas</option>
                    <option value="30">1 Mes</option>
                    <option value="60">2 Meses</option>
                    <option value="90">3 Meses</option>
                    <option value="180">6 Meses</option>
                    <option value="365">1 Año</option>
                </select>
            </div>
            <div class="form-group">
                <label for="dias">Motivo del cambio de Velocidad:</label>
                <textarea name="motivo_cambio_velocidad" id="motivo_cambio_velocidad" rows="5" 
                    class="form-control form-control-sm shadow-sm validateText" style="max-height: 150px;"></textarea>
            </div>
            
            <div class="form_group text-center">
                <a href="javascript: void(0)" id="enviar_velocidad" class="btn btn-success btn-sm shadow-sm" data-uno="{{$mac}}" data-dos="{{$velocidadActual}}">Enviar</a>
            </div>
        </article>
    
    </section>
    
    