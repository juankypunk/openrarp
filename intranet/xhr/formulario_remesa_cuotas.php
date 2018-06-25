<?
//require_once('../includes/autentica.php');
require("lib/CreaConexion.php");
?>
<form dojoType="dijit.form.Form" method="post" id="form_remesa">
<table>
<tr>
	<td><label for="fecha_cargo">Fecha cargo:</label></td>
	<td><input type="text" id="fecha_cargo" name="fecha_cargo" dojoType="dijit.form.DateTextBox" required="true"/></td>
</tr>
</table>
</form>
<div align="center"><input label="Aceptar" onClick="genera_remesa()" id="submitRemesa" dojoType="dijit.form.Button" /></div>
<div id="resultado_remesa"></div>
