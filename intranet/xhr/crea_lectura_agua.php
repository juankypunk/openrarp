<?php
// Creamos la conexión con la BD
require("lib/CreaConexion.php");
$fecha_lectura=$_REQUEST['fecha_lectura'];
// Consultamos la BD
$conexion->connect('openrarp') or die('Error al conectar con la BD');
$query_insert_agua="INSERT INTO agua (id_parcela,fecha,l1,pm3,averiado,notas,domicilia_bco) 
		SELECT id_parcela,'$fecha_lectura',l2,pm3,averiado,notas,domicilia_bco FROM agua WHERE fecha=(SELECT MAX(fecha) FROM agua)";
$query_upd_agua="UPDATE agua SET estado='C' WHERE estado='R'";
$query_insert_riego="INSERT INTO riego (id_contador,fecha,l1,averiado,notas)
		SELECT id_contador,'$fecha_lectura',l2,averiado,notas FROM riego WHERE fecha=(SELECT MAX(fecha) FROM riego)";
$query_upd_riego="UPDATE riego SET estado='C' WHERE estado='R'";
@$conexion->query("BEGIN;") or die('Error en la BD: Begin Transaction');
@$conexion->query($query_insert_agua) or die('Error al hacer la query insert_agua');
@$conexion->query($query_upd_agua) or die('Error al hacer la query upd_agua');
@$conexion->query($query_insert_riego) or die('Error al hacer la query insert_riego');
@$conexion->query($query_upd_riego) or die('Error al hacer la query upd_riego');
@$conexion->query("END;") or die('Error al hacer UPDATE en la BD: End Transaction');
?>
