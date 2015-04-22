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
<script type="text/javascript" src="js/validacionFinal.js"></script>
<script>
require(["dojo/parser", "dijit/form/DateTextBox"]);
</script>

</head>
<body class="claro">


	<div id="contenido">
<div>

<?php
	if(@$_GET['usuario']){
		$usuario=sanitize_sql_string(@$_GET['usuario']);

$conOracle=conexionORACLE($HOST, $PORT, $SID, $userName, $passkey);
$queryS="SELECT ID_USUARIO, NOMBRE, APELLIDO, CORREO, TURNO FROM CENACOM.usuarios_cenacom where ID_USUARIO=".$usuario;

//$queryS="SELECT ID_USUARIO, NOMBRE, APELLIDO, CORREO, TURNO FROM CENACOM.USUARIOS_CENACOM ORDER BY CR DESC WHERE ROWNUM <= ".$RegistrosAMostrarQuery." ) WHERE r > ".$RegistrosAEmpezar;
$stid = oci_parse($conOracle, $queryS);

if (!$stid) {
    $e = oci_error($conOracle);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

// Perform the logic of the query
$r = oci_execute($stid);
if (!$r) {
    $e = oci_error($stid);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

	$row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS);
	//echo $row["NOMBRE"]." ".$row["APELLIDO"];


?>
<h3>Actualizar datos de usuario:</h3>


	<form id='editandoCampo' name='editandoCampo' action="">	

	<table border='0px' cellpadding='4px' width='400x'>
		<tr>
		<td>Nombre:</td>
		<td>
			<input  type="text" name="edicionNombre" id="edicionNombre" title"Editar" value="<?php echo $row["NOMBRE"]; ?>" disabled=false>
			<input  type="checkbox"  name="edicionNombreX" id="edicionNombreX" onchange="javascript: activaDesactivaCampo('edicionNombre');">
		</td>
		</tr>
		<tr>
		<td>Apellido:</td>
		<td>
			<input  type="text" name="edicionApellido" id="edicionApellido" title"Editar" value="<?php echo $row["APELLIDO"]; ?>" disabled=false>
			<input  type="checkbox"  name="edicionApellidoX" id="edicionApellidoX" onchange="javascript: activaDesactivaCampo('edicionApellido');">
		</td>		
		</tr>
		<tr>
		<td>Correo electr&oacute;nico:</td> 
		<td>
			<input  type="text" name="edicionCorreo" id="edicionCorreo" value="<?php echo $row["CORREO"]; ?>" disabled=false>
			<input  type="checkbox"  name="edicionCorreoX" id="edicionCorreoX" title"Editar" onchange="javascript: activaDesactivaCampo('edicionCorreo');">
		</td>
		</tr>
		<tr>
		<td>Turno:</td>
		<td>
			<?php
				$turno=obtenerValorQuery($conOracle,'CENACOM', 'TURNOS', 'ID_TURNO', $row["TURNO"], 'NOMBRE');
				$queryT = oci_parse($conOracle, "SELECT ID_TURNO, NOMBRE FROM CENACOM.TURNOS ORDER BY NOMBRE");
				comboQuerySelecDisabledValor($queryT, "ID_TURNO", "NOMBRE",$row["TURNO"],"edicionTurno");
				
			?>
			<input  type="checkbox"  name="edicionTurnoX" id="edicionTurnoX" title"Editar" onchange="javascript: activaDesactivaCampo('edicionTurno');">
		</td>
		</tr>
	</form>


<?php
}
	cerrarConexionORACLE($conOracle);
?>


</div>
	</div>
</body>
</html>