<?php
$id_contador=$_REQUEST['id_contador'];
$m3=$_REQUEST['m3'];
$estado=$_REQUEST['estado'];
$notas=$_REQUEST['notas'];
$averiado=($estado=='A') ? 'f' : 't';
if($id_contador){
	// Creamos la conexión con la BD
	require("lib/CreaConexion.php");
	// Consultamos la BD
	$conexion->connect('sierramar') or die('Error al conectar con la BD');
	$query_actualizacion="UPDATE riego SET m3=$m3,notas='$notas',averiado='$averiado',estado='R' WHERE id_contador=$id_contador AND fecha=(SELECT MAX(fecha) FROM riego)";
	@$conexion->query($query_actualizacion) or die('Error al actualizar el registro');
	echo ">>> Última actualización: contador $id_contador, m3:$m3, notas:$notas";
	echo $query_actualizacion;
}else{
echo ">>> No se ha modificado ningún dato";
}
?>
