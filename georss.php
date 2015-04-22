<?php
include 'functions.php';

//Conexion a BD Cenacom
$HOST1="10.2.233.164";
$PORT1=1521;
$SID1="ORCL";
$userName1="cenacom";
$passkey1="jxkGR";


$conOracle1=conexionORACLE($HOST1, $PORT1, $SID1, $userName1, $passkey1);

	$query00 = oci_parse($conOracle1, "SELECT CLAVE FROM CENACOM.GEORSS WHERE ID_LINK=1");
	//$query00 = oci_parse($conOracle1, "SELECT CR, CLAVE_LUGAR, TIPOFENOMENO_ID, PERSONAS_AFECTADAS, MUERTOS, LESIONADOS, DESAPARECIDOS, EVACUADOS, EFECTO_ADVERSO from cenacom.reportes ORDER BY CR");
	if(!$query00){
    	$e = oci_error($conOracle1);
    	trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		print "Error en la conexion a la base de datos";
	}else if(!oci_execute($query00)){
    	$e = oci_error($query00);
    	trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		print "Error en el query";
	}else{
		
	$row00 = oci_fetch_array($query00, OCI_ASSOC+OCI_RETURN_NULLS);

	}

$a=0;

if (@$_GET["value"]){

 if($_GET["value"]!="kx4f6gaqo74j1ccbhpq6ic5mer68sjolggwdg4s7pro884liroia2mgqftnlo"){

		if($_GET["value"]!=$row00['CLAVE'])
		{
			$a=404;
		}else{
			$a=160;
		}
	
	}else{
		$a=404;
	}

}


