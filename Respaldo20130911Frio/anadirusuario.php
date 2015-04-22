<?php
include 'functions.php';
include 'vars.php';

session_start();
if(@$_SESSION['login'] != "si")
{
header("Location:index.php");
exit();
}
$conOracle=conexionORACLE($HOST, $PORT, $SID, $userName, $passkey);

require_once('FirePHPCore/FirePHP.class.php');
ob_start();
$firephp = FirePHP::getInstance(true);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<!-- <meta http-equiv="content-type" content="text/html; charset=utf-8" /> -->
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>Reportes relevantes</title>
<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/dojo/1.8/dijit/themes/claro/claro.css">
<link rel="stylesheet" href="images/style.css" type="text/css" />
<script src="//ajax.googleapis.com/ajax/libs/dojo/1.8.0/dojo/dojo.js" data-dojo-config='async: true, parseOnLoad: true'></script>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/functions.js"></script>

<script>
require(["dojo/parser", "dijit/form/DateTextBox"]);
</script>

</head>
<body class="claro">
	<div id="contenidosActualiza">
<div>

<?php
$conOracle=conexionORACLE($HOST, $PORT, $SID, $userName, $passkey);
?>
<h3>Nuevo usuario:</h3>
<div id="anadirUsuario" name="anadirUsuario"></div>

	<form id='anadirCampo' name='anadirCampo' method="post" action="javascript:anadeUsuario();">
	<table border='0px' cellpadding='4px' width='400x'>
		<tr>
		<td>Nombre:</td>
		<td>
			<input  type="text" name="anadirNombre" id="anadirNombre" title"Nombre" value="">
		</td>
		</tr>
		<tr>
		<td>Apellido:</td>
		<td>
			<input  type="text" name="anadirApellido" id="anadirApellido" title"Apellido" value="">
		</td>		
		</tr>
		<tr>
		<td>Correo electr&oacute;nico:</td> 
		<td>
			<input  type="text" name="anadirCorreo" id="anadirCorreo" title"Correo" value="">
		</td>
		</tr>
		<tr>
		<td>Turno:</td>
		<td>
			<?php
				$queryT = oci_parse($conOracle, "SELECT ID_TURNO, NOMBRE FROM CENACOM.TURNOS ORDER BY NOMBRE");
				comboQuery($queryT, "ID_TURNO", "NOMBRE","anadirTurno");
			?>
		</td>
		</tr>
		</table>
		<br />
	</form>

	<input type="button" value="A&ntilde;adir" onclick="valida('nuevo')">
	<input type="button" value="Cancelar" onclick='muestra_oculta_capa("nuevoUsuario");'>


<?php

	cerrarConexionORACLE($conOracle);
?>


</div>

	</div>
</body>
</html>