<?php
session_start();
/*
	print "DATA RECEIVED:"; 
	print "<ul>"; 
	foreach($_REQUEST as $key => $var){
		print "<li>".$key." = ".$var."</li>";
	}
	print "</ul>"; 
*/

$usuario=$_REQUEST['username'];
$clave=$_REQUEST['passwd'];
if($usuario and $clave){
	include("lib/CreaConexion.php");
	// Consultamos la BD
	$conexion->connect('sierramar') or die('Error al conectar con la BD');
	$query_login="SELECT login FROM socios WHERE login='$usuario' and passwd='$clave'";
	//echo $query;
	$id_result=@$conexion->query($query_login);
	$num_filas=@$conexion->num_rows($id_result);
	if($num_filas==1){
		$query_upd_usuario="UPDATE socios SET fult_entrada=CURRENT_TIMESTAMP WHERE login='$usuario'";
		@$conexion->query($query_upd_usuario);
		$_SESSION['identificado']=true;
		$_SESSION['usuario']='juanky';
		$resultado='inicio.php';
	}else{
		$resultado='fail';
	}		
}
echo $resultado;
?>
