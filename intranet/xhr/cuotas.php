<?
// Creamos la conexión con la BD
require("lib/CreaConexion.php");
$fecha=$_REQUEST['fecha'];
$filtro=$_REQUEST['filtro'];
$orden=$_REQUEST['sort'];
if($fecha){
	$fecha="'$fecha'";
}else{
	$fecha="(SELECT MAX(fecha) FROM cuotas)";
}
if($filtro){
	switch($filtro){
		case "D":
			$condicion=" AND domicilia_bco";
			break;
		case "S":
			$condicion=" AND NOT domicilia_bco";
			break;
		default:
			$condicion="";
	}
}

if($orden=='-id_parcela'){
	$orderby='ORDER BY id_parcela DESC';
}elseif($orden=='id_parcela'){
	$orderby='ORDER BY id_parcela ASC';
}elseif($orden=='-titular'){
	$orderby='ORDER BY titular DESC';
}elseif($orden=='titular'){
	$orderby='ORDER BY titular ASC';
}

if(!$orderby){
	$orderby='ORDER BY id_parcela ASC';
}

// Consultamos la BD
$conexion->connect('sierramar') or die('Error al conectar con esta BD');

$query = "SELECT id_parcela,titular,fecha,cuota,dto,to_char(domiciliado,'999,999.00') as domiciliado,estado
		FROM vista_cuotas WHERE fecha=$fecha $condicion $orderby";
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
	$cuota=json_encode($fila['cuota']);
	$dto=json_encode($fila['dto']);
	$estado=json_encode($fila['estado']);
	$domiciliado=json_encode($fila['domiciliado']);
	$cuotas .= "{id_parcela:$id_parcela,titular:$titular,fecha:$fecha,cuota:$cuota,dto:$dto,domiciliado:$domiciliado,estado:$estado},";
$i++;
}
$cuerpo=substr($cuotas,0,-1);
$resultado=$cabecera.$cuerpo.$pie;
echo $resultado;
?>
