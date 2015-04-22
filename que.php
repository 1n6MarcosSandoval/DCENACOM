<?php
include_once 'functions.php';
include_once 'vars.php';

$conOracle=conexionORACLE($HOST, $PORT, $SID, $userName, $passkey);
	
	
	$crReal = $_POST["crReal"];
	//echo "Cr:".$crReal;
	
	echo "Los registros anteriores han sido eliminados.";
	
	$borrar = "delete from cenacom.geor where ID_REPORTE =".$crReal;
	//echo $borrar;
	$escribeOracleEvento=oci_parse($conOracle,$borrar);
if (!$escribeOracleEvento) {
    $e = oci_error($conOracle);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
$resultadoEnOracleEvento=oci_execute($escribeOracleEvento);
if (!$resultadoEnOracleEvento) {
    $e = oci_error($escribeOracleEvento);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<!--<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />-->
<title>Reportes relevantes</title>
<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/dojo/1.8/dijit/themes/claro/claro.css">
<link rel="stylesheet" href="images/style.css" type="text/css" />

<script src="//ajax.googleapis.com/ajax/libs/dojo/1.8.0/dojo/dojo.js" data-dojo-config='async: true, parseOnLoad: true'></script>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/functions.js"></script>

<script>
require(["dojo/parser", "dijit/form/DateTextBox"]);
</script>

</head><meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<title></title>
</head>

<body>
	 <?php 

	 ?>
	<br/>
	<div id="agregarLugar"><a href="#Lugar" onclick="agregarLugar('lugar',0)">Agregar Lugar</a></div>
	<div id="lugar">
		<div id = "lugar0">
		</div>
	</div>
	<br/><br/>	

</body>
</html>