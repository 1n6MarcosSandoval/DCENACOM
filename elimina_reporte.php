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

<?php
$CR_borrar=51;
/* Selecciona el evento del reporte*/
$queryS1="SELECT ID_EVENTO from CENACOM.reportes where cr=".$CR_borrar;
$stid1 = oci_parse($conOracle, $queryS1);

if (!$stid1) {
    $e = oci_error($conOracle);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

// Perform the logic of the query
$r1 = oci_execute($stid1);
if (!$r1) {
    $e = oci_error($stid1);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$row1 = oci_fetch_array($stid1, OCI_ASSOC+OCI_RETURN_NULLS);
print "Evento: ".$row1["ID_EVENTO"]."<br/><br/>";
/* FIN Selecciona el evento del reporte*/

/*Selecciona los cr del mismo evento*/
$queryS2="SELECT CR from CENACOM.reportes where id_evento=".$row1["ID_EVENTO"]." ORDER BY CR DESC";
$stid2 = oci_parse($conOracle, $queryS2);

if (!$stid2) {
    $e = oci_error($conOracle);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

// Perform the logic of the query
$r2 = oci_execute($stid2);
if (!$r2) {
    $e = oci_error($stid2);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$row2 = oci_fetch_array($stid2, OCI_ASSOC+OCI_RETURN_NULLS);
print $row2["CR"]."<br/>";
/* FIN Selecciona los cr del mismo evento*/


/* Revisa si el CR a borrar es el mismo que el ultimo del evento,
 * si no es asi, no lo podra borrar
 */
	if($row2["CR"]==$CR_borrar){
		print "Es igual, si se puede borrar<BR/>";

		/*Obtiene el tipo de reporte*/		
		$queryTipoReporte="SELECT ID_TIPO_REPORTE from CENACOM.reportes where cr=".$CR_borrar;
		$stidTipoReporte = oci_parse($conOracle, $queryTipoReporte);
		$rTipoReporte = oci_execute($stidTipoReporte);
		$rowTipoReporte = oci_fetch_array($stidTipoReporte, OCI_ASSOC+OCI_RETURN_NULLS);
		//print "TIPO REPORTE: ".$rowTipoReporte["ID_TIPO_REPORTE"]."<br/><br/>";
		/*FIN Obtiene el tipo de reporte*/

		/*Obtiene el CR relacionado del reporte*/
		$queryRe="SELECT CR_RELACIONADO from CENACOM.reportes where cr=".$row2["CR"];
		$stidRe = oci_parse($conOracle, $queryRe);
		$rRe = oci_execute($stidRe);
		$rowRe = oci_fetch_array($stidRe, OCI_ASSOC+OCI_RETURN_NULLS);
		print "CR relacionado: ".$rowRe["CR_RELACIONADO"]."<br/>";
		/*FIN Obtiene el CR relacionado del reporte*/		
		
		/* Borra el reporte */
		$queryDelete="DELETE from CENACOM.reportes where CR=".$CR_borrar;
		$stidDelete = oci_parse($conOracle, $queryDelete);
		$rDelete = oci_execute($stidDelete);
		//print "Evento: ".$rowDelete["ID_EVENTO"]."<br/><br/>";
		/* FIN Borra el reporte */
		
		
		/* Checa si tambien borra el evento o actualiza el estado del evento */
		if($rowTipoReporte["ID_TIPO_REPORTE"]==0 || $rowTipoReporte["ID_TIPO_REPORTE"]==2){
			/* Borra el evento */
			$queryDelete="DELETE from CENACOM.evento where ID_EVENTO=".$row1["ID_EVENTO"];
			$stidDelete = oci_parse($conOracle, $queryDelete);
			$rDelete = oci_execute($stidDelete);
			/* FIN Borra el evento */
		}else{
			/* Actualiza el evento */
			//Actualiza el Estado
			$queryActualiza="UPDATE CENACOM.evento set estado_evento=1 where id_evento=".$row1["ID_EVENTO"];
			$stidActualiza = oci_parse($conOracle, $queryActualiza);
			$rActualiza = oci_execute($stidActualiza);
			
			//Obtiene el nombre del tipo de reporte relacionado para despues asignarlo en el reporte
			if($rowRe["CR_RELACIONADO"]!=0){
				$queryTr="SELECT ID_TIPO_REPORTE from CENACOM.reportes where cr=".$rowRe["CR_RELACIONADO"];
				$stidTr = oci_parse($conOracle, $queryTr);
				$rTr = oci_execute($stidTr);
				$rowTr = oci_fetch_array($stidTr, OCI_ASSOC+OCI_RETURN_NULLS);
				$nombre_tipo_cr=recuperaCampo($conOracle, 'tipo_reporte', 'tipo_reporte', $rowTr["ID_TIPO_REPORTE"], 'id_tipo_reporte');
			}

			//Actualiza el Nombre
			$queryActualiza="UPDATE CENACOM.evento set nombre_evento='".$nombre_tipo_cr."' where id_evento=".$row1["ID_EVENTO"];
			$stidActualiza = oci_parse($conOracle, $queryActualiza);
			$rActualiza = oci_execute($stidActualiza);
			/* FIN Actualiza el evento */
		}
		/* FIN Checa si tambien borra el evento o actualiza el estado del evento */

	}else{
		print "No es igual";
	}
/* FIN Revisa si el CR a borrar es el mismo que el ultimo del evento*/ 
 
 

cerrarConexionORACLE($conOracle);
?>

		<div class="footer">
			<p>Sistema Nacional de Protecci&oacute;n Civil</p>
		</div>
	</div>
</body>
</html>