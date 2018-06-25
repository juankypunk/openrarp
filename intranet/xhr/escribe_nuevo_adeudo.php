<?php
$id_parcela=$_REQUEST['id_parcela'];
$titular=$_REQUEST['titular'];
$bic=$_REQUEST['bic'];
$iban=$_REQUEST['iban'];
$importe=$_REQUEST['importe'];
$concepto=$_REQUEST['concepto'];
// Creamos la conexión con la BD
require("lib/CreaConexion.php");

if($id_parcela && $titular && $bic && $iban && $importe && $concepto){
	// Consultamos la BD
	$conexion->connect('sierramar') or die('Error al conectar con la BD');
	$query_insert="INSERT INTO remesas_especiales 
		(id_parcela,titular,bic,iban,importe,concepto) 
		VALUES ('$id_parcela','$titular','$bic','$iban',$importe,'$concepto')";
	echo ">>> Última inserción: $query_insert";
	@$conexion->query($query_insert) or die('Error al insertar el registro');
}else{
echo ">>> No se ha modificado ningún dato";
}
?>
