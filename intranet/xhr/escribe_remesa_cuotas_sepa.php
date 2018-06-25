<?php
// Creamos la conexión con la BD
require("lib/CreaConexion.php");
$conexion->connect('sierramar') or die('Error al conectar con la BD');
$query_properties="SELECT * FROM properties";
$id_result=@$conexion->query($query_properties) or die('Error al consultar propiedades');
$properties=@$conexion->fetch_array($id_result,0);
$ID_PRESENTADOR=$properties['id_presentador'];
$ID_ACREEDOR=$properties['id_acreedor'];
$NOMBRE_PRESENTADOR=$properties['nombre_presentador'];
$NOMBRE_ACREEDOR=$properties['nombre_acreedor'];
$IBAN_ACREEDOR=$properties['iban_acreedor'];
$ref_identificativa=$properties['ref_identificativa'];
$entidad_receptora=$properties['entidad_receptora'];
$oficina_receptora=$properties['oficina_receptora'];
$fecha_cobro=$_REQUEST['fecha_cargo'];
$fecha_cobro=str_replace('-','',$fecha_cobro);

function getStamp(){
   list($Mili, $bot) = explode(" ", microtime());
   $DM=substr(strval($Mili),2,5);
   return strval(date("Y").date("m").date("d").date("H").date("i").date("s") . $DM);
}
function getFechaTouch(){
   return strval(date("Y").date("m").date("d"));
}
//2014051510135255560
//echo getStamp() . '<br>';
$cont_lineas=0;

touch('../remesas/remesa_cuotas_sepa.dat');

//cabecera del presentador
$C1='01';
$C2='19143';
$C3='001';
$C4=str_pad($ID_PRESENTADOR,35," ",STR_PAD_RIGHT);
$C5=str_pad($NOMBRE_PRESENTADOR,70," ",STR_PAD_RIGHT);
$C6=getFechaTouch();
$C7='PRE'.getStamp().$ref_identificativa;
$C8=$entidad_receptora;
$C9=$oficina_receptora;
$C10=str_pad(" ",434," ",STR_PAD_RIGHT);
$R1=$C1.$C2.$C3.$C4.$C5.$C6.$C7.$C8.$C9.$C10."\r\n";

//cabecera acreedor por fecha de cobro
$C1='02';
$C2='19143';
$C3='002';
$C4=str_pad($ID_ACREEDOR,35," ",STR_PAD_RIGHT);
$C5=$fecha_cobro;
$C6=str_pad($NOMBRE_ACREEDOR,70," ",STR_PAD_RIGHT);
$C7=str_pad(" ",50," ",STR_PAD_RIGHT);
$C8=str_pad(" ",50," ",STR_PAD_RIGHT);
$C9=str_pad(" ",40," ",STR_PAD_RIGHT);
$C10=str_pad(" ",2," ",STR_PAD_RIGHT);
$C11=str_pad($IBAN_ACREEDOR,34," ",STR_PAD_RIGHT);
$C12=str_pad(" ",301," ",STR_PAD_RIGHT);
$R2=$C1.$C2.$C3.$C4.$C5.$C6.$C7.$C8.$C9.$C10.$C11.$C12."\r\n";

//registros 1º individual obligatorio
// Consultamos la BD
$conexion->connect('sierramar') or die('Error al conectar con la BD');
$query="SELECT id_parcela,titular_cc,iban,bic,to_char(fecha,'DD-MM-YY') as fecha,to_char(cuota,'999,999.00') as cuota_f,cuota,to_char(domiciliado,'999,999.00') as domiciliado_f,domiciliado,dto
		from vista_cuotas where fecha=(SELECT MAX(fecha) FROM cuotas) AND cuota > 0 AND domiciliado > 0";
