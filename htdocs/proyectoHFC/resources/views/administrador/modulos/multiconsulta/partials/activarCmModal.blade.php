<div class="modal fade" id="activarCmModal" tabindex="-1" role="dialog" aria-labelledby="activarCmModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header px-2 py-1">
        <h5 class="modal-title" id="exampleModalLongTitle">Activar CM </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body modal-height-responsive">
        <div class="row">
          <div id="preloadActivarCm" class="col-12"></div>
            <section class="col-12 form" id="formActiveCm">
              <div id="rptaActivarFormSend" class="form_group text-center"></div>
              <div class="form-group col-12 mx-0 px-0">
                  <label for="justificacionActivacion">Justifica la Activación del CM:</label>
                  <textarea name="justificacionActivacion" id="justificacionActivacion" class="form-control form-control-sm shadow-sm validateText" rows="5" style="max-height:150px;"></textarea>
              </div>
              <div class="form-group col-12 mx-0 px-0 text-center">
                  <a href="javascript:void(0)" id="activarCMCliente" class="btn btn-sm btn-outline-success shadow-sm">Activar</a>
              </div>
            </section>
        </div>
      </div> 
    </div>
  </div>
</div>
<script src="{{asset('js/sistema/modulos/multiconsulta/activar-cm.min.js')}}"></script>