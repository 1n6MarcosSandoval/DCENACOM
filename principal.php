<?php
session_start();

require_once('FirePHPCore/FirePHP.class.php');
ob_start();
$firephp = FirePHP::getInstance(true);

if(@$_SESSION['login'] != "si")
{
header("Location:index.php");
exit();
}


include 'functions.php';
include 'vars.php';



$conOracle=conexionORACLE($HOST, $PORT, $SID, $userName, $passkey);
?>


<?php
include 'arriba.php';
?>

		<!--IZQUIERDA -->
		<div class="left">
			<div class="left_articles">
				<h2>Sistema de captura</h2>
			</div>
						
			<div class="lt"></div>
			<div class="lbox">
				<h2>Seleccione tipo de reporte: </h2>


<?php
	//PARA Unicos
	echo '<table cellpadding="4px" width="490px">';
	echo '<tr>';
	echo '<td width="50%">';
	echo 'Reporte de tipo &Uacute;nico:';
	echo '</td>';
	echo '<td>';
	echo '<a href="formUnico.php">Levantar reporte</a>';
	echo '</td>';
	echo '<td></td>';
	//FIN PARA Unicos
	
	//PARA Alcances
	$i=0;
	echo '<tr>';
	echo '<td>';
	echo "Seleccione el reporte para Alcance: ";
	echo '</td>';
	echo '<td>';
	$query1 = oci_parse($conOracle, "SELECT ID_EVENTO FROM CENACOM.EVENTO WHERE NOMBRE_EVENTO='UNICO' AND ESTADO_EVENTO=1 ORDER BY ID_EVENTO DESC");
	oci_execute($query1);
	print '<select id="AlcanceCombo" name="AlcanceCombo" title="Seleccione el reporte para Alcance" onchange="escribeLinkAlReporte(value,\'ALCANCE\');">';
	print '<option value="0">Seleccione</option>';	
	while ($row = oci_fetch_array($query1, OCI_NUM+OCI_RETURN_NULLS)) {
		foreach ($row as $item)
			$query2 = oci_parse($conOracle, "SELECT CR FROM CENACOM.REPORTES WHERE ID_EVENTO=".htmlentities($item)." ORDER BY CR DESC");
			oci_execute($query2);
			while ($row2 = oci_fetch_array($query2, OCI_NUM+OCI_RETURN_NULLS)) {
				foreach ($row2 as $item2)
					$cadenaAlcance[$i]=htmlentities($item2);
					$i++;
			}
	}
	rsort($cadenaAlcance);
	for($i=0 ; $i < (sizeof($cadenaAlcance)) ; $i++){
		print '<option value="'.$cadenaAlcance[$i].'">'.$cadenaAlcance[$i].'</option>';	
	}
	print '</select>';
	echo '</td>';
	echo '<td>';
	print '<div id="ALCANCE" name="ALCANCE"></div>';
	echo '</td>';
	echo '</tr>';
	//FIN PARA Alcances
	
	//PARA otro Alcance
	$i=0;
	echo '<tr>';
	echo '<td>';
	echo "Seleccione el reporte para otro Alcance: ";
	echo '</td>';
	echo '<td>';
	$query1 = oci_parse($conOracle, "SELECT ID_EVENTO FROM CENACOM.EVENTO WHERE NOMBRE_EVENTO='ALCANCE' AND ESTADO_EVENTO=0 ORDER BY ID_EVENTO DESC");
	oci_execute($query1);
	print '<select id="Alcance2Combo" name="Alcance2Combo" title="Seleccione el reporte para 2o Alcance" onchange="escribeLinkAlReporte(value,\'ALCANCE2\');">';
	print '<option value="0">Seleccione</option>';	
	while ($row = oci_fetch_array($query1, OCI_NUM+OCI_RETURN_NULLS)) {
		foreach ($row as $item)
			$firephp->info(htmlentities($item));
			$query2 = oci_parse($conOracle, "SELECT CR FROM CENACOM.REPORTES WHERE ID_EVENTO=".htmlentities($item)." AND ID_TIPO_REPORTE=0 ORDER BY CR DESC");
			oci_execute($query2);
			while ($row2 = oci_fetch_array($query2, OCI_NUM+OCI_RETURN_NULLS)) {
				foreach ($row2 as $item2)
					$cadenaAlcance2[$i]=htmlentities($item2);
					$i++;

			}
	}
	rsort($cadenaAlcance2);
	for($i=0 ; $i < (sizeof($cadenaAlcance2)) ; $i++){
		print '<option value="'.$cadenaAlcance2[$i].'">'.$cadenaAlcance2[$i].'</option>';	
	}
	print '</select>';
	echo '</td>';
	echo '<td>';
	print '<div id="ALCANCE2" name="ALCANCE2"></div>';
	echo '</td>';
	echo '</tr>';
	//FIN PARA Segundo Alcance
	
	echo '<tr>';
	echo '<td>&nbsp;</td>';
	echo '<td>&nbsp;</td>';
	echo '<td>&nbsp;</td>';
	echo '</tr>';
		
	//PARA Iniciales
	echo '</tr>';
	echo '<td width="50%">';
	echo 'Reporte de tipo Inicial:';
	echo '</td>';
	echo '<td>';
	echo '<a href="formInicial.php">Levantar reporte</a>';
	echo '</td>';
	echo '<td></td>';
	echo '</tr>';
	//FIN PARA Iniciales
	
