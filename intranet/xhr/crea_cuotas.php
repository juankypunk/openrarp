<?php
// Creamos la conexión con la BD
require("lib/CreaConexion.php");
$fecha_cuota=$_REQUEST['fecha_cuota'];
$dto=$_REQUEST['dto'];
// Consultamos la BD
$conexion->connect('sierramar') or die('Error al conectar con la BD');
$query_upd="UPDATE cuotas SET estado='C' where estado='R'";
$query_insert="INSERT INTO cuotas (id_parcela,fecha,cuota,domicilia_bco,estado) SELECT id_parcela,'$fecha_cuota',cuota,domicilia_bco,'R' FROM cuotas WHERE fecha=(SELECT MAX(fecha) FROM cuotas)";
$query_upd_dto="UPDATE cuotas SET dto=$dto WHERE fecha='$fecha_cuota' AND estado='R'"; 
@$conexion->query("BEGIN;") or die('Error en la BD: Begin Transaction');
@$conexion->query($query_upd) or die('Error al hacer la query upd');
@$conexion->query($query_insert) or die('Error al hacer la query insert');
@$conexion->query($query_upd_dto) or die('Error al hacer la query upd(dto)');
@$conexion->query("END;") or die('Error al hacer UPDATE en la BD: End Transaction');
?>
