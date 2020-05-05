<div class="modal fade" id="filtroMapaCallPeru" tabindex="-1" role="dialog" aria-labelledby="filtroMapaCallPeruTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header px-2 py-1">
          <h5 class="modal-title" id="exampleModalLongTitle">Filtro Mapa Call Perú</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body modal-height-responsive">
          <div class="row">
                <section class="row mx-0 my-3 py-2 content_filter_basic" id="filtroContentMCP">
                                        
                    <div class="form-group row mx-0 px-2 col-12 col-sm-12 ">
                        <label for="listaJefaturaFiltro" class="col-12 col-sm-4">Jefaturas:</label>
                        <select name="listaJefaturaFiltro" id="listaJefaturaFiltro" class="col-12 col-sm-8 form-control form-control-sm shadow-sm">
                            @foreach ($jefaturas as $jeft)
                                @if($jeft->jefatura != "TODO" && $jeft->jefatura != 'LIMA')
                                    <option value="{{$jeft->jefatura}}" data-uno="{{$jeft->latitud}}" data-dos="{{$jeft->longitud}}">
                                            {{substr($jeft->jefatura,5,3)}}
                                    </option> 
                                @else
                                    <option value="{{$jeft->jefatura}}" data-uno="{{$jeft->latitud}}" data-dos="{{$jeft->longitud}}">
                                            {{$jeft->jefatura}}
                                    </option> 
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group row mx-0 px-2 col-12 col-sm-12 ">
                            <label for="filtroClteTelDni" class="col-12 col-sm-4">Clte/Tel/Dni:</label>
                            <input type="text" id="filtroClteTelDni" class="col-12 col-sm-8 form-control form-control-sm shadow-sm">
                    </div>
                    <div class="form-group row mx-0 mb-0 px-2 col-12 col-sm-12 justify-content-center align-items-center"> 
                            <a href="javascript:void(0)" class="btn btn-sm btn-success shadow-sm col-12 col-sm-4 m-1" id="filtroBasicoMCP">Filtrar</a>
                    </div>
                </section>
          </div>
        </div> 
      </div>
    </div>
  </div>