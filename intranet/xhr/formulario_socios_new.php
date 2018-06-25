<?
//require_once("../includes/autentica.php");
require("lib/CreaConexion.php");
$id_parcela=$_REQUEST["id_parcela"];
// Consultamos la BD
$conexion->connect("sierramar") or die("Error al conectar con la BD");
// datos del socio
$query_socio="SELECT titular,domicilio,localidad,cp,telef1,telef2,dni,email,titular_cc_agua,cc_agua,titular_cc_cuota,cc_cuota,notas FROM socios WHERE id_parcela='$id_parcela'";
$id_result_socio=@$conexion->query($query_socio) or die("Error al consultar datos del socio");
$fila_socio=@$conexion->fetch_array($id_result_socio,0);
$titular=$fila_socio['titular'];
$domicilio=$fila_socio['domicilio'];
$localidad=$fila_socio['localidad'];
$cp=$fila_socio['cp'];
$telef1=$fila_socio['telef1'];
$telef2=$fila_socio['telef2'];
$dni=$fila_socio['dni'];
$email=$fila_socio['email'];
$titular_cc_agua=$fila_socio['titular_cc_agua'];
$cc_agua=$fila_socio['cc_agua'];
$titular_cc_cuota=$fila_socio['titular_cc_cuota'];
$cc_cuota=$fila_socio['cc_cuota'];
$notas=$fila_socio['notas'];
?>
<h3 style="text-align: center"> Parcela: <?=$id_parcela?> </h3>
<form dojoType="dijit.form.Form" method="post" id="formulario">
<input type="hidden" name="id_parcela" value="$id_parcela"/>
	<table>
		<tr>
			<td> <label for="titular">Titular:</label> </td>
			<td> <input type="text" name="titular" style="width: 300px" id="titular" value="<?=$titular?>" data-dojo-type="dijit.form.ValidationTextBox"
				data-dojo-props="missingMessage:'El nombre del titular es obligatorio',required:'true',placeholder:'Nombre del socio',trim:'true',uppercase:'true'" /> </td>
		</tr>
		<tr>
			<td><label for="domicilio">Domicilio:</label></td>
			<td> <input type="text" name="domicilio" style="width: 300px" id="domicilio" value="<?=$domicilio?>" data-dojo-type="dijit.form.ValidationTextBox"
				data-dojo-props="required:'false',placeholder:'Domicilio del socio',trim:'true',uppercase:'true'" /></td>
		</tr>
		<tr>
			<td> <label for="localidad">Localidad:</label></td>
			<td> <input type="text" name="localidad" style="width: 300px" id="localidad" value="<?=$localidad?>" data-dojo-type="dijit.form.ValidationTextBox"
				data-dojo-props="required:'false',placeholder:'Localidad del socio',trim:'true',uppercase:'true'" /></td>
		</tr>
		<tr>
			<td> <label for="cp">C.P.:</label></td>
			<td> <input type="text" name="cp" style="width: 300px" id="cp" value="<?=$cp?>"  data-dojo-type="dijit.form.ValidationTextBox"
				data-dojo-props="required:'false',placeholder:'CP del socio',trim:'true',uppercase:'true'" /></td>
		</tr>
		<tr>
			<td> <label for="email">email:</label></td>
			<td> <input type="text" name="email" style="width: 300px" id="email" value="<?=$email?>"  data-dojo-type="dijit.form.ValidationTextBox"
				data-dojo-props="required:'false',placeholder:'Email del socio',trim:'true'" /></td>
		</tr>
		<tr>
			<td> <label for="telef1">Telf.1:</label></td>
			<td> <input type="text" name="telef1" style="width: 300px" id="telef1" value="<?=$telef1?>"  data-dojo-type="dijit.form.ValidationTextBox"
				data-dojo-props="required:'false',placeholder:'Tel. 1 del socio',trim:'true'" /></td>
		</tr>
		<tr>
			<td><label for="telef2">Telf.2:</label></td>
			<td> <input type="text" name="telef2" style="width: 300px" id="telef2" value="<?=$telef2?>"  data-dojo-type="dijit.form.ValidationTextBox"
				data-dojo-props="required:'false',placeholder:'Tel. 2 del socio',trim:'true'" /></td>
		</tr>
		<tr>
			<td> <label for="dni">DNI:</label></td>
			<td> <input type="text" name="dni" style="width: 300px" id="dni" value="<?=$dni?>"  data-dojo-type="dijit.form.ValidationTextBox"
				data-dojo-props="required:'false',placeholder:'DNI del socio',trim:'true'" /></td>
		</tr>
		<tr>
			<td> <label for="titular_cc_agua">Titular CC agua:</label></td>
			<td> <input type="text" name="titular_cc_agua" style="width: 300px" id="titular_cc_agua" value="<?=$titular_cc_agua?>"  data-dojo-type="dijit.form.ValidationTextBox"
				data-dojo-props="required:'false',placeholder:'Titular de la CC del agua',trim:'true',uppercase:'true'" /></td>
		</tr>
		<tr>
			<td> <label for='cc_agua'>CC agua:</label></td>
			<td> <input type="text" name="cc_agua" style="width: 300px" id="cc_agua" value="<?=$cc_agua?>"  data-dojo-type="dijit.form.ValidationTextBox"
				data-dojo-props="required:'false',placeholder:'CC del agua',maxLength:'20',trim:'true'" /></td>
		</tr>
		<tr>
			<td> <label for="titular_cc_cuota">Titular CC cuota:</label></td>
			<td> <input type="text" name="titular_cc_cuota" style="width: 300px" id="titular_cc_cuota" value="<?=$titular_cc_cuota?>"  data-dojo-type="dijit.form.ValidationTextBox"
				data-dojo-props="required:'false',placeholder:'Titular de la CC de la cuota',trim:'true',uppercase:'true'" /></td>
		</tr>
		<tr>
			<td> <label for="cc_cuota">CC cuota:</label></td>
			<td> <input type="text" name="cc_cuota" style="width: 300px" id="cc_cuota" value="<?=$cc_cuota?>"  data-dojo-type="dijit.form.ValidationTextBox"
				data-dojo-props="required:'false',placeholder:'CC de la cuota',maxLength:'20',trim:'true'" /></td>
		</tr>
		<tr>
			<td> <label for="notas">Notas:</label></td>
			<td> <textarea type="text"  name="notas" style="width: 300px" id="notas"  data-dojo-type="dijit.form.Textarea"><?=$notas?></textarea></td>
		</tr>
	</table>
</form>
<div align='center'><input label='Actualizar' onClick='actualiza_datos()' id='submitButton' dojoType='dijit.form.Button' /></div>
