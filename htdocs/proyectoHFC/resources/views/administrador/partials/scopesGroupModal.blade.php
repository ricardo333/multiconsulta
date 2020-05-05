<div class="modal fade" id="scopesGroupModal" tabindex="-1" role="dialog" aria-labelledby="scopesGroupModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header px-2 py-1">
        <h5 class="modal-title" id="exampleModalLongTitle">Cambio de IP</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body modal-height-responsive">
        <div class="row">
          <div id="preloadScopeGroupSend" class="col-12"></div>
            <section class="col-12 form" id="formScopeGroup">
              <div id="rptaScopeGroupFormSend" class="form_group text-center"></div>
              <div class="form-group col-12 mx-0 px-0">
                  <label for="motivoCambioScopeGroup">Elija el motivo del cambio de la IP:</label>
                  <select name='motivoCambioScopeGroup' id="motivoCambioScopeGroup" class="form-control form-control-sm shadow-sm validateSelect">
                      <option value='seleccionar'>Seleccionar</option>
                      <option value='SIN IP'>Sin IP</option>
                      <option value='LENTITUD'>Lentitud</option>
                      <option value='CAMARA'>Camaras/Juegos/Servisores</option>
                  </select>
              </div>
              <div class="form-group col-12 mx-0 px-0 text-center">
                  <a href="javascript:void(0)" id="cambiarScopeGroupClient" class="btn btn-sm btn-outline-success shadow-sm"
                  {{-- Utiliza las data para Averias COE / MacAddress--}}
                    data-uno=""
                  {{--END --}}
                  >Cambiar IP</a>
              </div>
             
            </section>
        </div>
      </div> 
    </div>
  </div>
</div>



