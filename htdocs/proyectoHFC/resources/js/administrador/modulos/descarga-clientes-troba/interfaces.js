import peticiones from './peticiones.js'

$.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

cargaPrincipalFiltros()

function cargaPrincipalFiltros()
{   
    peticiones.cargaInterfaces()
}