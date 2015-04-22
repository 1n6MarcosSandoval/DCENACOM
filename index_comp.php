<?php
	include 'functions.php';
	include 'vars.php';
	
	session_cache_limiter('private');
	$cache_limiter = session_cache_limiter();
	session_start();
	
	$conOracle=conexionORACLE($HOST, $PORT, $SID, $userName, $passkey);
	$stid = oci_parse($conOracle, 'SELECT * FROM CENACOM.ADMINISTRADORES');
	if (!$stid) {
		$e = oci_error($conOracle);
		print "error de conexión";
		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}

	// Perform the logic of the query
	$r = oci_execute($stid);
	if (!$r) {
		$e = oci_error($stid);
		print "error de ejecución";
		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}
	
	function recuperaCamp($conOr,$user){
		$stid = oci_parse($conOr, 'SELECT * FROM CENACOM.ADMINISTRADORES');
		$r=oci_execute($stid);
		if (!$r) {
			$e = oci_error($stid);
			print "error de ejecución";

		}
		while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
			if($row["USUARIO"]==$user)
				return $row["PASSWORD"];
			
		}
	}
	
	if((@$_POST["amigo"])&&(@$_POST["claveAmigo"])){
		$canservero="incorrecto";
		$user=sanitize_sql_string($_POST["amigo"],0,25);
		$contra=sanitize_paranoid_string($_POST["claveAmigo"],0,25);
		//$pass=recuperaCamp($conOracle,'CENACOM.ADMINISTRADORES','PASSWORD',$user,'USUARIO');
		$pass=recuperaCamp($conOracle,$user);
		if($pass==$contra)
			$canservero="correcto";
		if($canservero=="correcto"){
			$_SESSION['login'] = "si";
			$_SESSION['nombre'] = $_POST["amigo"];
			//print $_SESSION['login'];
			header("Location:principal.php");		
		}else{
			$_SESSION['tmptxt']="Tu no pasaraaaaaaaas!";
			$_SESSION['login'] = "no";
			header("Location:index.php");
		}
		
	}
?>