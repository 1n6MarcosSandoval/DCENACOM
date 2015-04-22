<?php
include 'functions.php';
include 'vars.php';

session_start();
if(@$_SESSION['login'] != "si")
{
header("Location:index.php");
exit();
}elseif(@$_SESSION['nombre']!="cenacomAdmin"){
header("Location:principal.php");
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
<script type="text/javascript" src="js/validacionUsuarioAct.js"></script>
<script>
require(["dojo/parser", "dijit/form/DateTextBox"]);
</script>

</head>
<body class="claro">


	<div id="contenidosEdita">
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
<div id="actualizaUsuario" name="actualizaUsuario">

	<form id='editandoCampo' name='editandoCampo' method="post" action="javascript:actualizaUsuario();">
	<table border='0px' cellpadding='4px' width='400x'>
		<tr>
		<td>Nombre:</td>
		<td>
			<input  type="text" name="edicionNombre" id="edicionNombre" title"Nombre" value="<?php echo $row["NOMBRE"]; ?>">
		</td>
		</tr>
		<tr>
		<td>Apellido:</td>
		<td>
			<input  type="text" name="edicionApellido" id="edicionApellido" title"Apellido" value="<?php echo $row["APELLIDO"]; ?>">
		</td>		
		</tr>
		<tr>
		<td>Correo electr&oacute;nico:</td> 
		<td>
			<input  type="text" name="edicionCorreo" id="edicionCorreo" title"Correo" value="<?php echo $row["CORREO"]; ?>">
		</td>
		</tr>
		<tr>
		<td>Turno:</td>
		<td>
			<?php
				$turno=obtenerValorQuery($conOracle,'CENACOM', 'TURNOS', 'ID_TURNO', $row["TURNO"], 'NOMBRE');
				$queryT = oci_parse($conOracle, "SELECT ID_TURNO, NOMBRE FROM CENACOM.TURNOS ORDER BY NOMBRE");
				comboQuerySelecValor($queryT, "ID_TURNO", "NOMBRE",$row["TURNO"],"edicionTurno");
			?>
		</td>
		</tr>
		</table>
		<input type="hidden" name="usuario" id="usuario" value="<?php print $usuario; ?>">
		<br />
	<input type="button" value="Actualizar" onclick="valida('edicion');">
	<input type="button" value="Cancelar" onclick='muestra_oculta_capa("editaUsuario");'>
	
	</form>

	<form id='eliminaCampo' name='eliminaCampo' method="post" action="javascript:eliminaUsuario();">
		<input type="hidden" name="usuarioElimina" id="usuarioElimina" value="<?php print $usuario; ?>">
		<input type="button" value="Eliminar" onclick="valida('elimina')">
	</form>
</div>
<?php
}
	cerrarConexionORACLE($conOracle);
?>


</div>

	</div>
</body>
</html>