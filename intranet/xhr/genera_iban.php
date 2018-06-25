<?php
// Creamos la conexión con la BD
require("lib/CreaConexion.php");
include("includes/funciones_iban.php");
// Consultamos la BD
$conexion->connect('sierramar') or die('Error al conectar con la BD');
$query_socios="SELECT id_parcela,cc_agua,cc_cuota FROM socios";
$id_result=@$conexion->query($query_socios) or die('Error al consultar socios');
$num_filas=@$conexion->num_rows($id_result);
//echo "las filas son $num_filas";
$i=0;
while($i < $num_filas){
	$fila=@$conexion->fetch_array($id_result,$i);
	$id_parcela=$fila['id_parcela'];
	if($fila['cc_agua']<>null and $fila['cc_agua']<>''){
		$cc_agua='ES00'.$fila['cc_agua'];
		$iban_agua=generarDCInToIban($cc_agua);
		$query_update="UPDATE socios SET iban_agua='$iban_agua' WHERE id_parcela='$id_parcela'";
		@$conexion->query($query_update) or die('Error al actualizar iban');
	}
	if($fila['cc_cuota']<>null and $fila['cc_cuota']<>''){
		$cc_cuota='ES00'.$fila['cc_cuota'];
		$iban_cuota=generarDCInToIban($cc_cuota);
		$query_update="UPDATE socios SET iban_cuota='$iban_cuota' WHERE id_parcela='$id_parcela'";
		@$conexion->query($query_update) or die('Error al actualizar iban');
	}
	echo $id_parcela."|".$fila['cc_agua']."|".$iban_agua."|".$fila['cc_cuota']."|".$iban_cuota."</br>";
	$i++;
}	
?>
