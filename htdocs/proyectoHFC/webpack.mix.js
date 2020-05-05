const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    //ADMINISTRADOR
    .js('resources/js/administrador/index.js','public/js/sistema/administrador/index.js')
    //USUARIOS
    .js('resources/js/administrador/modulos/user/index.js','public/js/sistema/modulos/users/index.min.js')
    .js('resources/js/administrador/modulos/user/show.js','public/js/sistema/modulos/users/show.min.js')
    .js('resources/js/administrador/modulos/user/edit.js','public/js/sistema/modulos/users/edit.min.js')
    .js('resources/js/administrador/modulos/user/store.js','public/js/sistema/modulos/users/store.min.js')
    .js('resources/js/administrador/modulos/user/delete.js','public/js/sistema/modulos/users/delete.min.js')
    //ROLES
    .js('resources/js/administrador/modulos/role/index.js','public/js/sistema/modulos/roles/index.min.js')
    .js('resources/js/administrador/modulos/role/show.js','public/js/sistema/modulos/roles/show.min.js')
    .js('resources/js/administrador/modulos/role/edit.js','public/js/sistema/modulos/roles/edit.min.js')
    .js('resources/js/administrador/modulos/role/edit-admin.js','public/js/sistema/modulos/roles/edit-admin.min.js')
    .js('resources/js/administrador/modulos/role/store.js','public/js/sistema/modulos/roles/store.min.js')
    .js('resources/js/administrador/modulos/role/store-admin.js','public/js/sistema/modulos/roles/store-admin.min.js')
    .js('resources/js/administrador/modulos/role/delete.js','public/js/sistema/modulos/roles/delete.min.js')
    //EMPRESAS
    .js('resources/js/administrador/modulos/empresa/index.js','public/js/sistema/modulos/empresas/index.min.js')
    .js('resources/js/administrador/modulos/empresa/show.js','public/js/sistema/modulos/empresas/show.min.js')
    .js('resources/js/administrador/modulos/empresa/edit.js','public/js/sistema/modulos/empresas/edit.min.js')
    .js('resources/js/administrador/modulos/empresa/store.js','public/js/sistema/modulos/empresas/store.min.js')
    .js('resources/js/administrador/modulos/empresa/delete.js','public/js/sistema/modulos/empresas/delete.min.js')
    //PERFIL
    .js('resources/js/administrador/perfil/perfil.js','public/js/sistema/perfil/perfil.min.js')
    //SEGURIDAD
    .js('resources/js/administrador/modulos/seguridad/index.js','public/js/sistema/modulos/seguridad/index.min.js')
    //MULTiCONSULTA
    .js('resources/js/administrador/modulos/multiconsulta/index.js','public/js/sistema/modulos/multiconsulta/index.min.js')
    .js('resources/js/administrador/modulos/multiconsulta/mapa.js','public/js/sistema/modulos/multiconsulta/mapa.min.js')
    .js('resources/js/administrador/modulos/multiconsulta/btn-intraway.js','public/js/sistema/modulos/multiconsulta/btn-intraway.min.js')
    .js('resources/js/administrador/modulos/multiconsulta/cablemodem.js','public/js/sistema/modulos/multiconsulta/cablemodem.min.js')
    .js('resources/js/administrador/modulos/multiconsulta/diagnostico-masivo.js','public/js/sistema/modulos/multiconsulta/diagnostico-masivo.min.js')
    .js('resources/js/administrador/modulos/multiconsulta/grafico-saturacion-downstream.js','public/js/sistema/modulos/multiconsulta/grafico-saturacion-downstream.min.js')
    .js('resources/js/administrador/modulos/multiconsulta/reset-cm-reaprovisionamiento.js','public/js/sistema/modulos/multiconsulta/reset-cm-reaprovisionamiento.min.js')
    .js('resources/js/administrador/modulos/multiconsulta/reset-decos.js','public/js/sistema/modulos/multiconsulta/reset-decos.min.js')
    .js('resources/js/administrador/modulos/multiconsulta/cambiar-velocidad.js','public/js/sistema/modulos/multiconsulta/cambiar-velocidad.min.js')
    .js('resources/js/administrador/modulos/multiconsulta/activar-cm.js','public/js/sistema/modulos/multiconsulta/activar-cm.min.js')
    .js('resources/js/administrador/modulos/multiconsulta/scopesgroup-cm.js','public/js/sistema/modulos/multiconsulta/scopesgroup-cm.min.js')
    .js('resources/js/administrador/modulos/multiconsulta/arbol-decisiones.js','public/js/sistema/modulos/multiconsulta/arbol-decisiones.min.js')
    .js('resources/js/administrador/modulos/multiconsulta/historico-niveles-trobas.js','public/js/sistema/modulos/multiconsulta/historico-niveles-trobas.min.js')
    .js('resources/js/administrador/modulos/multiconsulta/historico-caidas-trobas.js','public/js/sistema/modulos/multiconsulta/historico-caidas-trobas.min.js')
    .js('resources/js/administrador/modulos/multiconsulta/agenda.js','public/js/sistema/modulos/multiconsulta/agenda.min.js')
    
    //ARBOL DECISIONES
    .js('resources/js/administrador/modulos/arbol-decisiones/index.js','public/js/sistema/modulos/arbol-decisiones/index.min.js')
    .js('resources/js/administrador/modulos/arbol-decisiones/show.js','public/js/sistema/modulos/arbol-decisiones/show.min.js')
    .js('resources/js/administrador/modulos/arbol-decisiones/edit.js','public/js/sistema/modulos/arbol-decisiones/edit.min.js')
    .js('resources/js/administrador/modulos/arbol-decisiones/estructura.js','public/js/sistema/modulos/arbol-decisiones/estructura.min.js')
    .js('resources/js/administrador/modulos/arbol-decisiones/store.js','public/js/sistema/modulos/arbol-decisiones/store.min.js')
    .js('resources/js/administrador/modulos/arbol-decisiones/delete.js','public/js/sistema/modulos/arbol-decisiones/delete.min.js')

    //MONITOR AVERIAS
    .js('resources/js/administrador/modulos/monitor-averias/index.js','public/js/sistema/modulos/monitor-averias/index.min.js')
    .js('resources/js/administrador/modulos/monitor-averias/diagnostico-masivo.js','public/js/sistema/modulos/monitor-averias/diagnostico-masivo.min.js')
    .js('resources/js/administrador/modulos/monitor-averias/mapa.js','public/js/sistema/modulos/monitor-averias/mapa.min.js')
    .js('resources/js/administrador/modulos/monitor-averias/gestion-individual.js','public/js/sistema/modulos/monitor-averias/gestion-individual.min.js')
    .js('resources/js/administrador/modulos/monitor-averias/reporte-averias.js','public/js/sistema/modulos/monitor-averias/reporte-averias.min.js')
    .js('resources/js/administrador/modulos/monitor-averias/historial-gestion.js','public/js/sistema/modulos/monitor-averias/historial-gestion.min.js')
    .js('resources/js/globalResources/modulos/gestion-masiva.js','public/js/sistema/modulos/gestion/gestion-masiva.min.js')
    
    //CAIDAS
    .js('resources/js/administrador/modulos/caidas/index.js','public/js/sistema/modulos/caidas/index.min.js')
    .js('resources/js/administrador/modulos/caidas/diagnostico-masivo.js','public/js/sistema/modulos/caidas/diagnostico-masivo.min.js')
    .js('resources/js/administrador/modulos/caidas/mapa.js','public/js/sistema/modulos/caidas/mapa.min.js')
    .js('resources/js/administrador/modulos/caidas/gestion-individual.js','public/js/sistema/modulos/caidas/gestion-individual.min.js')
    .js('resources/js/administrador/modulos/caidas/reporte-caidas.js','public/js/sistema/modulos/caidas/reporte-caidas.min.js')
    .js('resources/js/administrador/modulos/caidas/historial-gestion.js','public/js/sistema/modulos/caidas/historial-gestion.min.js')
    .js('resources/js/administrador/modulos/caidas/clientes-criticos.js','public/js/sistema/modulos/caidas/clientes-criticos.min.js')
    .js('resources/js/administrador/modulos/caidas/trabajos-programados.js','public/js/sistema/modulos/caidas/trabajos-programados.min.js')
    
    //LLAMADAS
    .js('resources/js/administrador/modulos/llamadas/index.js','public/js/sistema/modulos/llamadas/index.min.js')
    .js('resources/js/administrador/modulos/llamadas/trabajos-programados.js','public/js/sistema/modulos/llamadas/trabajos-programados.min.js')
    .js('resources/js/administrador/modulos/llamadas/reporte-llamadas.js','public/js/sistema/modulos/llamadas/reporte-llamadas.min.js')
    .js('resources/js/administrador/modulos/llamadas/historial-gestion.js','public/js/sistema/modulos/llamadas/historial-gestion.min.js')
    .js('resources/js/administrador/modulos/llamadas/gestion-individual.js','public/js/sistema/modulos/llamadas/gestion-individual.min.js')
    .js('resources/js/administrador/modulos/llamadas/diagnostico-masivo.js','public/js/sistema/modulos/llamadas/diagnostico-masivo.min.js')
    .js('resources/js/administrador/modulos/llamadas/mapa.js','public/js/sistema/modulos/llamadas/mapa.min.js')
    .js('resources/js/administrador/modulos/llamadas/mapa-call.js','public/js/sistema/modulos/llamadas/mapa-call.min.js')

    //ESTADOS MODEMS
    .js('resources/js/administrador/modulos/estados-modems/index.js','public/js/sistema/modulos/estados-modems/index.min.js')
    .js('resources/js/administrador/modulos/estados-modems/reporte-estados-modems.js','public/js/sistema/modulos/estados-modems/reporte-estados-modems.min.js')

    //VALIDACION SERVICIOS
    .js('resources/js/administrador/modulos/validacion-servicio/index.js','public/js/sistema/modulos/gestion/validacion-servicio/index.min.js')

 
    //PROBLEMAS SEÑAL 
    .js('resources/js/administrador/modulos/problema-senal/index.js','public/js/sistema/modulos/problema-senal/index.min.js')
    .js('resources/js/administrador/modulos/problema-senal/diagnostico-masivo.js','public/js/sistema/modulos/problema-senal/diagnostico-masivo.min.js')    
    .js('resources/js/administrador/modulos/problema-senal/mapa.js','public/js/sistema/modulos/problema-senal/mapa.min.js')
    .js('resources/js/administrador/modulos/problema-senal/reporte-senal.js','public/js/sistema/modulos/problema-senal/reporte-senal.min.js')
    .js('resources/js/administrador/modulos/problema-senal/gestion-individual.js','public/js/sistema/modulos/problema-senal/gestion-individual.min.js')
    .js('resources/js/administrador/modulos/problema-senal/historial-gestion.js','public/js/sistema/modulos/problema-senal/historial-gestion.min.js')
    .js('resources/js/administrador/modulos/problema-senal/clientes-criticos.js','public/js/sistema/modulos/problema-senal/clientes-criticos.min.js')
    .js('resources/js/administrador/modulos/problema-senal/trabajos-programados.js','public/js/sistema/modulos/problema-senal/trabajos-programados.min.js')

    


    //DESCARGA CLIENTES TROBA
    .js('resources/js/administrador/modulos/descarga-clientes-troba/index.js','public/js/sistema/modulos/descarga-clientes-troba/index.min.js') 
    .js('resources/js/administrador/modulos/descarga-clientes-troba/interfaces.js','public/js/sistema/modulos/descarga-clientes-troba/interfaces.min.js') 


    //MASIVAS CMS
    .js('resources/js/administrador/modulos/masiva-cms/index.js','public/js/sistema/modulos/masiva-cms/index.min.js')
    .js('resources/js/administrador/modulos/masiva-cms/diagnostico-masivo.js','public/js/sistema/modulos/masiva-cms/diagnostico-masivo.min.js')    
    .js('resources/js/administrador/modulos/masiva-cms/mapa.js','public/js/sistema/modulos/masiva-cms/mapa.min.js')
    .js('resources/js/administrador/modulos/masiva-cms/reporte-masiva.js','public/js/sistema/modulos/masiva-cms/reporte-masiva.min.js')
    .js('resources/js/administrador/modulos/masiva-cms/gestion-individual.js','public/js/sistema/modulos/masiva-cms/gestion-individual.min.js')
    .js('resources/js/administrador/modulos/masiva-cms/historial-gestion.js','public/js/sistema/modulos/masiva-cms/historial-gestion.min.js')
    .js('resources/js/administrador/modulos/masiva-cms/clientes-criticos.js','public/js/sistema/modulos/masiva-cms/clientes-criticos.min.js')
    .js('resources/js/administrador/modulos/masiva-cms/trabajos-programados.js','public/js/sistema/modulos/masiva-cms/trabajos-programados.min.js')
    .js('resources/js/administrador/modulos/masiva-cms/eliminar-masiva.js','public/js/sistema/modulos/masiva-cms/eliminar-masiva.min.js')
    .js('resources/js/administrador/modulos/masiva-cms/carga-masiva.js','public/js/sistema/modulos/masiva-cms/carga-masiva.min.js')

 

    //TRABAJOS PROGRAMADOS
    .js('resources/js/administrador/modulos/trabajos-programados/index.js','public/js/sistema/modulos/trabajos-programados/index.min.js')
    .js('resources/js/administrador/modulos/trabajos-programados/store.js','public/js/sistema/modulos/trabajos-programados/store.min.js')
    .js('resources/js/administrador/modulos/trabajos-programados/cancelar.js','public/js/sistema/modulos/trabajos-programados/cancelar.min.js')
    .js('resources/js/administrador/modulos/trabajos-programados/aperturar.js','public/js/sistema/modulos/trabajos-programados/aperturar.min.js')
    .js('resources/js/administrador/modulos/trabajos-programados/cerrar.js','public/js/sistema/modulos/trabajos-programados/cerrar.min.js')
    .js('resources/js/administrador/modulos/trabajos-programados/descargar-clientes.js','public/js/sistema/modulos/trabajos-programados/descargar-clientes.min.js')
    .js('resources/js/administrador/modulos/trabajos-programados/gestion-individual.js','public/js/sistema/modulos/trabajos-programados/gestion-individual.min.js')
    .js('resources/js/administrador/modulos/trabajos-programados/historial-gestion.js','public/js/sistema/modulos/trabajos-programados/historial-gestion.min.js')
    .js('resources/js/administrador/modulos/trabajos-programados/mantenimiento.js','public/js/sistema/modulos/trabajos-programados/mantenimiento.min.js')
 


    //CONTEO MODEMS
    .js('resources/js/administrador/modulos/conteo-modems/index.js','public/js/sistema/modulos/conteo-modems/index.min.js')
 
    //MONITOR IPS
    .js('resources/js/administrador/modulos/monitor-ips/index.js','public/js/sistema/modulos/monitor-ips/index.min.js')

    //SATURACION DOWN
    .js('resources/js/administrador/modulos/saturacion-down/index.js','public/js/sistema/modulos/saturacion-down/index.min.js')
    .js('resources/js/administrador/modulos/saturacion-down/grafico.js','public/js/sistema/modulos/saturacion-down/grafico.min.js')
    .js('resources/js/administrador/modulos/saturacion-down/descarga.js','public/js/sistema/modulos/saturacion-down/descarga.min.js')

    //DESCARGA CMTS
    .js('resources/js/administrador/modulos/descarga-cmts/index.js','public/js/sistema/modulos/descarga-cmts/index.min.js')
    .js('resources/js/administrador/modulos/descarga-cmts/descarga.js','public/js/sistema/modulos/descarga-cmts/descarga.min.js')
 
    //MENSAJES OPERADOR
    .js('resources/js/administrador/modulos/mensajes-operador/index.js','public/js/sistema/modulos/mensajes-operador/index.min.js')
    .js('resources/js/administrador/modulos/mensajes-operador/subir-file.js','public/js/sistema/modulos/mensajes-operador/subir-file.min.js')

 
    //GESTION CUARENTENAS
    .js('resources/js/administrador/modulos/gestion-cuarentenas/index.js','public/js/sistema/modulos/gestion-cuarentenas/index.min.js')
    .js('resources/js/administrador/modulos/gestion-cuarentenas/store.js','public/js/sistema/modulos/gestion-cuarentenas/store.min.js')
    .js('resources/js/administrador/modulos/gestion-cuarentenas/edit.js','public/js/sistema/modulos/gestion-cuarentenas/edit.min.js')
    .js('resources/js/administrador/modulos/gestion-cuarentenas/delete.js','public/js/sistema/modulos/gestion-cuarentenas/delete.min.js')
    
    //CUARENTENAS
    .js('resources/js/administrador/modulos/cuarentenas/index.js','public/js/sistema/modulos/cuarentenas/index.min.js')
    .js('resources/js/administrador/modulos/cuarentenas/gestion-individual.js','public/js/sistema/modulos/cuarentenas/gestion-individual.min.js')


    //ETIQUETADO DE PUERTOS
    .js('resources/js/administrador/modulos/etiquetado-puertos/index.js','public/js/sistema/modulos/etiquetado-puertos/index.min.js')
    .js('resources/js/administrador/modulos/etiquetado-puertos/actualizar.js','public/js/sistema/modulos/etiquetado-puertos/actualizar.min.js')

    //INGRESO DE AVERIAS
    .js('resources/js/administrador/modulos/ingreso-averias/index.js','public/js/sistema/modulos/ingreso-averias/index.min.js')
    .js('resources/js/administrador/modulos/ingreso-averias/reporte-ingreso-averias.js','public/js/sistema/modulos/ingreso-averias/reporte-ingreso-averias.min.js')
    .js('resources/js/administrador/modulos/ingreso-averias/edit.js','public/js/sistema/modulos/ingreso-averias/edit.min.js')

    //CUADRO MANDO HFC
    .js('resources/js/administrador/modulos/cuadro-mando/index.js','public/js/sistema/modulos/cuadro-mando/index.min.js')
    .js('resources/js/administrador/modulos/cuadro-mando/consulta-modulo.js','public/js/sistema/modulos/cuadro-mando/consulta-modulo.min.js')
 
    
    //MONITOR DE FUENTES
    .js('resources/js/administrador/modulos/monitor-fuentes/index.js','public/js/sistema/modulos/monitor-fuentes/index.min.js')
    .js('resources/js/administrador/modulos/monitor-fuentes/grafico-fuentes.js','public/js/sistema/modulos/monitor-fuentes/grafico-fuentes.min.js')
    .js('resources/js/administrador/modulos/monitor-fuentes/mapa-fuentes.js','public/js/sistema/modulos/monitor-fuentes/mapa-fuentes.min.js')
    .js('resources/js/administrador/modulos/monitor-fuentes/diagnostico-masivo.js','public/js/sistema/modulos/monitor-fuentes/diagnostico-masivo.min.js')
    .js('resources/js/administrador/modulos/monitor-fuentes/alertas-down.js','public/js/sistema/modulos/monitor-fuentes/alertas-down.min.js')
    .js('resources/js/administrador/modulos/monitor-fuentes/historico-down.js','public/js/sistema/modulos/monitor-fuentes/historico-down.min.js')
    .js('resources/js/administrador/modulos/monitor-fuentes/edit.js','public/js/sistema/modulos/monitor-fuentes/edit.min.js')
    .js('resources/js/administrador/modulos/monitor-fuentes/multilink.js','public/js/sistema/modulos/monitor-fuentes/multilink.min.js')
 
    //MAPA CALL PERU
    .js('resources/js/administrador/modulos/mapa-llamadas-peru/index.js','public/js/sistema/modulos/mapa-llamadas-peru/index.min.js')
    


    //LLAMADAS POR NODO
    .js('resources/js/administrador/modulos/llamadas-nodos/index.js','public/js/sistema/modulos/llamadas-nodos/index.min.js')
    .js('resources/js/administrador/modulos/llamadas-nodos/reporte-llamadas-nodo.js','public/js/sistema/modulos/llamadas-nodos/reporte-llamadas-nodo.min.js')
 
    //CONTENCION LLAMADAS
    .js('resources/js/administrador/modulos/contencion-llamadas/index.js','public/js/sistema/modulos/contencion-llamadas/index.min.js')
    .js('resources/js/administrador/modulos/contencion-llamadas/reporte-contencion-llamadas.js','public/js/sistema/modulos/contencion-llamadas/reporte-contencion-llamadas.min.js')


    //DIAGNOSTICO OUTSIDE
    .js('resources/js/administrador/modulos/diagnostico-outside/index.js','public/js/sistema/modulos/diagnostico-outside/index.min.js')
    

    //GRÁFICA LLAMADAS NODOS
    .js('resources/js/administrador/modulos/grafica-llamadas-nodos/index.js','public/js/sistema/modulos/grafica-llamadas-nodos/index.min.js')
    .js('resources/js/administrador/modulos/grafica-llamadas-nodos/edit.js','public/js/sistema/modulos/grafica-llamadas-nodos/edit.min.js')

    //GRÁFICA LLAMADAS NODOS DÍA
    .js('resources/js/administrador/modulos/grafica-llamadas-nodos-dia/index.js','public/js/sistema/modulos/grafica-llamadas-nodos-dia/index.min.js')
    .js('resources/js/administrador/modulos/grafica-llamadas-nodos-dia/edit.js','public/js/sistema/modulos/grafica-llamadas-nodos-dia/edit.min.js')

    //GRÁFICA VISOR AVERÍAS
    .js('resources/js/administrador/modulos/grafica-visor-averias/index.js','public/js/sistema/modulos/grafica-visor-averias/index.min.js')
    .js('resources/js/administrador/modulos/grafica-visor-averias/edit.js','public/js/sistema/modulos/grafica-visor-averias/edit.min.js')
    .js('resources/js/administrador/modulos/grafica-visor-averias/reporte-averias.js','public/js/sistema/modulos/grafica-visor-averias/reporte-averias.min.js')
    
    //SEGUIMIENTO LLAMADAS
    .js('resources/js/administrador/modulos/seguimiento-llamadas/index.js','public/js/sistema/modulos/seguimiento-llamadas/index.min.js')
    .js('resources/js/administrador/modulos/seguimiento-llamadas/reporte-seguimiento-llamadas.js','public/js/sistema/modulos/seguimiento-llamadas/reporte-seguimiento-llamadas.min.js')
    
    //AGENDAS
    .js('resources/js/administrador/modulos/agenda/index.js','public/js/sistema/modulos/agenda/index.min.js')
    .js('resources/js/administrador/modulos/agenda/gestion.js','public/js/sistema/modulos/agenda/gestion.min.js')

    //MONITOR PERFORMANCE
    .js('resources/js/administrador/modulos/monitor-performance/index.js','public/js/sistema/modulos/monitor-performance/index.min.js')
    .js('resources/js/administrador/modulos/monitor-performance/kill-process.js','public/js/sistema/modulos/monitor-performance/kill-process.min.js')
    //AVERIAS - COE
    .js('resources/js/administrador/modulos/averias-coe/index.js','public/js/sistema/modulos/averias-coe/index.min.js')
    .js('resources/js/administrador/modulos/averias-coe/diagnostico-masivo.js','public/js/sistema/modulos/averias-coe/diagnostico-masivo.min.js')
    .js('resources/js/administrador/modulos/averias-coe/scopesgroup-cm.js','public/js/sistema/modulos/averias-coe/scopesgroup-cm.min.js')
    .js('resources/js/administrador/modulos/averias-coe/reset-cm-reaprovisionamiento.js','public/js/sistema/modulos/averias-coe/reset-cm-reaprovisionamiento.min.js')
    .js('resources/js/administrador/modulos/averias-coe/cablemodem.js','public/js/sistema/modulos/averias-coe/cablemodem.min.js')
    .js('resources/js/administrador/modulos/averias-coe/agenda.js','public/js/sistema/modulos/averias-coe/agenda.min.js')
    .js('resources/js/administrador/modulos/averias-coe/gestion.js','public/js/sistema/modulos/averias-coe/gestion.min.js')
   
    
    //MIGRACION
    .js('resources/js/administrador/modulos/migracion/index.js','public/js/sistema/modulos/migracion/index.min.js')
    



    .sass('resources/sass/app.scss', 'public/css')
    .sass('resources/sass/multiconsulta.scss', 'public/css/modulos/multiconsulta.css')
    .sass('resources/sass/cablemodem.scss', 'public/css/cablemodems/maping.css')
    .sass('resources/sass/arbol-decisiones-detalles.scss', 'public/css/modulos/arbol-decisiones-detalles.css')
    .sass('resources/sass/page-error-403.scss', 'public/css/page-error-403.css')
    .sass('resources/sass/page-error-404.scss', 'public/css/page-error-404.css')
    .sass('resources/sass/page-error-405.scss', 'public/css/page-error-405.css')
    .sass('resources/sass/page-error-navegador.scss', 'public/css/page-error-navegador.css')
    .sass('resources/sass/login.scss', 'public/css')
    .sass('resources/sass/bootstrap.scss', 'public/css');


mix.webpackConfig({
    resolve: {
        alias: {
            "@": path.resolve(
                __dirname,
                "resources/js"
            )
        }
    }
    });
