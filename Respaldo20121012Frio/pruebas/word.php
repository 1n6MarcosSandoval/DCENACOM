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
	}
	if($row[CR]!=NULL){
		//print "Número de Reporte (CR): " . $row[CR]."\n";
		$nom="CR".$row[CR];
		$docx->addText("Número de Reporte: " . $row[CR]);
	}
	
	if($row[EFECTO_ADVERSO]!=NULL){
		//print "Efecto Adverso: " . $row[EFECTO_ADVERSO]."\n";
		$docx->addText("Efecto Adverso: " . $row[EFECTO_ADVERSO]);
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
	}
	
	if($row[FECHA_AVISO]!=NULL){
		//print "Fecha que reporta: " . $row[FECHA_AVISO] . "\n";
		$docx->addText("Fecha que Reporta: " . $row[FECHA_AVISO]);
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
	}
	
	if($row[FECHA_INICIO_FENOMENO]!=NULL){
		//print "Fecha de inicio del fenómeno perturbador: " . $row[FECHA_INICIO_FENOMENO] . "\n";
		$docx->addText("Fecha de Inicio del Fenómeno Perturbador: " . $row[FECHA_INICIO_FENOMENO]);
	}
	if($row[AREAS_AFECTADAS]!=NULL){
		//print "Areas afectadas: " . $row[AREAS_AFECTADAS] . "\n";
		$docx->addText("Áreas Afectadas: " . $row[AREAS_AFECTADAS]);
	}	
	if($row[PERSONAS_AFECTADAS]!=NULL){
		//print "Personas afectadas: " . $row[PERSONAS_AFECTADAS] . "\n";
		$docx->addText("Personas Afectadas: " . $row[PERSONAS_AFECTADAS]);
	}
	if($row[MUERTOS]!=NULL){
		//print "Muertos: " . $row[MUERTOS] . "\n";
		$docx->addText("Muertos: " . $row[MUERTOS]);
	}
	if($row[LESIONADOS]!=NULL){
		//print "Lesionados: " . $row[LESIONADOS] . "\n";
		$docx->addText("Lesionados: " . $row[LESIONADOS]);
	}
	if($row[EVACUADOS]!=NULL){
		//print "Evacuados: " . $row[EVACUADOS] . "\n";
		$docx->addText("Evacuados: " . $row[EVACUADOS]);
	}
	if($row[LINEAS_VITALES]!=NULL){
		//print "Lineas vitales: " . $row[LINEAS_VITALES] . "\n";
		$docx->addText("Lineas Vitales: " . $row[LINEAS_VITALES]);
	}	
	if($row[INFRAESTRUCTURA_DANADA]!=NULL){
		//print "Infraestructura dañanda: " . $row[INFRAESTRUCTURA_DANADA] . "\n";
		$docx->addText("Infraestructura Dañada: " . $row[INFRAESTRUCTURA_DANADA]);
	}
	if($row[OBSERVACIONES]!=NULL){
		//print "Observaciones: " . $row[OBSERVACIONES] . "\n";
		$docx->addText("Observaciones: " . $row[OBSERVACIONES]);
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
	}
}
$ruta="C:\inetpub\wwwroot\cenacom\\";
$docx->createDocx($ruta.$nom);


oci_free_statement($stid);
cerrarConexionORACLE($conOracle)

?>


<a href="download1.php?download_file=<?= $nom ?>.docx" target="_blank">Click para descargar</a>