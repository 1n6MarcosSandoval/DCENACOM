<?php
session_start();
if(@$_SESSION['login'] != "si")
{
header("Location:index.php");
exit();
}

include 'functions.php';
include 'vars.php';

require_once 'C:\php\phpdocx\classes\CreateDocx.inc';
$docx = new CreateDocx();
$docx->setEncodeUTF8();
$conOracle=conexionORACLE($HOST, $PORT, $SID, $userName, $passkey);
?>
<?php
include 'arriba.php';
?>
		<!--IZQUIERDA -->

			<div class="left_articles">
				<h2>Sistema de captura</h2>
			</div>
<?php
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
	echo '<h2>Datos del Reporte '.$row["CR"].'</h2>';
	$contador=0;
	if($row[FECHA_REPORTE]!=NULL){
		//print "Fecha de Elaboración: " . $row[FECHA_REPORTE]."\n";
		//$docx->addText("Fecha de Elaboración: " . $row[FECHA_REPORTE]);
		//print "<b>Fecha de Elaboración: " . $row[FECHA_REPORTE]."\n</b>";
			$sql = "SELECT TO_CHAR(FECHA_REPORTE, 'YYYY/MM/DD hh24:mm:ss') FROM CENACOM.REPORTES WHERE CR=$cr_in";
			$stmt=oci_parse($conOracle,$sql);
			oci_execute($stmt, OCI_DEFAULT);
			while ($raw = oci_fetch_array($stmt, OCI_ASSOC+OCI_RETURN_NULLS)) {
				foreach ($raw as $item) {
					$docx->addText("Fecha y hora de elaboración: " . $item);
					print "<br><b>Fecha y hora de elaboración:</b> " . $item."\n";
				}
		}
		
	}
	if($row[CR]!=NULL){
		//print "Número de Reporte (CR): " . $row[CR]."\n";
		$nom="CR".$row[CR];
		$docx->addText("Número de Reporte: " . $row[CR]);
	}
	
	if($row[EFECTO_ADVERSO]!=NULL){
		//print "Efecto Adverso: " . $row[EFECTO_ADVERSO]."\n";
		$docx->addText("Efecto Adverso: " . filtroCaracteresXML($row[EFECTO_ADVERSO]));
		print "<br><br><h3>Efecto Adverso</h3>" . $row[EFECTO_ADVERSO]."\n";
	}
	if($row[ESTADO]!=NULL){
		$dato = recuperaCampo($conOracle, 'ANRO.LOCALIDADES', 'NOM_ENT', $row[ESTADO], 'ENTIDAD'); 
		//print "Estado: " . $dato . "\n";
		$docx->addText("Estado: " . filtroCaracteresXML($dato));
		print "<br><br><h3>Ubicaci&oacute;n</h3><b>Estado:</b> " . $dato."\n";
	}
	
	if($row[MUNICIPIO]!=NULL){
		$query='SELECT NOM_MUN from ANRO.LOCALIDADES WHERE ENTIDAD = '.$row[ESTADO].' AND MUN ='.$row[MUNICIPIO].' GROUP BY NOM_MUN';
		$dato = queryRecuperaCampo($conOracle, $query);
		$docx->addText("Municipio: " . $dato);
		print "<br><b>Municipio:</b> " . $dato."\n";
	}
	
	if($row[LOCALIDAD]!=NULL){
		if($row[LOCALIDAD]!='-'){
			$query='SELECT NOM_LOC FROM ANRO.LOCALIDADES WHERE ENTIDAD='.$row[ESTADO].' and MUN='.$row[MUNICIPIO].' and LOC='.$row[LOCALIDAD];
			$dato = queryRecuperaCampo($conOracle, $query);
			$docx->addText("Localidad: " . $dato);
			print "<br><b>Localidad:</b> " . $dato."\n";
		}
	}
	if($row[OTRO_LUGAR]!=NULL){
		if($row[OTRO_LUGAR]!="-"){
			$docx->addText("Lugar: " . filtroCaracteresXML($row[OTRO_LUGAR]));
			print "<br><b>Lugar:</b> " . $row[OTRO_LUGAR]."\n";
			}
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
		print "<br><br><h3>Organismo</h3><b>Organismo que Reporta:</b> " . $dato."\n";
	}
	
	if($row[FECHA_AVISO]!=NULL){
		//print "Fecha que reporta: " . $row[FECHA_AVISO] . "\n";
		//$docx->addText("Fecha que Reporta: " . $row[FECHA_AVISO]);
			$sql = "SELECT TO_CHAR(FECHA_AVISO, 'YYYY/MM/DD hh24:mm:ss') FROM CENACOM.REPORTES WHERE CR=$cr_in";
			$stmt=oci_parse($conOracle,$sql);
			oci_execute($stmt, OCI_DEFAULT);
			while ($raw = oci_fetch_array($stmt, OCI_ASSOC+OCI_RETURN_NULLS)) {
				foreach ($raw as $item) {
					$docx->addText("Fecha que reporta: " . $item);
					print "<br><b>Fecha que reporta:</b> " . $item."\n";
				}
			}	
		//print "<br><b>Fecha que Reporta:</b> " . $row[FECHA_AVISO]."\n";
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
		print "<br><br><h3>Acerca del Fen&oacute;meno</h3><b>Fen&oacute;meno Perturbador:</b> " . $dato."\n";
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
					print "<br><b>Fecha y Hora del Fenómeno Perturbador:</b> " . $item."\n";
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
					print "<br><b>Fecha y Hora Inicial del Fenómeno Perturbador:</b> " . $item."\n";
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
					print "<br><b>Fecha y Hora Inicial del Fenómeno Perturbador:</b> " . $item."\n";
				}
			}
			$sql = "SELECT TO_CHAR(FECHA_FINAL_FENOMENO, 'YYYY/MM/DD hh24:mm:ss') FROM CENACOM.REPORTES WHERE CR=$cr_in";
			$stmt=oci_parse($conOracle,$sql);
			oci_execute($stmt, OCI_DEFAULT);
			while ($raw = oci_fetch_array($stmt, OCI_ASSOC+OCI_RETURN_NULLS)) {
				foreach ($raw as $item) {
					$docx->addText("Fecha y Hora Final del Fenómeno Perturbador: " . $item);
					print "<br><b>Fecha y Hora Final del Fenómeno Perturbador:</b> " . $item."\n";
				}
			}			
			break;
	}
	
	if($row[AREAS_AFECTADAS]!=NULL){
		//print "Areas afectadas: " . $row[AREAS_AFECTADAS] . "\n";
		$docx->addText("Áreas Afectadas: " . filtroCaracteresXML($row[AREAS_AFECTADAS]));
		print "<br><br><h3>Afectaciones</h3><b>Áreas Afectadas:</b><br> " . $row[AREAS_AFECTADAS]."\n";
	}	
	if($row[PERSONAS_AFECTADAS]!=NULL){
		//print "Personas afectadas: " . $row[PERSONAS_AFECTADAS] . "\n";
		$docx->addText("Personas Afectadas: " . filtroCaracteresXML($row[PERSONAS_AFECTADAS]));
		print "<br><b>Personas Afectadas:</b><br> " . $row[PERSONAS_AFECTADAS]."\n";
	}
	if($row[MUERTOS]!=NULL){
		if($row[MUERTOS]>0){
			$docx->addText("Muertos: " . $row[MUERTOS]);
			print "<br><b>Muertos:</b> " . $row[MUERTOS]."\n";
		}
	}
	if($row[LESIONADOS]!=NULL){
		if($row[LESIONADOS]>0){
			$docx->addText("Lesionados: " . $row[LESIONADOS]);
			print "<br><b>Lesionados:</b> " . $row[LESIONADOS]."\n";
		}
	}
	if($row[EVACUADOS]!=NULL){
		if($row[EVACUADOS]>0){
			$docx->addText("Evacuados: " . $row[EVACUADOS]);
			print "<br><b>Evacuados:</b> " . $row[EVACUADOS]."\n";
		}
	}
	if($row[LINEAS_VITALES]!=NULL){
		$docx->addText("Lineas Vitales: " . filtroCaracteresXML($row[LINEAS_VITALES]));
		print "<br><b>Lineas Vitales:</b><br> " . $row[LINEAS_VITALES]."\n";
	}	
	if($row[INFRAESTRUCTURA_DANADA]!=NULL){
		//print "Infraestructura dañanda: " . $row[INFRAESTRUCTURA_DANADA] . "\n";
		$docx->addText("Infraestructura Dañada: " . filtroCaracteresXML($row[INFRAESTRUCTURA_DANADA]));
		print "<br><b>Infraestructura Dañada:</b><br> " . $row[INFRAESTRUCTURA_DANADA]."\n";
	}
	if($row[OBSERVACIONES]!=NULL){
		//print "Observaciones: " . $row[OBSERVACIONES] . "\n";
		$docx->addText("Observaciones: " . filtroCaracteresXML($row[OBSERVACIONES]));
		print "<br><b>Observaciones:</b><br> " . $row[OBSERVACIONES]."\n";
	}

	if($row[ID_USUARIO]!=NULL){
		$myArray = explode(',', $row[ID_USUARIO]);
		$dato = '';
		$i=0;
		for ($i = 0; $i < (sizeof($myArray)-1); $i++)
			$dato = $dato . recuperaCampoS($conOracle, 'CENACOM.USUARIOS_CENACOM', 'NOMBRE', 'APELLIDO', $myArray[$i], 'ID_USUARIO'). ', '; 
		$dato = $dato . recuperaCampoS($conOracle, 'CENACOM.USUARIOS_CENACOM', 'NOMBRE', 'APELLIDO', $myArray[$i], 'ID_USUARIO'); 
		//print "Usuario: " . $dato . "\n";
		$docx->addText("Autor(es): " . $dato);
		print "<br><br><h3>Autor(es): </h3>" . $dato."\n";
	}	
		if($row[LINK]!=NULL){
		$myArray = explode(',', $row[LINK]);
		$myArrayTitulos = explode(',', $row[LINK_TITULO]);
		$link = '';
		$linkTitulo = '';
		$i=0;
		print "<br><br><h3>Ligas de referenc&iacute;a:</h3> \n";
		$docx->addText("");
		$docx->addText("Ligas:");
		for ($i = 0; $i < (sizeof($myArray)-1); $i++){
			print "- ".filtroCaracteresXML($myArrayTitulos[$i]).":<br>";
			print filtroCaracteresXML($myArray[$i])."<br>";			
			$linkTitulo = "- ".filtroCaracteresXML($myArrayTitulos[$i]);
			$link = filtroCaracteresXML($myArray[$i]);
			$docx->addText($linkTitulo.": ".$link);
		}
	}
}
$ruta="C:\inetpub\wwwroot\cenacom\\";
$docx->createDocx($ruta.$nom);


oci_free_statement($stid);
cerrarConexionORACLE($conOracle);

?>


<br>
<br>
<a href="download1.php?download_file=<?= $nom ?>.docx" target="_blank"><h2>Click para descargar en formato MS Word</h2></a>

		<div class="footer">
			<p>Sistema Nacional de Protecci&oacute;n Civil</p>
		</div>
	</div>
</body>
</html>