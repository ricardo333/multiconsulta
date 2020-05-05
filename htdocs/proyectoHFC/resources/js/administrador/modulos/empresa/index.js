  
 $(function(){

    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

     //peticiones.cargaCompletaUsuarios(SORTBY,0)

     let columnasComple = [
                            {data: 'id'},
                            {data: 'nombre'}
                        ]
     if (BTN_PERMISOS) {
        columnasComple.push({data: 'btn'})
     }
 
     var cargaEmpresaLista = $('#listEmpresasPrint').DataTable({
                                "serverSide": true,
                                "processing": true,
                                "dom":'<"row mx-0"'
                                            +'<"col-12 col-sm-6"l><"col-12 col-sm-6"f>>'
                                        +'<"row position-relative"'
                                            +'<"col-sm-12 px-0 table-responsive table-text-xs tableFixHead"t>'
                                            +'r>'
                                        +'<"row"'
                                            +'<"col-12 col-sm-5"i><"col-12 col-sm-7"p>>',
                                "ajax": {
                                    "url":"/administrador/empresas/lista", 
                                    "error": function(jqXHR, textStatus)
                                            { 
                                            // console.log( "Error: " ,jqXHR, textStatus); 
                                                if(jqXHR.status){
                                                    if (jqXHR.status == 401) {
                                                        location.reload();
                                                        return false
                                                        } 
                                                        cargaEmpresaLista.ajax.reload();
                                                    return false
                                                } 
                                                cargaEmpresaLista.ajax.reload();
                                                return false 
                                            }
                                    },
                                "columns": columnasComple,
                                "language": {
                                    "info": "_TOTAL_ registros",
                                    "search": "Buscar",
                                    "paginate": {
                                        "next": "Siguiente",
                                        "previous": "Anterior",
                                    },
                                    "lengthMenu": 'Mostrar <select >'+
                                                '<option value="15">15</option>'+
                                                '<option value="50">50</option>'+
                                                '<option value="100">100</option>'+
                                                '<option value="-1">Todos</option>'+
                                                '</select> registros',
                                    "loadingRecords": "<div id='carga_person'> <div class='loader'>Cargando...</div></div>",
                                    "processing": "<div id='carga_person'> <div class='loader'>Procesando...</div></div>",
                                    "emptyTable": "No hay empresas disponibles",
                                    "zeroRecords": "No hay coincidencias", 
                                    "infoEmpty": "",
                                    "infoFiltered": ""
                                }
                            });

        $("#listEmpresasPrint").parent().addClass("table-responsive tableFixHead") 
     
  /**Tabla fixed */
    var th = $('.tableFixHead').find('thead th')
    
    $('.tableFixHead').on('scroll', function() {
        //console.log("ejecutando"+this.scrollTop); 
        th.css('transform', 'translateY('+ this.scrollTop +'px)'); 
    });
  /**Tabla tabla fixed */
   
 })

 