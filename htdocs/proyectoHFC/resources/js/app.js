/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
 


//window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

//Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

/*const app = new Vue({
    el: '#app',
});*/

/** Cerrar modal con enter el de error y success */

$('#errorsModal').on('shown.bs.modal', function (e) {
    $(this).keydown(function(e){
      if(e.which == 13) {
        $('#errorsModal').modal('hide');
      }
      });
  });
  $('#successModal').on('shown.bs.modal', function (e) {
    $(this).keydown(function(e){
      if(e.which == 13) {
        $('#successModal').modal('hide');
      }
      });
  });
  $('#reloadModal').on('shown.bs.modal', function (e) {
    $(this).keydown(function(e){
      if(e.which == 13) {
        $('#reloadModal').modal('hide');
      }
      });
  });
  $('#reloadModal').on('hidden.bs.modal', function (e) {
        location.reload();
  });


  //Cabezera de tablas en fullScreen fixed

  //var alturaBody = document.getElementById('aplicacion_content').contentWindow. document; 

 $(function(){
 
    //var alturaBody = $("#aplicacion_content").height()
    //var alturaNavHeader = $("#main_header").height()

    //const ALTURA_TABLA = alturaBody - alturaNavHeader

    //console.log("La altura ideal de la tabla es: ", ALTURA_TABLA)

   /* $(".maxi_tab").click(function(){
     // console.log("Estamo en el Maxi y debería ejecutarse... con esta altura: ",ALTURA_TABLA)
      
     // $(".fullscreen .tableFixHead").css({"height":`${ALTURA_TABLA}px`}) || $(".tableFixHead").css({"height":`400px`})
    })*/
   

 })

  
  
   
  /** fin modal cerrar con enter */
