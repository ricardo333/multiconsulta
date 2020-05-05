<div class="modal fade" id="descargasCaidasModal" tabindex="-1" role="dialog" aria-labelledby="descargasCaidasModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
          <div class="modal-content">
            <div class="modal-header py-1 px-3 text-success text-uppercase">
              <h5 class="modal-title" id="descargasCaidasModalLabel">Descargas Caidas</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center">
                  @if (isset($motivo) && $motivo=="cuadroMando")
                    <section id="resultOpcionesCaidas"></section>
                    <div>
                        <input type="button" id="btnTotal" value="Descarga Total" class="btn btn-sm btn-success total">
                        <input type="hidden" id="var_nodo" value="{{$nodo}}" >
                    </div>
                  @else
                    <section id="resultOpcionesCaidas"></section>
                    <div>
                        <input type="button" id="btnTotal" value="Descarga Total" class="btn btn-sm btn-success total">
                    </div>
                  @endif
                </div>
                <div id="preloadMaping">
                </div>
            </div> 
          </div>
        </div>
      </div>