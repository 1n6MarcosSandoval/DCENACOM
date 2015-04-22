<?php
/* Variables de conexion a la base de datos de Oracle */
$HOST="10.2.233.164";
$PORT=1521;
$SID="ORCL";
$userName="cenacom";
$passkey="jxkGR"
/* FIN Variables de conexion a la base de datos de Oracle */
?>


<?php


/* Funcion de conexion a la base de datos de Oracle */
function conectaORACLE($HOST, $PORT, $SID, $userName, $passkey) {
	$db = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = $HOST)(PORT = $PORT)))(CONNECT_DATA=(SID=$SID)))";
	$c = oci_connect($userName,$passkey,$db); 
	if (!$c) {
   		$m = oci_error();
  		print htmlentities($m['message']);
   		exit;
		}
		else {
   			//echo "Connected to Oracle!<br/>";
			return $c;			
   			}		
}
/* FIN Funcion de conexion a la base de datos de Oracle */



function escribeOracle($sql0, $sql){

$conOracleEscribe=conectaOracle($HOST, $PORT, $SID, $userName, $passkey);

//ESCRIBE EN LA BD QUERY DEL EVENTO
$escribeOracleEvento=oci_parse($conOracleEscribe,$sql0);
if (!$escribeOracleEvento) {
    $e = oci_error($conOracleEscribe);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
$resultadoEnOracleEvento=oci_execute($escribeOracleEvento);
if (!$resultadoEnOracleEvento) {
    $e = oci_error($escribeOracleEvento);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

	//ESCRIBE EN LA BD QUERY DEL REPORTE si se capturo el Eento
if($resultadoEnOracleEvento){

	$escribeOracleReporte=oci_parse($conOracleEscribe,$sql);
	if (!$escribeOracleReporte) {
    	$e = oci_error($conOracleEscribe);
    	trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}
	$resultadoEnOracleReporte=oci_execute($escribeOracleReporte);
	if (!$resultadoEnOracleReporte) {
    	$e = oci_error($escribeOracleReporte);
    	trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}
	
}

if($resultadoEnOracleEvento && $resultadoEnOracleReporte){
	return 1;
}else{
	return 0;
}

oci_close($conOracleEscribe);
}


function imprimeConWord0($cr){
	return 1;
}

//Cierra la conexion a la base de datos



?>