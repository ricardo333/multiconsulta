  //validaciones
  const validaKey = {}

    validaKey.alfanumerico1 = function alfanumerico1(elemento) { 

        $("body").on("keyup",`${elemento}`, function(event){
            console.log("El evento :",event)
            var regex = new RegExp("^[a-zA-Z0-9() ]+$");
            var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
            if (!regex.test(key)) {
                event.preventDefault();
                console.log("Ingresa valición")
                return false;
            }
             
        })
        

    }

export default validaKey