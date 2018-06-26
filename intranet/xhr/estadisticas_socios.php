<?
// Creamos la conexión con la BD
require("lib/CreaConexion.php");
//$fecha=$_REQUEST['fecha'];

// Consultamos la BD
$conexion->connect('openrarp') or die('Error al conectar con la BD');
$query_parcelas = "SELECT count(*) as parcelas FROM cuotas WHERE fecha=(SELECT MAX(fecha) FROM cuotas)";
$query_contadores = "SELECT count(*) as contadores FROM agua WHERE fecha=(SELECT MAX(fecha) FROM agua)";
$id_result=$conexion->query($query_parcelas) or die('Error al hacer la query');
$fila=@$conexion->fetch_array($id_result,0);
$parcelas=$fila['parcelas'];
$id_result=$conexion->query($query_contadores) or die('Error al hacer la query');
$fila=@$conexion->fetch_array($id_result,0);
$contadores=$fila['contadores'];
$resultado="<h4 style='padding-left:1em'>Socios</h4>";
$resultado.="<ul class='estadistica'>
		<li>Socios:&nbsp;$parcelas</li>
		<li>Contadores:&nbsp;$contadores</li>
</ul>";
echo $resultado;
?>
