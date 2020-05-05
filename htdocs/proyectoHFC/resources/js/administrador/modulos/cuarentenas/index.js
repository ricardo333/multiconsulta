import peticiones from './peticiones.js'

$(function(){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function leadingZero(value) {
        if (value < 10) {
          return "0" + value.toString();
        }
        return value.toString();
    }


    peticiones.cargaListaCuarentenas()

    $("#filtroBasicoCuarentena").click(function(){
        peticiones.cargaListaCuarentenas()
    })

    /*$("#limpiarFiltro").click(function(){
        
        $("#listaJefaturasCuarentenas").val("seleccionar")
        $("#reiteradasFilter").prop('checked',false)
        $("#averiaspFiltro").val("")
        $("#codigoMotvFiltro").val("")
        $("#tipoEstadoFiltro").val("")
        $("#segunColorFiltro").val("")
 
    })*/

    $("#display_filter_special").change(function(){
        peticiones.cargaListaCuarentenas()
    })

    
    $(".return_cuarentenas").click(function(){
        peticiones.redirectTabs($("#cuarentenaListaTab"))
    })

    $("#descargaGeneralCuarentena").click(function(){
        console.log("La descarga de Cuarentenas deberia iniciar...")
    })

     //------------MODAL---------//
     //$("body").on("click","#btnTotal", function(){
     $("body").on("click","#btnTotal", function(){
 
            let today = new Date();
            let dd = today.getDate();
            let mm = today.getMonth(mm)+1;
            let yyyy = today.getFullYear();
            let hh = today.getHours();
            let mi = today.getMinutes();
            let ss = today.getSeconds();
    
            let fecha = yyyy+''+leadingZero(mm)+''+leadingZero(dd)+''+leadingZero(hh)+''+leadingZero(mi)+''+leadingZero(ss);
    
            //console.log(fecha);

            let idCuarentena = $("#display_filter_special").val()
            let jefatura =  $("#listaJefaturasCuarentenas").val()
            let reiteradas = ""
            let averiasp = $("#averiaspFiltro").val()
            let codmotv = $("#codigoMotvFiltro").val()
            let tipoEstado = $("#tipoEstadoFiltro").val()
            let segunColor = $("#segunColorFiltro").val()
        
            if( $('#reiteradasFilter').prop('checked') ) {
                reiteradas = $('#reiteradasFilter').val()
            }
    
            
            $("#preloadMaping").html(`<div id="carga_person">
                                      <div class="loader">Loading...</div>
                                    </div>`)
    
        
            let _this = $(this)
            _this.prop("disabled", true);   
    
            $.ajax({
                xhrFields: { responseType: 'blob', },
                url: `/administrador/cuarentenas/${idCuarentena}/reportes-excel`,
                method: 'get',
                data: {
                  //  idCuarentena,
                    jefatura,
                    reiteradas,
                    averiasp,
                    codmotv,
                    tipoEstado,
                    segunColor
                },
                cache: false,
                })
                .done(function(result){
                    $("#preloadMaping").html("");

                   
                    let nombreCuarentena = $( "#display_filter_special option:selected" ).text();
                    _this.prop("disabled", false);   
                   // console.log(result);
    
                    var blob = new Blob([result], {
                        type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                    });
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = nombreCuarentena+"_"+fecha+'.xlsx';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                })
    
                .fail(function(xhr, jqXHR, textStatus) {
                    _this.prop("disabled", false); 
                    $("#preloadMaping").html("");
                   
                    console.log(xhr)
                    console.log(jqXHR)
                    console.log(textStatus)
                    var errorMessage1 = xhr.status + ': ' + "Hubo un error en los datos, intente en un minuto por favor."
                    
                    //alert(errorMessage1);
                    $("#resultOpcionesCuarentenas").html(`<div class="container text-center font-weight-bold alert alert-danger fade show" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    ${errorMessage1}</div>`);
                });
           
    
        })

        //Maximizar

    $(".maxi_tab").click(function(){
       
        $("#tabsCuarentenasContent").toggleClass("fullscreen");
  
        if ($("#tabsCuarentenasContent").hasClass("fullscreen")) {
          console.log("tiene la clase full ")
           $(".maxi_tab").html('<i class="icofont-close-line-squared-alt"></i>') 
        }else{
          console.log("no tiene la clase full ")
          $(".maxi_tab").html('<i class="icofont-maximize"></i>')  
        } 
   
      })


})