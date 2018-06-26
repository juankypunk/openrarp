<?
// Creamos la conexión con la BD
require("lib/CreaConexion.php");
$fecha=$_REQUEST['fecha'];
$filtro=$_REQUEST['filtro'];
$orden=$_REQUEST['sort'];
if($fecha){
	$fecha="'$fecha'";
}else{
	$fecha="(SELECT MAX(fecha) FROM agua)";
}
if($filtro){
	switch($filtro){
		case "D":
			$condicion=" AND domicilia_bco";
			break;
		case "S":
			$condicion=" AND NOT domicilia_bco";
			break;
		case "A":
			$condicion=" AND averiado";
			break;
		case "F":
			$condicion=" AND m3 > (avg+stddev)";
			break;
		case "B":
			$condicion=" AND m3 < (avg-stddev)";
			break;
		default:
			$condicion="";
	}
}

if($orden=='-id_parcela'){
	$orderby='ORDER BY va.id_parcela DESC';
}elseif($orden=='id_parcela'){
	$orderby='ORDER BY va.id_parcela ASC';
}elseif($orden=='-titular'){
	$orderby='ORDER BY va.titular DESC';
}elseif($orden=='titular'){
	$orderby='ORDER BY va.titular ASC';
}elseif($orden=='-notas'){
	$orderby='ORDER BY va.notas DESC';
}elseif($orden=='notas'){
	$orderby='ORDER BY va.notas ASC';
}elseif($orden=='-m3'){
	$orderby='ORDER BY va.m3 DESC';
}elseif($orden=='m3'){
	$orderby='ORDER BY va.m3 ASC';
}

if(!$orderby){
	$orderby='ORDER BY va.id_parcela ASC';
}

// Consultamos la BD
$conexion->connect('openrarp') or die('Error al conectar con esta BD');

$query = "SELECT va.id_parcela,va.averiado,va.titular,va.fecha,va.l1,va.l2,va.m3,va.pm3,va.importe,va.domiciliado,va.notas,va.estado,ea.avg,ea.stddev
		FROM vista_agua va INNER JOIN estadistica_agua_parcela ea ON va.id_parcela=ea.id_parcela AND ea.trimestre=extract(quarter from va.fecha)
 		WHERE va.fecha=$fecha $condicion $orderby";

$id_result=@$conexion->query($query) or die('Error al hacer la query');
$num_filas=@$conexion->num_rows($id_result);
//echo "$query";
$i=0;
$cabecera='{
	identifier:"id_parcela",
	label: "titular",
	items: [';
$pie=']}';
//echo $cabecera;
while ($i < $num_filas){
	$fila=@$conexion->fetch_array($id_result,$i);
	$id_parcela=json_encode($fila['id_parcela']);
	$titular=json_encode($fila['titular']);
	$fecha=json_encode($fila['fecha']);
	$l1=json_encode($fila['l1']);
	$l2=json_encode($fila['l2']);
	$m3=json_encode($fila['m3']);
	$pm3=json_encode($fila['pm3']);
	$importe=json_encode($fila['importe']);
	$averiado=json_encode($fila['averiado']);
	$avg=json_encode($fila['avg']);
	$stddev=json_encode($fila['stddev']);
	$notas=json_encode($fila['notas']);
	$estado=json_encode($fila['estado']);
	$agua .= "{id_parcela:$id_parcela,titular:$titular,averiado:$averiado,avg:$avg,stddev:$stddev,fecha:$fecha,l1:$l1,l2:$l2,m3:$m3,pm3:$pm3,importe:$importe,notas:$notas,estado:$estado},";
	$i++;
}
$cuerpo=substr($agua,0,-1);
$resultado=$cabecera.$cuerpo.$pie;
echo $resultado;
?>
