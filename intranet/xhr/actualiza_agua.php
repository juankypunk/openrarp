<?php
$id_parcela=$_REQUEST['id_parcela'];
$l2=$_REQUEST['l2'];
$m3=$_REQUEST['m3'];
if(!$l2){
	$l2=0;
}
if(!$m3){
	$m3=0;
}
$estado=$_REQUEST['estado'];
$notas=$_REQUEST['notas'];
$notas=trim($notas);
$averiado=($estado=='A') ? 'f' : 't';
if($id_parcela){
	// Creamos la conexión con la BD
	require("lib/CreaConexion.php");
	// Consultamos la BD
	$conexion->connect('openrarp') or die('Error al conectar con la BD');
	$query_actualizacion="UPDATE agua SET l2=$l2,m3=$m3,notas='$notas',averiado='$averiado',estado='R' WHERE id_parcela='$id_parcela' AND fecha=(SELECT MAX(fecha) FROM agua)";
	@$conexion->query($query_actualizacion) or die('Error al actualizar el registro');
	echo ">>> Última actualización: parcela $id_parcela, l2:$l2, m3:$m3, notas:$notas";
//	echo $query_actualizacion;
}else{
echo ">>> No se ha modificado ningún dato";
}
?>
