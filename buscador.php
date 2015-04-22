<?php
include 'functions.php';
include 'vars.php';
require_once('FirePHPCore/FirePHP.class.php');
ob_start();
$firephp = FirePHP::getInstance(true);

session_start();
if(@$_SESSION['login'] != "si")
{
header("Location:index.php");
exit();
}
$conOracle=conexionORACLE($HOST, $PORT, $SID, $userName, $passkey,'AL32UTF8');
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
<script type="text/javascript" src="js/validaBusqueda.js"></script>


<script>
require(["dojo/parser", "dijit/form/DateTextBox"]);
</script>

</head>
<body>

	<?php
	include 'formsMenuHead.php';
	?>
	
	
<h1>Sistema de captura</h1>

		<!--IZQUIERDA -->
		<div class="left">
			<div class="lboxForm">

	<h2>Reporte de tipo Inicial</h2>


	<br />
<?php
//Validar si existe el número de reporte antes de enviar 
?>
	<br />
	<form method="post" id="lista_estados" name="lista_estados" action="lista_estados.php">
		<select id="'BuscarEdo" title="BuscarEdo" name="BuscarEdo">';
			<option value="0">Seleccionar</option>
			<option value="1">Aguascalientes</option>
			<option value="2">Baja California</option>
			<option value="3">Baja California Sur</option>
			<option value="4">Campeche</option>
			<option value="5">Coahuila de Zaragoza</option>
			<option value="6">Colima</option>
			<option value="7">Chiapas</option>
			<option value="8">Chihuahua</option>
			<option value="9">Distrito Federal</option>
			<option value="10">Durango</option>
			<option value="11">Guanajuato</option>
			<option value="12">Guerrero</option>
			<option value="13">Hidalgo</option>
			<option value="14">Jalisco</option>
			<option value="15">Mexico</option>
			<option value="16">Michoacan de Ocampo</option>
			<option value="17">Morelos</option>
			<option value="18">Nayarit</option>
			<option value="19">Nuevo Leon</option>
			<option value="20">Oaxaca</option>
			<option value="21">Puebla</option>
			<option value="22">Queretaro</option>
			<option value="23">Quintana Roo</option>
			<option value="24">San Luis Potosi</option>
			<option value="25">Sinaloa</option>
			<option value="26">Sonora</option>
			<option value="27">Tabasco</option>
			<option value="28">Tamaulipas</option>
			<option value="29">Tlaxcala</option>
			<option value="30">Veracruz de Ignacio de la Llave</option>
			<option value="31">Yucatan</option>
			<option value="32">Zacatecas</option>
		</select>
		<br/>
		<input type="submit" value="Buscar por Estado" /><br />
	</form>
	<br />
	<br />
	<form method="post" id="lista_fenom" name="lista_fenom" action="lista_fenom.php">
	<?php
		$query = oci_parse($conOracle, 'SELECT ID, CLASIFICACION from ANRO.CLASIFICACIONFENOMENO ORDER BY ID');
		comboQueryJS($query,"ID", 'CLASIFICACION', 'ClasificacionFenomeno', 'TipoFenomeno', 'fenomeno', 'Seleccionar','Clasificaci&oacute;n y tipo de f&eacute;nomeno');
	?>
	<br/>
		<input type="submit" value="Buscar por Fenomeno" /><br />
	</form>
	<br />
	<br />
	<form method="post" id="lista_reportes" name="lista_reportes" action="lista_reportes.php">
		<input id="BuscarRepo" name="BuscarRepo" title="Buscar por numero de Reporte" style="width: 76px">
		<br/>
		<input type="button" value="Buscar por numero de Reporte"  onclick ="valida()"/>
	</form>
	


			</div>
		</div>
		<!-- FIN DE IZQUIERDA -->
		


		<div class="footer">
			<p>Sistema Nacional de Protecci&oacute;n Civil</p>
		</div>
	</div>
</body>
</html>