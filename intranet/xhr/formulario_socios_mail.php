<?
//require_once('../includes/autentica.php');
$seleccion=$_REQUEST['seleccion'];
$seleccion=explode(",",$seleccion);
foreach($seleccion as $valor){
	$lista_parcelas.="'$valor',";
}
$lista_parcelas=substr($lista_parcelas,0,-1);
require("lib/CreaConexion.php");
// Consultamos la BD
$conexion->connect('sierramar') or die('Error al conectar con la BD');
$query_email="SELECT email FROM socios WHERE email IS NOT NULL AND email<>'' AND id_parcela IN ($lista_parcelas)";
//echo $query_email;
$id_result=@$conexion->query($query_email) or die('Error al consultar email socios');
$num_filas=@$conexion->num_rows($id_result);
//echo "las filas: $num_filas";
$i=0;
while($i < $num_filas){
	$fila=@$conexion->fetch_array($id_result,$i);
	$lista_emails.=$fila['email'].',';
	$i++;
}
$lista_emails=substr($lista_emails,0,-1);
?>
<form dojoType="dijit.form.Form" method="post" id="form_correo">
<input type="hidden" id="editorContent" name="mensaje"/>
<label for="destinatarios">Destinatarios:</label>
<textarea id="destinatarios" data-dojo-type="dijit.form.Textarea" name="destinatarios"
		data-dojo-props="style:'width:670px;padding:.5em;border:1px solid lightgray'"><?=$lista_emails?></textarea>
<br>
<label for="asunto">Asunto:</label><br>
<input type="text" id="asunto" name="asunto" data-dojo-type="dijit.form.ValidationTextBox" style="width:100%" 
			data-dojo-props="placeholder:'',trim:'true'" required="true"/>
<br><br>
<label for="editor">Contenido:</label>
<div id="editor" data-dojo-type="dijit.Editor" onChange="dojo.byId('editorContent').value=this.getValue();"></div>
</form>
<div align="center"><input label="Enviar" onClick="envia_correo()" id="submitCorreo" dojoType="dijit.form.Button" /></div>
