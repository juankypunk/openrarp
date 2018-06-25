<?
// Creamos la conexión con la BD
require("lib/CreaConexion.php");
$fecha=$_REQUEST['fecha'];
$filtro=$_REQUEST['filtro'];
$orden=$_REQUEST['sort'];
if($fecha){
	$fecha="'$fecha'";
}else{
	$fecha="(SELECT MAX(fecha) FROM riego)";
}
if($filtro){
	switch($filtro){
		case "A":
			$condicion=" AND averiado";
			break;
		default:
			$condicion="";
	}
}

if($orden=='-id_contador'){
	$orderby='ORDER BY r.id_contador DESC';
}elseif($orden=='id_contador'){
	$orderby='ORDER BY r.id_contador ASC';
}elseif($orden=='-lugar'){
	$orderby='ORDER BY lugar DESC';
}elseif($orden=='lugar'){
	$orderby='ORDER BY lugar ASC';
}elseif($orden=='-notas'){
	$orderby='ORDER BY notas DESC';
}elseif($orden=='notas'){
	$orderby='ORDER BY notas ASC';
}elseif($orden=='-m3'){
	$orderby='ORDER BY m3 DESC';
}elseif($orden=='m3'){
	$orderby='ORDER BY m3 ASC';
}

if(!$orderby){
	$orderby='ORDER BY r.id_contador ASC';
}

// Consultamos la BD
$conexion->connect('sierramar') or die('Error al conectar con esta BD');

$query = "SELECT r.id_contador,r.averiado,c.lugar,r.fecha,r.l1,r.l2,r.m3,notas,estado
		FROM riego r INNER JOIN contadores_riego c ON r.id_contador=c.id_contador WHERE r.fecha=$fecha $condicion $orderby";

$id_result=@$conexion->query($query) or die('Error al hacer la query');
$num_filas=@$conexion->num_rows($id_result);
//echo "$query";
$i=0;
$cabecera='{
	identifier:"id_contador",
	label: "lugar",
	items: [';
$pie=']}';
//echo $cabecera;
while ($i < $num_filas){
	$fila=@$conexion->fetch_array($id_result,$i);
	$id_contador=json_encode($fila['id_contador']);
	$lugar=json_encode($fila['lugar']);
	$fecha=json_encode($fila['fecha']);
	$l1=json_encode($fila['l1']);
	$l2=json_encode($fila['l2']);
	$m3=json_encode($fila['m3']);
	$averiado=json_encode($fila['averiado']);
	$notas=json_encode($fila['notas']);
	$estado=json_encode($fila['estado']);
	$riego .= "{id_contador:$id_contador,lugar:$lugar,averiado:$averiado,fecha:$fecha,l1:$l1,l2:$l2,m3:$m3,notas:$notas,estado:$estado},";
	$i++;
}
$cuerpo=substr($riego,0,-1);
$resultado=$cabecera.$cuerpo.$pie;
echo $resultado;
?>
