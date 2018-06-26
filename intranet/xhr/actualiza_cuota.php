<?php
$id_parcela=$_REQUEST['id_parcela'];
$cuota=$_REQUEST['cuota'];
$dto=$_REQUEST['dto'];
if($id_parcela and $cuota and $dto){
	// Creamos la conexión con la BD
	require("lib/CreaConexion.php");
	// Consultamos la BD
	$conexion->connect('openrarp') or die('Error al conectar con la BD');
	$query_actualizacion="UPDATE cuotas SET cuota=$cuota,dto=$dto WHERE id_parcela='$id_parcela' AND fecha=(SELECT MAX(fecha) FROM cuotas)";
	@$conexion->query($query_actualizacion) or die('Error al actualizar el registro');
	echo ">>> Última actualización: parcela $id_parcela, cuota:$cuota, dto:$dto";
}else{
echo ">>> No se ha modificado ningún dato";
}
?>
