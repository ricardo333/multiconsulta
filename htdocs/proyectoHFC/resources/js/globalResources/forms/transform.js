const transform = {}

transform.fechaParaBD = function fechaParaBD(elemento)
{
  return moment(elemento.val(),'DD-MM-YYYY').format("YYYY-MM-DD")
}

transform.dataTimeParaBD = function dataTimeParaBD(elemento)
{
  return moment(elemento.val(),'DD/MM/YYYY H:mm').format("YYYY-MM-DD H:mm")
}

transform.dataTimeSlashParaView = function dataTimeSlashParaView(elemento)
{
  return moment(elemento,'YYYY-MM-DD H:mm').format("DD/MM/YYYY H:mm")
}

export default transform
