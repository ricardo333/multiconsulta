<section class="row w-100 my-3 mx-0 py-2 content_filter_basic" id="filtroContentCaidas{{$filtro}}" style="display:none">
        <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
            <label for="" class="col-12 col-sm-3">Jefaturas:</label>
            <select name="listajefaturaCaidas{{$filtro}}" id="listajefaturaCaidas{{$filtro}}" class="col-12 col-sm-9 form-control form-control-sm shadow-sm">
                <option value="seleccionar">Sin Filtro</option>
                    @forelse ($jefaturas as $jeft)
                    <option value="{{$jeft->jefatura}}">{{$jeft->jefatura}}</option>
                    @empty
                
                    @endforelse
            </select>
        </div>
        @if ($filtro == "AMPLIFICADOR")
            <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6" id="content_filtro_trobas">
                <label for="" class="col-12 col-sm-3">Trobas:</label>
                <select name="listaTrobasCaidas{{$filtro}}" id="listaTrobasCaidas{{$filtro}}" class="col-12 col-sm-9 form-control form-control-sm shadow-sm">
                    <option value="seleccionar">Sin Filtro</option>
                    @forelse ($trobas as $trob)
                        <option value="{{$trob->troba}}">{{$trob->troba}}</option>
                    @empty
                        
                    @endforelse
                </select>
            </div>
        @else
            <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6" id="content_filtro_estados">
                <label for="" class="col-12 col-sm-3">Estados:</label>
                <select name="listaEstadoCaidas{{$filtro}}" id="listaEstadoCaidas{{$filtro}}" class="col-12 col-sm-9 form-control form-control-sm shadow-sm">
                    <option value="seleccionar">Sin Filtro</option>
                    @forelse ($estados as $est)
                        <option value="{{$est->estado}}">{{$est->estado}}</option>
                    @empty
                        
                    @endforelse
                </select>
            </div>
            
        @endif 

        <div class="form-group row mx-0 mb-0 px-2 col-12 col-sm-12 col-md-12 col-lg-12 justify-content-center"> 
            <a href="javascript:void(0)" class="btn btn-sm btn-primary shadow-sm w-25 filtroBasicoCaidasGeneral" id="filtroBasicoCaidas{{$filtro}}">Filtrar</a>
        </div>
    </section>