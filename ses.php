<?php

function ses(){

include_once 'functions.php';
include 'vars.php';

$conOracle=conexionORACLE($HOST, $PORT, $SID, $userName, $passkey);

	$x = 0;
	
	if (@$_SESSION['login'] == "si")
	{
	//Test de funcionalidad
/*	echo "Charly";
	$x=0;
	$x = $x+1;
	echo "<br/>x=".$x;*/


	//Ultimo registro del IDU
		//$LastU = oci_parse ($conOracle, "select IDU as valor from (select * from CENACOM.SESS order by IDU desc) where ROWNUM = 1");
		$LastU = oci_parse ($conOracle, "SELECT count (IDU) AS VALOR FROM CENACOM.SESS");
		oci_execute($LastU);
		$row_IDU = oci_fetch_array($LastU, OCI_ASSOC);
		$x = $row_IDU["VALOR"];
		$IDU = $x;
			//echo "IDU=".$IDU;
	
		$IDUs = $IDU+1;
			//echo "<br/>IDUs=".$IDUs;
	//Fin $IDU
	
	//Usuario
		
		$sqlUsuario = "select USUARIO,NOMBRE_REP from cenacom.ADMINISTRADORES where USUARIO = '".$_SESSION['nombre']."'";
		//$sqlUsuario = "select USUARIO from cenacom.ADMINISTRADORES where USUARIO = 'cenacomAdmin'";
			//echo $sqlUsuario;
		$stid = oci_parse($conOracle,$sqlUsuario);
		oci_execute($stid);
		while ($row = oci_fetch_array($stid, OCI_ASSOC)) {
			$USERS = $row["USUARIO"];
			$NOM_U = $row["NOMBRE_REP"];
		}
			//echo "<br/>USERS=".$USERS;
		
		$US_R = "";
		$sqlBusU = "SELECT USERS FROM CENACOM.SESS WHERE USERS ='".$USERS."'";
			//echo "sqlBusU=".$sqlBusU;
		$BusU = oci_parse ($conOracle,$sqlBusU);
		oci_execute($BusU);
		while ($row = oci_fetch_array($BusU, OCI_ASSOC)) {
			$US_R = $row["USERS"];
		}
			//echo "<br/>US_R=".$US_R;
			
		if ($US_R == $USERS){
		//Actualizar
	
			$sqlAct = "UPDATE CENACOM.SESS SET ACT=1 Where USERS='".$USERS."'";
			$Act = oci_parse ($conOracle,$sqlAct);
			
			if (!$Act) {
				$e = oci_error($conOracle);
				trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
			}
			$resultadoEnOracleEvento=oci_execute($Act);
			if (!$resultadoEnOracleEvento) {
				$e = oci_error($Act);
				trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
			}
	
		//Fin Actualizar
		}else{
		//Insertar
		
			$sqlIns = "INSERT INTO CENACOM.SESS (IDU,USERS,ACT,NOMBRE) values (".$IDUs.",'".$USERS."',1,'".$NOM_U."')";
				//echo $sqlIns;
			
			$ActSes = oci_parse ($conOracle, $sqlIns);
			
			if (!$ActSes) {
				$e = oci_error($conOracle);
				trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
			}
			$resultadoEnOracleEvento=oci_execute($ActSes);
			if (!$resultadoEnOracleEvento) {
				$e = oci_error($ActSes);
				trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
			}
		
		//Fin Insertar
		}
	//Fin $USERS	
	}	
cerrarConexionORACLE($conOracle);

}
?>