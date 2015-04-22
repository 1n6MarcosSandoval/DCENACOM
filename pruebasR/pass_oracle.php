<?php

	require('./variables.php');
	include 'sanitize.php';
	include 'functions.php';
	include 'vars.php';

	function consultaSQLPass($campoSelect, $campoWhere, $user1, $pass1, $tablePass){
		$password="No";
		$valor=$pass1;
		$pass1=sanitize_paranoid_string($valor,5,20);
		$valor=$user1;
		$user1=sanitize_sql_string($valor,8,25);
		$password=consultaORACLEGetValor($campoSelect, $campoWhere, $user1, $tablePass);
		if($password==$pass1){return "correcto";}
		else{return "incorrecto";}
	}



	function consultaORACLEGetValor($campoSelect, $campoWhere, $valor, $tabla){
		$conOracle=conexionORACLE($HOST, $PORT, $SID, $userName, $passkey);
		$pass=recuperaCampo($conOracle,$tablePass,$campoSelect,$valor,$campoWhere);
		cerrarConexionORACLE($conOracle);
		return $pass;
	}



	 if((@$_POST["amigo"])&&(@$_POST["claveAmigo"])){
	   $canservero=consultaSQLPass("PASSWORD", "USUARIO", $_POST["amigo"], $_POST["claveAmigo"], "CENACOM.ADMINISTRADORES");
		if($canservero!="correcto"){
			$_SESSION['tmptxt']="Tu no pasaraaaaaaaas!";
			$_SESSION['login'] = "no";		
		}else{
			$_SESSION['login'] = "si";
			$_SESSION['nombre'] = $_POST["amigo"];
			header("Location:principal.php");
		}
	}

?>