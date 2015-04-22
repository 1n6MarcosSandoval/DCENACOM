<?php
include 'functions.php';
include 'vars.php';
/*
require_once('FirePHPCore/FirePHP.class.php');
ob_start();
$firephp = FirePHP::getInstance(true);
 */

//Valida que la secion de usuario sea correcta
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
	<script type="text/javascript" src="js/functions.js"></script>
	<script language="JavaScript">
</script>
</head>
<body>
	<div id="agregarLugar">
	<a href="#lugar" onclick="agregarLugar('lugar',0);"> Agregar lugar</a> <a href="#lugar" onclick="agregarLugarManual('lugar',0);"> Agregar lugar simplificado</a><br/><br/>
	</div>
	<div id="lugar" >
	</div>
	<input type="hidden" name="lugares" id="lugares">


</body>
</html> 