<?
$id_usuario=$_REQUEST['id_usuario'];
$id_pista=$_REQUEST['id_pista'];
require("lib/CreaConexion.php");
// Consultamos la BD
$conexion->connect('openrarp') or die('Error al conectar con la BD');
$query="SELECT id_pista AS pista,to_char(turno,'DD-MM-YYYY (HH24:MI)') AS turno 
		FROM reserva WHERE id_pista=$id_pista AND user_id=$id_usuario AND turno > CURRENT_TIMESTAMP ORDER BY turno";
//echo $query;
$id_result=@$conexion->query($query) or die('Error al leer quien reserva');
$num_filas=@$conexion->num_rows($id_result);
//echo "$num_filas filas";
$resultado="<ul>";
$i=0;
if($num_filas>0){
	while ($i<$num_filas){
		$fila=@$conexion->fetch_array($id_result,$i);
		$id_pista=$fila['pista'];
		$turno=$fila['turno'];
		$resultado.="<li>P$id_pista: $turno</li>";
		$i++;
	}
}else{
	$resultado.="<li>Todavía ninguna :(</li>";
}
$resultado.="</ul>";
echo $resultado;
?>