//echo $query;
$id_result=$conexion->query($query) or die('Error al hacer la query');
$num_filas=@$conexion->num_rows($id_result);
$cont_lineas+=2;
$i=0;
$total=0;
while ($i < $num_filas) {
	$fila=@$conexion->fetch_array($id_result,$i);
	$id_parcela=str_pad($fila['id_parcela'],12,"0",STR_PAD_LEFT);
	$AT10='CUOTA'.$fecha_cobro.$id_parcela; //referencia del adeudo
	$C1='03';
	$C2='19143';
	$C3='003';
	$C4=str_pad($AT10,35," ",STR_PAD_RIGHT);
	$C5=str_pad($id_parcela,35," ",STR_PAD_RIGHT);
	$AT21='RCUR'; // tipos de adeudo: FNAL FRST OOFF RCUR
	$C6=$AT21;
	$AT59='TREA'; // categoría de propósito ('TREA: Pago de tesorería)
	$C7=$AT59;
	$total+=$fila['domiciliado'];
	$bonificacion=$fila['cuota']-$fila['domiciliado'];
	$bonificacion=sprintf("%01.2f",$bonificacion);
	$domiciliado_f=str_replace('.','',$fila['domiciliado_f']);
	$domiciliado_f=trim($domiciliado_f);
	$C8=str_pad($domiciliado_f,11,"0",STR_PAD_LEFT);
	$AT25='20140201'; // fecha de firma del mandato (la entrada en vigor de SEPA)
	$C9=$AT25;
	$C10=str_pad($fila['bic'],11," ",STR_PAD_RIGHT);
	$titular=iconv("UTF-8", "ISO-8859-1", $fila['titular_cc']);
	$C11=str_pad($titular,70," ",STR_PAD_RIGHT);
	$C12C18=str_pad(" ",214," ",STR_PAD_RIGHT); //relleno, campos optativos
	$C19='A';
	$C20=str_pad($fila['iban'],34," ",STR_PAD_RIGHT);
	$AT58='MSVC'; // propósito del adeudo: MULTIPLE SERVICE TYPES
	$C21=$AT58;
	$dto=$fila['dto'];
	$concepto="CUOTAS COMUNIDAD RESID. SIERRAMAR: DTO. POR DOMICILIACION $dto% BONIFICACION APLICADA: -$bonificacion EUROS";
	$C22=str_pad($concepto,140," ",STR_PAD_RIGHT);
	$C23=str_pad(" ",19," ",STR_PAD_RIGHT);
	$R3.=$C1.$C2.$C3.$C4.$C5.$C6.$C7.$C8.$C9.$C10.$C11.$C12C18.$C19.$C20.$C21.$C22.$C23."\r\n";
	$i++;
	$cont_lineas++;
}
// los pies
// registro totales por acreedor y fecha de cobro
$C1='04';
$C2=str_pad($ID_ACREEDOR,35," ",STR_PAD_RIGHT);
$C3=$fecha_cobro;
$total=sprintf("%01.2f",$total);
$total_f=str_replace('.','',$total);
$C4=str_pad($total_f,17,'0',STR_PAD_LEFT);
$C5=str_pad($i,8,"0",STR_PAD_LEFT); //número de adeudos
$C6=str_pad($cont_lineas,10,"0",STR_PAD_LEFT);
$C7=str_pad(" ",520," ",STR_PAD_RIGHT);
$R4=$C1.$C2.$C3.$C4.$C5.$C6.$C7."\r\n";
$cont_lineas++;
//registro de totales de acreedor
$C1='05';
$C2=str_pad($ID_ACREEDOR,35," ",STR_PAD_RIGHT);
$C3=str_pad($total_f,17,'0',STR_PAD_LEFT);
$C4=str_pad($i,8,"0",STR_PAD_LEFT); //número de adeudos
$C5=str_pad($cont_lineas,10,"0",STR_PAD_LEFT);
$C6=str_pad(" ",528," ",STR_PAD_RIGHT);
$R5=$C1.$C2.$C3.$C4.$C5.$C6."\r\n";
$cont_lineas++;
//registro de totales general
$cont_lineas++;
$C1='99';
$C2=str_pad($total_f,17,'0',STR_PAD_LEFT);
$C3=str_pad($i,8,"0",STR_PAD_LEFT); //número de adeudos
$C4=str_pad($cont_lineas,10,"0",STR_PAD_LEFT);
$C5=str_pad(" ",563," ",STR_PAD_RIGHT);
$R6=$C1.$C2.$C3.$C4.$C5."\r\n";
echo "<img src='images/ok.jpeg' alt='ok' width='50px' />";
echo "Total lineas: $cont_lineas<br>";
echo "Total recibos: $num_filas<br>";
echo "Importe total: $total<br>";
echo 'Fichero: <a href="remesas/remesa_cuotas_sepa.dat" title="remesa_cuotas_sepa.dat">Descargar fichero</a><br>';
$contenido.= $R1.$R2.$R3.$R4.$R5.$R6;

if (is_writable('../remesas/remesa_cuotas_sepa.dat')) {
	if (!$gestor = fopen('../remesas/remesa_cuotas_sepa.dat', 'w')) {
		echo "No se puede abrir el archivo remesa_cuotas_sepa.dat";
		exit;
	}else{
		fwrite($gestor, $contenido) or die('Error al escribir la cabecera'); 
	}
}
fclose($gestor);
?>
