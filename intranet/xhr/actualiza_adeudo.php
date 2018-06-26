<?php
$id_parcela=$_REQUEST['id_parcela'];
$titular=$_REQUEST['titular'];
$bic=$_REQUEST['bic'];
$iban=$_REQUEST['iban'];
$importe=$_REQUEST['importe'];
$concepto=$_REQUEST['concepto'];
if($id_parcela){
	// Creamos la conexión con la BD
	require("lib/CreaConexion.php");
	// Consultamos la BD
	$conexion->connect('openrarp') or die('Error al conectar con la BD');
	$query_upd="UPDATE remesas_especiales SET titular='$titular',bic='$bic',iban='$iban',importe=$importe,
			concepto='$concepto' WHERE id_parcela='$id_parcela'";
	echo ">>> Última actualización: $titular ($id_parcela)";
	@$conexion->query($query_upd) or die('Error al actualizar el registro');
}else{
echo ">>> No se ha modificado ningún dato";
}
?>
