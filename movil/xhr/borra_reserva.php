<?
$id_pista=$_REQUEST['id_pista'];
$id_usuario=$_REQUEST['id_usuario'];
$fecha=$_REQUEST['fecha'];
$turno=$_REQUEST['turno'];
$fecha_turno=$fecha.' '.$turno;
require("lib/CreaConexion.php");
// Consultamos la BD
$conexion->connect('openrarp') or die('Error al conectar con la BD');
$query_delete="DELETE FROM reserva WHERE turno='$fecha_turno' AND user_id=$id_usuario AND id_pista=$id_pista";
echo $query_delete;
@$conexion->query($query_delete) or die('Error al eliminar datos reserva');
$resultado="Pista: $id_pista, Usuario: $id_usuario, Turno: $fecha_turno<br/>(Reserva eliminada)";
echo $resultado;
?>