?>

<?php
	//PARA SEGUIMIENTOS
	$i=0;	
	echo '<tr>';
	echo '<td width="50%">';
	echo "Seleccione el reporte para Seguimiento: ";
	echo '</td>';
	echo '<td width="25%">';
	$query1 = oci_parse($conOracle, "SELECT ID_EVENTO FROM CENACOM.EVENTO WHERE NOMBRE_EVENTO='INICIAL' OR NOMBRE_EVENTO='SEGUIMIENTO' AND ESTADO_EVENTO=1 ORDER BY ID_EVENTO ASC");
	oci_execute($query1);
	print '<select id="SeguimientoCombo" name="SeguimientoCombo" title="Seleccione el reporte para Seguimiento" onchange="escribeLinkAlReporte(value,\'SEGUIMIENTO\');">';
	print '<option value="0">Seleccione</option>';	
	while ($row = oci_fetch_array($query1, OCI_NUM+OCI_RETURN_NULLS)) {
		foreach ($row as $item)
			$query2 = oci_parse($conOracle, "SELECT MAX(CR) FROM CENACOM.REPORTES WHERE ID_EVENTO=".htmlentities($item)." ORDER BY CR");
			oci_execute($query2);
			while ($row2 = oci_fetch_array($query2, OCI_NUM+OCI_RETURN_NULLS)) {
				foreach ($row2 as $item2){
					$cadena[$i]=htmlentities($item2);
					//print '<option value="'.$cadena[i].'">'.$cadena[i].'</option>';
					$i++;
				}
										
			}			
	}
	rsort($cadena);
	for($i=0 ; $i < (sizeof($cadena)) ; $i++){
		print '<option value="'.$cadena[$i].'">'.$cadena[$i].'</option>';	
	}
	print '</select>';
	echo '</td>';
	echo '<td width="25%">';
	print '<div id="SEGUIMIENTO" name="SEGUIMIENTO"></div>';
	echo '</td>';
	echo '</tr>';
	//FIN PARA SEGUIMIENTOS
	
	//PARA Finales
	echo '<tr>';
	echo '<td>';
	echo "Seleccione el reporte para Final: ";
	echo '</td>';
	echo '<td>';
	//$query1 = oci_parse($conOracle, "SELECT ID_EVENTO FROM CENACOM.EVENTO WHERE NOMBRE_EVENTO='INICIAL' OR NOMBRE_EVENTO='SEGUIMIENTO' AND ESTADO_EVENTO=1 ORDER BY ID_EVENTO ASC");
	//oci_execute($query1);
	print '<select id="FinalCombo" name="FinalCombo" title="Seleccione el reporte para Final" onchange="escribeLinkAlReporte(value,\'FINAL\');">';
	print '<option value="0">Seleccione</option>';	
	/*
	while ($row = oci_fetch_array($query1, OCI_NUM+OCI_RETURN_NULLS)) {
		foreach ($row as $item)
			$query2 = oci_parse($conOracle, "SELECT MAX(CR) FROM CENACOM.REPORTES WHERE ID_EVENTO=".htmlentities($item)." ORDER BY CR ASC");
			oci_execute($query2);
			while ($row2 = oci_fetch_array($query2, OCI_NUM+OCI_RETURN_NULLS)) {
				foreach ($row2 as $item2)
					print '<option value="'.htmlentities($item2).'">'.htmlentities($item2).'</option>';
			}
	}*/
	for($i=0 ; $i < (sizeof($cadena)) ; $i++){
		print '<option value="'.$cadena[$i].'">'.$cadena[$i].'</option>';	
	}
	print '</select>';
	echo '</td>';
	echo '<td>';
	print '<div id="FINAL" name="FINAL"></div>';
	echo '</td>';
	echo '</tr>';
	//FIN PARA Finales	


echo '</table>';



cerrarConexionORACLE($conOracle);
?>				
			</div>
			
		<div id="postFormALCANCE"></div>
		<div id="postFormALCANCE2"></div>
		<div id="postFormSEGUIMIENTO"></div>
		<div id="postFormFINAL"></div>
		</div>
		<!-- FIN DE IZQUIERDA -->	
		
	<?php
		if($_SESSION['nombre']=="cenacomAdmin")
			include 'derechaAdmin.php';
		else
			include 'derecha0.php';
	?>
		<div class="footer">
			<p>Sistema Nacional de Protecci&oacute;n Civil</p>
		</div>
</body>
</html>