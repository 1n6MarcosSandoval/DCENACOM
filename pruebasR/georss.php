<?php
include 'functions.php';
//include 'vars.php';
/*
require_once('FirePHPCore/FirePHP.class.php');
ob_start();
$firephp = FirePHP::getInstance(true);
 */
//Valida que la seccion de usuario sea correcta
/*
session_start();
if(@$_SESSION['login'] != "si")
{
header("Location:index.php");
exit();
}
 */ 

//Conexion a BD Cenacom
$HOST1="10.2.233.164";
$PORT1=1521;
$SID1="ORCL";
$userName1="cenacom";
$passkey1="jxkGR";

//Conexion a BD ArcSDE
$HOST2="10.2.233.164";
$PORT2=1521;
$SID2="ORCL";
$userName2="cenapredowner";
$passkey2="c3en4rpre3d2010$";

	
$conOracle1=conexionORACLE($HOST1, $PORT1, $SID1, $userName1, $passkey1);
$conOracle2=conexionORACLE($HOST2, $PORT2, $SID2, $userName2, $passkey2);

/*** OBTIENE LA COORDENADA DE LA LOCALIDAD ***/
/*
function coordenadas($conOracle2,$clave_mun){

	$query2 = oci_parse($conOracle2, "select LONGD, LATD from iter2010 where CLAVE='".$clave_mun."'");
	if(!$query2){
    	$e = oci_error($conOracle2);
    	trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		print "Error en la conexion a la base de datos";
	}else if(!oci_execute($query2)){
    	$e = oci_error($query2);
    	trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		print "Error en el query";
	}else{
		$row2 = oci_fetch_array($query2, OCI_ASSOC);
			print $row2['LONGD']."<br/>";
			print $row2['LATD']."<br/>";
	}
}
 */
/*** FIN OBTIENE LA COORDENADA DE LA LOCALIDAD ***/



print '<?xml version="1.0"  encoding="iso-8859-1"?>';
print '<rss version="2.0" xmlns:geo="http://www.w3.org/2003/01/geo/wgs84_pos#">';
print '<channel>';
print '<title>Ultimos eventos registrados por el CENACOM-SEGOB</title>';
print '<link>http://www.ssn.unam.mx</link>';
print '<description>Reportes relevantes CENACOM</description>';

	$query0 = oci_parse($conOracle1, 'SELECT MAX(ID_REPORTE) AS MAXIMO FROM CENACOM.REPORTES');
	oci_execute($query0);
	$row0 = oci_fetch_array($query0, OCI_ASSOC);
	$ReportesRango=$row0["MAXIMO"]-20;


	$query1 = oci_parse($conOracle1, "SELECT CR, CLAVE_LUGAR, TIPOFENOMENO_ID, PERSONAS_AFECTADAS, MUERTOS, LESIONADOS, DESAPARECIDOS, EVACUADOS, EFECTO_ADVERSO, OBSERVACIONES from cenacom.reportes where ID_REPORTE >".$ReportesRango." ORDER BY CR DESC");
	//$query1 = oci_parse($conOracle1, "SELECT CR, CLAVE_LUGAR, TIPOFENOMENO_ID, PERSONAS_AFECTADAS, MUERTOS, LESIONADOS, DESAPARECIDOS, EVACUADOS, EFECTO_ADVERSO from cenacom.reportes ORDER BY CR");
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
			
			print "\n<item>\n";
			print "<title>";
			print recuperaCampo($conOracle1, 'CENACOM.TIPO_FENOMENO', 'NOMBRE', $row1['TIPOFENOMENO_ID'], 'ID_FENOMENO');
			print "</title>\n";
			$descripcion="<b>Reporte:</b> ".$row1["CR"]."<br/><b>Efecto adverso:</b> ".$row1["EFECTO_ADVERSO"]."<br/><b>Personas afectadas:</b> ".$row1["PERSONAS_AFECTADAS"]."<br/><b>Muertos:</b> ".$row1["MUERTOS"]."<br/><b>Lesionados:</b> ".$row1["LESIONADOS"]."<br/><b>Desaparecidos:</b> ".$row1["DESAPARECIDOS"]."<br/><b>Evacuados:</b> ".$row1["EVACUADOS"]."<br/><br/><b>Observaciones:</b><br/>".$row1["OBSERVACIONES"];
			//$descripcion="Hola mundo<br/>";
			print "<description><![CDATA[".$descripcion;
			print " ]]></description>\n";
			//print "<link>";
			//print "<![CDATA[http://www2.ssn.unam.mx/ ]]>";
			//print "</link>\n";
			$latitud=recuperaCampo($conOracle2,"iter2010","LATD",$row1["CLAVE_LUGAR"], "CLAVE");
			$longitud=recuperaCampo($conOracle2,"iter2010","LONGD",$row1["CLAVE_LUGAR"], "CLAVE");
			print "<geo:lat>".$latitud."</geo:lat>\n";
			print "<geo:long>".$longitud."</geo:long>\n";
			print "</item>\n";
			
			$contador++;
		}
	}

print '</channel>';
print '</rss>';





//$clave_mun='270010043';
//coordenadas($conOracle2,$clave_mun);







cerrarConexionORACLE($conOracle1);
cerrarConexionORACLE($conOracle2);

?>