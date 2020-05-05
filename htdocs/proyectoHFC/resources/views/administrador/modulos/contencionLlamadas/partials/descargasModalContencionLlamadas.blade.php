<div class="modal fade" id="descargasModalContencionLlamadas" tabindex="-1" role="dialog" aria-labelledby="descargasModalTitle" aria-hidden="true">
    <div class="form_group text-center" id="rpta_error" style="height: 100px; padding-top: 50px;"></div>
  
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
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
              <section id="resultOpcionesContencionLlamadas" class="width-100"></section>
                <div class="col-lg-12">
                    <div class="card-body-detalles text-center">
                      <div style="padding:8px">
                        <a href="javascript:void(0)" data-uno="llamadas_mes.csv" class="downloadContencionLlamadas btn btn-sm btn-success">Descargar Llamadas Mes</a>
                      </div>
                    </div>
                </div>
            </div>
            <div id="preloadModal"></div>
            <div id="preloadModalMensaje" class="text-center"></div>
          </div> 
        </div>
  
      </div>
    </div>
    
</div>