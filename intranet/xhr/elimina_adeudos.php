<?php
// Creamos la conexión con la BD
$seleccion=$_REQUEST['seleccion'];
if($seleccion){
$seleccion=explode(",",$seleccion);
foreach($seleccion as $valor){
	$lista_parcelas.="'$valor',";
}
$lista_parcelas=substr($lista_parcelas,0,-1);
require("lib/CreaConexion.php");
// Consultamos la BD
$conexion->connect('openrarp') or die('Error al conectar con la BD');
$query_delete="DELETE FROM remesas_especiales WHERE id_parcela IN ($lista_parcelas)";
//echo "$query_delete";
@$conexion->query($query_delete) or die('Error al eliminar registros');
echo ">>> Adeudos eliminados con éxito: ($lista_parcelas)";
}else{
	echo ">>>No se ha seleccionado ningún adedudo (nada que hacer)";

}
?>
