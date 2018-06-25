<?
// Creamos la conexión con la BD
require("lib/CreaConexion.php");
// Consultamos la BD
$conexion->connect('sierramar') or die('Error al conectar con la BD');

$query = "SELECT MAX(fecha) FROM agua;";
$id_result=$conexion->query($query) or die('Error al hacer la query estadistica');
$fila=@$conexion->fetch_array($id_result,0);
$fecha_lectura=$fila['fecha'];
echo $fecha_lectura;
?>
