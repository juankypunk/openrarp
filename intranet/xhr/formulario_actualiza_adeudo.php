<?
//require_once('../includes/autentica.php');
require("lib/CreaConexion.php");
$id_parcela=$_REQUEST['id_parcela'];
// Consultamos la BD
$conexion->connect('sierramar') or die('Error al conectar con la BD');
$query_adeudo="SELECT * FROM remesas_especiales WHERE id_parcela='$id_parcela'";
$id_result=@$conexion->query($query_adeudo) or die('Error al consultar adeudo');
$adeudo=@$conexion->fetch_array($id_result,0);
?>
<form dojoType="dijit.form.Form" method="post" id="form_actualiza_adeudo">
<table>
<tr>
	<td><label for="id_parcela">Id parcela:</label></td>
	<td><input type="text" id="id_parcela" name="id_parcela" data-dojo-type="dijit.form.ValidationTextBox" style="width:300px"
		value="<?=$adeudo['id_parcela']?>" data-dojo-props="placeholder:'',trim:'true',readOnly:'true',uppercase:'true'" />
	</td>
</tr>
<tr>
	<td><label for="titular_cc">Titular:</label></td>
	<td><input type="text" id="titular" name="titular" data-dojo-type="dijit.form.ValidationTextBox"  style="width:300px"
		value="<?=$adeudo['titular']?>" data-dojo-props="placeholder:'',trim:'true',maxLength:'40',uppercase:'true'" 
		required="true"/></td>
</tr>
<tr>
	<td> <label for='bic'>BIC/IBAN:</label></td>
	<td> 
		<input  required="true" name="bic" style="width:80px" id="bic" 
		data-dojo-type="dijit.form.ValidationTextBox" value="<?=$adeudo['bic']?>"
		data-dojo-props="placeholder:'XXXXXXXX',uppercase:'true',maxLength:'11',trim:'true'" />
		<input  required="true" name="iban" style="width:214px" id="iban" 
		value="<?=$adeudo['iban']?>"  data-dojo-type="dijit.form.ValidationTextBox"
		data-dojo-props="validator:valida_iban,invalidMessage:'El IBAN no es válido',
		placeholder:'ES00',uppercase:true,maxLength:'24',trim:'true'" />
	</td>
</tr>
<tr>
	<td><label for="importe">Importe:</label></td>
	<td><input type="text" id="importe" name="importe" data-dojo-type="dijit.form.ValidationTextBox" style="width:300px"
		value="<?=$adeudo['importe']?>" data-dojo-props="placeholder:'',trim:'true'" required="true"/></td>
</tr>
<tr>
	<td><label for="concepto">Concepto:</label></td>
	<td><textarea id="concepto" name="concepto" data-dojo-type="counterTextArea" style="width:295px"
			data-dojo-props="placeholder:'',trim:'true',maxLength:'140'" required="true"><?=$adeudo['concepto']?>
	</textarea>
	</td>
</tr>
</table>
</form>
<div align="center"><input label="Aceptar" onClick="actualiza_adeudo()" id="submitAdeudo" dojoType="dijit.form.Button" /></div>
