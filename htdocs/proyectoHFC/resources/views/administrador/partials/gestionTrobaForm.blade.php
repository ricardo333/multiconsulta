<div id="preloadGestionIndivisual"></div>
<div id="formularioContenedorGestionInd" class="row text-sm"> 
                                    <section class="form row my-2 mx-0" id="form_store_detail">
                                            <div class="form-group row justify-content-center mx-0 px-2 col-12 errors_message" id="errors_store">

                                            </div>
                                                
                                            <input type="hidden" id="numRequerimiento"  value="">
                                            <input type="hidden" id="nodoGestionStoreIndv"  value="">
                                            <input type="hidden" id="trobaGestionStoreIndv"  value="">
                                            <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                                <label for="tecnicoStore" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Técnico: </label>
                                                <select name="tecnicoStore" id="tecnicoStore" class="col-sm-7  col-md-8 form-control form-control-sm shadow-sm validateSelect"> 
                                                    
                                                </select>
                                            </div>
                                            <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                                <label for="estadoStore" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Estado: </label>
                                                <select name="estadoStore" id="estadoStore" class="col-sm-7  col-md-8 form-control form-control-sm shadow-sm validateSelect"> 
                                                   
                                                </select>
                                            </div>
                                            <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 display_options_by_estado display_trabajo_programado">
                                                <label for="causaStore" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Causa: </label>
                                                <select name="causaStore" id="causaStore" class="col-sm-7  col-md-8 form-control form-control-sm shadow-sm validateSelect"> 
                                                    
                                                </select>
                                            </div>
                                            <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 display_options_by_estado display_trabajo_programado">
                                                <label for="areaRespMasivaStore" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Área Responsable de la Masiva: </label>
                                                <select name="areaRespMasivaStore" id="areaRespMasivaStore" class="col-sm-7  col-md-8 form-control form-control-sm shadow-sm validateSelect"> 
                                                        
                                                </select>
                                            </div>
                                            <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 display_options_by_liq_masiva">
                                                <label for="codigoTecLiqStore" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Cod. Tec. liq.: </label>
                                                <input type="text" name="codigoTecLiqStore" id="codigoTecLiqStore" class="col-sm-7 col-md-8 form-control form-control-sm shadow-sm validateText">
                                            </div>
                                            <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 display_options_by_liq_masiva">
                                                <label for="codigoLiqStore" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Cod. liq.: </label>
                                                <input type="text" name="codigoLiqStore" id="codigoLiqStore" class="col-sm-7 col-md-8 form-control form-control-sm shadow-sm validateText">
                                            </div>
                                            <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 display_options_by_liq_masiva">
                                                <label for="detLiqStore" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Det. liq.: </label>
                                                <input type="text" name="detLiqStore" id="detLiqStore" class="col-sm-7 col-md-8 form-control form-control-sm shadow-sm validateText">
                                            </div>
                                            <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 display_options_by_liq_masiva">
                                                <label for="afectacionStore" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Afectación: </label>
                                                <input type="text" name="afectacionStore" id="afectacionStore" class="col-sm-7 col-md-8 form-control form-control-sm shadow-sm validateText">
                                            </div>
                                            <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 display_options_by_liq_masiva">
                                                <label for="contrataStore" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Contrata: </label>
                                                <input type="text" name="contrataStore" id="contrataStore" class="col-sm-7 col-md-8 form-control form-control-sm shadow-sm validateText">
                                            </div>
                                            <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 display_options_by_liq_masiva">
                                                <label for="nombreTecStore" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Nombre Tec.: </label>
                                                <input type="text" name="nombreTecStore" id="nombreTecStore" class="col-sm-7 col-md-8 form-control form-control-sm shadow-sm validateText">
                                            </div>
                                            <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-12 col-lg-12 ">
                                                <label for="observacionesStore" class="col-sm-5 col-md-2 col-form-label col-form-label-sm mb-0 px-0">Observaciones: </label>
                                                <textarea name="observacionesStore" id="observacionesStore" class="col-sm-7 col-md-10 form-control form-control-sm shadow-sm validateText" cols="30" rows="5"></textarea>
                                            </div>
                                            <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                                <label for="caidaCompletaStore" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Caida 100%: </label>
                                                <select name="caidaCompletaStore" id="caidaCompletaStore" class="col-sm-7  col-md-8 form-control form-control-sm shadow-sm validateSelect"> 
                                                    <option value="NO">NO</option>
                                                    <option value="SI">SI</option>
                                                </select>
                                            </div>
                                            <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                                <label for="servicioAfectadoStore" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Servicio Afectado: </label>
                                                <select name="servicioAfectadoStore" id="servicioAfectadoStore" class="col-sm-7  col-md-8 form-control form-control-sm shadow-sm validateSelect"> 
                                                    <option value="seleccionar">Seleccionar</option>
                                                    <option value="CATV">CATV</option>
                                                    <option value="INTERNET">INTERNET</option>
                                                    <option value="VOZ">VOZ</option>
                                                    <option value="INTERNET-CATV">INTERNET-CATV</option>
                                                    <option value="INTERNET-VOZ">INTERNET-VOZ</option>
                                                </select> 
                                            </div>
                                            <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-6 col-lg-6 ">
                                                <label for="remedyStore" class="col-sm-5 col-md-4 col-form-label col-form-label-sm mb-0 px-0">Remedy: </label>
                                                <input type="text" name="remedyStore" id="remedyStore" class="col-sm-7 col-md-8 form-control form-control-sm shadow-sm validateText">
                                            </div> 
                                            <div class="form-group row mx-0 px-2 col-12 col-sm-12 col-md-12 col-lg-12 d-flex justify-content-center">
                                                <a href="javascript:void(0)" class="btn btn-sm btn-outline-primary shadow-sm" id="registrarGestIndiv">Enviar</a>
                                            </div>
                                    </section>
</div>
                                            