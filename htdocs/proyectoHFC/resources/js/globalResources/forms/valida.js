
  //validaciones
const valida = {}

valida.isValidEmail = function isValidEmail(mail) { 
    return /^\w+([\.\+\-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/.test(mail);
  }

valida.isValidText = function isValidText(text) { 
    return text.trim() != ''; 
  }

valida.isValidPassword = function isValidPassword(password) { 
    return  /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/.test(password);
  }

valida.isValidNumber = function isValidNumber(text) {
    return /^[0-9]+$/.test(text) //solo numeros
  }

valida.isValidPrecios = function isValidPrecios(text) {
    return /^[0-9]\d*(\.\d+)?$/.test(text)
  }

valida.isValidLetters= function isValidLetters(text) {
   // return /^[a-zA-Z]+(\s*[a-zA-Z]*)*[a-zA-Z]+$/.test(text)//solo letras y espacios intermedios
    return /^[a-zA-ZÀ-ÿ\:._-]+(\s*[a-zA-ZÀ-ÿ\:._-]*)*[a-zA-ZÀ-ÿ\:._-]+$/.test(text)//solo letras y espacios intermedios + cracteres
    //return /^[a-zA-Z]+$/.test(text)
  }

valida.isValidaTextArea= function isValidaTextArea(text) {
    return /^[a-zA-ZÀ-ÿ0-9\:._-]+(\s*[a-zA-ZÀ-ÿ0-9\:._-]*)*[a-zA-ZÀ-ÿ0-9\:._-]+$/.test(text)//solo letras y espacios intermedios
    //return /^[a-zA-Z]+$/.test(text)
  }
  
valida.isValidAlfaNumerico= function isValidAlfaNumerico(text) {
    return /^[a-zA-Z0-9]+$/.test(text)//solo letras y numeros
    //return /^[a-zA-Z]+$/.test(text)
  }

valida.isValidDecimalPositiveAndNegative= function isValidDecimalPositiveAndNegative(text) {
    return /^-?[0-9]\d*(\.\d+)?$/.test(text)//solo letras y numeros
    //return /^[a-zA-Z]+$/.test(text)
  }

valida.isValidateInputText = function isValidateInputText(campo)
{
  campo.addClass("valida-error-input")
}

valida.isValidateCampoHtml = function isValidateCampoHtml(campo)
{
  campo.addClass("valida-error-html")
}

export default valida
