<?php
include 'functions.php';
include 'vars.php';


require_once 'C:\php\phpdocx\classes\CreateDocx.inc';

$docx = new CreateDocx();
$docx->setEncodeUTF8();


$conOracle=conexionORACLE($HOST, $PORT, $SID, $userName, $passkey);
$cr_in=$_GET["cr"];
// Prepare the statement
$stid = oci_parse($conOracle, 'SELECT * FROM CENACOM.REPORTES WHERE CR = ' .$cr_in);
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

$CENAPRED = array(
    'name' => 'headCENACOM.jpg',
    'scaling' => 100,
    'spacingTop' => 100,
    'spacingBottom' => 0,
    'spacingLeft' => 100,
    'spacingRight' => 0,
    'textWrap' => 1,
    'border' => 1,
    'borderDiscontinuous' => 1
);


$docx->addImage($CENAPRED);

// Fetch the results of the query
while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
	$contador=0;
	if($row[FECHA_REPORTE]!=NULL){
		//print "Fecha de Elaboración: " . $row[FECHA_REPORTE]."\n";
		$docx->addText("Fecha de Elaboración: " . $row[FECHA_REPORTE]);
		print "<br>Fecha de Elaboración: " . $row[FECHA_REPORTE]."\n";
	}
	if($row[CR]!=NULL){
		//print "Número de Reporte (CR): " . $row[CR]."\n";
		$nom="CR".$row[CR];
		$docx->addText("Número de Reporte: " . $row[CR]);
		print "<br>Número de Reporte: " . $row[CR]."\n";
	}
	
	if($row[EFECTO_ADVERSO]!=NULL){
		//print "Efecto Adverso: " . $row[EFECTO_ADVERSO]."\n";
		$docx->addText("Efecto Adverso: " . $row[EFECTO_ADVERSO]);
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
		//print "Estado: " . $dato . "\n";
		$docx->addText("Estado: " . $dato);
		print "<br>Estado: " . $dato."\n";
	}
	
	if($row[MUNICIPIO]!=NULL){
		$myArray = explode(',', $row[MUNICIPIO]);
		$dato = '';
		$i=0;
		for ($i = 0; $i < (sizeof($myArray)-1); $i++)
		$dato = $dato . recuperaCampo($conOracle, 'ANRO.LOCALIDADES', 'NOM_MUN', $myArray[$i], 'MUN'). ', '; 
		$dato = $dato . recuperaCampo($conOracle, 'ANRO.LOCALIDADES', 'NOM_MUN', $myArray[$i], 'MUN'); 
		//print "Municipio: " . $dato . "\n";
		$docx->addText("Municipio: " . $dato);
		print "<br>Municipio: " . $dato."\n";
	}
	
	if($row[LOCALIDAD]!=NULL){
		$myArray = explode(',', $row[LOCALIDAD]);
		$dato = '';
		$i=0;
		for ($i = 0; $i < (sizeof($myArray)-1); $i++)
		$dato = $dato . recuperaCampo($conOracle, 'ANRO.LOCALIDADES', 'NOM_LOC', $myArray[$i], 'LOC'). ', '; 
		$dato = $dato . recuperaCampo($conOracle, 'ANRO.LOCALIDADES', 'NOM_LOC', $myArray[$i], 'LOC'); 
		//print "Localidad: " . $dato . "\n";
		$docx->addText("Localidad: " . $dato);		
	}
	if($row[OTRO_LUGAR]!=NULL){
		//print "Lugar: " . $row[OTRO_LUGAR] . "\n";
		$docx->addText("Lugar: " . $row[OTRO_LUGAR]);
		print "<br>Lugar: " . $row[OTRO_LUGAR]."\n";
	}
	if($row[ORGANISMO_AVISO]!=NULL){
		$myArray = explode(',', $row[ORGANISMO_AVISO]);
		$dato = '';
		$i=0;
		for ($i = 0; $i < (sizeof($myArray)-1); $i++)
		$dato = $dato . recuperaCampo($conOracle, 'CENACOM.DEPENDENCIAS', 'NOMBRE', $myArray[$i], 'ID'). ', '; 
		$dato = $dato . recuperaCampo($conOracle, 'CENACOM.DEPENDENCIAS', 'NOMBRE', $myArray[$i], 'ID'); 
		//print "Organismo Aviso: " . $dato . "\n";
		$docx->addText("Organismo que Reporta: " . $dato);
		print "<br>Organismo que Reporta: " . $dato."\n";
	}
	
	if($row[FECHA_AVISO]!=NULL){
		//print "Fecha que reporta: " . $row[FECHA_AVISO] . "\n";
		$docx->addText("Fecha que Reporta: " . $row[FECHA_AVISO]);
		print "<br>Fecha que Reporta: " . $row[FECHA_AVISO]."\n";
	}
	if($row[TIPOFENOMENO_ID]!=NULL){
		$myArray = explode(',', $row[TIPOFENOMENO_ID]);
		$dato = '';
		$i=0;
		for ($i = 0; $i < (sizeof($myArray)-1); $i++)
			$dato = $dato . recuperaCampo($conOracle, 'CENACOM.TIPO_FENOMENO', 'NOMBRE', $myArray[$i], 'ID_FENOMENO'). ', '; 
		$dato = $dato . recuperaCampo($conOracle, 'CENACOM.TIPO_FENOMENO', 'NOMBRE', $myArray[$i], 'ID_FENOMENO'); 
		//print "Fenomeno Perturbador: " . $dato . "\n";
		$docx->addText("Fenómeno Perturbador: " . $dato);
		print "<br>Fenómeno Perturbador: " . $dato."\n";
	}
	
	//if($row[FECHA_INICIO_FENOMENO]!=NULL){
	//	//print "Fecha de inicio del fenómeno perturbador: " . $row[FECHA_INICIO_FENOMENO] . "\n";
	//	$docx->addText("Fecha de Inicio del Fenómeno Perturbador: " . $row[FECHA_INICIO_FENOMENO]);
	//	print "<br>Fecha de Inicio del Fenómeno Perturbador: " . $row[FECHA_INICIO_FENOMENO]."\n";
	//}
	
	switch($row[ID_TIPO_REPORTE]){
		case ($row[ID_TIPO_REPORTE]==0 || $row[ID_TIPO_REPORTE]==1):
			$sql = "SELECT TO_CHAR(FECHA_INICIO_FENOMENO, 'YYYY/MM/DD hh24:mm:ss') FROM CENACOM.REPORTES WHERE CR=$cr_in";
			$stmt=oci_parse($conOracle,$sql);
			oci_execute($stmt, OCI_DEFAULT);
			while ($raw = oci_fetch_array($stmt, OCI_ASSOC+OCI_RETURN_NULLS)) {
				foreach ($raw as $item) {
					$docx->addText("Fecha y Hora del Fenómeno Perturbador: " . $item);
					print "<br>Fecha y Hora del Fenómeno Perturbador: " . $item."\n";
				}
			}
			break;

		case ($row[ID_TIPO_REPORTE]==2 || $row[ID_TIPO_REPORTE]==3):
			$sql = "SELECT TO_CHAR(FECHA_INICIO_FENOMENO, 'YYYY/MM/DD hh24:mm:ss') FROM CENACOM.REPORTES WHERE CR=$cr_in";
			$stmt=oci_parse($conOracle,$sql);
			oci_execute($stmt, OCI_DEFAULT);
			while ($raw = oci_fetch_array($stmt, OCI_ASSOC+OCI_RETURN_NULLS)) {
				foreach ($raw as $item) {
					$docx->addText("Fecha y Hora Inicial del Fenómeno Perturbador: " . $item);
					print "<br>Fecha y Hora Inicial del Fenómeno Perturbador: " . $item."\n";
				}
			}
			break;
		
		case ($row[ID_TIPO_REPORTE]==4):
			$sql = "SELECT TO_CHAR(FECHA_INICIO_FENOMENO, 'YYYY/MM/DD hh24:mm:ss') FROM CENACOM.REPORTES WHERE CR=$cr_in";
			$stmt=oci_parse($conOracle,$sql);
			oci_execute($stmt, OCI_DEFAULT);
			while ($raw = oci_fetch_array($stmt, OCI_ASSOC+OCI_RETURN_NULLS)) {
				foreach ($raw as $item) {
					$docx->addText("Fecha y Hora Inicial del Fenómeno Perturbador: " . $item);
					print "<br>Fecha y Hora Inicial del Fenómeno Perturbador: " . $item."\n";
				}
			}
			$sql = "SELECT TO_CHAR(FECHA_FINAL_FENOMENO, 'YYYY/MM/DD hh24:mm:ss') FROM CENACOM.REPORTES WHERE CR=$cr_in";
			$stmt=oci_parse($conOracle,$sql);
			oci_execute($stmt, OCI_DEFAULT);
			while ($raw = oci_fetch_array($stmt, OCI_ASSOC+OCI_RETURN_NULLS)) {
				foreach ($raw as $item) {
					$docx->addText("Fecha y Hora Final del Fenómeno Perturbador: " . $item);
					print "<br>Fecha y Hora Final del Fenómeno Perturbador: " . $item."\n";
				}
			}			
			break;
	}
	
	if($row[AREAS_AFECTADAS]!=NULL){
		//print "Areas afectadas: " . $row[AREAS_AFECTADAS] . "\n";
		$docx->addText("Áreas Afectadas: " . $row[AREAS_AFECTADAS]);
		print "<br>Áreas Afectadas: " . $row[AREAS_AFECTADAS]."\n";
	}	
	if($row[PERSONAS_AFECTADAS]!=NULL){
		//print "Personas afectadas: " . $row[PERSONAS_AFECTADAS] . "\n";
		$docx->addText("Personas Afectadas: " . $row[PERSONAS_AFECTADAS]);
		print "<br>Personas Afectadas: " . $row[PERSONAS_AFECTADAS]."\n";
	}
	if($row[MUERTOS]!=NULL){
		//print "Muertos: " . $row[MUERTOS] . "\n";
		$docx->addText("Muertos: " . $row[MUERTOS]);
		print "<br>Muertos: " . $row[MUERTOS]."\n";
	}
	if($row[LESIONADOS]!=NULL){
		//print "Lesionados: " . $row[LESIONADOS] . "\n";
		$docx->addText("Lesionados: " . $row[LESIONADOS]);
		print "<br>Lesionados: " . $row[LESIONADOS]."\n";
	}
	if($row[EVACUADOS]!=NULL){
		//print "Evacuados: " . $row[EVACUADOS] . "\n";
		$docx->addText("Evacuados: " . $row[EVACUADOS]);
		print "<br>Evacuados: " . $row[EVACUADOS]."\n";
	}
	if($row[LINEAS_VITALES]!=NULL){
		//print "Lineas vitales: " . $row[LINEAS_VITALES] . "\n";
		$docx->addText("Lineas Vitales: " . $row[LINEAS_VITALES]);
		print "<br>Lineas Vitales: " . $row[LINEAS_VITALES]."\n";
	}	
	if($row[INFRAESTRUCTURA_DANADA]!=NULL){
		//print "Infraestructura dañanda: " . $row[INFRAESTRUCTURA_DANADA] . "\n";
		$docx->addText("Infraestructura Dañada: " . $row[INFRAESTRUCTURA_DANADA]);
		print "<br>Infraestructura Dañada: " . $row[INFRAESTRUCTURA_DANADA]."\n";
	}
	if($row[OBSERVACIONES]!=NULL){
		//print "Observaciones: " . $row[OBSERVACIONES] . "\n";
		$docx->addText("Observaciones: " . $row[OBSERVACIONES]);
		print "<br>Observaciones: " . $row[OBSERVACIONES]."\n";
	}

	if($row[ID_USUARIO]!=NULL){
		$myArray = explode(',', $row[ID_USUARIO]);
		$dato = '';
		$i=0;
		for ($i = 0; $i < (sizeof($myArray)-1); $i++)
			$dato = $dato . recuperaCampoS($conOracle, 'CENACOM.USUARIOS_CENACOM', 'NOMBRE', 'APELLIDO', $myArray[$i], 'ID_USUARIO'). ', '; 
		$dato = $dato . recuperaCampoS($conOracle, 'CENACOM.USUARIOS_CENACOM', 'NOMBRE', 'APELLIDO', $myArray[$i], 'ID_USUARIO'); 
		//print "Usuario: " . $dato . "\n";
		$docx->addText("Usuario: " . $dato);
		print "<br>Usuario: " . $dato."\n";
	}
}
$ruta="C:\inetpub\wwwroot\cenacom\\";
$docx->createDocx($ruta.$nom);


oci_free_statement($stid);
cerrarConexionORACLE($conOracle)

?>

<br>
<br>
<a href="download1.php?download_file=<?= $nom ?>.docx" target="_blank">Click para descargar</a>