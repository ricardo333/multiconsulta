/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 3);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/administrador/index.js":
/*!*********************************************!*\
  !*** ./resources/js/administrador/index.js ***!
  \*********************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _globalResources_errors__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @/globalResources/errors */ "./resources/js/globalResources/errors.js");

$(function () {
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  load_modulos();
  var filter_modulo = document.getElementById('filter_modulos');
  filter_modulo.addEventListener('keydown', function (e) {
    if (e.keyCode == 13) {
      load_modulos();
    }
  });
});

function load_modulos() {
  document.getElementById("listModulos").innerHTML = "<div id=\"carga_person\">\n                                                        <div class=\"loader\">Loading...</div>\n                                                      </div>";
  var filter = $("#filter_modulos").val();
  $.ajax({
    url: "/administrador/lista",
    method: "get",
    data: {
      nombre: filter
    },
    dataType: "json"
  }).done(function (data) {
    // console.log(data) 
    if (data.error) {
      $("#body-reload-modal").html("\n            <p>Hubo un error al cargar los modulos, se intentar\xE1 nuevamente!</p>\n          ");
      $("#reloadModal").modal("show");
      return false;
    } //console.log("la ruta es: ",data.response.data.length)


    if (data.response.data.length == 0) {
      // console.log("esta ingresando por vacio")
      $("#body-errors-modal").html("<p>No hay modulos disponibles asignados para su rol</p>");
      $("#errorsModal").modal("show");
    }

    var lista_modulos = data.response.data;
    var estructura = "";
    lista_modulos.forEach(function (el) {
      //col-6 col-sm-4 col-md-3 col-lg-3
      estructura += "<div class=\"col-6 col-sm-3 col-md-2 px-1 mb-2\">\n                            <a href=\"".concat(el.url, "\" class=\"text-decoration-none\">\n                              <div class=\"card h-100\">\n                                <div class=\"content-img-mod text-center d-flex\"><img class=\"card-img-top img-modulo-general\" src=\"").concat(el.imagen, "\" alt=\"Modulos publicos list\"></div>\n                                <div class=\"card-body p-1 text-center text_decoration_none d-flex align-items-center justify-content-center content-text-mod\">\n                                  <div class=\"font-weight-bold text-uppercase text_modulo_publico text-center\">").concat(el.nombre, "</div>\n                                </div>\n                              </div>\n                              </a>\n                              </div> "); //  <h6 class="font-weight-bold text-uppercase text_modulo_publico text-center">${el.nombre}</h6>
    });
    $("#listModulos").html(estructura);
  }).fail(function (jqXHR, textStatus) {
    console.log("error", jqXHR, textStatus);
    $("#listModulos").html("");
    /*if(jqXHR.responseJSON){
      let errors = jqXHR.responseJSON.message  //captura objeto
      //recorreo objeto como array
      let mensaje_error = errors.mensajeErrorJson(errors) 
      $("#body-reload-modal").html(`<p>${mensaje_error}.</p>`)
      $("#reloadModal").modal("show") 
      return false;
    }*/

    if (jqXHR.status) {
      var mensaje_error = _globalResources_errors__WEBPACK_IMPORTED_MODULE_0__["default"].codigos(jqXHR.status);
      $("#body-reload-modal").html("<p>".concat(mensaje_error, "</p>"));
      $("#reloadModal").modal("show");
      return false;
    }

    $("#body-reload-modal").html("<p>Falla inesperada con la petici\xF3n. Intente nuevamente.</p>");
    $("#reloadModal").modal("show");
  });
}

/***/ }),

/***/ "./resources/js/globalResources/errors.js":
/*!************************************************!*\
  !*** ./resources/js/globalResources/errors.js ***!
  \************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
var errors = {};

errors.codigos = function codigos(codigo) {
  var texto = "";

  if (codigo == 401) {
    location.reload();
    texto = "Sesi\xF3n terminada.";
  }

  switch (codigo) {
    case 204:
      texto = "La petici\xF3n se ha completado con \xE9xito pero su respuesta no tiene ning\xFAn contenido.";
      break;

    case 401:
      texto = "Su sesi\xF3n expiro.";
      break;

    case 403:
      texto = "Petici\xF3n no autoriazada.";
      break;

    case 404:
      texto = "Petici\xF3n no encontrada.";
      break;

    case 405:
      texto = "Error en el servicio. Intente nuevamente.";
      break;

    case 409:
      texto = "Conflicto de petici\xF3n en el servidor. Intente nuevamente. Si persiste el error, actualizar la Web.";
      break;

    case 422:
      texto = "No se puede procesar la petici\xF3n. Verifique los datos enviados.";
      break;

    case 500:
      texto = "Falla inesperada. Intente nuevamente.";
      break;

    default:
      texto = "Falla inesperada con la petici\xF3n. Intente nuevamente.";
      break;
  }

  return texto;
};

errors.mensajeErrorJson = function mensajeErrorJson(erroresJson) {
  //console.log("el tipo de mensaje es:",typeof(erroresJson),erroresJson)
  if (typeof erroresJson == "string") {
    return erroresJson;
  } //recorreo objeto como array


  var msj = "";
  Object.keys(erroresJson).forEach(function (key) {
    //console.log("El key es: -",key,"-")
    if (!isNaN(key)) {
      msj += "<li>".concat(erroresJson[key], "</li>");
    } else {
      msj += "".concat(key, " : ").concat(erroresJson[key], " <br/>");
    }
  });
  return msj;
};

/* harmony default export */ __webpack_exports__["default"] = (errors);

/***/ }),

/***/ 3:
/*!***************************************************!*\
  !*** multi ./resources/js/administrador/index.js ***!
  \***************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\laragon\www\new_system\resources\js\administrador\index.js */"./resources/js/administrador/index.js");


/***/ })

/******/ });