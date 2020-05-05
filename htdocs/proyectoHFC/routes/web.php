<?php

use App\Transformers\RolTransformer;
use App\Transformers\UserTransformer;
use App\Transformers\EmpresaTransformer;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
  
Route::get('/','Modulos\Auth\LoginController@index')->name('modulo.login.index')->middleware('guest');
Route::post('/','Modulos\Auth\LoginController@login')->name('login');
Route::get('/captcha-refresh', 'Modulos\Captcha\CaptchaController@refresh')->name('captcha.refresh.ajax');
 

//->middleware('transform.input:' . UserTransformer::class);
 
Route::group(['middleware' => 'auth'], function () {
 
    //CIERRE DE SESION
    Route::post('/logout','Modulos\Auth\LoginController@logout')->name('logout');

    //ADMINISTRADOR PRINCIPAL VIEW
    Route::get('/administrador', 'AdministradorController@index')->name('administrador');

    //PASSWORD VIEWS
    Route::get('/password/cambio', 'Modulos\Password\PasswordController@primerCambio')->name('password.change.view');
    Route::post('/password/usuario/{usuario}/update', 'Modulos\Password\PasswordController@update')->name('password.usuario.update');
 
    //PERFIL VIEWS
    Route::get('/perfil/{username}/detalle','Modulos\User\PerfilController@detalle')->name('perfil.usuario.detalle');
     
    //SEGURIDAD VIEW
    Route::get('/administrador/seguridad','Modulos\Seguridad\SeguridadController@index')->name('modulo.seguridad.index')
    ->middleware('permiso:modulo.seguridad.index');

    //ADMINISTRADOR EMPRESA VIEW
    Route::get('/administrador/empresa','Modulos\Empresa\EmpresaController@index')->name('modulo.empresa.index')
    ->middleware('permiso:modulo.empresa.index');  
    Route::get('/administrador/empresa/{empresa}/detalle','Modulos\Empresa\EmpresaController@show')->name('submodulo.empresa.show')
    ->middleware('permiso:submodulo.empresa.show'); 
    Route::get('/administrador/empresa/{empresa}/editar','Modulos\Empresa\EmpresaController@edit')->name('submodulo.empresa.edit')
    ->middleware('permiso:submodulo.empresa.edit'); 
    Route::get('/administrador/empresa/crear','Modulos\Empresa\EmpresaController@create')->name('submodulo.empresa.store')
    ->middleware('permiso:submodulo.empresa.store'); 

    // ADMINISTRADOR USUARIOS VIEW
    Route::get('/administrador/usuario','Modulos\User\UserController@index')->name('modulo.usuario.index')
    ->middleware('permiso:modulo.usuario.index');
    Route::get('/administrador/usuario/crear','Modulos\User\UserController@create')->name('submodulo.usuario.store')
    ->middleware('permiso:submodulo.usuario.store');
    Route::get('/administrador/usuario/{usuario}/detalle','Modulos\User\UserController@show')->name('submodulo.usuario.show')
    ->middleware('permiso:submodulo.usuario.show')
    ->middleware('can:show,usuario');//policy
    Route::get('/administrador/usuario/{usuario}/editar','Modulos\User\UserController@edit')->name('submodulo.usuario.edit')
    ->middleware('permiso:submodulo.usuario.edit')
    ->middleware('can:edit,usuario');//policy
 
    //ADMINISTRADOR ROLES VIEW
    Route::get('/administrador/rol','Modulos\Rol\RolController@index')->name('modulo.rol.index')
    ->middleware('permiso:modulo.rol.index');
    Route::get('/administrador/rol/crear','Modulos\Rol\RolController@create')->name('submodulo.rol.store')
    ->middleware('permiso:submodulo.rol.store');
    Route::get('/administrador/rol/{rol}/detalle','Modulos\Rol\RolController@show')->name('submodulo.rol.show')
    ->middleware('permiso:submodulo.rol.show')
    ->middleware('can:show,rol');//policy
    Route::get('/administrador/rol/{rol}/editar','Modulos\Rol\RolController@edit')->name('submodulo.rol.edit')
    ->middleware('permiso:submodulo.rol.edit')
    ->middleware('can:edit,rol');//policy
   
    //ADMINISTRADOR MULTICONSULTA VIEW
    Route::get('/administrador/multiconsulta','Modulos\Multiconsulta\MulticonsultaController@index')->name('modulo.multiconsulta.index')
    ->middleware('permiso:modulo.multiconsulta.index');
    
       

    //ADMINISTRADOR ALBOL DE DECISIONES VIEW
    Route::get('/administrador/arbol-decision','Modulos\Arbol\AdminArbolController@index')->name('modulo.arbol-decision.index')
        ->middleware('permiso:modulo.arbol-decision.index');
    Route::get('/administrador/arbol-decision/paso/{paso}/show','Modulos\Arbol\AdminArbolController@showPaso')->name('submodulo.arbol-decision.pasos.show')
        ->middleware('permiso:submodulo.arbol-decision.pasos.show');
        
    
    //LLAMADAS TROBA VIEW
    Route::match(array('GET','POST'),'/administrador/llamadas-troba','Modulos\Llamada\LlamadaController@view')->name('modulo.llamadas.index')
    ->middleware('permiso:modulo.llamadas.index');


    Route::get('/administrador/llamadas-troba/gestion-masiva/view','Modulos\Gestion\GestionController@view')->name('submodulo.llamadas.gestion-masiva.view')
    ->middleware('permiso:submodulo.llamadas.gestion-masiva.store');

    //Cablemodems
    Route::get('/administrador/multiconsulta/cablemodem','Modulos\Multiconsulta\CablemodemController@status')->name('submodulo.multiconsulta.cm.estado.view')
    ->middleware('permiso:submodulo.multiconsulta.cm.estado.view');

    //ADMINISTRADOR MONITORE DE AVERIAS
    Route::get('/administrador/monitor-averias','Modulos\MonitorAverias\MonitorAveriasController@index')->name('modulo.monitor-averias.index')
    ->middleware('permiso:modulo.monitor-averias.index');
    Route::get('/administrador/monitor-averias/gestion-masiva/view','Modulos\Gestion\GestionController@view')->name('submodulo.monitor-averias.gestion-masiva.view')
    ->middleware('permiso:submodulo.monitor-averias.gestion-masiva.store');

    //GESTIÓN

    //VALICACIÓN DE SERVICIOS
    Route::get('/administrador/validacion-servicios','Modulos\ValidacionServicio\ValidacionServiciosController@index')->name('modulo.validacion-servicios.index')
    ->middleware('permiso:modulo.validacion-servicios.index');

    //CAIDAS
    Route::match(array('GET','POST'),'/administrador/caidas','Modulos\Caidas\CaidasController@view')->name('modulo.caidas.index')
    ->middleware('permiso:modulo.caidas.index');
    Route::get('/administrador/caidas/gestion-masiva/store','Modulos\Gestion\GestionController@view')->name('submodulo.caidas.gestion-masiva.view')
    ->middleware('permiso:submodulo.caidas.gestion-masiva.store');
    
    //PROBLEMA SEÑAL
    Route::get('/administrador/problema-senal','Modulos\ProblemaSenal\ProblemaSenalController@index')->name('modulo.problema-senal.index')
    ->middleware('permiso:modulo.problema-senal.index');
    
    
    //DESCARGA CLIENTES TROBA
    Route::get('/administrador/descarga-clientes-troba','Modulos\DescargaClientesTroba\DescargaClientesTrobaController@view')->name('modulo.descarga-cliente-troba.index')
    ->middleware('permiso:modulo.descarga-cliente-troba.index');
    
    //ESTADOS DE LOS MODEMS VIEW
    Route::get('/administrador/estados-modems','Modulos\EstadosModems\EstadosModemsController@view')->name('modulo.estados-modems.index')
    ->middleware('permiso:modulo.estados-modems.index');

    //MASIVAS CMS
    Route::match(array('GET','POST'),'/administrador/masiva-cms','Modulos\MasivaCMS\MasivaCmsController@index')->name('modulo.masiva-cms.index')
    ->middleware('permiso:modulo.masiva-cms.index');
    Route::get('/administrador/masiva-cms/gestion-masiva/view','Modulos\Gestion\GestionController@view')->name('submodulo.masiva-cms.gestion-masiva.view')
    ->middleware('permiso:submodulo.masiva-cms.gestion-masiva.store');
    Route::get('/administrador/masiva-cms/cargar-masiva/view','Modulos\MasivaCMS\MasivaCmsController@verCargaMasiva')->name('submodulo.masiva-cms.cargar-masiva.view')
    ->middleware('permiso:submodulo.masiva-cms.cargar-masiva.view');

    
    //TRABAJOS PROGRAMADOS VIEW
    Route::get('/administrador/trabajos-programados','Modulos\TrabajosProgramados\TrabajosProgramadosController@index')->name('modulo.trabajos-programados.index')
    ->middleware('permiso:modulo.trabajos-programados.index');
     

    //CONTEO DE LOS MODEMS VIEW
    Route::get('/administrador/conteo-modems','Modulos\ConteoModems\ConteoModemsController@view')->name('modulo.conteo-modems.index')
    ->middleware('permiso:modulo.conteo-modems.index');

    //MONITOR IPS VIEW
    Route::match(array('GET','POST'),'/administrador/monitor-ips','Modulos\MonitorIPS\MonitorIPSController@view')->name('modulo.monitor-ips.index')
    ->middleware('permiso:modulo.monitor-ips.index');

    //SATURACION DOWN VIEW
    Route::match(array('GET','POST'),'/administrador/saturacion-down','Modulos\SaturacionDown\SaturacionDownController@view')->name('modulo.saturacion-down.index')
    ->middleware('permiso:modulo.saturacion-down.index');


    //DESCARGA CMTS
    Route::get('/administrador/descarga-cmts','Modulos\DescargaCmts\DescargaCmtsController@view')->name('modulo.descarga-cmts.index')
    ->middleware('permiso:modulo.descarga-cmts.index');

 
    //GESTION CUARENTENAS VIEW 
    Route::get('/administrador/gestion-cuarentena','Modulos\Cuarentenas\GestionCuarentenasController@index')->name('modulo.gestion-cuarentena.index')
    ->middleware('permiso:modulo.gestion-cuarentena.index');

    //CUARENTENAS VIEW 
    Route::match(array('GET', 'POST'),'/administrador/cuarentenas','Modulos\Cuarentenas\CuarentenasController@index')->name('modulo.cuarentenas.index')
    ->middleware('permiso:modulo.cuarentenas.index');
   /*Route::post('/administrador/cuarentenas','Modulos\Cuarentenas\CuarentenasController@index')->name('modulo.cuarentenas.index.post')
    ->middleware('permiso:modulo.cuarentenas.index');*/
 
    //MENSAJES OPERADOR VIEW
    Route::get('/administrador/mensajes-operador','Modulos\MensajesOperador\MensajesOperadorController@view')->name('modulo.mensajes-operador.index')
    ->middleware('permiso:modulo.mensajes-operador.index');

    //ETIQUETADO DE PUERTOS
    Route::get('/administrador/etiquetado-puertos','Modulos\EtiquetadoPuertos\EtiquetadoPuertosController@view')->name('modulo.etiquetado-puertos.index')
    ->middleware('permiso:modulo.etiquetado-puertos.index');
 
    //INGRESO DE AVERIAS
    Route::match(array('GET','POST'),'/administrador/ingreso-averias','Modulos\IngresoAverias\IngresoAveriasController@view')->name('modulo.ingreso-averias.index')
    ->middleware('permiso:modulo.ingreso-averias.index');
    
    //CUADRO MANDO HFC
    Route::get('/administrador/cuadro-mando','Modulos\CuadroMandoHFC\CuadroMandoController@view')->name('modulo.cuadro-mando.index')
    ->middleware('permiso:modulo.cuadro-mando.index');

    //CONSULTA DHCP
    Route::get('/administrador/consultaDhcp','Modulos\consultaDhcp\consultaDhcpController@index')->name('modulo.consultaDhcp.index');

    //LLAMADAS POR NODO VIEW
    Route::get('/administrador/llamadas-nodo','Modulos\LlamadasNodo\LlamadasNodoController@view')->name('modulo.llamadas-nodo.index')
    ->middleware('permiso:modulo.llamadas-nodo.index');

    //MONITOR FUENTES
    Route::get('/administrador/monitor-fuentes','Modulos\MonitorFuentes\MonitorFuentesController@view')->name('modulo.monitor-fuentes.index')
    ->middleware('permiso:modulo.monitor-fuentes.index');
 
    //MAPA LLAMADAS PERU
    Route::get('/administrador/mapa-llamadas-peru','Modulos\MapaLlamadasPeru\MapaLlamadasPeruController@view')->name('modulo.mapa-llamadas-peru.index')
    ->middleware('permiso:modulo.mapa-llamadas-peru.index');

 
    //CONTENCION LLAMADAS VIEW
    Route::get('/administrador/contencion-llamadas','Modulos\ContencionLlamadas\ContencionLlamadasController@view')->name('modulo.contencion-llamadas.index')
    ->middleware('permiso:modulo.contencion-llamadas.index');

    //DIAGNOSTICO OUTSIDE
    Route::get('/administrador/diagnostico-outside','Modulos\DiagnosticoOutside\DiagnosticoOutsideController@view')->name('modulo.diagnostico-outside.index')
    ->middleware('permiso:modulo.diagnostico-outside.index');

    //GRAFICA LLAMADAS NODOS VIEW
    Route::get('/administrador/grafica-llamadas-nodos','Modulos\GraficaLlamadasNodos\GraficaLlamadasNodosController@view')->name('modulo.grafica-llamadas-nodos.index')
    ->middleware('permiso:modulo.grafica-llamadas-nodos.index');

    //SEGUIMIENTO LLAMADAS VIEW
    Route::get('/administrador/seguimiento-llamadas','Modulos\SeguimientoLlamadas\SeguimientoLlamadasController@view')->name('modulo.seguimiento-llamadas.index')
    ->middleware('permiso:modulo.seguimiento-llamadas.index');

    //GRAFICA LLAMADAS NODOS DIA VIEW
    Route::get('/administrador/grafica-llamadas-nodos-dia','Modulos\GraficaLlamadasNodosDia\GraficaLlamadasNodosDiaController@view')->name('modulo.grafica-llamadas-nodos-dia.index')
    ->middleware('permiso:modulo.grafica-llamadas-nodos-dia.index');

    //GRAFICA VISOR DE AVERÍAS
    Route::get('/administrador/grafica-visor-averias','Modulos\GraficaVisorAverias\GraficaVisorAveriasController@view')->name('modulo.grafica-visor-averias.index')
    ->middleware('permiso:modulo.grafica-visor-averias.index');
 
    //AGENDAS
    Route::get('/administrador/agendas','Modulos\Agendas\AgendasController@view')->name('modulo.agendas.index')
    ->middleware('permiso:modulo.agendas.index');
 

    //MONITOR PERFORMANCE
    Route::get('/administrador/performance','Modulos\MonitorPerformance\MonitorPerformanceController@view')->name('modulo.performance.index')
    ->middleware('permiso:modulo.performance.index');

    //AVERIAS COE
    Route::get('/administrador/averias-coe','Modulos\AveriasCoe\AveriasCoeController@view')->name('modulo.averias-coe.index')
    ->middleware('permiso:modulo.averias-coe.index'); 
    //GESTIÓN AVERIAS OE
    Route::post('/administrador/averias-coe/gestion/view', 'Modulos\AveriasCoe\AveriasCoeController@gestViewGestion')->name('submodulo.averias-coe.gestion.view')
        ->middleware('permiso:submodulo.averias-coe.gestion.view');
 
    
    // --------------      -------------- //
    // -------------- JSON -------------- //
    // --------------      -------------- //

    //ADMINISTRADOR PRINCIPAL
    Route::get('/administrador/lista', 'AdministradorController@list')->name('administrador.list');
     
    //ADMINISTRADOR USUARIOS
    Route::get('/administrador/usuarios/lista', 'Modulos\User\UserController@lista')->name('submodulo.usuario.list.ajax')
    ->middleware('permiso:modulo.usuario.index');
    Route::post('/administrador/usuario/empresa/{empresa}/rol/{rol}/store', 'Modulos\User\UserController@store')->name('submodulo.usuario.store.ajax')
    ->middleware('permiso:submodulo.usuario.store')
    ->middleware('transform.input:'. UserTransformer::class)
    ->middleware('can:user-store,rol');//policy
    Route::post('/administrador/usuario/{usuario}/empresa/{empresa}/rol/{rol}/update', 'Modulos\User\UserController@update')->name('submodulo.empresa.edit.ajax')
    ->middleware('permiso:submodulo.usuario.edit')
    ->middleware('transform.input:'. UserTransformer::class)
    ->middleware('can:update,usuario,rol');//policy 
    Route::post('/administrador/usuario/{usuario}/eliminar', 'Modulos\User\UserController@delete')->name('submodulo.usuario.delete.ajax')
    ->middleware('permiso:submodulo.usuario.delete')
    ->middleware('can:delete,usuario');//policy

    //ADMINISTRADOR ROLES
    Route::get('/administrador/roles/lista', 'Modulos\Rol\RolController@lista')->name('modulo.rol.index.ajax')
    ->middleware('permiso:modulo.rol.index');
    Route::post('/administrador/rol/store', 'Modulos\Rol\RolController@store')->name('submodulo.rol.store.ajax')
    ->middleware('permiso:submodulo.rol.store')
    ->middleware('transform.input:'. RolTransformer::class);
    Route::post('/administrador/rol/{rol}/update', 'Modulos\Rol\RolController@update')->name('submodulo.rol.edit.ajax')
    ->middleware('permiso:submodulo.rol.edit')
    ->middleware('transform.input:'. RolTransformer::class)
    ->middleware('can:update,rol');//policy
    Route::post('/administrador/rol/{rol}/eliminar', 'Modulos\Rol\RolController@delete')->name('submodulo.rol.delete.ajax')
    ->middleware('permiso:submodulo.rol.delete')
    ->middleware('can:delete,rol');//policy 
    

    //ROLES - PERMISOS
    Route::get('/administrador/roles/{rol}/permisos', 'Modulos\Rol\RolController@permisos')->name('submodulo.rol.permisos.lista');
     

    //ADMINISTRADOR EMPRESA
    Route::get('/administrador/empresas/lista','Modulos\Empresa\EmpresaController@lista')->name('modulo.empresa.index.ajax')
    ->middleware('permiso:modulo.empresa.index'); 
    Route::post('/administrador/empresa/store','Modulos\Empresa\EmpresaController@store')->name('submodulo.empresa.store.ajax')
    ->middleware('permiso:submodulo.empresa.store')
    ->middleware('transform.input:'. EmpresaTransformer::class);
    Route::post('/administrador/empresa/{empresa}/update','Modulos\Empresa\EmpresaController@update')->name('submodulo.empresa.edit.ajax')
    ->middleware('permiso:submodulo.empresa.edit')
    ->middleware('transform.input:'. EmpresaTransformer::class);
    Route::post('/administrador/empresa/{empresa}/eliminar','Modulos\Empresa\EmpresaController@delete')->name('submodulo.empresa.delete.ajax')
    ->middleware('permiso:submodulo.empresa.delete');

    //PERFIL
    Route::post('/perfil/usuario/{usuario}/update','Modulos\User\PerfilController@updatePerfil')->name('perfil.usuario.update')
    ->middleware('transform.input:'. UserTransformer::class);
    Route::post('/perfil/usuario/{usuario}/password/update','Modulos\User\PerfilController@updatePassword')->name('perfil.usuario-password.update')
    ->middleware('transform.input:'. UserTransformer::class);

    //SEGURIDAD
    Route::post('/administrador/seguridad/{parametro}/update','Modulos\Seguridad\SeguridadController@update')->name('modulo.seguridad.update.ajax');
    //->middleware('permiso:modulo.seguridad.index');
 
    //ADMINISTRADOR MULTICONSULTA
    Route::post('/administrador/multiconsulta','Modulos\Multiconsulta\MulticonsultaController@search')->name('modulo.multiconsulta.index.search')
        ->middleware('permiso:modulo.multiconsulta.index');
    //Mapa
    Route::get('/administrador/multiconsulta/mapa/detalle','Modulos\Multiconsulta\MulticonsultaController@verMapa')->name('submodulo.multiconsulta.mapa.view')
        ->middleware('permiso:submodulo.multiconsulta.mapa.view');
    //Edificios
    Route::get('/administrador/multiconsulta/mapa/edificios/detalle','Modulos\Multiconsulta\MulticonsultaController@verEdificios')->name('submodulo.multiconsulta.mapa.edificios.view')
        ->middleware('permiso:submodulo.multiconsulta.mapa.view');
    //Cliente Intraway - Datos Comerciales
    Route::get('/administrador/multiconsulta/intraway/detalle','Modulos\Multiconsulta\MulticonsultaController@searchClientIntraway')->name('submodulo.multiconsulta.intraway.view')
        ->middleware('permiso:submodulo.multiconsulta.intraway.view');
    //Cliente Intraway - Histórico Conectividad
    Route::get('/administrador/multiconsulta/intraway/historico-conectividad/detalle','Modulos\Multiconsulta\MulticonsultaController@historicoConectIntraway')->name('submodulo.multiconsulta.intraway.historico-conectividad.view')
        ->middleware('permiso:submodulo.multiconsulta.intraway.view');
    //Diagnostico Masivo
    Route::get('/administrador/multiconsulta/diagnostico-masivo/detalle','Modulos\DiagnosticoMasivo\DiagnosticoMasivoController@lista')->name('submodulo.multiconsulta.diagnostico-masivo.view')
        ->middleware('permiso:submodulo.multiconsulta.diagnostico-masivo.view');
    //Grafica down
    Route::post('/administrador/multiconsulta/grafico-trafico-down/detalle','Modulos\Multiconsulta\MulticonsultaController@graficoDownstream')->name('submodulo.multiconsulta.grafico-trafico-down.view')
        ->middleware('permiso:submodulo.multiconsulta.grafico-trafico-down.view');
    //Reset cm Intraway
    Route::post('/administrador/multiconsulta/reset-cm-reaprovisionamiento/detalle','Modulos\Multiconsulta\MulticonsultaController@resetCmReaprovisionamiento')->name('submodulo.multiconsulta.reset-cm-reaprovisionamiento.view')
        ->middleware('permiso:submodulo.multiconsulta.reset-cm-reaprovisionamiento.view');
    //Reset Decos
    Route::get('/administrador/multiconsulta/reset-decos/detalle','Modulos\Multiconsulta\MulticonsultaController@getDataResetDeco')->name('submodulo.multiconsulta.reset-decos.view')
        ->middleware('permiso:submodulo.multiconsulta.reset-decos.view');
    Route::post('/administrador/multiconsulta/reset-decos/trama','Modulos\Multiconsulta\MulticonsultaController@resetDecoTrama')->name('submodulo.multiconsulta.reset-deco.trama')
        ->middleware('permiso:submodulo.multiconsulta.reset-decos.view');
    Route::post('/administrador/multiconsulta/reset-decos/tramas','Modulos\Multiconsulta\MulticonsultaController@resetDecosTrama')->name('submodulo.multiconsulta.reset-decos.tramas')
        ->middleware('permiso:submodulo.multiconsulta.reset-decos.view');
    //Cambiar Velocidad
    Route::get('/administrador/multiconsulta/velocidad-cm/detalle','Modulos\Multiconsulta\MulticonsultaController@getDataVelocidadCm')->name('submodulo.multiconsulta.velocidad-cm.view')
        ->middleware('permiso:submodulo.multiconsulta.velocidad-cm.view');
    Route::post('/administrador/multiconsulta/velocidad-cm/update','Modulos\Multiconsulta\MulticonsultaController@cambiarVelocidadCm')->name('submodulo.multiconsulta.velocidad-cm.update')
        ->middleware('permiso:submodulo.multiconsulta.velocidad-cm.view');
    //Activar Cm
    Route::post('/administrador/multiconsulta/activar-cm/detalle','Modulos\Multiconsulta\MulticonsultaController@activarCm')->name('submodulo.multiconsulta.activar-cm.view')
        ->middleware('permiso:submodulo.multiconsulta.activar-cm.view');
    //ScopeGroup cambio de IP
    Route::post('/administrador/multiconsulta/scopegroup-cm-intraway/detalle','Modulos\Multiconsulta\MulticonsultaController@cambioScopeGroup')->name('submodulo.multiconsulta.scopegroup-cm.view')
        ->middleware('permiso:submodulo.multiconsulta.scopegroup-cm.view');
    //Historico Niveles Troba
    Route::post('/administrador/multiconsulta/historico/niveles/troba','Modulos\Multiconsulta\MulticonsultaController@historicoNivelesTroba')->name('submodulo.multiconsulta.historico-masivo-trobas.view')
        ->middleware('permiso:submodulo.multiconsulta.historico-masivo-trobas.view');
    //Historico Caidas Troba
    Route::post('/administrador/multiconsulta/historico/caidas/troba','Modulos\Multiconsulta\MulticonsultaController@historicoCaidasTroba')->name('submodulo.multiconsulta.historico-caidas-trobas.view')
        ->middleware('permiso:submodulo.multiconsulta.historico-caidas-trobas.view');
        //Telefono Registro y Actualización
    Route::post('/administrador/multiconsulta/telefono/store-update','Modulos\Multiconsulta\MulticonsultaController@telefonoStoreUpdate')->name('submodulo.multiconsulta.telefono.store-update')
        ->middleware('permiso:modulo.multiconsulta.index');
        //Pre Agenda
    Route::get('/administrador/multiconsulta/agenda/detalle','Modulos\Multiconsulta\MulticonsultaController@agendaDetalle')->name('submodulo.multiconsulta.agenda.detalle')
        ->middleware('permiso:submodulo.multiconsulta.agenda.view');
    Route::get('/administrador/multiconsulta/agenda/verificar-turno','Modulos\Multiconsulta\MulticonsultaController@verificarTurnoAgenda')->name('submodulo.multiconsulta.agenda.detalle')
        ->middleware('permiso:submodulo.multiconsulta.agenda.view');
    Route::get('/administrador/multiconsulta/agenda/verificar-cupos','Modulos\Multiconsulta\MulticonsultaController@verificarCuposAgenda')->name('submodulo.multiconsulta.agenda.detalle')
        ->middleware('permiso:submodulo.multiconsulta.agenda.view');
    Route::post('/administrador/multiconsulta/agenda/quitar-cupos','Modulos\Multiconsulta\MulticonsultaController@retirarCupoTemporalReservado')->name('submodulo.multiconsulta.agenda.quitar')
        ->middleware('permiso:submodulo.multiconsulta.agenda.view');
    Route::post('/administrador/multiconsulta/agenda/store','Modulos\Multiconsulta\MulticonsultaController@registroPreAgenda')->name('submodulo.multiconsulta.agenda.store')
        ->middleware('permiso:submodulo.multiconsulta.agenda.view');
    //Historico Niveles Interfaz
    Route::get('/administrador/multiconsulta/historico/ruidos-interfaz','Modulos\AveriasCoe\AveriasCoeController@ruidosInterfaz')->name('submodulo.multiconsulta.historico.ruido-interfaz')
        ->middleware('permiso:modulo.multiconsulta.index');



    //MULTICONSULTA ARBOL DE DECISIONES
     Route::get('/administrador/multiconsulta/arbol-decision','Modulos\Multiconsulta\ArbolDecisionesController@index')->name('submodulo.multiconsulta.arbol-decisiones.view')
     ->middleware('permiso:submodulo.multiconsulta.arbol-decisiones.view');
     Route::get('/administrador/multiconsulta/arbol-decision/por-mensaje','Modulos\Multiconsulta\ArbolDecisionesController@indexPorMensaje')->name('submodulo.multiconsulta.arbol-decisiones.por-mensaje.view')
     ->middleware('permiso:submodulo.multiconsulta.arbol-decisiones.view');
     Route::get('/administrador/multiconsulta/arbol-decision/paso/{paso}/detalles','Modulos\Multiconsulta\ArbolDecisionesController@detallesDecision')->name('submodulo.multiconsulta.arbol-decisiones.pasos.detalles.view')
     ->middleware('permiso:submodulo.multiconsulta.arbol-decisiones.view');
     Route::post('/administrador/multiconsulta/arbol-decision/registros','Modulos\Multiconsulta\ArbolDecisionesController@registrosDecision')->name('submodulo.multiconsulta.arbol-decisiones.registros.view')
     ->middleware('permiso:submodulo.multiconsulta.arbol-decisiones.view');

    //ADMINISTRADOR ALBOL DE DECISIONES 
    Route::get('/administrador/arbol-decision/paso-anterior/{paso}/show','Modulos\Arbol\AdminArbolController@showPasoAnterior')->name('submodulo.arbol-decision.paso-anterior.show')
        ->middleware('permiso:submodulo.arbol-decision.pasos.show');
    Route::post('/administrador/arbol-decision/lista-pasos','Modulos\Arbol\AdminArbolController@listaPasos')->name('modulo.arbol-decision.listar-pasos')
        ->middleware('permiso:modulo.arbol-decision.index');
    Route::get('/administrador/arbol-decision/ramas/estructura','Modulos\Arbol\AdminArbolController@estructuraRama')->name('submodulo.arbol-decision.rama.estructura')
        ->middleware('permiso:submodulo.arbol-decision.rama.estructura');
    Route::post('/administrador/arbol-decision/rama/store','Modulos\Arbol\AdminArbolController@storeRama')->name('submodulo.arbol-decision.rama.store')
        ->middleware('permiso:submodulo.arbol-decision.rama.store'); 
    Route::post('/administrador/arbol-decision/rama/edit','Modulos\Arbol\AdminArbolController@updateRama')->name('submodulo.arbol-decision.rama.edit')
        ->middleware('permiso:submodulo.arbol-decision.rama.edit');
    Route::post('/administrador/arbol-decision/rama/delete','Modulos\Arbol\AdminArbolController@deleteRama')->name('submodulo.arbol-decision.rama.delete')
        ->middleware('permiso:submodulo.arbol-decision.rama.delete');
        

    //CABLEMODEM
    Route::get('/administrador/multiconsulta/search/cablemodem','Modulos\Multiconsulta\CablemodemController@status')->name('module.multiconsulta.search.cablemodem')
        ->middleware('permiso:modulo.multiconsulta.index');
    Route::get('/administrador/multiconsulta/search/cablemodem2','Modulos\Multiconsulta\CablemodemController@dhcp')->name('submodulo.multiconsulta.cm.dhcp.view')
        ->middleware('permiso:submodulo.multiconsulta.cm.dhcp.view');
    Route::get('/administrador/multiconsulta/search/wifivecino','Modulos\Multiconsulta\CablemodemController@wifivecino')->name('submodulo.multiconsulta.cm.wifi-vecinos.view')
        ->middleware('permiso:submodulo.multiconsulta.cm.wifi-vecinos.view');
    Route::get('/administrador/multiconsulta/search/wifi','Modulos\Multiconsulta\CablemodemController@wifi')->name('submodulo.multiconsulta.cm.config-wifi.view')
        ->middleware('permiso:submodulo.multiconsulta.cm.config-wifi.view');
    Route::get('/administrador/multiconsulta/search/updatewifi','Modulos\Multiconsulta\CablemodemController@updatewifi')->name('submodulo.multiconsulta.cm.config-wifi.update.view')
        ->middleware('permiso:submodulo.multiconsulta.cm.config-wifi.update.view');
    Route::get('/administrador/multiconsulta/search/updatewifi5G','Modulos\Multiconsulta\CablemodemController@updatewifi5G')->name('submodulo.multiconsulta.cm.config-wifi.update.view')
        ->middleware('permiso:submodulo.multiconsulta.cm.config-wifi.update.view');
    Route::get('/administrador/multiconsulta/search/updatewifiHitron','Modulos\Multiconsulta\CablemodemController@updatewifiHitron')->name('submodulo.multiconsulta.cm.config-wifi.update.view')
        ->middleware('permiso:submodulo.multiconsulta.cm.config-wifi.update.view');
    Route::get('/administrador/multiconsulta/search/updatewifiUbee','Modulos\Multiconsulta\CablemodemController@updatewifiUbee')->name('submodulo.multiconsulta.cm.config-wifi.update.view')
        ->middleware('permiso:submodulo.multiconsulta.cm.config-wifi.update.view');
    Route::get('/administrador/multiconsulta/search/updatewifiSagem','Modulos\Multiconsulta\CablemodemController@updatewifiSagem')->name('submodulo.multiconsulta.cm.config-wifi.update.view')
        ->middleware('permiso:submodulo.multiconsulta.cm.config-wifi.update.view');
    Route::get('/administrador/multiconsulta/search/updatewifiCastlenet','Modulos\Multiconsulta\CablemodemController@updatewifiCastlenet')->name('submodulo.multiconsulta.cm.config-wifi.update.view')
        ->middleware('permiso:submodulo.multiconsulta.cm.config-wifi.update.view');
    Route::get('/administrador/multiconsulta/search/upnp','Modulos\Multiconsulta\CablemodemController@upnp')->name('submodulo.multiconsulta.cm.upnp.view')
        ->middleware('permiso:submodulo.multiconsulta.cm.upnp.view');
    Route::get('/administrador/multiconsulta/search/updateupnp','Modulos\Multiconsulta\CablemodemController@updateUpnp')->name('submodulo.multiconsulta.cm.config-upnp.update.view')
        ->middleware('permiso:submodulo.multiconsulta.cm.config-upnp.update.view');
    Route::get('/administrador/multiconsulta/search/dmz','Modulos\Multiconsulta\CablemodemController@dmz')->name('submodulo.multiconsulta.cm.dmz.view')
        ->middleware('permiso:submodulo.multiconsulta.cm.dmz.view');
    Route::get('/administrador/multiconsulta/search/updatedmz','Modulos\Multiconsulta\CablemodemController@updateDmz')->name('submodulo.multiconsulta.cm.config-dmz.update.view')
        ->middleware('permiso:submodulo.multiconsulta.cm.config-dmz.update.view');
    Route::get('/administrador/multiconsulta/search/updatedmzHitron','Modulos\Multiconsulta\CablemodemController@updateDmzHitron')->name('submodulo.multiconsulta.cm.config-dmz.update.view')
        ->middleware('permiso:submodulo.multiconsulta.cm.config-dmz.update.view');
    Route::get('/administrador/multiconsulta/search/updatedmzUbee','Modulos\Multiconsulta\CablemodemController@updateDmzUbee')->name('submodulo.multiconsulta.cm.config-dmz.update.view')
        ->middleware('permiso:submodulo.multiconsulta.cm.config-dmz.update.view');
    Route::get('/administrador/multiconsulta/search/diagnostico','Modulos\Multiconsulta\CablemodemController@diagnostico')->name('submodulo.multiconsulta.cm.config-diagnostico.update.view')
        ->middleware('permiso:submodulo.multiconsulta.cm.config-diagnostico.update.view');
    Route::get('/administrador/multiconsulta/search/reset','Modulos\Multiconsulta\CablemodemController@updateReset')->name('submodulo.multiconsulta.cm.config-reset.update.view')
        ->middleware('permiso:submodulo.multiconsulta.cm.config-reset.update.view');
    Route::get('/administrador/multiconsulta/search/maping','Modulos\Multiconsulta\CablemodemController@maping')->name('submodulo.multiconsulta.cm.maping.view')
        ->middleware('permiso:submodulo.multiconsulta.cm.maping.view');
    Route::get('/administrador/multiconsulta/search/updatemaping','Modulos\Multiconsulta\CablemodemController@updateMaping')->name('submodulo.multiconsulta.cm.config-maping.update.view')
        ->middleware('permiso:submodulo.multiconsulta.cm.config-maping.update.view'); 


    //MONITORE DE AVERIAS
    Route::get('/administrador/monitor-averias/lista','Modulos\MonitorAverias\MonitorAveriasController@lista')->name('modulo.monitor-averias.index-ajax')
        ->middleware('permiso:modulo.monitor-averias.index');
    Route::get('/administrador/monitor-averias/ultimo-update','Modulos\MonitorAverias\MonitorAveriasController@lastDateUpdate')->name('submodulo.monitor-averias.ultimo-update')
        ->middleware('permiso:modulo.monitor-averias.index');
    Route::get('/administrador/monitor-averias/diagnostico-masivo/view','Modulos\DiagnosticoMasivo\DiagnosticoMasivoController@lista')->name('submodulo.monitor-averias.diagnostico-masivo.view')
        ->middleware('permiso:submodulo.monitor-averias.diagnostico-masivo.view');
    Route::get('/administrador/monitor-averias/mapa/view','Modulos\MonitorAverias\MonitorAveriasController@verMapa')->name('submodulo.monitor-averias.mapa.view')
        ->middleware('permiso:submodulo.monitor-averias.mapa.view');
    Route::get('/administrador/monitor-averias/mapa/edificios/view','Modulos\MonitorAverias\MonitorAveriasController@verEdificios')->name('submodulo.monitor-averias.mapa.edificios.view')
        ->middleware('permiso:submodulo.monitor-averias.mapa.view');
    Route::get('/administrador/monitor-averias/gestion/requires','Modulos\Gestion\GestionController@requiresLoad')->name('submodulo.monitor-averias.gestion.requires-load.view')
        ->middleware('permiso:submodulo.monitor-averias.gestion-individual.store');
    Route::post('/administrador/monitor-averias/gestion-individual/store','Modulos\Gestion\GestionController@storeIndividual')->name('submodulo.monitor-averias.gestion-individual.store')
        ->middleware('permiso:submodulo.monitor-averias.gestion-individual.store');
    Route::get('/administrador/monitor-averias/historico/nodo-troba','GeneralController@historicoNodoTroba')->name('submodulo.monitor-averias.historico.nodo-troba')
        ->middleware('permiso:modulo.monitor-averias.index');
    Route::get('/administrador/monitor-averias/gestion-individual/detalle','Modulos\Gestion\GestionController@detalleMasiva')->name('submodulo.monitor-averias.gestion-individual.detalle-masiva')
        ->middleware('permiso:submodulo.monitor-averias.gestion-individual.store');

    //GESTION
    
    Route::get('/administrador/gestion/lista','Modulos\Gestion\GestionController@lista')->name('submodulo.gestion.lista');
    Route::post('/administrador/gestion/masiva-store','Modulos\Gestion\GestionController@storeMasiva')->name('submodulo.gestion.masiva.store');
     


    //VALICACIÓN DE SERVICIOS
    Route::post('/administrador/validacion-servicios/carga-archivo','Modulos\ValidacionServicio\ValidacionServiciosController@cargaArchivo')->name('modulo.validacion-servicios.carga-archivo')
        ->middleware('permiso:modulo.validacion-servicios.index');

    //LLAMADAS POR TROBA
    Route::get('/administrador/llamadas/lista','Modulos\Llamada\LlamadaController@lista')->name('modulo.llamadas.lista')
        ->middleware('permiso:modulo.llamadas.index'); 
    Route::get('/administrador/llamadas/trabajos-programados/view','Modulos\TrabajosProgramados\TrabajosProgramadosController@detallePorNodoTroba')->name('submodulo.llamadas.trabajos-programados.view')
        ->middleware('permiso:submodulo.llamadas.trabajos-programados.view');
    Route::get('/administrador/llamadas/gestion/requires','Modulos\Gestion\GestionController@requiresLoad')->name('submodulo.llamadas.gestion-individual.requires-load.view')
        ->middleware('permiso:submodulo.llamadas.gestion-individual.store');
    Route::post('/administrador/llamadas/gestion-individual/store','Modulos\Gestion\GestionController@storeIndividual')->name('submodulo.llamadas.gestion-individual.store')
        ->middleware('permiso:submodulo.llamadas.gestion-individual.store');
    Route::get('/administrador/llamadas/diagnostico-masivo/view','Modulos\DiagnosticoMasivo\DiagnosticoMasivoController@lista')->name('submodulo.llamadas.diagnostico-masivo.view')
        ->middleware('permiso:submodulo.llamadas.diagnostico-masivo.view'); 
    Route::get('/administrador/llamadas/mapa/view','Modulos\Mapa\MapaController@verMapa')->name('submodulo.llamadas.mapa.view')
        ->middleware('permiso:submodulo.llamadas.mapa.view');
    Route::get('/administrador/llamadas/gestion-individual/detalle','Modulos\Gestion\GestionController@detalleMasiva')->name('submodulo.llamadas.gestion-individual.detalle-masiva')
        ->middleware('permiso:submodulo.llamadas.gestion-individual.store');
    Route::get('/administrador/llamadas/mapa-call/view','Modulos\Mapa\MapaController@verMapaCall')->name('submodulo.llamadas.mapa-call.view')
        ->middleware('permiso:submodulo.llamadas.mapa-call.view');

    //CAIDAS
    Route::get('/administrador/caidas/lista','Modulos\Caidas\CaidasController@lista')->name('modulo.caidas.lista')
        ->middleware('permiso:modulo.caidas.index'); 
    Route::get('/administrador/caidas/diagnostico-masivo/view','Modulos\DiagnosticoMasivo\DiagnosticoMasivoController@lista')->name('submodulo.caidas.diagnostico-masivo.view')
        ->middleware('permiso:submodulo.caidas.diagnostico-masivo.view'); 
    Route::get('/administrador/caidas/mapa/view','Modulos\Mapa\MapaController@verMapa')->name('submodulo.caidas.mapa.view')
        ->middleware('permiso:submodulo.caidas.mapa.view'); 
    Route::get('/administrador/caidas/mapa/edificios/view','Modulos\Mapa\MapaController@verEdificios')->name('submodulo.caidas.mapa.edificios.view')
        ->middleware('permiso:submodulo.caidas.mapa.view'); 
    Route::get('/administrador/caidas/gestion/requires','Modulos\Gestion\GestionController@requiresLoad')->name('submodulo.caidas.gestion-individual.requires-load.view')
        ->middleware('permiso:submodulo.caidas.gestion-individual.store');
    Route::post('/administrador/caidas/gestion-individual/store','Modulos\Gestion\GestionController@storeIndividual')->name('submodulo.caidas.gestion-individual.store')
        ->middleware('permiso:submodulo.caidas.gestion-individual.store');
    Route::get('/administrador/caidas/gestion-individual/detalle','Modulos\Gestion\GestionController@detalleMasiva')->name('submodulo.caidas.gestion-individual.detalle-masiva')
        ->middleware('permiso:submodulo.caidas.gestion-individual.store');
    Route::get('/administrador/caidas/criticas/view','Modulos\Caidas\CaidasController@listaClientesCriticos')->name('submodulo.caidas.criticas.view')
        ->middleware('permiso:submodulo.caidas.criticas.view');
    Route::get('/administrador/caidas/trabajos-programados/view','Modulos\TrabajosProgramados\TrabajosProgramadosController@detallePorNodoTroba')->name('submodulo.caidas.trabajos-programados.view')
        ->middleware('permiso:submodulo.caidas.trabajos-programados.view');

    //DESCARGA CLIENTES TROBA
    Route::get('/administrador/descarga-clientes-troba/interfaces/list','Modulos\DescargaClientesTroba\DescargaClientesTrobaController@interfacesLista')->name('modulo.descarga-cliente-troba.interfaces-lista')
        ->middleware('permiso:modulo.descarga-cliente-troba.index'); 
    Route::get('/administrador/descarga-clientes-troba/filtro','Modulos\DescargaClientesTroba\DescargaClientesTrobaController@filtro')->name('modulo.descarga-cliente-troba.filtro')
        ->middleware('permiso:modulo.descarga-cliente-troba.index'); 
    Route::get('/administrador/descarga-clientes-troba/interface/cantidad-trobas','Modulos\DescargaClientesTroba\DescargaClientesTrobaController@cantidadTrobasPorInterface')->name('modulo.descarga-cliente-troba.interface.cantidad-trobas')
        ->middleware('permiso:modulo.descarga-cliente-troba.index'); 
    Route::get('/administrador/descarga-clientes-troba/diagnostico-masivo/view','Modulos\DiagnosticoMasivo\DiagnosticoMasivoController@lista')->name('submodulo.descarga-cliente-troba.diagnostico-masivo.view')
        ->middleware('permiso:modulo.descarga-cliente-troba.index'); 
    Route::get('/administrador/descarga-clientes-troba/historico/nodo-troba','GeneralController@historicoNodoTroba')->name('submodulo.descarga-cliente-troba.historico.nodo-troba')
        ->middleware('permiso:modulo.descarga-cliente-troba.index');
    Route::get('/administrador/descarga-clientes-troba/mapa/view','Modulos\Mapa\MapaController@verMapa')->name('submodulo.descarga-cliente-troba.mapa.view')
        ->middleware('permiso:modulo.descarga-cliente-troba.index'); 
    Route::get('/administrador/descarga-clientes-troba/mapa/edificios/view','Modulos\Mapa\MapaController@verEdificios')->name('submodulo.descarga-clientes-troba.mapa.edificios.view')
        ->middleware('permiso:modulo.descarga-cliente-troba.index'); 
    Route::get('/administrador/descarga-clientes-troba/excel/clientes-troba','Modulos\DescargaClientesTroba\DescargaExcelClientesTrobaController@clientesTroba')->name('submodulo.descarga-clientes-troba.excel.clientes-troba')
        ->middleware('permiso:modulo.descarga-cliente-troba.index'); 
    Route::get('/administrador/descarga-clientes-troba/excel/clientes-troba-puertos','Modulos\DescargaClientesTroba\DescargaExcelClientesTrobaController@trobasPorPuerto')->name('submodulo.descarga-clientes-troba.excel.clientes-troba-puerto')
        ->middleware('permiso:modulo.descarga-cliente-troba.index'); 
    Route::get('/administrador/descarga-clientes-troba/puerto/promedio-niveles-cmts','Modulos\DescargaClientesTroba\DescargaClientesTrobaController@promedioNivelesPorPuerto')->name('submodulo.descarga-clientes-troba.promedio.puerto.niveles-cmts')
        ->middleware('permiso:modulo.descarga-cliente-troba.index'); 
    Route::get('/administrador/descarga-clientes-troba/puerto/historico-niveles-cmts','Modulos\DescargaClientesTroba\DescargaClientesTrobaController@historicoNivelesCmtsPorPuerto')->name('submodulo.descarga-clientes-troba.historico.puerto.niveles-cmts')
        ->middleware('permiso:modulo.descarga-cliente-troba.index'); 
    Route::get('/administrador/descarga-clientes-troba/excel/puerto/srn-cablemodem','Modulos\DescargaClientesTroba\DescargaExcelClientesTrobaController@CableModemSnr')->name('submodulo.descarga-clientes-troba.excel.puerto.cablemodem-snr')
        ->middleware('permiso:modulo.descarga-cliente-troba.index'); 
     
    Route::get('/git/update', 'GitController@update')->name('git.update');  

    Route::get('/export_excel', 'ExcelController@index');
    Route::get('/export_excel/excel', 'Modulos\MonitorAverias\ExcelController@excel')->name('export_excel.excel');
    Route::get('/export_excel/excelDMPE', 'Modulos\MonitorAverias\ExcelController@excelDMPE')->name('export_excel.excelDMPE');
    Route::get('/export_excel/excelReverificar', 'Modulos\MonitorAverias\ExcelController@excelReverificar')->name('export_excel.excelReverificar');
    Route::get('/export_excel/excelSuspendidos', 'Modulos\MonitorAverias\ExcelController@excelSuspendidos')->name('export_excel.excelSuspendidos');
    Route::get('/export_excel/excelGestion', 'Modulos\MonitorAverias\ExcelController@excelGestion')->name('export_excel.excelGestion');
    Route::get('/export_excel/excelEstadoM', 'Modulos\MonitorAverias\ExcelController@excelEstadoM')->name('export_excel.excelEstadoM');
    Route::get('/export_excel/excelTotal', 'Modulos\MonitorAverias\ExcelController@excelTotal')->name('export_excel.excelTotal');
    Route::get('/export_excel/excelTotalGpon', 'Modulos\MonitorAverias\ExcelController@excelTotalGpon')->name('export_excel.excelTotalGpon');

    //Caidas Masivas Excel
    Route::get('/administrador/monitor-averias/excel/excelCaidasAlertasDown', 'Modulos\CaidasMasivas\CaidasMasivasExcelController@excelAlertasDown')->name('excel.excelCaidasAlertasDown');
    Route::get('/administrador/monitor-averias/excel/excelCaidasEnergia', 'Modulos\CaidasMasivas\CaidasMasivasExcelController@excelEnergia')->name('excel.excelCaidasEnergia');
    Route::get('/administrador/monitor-averias/excel/excelCaidasTotal', 'Modulos\CaidasMasivas\CaidasMasivasExcelController@excelCaidasTotal')->name('excel.excelCaidasTotal');

    //Llamadas Masivas Excel
    Route::get('/administrador/monitor-averias/excel/excelLlamadasTotal', 'Modulos\LlamadasMasivas\LlamadasMasivasExcelController@excelLlamadasTotal')->name('excel.excelLlamadasTotal');


    //PROBLEMAS SEÑAL
    Route::get('/administrador/problema-senal/lista','Modulos\ProblemaSenal\ProblemaSenalController@lista')->name('modulo.problema-senal.index-ajax')
        ->middleware('permiso:modulo.problema-senal.index');
    Route::get('/administrador/problema-senal/diagnostico-masivo/view','Modulos\DiagnosticoMasivo\DiagnosticoMasivoController@lista')->name('submodulo.problema-senal.diagnostico-masivo.view')
        ->middleware('permiso:submodulo.problema-senal.diagnostico-masivo.view');
    Route::get('/administrador/problema-senal/mapa/view','Modulos\ProblemaSenal\ProblemaSenalController@verMapa')->name('submodulo.problema-senal.mapa.view')
        ->middleware('permiso:submodulo.problema-senal.mapa.view');
    Route::get('/administrador/problema-senal/mapa/edificios/view','Modulos\ProblemaSenal\ProblemaSenalController@verEdificios')->name('submodulo.problema-senal.mapa.edificios.view')
        ->middleware('permiso:submodulo.problema-senal.mapa.view');
    Route::get('/administrador/problema-senal/gestion/requires','Modulos\Gestion\GestionController@requiresLoad')->name('submodulo.problema-senal.gestion.requires-load.view')
        ->middleware('permiso:submodulo.problema-senal.gestion-individual.store');
    Route::post('/administrador/problema-senal/gestion-individual/store','Modulos\Gestion\GestionController@storeIndividual')->name('submodulo.problema-senal.gestion-individual.store')
        ->middleware('permiso:submodulo.problema-senal.gestion-individual.store');
    Route::get('/administrador/problema-senal/criticas/view','Modulos\ProblemaSenal\ProblemaSenalController@listaClientesCriticos')->name('submodulo.problema-senal.criticas.view')
        ->middleware('permiso:submodulo.problema-senal.criticas.view');
    Route::get('/administrador/problema-senal/trabajos-programados/view','Modulos\TrabajosProgramados\TrabajosProgramadosController@detallePorNodoTroba')->name('submodulo.problema-senal.trabajos-programados.view')
        ->middleware('permiso:submodulo.problema-senal.trabajos-programados.view');


    //MASIVAS CMS
    Route::get('/administrador/masiva-cms/lista','Modulos\MasivaCMS\MasivaCmsController@lista')->name('modulo.masiva-cms.index-ajax')
        ->middleware('permiso:modulo.masiva-cms.index');
    Route::get('/administrador/masiva-cms/diagnostico-masivo/view','Modulos\DiagnosticoMasivo\DiagnosticoMasivoController@lista')->name('submodulo.masiva-cms.diagnostico-masivo.view')
        ->middleware('permiso:submodulo.masiva-cms.diagnostico-masivo.view');
    Route::get('/administrador/masiva-cms/mapa/view','Modulos\MasivaCMS\MasivaCmsController@verMapa')->name('submodulo.masiva-cms.mapa.view')
        ->middleware('permiso:submodulo.masiva-cms.mapa.view');
    Route::get('/administrador/masiva-cms/mapa/edificios/view','Modulos\MasivaCMS\MasivaCmsController@verEdificios')->name('submodulo.masiva-cms.mapa.edificios.view')
        ->middleware('permiso:submodulo.masiva-cms.mapa.view');
    Route::get('/administrador/masiva-cms/gestion/requires','Modulos\Gestion\GestionController@requiresLoad')->name('submodulo.masiva-cms.gestion.requires-load.view')
        ->middleware('permiso:submodulo.masiva-cms.gestion-individual.store');
    Route::post('/administrador/masiva-cms/gestion-individual/store','Modulos\Gestion\GestionController@storeIndividual')->name('submodulo.masiva-cms.gestion-individual.store')
        ->middleware('permiso:submodulo.masiva-cms.gestion-individual.store');
    Route::get('/administrador/masiva-cms/criticas/view','Modulos\MasivaCMS\MasivaCmsController@listaClientesCriticos')->name('submodulo.masiva-cms.criticas.view')
        ->middleware('permiso:submodulo.masiva-cms.criticas.view');
    Route::get('/administrador/masiva-cms/trabajos-programados/view','Modulos\TrabajosProgramados\TrabajosProgramadosController@detallePorNodoTroba')->name('submodulo.masiva-cms.trabajos-programados.view')
        ->middleware('permiso:submodulo.masiva-cms.trabajos-programados.view');
    Route::get('/administrador/masiva-cms/gestion-individual/detalle','Modulos\Gestion\GestionController@detalleMasiva')->name('submodulo.masiva-cms.gestion-individual.detalle-masiva')
        ->middleware('permiso:submodulo.masiva-cms.gestion-individual.store');
    Route::get('/administrador/masiva-cms/gestion-masiva/delete','Modulos\MasivaCMS\MasivaCmsController@eliminarMasivaCms')->name('submodulo.masiva-cms.gestion-masiva.delete')
        ->middleware('permiso:submodulo.masiva-cms.gestion-masiva.delete');
    Route::post('/administrador/masiva-cms/carga-masiva/view','Modulos\MasivaCMS\MasivaCmsController@cargaArchivo')->name('submodulo.masiva-cms.carga-archivo.view')
        ->middleware('permiso:submodulo.masiva-cms.cargar-masiva.view');

    //Masivas cms Excel
    Route::get('/administrador/monitor-averias/excel/excelCaidasMasivasAlertasDown', 'Modulos\MasivaCMS\MasivaCmsExcelController@excelAlertasDown')->name('excel.excelCaidasMasivasAlertasDown');
    Route::get('/administrador/masiva-cms/excel/excelCaidasMasivasTotal', 'Modulos\MasivaCMS\MasivaCmsExcelController@excelMasivasTotal')->name('excel.excelMasivasTotal');
    Route::get('/administrador/masiva-cms/excel/excelCaidasMasivasAveriasTotal', 'Modulos\MasivaCMS\MasivaCmsExcelController@excelAveriasTotal')->name('excel.excelCaidasMasivasAveriasTotal');
    
    
    //ESTADOS DE LOS MODEMS VIEW
    Route::get('/administrador/estados-modems/lista','Modulos\EstadosModems\EstadosModemsController@lista')->name('modulo.estados-modems.lista')
        ->middleware('permiso:modulo.estados-modems.index');
    Route::get('/administrador/estados-modems/excel/excelEstadosModems', 'Modulos\EstadosModems\ExcelController@excelEstadosModems')->name('excel.excelEstadosModems');

    //CONTEO DE LOS MODEMS VIEW
    Route::get('/administrador/conteo-modems/lista','Modulos\ConteoModems\ConteoModemsController@lista')->name('modulo.conteo-modems.lista')
        ->middleware('permiso:modulo.conteo-modems.index');


    //TRABAJOS PROGRAMADOS
    Route::get('/administrador/trabajos-programados/lista','Modulos\TrabajosProgramados\TrabajosProgramadosController@lista')->name('modulo.trabajos-programados.index-ajax')
    ->middleware('permiso:modulo.trabajos-programados.index');
    Route::get('/administrador/trabajos-programados/tipo-trabajo/{tipoTrabajo}/detalles','Modulos\TrabajosProgramados\TrabajosProgramadosController@detallesTipoTrabajo')->name('modulo.trabajos-programados.tipo-trabajos.detalles')
    ->middleware('permiso:submodulo.trabajos-programados.store');
    Route::post('/administrador/trabajos-programados/store','Modulos\TrabajosProgramados\TrabajosProgramadosController@store')->name('submodulo.trabajos-programados.store')
    ->middleware('permiso:submodulo.trabajos-programados.store');
    Route::get('/administrador/trabajos-programados/{item}/detalle','Modulos\TrabajosProgramados\TrabajosProgramadosController@detallePorItem')->name('submodulo.trabajos-programados.item.detalle')
    ->middleware('permiso:modulo.trabajos-programados.index');
    Route::post('/administrador/trabajos-programados/{item}/cancelar','Modulos\TrabajosProgramados\TrabajosProgramadosController@cancelarTP')->name('submodulo.trabajos-programados.cancelar')
    ->middleware('permiso:submodulo.trabajos-programados.cancelar');
    Route::post('/administrador/trabajos-programados/{item}/aperturar','Modulos\TrabajosProgramados\TrabajosProgramadosController@aperturarTP')->name('submodulo.trabajos-programados.aperturar')
    ->middleware('permiso:submodulo.trabajos-programados.aperturar');
    Route::post('/administrador/trabajos-programados/{item}/cerrar','Modulos\TrabajosProgramados\TrabajosProgramadosController@cerrarTP')->name('submodulo.trabajos-programados.cerrar')
    ->middleware('permiso:submodulo.trabajos-programados.cerrar');
    Route::get('/administrador/trabajos-programados/descargar/excel/clientes','Modulos\TrabajosProgramados\TrabajosProgramadosExcelController@clientesPorNodoTroba')->name('submodulo.trabajos-programados.descargar-clientes')
    ->middleware('permiso:submodulo.trabajos-programados.descargar-clientes');
    Route::get('/administrador/trabajos-programados/descargar/excel/total','Modulos\TrabajosProgramados\TrabajosProgramadosExcelController@descargaTotal')->name('submodulo.trabajos-programados.descarga-total')
    ->middleware('permiso:modulo.trabajos-programados.index');
    Route::get('/administrador/trabajos-programados/gestion/requires','Modulos\Gestion\GestionController@requiresLoad')->name('submodulo.trabajos-programados.gestion-individual.requires-load.view')
    ->middleware('permiso:submodulo.trabajos-programados.gestion-individual.store');
    Route::post('/administrador/trabajos-programados/gestion-individual/store','Modulos\Gestion\GestionController@storeIndividual')->name('submodulo.trabajos-programados.gestion-individual.store')
    ->middleware('permiso:submodulo.trabajos-programados.gestion-individual.store');
    Route::post('/administrador/trabajos-programados/mantenimiento/nodos-trobas','Modulos\TrabajosProgramados\TrabajosProgramadosController@MantenimientoTrobas')->name('submodulo.trabajos-programados.mantenimiento.nodo-trobas')
    ->middleware('permiso:submodulo.trabajos-programados.mantenimiento');
    Route::post('/administrador/trabajos-programados/mantenimiento/tipo-trabajo','Modulos\TrabajosProgramados\TrabajosProgramadosController@MantenimientoTipoTrabajo')->name('submodulo.trabajos-programados.mantenimiento.tipo-trabajo')
    ->middleware('permiso:submodulo.trabajos-programados.mantenimiento');
    Route::post('/administrador/trabajos-programados/mantenimiento/supervisor','Modulos\TrabajosProgramados\TrabajosProgramadosController@MantenimientoSupervisor')->name('submodulo.trabajos-programados.mantenimiento.supervisor')
    ->middleware('permiso:submodulo.trabajos-programados.mantenimiento');
    Route::get('/administrador/trabajos-programados/supervisor/{supervisor}/tipo-trabajos/list','Modulos\TrabajosProgramados\TrabajosProgramadosController@listaTipoTrabajoBySupervisor')->name('submodulo.trabajos-programados.mantenimiento.supervisor.tipo-trabajo.list')
    ->middleware('permiso:submodulo.trabajos-programados.mantenimiento');
    Route::post('/administrador/trabajos-programados/supervisor/{supervisor}/tipo-trabajos/update','Modulos\TrabajosProgramados\TrabajosProgramadosController@updateTipoTrabajoBySupervisor')->name('submodulo.trabajos-programados.mantenimiento.supervisor.tipo-trabajo.update')
    ->middleware('permiso:submodulo.trabajos-programados.mantenimiento');

    Route::get('/administrador/trabajos-programados/llamadas-nodo/excel/excelDMPE/', 'Modulos\LlamadasNodo\ExcelController@excelDMPE')->name('trabajos-programados.excel.llamadas-nodo.excelDMPE')
    ->middleware('permiso:modulo.trabajos-programados.index');
    Route::get('/administrador/trabajos-programados/llamadas-nodo/excel/excelAverias', 'Modulos\LlamadasNodo\ExcelController@excelAverias')->name('trabajos-programados.excel.llamadas-nodo.excelAverias')
    ->middleware('permiso:modulo.trabajos-programados.index');
    Route::post('/administrador/trabajos-programados/llamadas-troba/grafica', 'Modulos\TrabajosProgramados\TrabajosProgramadosController@graficaLlamadasTroba')->name('trabajos-programados.llamadas-troba.grafica')
    ->middleware('permiso:modulo.trabajos-programados.index');

    //MONITOR IPS VIEW
    Route::get('/administrador/monitor-ips/lista','Modulos\MonitorIPS\MonitorIPSController@lista')->name('modulo.monitor-ips.lista')
        ->middleware('permiso:modulo.monitor-ips.index');

    //DESCARGA DE CMTS
    Route::get('/administrador/descarga-cmts/lista','Modulos\DescargaCmts\DescargaCmtsController@lista')->name('modulo.descarga-cmts.lista')
        ->middleware('permiso:modulo.descarga-cmts.index');
    Route::get('/administrador/descarga-cmts/download','Modulos\DescargaCmts\DescargaCmtsController@descargarArchivos')->name('modulo.descarga-cmts.download');

    //SATURACION DOWN VIEW
    Route::post('/administrador/saturacion-down/grafico','Modulos\SaturacionDown\SaturacionDownController@graficoSaturacionDown')->name('submodulo.saturacion-down.grafico');
    Route::get('/administrador/saturacion-down/lista','Modulos\SaturacionDown\SaturacionDownController@lista')->name('modulo.saturacion-down.lista')
        ->middleware('permiso:modulo.saturacion-down.index'); 
    Route::get('/administrador/saturacion-down/grafico/view','Modulos\SaturacionDown\SaturacionDownController@grafico')->name('submodulo.saturacion-down.grafico.view')
        ->middleware('permiso:submodulo.saturacion-down.grafico.view');

    //MENSAJES AL OPERADOR
    Route::post('/administrador/mensajes-operador/file','Modulos\MensajesOperador\MensajesOperadorController@cargaArchivo')->name('submodulo.mensajes-operador.file')
        ->middleware('permiso:submodulo.mensajes-operador.file');

    //GESTION CUARENTENAS
    Route::get('/administrador/gestion-cuarentena/lista','Modulos\Cuarentenas\GestionCuarentenasController@lista')->name('modulo.gestion-cuarentena.index.lista')
    ->middleware('permiso:modulo.gestion-cuarentena.index');
    Route::get('/administrador/gestion-cuarentena/{cuarentena}/detalles','Modulos\Cuarentenas\GestionCuarentenasController@detalles')->name('modulo.gestion-cuarentena.detalles')
    ->middleware('permiso:submodulo.gestion-cuarentena.edit');
    Route::get('/administrador/gestion-cuarentena/{cuarentena}/clientes','Modulos\Cuarentenas\GestionCuarentenasController@listaClientesPorCuarentena')->name('modulo.gestion-cuarentena.index.lista-clientes')
    ->middleware('permiso:modulo.gestion-cuarentena.index');
    Route::get('/administrador/gestion-cuarentena/{cuarentena}/trobas','Modulos\Cuarentenas\GestionCuarentenasController@listaTrobasPorCuarentena')->name('modulo.gestion-cuarentena.index.lista-trobas')
    ->middleware('permiso:modulo.gestion-cuarentena.index');
    Route::get('/administrador/gestion-cuarentena/jefatura-trobas','Modulos\Cuarentenas\GestionCuarentenasController@trobasPorjefatura')->name('submodulo.gestion-cuarentena.requerimientos.crear')
    ->middleware('permiso:modulo.gestion-cuarentena.index');
    Route::post('/administrador/gestion-cuarentena/store','Modulos\Cuarentenas\GestionCuarentenasController@store')->name('submodulo.gestion-cuarentena.requerimientos.store')
    ->middleware('permiso:submodulo.gestion-cuarentena.store');
    Route::post('/administrador/gestion-cuarentena/{cuarentena}/update','Modulos\Cuarentenas\GestionCuarentenasController@update')->name('submodulo.gestion-cuarentena.edit')
    ->middleware('permiso:submodulo.gestion-cuarentena.edit');
    Route::post('/administrador/gestion-cuarentena/{cuarentena}/delete','Modulos\Cuarentenas\GestionCuarentenasController@delete')->name('Submodulo.gestion-cuarentena.delete')
    ->middleware('permiso:Submodulo.gestion-cuarentena.delete');
    Route::post('/administrador/gestion-cuarentena/store-file','Modulos\Cuarentenas\GestionCuarentenasController@saveFile')->name('Submodulo.gestion-cuarentena.store-file')
    ->middleware('permiso:Submodulo.gestion-cuarentena.store');

    //CUARENTENAS
    Route::get('/administrador/cuarentena/{cuarentena}/lista','Modulos\Cuarentenas\CuarentenasController@lista')->name('mmodulo.cuarentenas.index.lista')
    ->middleware('permiso:modulo.cuarentenas.index');
    Route::post('/administrador/cuarentena-general/gestion-individual/store','Modulos\Cuarentenas\CuarentenasController@storeGestionIndividual')->name('submodulo.cuarentenas.gestion-individual.store')
    ->middleware('permiso:submodulo.cuarentenas.gestion-individual.store');//Registro Gestion Individual de cliente en Listado
    Route::get('/administrador/cuarentenas-general/gestion-individual/lista','Modulos\Cuarentenas\CuarentenasController@listaGestionIndividual')->name('submodulo.cuarentenas.gestion-individual.lista')
    ->middleware('permiso:submodulo.cuarentenas.gestion-individual.store');//Listado Historico Gestion Individual de cliente en Listado
    Route::get('/administrador/cuarentenas/{cuarentena}/reportes-excel','Modulos\Cuarentenas\CuarentenasExcelController@TotalCuarentenas')->name('submodulo.cuarentenas.descarga.excel')
    ->middleware('permiso:modulo.cuarentenas.index');//Descarga general Cuarentenas Excel

    //ETIQUETADO DE PUERTOS
    Route::get('/administrador/etiquetado-puertos/lista','Modulos\EtiquetadoPuertos\EtiquetadoPuertosController@lista')->name('modulo.etiquetado-puertos.lista')
        ->middleware('permiso:modulo.etiquetado-puertos.index');
    Route::post('/administrador/etiquetado-puertos/actualizar','Modulos\EtiquetadoPuertos\EtiquetadoPuertosController@actualizar')->name('submodulo.etiquetado-puertos.actualizar')
        ->middleware('permiso:submodulo.etiquetado-puertos.actualizar');

    //INGRESO DE AVERIAS
    Route::post('/administrador/ingreso-averias/grafico-averias-jefatura','Modulos\IngresoAverias\IngresoAveriasController@graficoAveriasJefatura')->name('submodulo.ingreso-averias.grafico-averias-jefatura');
    Route::post('/administrador/ingreso-averias/grafico-averias-motivos','Modulos\IngresoAverias\IngresoAveriasController@graficoAveriasMotivos')->name('submodulo.ingreso-averias.grafico-averias-motivos');
    Route::get('/administrador/ingreso-averias/excel/excelAveriaReporte', 'Modulos\IngresoAverias\ExcelController@excelAveriaReporte')->name('excel.excelAveriaReporte');
    Route::get('/administrador/ingreso-averias/excel/excelAveriasDia', 'Modulos\IngresoAverias\ExcelController@excelAveriasDia')->name('excel.excelAveriasDia');
    Route::get('/administrador/ingreso-averias/excel/exportAveriasResumenIngresos', 'Modulos\IngresoAverias\ExcelController@exportAveriasResumenIngresos')->name('excel.ingreso-averias.exportAveriasResumenIngresos');
    Route::get('/administrador/ingreso-averias/excel/excelAveriasMotivos', 'Modulos\IngresoAverias\ExcelController@excelAveriasMotivos')->name('excel.ingreso-averias.excelAveriasMotivos');
    Route::get('/administrador/ingreso-averias/descarga-file/download','Modulos\IngresoAverias\IngresoAveriasController@descargarArchivos')->name('modulo.ingreso-averias.descargarArchivo');
    Route::get('/administrador/ingreso-averias/excel/excelExportAveriasMes', 'Modulos\IngresoAverias\ExcelController@excelExportAveriasMes')->name('excel.ingreso-averias.excelExportAveriasMes');
    Route::get('/administrador/ingreso-averias/jefatura-trobas','Modulos\IngresoAverias\IngresoAveriasController@trobasPorjefatura')->name('submodulo.ingreso-averias.trobasPorjefatura')
    ->middleware('permiso:modulo.ingreso-averias.index');

    //MIGRACION USUARIOS
    Route::get('/migracion/update/{area}', 'MigracionController@update')->name('migracion.update');
    Route::get('/migracion/migrar', 'MigracionController@index')->name('migracion.migrar');
    Route::get('/migracion/proceso', 'MigracionController@migrar')->name('migracion.migrar');

    //CUADRO DE MANDO HFC
    Route::get('/administrador/cuadro-mando/lista','Modulos\CuadroMandoHFC\CuadroMandoController@lista')->name('modulo.cuadro-mando.lista')
        ->middleware('permiso:modulo.cuadro-mando.index');

    //LLAMADAS POR NODO
    Route::get('/administrador/llamadas-nodo/lista','Modulos\LlamadasNodo\LlamadasNodoController@lista')->name('modulo.llamadas-nodo.lista')
        ->middleware('permiso:modulo.llamadas-nodo.index'); 
    Route::get('/administrador/llamadas-nodo/excel/excelDMPE', 'Modulos\LlamadasNodo\ExcelController@excelDMPE')->name('excel.llamadas-nodo.excelDMPE');
    Route::get('/administrador/llamadas-nodo/excel/excelAverias', 'Modulos\LlamadasNodo\ExcelController@excelAverias')->name('excel.llamadas-nodo.excelAverias');
    Route::get('/administrador/llamadas-nodo/excel/excelTotal','Modulos\LlamadasNodo\ExcelController@excelTotal')->name('modulo.llamadas-nodo.excelTotal');
    


    //MONITOR FUENTES
    Route::get('/administrador/monitor-fuentes/lista','Modulos\MonitorFuentes\MonitorFuentesController@lista')->name('modulo.monitor-fuentes.index-lista')
    ->middleware('permiso:modulo.monitor-fuentes.index');
    Route::post('/administrador/monitor-fuentes/grafico-fuentes','Modulos\MonitorFuentes\MonitorFuentesController@graficoFuentes')->name('submodulo.monitor-fuentes.grafico-fuentes.view')
    ->middleware('permiso:submodulo.monitor-fuentes.grafico-fuentes.view');
    Route::get('/administrador/monitor-fuentes/mapa-fuentes','Modulos\Mapa\MapaController@mapaFuentes')->name('submodulo.monitor-fuentes.mapa-fuentes.view')
    ->middleware('permiso:submodulo.monitor-fuentes.mapa-fuentes.view');
    Route::get('/administrador/monitor-fuentes/mapa-fuentes/edificios/detalle','Modulos\Mapa\MapaController@verEdificios')->name('submodulo.monitor-fuentes.mapa-fuentes.edificio.detalle')
    ->middleware('permiso:submodulo.monitor-fuentes.mapa-fuentes.view');
    Route::get('/administrador/monitor-fuentes/diagnostico-masivo','Modulos\DiagnosticoMasivo\DiagnosticoMasivoController@lista')->name('submodulo.monitor-fuentes.diagnostico-masivo.view')
    ->middleware('permiso:submodulo.monitor-fuentes.diagnostico-masivo.view');
    Route::get('/administrador/monitor-fuentes/excel/historico-down','Modulos\MonitorFuentes\MonitorFuentesExcelController@fuenteHistoricaDown')->name('submodulo.monitor-fuentes.download.historico-down.view')
    ->middleware('permiso:submodulo.monitor-fuentes.download.historico-down.view');
    Route::get('/administrador/monitor-fuentes/excel/total','Modulos\MonitorFuentes\MonitorFuentesExcelController@descargaTotal')->name('submodulo.monitor-fuentes.download.total.view')
    ->middleware('permiso:modulo.monitor-fuentes.index');
    Route::get('/administrador/monitor-fuentes/editar','Modulos\MonitorFuentes\MonitorFuentesController@editar')->name('submodulo.monitor-fuentes.show')
    ->middleware('permiso:submodulo.monitor-fuentes.editar');
    Route::post('/administrador/monitor-fuentes/update','Modulos\MonitorFuentes\MonitorFuentesController@update')->name('submodulo.monitor-fuentes.editar')
    ->middleware('permiso:submodulo.monitor-fuentes.editar');
    Route::get('/administrador/monitor-fuentes/multilink','Modulos\MonitorFuentes\MonitorFuentesController@multilink')->name('submodulo.monitor-fuentes.multilink.detalles')
    ->middleware('permiso:submodulo.monitor-fuentes.multilink.detalles');
 
    //MAPA LLAMADAS PERU
    Route::get('/administrador/mapa-llamadas-peru/lista','Modulos\MapaLlamadasPeru\MapaLlamadasPeruController@grafico')->name('modulo.mapa-llamadas-peru.index.lista')
    ->middleware('permiso:modulo.mapa-llamadas-peru.index');
    Route::get('/administrador/mapa-llamadas-peru/graficoNiveles','Modulos\MapaLlamadasPeru\MapaLlamadasPeruController@graficoHistoricoNivelesCmtsPorPuerto')->name('modulo.mapa-llamadas-peru.index.grafico-niveles')
    ->middleware('permiso:modulo.mapa-llamadas-peru.index');
 
    //CONTENCION LLAMADAS
    Route::post('/administrador/contencion-llamadas/grafico-llamadas-contenidas','Modulos\ContencionLlamadas\ContencionLlamadasController@graficoLlamadasContenidas')->name('submodulo.contencion-llamadas.grafico');
    Route::get('/administrador/contencion-llamadas/descarga-file/download','Modulos\ContencionLlamadas\ContencionLlamadasController@descargarArchivos')->name('modulo.contencion-llamadas.descargarArchivo');

    //DIAGNOSTICO OUTSIDE
    Route::get('/administrador/diagnostico-outside/lista','Modulos\DiagnosticoOutside\DiagnosticoOutsideController@verMapaOutside')->name('modulo.diagnostico-outside.index.lista')
    ->middleware('permiso:modulo.diagnostico-outside.index');
    Route::get('/administrador/diagnostico-outside/diagnostico','Modulos\DiagnosticoOutside\DiagnosticoOutsideController@consultaClientes')->name('modulo.diagnostico-outside.index.diagnostico');
 
    //GRAFICA LLAMADAS NODOS
    Route::post('/administrador/grafica-llamadas-nodos/graficas-nodos-lineal','Modulos\GraficaLlamadasNodos\GraficaLlamadasNodosController@graficasNodosLineales')->name('submodulo.grafica-llamadas-nodos.grafico');
    Route::get('/administrador/grafica-llamadas-nodos/lista-nodos-graficas','Modulos\GraficaLlamadasNodos\GraficaLlamadasNodosController@listaNodosGraficasNodosLineales')->name('submodulo.lista-llamadas-nodos.grafico');
    Route::get('/administrador/grafica-llamadas-nodos/jefatura-nodos','Modulos\GraficaLlamadasNodos\GraficaLlamadasNodosController@nodosPorjefatura')->name('submodulo.lista-llamadas-nodos.nodosPorjefatura');

    //SEGUIMIENTO LLAMADAS
    Route::post('/administrador/seguimiento-llamadas/grafico-llamadas-contenidas','Modulos\SeguimientoLlamadas\SeguimientoLlamadasController@graficoLlamadasContenidas')->name('submodulo.seguimiento-llamadas.grafico');
    
    //GRAFICA LLAMADAS NODOS DIA
    Route::get('/administrador/grafica-llamadas-nodos-dia/lista-nodos-graficas','Modulos\GraficaLlamadasNodosDia\GraficaLlamadasNodosDiaController@listaNodosGraficasNodosDia')->name('submodulo.lista-llamadas-nodos-dia.grafico');
    Route::post('/administrador/grafica-llamadas-nodos-dia/graficas-nodos-barras','Modulos\GraficaLlamadasNodosDia\GraficaLlamadasNodosDiaController@graficasNodosBarras')->name('submodulo.grafica-llamadas-nodos-dia.grafico');
    Route::get('/administrador/grafica-llamadas-nodos-dia/jefatura-nodos','Modulos\GraficaLlamadasNodosDia\GraficaLlamadasNodosDiaController@nodosPorjefatura')->name('submodulo.lista-llamadas-nodos-dia.nodosPorjefatura');

    //GRAFICA VISOR DE AVERÍAS
    Route::get('/administrador/grafica-visor-averias/lista-nodos-graficas','Modulos\GraficaVisorAverias\GraficaVisorAveriasController@listaNodosGraficasVisorAverias')->name('submodulo.lista-nodos-visor-averias.grafico');
    Route::post('/administrador/grafica-visor-averias/graficas-visor-averias-barras','Modulos\GraficaVisorAverias\GraficaVisorAveriasController@graficasVisorAveriasBarras')->name('submodulo.grafica-visor-averias-barras.grafico');
    Route::get('/administradorgrafica-visor-averias/excel/excelVisorAverias', 'Modulos\GraficaVisorAverias\ExcelController@excelVisorAverias')->name('excel.excelVisorAverias');
    

    //MONITOR PERFORMANCE
    Route::post('/administrador/monitor-performance/grafico-monitor-performance','Modulos\MonitorPerformance\MonitorPerformanceController@graficoPerformanceApache')->name('submodulo.monitor-performance.grafico');
    Route::get('/administrador/monitor-performance/lista','Modulos\MonitorPerformance\MonitorPerformanceController@lista')->name('modulo.monitor-performance.lista')
        ->middleware('permiso:modulo.performance.index');
    Route::get('/administrador/monitor-performance/listaGuardian','Modulos\MonitorPerformance\MonitorPerformanceController@listaGuardian')->name('modulo.monitor-performance.listaGuardian')
        ->middleware('permiso:modulo.performance.index');
    Route::get('/administrador/monitor-performance/kill','Modulos\MonitorPerformance\MonitorPerformanceController@eliminarProceso')->name('submodulo.monitor-performance.kill');


    //AGENDAS
    Route::get('/administrador/agendas/lista','Modulos\Agendas\AgendasController@lista')->name('modulo.agendas.lista')
    ->middleware('permiso:modulo.agendas.index');
    Route::post('/administrador/agendas/gestion/store','Modulos\Agendas\AgendasController@storeGestionAgenda')->name('submodulo.agendas.gestion.store')
    ->middleware('permiso:submodulo.agendas.gestion.store');
    Route::get('/administrador/agendas/gestion/lista','Modulos\Agendas\AgendasController@listaGestionAgenda')->name('submodulo.agendas.gestion.lista')
    ->middleware('permiso:submodulo.agendas.gestion.store');
    Route::get('/administrador/agendas/reporte/excel/total','Modulos\Agendas\AgendasExcelController@agendaTotal')->name('submodulo.agendas.gestion.excel.total')
    ->middleware('permiso:modulo.agendas.index');
    Route::get('/administrador/agendas/reporte/excel/ultima-semana','Modulos\Agendas\AgendasExcelController@agendaUltimaSemana')->name('submodulo.agendas.gestion.excel.ultima-semana')
    ->middleware('permiso:modulo.agendas.index');


    //AVERIAS COE
    Route::post('/administrador/averias-coe/lista','Modulos\AveriasCoe\AveriasCoeController@lista')->name('modulo.averias-coe.lista')
    ->middleware('permiso:modulo.averias-coe.index');
    Route::get('/administrador/averias-coe/diagnostico-masivo/view','Modulos\DiagnosticoMasivo\DiagnosticoMasivoController@lista')->name('submodulo.averias-coe.diagnostico-masivo.view')
    ->middleware('permiso:submodulo.averias-coe.diagnostico-masivo.view'); 
     //ScopeGroup cambio de IP
    Route::post('/administrador/averias-coe/scopegroup-cm-intraway/detalle','Modulos\Multiconsulta\MulticonsultaController@cambioScopeGroup')->name('submodulo.averias-coe.scopegroup.update')
    ->middleware('permiso:submodulo.averias-coe.scopegroup.update');
      //Reset cm Intraway
    Route::post('/administrador/averias-coe/reset-cm-reaprovisionamiento/detalle','Modulos\Multiconsulta\MulticonsultaController@resetCmReaprovisionamiento')->name('submodulo.averias-coe.reset-cm-iw.update')
    ->middleware('permiso:submodulo.averias-coe.reset-cm-iw.update');
    //Ver CM
    Route::get('/administrador/averias-coe/cm/estado/detalle','Modulos\Multiconsulta\CablemodemController@status')->name('submodulo.averias-coe.cm.estado.view')
        ->middleware('permiso:submodulo.averias-coe.cm.estado.view');
    Route::get('/administrador/averias-coe/cm/dhcp/detalle','Modulos\Multiconsulta\CablemodemController@dhcp')->name('submodulo.averias-coe.cm.dhcp.view')
        ->middleware('permiso:submodulo.averias-coe.cm.dhcp.view');
    Route::get('/administrador/averias-coe/cm/wifi-vecinos/detalle','Modulos\Multiconsulta\CablemodemController@wifivecino')->name('submodulo.averias-coe.cm.wifi-vecinos.view')
        ->middleware('permiso:submodulo.averias-coe.cm.wifi-vecinos.view');
    Route::get('/administrador/averias-coe/cm/config-wifi/detalle','Modulos\Multiconsulta\CablemodemController@wifi')->name('submodulo.averias-coe.cm.config-wifi.view')
        ->middleware('permiso:submodulo.averias-coe.cm.config-wifi.view');
    Route::get('/administrador/averias-coe/search/updatewifi','Modulos\Multiconsulta\CablemodemController@updatewifi')->name('submodulo.averias-coe.cm.config-wifi-general.update')
        ->middleware('permiso:submodulo.averias-coe.cm.config-wifi.update');
    Route::get('/administrador/averias-coe/search/updatewifi5G','Modulos\Multiconsulta\CablemodemController@updatewifi5G')->name('submodulo.averias-coe.cm.config-wifi-general.update')
        ->middleware('permiso:submodulo.averias-coe.cm.config-wifi.update');
    Route::get('/administrador/averias-coe/search/updatewifiHitron','Modulos\Multiconsulta\CablemodemController@updatewifiHitron')->name('submodulo.averias-coe.cm.config-wifi-hitron.update')
        ->middleware('permiso:submodulo.averias-coe.cm.config-wifi.update');
    Route::get('/administrador/averias-coe/search/updatewifiUbee','Modulos\Multiconsulta\CablemodemController@updatewifiUbee')->name('submodulo.averias-coe.cm.config-wifi-ubee.update')
        ->middleware('permiso:submodulo.averias-coe.cm.config-wifi.update');
    Route::get('/administrador/averias-coe/search/updatewifiSagem','Modulos\Multiconsulta\CablemodemController@updatewifiSagem')->name('submodulo.averias-coe.cm.config-wifi-sagem.update')
        ->middleware('permiso:submodulo.averias-coe.cm.config-wifi.update');
    Route::get('/administrador/averias-coe/search/updatewifiCastlenet','Modulos\Multiconsulta\CablemodemController@updatewifiCastlenet')->name('submodulo.averias-coe.cm.config-wifi-castlenet.update')
        ->middleware('permiso:submodulo.averias-coe.cm.config-wifi.update');
    Route::get('/administrador/averias-coe/cm/upnp/detalle','Modulos\Multiconsulta\CablemodemController@upnp')->name('submodulo.averias-coe.cm.upnp.view')
        ->middleware('permiso:submodulo.averias-coe.cm.upnp.view');
    Route::get('/administrador/averias-coe/search/updateupnp','Modulos\Multiconsulta\CablemodemController@updateUpnp')->name('submodulo.averias-coe.cm.config-upnp.update.view')
        ->middleware('permiso:submodulo.averias-coe.cm.upnp.view');
    Route::get('/administrador/averias-coe/cm/dmz/detalle','Modulos\Multiconsulta\CablemodemController@dmz')->name('submodulo.averias-oe.cm.dmz.view')
        ->middleware('permiso:submodulo.averias-oe.cm.dmz.view');
    Route::get('/administrador/averias-coe/search/updatedmz','Modulos\Multiconsulta\CablemodemController@updateDmz')->name('submodulo.averias-coe.cm.config-dmz.update.view')
        ->middleware('permiso:submodulo.averias-oe.cm.dmz.view');
    Route::get('/administrador/averias-coe/search/updatedmzHitron','Modulos\Multiconsulta\CablemodemController@updateDmzHitron')->name('submodulo.averias-coe.cm.config-dmz.hitron.update.view')
        ->middleware('permiso:submodulo.averias-oe.cm.dmz.view');
    Route::get('/administrador/averias-coe/search/updatedmzUbee','Modulos\Multiconsulta\CablemodemController@updateDmzUbee')->name('submodulo.averias-coe.cm.config-dmz.ubee.update.view')
        ->middleware('permiso:submodulo.averias-oe.cm.dmz.view');
    Route::get('/administrador/averias-coe/cm/diagnostico/detalle','Modulos\Multiconsulta\CablemodemController@diagnostico')->name('submodulo.averias-coe.cm.diagnostico.view')
        ->middleware('permiso:submodulo.averias-coe.cm.diagnostico.view');
    Route::get('/administrador/averias-coe/cm/reset-scraping/detalle','Modulos\Multiconsulta\CablemodemController@updateReset')->name('submdoulo.averias-coe.cm.reset-scraping.view')
        ->middleware('permiso:submdoulo.averias-coe.cm.reset-scraping.view');
    Route::get('/administrador/averias-coe/cm/port-maping/detalle','Modulos\Multiconsulta\CablemodemController@maping')->name('submodulo.averias-coe.cm.port-maping.view')
        ->middleware('permiso:submodulo.averias-coe.cm.port-maping.view');
    Route::get('/administrador/averias-coe/search/updatemaping','Modulos\Multiconsulta\CablemodemController@updateMaping')->name('submodulo.averias-coe.cm.config-maping.update.view')
        ->middleware('permiso:submodulo.averias-coe.cm.port-maping.view'); 
   //Pre Agenda
    Route::get('/administrador/averias-coe/agenda/detalle','Modulos\Multiconsulta\MulticonsultaController@agendaDetalle')->name('submodulo.averias-coe.agenda.detalle')
    ->middleware('permiso:submodulo.averias-coe.cm.agenda.view');
    Route::get('/administrador/averias-coe/agenda/verificar-turno','Modulos\Multiconsulta\MulticonsultaController@verificarTurnoAgenda')->name('submodulo.averias-coe.agenda.detalle')
    ->middleware('permiso:submodulo.averias-coe.cm.agenda.view');
    Route::get('/administrador/averias-coe/agenda/verificar-cupos','Modulos\Multiconsulta\MulticonsultaController@verificarCuposAgenda')->name('submodulo.averias-coe.agenda.detalle')
    ->middleware('permiso:submodulo.averias-coe.cm.agenda.view');
    Route::post('/administrador/averias-coe/agenda/quitar-cupos','Modulos\Multiconsulta\MulticonsultaController@retirarCupoTemporalReservado')->name('submodulo.averias-coe.agenda.quitar')
    ->middleware('permiso:submodulo.averias-coe.cm.agenda.view');
    Route::post('/administrador/averias-coe/agenda/store','Modulos\Multiconsulta\MulticonsultaController@registroPreAgenda')->name('submodulo.averias-coe.agenda.store')
    ->middleware('permiso:submodulo.averias-coe.cm.agenda.view');
    //Historico Niveles Interfaz
    Route::get('/administrador/averias-coe/historico/ruidos-interfaz','Modulos\AveriasCoe\AveriasCoeController@ruidosInterfaz')->name('submodulo.averias-coe.historico.ruido-interfaz')
    ->middleware('permiso:modulo.averias-coe.index');
    //LlamadasNodo DMPE
    Route::get('/administrador/averias-coes/llamadas-nodo/excel/excelDMPE', 'Modulos\LlamadasNodo\ExcelController@excelDMPE')->name('submodulo.averias-coe.llamadas-nodo.excelDMPE')
    ->middleware('permiso:modulo.averias-coe.index');
    //Averias Ultimos dias
    Route::get('/administrador/averias-coes/averias/excel', 'Modulos\AveriasCoe\ExcelAveriasCoeController@averiasMUno')->name('submodulo.averias-coe.averias.m-uno')
    ->middleware('permiso:modulo.averias-coe.index');
    //GESTIÓN AVERIAS OE STORE
    Route::post('/administrador/averias-coe/gestion/store', 'Modulos\AveriasCoe\AveriasCoeController@storeGestion')->name('submodulo.averias-coe.gestion.store')
        ->middleware('permiso:submodulo.averias-coe.gestion.view');
    Route::get('/administrador/averias-coe/gestion/historico', 'Modulos\AveriasCoe\AveriasCoeController@historicoGestion')->name('submodulo.averias-coe.gestion.history')
        ->middleware('permiso:submodulo.averias-coe.gestion.view');
    



});


//WEBSERVICE
//Route::post('/wstest/server','Services\ServiceMulticonsulta\Multiconsulta1Controller@server');
//Route::any('/wstest3/server','Services\ServiceMulticonsulta\ServidorController@server');

