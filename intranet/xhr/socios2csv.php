<?
// Creamos la conexión con la BD
require("lib/CreaConexion.php");
// Consultamos la BD
$conexion->connect('openrarp') or die('Error al conectar con esta BD');

$query = "SELECT id_parcela,titular,titular2,titular_cc_agua,cc_agua,titular_cc_cuota,cc_cuota,email,email2,domicilio,localidad,telef1,telef2,telef3,cp,
		replace(notas,E'\n',' ') AS notas
		FROM socios ORDER BY id_parcela";
//echo "$query";
$id_result=@$conexion->query($query) or die('Error al hacer la query');
$num_filas=@$conexion->num_rows($id_result);
$i=0;
$cabecera='id_parcela,titular,titular2,titular_cc_agua,cc_agua,titular_cc_cuota,cc_cuota,email,email2,domicilio,localidad,telef1,telef2,telef3,cp,notas'."\r\n";
//echo $cabecera;
while ($i < $num_filas){
	$fila=@$conexion->fetch_array($id_result,$i);
	$id_parcela=str_pad($fila['id_parcela'],3,"0",STR_PAD_RIGHT);
	$titular=$fila['titular'];
	$titular2=$fila['titular2'];
	$titular_cc_agua=$fila['titular_cc_agua'];
	$cc_agua=$fila['cc_agua'];
	$titular_cc_cuota=$fila['titular_cc_cuota'];
	$cc_cuota=$fila['cc_cuota'];
	$email=$fila['email'];
	$email2=$fila['email2'];
	$domicilio=$fila['domicilio'];
	$localidad=$fila['localidad'];
	$telef1=$fila['telef1'];
	$telef2=$fila['telef2'];
	$telef3=$fila['telef3'];
	$cp=$fila['cp'];
	$notas=$fila['notas'];
	$socios .= "'$id_parcela','$titular','$titular2','$titular_cc_agua','$cc_agua','$titular_cc_cuota','$cc_cuota','$email','$email2','$domicilio','$localidad','$telef1','$telef2','$telef3','$cp','$notas'"."\r\n";
$i++;
}
$contenido=$cabecera.$socios;
//echo $resultado;
$anyo=date('y');
$mes=date('m');
$dia=date('d');
$tiempo_touch=strtotime($dia.'-'.$mes.'-'.$anyo);
touch('../temp/socios.csv',$tiempo_touch);
if (is_writable('../temp/socios.csv')) {
	if (!$gestor = fopen('../temp/socios.csv', 'w')) {
		echo "No se puede abrir el archivo socios.csv";
		exit;
	}else{
		fwrite($gestor, $contenido) or die('Error al escribir el fichero'); 
		echo "OK";
	}
}
fclose($gestor);
?>
