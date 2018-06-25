<?
session_start();
if(!$_SESSION['logged_in']){
	header('location: index.php');
}
$version="1.0";
$to=$_REQUEST['destinatarios'];
$subject=$_REQUEST['asunto'];
$message=$_REQUEST['mensaje'];

// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
$headers .= 'From: <admon@sierramar.es>' . "\r\n";
$headers .= 'Cc: juanky.moral@sierramar.es' . "\r\n";

mail($to,$subject,$message,$headers);
echo "Correo enviado con éxito";
?>
