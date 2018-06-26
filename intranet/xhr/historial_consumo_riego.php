<?
require("lib/CreaConexion.php");
$id_contador=$_REQUEST['id_contador'];
// Consultamos la BD
$conexion->connect('openrarp') or die('Error al conectar con la BD');
// historia del consumo del socio
$query_consumo="SELECT fecha,l2,m3,notas FROM riego WHERE id_contador='$id_contador' AND fecha < (SELECT MAX(fecha) FROM riego) ORDER BY fecha";
$id_result_consumo=@$conexion->query($query_consumo) or die('Error al consultar historia del consumo');
?>
<center>
	<table width="90%">
			<tr><th>Fecha</th><th>Lectura</th><th>M3</th><th>Contador</th><th>Notas</th></tr>
<?
$i=0;
$num_filas=@$conexion->num_rows($id_result_consumo);
while($i<$num_filas){
	$fila=@$conexion->fetch_array($id_result_consumo,$i);
	$averiado=($fila['averiado']=='t') ? 'AVERIADO' : 'OK';
?>
	<tr>
		<td style="color:gray"><?=$fila['fecha']?></td>
		<td style='color:gray'><?=$fila['l2']?></td>
		<td style='color:gray'><?=$fila['m3']?></td>
		<td style='color:gray'><?=$averiado?></td>
		<td style='color:gray'><?=$fila['notas']?></td></tr>
<?
	$i++;
};
?>
	</table>
</center>
