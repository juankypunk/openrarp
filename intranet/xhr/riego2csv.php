<?
// Creamos la conexión con la BD
require("lib/CreaConexion.php");
$fecha_lectura=$_REQUEST['fecha'];
$clave=$_REQUEST['filtro'];
if($clave){
	switch($clave){
		case "A":
			$condicion=" AND r.averiado";
			break;
		default:
			$condicion="";
	}
}
if($fecha_lectura){
	$fecha_lectura="'$fecha_lectura'";
}else{
	$fecha_lectura="(SELECT MAX(fecha) FROM riego)";
}
// Consultamos la BD
$conexion->connect('openrarp') or die('Error al conectar con esta BD');

$query = "SELECT r.id_contador,r.averiado,c.lugar,r.fecha,r.l1,r.l2,r.m3
		FROM riego r INNER JOIN contadores_riego c ON r.id_contador=c.id_contador 
		WHERE r.fecha=$fecha_lectura $condicion ORDER BY r.id_contador";
//echo "$query";
$id_result=@$conexion->query($query) or die('Error al hacer la query');
$num_filas=@$conexion->num_rows($id_result);
$i=0;
$cabecera='id_contador,lugar,fecha,l1,l2,m3'."\r\n";
//echo $cabecera;
while ($i < $num_filas){
	$fila=@$conexion->fetch_array($id_result,$i);
	$id_contador=$fila['id_contador'];
	$lugar=$fila['lugar'];
	$l1=$fila['l1'];
	$l2=$fila['l2'];
	$m3=$fila['m3'];
	$fecha=$fila['fecha'];
	$riego .= "$id_contador,$lugar,$fecha,$l1,$l2,$m3"."\r\n";
$i++;
}
$contenido=$cabecera.$riego;
//echo $resultado;
$anyo=date('y');
$mes=date('m');
$dia=date('d');
$tiempo_touch=strtotime($dia.'-'.$mes.'-'.$anyo);
touch('../temp/riego.csv',$tiempo_touch);
if (is_writable('../temp/agua.csv')) {
	if (!$gestor = fopen('../temp/riego.csv', 'w')) {
		echo "No se puede abrir el archivo riego.csv";
		exit;
	}else{
		fwrite($gestor, $contenido) or die('Error al escribir el fichero'); 
		echo "OK";
	}
}
fclose($gestor);
?>
