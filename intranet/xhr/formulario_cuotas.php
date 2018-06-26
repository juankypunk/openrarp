<?
require("lib/CreaConexion.php");
$id_parcela=$_REQUEST['id_parcela'];
// Consultamos la BD
$conexion->connect('openrarp') or die('Error al conectar con la BD');
// historia del consumo del socio
$query_cuotas="SELECT fecha,titular,cuota FROM vista_cuotas WHERE id_parcela='$id_parcela' ORDER BY fecha";
$id_result=@$conexion->query($query_cuotas) or die('Error al consultar historia de cuotas');
$num_filas=@$conexion->num_rows($id_result);
$i=0;
?>
<h3>Historial de cuotas parcela: <?=$id_parcela?></h3>
	<center>
		<table width="80%">
			<tr><th>Fecha</th><th>Cuota</th></tr>
<?
while($i<$num_filas){
	$fila=@$conexion->fetch_array($id_result,$i);
?>
			<tr><td style="color:gray"><?=$fila['fecha']?></td><td style="color:gray"><?=$fila['cuota']?></td></tr>
<?
	$i++;
};
$query_cuota_actual="SELECT fecha,cuota,dto FROM cuotas WHERE id_parcela='$id_parcela' AND fecha=(SELECT MAX(fecha) FROM cuotas)";
$id_result_cuota_actual=@$conexion->query($query_cuota_actual) or die('Error al consultar cuota actual');
$fila=@$conexion->fetch_array($id_result_cuota_actual,0);
?>
		</table>
	</center>
<h3>Modificar cuota de <?=$fila['fecha']?> - <?=$fila['titular']?></h3>
<form dojoType="dijit.form.Form" method="post" id="formulario_cuotas">
		<input type="hidden" name="id_parcela" value="<?=$id_parcela?>"/>
		<center>
		<table width="80%">
		<tr>
			<td><label for="cuota">Cuota:</label></td>
			<td><input data-dojo-type="dijit.form.ValidationTextBox" name="cuota" style="width: 50px" 
				id="cuota" value="<?=$fila['cuota']?>" data-dojo-props="placeholder:'cuota',trim:true,required:false" /></td>
			<td><label for="dto">Bonificación (% dto.):</label></td>
			<td><input data-dojo-type="dijit.form.ValidationTextBox" name="dto" style="width: 50px" 
				id="dto" value="<?=$fila['dto']?>" data-dojo-props="placeholder:'ej. 5.00',trim:true,required:false" /></td>
		</tr>
		</table>
			<input label='Modificar cuota' onClick='actualiza_datos()' id='submitButton' dojoType='dijit.form.Button' /></td>
		</center>
		</form>
