<?
// Creamos la conexión con la BD
require("lib/CreaConexion.php");

$orden=$_REQUEST['sort'];
if($orden=='-id_parcela'){
	$orderby='ORDER BY id_parcela DESC';
}elseif($orden=='id_parcela'){
	$orderby='ORDER BY id_parcela ASC';
}elseif($orden=='-titular'){
	$orderby='ORDER BY titular DESC';
}elseif($orden=='titular'){
	$orderby='ORDER BY titular ASC';
}elseif($orden=='-localidad'){
	$orderby='ORDER BY localidad DESC';
}elseif($orden=='localidad'){
	$orderby='ORDER BY localidad ASC';
}

if(!$orderby){
	$orderby='ORDER BY id_parcela ASC';
}

// Consultamos la BD
$conexion->connect('openrarp') or die('Error al conectar con la BD');

$query = "SELECT id_parcela,titular,titular2,domicilio,localidad,cp,telef1,telef2,email
		FROM socios $orderby";

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
	$titular2=json_encode($fila['titular2']);
	$domicilio=json_encode($fila['domicilio']);
	$localidad=json_encode($fila['localidad']);
	$cp=json_encode($fila['cp']);
	$telef1=json_encode($fila['telef1']);
	$telef2=json_encode($fila['telef2']);
	$email=json_encode($fila['email']);
	$socios .= "{id_parcela:$id_parcela,titular:$titular,titular2:$titular2,domicilio:$domicilio,localidad:$localidad,cp:$cp,telef1:$telef1,telef2:$telef2,email:$email},";

$i++;
}
$cuerpo=substr($socios,0,-1);
$resultado=$cabecera.$cuerpo.$pie;
echo $resultado;
?>
