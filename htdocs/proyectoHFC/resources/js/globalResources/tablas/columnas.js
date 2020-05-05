const columnas = {}

columnas.armandoEstadoGestionHtml = function armandoEstadoGestionHtml(parametros)
{
    
    let dataReturn = `<div class="text-left p-1"><strong style="color:${parametros.estadoColor};">${parametros.estadoText == null? "" : parametros.estadoText} </strong><br>
                        <span style="color:${parametros.observacionesColor};">${parametros.observacionesText == null? "" : parametros.observacionesText} `
                        if (parametros.usuarioText != null) {
                            
                            dataReturn += `<span style="color:${parametros.usuarioColor};">(${parametros.usuarioText})</span>`
                        }
            dataReturn += `</span> 
                            <br/>
                            <span style="color:${parametros.fechahoraColor};">${parametros.fechahoraText == null? "" : parametros.fechahoraText}</span></div>`

    return dataReturn
    
}

export default columnas