<?php
include 'functions.php';
include 'vars.php';
include 'func_temp.php';

$conOracle=conexionORACLE($HOST, $PORT, $SID, $userName, $passkey);
$idReporte=$_GET["id_r"];
// Prepare the statement
$stid = oci_parse($conOracle, 'SELECT * FROM CENACOM.REPORTES WHERE ID_REPORTE = ' .$idReporte);
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
while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
	$contador=0;
	if($row[FECHA_REPORTE]!=NULL){
		print "<br>Fecha de Elaboración: " . $row[FECHA_REPORTE]."\n";

	}
	if($row[CR]!=NULL){
		print "<br>Número de Reporte (CR): " . $row[CR]."\n";


	}
	
	if($row[EFECTO_ADVERSO]!=NULL){
		print "<br>Efecto Adverso: " . $row[EFECTO_ADVERSO]."\n";

	}
	if($row[ESTADO]!=NULL){
		$myArray = explode(',', $row[ESTADO]);
		$dato = '';
		$i=0;
		for ($i = 0; $i < (sizeof($myArray)-1); $i++)
		$dato = $dato . recuperaCampo($conOracle, 'ANRO.LOCALIDADES', 'NOM_ENT', $myArray[$i], 'ENTIDAD'). ', '; 
		//$i++;
		$dato = $dato . recuperaCampo($conOracle, 'ANRO.LOCALIDADES', 'NOM_ENT', $myArray[$i], 'ENTIDAD'); 
		print "<br>Estado: " . $dato . "\n";

	}
	
	if($row[MUNICIPIO]!=NULL){
		$myArray = explode(',', $row[MUNICIPIO]);
		$dato = '';
		$i=0;
		for ($i = 0; $i < (sizeof($myArray)-1); $i++)
		$dato = $dato . recuperaCampo($conOracle, 'ANRO.LOCALIDADES', 'NOM_MUN', $myArray[$i], 'MUN'). ', '; 
		$dato = $dato . recuperaCampo($conOracle, 'ANRO.LOCALIDADES', 'NOM_MUN', $myArray[$i], 'MUN'); 
		print "<br>Municipio: " . $dato . "\n";

	}
	
	if($row[LOCALIDAD]!=NULL){
		$myArray = explode(',', $row[LOCALIDAD]);
		$dato = '';
		$i=0;
		for ($i = 0; $i < (sizeof($myArray)-1); $i++)
		$dato = $dato . recuperaCampo($conOracle, 'ANRO.LOCALIDADES', 'NOM_LOC', $myArray[$i], 'LOC'). ', '; 
		$dato = $dato . recuperaCampo($conOracle, 'ANRO.LOCALIDADES', 'NOM_LOC', $myArray[$i], 'LOC'); 
		print "<br>Localidad: " . $dato . "\n";
	
	}
	if($row[OTRO_LUGAR]!=NULL){
		print "<br>Lugar: " . $row[OTRO_LUGAR] . "\n";

	}
	if($row[ORGANISMO_AVISO]!=NULL){
		$myArray = explode(',', $row[ORGANISMO_AVISO]);
		$dato = '';
		$i=0;
		for ($i = 0; $i < (sizeof($myArray)-1); $i++)
		$dato = $dato . recuperaCampo($conOracle, 'CENACOM.DEPENDENCIAS', 'NOMBRE', $myArray[$i], 'ID'). ', '; 
		$dato = $dato . recuperaCampo($conOracle, 'CENACOM.DEPENDENCIAS', 'NOMBRE', $myArray[$i], 'ID'); 
		print "<br>Organismo Aviso: " . $dato . "\n";

	}
	
	if($row[FECHA_AVISO]!=NULL){
		print "<br>Fecha que reporta: " . $row[FECHA_AVISO] . "\n";

	}
	if($row[TIPOFENOMENO_ID]!=NULL){
		$myArray = explode(',', $row[TIPOFENOMENO_ID]);
		$dato = '';
		$i=0;
		for ($i = 0; $i < (sizeof($myArray)-1); $i++)
			$dato = $dato . recuperaCampo($conOracle, 'CENACOM.TIPO_FENOMENO', 'NOMBRE', $myArray[$i], 'ID_FENOMENO'). ', '; 
		$dato = $dato . recuperaCampo($conOracle, 'CENACOM.TIPO_FENOMENO', 'NOMBRE', $myArray[$i], 'ID_FENOMENO'); 
		print "<br>Fenomeno Perturbador: " . $dato . "\n";

	}
	
	if($row[FECHA_INICIO_FENOMENO]!=NULL){
		print "<br>Fecha de inicio del fenómeno perturbador: " . $row[FECHA_INICIO_FENOMENO] . "\n";

	}
	if($row[AREAS_AFECTADAS]!=NULL){
		print "<br>Areas afectadas: " . $row[AREAS_AFECTADAS] . "\n";

	}	
	if($row[PERSONAS_AFECTADAS]!=NULL){
		print "<br>Personas afectadas: " . $row[PERSONAS_AFECTADAS] . "\n";

	}
	if($row[MUERTOS]!=NULL){
		print "<br>Muertos: " . $row[MUERTOS] . "\n";

	}
	if($row[LESIONADOS]!=NULL){
		print "<br>Lesionados: " . $row[LESIONADOS] . "\n";

	}
	if($row[EVACUADOS]!=NULL){
		print "<br>Evacuados: " . $row[EVACUADOS] . "\n";

	}
	if($row[LINEAS_VITALES]!=NULL){
		print "<br>Lineas vitales: " . $row[LINEAS_VITALES] . "\n";

	}	
	if($row[INFRAESTRUCTURA_DANADA]!=NULL){
		print "<br>Infraestructura dañanda: " . $row[INFRAESTRUCTURA_DANADA] . "\n";

	}
	if($row[OBSERVACIONES]!=NULL){
		print "<br>Observaciones: " . $row[OBSERVACIONES] . "\n";

	}

	if($row[ID_USUARIO]!=NULL){
		$myArray = explode(',', $row[ID_USUARIO]);
		$dato = '';
		$i=0;
		for ($i = 0; $i < (sizeof($myArray)-1); $i++)
			$dato = $dato . recuperaCampoS($conOracle, 'CENACOM.USUARIOS_CENACOM', 'NOMBRE', 'APELLIDO', $myArray[$i], 'ID_USUARIO'). ', '; 
		$dato = $dato . recuperaCampoS($conOracle, 'CENACOM.USUARIOS_CENACOM', 'NOMBRE', 'APELLIDO', $myArray[$i], 'ID_USUARIO'); 
		print "<br>Usuario: " . $dato . "\n";

	}
}



oci_free_statement($stid);
cerrarConexionORACLE($conOracle)

?>