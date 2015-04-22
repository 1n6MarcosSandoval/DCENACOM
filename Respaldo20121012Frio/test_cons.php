<?php
include 'functions.php';
include 'vars.php';

$conOracle=conexionORACLE($HOST, $PORT, $SID, $userName, $passkey);
// Prepare the statement
$stid = oci_parse($conOracle, 'SELECT * FROM CENACOM.REPORTES');
if (!$stid) {
    $e = oci_error($conOracle);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

// Perform the logic of the query
$r = oci_execute($stid);
if (!$r) {
    $e = oci_error($stid);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

// Fetch the results of the query
print "<html>\n";
while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
	$contador=0;
    foreach ($row as $item) {
        //print "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : " ") . "</td>\n";
		if ($item != NULL){
			switch ($contador){
				case 1:
					print "	<br> CR: " . $item . "\n";
					break;
				
				case 2:
					print "	<br> Fecha Reporte: " . $item . "\n";
					break;
				
				case 3:
					print "	<br> Efecto Adverso: " . $item . "\n";
					break;
				
				case 4:
					print "	<br> Fecha Aviso: " . $item . "\n";
					break;
				
				case 5:
					$myArray = explode(',', $item);
					$dato = '';
					$i=0;
					for ($i = 0; $i < (sizeof($myArray)-1); $i++)
						$dato = $dato . recuperaCampo($conOracle, 'CENACOM.DEPENDENCIAS', 'NOMBRE', $myArray[$i], 'ID'). ', '; 
					//$i++;
					$dato = $dato . recuperaCampo($conOracle, 'CENACOM.DEPENDENCIAS', 'NOMBRE', $myArray[$i], 'ID'); 
					print "	<br> Organismo Aviso: " . $dato . "\n";	
					break;
				
				case 6:
					print "	<br> Areas Afectadas: " . $item . "\n";
					break;
				
				case 3:
					print "	<br> Personas Afectadas: " . $item . "\n";
					break;
				
				case 8:
					print "	<br> Muertos: " . $item . "\n";
					break;
				
				case 9:
					print "	<br> Lesionados: " . $item . "\n";
					break;
				
				case 10:
					print "	<br> Desaparecidos: " . $item . "\n";
					break;
					
				case 11:
					print "	<br> Evacuados: " . $item . "\n";
					break;
				
				case 12:
					print "	<br> Lineas Vitales: " . $item . "\n";
					break;
				
				case 13:
					print "	<br> Infraestructura Dañada: " . $item . "\n";
					break;
				
				case 14:
					print "	<br> Observaciones: " . $item . "\n";
					break;
				
				case 15:
					$myArray = explode(',', $item);
					$dato = '';
					$i=0;
					for ($i = 0; $i < (sizeof($myArray)-1); $i++)
						$dato = $dato . recuperaCampo($conOracle, 'CENACOM.DEPENDENCIAS', 'NOMBRE', $myArray[$i], 'ID'). ', '; 
					//$i++;
					$dato = $dato . recuperaCampo($conOracle, 'CENACOM.DEPENDENCIAS', 'NOMBRE', $myArray[$i], 'ID'); 
					print "	<br> Respuesta: " . $dato . "\n";						
					break;
				
				case 16:
					print "	<br> Link: " . $item . "\n";
					break;
					
				case 17:
					$myArray = explode(',', $item);
					$dato = '';
					$i=0;
					for ($i = 0; $i < (sizeof($myArray)-1); $i++)
						$dato = $dato . recuperaCampo($conOracle, 'CENACOM.USUARIOS_CENACOM', 'NOMBRE', $myArray[$i], 'ID_USUARIO'). ', '; 
					//$i++;
					$dato = $dato . recuperaCampo($conOracle, 'CENACOM.USUARIOS_CENACOM', 'NOMBRE', $myArray[$i], 'ID_USUARIO'); 
					print "	<br> Usuario: " . $dato . "\n";						
					break;	
				
				case 19:
					$myArray = explode(',', $item);
					$dato = '';
					$i=0;
					for ($i = 0; $i < (sizeof($myArray)-1); $i++)
						$dato = $dato . recuperaCampo($conOracle, 'CENACOM.TIPO_REPORTE', 'TIPO_REPORTE', $myArray[$i], 'ID_TIPO_REPORTE'). ', '; 
					//$i++;
					$dato = $dato . recuperaCampo($conOracle, 'CENACOM.TIPO_REPORTE', 'TIPO_REPORTE', $myArray[$i], 'ID_TIPO_REPORTE'); 
					print "	<br> Tipo de Reporte: " . $dato . "\n";						
					break;
				
				case 20:
					print "	<br> Clasificacion Fenomeno: " . $item . "\n";
					break;
				
				case 21:
					$myArray = explode(',', $item);
					$dato = '';
					$i=0;
					for ($i = 0; $i < (sizeof($myArray)-1); $i++)
						$dato = $dato . recuperaCampo($conOracle, 'ANRO.LOCALIDADES', 'NOM_ENT', $myArray[$i], 'ENTIDAD'). ', '; 
					//$i++;
					$dato = $dato . recuperaCampo($conOracle, 'ANRO.LOCALIDADES', 'NOM_ENT', $myArray[$i], 'ENTIDAD'); 
					print "	<br> Estado: " . $dato . "\n";						
					break;
				
				case 22:
					$myArray = explode(',', $item);
					$dato = '';
					$i=0;
					for ($i = 0; $i < (sizeof($myArray)-1); $i++)
						$dato = $dato . recuperaCampo($conOracle, 'ANRO.LOCALIDADES', 'NOM_MUN', $myArray[$i], 'MUN'). ', '; 
					//$i++;
					$dato = $dato . recuperaCampo($conOracle, 'ANRO.LOCALIDADES', 'NOM_MUN', $myArray[$i], 'MUN'); 
					print "	<br> Municipio: " . $dato . "\n";						
					break;
				
				case 23:
					$myArray = explode(',', $item);
					$dato = '';
					$i=0;
					for ($i = 0; $i < (sizeof($myArray)-1); $i++)
						$dato = $dato . recuperaCampo($conOracle, 'ANRO.LOCALIDADES', 'NOM_LOC', $myArray[$i], 'LOC'). ', '; 
					//$i++;
					$dato = $dato . recuperaCampo($conOracle, 'ANRO.LOCALIDADES', 'NOM_LOC', $myArray[$i], 'LOC'); 
					print "	<br> Localidad: " . $dato . "\n";						
					break;
				
				case 27:
					$myArray = explode(',', $item);
					$dato = '';
					$i=0;
					for ($i = 0; $i < (sizeof($myArray)-1); $i++)
						$dato = $dato . recuperaCampo($conOracle, 'CENACOM.TIPO_FENOMENO', 'NOMBRE', $myArray[$i], 'ID_FENOMENO'). ', '; 
					//$i++;
					$dato = $dato . recuperaCampo($conOracle, 'CENACOM.TIPO_FENOMENO', 'NOMBRE', $myArray[$i], 'ID_FENOMENO'); 
					print "	<br> Fenomeno: " . $dato . "\n";						
					break;	
				
				case 28:
					print "	<br> Otro Lugar: " . $item . "\n";
					break;
					
				default:;
			}
		}
		$contador++;
    }
}
print "</html>\n";


oci_free_statement($stid);
cerrarConexionORACLE($conOracle)

?>