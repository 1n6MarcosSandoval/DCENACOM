<?php
include_once 'functions.php';

function CrearGeorss() {

$nombre_carpeta = 'xml/results';
if(!is_dir($nombre_carpeta)){ 
	mkdir($nombre_carpeta, 0777, true);
	chmod($nombre_carpeta, 0777);
}else{
	if (file_exists("xml/results/reporte.xml")) {
		unlink('xml/results/reporte.xml');
	}
}

$HOST1="10.2.233.164";
$PORT1=1521;
$SID1="ORCL";
$userName1="cenacom";
$passkey1="jxkGR";
$conOracle = conexionORACLE($HOST1, $PORT1, $SID1, $userName1, $passkey1);

	
$xmlCode="";
$xmlCode= $xmlCode.'<?xml version="1.0"  encoding="UTF-8"?>';
$xmlCode= $xmlCode.'<rss version="2.0" xmlns:geo="http://www.w3.org/2003/01/geo/wgs84_pos#">';
$xmlCode= $xmlCode.'<channel>';
$xmlCode= $xmlCode.'<title>Ultimos eventos registrados por el CENACOM-SEGOB</title>';
$xmlCode= $xmlCode.'<link>http://www.incendiosmovil.gob.mx/dcenacom/</link>';
$xmlCode= $xmlCode.'<description>Reportes relevantes CENACOM</description>';
	
	//************
	
$query1 = oci_parse($conOracle, "SELECT ID_REPORTE, EFECTO_ADVERSO, ORGANISMO_AVISO, TO_CHAR(FECHA_AVISO, 'HH24:MI DD-MON-YYYY') AS FECHA,CLAVE_LUGAR, CLASIFICACIONFENOMENO_ID, FECHA_INICIO_FENOMENO, AREAS_AFECTADAS, PERSONAS_AFECTADAS, LINEAS_VITALES, INFRAESTRUCTURA_DANADA, OBSERVACIONES, AUTOR, NIVEL from cenacom.reportes where ATENDIDO = 0 ORDER BY ID_REPORTE DESC");
if(!$query1){
	$e = oci_error($conOracle);
	trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	print "Error en la conexion a la base de datos";
}
else if(!oci_execute($query1)){
	$e = oci_error($query1);
	trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	print "Error en el query";
}
else{
	$contador=0;
	$ConcatenaEdo="";
	$ConcatenaMun="";
	$contadorEventos=0;
	$DatosEvento="Hola";
	$ElemtEvento = array();
	//mis argumentos para procesar
	while ($row1 = oci_fetch_array($query1, OCI_ASSOC+OCI_RETURN_NULLS)) {	
		//Obtener Datos para elementos puntuales
		$Cadena=$row1["CLAVE_LUGAR"];
		$logitudCad=strlen($Cadena);
		
		$idReporte=$row1["ID_REPORTE"];
		$efectoAdverso=$row1["EFECTO_ADVERSO"];
		$organismoAviso=$row1["ORGANISMO_AVISO"];
		$fechaAviso=$row1["FECHA"];
		$fenomenoPerturbador=$row1["CLASIFICACIONFENOMENO_ID"];
		$fechaInicioFenomeno=$row1["FECHA_INICIO_FENOMENO"];
		$areasAfectadas=$row1["AREAS_AFECTADAS"];
		$personasAfectadas=$row1["PERSONAS_AFECTADAS"];
		$lineasVitales=$row1["LINEAS_VITALES"];
		$infraestructuraDanada=$row1["INFRAESTRUCTURA_DANADA"];
		if(recuperaCampo($conOracle,'REPORTES','FENOMENOMAYORAFECTACION',$row1['ID_REPORTE'], 'ID_REPORTE') == '0' || recuperaCampo($conOracle,'REPORTES','FENOMENOMAYORAFECTACION',$row1['ID_REPORTE'], 'ID_REPORTE')== NULL){
			$fenomenoMayorAfectacion="Sin Fenómeno de Mayor Afectación";
		}
		else{
			$fenomenoMayorAfectacion=recuperaCampo($conOracle,'REPORTES','FENOMENOMAYORAFECTACION',$row1['ID_REPORTE'], 'ID_REPORTE');
		}
		if(recuperaCampo($conOracle,'REPORTES','TIPOFENOMENOMAYORAFECTACION',$row1['ID_REPORTE'], 'ID_REPORTE') == 0 || recuperaCampo($conOracle,'REPORTES','TIPOFENOMENOMAYORAFECTACION',$row1['ID_REPORTE'], 'ID_REPORTE')== NULL){
			$tipoFenomenoMayorAfectacion="Sin Fenómeno de Mayor Afectación";
		}
		else{
			$tipoFenomenoMayorAfectacion=recuperaCampo($conOracle,'REPORTES','TIPOFENOMENOMAYORAFECTACION',$row1['ID_REPORTE'], 'ID_REPORTE');
		}
		$observaciones=$row1["OBSERVACIONES"];
		$autor=$row1["AUTOR"];
		$latitud=recuperaCampo($conOracle,"coordenadas","LATITUD",$row1["CLAVE_LUGAR"], "CLVE_LUGAR");	
		$longitud=recuperaCampo($conOracle,"coordenadas","LONGITUD",$row1["CLAVE_LUGAR"], "CLVE_LUGAR");
		
		$xmlCode= $xmlCode."\n<item>\n";
		$xmlCode= $xmlCode."<title>CENACOM</title>\n";
		$xmlCode= $xmlCode."<idReporte>".$idReporte."</idReporte>\n";
		$xmlCode= $xmlCode."<efectoAdverso>".$efectoAdverso."</efectoAdverso>\n";
		$xmlCode= $xmlCode."<organismoAviso>".$organismoAviso."</organismoAviso>\n";
		$xmlCode= $xmlCode."<fechaAviso>".$fechaAviso."</fechaAviso>\n";
		$xmlCode= $xmlCode."<fenomenoPerturbador>".$fenomenoPerturbador."</fenomenoPerturbador>\n";
		$xmlCode= $xmlCode."<fechaInicioFenomeno>".$fechaInicioFenomeno."</fechaInicioFenomeno>\n";
		$xmlCode= $xmlCode."<areasAfectadas>".$areasAfectadas."</areasAfectadas>\n";
		$xmlCode= $xmlCode."<personasAfectadas>".$personasAfectadas."</personasAfectadas>\n";
		$xmlCode= $xmlCode."<lineasVitales>".$lineasVitales."</lineasVitales>\n";
		$xmlCode= $xmlCode."<infraestructuraDanada>".$infraestructuraDanada."</infraestructuraDanada>\n";
		$xmlCode= $xmlCode."<fenomenoMayorAfectacion>".$fenomenoMayorAfectacion."</fenomenoMayorAfectacion>\n";
		$xmlCode= $xmlCode."<tipoFenomenoMayorAfectacion>".$tipoFenomenoMayorAfectacion."</tipoFenomenoMayorAfectacion>\n";
		$xmlCode= $xmlCode."<observaciones>".$observaciones."</observaciones>\n";
		$xmlCode= $xmlCode."<nivel>".$row1["NIVEL"]."</nivel>\n";
		$xmlCode= $xmlCode."<autor>".$autor."</autor>\n";
		$xmlCode= $xmlCode."<geo:lat>".$latitud."</geo:lat>\n";
		$xmlCode= $xmlCode."<geo:long>".$longitud."</geo:long>\n";
		$xmlCode= $xmlCode."</item>\n";
	}
	cerrarConexionORACLE($conOracle);
}
$xmlCode= $xmlCode.'</channel>';
$xmlCode= $xmlCode.'</rss>';

$archivo = fopen("xml/results/reporte.xml","w"); 
fwrite($archivo, $xmlCode); 
fclose($archivo); 
}
CrearGeorSS();
?>