if($a==160){

/* Escribe correctamente el XML del GEORSS */	
print '<?xml version="1.0"  encoding="iso-8859-1"?>';
print '<rss version="2.0" xmlns:geo="http://www.w3.org/2003/01/geo/wgs84_pos#">';
print '<channel>';
print '<title>Ultimos eventos registrados por el CENACOM-SEGOB</title>';
print '<link>http://www.ssn.unam.mx</link>';
print '<description>Reportes relevantes CENACOM</description>';
	
	//ESCRIBE LA LLAVE DEFAULT DE SEGURIDAD PARA VISUALIZAR EL GEORSS
$sql0="UPDATE CENACOM.GEORSS SET CLAVE='kx4f6gaqo74j1ccbhpq6ic5mer68sjolggwdg4s7pro884liroia2mgqftnlo' WHERE ID_LINK=1";
$escribeOracleGEORSS=oci_parse($conOracle1,$sql0);
if (!$escribeOracleGEORSS) {
    $e = oci_error($conOracle1);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
$resultadoEnOracleGEORSS=oci_execute($escribeOracleGEORSS);
if (!$resultadoEnOracleGEORSS) {
    $e = oci_error($escribeOracleGEORSS);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
	//FIN ESCRIBE LA LLAVE DEFAULT DE SEGURIDAD PARA VISUALIZAR EL GEORSS
	
	
	//$query0 = oci_parse($conOracle1, 'SELECT MAX(ID_REPORTE) AS MAXIMO FROM CENACOM.REPORTES');
	$query0 = oci_parse($conOracle1, 'SELECT COUNT(ID_REPORTE) AS MAXIMO FROM CENACOM.REPORTES');
	//Select COUNT(Supplier_ID) from suppliers
	oci_execute($query0);
	$row0 = oci_fetch_array($query0, OCI_ASSOC);
	$ReportesRango=$row0["MAXIMO"]-35;
	//$ReportesRango=90;

	$query1 = oci_parse($conOracle1, "SELECT TO_CHAR(FECHA_AVISO, 'HH24:MI DD-MON-YYYY') AS FECHA, CR, CLAVE_LUGAR, NIVEL, TIPOFENOMENO_ID, PERSONAS_AFECTADAS, MUERTOS, LESIONADOS, DESAPARECIDOS, EVACUADOS, EFECTO_ADVERSO, OBSERVACIONES from cenacom.reportes where ID_REPORTE >".$ReportesRango." ORDER BY CR DESC");

	if(!$query1){
    	$e = oci_error($conOracle1);
    	trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		print "Error en la conexion a la base de datos";
	}else if(!oci_execute($query1)){
    	$e = oci_error($query1);
    	trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		print "Error en el query";
	}else{
		
	while ($row1 = oci_fetch_array($query1, OCI_ASSOC+OCI_RETURN_NULLS)) {			
			$latitud=recuperaCampo($conOracle1,"coordenadas","LATITUD",$row1["CLAVE_LUGAR"], "CLVE_LUGAR");
			$longitud=recuperaCampo($conOracle1,"coordenadas","LONGITUD",$row1["CLAVE_LUGAR"], "CLVE_LUGAR");
			$estado=recuperaCampo($conOracle1,"coordenadas","ESTADO",$row1["CLAVE_LUGAR"], "CLVE_LUGAR");
			$municipio=recuperaCampo($conOracle1,"coordenadas","MUNICIPIO",$row1["CLAVE_LUGAR"], "CLVE_LUGAR");
			$fenomeno=recuperaCampo($conOracle1, 'CENACOM.TIPO_FENOMENO', 'NOMBRE', $row1['TIPOFENOMENO_ID'], 'ID_FENOMENO');
			
			print "\n<item>\n";
			print "<title>";
			$titulo=recuperaCampo($conOracle1,"coordenadas","ESTADO",$row1["CLAVE_LUGAR"], "CLVE_LUGAR").",".recuperaCampo($conOracle1,"coordenadas","MUNICIPIO",$row1["CLAVE_LUGAR"], "CLVE_LUGAR");
			print $titulo;
			
			print "</title>\n";
			print "<NIVEL>";
			$nivel=$row1["NIVEL"];
			print $nivel;
			print "</NIVEL>\n";
			//$descripcion="numero reportes".$ReportesRango." <b>Reporte:</b> ".$row1["CR"]."<br/><b>Efecto adverso:</b> ".$row1["EFECTO_ADVERSO"]."<br/><b>Personas afectadas:</b> ".$row1["PERSONAS_AFECTADAS"]."<br/><b>Muertos:</b> ".$row1["MUERTOS"]."<br/><b>Lesionados:</b> ".$row1["LESIONADOS"]."<br/><b>Desaparecidos:</b> ".$row1["DESAPARECIDOS"]."<br/><b>Evacuados:</b> ".$row1["EVACUADOS"]."<br/><br/><b>Observaciones:</b><br/>".$row1["OBSERVACIONES"];
			
			//$descripcion="<b>".strtoupper($estado).", ".strtoupper($municipio)."<br/>".$row1["FECHA"]."</b><br/><br/>";
			$descripcion="<b>".strtoupper($fenomeno)."<br/>".$row1["FECHA"]."</b><br/><br/>";
			
			//$descripcion=$descripcion."Clave:".$row1["CLAVE_LUGAR"]."<br/>";
			//$descripcion=$descripcion."Latitud: ".$latitud."<br/>Longitud: ".$longitud."<br/><br/>";
			//$descripcion=$descripcion."<b>Reporte:</b> ".$row1["CR"]."<br/><b>Efecto adverso:</b> ".$row1["EFECTO_ADVERSO"]."<br/><b>Personas afectadas:</b> ".$row1["PERSONAS_AFECTADAS"]."<br/><b>Muertos:</b> ".$row1["MUERTOS"]."<br/><b>Lesionados:</b> ".$row1["LESIONADOS"]."<br/><b>Desaparecidos:</b> ".$row1["DESAPARECIDOS"]."<br/><b>Evacuados:</b> ".$row1["EVACUADOS"]."<br/><br/><b>Observaciones:</b><br/>".$row1["OBSERVACIONES"];
			$descripcion=$descripcion."<br/><br/><b>Recomendaciones:</b><br/>".$row1["OBSERVACIONES"];
			
			print "<description><![CDATA[".$descripcion;
			print " ]]></description>\n";
			print "<link>";
			print "<![CDATA[http://www.incendiosmovil.gob.mx/dcenacom/visual_download.php?cr=".$row1["CR"]." ]]>";
			print "</link>\n";
			print "<geo:lat>".$latitud."</geo:lat>\n";
			print "<geo:long>".$longitud."</geo:long>\n";
			print "</item>\n";
		}
	}

print '</channel>';
print '</rss>';

/*FIN Escribe correctamente el XML del GEORSS */	
}else{
/* Escribe OTRO XML */	
print '<?xml version="1.0"  encoding="iso-8859-1"?>';
print '<rss version="2.0">';
print '<channel>';
print '<title>RSS</title>';
print '<link>http://www.cenapred.unam.mx</link>';
print '<description>Noticias</description>';
print "\n<item>\n";
print "<title>";
print "Equipos de noticias";
print "</title>\n";
$descripcion="Se llevan a cabo labores de prevenci&oacute;n de desastres";
print "<description><![CDATA[".$descripcion;
print " ]]></description>\n";
print "<link>";
print "<![CDATA[http://www.cenapred.unam.mx]]>";
print "</link>\n";
print "</item>\n";
print '</channel>';
print '</rss>';
/*FIN Escribe OTRO XML */
}


cerrarConexionORACLE($conOracle1);

?>