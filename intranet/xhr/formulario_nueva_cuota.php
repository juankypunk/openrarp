<?
//require_once('../includes/autentica.php');
require("lib/CreaConexion.php");
?>
<form dojoType="dijit.form.Form" method="post" id="form_cuota">
<table>
<tr>
	<td><label for="fecha_cuota">Fecha cuota:</label></td>
	<td><input type="text" id="fecha_cuota" name="fecha_cuota" dojoType="dijit.form.DateTextBox" required="true"/></td>
</tr>
<tr>
	<td><label for="dto">Descuento:</label></td>
	<td><input type="text" id="dto" name="dto" data-dojo-type="dijit.form.ValidationTextBox"
			data-dojo-props="placeholder:'ej. 5',trim:true,required:true" /></td>
</tr>
</table>
</form>
<div align="center"><input label="Aceptar" onClick="genera_cuotas()" id="submitCuotas" dojoType="dijit.form.Button" /></div>
<div id="resultado_cuotas"></div>
