<?php
require("lib/CreaConexion.php");
require('lib/linegraph.php');
// Consultamos la BD
$conexion->connect('openrarp') or die('Error al conectar con la BD');
function convierte_texto($cadena){
	$str=iconv('UTF-8','windows-1252',$cadena);
	return $str;
}
$mediaSM=[
	  '08-02' => 3.7,
	  '08-23' => 4.0,
	  '09-13' => 4.3928571,
	  '10-04' => 4.2903226,
	  '10-25' => 5.1
  ];

$mediaSocio=[
	  '08-02' => 2.7,
	  '08-23' => 3.0,
	  '09-13' => 3.3928571,
	  '10-04' => 3.2903226,
	  '10-25' => 3.1
  ];

$m3=[
          '08-02' => 4.5,
          '08-23' => 2.0,
          '09-13' => 5.1785714,
          '10-04' => 3.9677419,
          '10-25' => 2.33333
  ];

$data = ['Tus vecinos' => $mediaSM,'Tu promedio' => $mediaSocio, 'Tu consumo' => $m3];

$colors = [
	    'Tus vecinos' => [114,171,237],
	    'Tu promedio' => [112,151,137],
	    'Tu consumo' => [163,36,153]
	 ];

class PDF_Graph extends PDF_LineGraph {
		function Header() {
			$this->Image('../images/openrarp_logo.png',20,8,25);
			$this->Image('../images/pozoSM1.png',70,0,145);
		}
}
$query_titular = "SELECT id_parcela,titular 
		FROM socios
	WHERE id_parcela='055'";
//echo $query;

$id_result_titular=@$conexion->query($query_titular) or die('Error al hacer la query');
$num_filas_titular=@$conexion->num_rows($id_result_titular);
$i=0;
while ($i < $num_filas_titular){
	$fila_titular=@$conexion->fetch_array($id_result_titular,$i);
	$id_parcela=$fila_titular['id_parcela'];
	$nombre_titular=$fila_titular['titular'];
	//echo "titlar: $nombre_titular";
	$pdf = new PDF_Graph();
	$pdf->SetFont('Arial','',10);
	$pdf->AddPage();
	$pdf->Ln(1);
	$pdf->Cell(185,5,convierte_texto($nombre_titular),0,1,'R');
	$pdf->Cell(185,5,"Urb. Sierramar, $id_parcela",0,1,'R');
	$pdf->Cell(185,5,'46220 PICASSENT (VALENCIA)',0,1,'R');
	$pdf->Ln(5);
	$pdf->Cell(50,0,'Residenciales Sierramar S.C.',0,0,'C');
	$pdf->Ln(80);
	$pdf->Cell(65);
	$pdf->LineGraph(110,30,$data,'H',$colors);
	//$pdf->Output('F','pdf/'.$id_parcela.'.pdf');
	$pdf->Output();
	$i++;
}
?>
