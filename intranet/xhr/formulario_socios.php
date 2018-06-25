<?
//require_once("../includes/autentica.php");
require("lib/CreaConexion.php");
$id_parcela=$_REQUEST["id_parcela"];
// Consultamos la BD
$conexion->connect("sierramar") or die("Error al conectar con la BD");
// datos del socio
$query_socio="SELECT titular,titular2,domicilio,localidad,cp,telef1,telef2,telef3,email,email2,titular_cc_agua,bic_agua,iban_agua,titular_cc_cuota,bic_cuota,iban_cuota,notas FROM socios WHERE id_parcela='$id_parcela'";
$id_result_socio=@$conexion->query($query_socio) or die("Error al consultar datos del socio");
$fila_socio=@$conexion->fetch_array($id_result_socio,0);
$titular=$fila_socio['titular'];
$titular2=$fila_socio['titular2'];
$domicilio=$fila_socio['domicilio'];
$localidad=$fila_socio['localidad'];
$cp=$fila_socio['cp'];
$telef1=$fila_socio['telef1'];
$telef2=$fila_socio['telef2'];
$telef3=$fila_socio['telef3'];
$email=$fila_socio['email'];
$email2=$fila_socio['email2'];
$titular_cc_agua=$fila_socio['titular_cc_agua'];
$bic_agua=$fila_socio['bic_agua'];
$iban_agua=$fila_socio['iban_agua'];
$titular_cc_cuota=$fila_socio['titular_cc_cuota'];
$bic_cuota=$fila_socio['bic_cuota'];
$iban_cuota=$fila_socio['iban_cuota'];
$notas=$fila_socio['notas'];
?>
<h3 style="text-align: center"> Parcela: <?=$id_parcela?> </h3>
<form dojoType="dijit.form.Form" method="post" id="formulario">
<input type="hidden" name="id_parcela" value="<?=$id_parcela?>"/>
<table>
	<tr>
		<td>
		<table>
		<tr>
			<td width="25%"> <label for="titular">Titular:</label> </td>
			<td> <input name="titular" style="width: 300px" id="titular" value="<?=$titular?>" data-dojo-type="dijit.form.ValidationTextBox"
				data-dojo-props="missingMessage:'El nombre del titular es obligatorio',required:'true',placeholder:'Nombre del socio',trim:'true',uppercase:'true'" /> </td>
		</tr>
		<tr>
			<td width="25%"> <label for="titular2">Titular2:</label> </td>
			<td> <input name="titular2" style="width: 300px" id="titular2" value="<?=$titular2?>" data-dojo-type="dijit.form.ValidationTextBox"
				data-dojo-props="placeholder:'El nombre del 2º titular (opcional)',trim:'true',uppercase:'true'" /> </td>
		</tr>
		<tr>
			<td><label for="domicilio">Domicilio:</label></td>
			<td> <input required="false" name="domicilio" style="width: 300px" id="domicilio" value="<?=$domicilio?>" data-dojo-type="dijit.form.ValidationTextBox"
				data-dojo-props="placeholder:'Domicilio del socio',trim:'true',uppercase:'true'" /></td>
		</tr>
		<tr>
			<td> <label for="localidad">Localidad:</label></td>
			<td> <input required="false" name="localidad" style="width: 300px" id="localidad" value="<?=$localidad?>" data-dojo-type="dijit.form.ValidationTextBox"
				data-dojo-props="placeholder:'Localidad del socio',trim:'true',uppercase:'true'" /></td>
		</tr>
		<tr>
			<td> <label for="cp">C.P.:</label></td>
			<td> <input required="false" name="cp" style="width: 300px" id="cp" value="<?=$cp?>"  data-dojo-type="dijit.form.ValidationTextBox"
				data-dojo-props="placeholder:'CP del socio',trim:'true',uppercase:'true'" /></td>
		</tr>
		<tr>
			<td> <label for="email">email:</label></td>
			<td> <input required="false" name="email" style="width: 300px" id="email" value="<?=$email?>"  data-dojo-type="dijit.form.ValidationTextBox"
				data-dojo-props="placeholder:'Email del socio',trim:'true'" /></td>
		</tr>
		<tr>
			<td> <label for="email2">email2:</label></td>
			<td> <input required="false" name="email2" style="width: 300px" id="email2" value="<?=$email2?>"  data-dojo-type="dijit.form.ValidationTextBox"
				data-dojo-props="placeholder:'Email2 del socio',trim:'true'" /></td>
		</tr>
		<tr>
			<td> <label for="telef1">Telf.1:</label></td>
			<td> <input required="false" name="telef1" style="width: 300px" id="telef1" value="<?=$telef1?>"  data-dojo-type="dijit.form.ValidationTextBox"
				data-dojo-props="placeholder:'Tel. 1 del socio',trim:'true'" /></td>
		</tr>
		<tr>
			<td><label for="telef2">Telf.2:</label></td>
			<td> <input required="false" name="telef2" style="width: 300px" id="telef2" value="<?=$telef2?>"  data-dojo-type="dijit.form.ValidationTextBox"
				data-dojo-props="placeholder:'Tel. 2 del socio',trim:'true'" /></td>
		</tr>
		<tr>
			<td> <label for="dni">Telf.3:</label></td>
			<td> <input  required="false" name="telef3" style="width: 300px" id="telef3" value="<?=$telef3?>"  data-dojo-type="dijit.form.ValidationTextBox"
				data-dojo-props="placeholder:'Tel. 3 del socio',trim:'true'" /></td>
		</tr>
		<tr>
			<td> <label for="titular_cc_agua">Titular agua:</label></td>
			<td> <input  required="false" name="titular_cc_agua" style="width: 300px" id="titular_cc_agua" value="<?=$titular_cc_agua?>"  data-dojo-type="dijit.form.ValidationTextBox"
				data-dojo-props="placeholder:'Titular de la CC del agua',trim:'true',uppercase:'true'" /></td>
		</tr>
		<tr>
			<td> <label for='bic_agua'>BIC/IBAN agua:</label></td>
			<td> 
				<input  required="false" name="bic_agua" style="width:110px" id="bic_agua" 
				value="<?=$bic_agua?>"  data-dojo-type="dijit.form.ValidationTextBox"
				data-dojo-props="placeholder:'XXXXXXXX',uppercase:true,maxLength:'11',trim:'true'" />
				<input  required="false" name="iban_agua" style="width:185px" id="iban_agua" 
				value="<?=$iban_agua?>"  data-dojo-type="dijit.form.ValidationTextBox"
				data-dojo-props="validator:valida_iban,invalidMessage:'El IBAN no es válido',
				placeholder:'ES00',uppercase:true,maxLength:'24',trim:'true'" />
			</td>
		</tr>
		<tr>
			<td> <label for="titular_cc_cuota">Titular cuota:</label></td>
			<td> <input  required="false" name="titular_cc_cuota" style="width: 300px" id="titular_cc_cuota" value="<?=$titular_cc_cuota?>"  data-dojo-type="dijit.form.ValidationTextBox"
				data-dojo-props="placeholder:'Titular de la CC de la cuota',trim:'true',uppercase:'true'" /></td>
		</tr>
		<tr>
			<td> <label for="bic_cuota">BIC/IBAN cuota:</label></td>
			<td>
				<input  required="false" name="bic_cuota" style="width:110px" id="bic_cuota" 
				value="<?=$bic_cuota?>"  data-dojo-type="dijit.form.ValidationTextBox"
				data-dojo-props="placeholder:'XXXXXXXX',uppercase:true,maxLength:'11',trim:'true'" />
				<input  required="false" name="iban_cuota" style="width:185px" id="iban_cuota" 
				value="<?=$iban_cuota?>"  data-dojo-type="dijit.form.ValidationTextBox"
				data-dojo-props="validator:valida_iban,invalidMessage:'El IBAN no es válido',
				placeholder:'ES00',uppercase:true,maxLength:'24',trim:'true'" />
			</td>
		</tr>
		</table>
		</td>
		<td valign="top">
			<table>
			<tr>
			<td><label for="notas">Notas:</label>
			<textarea rows="5" cols="50" name="notas"  id="notas"  data-dojo-type="dijit.form.Textarea"><?=$notas?></textarea></td>
			</tr>
			</table>
		</td>
		</tr>
	</table>
</form>
<div align='center'><input label='Actualizar' onClick='actualiza_datos()' id='submitButton' dojoType='dijit.form.Button' /></div>
