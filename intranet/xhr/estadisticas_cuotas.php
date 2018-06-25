<?
$fecha=$_REQUEST['fecha'];
if($fecha){
	$fecha="'$fecha'";
}else{
	$fecha="(SELECT MAX(fecha) FROM cuotas)";
}

// Creamos la conexión con la BD
require("lib/CreaConexion.php");
// Consultamos la BD
$conexion->connect('sierramar') or die('Error al conectar con la BD');
$query_estadistica="SELECT fecha,cuota,domiciliado
			FROM estadistica_cuotas
			WHERE fecha=$fecha";
$id_result=$conexion->query($query_estadistica) or die('Error al hacer la query: estadistica');
$fila_estadistica=@$conexion->fetch_array($id_result,0);
$fecha_cuota=$fila_estadistica['fecha'];
$total_cuota=$fila_estadistica['cuota'];
$total_domiciliado=$fila_estadistica['domiciliado'];

$query_no_domiciliados="SELECT SUM(cuota) AS cuota_sindomiciliar FROM cuotas WHERE fecha=$fecha AND NOT domicilia_bco";
$id_result=$conexion->query($query_no_domiciliados) or die('Error al hacer la query: no-domicilado');
$fila_nodomiciliado=@$conexion->fetch_array($id_result,0);
$total_sindomiciliar=$fila_nodomiciliado['cuota_sindomiciliar'];
$total_recaudado=$total_domiciliado+$total_sindomiciliar;
$total_recaudado=number_format($total_recaudado, 2, ',', '.');
$pendiente_cobro=number_format($total_sindomiciliar, 2, ',', '.');
$total_cuota=number_format($total_cuota, 2, ',', '.');
$domiciliado=number_format($total_domiciliado, 2, ',', '.');
$resultado="<h4 >Recaudación de cuotas</h4>";
$resultado.="<ul class='estadistica'>
		<li>Fecha: $fecha_cuota </li>
		<li>Total cuotas: $total_cuota €</li>
		<li>Domicilado: $domiciliado €</li>
		<li>Pdte. cobro: $pendiente_cobro €</li>
		<li>Total recaud.: $total_recaudado €</li>
</ul>";
echo $resultado;
?>
