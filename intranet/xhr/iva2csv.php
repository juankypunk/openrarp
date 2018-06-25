<?
//id_parcela;titular;fecha;l1;l2;m3;pm3;importe;domiciliado
//001;MARIA EMILIA MOYA TORRES;09-03-2012;4626;4662;36;0.48;17.28;17.28
//002;ALFONSO GUAITA MARTORELL;09-03-2012;5951;5951;0;0.48;0.00;0

// Creamos la conexión con la BD
require("lib/CreaConexion.php");
$fecha_lectura=$_REQUEST['fecha'];
if($fecha_lectura){
	$fecha_lectura="'$fecha_lectura'";
}else{
	$fecha_lectura="(SELECT MAX(fecha) FROM agua)";
}
// Consultamos la BD
$conexion->connect('sierramar') or die('Error al conectar con esta BD');

$query = "SELECT num_recibo,id_parcela,titular,fecha,subtotal,iva_repercutido,total
		FROM vista_agua_iva WHERE fecha=$fecha_lectura ORDER BY id_parcela";
$id_result=@$conexion->query($query) or die('Error al hacer la query');
$num_filas=@$conexion->num_rows($id_result);
//echo "$query";
$i=0;
$cabecera='num_recibo;id_parcela;titular;fecha;subtotal;iva_repercutido;total'."\r\n";
//echo $cabecera;
while ($i < $num_filas){
	$fila=@$conexion->fetch_array($id_result,$i);
	$num_recibo=$fila['num_recibo'];
	$id_parcela=$fila['id_parcela'];
	$titular=$fila['titular'];
	$fecha=$fila['fecha'];
	$subtotal=$fila['subtotal'];
	$iva_repercutido=$fila['iva_repercutido'];
	$total=$fila['total'];
	$agua_iva .= "$num_recibo;$id_parcela;$titular;$fecha;$subtotal;$iva_repercutido;$total"."\r\n";
$i++;
}
$contenido=$cabecera.$agua_iva;
//echo $resultado;

$anyo=date('y');
$mes=date('m');
$dia=date('d');
$tiempo_touch=strtotime($dia.'-'.$mes.'-'.$anyo);
touch('../temp/agua_iva.csv',$tiempo_touch);
if (is_writable('../temp/agua_iva.csv')) {
	if (!$gestor = fopen('../temp/agua_iva.csv', 'w')) {
		echo "No se puede abrir el archivo agua_iva.csv";
		exit;
	}else{
		fwrite($gestor, $contenido) or die('Error al escribir el fichero'); 
		echo "OK";
	}
}
fclose($gestor);
?>
