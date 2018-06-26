<?
$cadena=$_REQUEST['name'];
require("lib/CreaConexion.php");
// Consultamos la BD
$conexion->connect('openrarp') or die('Error al conectar con la BD');
$query="SELECT id_parcela, titular
		FROM vista_socios_titulares
		WHERE titular ILIKE '%$cadena%'";
$id_result=@$conexion->query($query);
$num_filas=@$conexion->num_rows($id_result);
$i=0;
$cabecera='{
	identifier:"id_parcela",
	label: "name",
	items: [';

$pie=']}';
//echo $cabecera;
while ($i < $num_filas){
	$fila=@$conexion->fetch_array($id_result,$i);
	$id_parcela=json_encode($fila['id_parcela']);
	$titular=json_encode($fila['titular']);
	$socios.= "{name:$titular,label:$titular,id_parcela:$id_parcela},";
$i++;
}
$cuerpo=substr($socios,0,-1);
$resultado=$cabecera.$cuerpo.$pie;
echo $resultado;
?>
