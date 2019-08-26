<?
$id_pista=$_REQUEST['id_pista'];
$fecha=$_REQUEST['fecha'];
$turno=$_REQUEST['turno'];
$fecha_turno=$fecha.' '.$turno;
require("lib/CreaConexion.php");
// Consultamos la BD
$conexion->connect('openrarp') or die('Error al conectar con la BD');
$query="SELECT name,turno,fecha_reserva FROM vista_reservas_pistas WHERE turno='$fecha_turno' AND id_pista=$id_pista";
//echo $query;
$id_result=@$conexion->query($query) or die('Error al leer quien reserva');
$fila=@$conexion->fetch_array($id_result);
$nombre_usuario=$fila['name'];
$resultado="Pista reservada por $nombre_usuario";
echo $resultado;
?>
