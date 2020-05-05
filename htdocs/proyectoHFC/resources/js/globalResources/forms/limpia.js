
  //limpiadores
const limpia = {}
    function limpiaTexInput()
    {
      $(".validateText").val('')
    }

    function limpiaSelectInput()
    {
      if ($(".validateSelect")[0]) { 
          //$(".validateSelect")[0].selectedIndex = 0
          $.each($('.validateSelect'), function( index, value ) {  
            value.selectedIndex = 0 
          }); 
        
      }
     
    }

    function limpiaCheckboxInput()
    {
      $('.validateCheckbox').prop('checked',false);
      $('.validateCheckbox').prop('disabled',false);
    }

    function limpiaFilesInput()
    {
      //file input bootstrap4
      $(".validateFile").fileinput('clear');
    }
    function limpiaFilesInputHTML()
    {
      //file input HTML
      $(".validateFile").val("");
    }

    limpia.defaultImageReset = function defaultImageReset(data,url)
    {
      data.attr('src',url);
    }

    limpia.limpiaHtml = function limpiaHtml(html)
    {
      html.html('')
    }

    limpia.limpiaFormUser = function limpiaFormUser()
    {
      limpiaTexInput()
      limpiaSelectInput()
      limpiaCheckboxInput()
    }

    limpia.limpiaFormRol = function limpiaFormRol()
    {
      limpiaTexInput()
      limpiaSelectInput()
      limpiaCheckboxInput()
    }
    limpia.limpiaFormEmpresa = function limpiaFormEmpresa()
    {
      limpiaTexInput() 
    }

    limpia.limpiaFormModulos = function limpiaFormModulos()
    {
      limpiaTexInput()
      limpiaSelectInput()
      limpiaFilesInput()
    }
    limpia.limpiaFormCursos = function limpiaFormCursos()
    {
      limpiaTexInput()
      limpiaFilesInput()
    }
    limpia.limpiaFormGrados = function limpiaFormGrados()
    {
      limpiaTexInput()
    }
    limpia.limpiaFormNiveles = function limpiaFormNiveles()
    {
      limpiaTexInput() 
    }
    limpia.limpiaFormYears = function limpiaFormYears()
    {
      limpiaTexInput() 
    }
    limpia.limpiaFormPeriodos = function limpiaFormPeriodos()
    {
      limpiaTexInput()
      limpiaSelectInput()
    }
    limpia.limpiaFormPensiones = function limpiaFormPensiones()
    {
      limpiaTexInput()
    }
    limpia.limpiaFormDetallePension = function limpiaFormDetallePension()
    {
      limpiaTexInput()
    }
    limpia.limpiaFormHorarios = function limpiaFormHorarios()
    {
      limpiaTexInput()
    }
    limpia.limpiaFormAsignacionProfesores = function limpiaFormAsignacionProfesores()
    {
      limpiaTexInput()
    }
    limpia.limpiaFormDetalleMatricula = function limpiaFormDetalleMatricula()
    {
      limpiaTexInput()
    }
    limpia.limpiaFormValidaServicio = function limpiaFormValidaServicio()
    {
      limpiaSelectInput()
     // $("#fileLoadValidaServicio").val("")
      limpiaFilesInputHTML()
    }
    limpia.limpiaFormGestionIndividual = function limpiaFormGestionIndividual()
    {
      limpiaSelectInput()
      limpiaTexInput()
    }
    limpia.limpiaFormGestionMasiva = function limpiaFormGestionMasiva()
    {
      limpiaSelectInput()
      limpiaTexInput()
    }
    limpia.limpiaFormTrabajoProgramado = function limpiaFormTrabajoProgramado()
    {
      limpiaSelectInput()
      limpiaTexInput()
    }
    limpia.limpiaFormAperturaTP = function limpiaFormAperturaTP()
    {
      limpiaSelectInput()
      limpiaTexInput()
    }
    limpia.limpiaFormCierreTP = function limpiaFormCierreTP()
    {
      limpiaSelectInput()
      limpiaTexInput()
    }
    limpia.limpiaFormStoreCuarentena = function limpiaFormStoreCuarentena()
    {
      limpiaSelectInput()
      limpiaTexInput()
    }
    

    export default limpia
