<?php
include 'functions.php';
include 'vars.php';
require_once('FirePHPCore/FirePHP.class.php');
ob_start();
$firephp = FirePHP::getInstance(true);

//Valida que la secion de usuario sea correcta
session_start();
if(@$_SESSION['login'] != "si")
{
header("Location:index.php");
exit();
}

$conOracle=conexionORACLE($HOST, $PORT, $SID, $userName, $passkey);

	$query = oci_parse($conOracle, 'SELECT CR, ESTADO, MUNICIPIO, LOCALIDAD FROM CENACOM.REPORTES');
	oci_execute($query);
	//$row = oci_fetch_array($query, OCI_ASSOC);

	while ($row = oci_fetch_array($query, OCI_ASSOC+OCI_RETURN_NULLS)) {
		//echo $row["CR"].", ".$row["ESTADO"].", ".$row["MUNICIPIO"].", ".$row["LOCALIDAD"]."<BR/>";
		
	/*ESTADO*/
	echo "CR: ".$row["CR"]." ";
	echo "ESTADO: ".$row["ESTADO"]."<br/>";
	$edo_tamanio=strlen($row["ESTADO"]);
	
	switch ($edo_tamanio) {
		case 1:
			$nuevo_edo="0".$row["ESTADO"];
			break;
		default:
			$nuevo_edo=$row["ESTADO"];
			break;
	}
	echo "NUEVO ESTADO: ".$nuevo_edo."<br/>";
	/*FIN ESTADO*/	

	/*MUNICIPIO*/
	echo "MUNICIPIO: ".$row["MUNICIPIO"]."<br/>";
	$mun_tamanio=strlen($row["MUNICIPIO"]);
	
	switch ($mun_tamanio) {
		case 1:
			$nuevo_mun="00".$row["MUNICIPIO"];
			break;
		case 2:
			$nuevo_mun="0".$row["MUNICIPIO"];
			break;
		default:
			$nuevo_mun=$row["MUNICIPIO"];
			break;
	}
	echo "NUEVO MUNICIPIO: ".$nuevo_mun."<br/>";
	/*FIN MUNICIPIO*/
	
	/*LOCALIDAD*/
	echo "LOCALIDAD: ".$row["LOCALIDAD"]."<br/>";
	$loc_tamanio=strlen($row["LOCALIDAD"]);
	
	if($row["LOCALIDAD"]=="0"){
		$loc_tamanio=5;
	}
	$firephp->log($loc_tamanio, 'Tamanio');
	switch ($loc_tamanio) {
		case 1:
			$nuevo_loc="000".$row["LOCALIDAD"];
			break;
		case 2:
			$nuevo_loc="00".$row["LOCALIDAD"];
			break;
		case 3:
			$nuevo_loc="0".$row["LOCALIDAD"];
			break;
		case 4:
			$nuevo_loc=$row["LOCALIDAD"];
			break;
		default:
			$nuevo_loc='0001';
			break;
	}
	echo "NUEVO LOCALIDAD: ".$nuevo_loc."<br/>";
	/*FIN LOCALIDAD*/
	
	echo "NUEVA CLAVE: ".$nuevo_edo.$nuevo_mun.$nuevo_loc."<br/>";
	
	/*ACTUALIZA LA BD */
	$queryEdo="UPDATE CENACOM.REPORTES SET CLAVE_LUGAR='".$nuevo_edo.$nuevo_mun.$nuevo_loc."' WHERE CR=".$row["CR"];
	
	$escribeOracleAct=oci_parse($conOracle,$queryEdo);
	if (!$escribeOracleAct) {
    	$e = oci_error($conOracle);
    	trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}
	$resultadoEnOracleAct=oci_execute($escribeOracleAct);
	if (!$resultadoEnOracleAct) {
    	$e = oci_error($escribeOracleAct);
    	trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}

	if($resultadoEnOracleAct){
		echo '<h3>Reporte Actualizado</h3>';
	}else{
		echo 'Hubo un error con la actualizaci&oacute;n en la base de datos. <br/>';	
	}	
	/* FIN ACTUALIZA LA BD */	


	echo "<br/>";
	}

cerrarConexionORACLE($conOracle);

?>