<?php
include 'functions.php';
include 'vars.php';
/*
require_once('FirePHPCore/FirePHP.class.php');
ob_start();
$firephp = FirePHP::getInstance(true);
 */

//Valida que la seccion de usuario sea correcta
session_start();
if(@$_SESSION['login'] != "si")
{
header("Location:index.php");
exit();
}

$conOracle=conexionORACLE($HOST, $PORT, $SID, $userName, $passkey);
?>

<html>
<head>
	<title>Nothing else matter...</title>
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/validacionUnico.js"></script>
	<script type="text/javascript" src="js/functions.js"></script>

</head>
<body>
	<div id="agregarLugar">
		<a href="#lugares" onclick="agregarLugar('lugar', 1);">Agregar lugar</a>
		<a href="#lugares" onclick="agregarLugarManual('lugar', 1);">Agregar lugar simplificado</a>
	</div>

	<div id="lugar" ></div>
	
	<input type="hidden" name="lugares" id="lugares">
	<input type="hidden" name="otrosLugares" id="otrosLugares">
	<button type="button" onclick="valuesAnidadosLugares('lugar');">come on</button>

</body>
</html> 