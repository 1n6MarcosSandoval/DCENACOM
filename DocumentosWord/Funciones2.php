<?php
	include 'functions.php';
	//Conexion a BD Cenacom
	$HOST1="10.2.233.164";
	$PORT1=1521;
	$SID1="ORCL";
	$userName1="cenacom";
	$passkey1="jxkGR";
	//Conexion a oracle
	//$codigo = $_POST['codigo'];
	$conOracle1=conexionORACLE($HOST1, $PORT1, $SID1, $userName1, $passkey1);

	//*************************
	//Consulta  a la BD
	//Obtener últimos 32 elementos
	$query0 = oci_parse($conOracle1, 'SELECT COUNT(ID_REPORTE) AS MAXIMO FROM CENACOM.REPORTES');
	//Select COUNT(Supplier_ID) from suppliers
	oci_execute($query0);
	$row0 = oci_fetch_array($query0, OCI_ASSOC);
	$ReportesRango=$row0["MAXIMO"]-35;	
	/* Escribe correctamente el XML del GEORSS */	
	$xmlCode="";
	$xmlCode= $xmlCode.'<?xml version="1.0"  encoding="UTF-8"?>';
	$xmlCode= $xmlCode.'<rss version="2.0" xmlns:geo="http://www.w3.org/2003/01/geo/wgs84_pos#">';
	$xmlCode= $xmlCode.'<channel>';
	$xmlCode= $xmlCode.'<title>Ultimos eventos registrados por el CENACOM-SEGOB</title>';
	$xmlCode= $xmlCode.'<link>http://www.ssn.unam.mx</link>';
	$xmlCode= $xmlCode.'<description>Reportes relevantes CENACOM</description>';
		
	//************
	$query1 = oci_parse($conOracle1, "SELECT ID_REPORTE, EFECTO_ADVERSO, ORGANISMO_AVISO, TO_CHAR(FECHA_AVISO, 'HH24:MI DD-MON-YYYY') AS FECHA,CLAVE_LUGAR, CLASIFICACIONFENOMENO_ID, FECHA_INICIO_FENOMENO, AREAS_AFECTADAS, PERSONAS_AFECTADAS, LINEAS_VITALES, INFRAESTRUCTURA_DANADA, OBSERVACIONES, AUTOR from cenacom.reportes where ID_REPORTE >".$ReportesRango." AND CLAVE_LUGAR IS NOT NULL ORDER BY CR DESC");
	//$query1 = oci_parse($conOracle1, "SELECT *, TO_CHAR(FECHA_AVISO, 'HH24:MI DD-MON-YYYY') AS FECHA from cenacom.reportes where ID_REPORTE >".$ReportesRango." AND CLAVE_LUGAR IS NOT NULL ORDER BY CR DESC");
	if(!$query1){
		$e = oci_error($conOracle1);
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
			$observaciones=$row1["OBSERVACIONES"];
			$autor=recuperaCampo($conOracle1,"REPORTES","AUTOR",$row1["ID_REPORTE"], "ID_REPORTE");
			$latitud=recuperaCampo($conOracle1,"coordenadas","LATITUD",$row1["CLAVE_LUGAR"], "CLVE_LUGAR");
			$longitud=recuperaCampo($conOracle1,"coordenadas","LONGITUD",$row1["CLAVE_LUGAR"], "CLVE_LUGAR");
			
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
			$xmlCode= $xmlCode."<observaciones>".$observaciones."</observaciones>\n";
			$xmlCode= $xmlCode."<autor>".$autor."</autor>\n";
			/*$descripcion="<b>".strtoupper($fenomeno)."<br/>".$row1["FECHA"]."</b><br/><br/>";
			$descripcion=$descripcion."<br/><br/><b>Recomendaciones:</b><br/>".$row1["OBSERVACIONES"];
			$xmlCode= $xmlCode."<description><![CDATA[".$descripcion;
			$xmlCode= $xmlCode." ]]></description>\n";
			$xmlCode= $xmlCode."<link>";
			$xmlCode= $xmlCode."<![CDATA[http://www.incendiosmovil.gob.mx/dcenacom/visual_download.php?cr=".$row1["CR"]." ]]>";
			$xmlCode= $xmlCode."</link>\n";*/
			$xmlCode= $xmlCode."<geo:lat>".$latitud."</geo:lat>\n";
			$xmlCode= $xmlCode."<geo:long>".$longitud."</geo:long>\n";
			$xmlCode= $xmlCode."</item>\n";
		}
		cerrarConexionORACLE($conOracle1);
	}
	$xmlCode= $xmlCode.'</channel>';
	$xmlCode= $xmlCode.'</rss>';
	$file = 'results/REPORTE.xml';
	if(file_put_contents($file,$xmlCode)== TRUE){
		print $xmlCode;
	}
	/*FIN Escribe correctamente el XML del GEORSS */	
	//******************************************
	//***************************
?>