<?
$id_pista=$_REQUEST['id_pista'];
$id_usuario=$_REQUEST['id_usuario'];
$fecha=$_REQUEST['fecha'];
$turno=$_REQUEST['turno'];
$fecha_turno=$fecha.' '.$turno;
require("lib/CreaConexion.php");
// Consultamos la BD
$conexion->connect('openrarp') or die('Error al conectar con la BD');
// comprobamos nº reservas pendientes
$query_select="SELECT COUNT(*) AS num_reservas FROM reserva 
		WHERE user_id=$id_usuario AND id_pista=$id_pista AND turno > CURRENT_TIMESTAMP";
$id_result=@$conexion->query($query_select) or die('Error al consultar núm. reservas activas');
$fila=@$conexion->fetch_array($id_result);
$num_reservas=$fila['num_reservas'];
if($num_reservas < 3){
	$query_insert="INSERT INTO RESERVA (turno,user_id,id_pista) VALUES ('$fecha_turno',$id_usuario,$id_pista)";
	//echo $query_insert;
	@$conexion->query($query_insert) or die('Error al escribir datos reserva');
}
$id_result=@$conexion->query($query_select) or die('Error al consultar núm. reservas activas');
$fila=@$conexion->fetch_array($id_result);
$num_reservas=$fila['num_reservas'];
echo $num_reservas;
?>
