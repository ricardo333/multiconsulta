<div class="modal fade" id="descargasModal" tabindex="-1" role="dialog" aria-labelledby="descargasModalTitle" aria-hidden="true">
    <div class="form_group text-center" id="rpta_error" style="height: 100px; padding-top: 50px;">
    </div>
    <div><span></span></div>
  
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
              <div class="modal-header px-2 py-1">
                <h5 class="modal-title" id="exampleModalLongTitle">Descargas disponibles</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
  
              <div id="opciones">
                <div class="modal-body modal-height-responsive">
                  <div class="row">

                      @if (isset($motivo) && $motivo=="cuadroMando")
                        <section id="resultOpcionesMasivaCms">
                          <div style="padding:8px">
                            <input type="button" id="btnTotal" value="Descargar Total" class="btn btn-sm btn-success totalmasiva">
                            <input type="hidden" id="var_nodo" value="{{$nodo}}" >
                          </div>
                        </section>
                      @else
                        <section id="resultOpcionesMasivaCms">
                          <div style="padding:8px">
                            <input type="button" id="btnTotal" value="Descargar Total" class="btn btn-sm btn-success totalmasiva">
                          </div>
                          <div style="padding:8px">
                            <input type="button" id="btnTotalAverias" value="Descargar Total Averias" class="btn btn-sm btn-success totalaverias">
                          </div>
                        </section>
                      @endif
                      
                  </div>
                  <div id="preloadMaping">
                  </div>
                </div> 
              </div>
  
  
  
            </div>
          </div>
        </div>