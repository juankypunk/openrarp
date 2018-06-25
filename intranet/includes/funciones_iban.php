<?

 /**
 * Gernerar el DC, dígito control de IBAN, y devolver en nuevo IBAN con DC
 * 
 * @link http://www.desarrolloweb.com/articulos/2484.php
 * @param string $_iban
 * @return $iban_
 */
 function generarDCInToIban( $_iban ) {
  
  $ibanConDC_ = -1;
  
  // IBAN sin DC, DC = 00 : BE00999999999999 
   // IBAN con DC, DC = 89 : BE89999999999999
  
  // Mover los cuatro primeros caracteres del número IBAN a la derecha: 
  $ibanConDC_ = substr($_iban,4)."".substr($_iban,0,4);
  
  
  // Convertir las letras a números según la siguiente tabla:
  // A=10 G=16 M=22  S=28 Y=34
  // B=11 H=17 N=23  T=29 Z=35
  // C=12 I=18 O=24 U=30
  // D=13 J=19 P=25 V=31
  // E=14 K=20 Q=26 W=32
  // F=15 L=21 R=27 X=33
  $letras_array = array("A","B","C","D","E","F","G","H","I","J","K","L",
        "M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
  $numeros_array = array("10","11","12","13","14","15","16","17","18","19","20","21",
        "22","23","24","25","26","27","28","29","30","31","32","33","34","35");
  
  
  $ibanConDC_ = str_replace( $letras_array, $numeros_array, $ibanConDC_);
  
  
  // Aplicar la operación módulo 97 y restar al número 98, el valor obtenido. 
  // Si el resultado consta de sólo un dígito, insertar un cero a la izquierda.
  
  //$modulo97 = intval($ibanConDC_) % 97;
  //$modulo97 = $ibanConDC_ % 97;
  $modulo97 = bcmod($ibanConDC_,  97);
  
  $dc = 98 - $modulo97;
  
  // insertar 0 a la izquierda si fuera menor de dos dígitos
  $dc = sprintf("%02d",$dc);
  
  // Sustituimos los dígitos 2 y 4 por el $dc
  $ibanConDC_ =  substr($_iban,0,2).$dc.substr($_iban,4);
  
  
  return $ibanConDC_;
 }

/**
 * Calcular IBAN
 * 
 * @link https://empresas.bankinter.com/www/es-es/cgi/empresas+fichhtml?nombre=empresas/cmd_exterior/cmd_negocio_internacional/calcula_iban.html
 * @param string $_entidad, $_sucursal, $_dc, $_cuenta
 * @return $iban_
 */
 function calcularIban( $_entidad, $_sucursal, $_dc, $_cuenta ) {
  
  $iban_ = -1;
  
  // CCC :      01280010120123456789
   // IBAN : ES7001280010120123456789
  
  $codPais = "ES";
  $dc = "00"; // No sabemos el dígito de control del IBAN, ponemos 00
  
  $iban_ = $codPais."".$dc."".$_entidad."".$_sucursal."".$_dc."".$_cuenta;
  
  return $iban_;
 }

//$iban=generarDCInToIban(calcularIban('0065','0174','00','0001040377'));
//$iban=generarDCInToIban(calcularIban('0075','0028','48','0600272857'));
//echo "IBAN $iban\n";
?>
