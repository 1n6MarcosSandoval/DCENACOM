<?php
include 'functions.php';
include 'vars.php';
$conOracle=conexionORACLE($HOST, $PORT, $SID, $userName, $passkey);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<!-- <meta http-equiv="content-type" content="text/html; charset=utf-8" /> -->
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>PRUEBAS</title>
<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/dojo/1.8/dijit/themes/claro/claro.css">
<script src="//ajax.googleapis.com/ajax/libs/dojo/1.8.0/dojo/dojo.js" data-dojo-config='async: true, parseOnLoad: true'></script>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/functions.js"></script>
<script type="text/javascript" src="js/validacionfinal.js"></script>
<script>
require(["dojo/parser", "dijit/form/DateTextBox"]);
</script>

</head>
<body class="claro">

<?php
//eliminaCaracteresRepetidos("6,8,9,10,88,6,1,5,9,4,4,9,5,6");
?>

<?php
	echo '<h3> Seleccione tipo de reporte:</h3>';
	echo '<table cellpadding="4px" width="500px">';
	echo '<tr>';
	echo '<td width="50%">';
	echo 'Reporte de tipo &Uacute;nico.';
	echo '</td>';
	echo '<td>';
	echo '<a href="formUnico.php">Generar reporte</a>';
	echo '</td>';
	echo '</tr>';
	echo '<td width="50%">';
	echo 'Reporte de tipo Alcance.';
	echo '</td>';
	echo '<td>';
	echo '<a href="formInicial.php">Generar reporte</a>';
	echo '</td>';
	echo '</table>';

?>


<?php
	//PARA SEGUIMIENTOS
	echo '<table cellpadding="4px" width="500px">';
	echo '<tr>';
	echo '<td width="50%">';
	echo "Seleccione el reporte para Seguimiento: ";
	echo '</td>';
	echo '<td width="25%">';
	$query1 = oci_parse($conOracle, "SELECT ID_EVENTO FROM CENACOM.EVENTO WHERE NOMBRE_EVENTO='INICIAL' OR NOMBRE_EVENTO='SEGUIMIENTO' AND ESTADO_EVENTO=1");
	oci_execute($query1);
	print '<select id="SeguimientoCombo" name="SeguimientoCombo" title="Seleccione el reporte para Seguimiento" onchange="escribeLinkAlReporte(value,\'SEGUIMIENTO\');">';
	print '<option value="0">Seleccione</option>';	
	while ($row = oci_fetch_array($query1, OCI_NUM+OCI_RETURN_NULLS)) {
		foreach ($row as $item)
			$query2 = oci_parse($conOracle, "SELECT CR FROM CENACOM.REPORTES WHERE ID_EVENTO=".htmlentities($item));
			oci_execute($query2);
			while ($row2 = oci_fetch_array($query2, OCI_NUM+OCI_RETURN_NULLS)) {
				foreach ($row2 as $item2)
					print '<option value="'.htmlentities($item2).'">'.htmlentities($item2).'</option>';
			}
	}
	print '</select>';
	echo '</td>';
	echo '<td width="25%">';
	print '<div id="SEGUIMIENTO" name="SEGUIMIENTO"></div>';
	echo '</td>';
	echo '</tr>';
	
	//PARA Finales
	echo '<tr>';
	echo '<td>';
	echo "Seleccione el reporte para Final: ";
	echo '</td>';
	echo '<td>';
	$query1 = oci_parse($conOracle, "SELECT ID_EVENTO FROM CENACOM.EVENTO WHERE NOMBRE_EVENTO='INICIAL' OR NOMBRE_EVENTO='SEGUIMIENTO' AND ESTADO_EVENTO=1");
	oci_execute($query1);
	print '<select id="FinalCombo" name="FinalCombo" title="Seleccione el reporte para Final" onchange="escribeLinkAlReporte(value,\'FINAL\');">';
	print '<option value="0">Seleccione</option>';	
	while ($row = oci_fetch_array($query1, OCI_NUM+OCI_RETURN_NULLS)) {
		foreach ($row as $item)
			$query2 = oci_parse($conOracle, "SELECT CR FROM CENACOM.REPORTES WHERE ID_EVENTO=".htmlentities($item));
			oci_execute($query2);
			while ($row2 = oci_fetch_array($query2, OCI_NUM+OCI_RETURN_NULLS)) {
				foreach ($row2 as $item2)
					print '<option value="'.htmlentities($item2).'">'.htmlentities($item2).'</option>';
			}
	}
	print '</select>';
	echo '</td>';
	echo '<td>';
	print '<div id="FINAL" name="FINAL"></div>';
	echo '</td>';
	echo '</tr>';
	
	
	//PARA Alcances
	echo '<tr>';
	echo '<td>';
	echo "Seleccione el reporte para Alcance: ";
	echo '</td>';
	echo '<td>';
	$query1 = oci_parse($conOracle, "SELECT ID_EVENTO FROM CENACOM.EVENTO WHERE NOMBRE_EVENTO='UNICO' AND ESTADO_EVENTO=1");
	oci_execute($query1);
	print '<select id="FinalCombo" name="FinalCombo" title="Seleccione el reporte para Final" onchange="escribeLinkAlReporte(value,\'ALCANCE\');">';
	print '<option value="0">Seleccione</option>';	
	while ($row = oci_fetch_array($query1, OCI_NUM+OCI_RETURN_NULLS)) {
		foreach ($row as $item)
			$query2 = oci_parse($conOracle, "SELECT CR FROM CENACOM.REPORTES WHERE ID_EVENTO=".htmlentities($item));
			oci_execute($query2);
			while ($row2 = oci_fetch_array($query2, OCI_NUM+OCI_RETURN_NULLS)) {
				foreach ($row2 as $item2)
					print '<option value="'.htmlentities($item2).'">'.htmlentities($item2).'</option>';
			}
	}
	print '</select>';
	echo '</td>';
	echo '<td>';
	print '<div id="ALCANCE" name="ALCANCE"></div>';
	echo '</td>';
	echo '</tr>';

echo '</table>';

cerrarConexionORACLE($conOracle);
?>

</body>
</html>
