<?
// Creamos la conexión con la BD
require("lib/CreaConexion.php");
// Consultamos la BD
$conexion->connect('openrarp') or die('Error al conectar con la BD');
//$query="SELECT distinct(corresponsal) FROM com_escritas WHERE nis='$nis'";
$query = "SELECT DISTINCT fecha FROM agua ORDER BY fecha DESC;";
$id_result=@$conexion->query($query);
$num_filas=@$conexion->num_rows($id_result);
$i=0;
$cabecera='{
	identifier:"fecha",
	label: "name",
	items: [';

$pie=']}';
//echo $cabecera;
while ($i < $num_filas){
	$fila=@$conexion->fetch_array($id_result,$i);
	$fecha=json_encode($fila['fecha']);
	$fechas .= "{name:$fecha, label:$fecha, fecha:$fecha},";

$i++;
}
$cuerpo=substr($fechas,0,-1);
$resultado=$cabecera.$cuerpo.$pie;
echo $resultado;
?>
