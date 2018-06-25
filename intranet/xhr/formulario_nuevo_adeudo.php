<?
//require_once('../includes/autentica.php');
?>
<form dojoType="dijit.form.Form" method="post" id="form_nuevo_adeudo">
<table>
<tr>
	<td><label for="id_parcela">Id parcela:</label></td>
	<td><input type="text" id="id_parcela" name="id_parcela" data-dojo-type="dijit.form.ValidationTextBox" style="width:300px"
			data-dojo-props="placeholder:'',trim:'true',uppercase:'true'" required="true"/></td>
</tr>
<tr>
	<td><label for="titular">Titular:</label></td>
	<td><input type="text" id="titular" name="titular" data-dojo-type="dijit.form.ValidationTextBox" style="width:300px" 
			data-dojo-props="placeholder:'',trim:'true',maxLength:'40',uppercase:'true'" required="true"/></td>
</tr>
<tr>
	<td> <label for='bic'>BIC/IBAN:</label></td>
	<td> 
		<input  required="true" name="bic" style="width:80px" id="bic" 
		data-dojo-type="dijit.form.ValidationTextBox"
		data-dojo-props="placeholder:'XXXXXXXX',uppercase:'true',maxLength:'11',trim:'true'" />
		<input  required="true" name="iban" style="width:214px" id="iban" 
		value=""  data-dojo-type="dijit.form.ValidationTextBox"
		data-dojo-props="validator:valida_iban,invalidMessage:'El IBAN no es válido',
		placeholder:'ES00',uppercase:true,maxLength:'24',trim:'true'" />
	</td>
</tr>
<tr>
	<td><label for="importe">Importe:</label></td>
	<td><input type="text" id="importe" name="importe" data-dojo-type="dijit.form.ValidationTextBox" style="width:300px"
			data-dojo-props="placeholder:'',trim:'true'" required="true"/></td>
</tr>
<tr>
	<td><label for="concepto">Concepto:</label></td>
	<td><textarea id="concepto" name="concepto" data-dojo-type="counterTextArea" style="width:295px"
			data-dojo-props="placeholder:'',trim:'true',maxLength:'140'" required="true"></textarea>
	</td>
</tr>
</table>
</form>
<div align="center"><input label="Aceptar" onClick="escribe_adeudo()" id="submitAdeudo" dojoType="dijit.form.Button" /></div>
