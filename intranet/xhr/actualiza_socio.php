<?php
$id_parcela=$_REQUEST['id_parcela'];
$titular=$_REQUEST['titular'];
$titular2=$_REQUEST['titular2'];
$domicilio=$_REQUEST['domicilio'];
$localidad=$_REQUEST['localidad'];
$cp=$_REQUEST['cp'];
$telef1=$_REQUEST['telef1'];
$telef2=$_REQUEST['telef2'];
$telef3=$_REQUEST['telef3'];
$email=$_REQUEST['email'];
$email2=$_REQUEST['email2'];
$titular_cc_agua=$_REQUEST['titular_cc_agua'];
$bic_agua=$_REQUEST['bic_agua'];
$iban_agua=$_REQUEST['iban_agua'];
$titular_cc_cuota=$_REQUEST['titular_cc_cuota'];
$bic_cuota=$_REQUEST['bic_cuota'];
$iban_cuota=$_REQUEST['iban_cuota'];
$notas=$_REQUEST['notas'];

// Creamos la conexión con la BD
require("lib/CreaConexion.php");
// Consultamos la BD
$conexion->connect('openrarp') or die('Error al conectar con la BD');
$query_actualizacion="UPDATE socios SET titular='$titular',titular2='$titular2',domicilio='$domicilio',localidad='$localidad',cp='$cp',
				telef1='$telef1',telef2='$telef2',telef3='$telef3',email='$email',email2='$email2',
				titular_cc_agua='$titular_cc_agua',bic_agua='$bic_agua',iban_agua='$iban_agua',
				titular_cc_cuota='$titular_cc_cuota',bic_cuota='$bic_cuota',iban_cuota='$iban_cuota',notas='$notas'
                        WHERE id_parcela='$id_parcela'";
$query_upd_domicilia_agua="UPDATE agua SET domicilia_bco=domicilia_agua_parcela('$id_parcela') WHERE id_parcela='$id_parcela' AND fecha=(SELECT MAX(fecha) FROM agua)";
$query_upd_domicilia_cuota="UPDATE cuotas SET domicilia_bco=domicilia_cuota_parcela('$id_parcela') WHERE id_parcela='$id_parcela' AND fecha=(SELECT MAX(fecha) FROM cuotas)";
@$conexion->query('BEGIN TRANSACTION;');
@$conexion->query($query_actualizacion) or die('Error al actualizar el registro: socios');
@$conexion->query($query_upd_domicilia_agua) or die('Error al actualizar el registro: domicilia_agua');
@$conexion->query($query_upd_domicilia_cuota) or die('Error al actualizar el registro: domicilia_cuota');
@$conexion->query('END TRANSACTION;');
echo ">>> Última actualización: $id_parcela, titular: $titular";
?>
