<?
require("lib/CreaConexion.php");
$id_contador=$_REQUEST['id_contador'];
?>
<h3 style="padding-left:2em"> Historial de consumo</h3>
<div data-dojo-type="dijit.layout.ContentPane" href="xhr/historial_consumo_riego.php?id_contador=<?=$id_contador?>" 

		data-dojo-props="style:'padding:.2em;width:515px;height:150px;border: 1px solid lightgray'">
</div>

<?
// Lectura actual
$conexion->connect('openrarp') or die('Error al conectar con la BD');
$query_lectura_actual="SELECT m3,averiado,notas FROM riego WHERE id_contador='$id_contador' AND fecha=(SELECT MAX(fecha) FROM riego)";
$id_result_lectura_actual=@$conexion->query($query_lectura_actual) or die('Error al consultar historia del consumo');
$fila_lectura=@$conexion->fetch_array($id_result_lectura_actual,0);
$activo=($fila_lectura['averiado']=='f')? 'checked' : '';
$inactivo=($fila_lectura['averiado']=='t')? 'checked' : '';
?>
<h3 style="padding-left:2em">Lectura actual</h3>
<div data-dojo-type="dijit.layout.ContentPane" data-dojo-props="style:'padding:.2em;width:515px;height:180px;border: 1px solid lightgray'">
<form dojoType="dijit.form.Form" method="post" id="formulario_riego">
	<input type="hidden" name="id_contador" value="<?=$id_contador?>"/>
	<center>
		<table border="0" width="90%">
			<tr>
				<td> <label for="m3">m3:</label></td>
				<td> <input type="text" required="false" name="m3" style="width: 50px" id="m3" value="<?=$fila_lectura['m3']?>" placeholder="m3" dojoType="dijit.form.ValidationTextBox" /></td>
			</tr>
			<tr>
				<td> <label for="notas">Notas:</label></td>
				<td><textarea id="notas" name="notas" data-dojo-type="dijit.form.Textarea" style="width:400px;"><?=$fila_lectura['notas']?></textarea>
			</tr>
			<tr>
				<td> <label for="notas">Contador:</label></td>
				<td>
					<input id="r_activo" type="radio" name="estado" value="A" <?=$activo?>
						    data-dojo-type="dijit.form.RadioButton">
					<label for="r_activo">Activo</label>
					<input id="r_inactivo" type="radio" name="estado" value="I" <?=$inactivo?>
						    data-dojo-type="dijit.form.RadioButton">
					<label for="r_inactivo">Inactivo</label>
				</td>
			</tr>
		</table>
		<input label='Actualizar' onClick="actualiza_datos_riego()" id="submitButton" dojoType="dijit.form.Button" />
	</center>
</form>
</div>
