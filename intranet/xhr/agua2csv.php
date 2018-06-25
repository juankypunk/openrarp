<?
//id_parcela;titular;fecha;l1;l2;m3;pm3;importe;domiciliado
//001;MARIA EMILIA MOYA TORRES;09-03-2012;4626;4662;36;0.48;17.28;17.28
//002;ALFONSO GUAITA MARTORELL;09-03-2012;5951;5951;0;0.48;0.00;0

// Creamos la conexión con la BD
require("lib/CreaConexion.php");
$fecha_lectura=$_REQUEST['fecha'];
$clave=$_REQUEST['filtro'];
if($clave){
	switch($clave){
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
if($fecha_lectura){
	$fecha_lectura="'$fecha_lectura'";
}else{
	$fecha_lectura="(SELECT MAX(fecha) FROM agua)";
}
// Consultamos la BD
$conexion->connect('sierramar') or die('Error al conectar con esta BD');

$query = "SELECT va.id_parcela,va.averiado,va.titular,va.fecha,va.l1,va.l2,va.m3,va.pm3,va.importe,va.domiciliado,va.notas,va.estado,ea.avg,ea.stddev
		FROM vista_agua va INNER JOIN estadistica_agua_parcela ea ON va.id_parcela=ea.id_parcela AND ea.trimestre=extract(quarter from va.fecha)
 		WHERE va.fecha=$fecha_lectura $condicion ORDER BY va.id_parcela";
$id_result=@$conexion->query($query) or die('Error al hacer la query');
$num_filas=@$conexion->num_rows($id_result);
//echo "$query";
$i=0;
$cabecera='id_parcela;titular;fecha;l1;l2;m3;pm3;importe;domiciliado;averiado;avg;sttdev'."\r\n";
//echo $cabecera;
while ($i < $num_filas){
	$fila=@$conexion->fetch_array($id_result,$i);
	$id_parcela=$fila['id_parcela'];
	$titular=$fila['titular'];
	$l1=$fila['l1'];
	$l2=$fila['l2'];
	$m3=$fila['m3'];
	$pm3=$fila['pm3'];
	$fecha=$fila['fecha'];
	$importe=$fila['importe'];
	$domiciliado=$fila['domiciliado'];
	$averiado=$fila['averiado'];
	$avg=$fila['avg'];
	$stddev=$fila['stddev'];
	$agua .= "$id_parcela;$titular;$fecha;$l1;$l2;$m3;$pm3;$importe;$domiciliado;$averiado;$avg;$stddev"."\r\n";
$i++;
}
$contenido=$cabecera.$agua;
//echo $resultado;

$anyo=date('y');
$mes=date('m');
$dia=date('d');
$tiempo_touch=strtotime($dia.'-'.$mes.'-'.$anyo);
touch('../temp/agua.csv',$tiempo_touch);
if (is_writable('../temp/agua.csv')) {
	if (!$gestor = fopen('../temp/agua.csv', 'w')) {
		echo "No se puede abrir el archivo agua.csv";
		exit;
	}else{
		fwrite($gestor, $contenido) or die('Error al escribir el fichero'); 
		echo "OK";
	}
}
fclose($gestor);
?>
