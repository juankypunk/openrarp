<?
//require_once('../includes/autentica.php');
require("lib/CreaConexion.php");
?>
<form dojoType="dijit.form.Form" method="post" id="form_lectura">
<table>
<tr>
<td><label for="fecha_lectura">Fecha lectura:</label></td>
<td><input type="text" id="fecha_lectura" name="fecha_lectura" dojoType="dijit.form.DateTextBox" required="true"/></td>
</tr>
</table>
</form>
<div align="center"><input label="Aceptar" onClick="genera_lectura()" id="submitLectura" dojoType="dijit.form.Button" /></div>
<div id="resultado_lectura"></div>
