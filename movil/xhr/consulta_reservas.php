<?
session_start();
$id_usuario=$_SESSION['user_id'];
$fecha=$_REQUEST['fecha'];
$id_pista=$_REQUEST['id_pista'];

require("lib/CreaConexion.php");
// Consultamos la BD

$conexion->connect('openrarp') or die('Error al conectar con la BD');
$query="SELECT consulta_reservas_fecha('$fecha',$id_pista) AS turno";
//$query="SELECT hora_turno,user_id,id_pista FROM vista_reservas WHERE turno::date='$fecha' ORDER BY turno";


//echo $query;
$id_result=@$conexion->query($query);
$num_filas=@$conexion->num_rows($id_result);
//echo "num_filas: $num_filas";
$i=0;
while ($i<$num_filas){
	$fila=@$conexion->fetch_array($id_result,$i);
	$cadena=substr($fila[0],1,-1);
	//echo "$cadena <br/>";
	list($turno,$reservadopor,$pista)=explode(',',$cadena);
	$hora_turno=substr($turno,11,6);
	//echo "$hora_turno <br/>";
	//$ocupado=json_encode($ocupado);
	//$reservadopor=$fila['user_id'];
	//$hora_turno=$fila['hora_turno'];
	if(!$reservadopor){
		$icon="images/i-icon-2.png";
		$rightText="RESERVAR";
	}elseif($reservadopor==$id_usuario){
		$icon="images/i-icon-9.png";
		$rightText="CANCELAR";
	}else{
		$icon="images/i-icon-9.png";
		$rightText="INFO";
	}
	$reservas[]=array('label' => $hora_turno, 'icon' => $icon, 'rightText' => $rightText); 
	$i++;
}
	$reservas_json=json_encode($reservas);
$cabecera='{
	"items": ';
$pie='}';
$resultado=$cabecera.$reservas_json.$pie;
echo $resultado;
?>
