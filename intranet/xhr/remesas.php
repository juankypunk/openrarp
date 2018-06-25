<?
// Creamos la conexión con la BD
require("lib/CreaConexion.php");
//$fecha=$_REQUEST['fecha'];
//$filtro=$_REQUEST['filtro'];
$orden=$_REQUEST['sort'];

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

$query = "SELECT id_parcela,titular,bic,iban,to_char(importe,'999,999.00') as importe,concepto
		FROM remesas_especiales $orderby";
//echo "$query";
$id_result=@$conexion->query($query) or die('Error al hacer la query');
$num_filas=@$conexion->num_rows($id_result);
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
	$bic=json_encode($fila['bic']);
	$iban=json_encode($fila['iban']);
	$importe=json_encode($fila['importe']);
	$concepto=json_encode($fila['concepto']);
	$remesas .= "{id_parcela:$id_parcela,titular:$titular,bic:$bic,iban:$iban,importe:$importe,concepto:$concepto},";
$i++;
}
$cuerpo=substr($remesas,0,-1);
$resultado=$cabecera.$cuerpo.$pie;
echo $resultado;
?>
