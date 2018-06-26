<?
// Creamos la conexión con la BD
require("lib/CreaConexion.php");
$fecha=$_REQUEST['fecha'];
if($fecha){
	$fecha="'$fecha'";
}else{
	$fecha="(SELECT MAX(fecha) FROM riego)";
}

// Consultamos la BD
$conexion->connect('openrarp') or die('Error al conectar con la BD');

$query = "SELECT fecha,m3,max,avg
		FROM estadistica_riego WHERE m3 > 0 AND fecha=$fecha";
$id_result=$conexion->query($query) or die('Error al hacer la query estadistica');
$fila=@$conexion->fetch_array($id_result,0);
$m3=$fila['m3'];
$max=$fila['max'];
$avg=$fila['avg'];
$fecha_lectura=$fila['fecha'];
$query_averiados="SELECT count(*) as averiados FROM riego WHERE fecha=$fecha AND averiado=true";
$id_result=$conexion->query($query_averiados) or die('Error al hacer la query averiados');
$fila=@$conexion->fetch_array($id_result,0);
$averiados=$fila['averiados'];
$m3=number_format($m3, 0, ',', '.');
$resultado="<h4 style='padding-left:1em'>Lectura del agua</h4>";
$resultado.="<ul class='estadistica'>
		<li>Fecha:&nbsp;$fecha_lectura</li>
		<li>Consumo: $m3 m<sup>3</sup></li>
		<li>Máx.: $max m<sup>3</sup></li>
		<li>Media: $avg m<sup>3</sup></li>
		<li>Cont. inactivos: $averiados </li>
</ul>";
echo $resultado;
?>
