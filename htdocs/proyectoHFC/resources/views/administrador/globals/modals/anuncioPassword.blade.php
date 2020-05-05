<div class="modal fade" id="anuncioPassword" tabindex="-1" role="dialog" aria-labelledby="anuncioPasswordTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header px-2 py-1">
              <h5 class="modal-title" id="exampleModalLongTitle">RECORDATORIO DE CAMBIO DE CONTRASEÑA</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body modal-height-responsive">
              <div class="card-body">
                    <p class="text-secondary">Estimado <strong>{{ Auth()->user()->username}}</strong>.<br>
                        Te informamos que solo tienes {{$diasCambio}} días para poder cambiar su contraseña,
                        caso contrario se estará desactivando su cuenta actual.</p>
              </div>
            </div> 
          </div>
        </div>
      </div>