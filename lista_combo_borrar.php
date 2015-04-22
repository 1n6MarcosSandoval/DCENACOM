<?php

session_start();
if(@$_SESSION['login'] != "si")
{
header("Location:index.php");
exit();
}
  
require_once('FirePHPCore/FirePHP.class.php');
ob_start();
$firephp = FirePHP::getInstance(true);
?>

<script language="JavaScript">

function valida_borrado(){
		var eliminar = confirm("Confirme que desea eliminar este usuario")
		//console.log(eliminar)
		if(eliminar){
			document.borraCR.submit();
		}else{
			return 0;
		}
}
</script>

	<div id="contenido">


<?php
$RegistrosAMostrar=20;

//estos valores los recibo por GET
if(isset($_GET['pag'])){
	$RegistrosAEmpezar=($_GET['pag']-1)*$RegistrosAMostrar;
	$PagAct=$_GET['pag'];
	$RegistrosAMostrarQuery=$RegistrosAMostrar+$RegistrosAEmpezar;
//caso contrario los iniciamos
}else{
	$RegistrosAEmpezar=0;
	$PagAct=1;
	$RegistrosAMostrarQuery=$RegistrosAMostrar;	
}

$conOracle=conexionORACLE($HOST, $PORT, $SID, $userName, $passkey);
//$queryS="SELECT CR, TIPOFENOMENO_ID, EFECTO_ADVERSO, FECHA, ID_USUARIO FROM (SELECT CR, ESTADO, MUNICIPIO, TIPOFENOMENO_ID, EFECTO_ADVERSO, FECHA, ID_USUARIO, ROWNUM r FROM (SELECT CR, ESTADO, MUNICIPIO, TIPOFENOMENO_ID, EFECTO_ADVERSO, TO_CHAR(FECHA_AVISO, 'YYYY/MM/DD hh24:mm:ss') AS FECHA, ID_USUARIO FROM CENACOM.REPORTES ORDER BY CR DESC) WHERE ROWNUM <= ".$RegistrosAMostrarQuery." ) WHERE r > ".$RegistrosAEmpezar;
$queryS="SELECT CR, TIPOFENOMENO_ID, EFECTO_ADVERSO, FECHA, ID_USUARIO FROM (SELECT CR, ESTADO, MUNICIPIO, TIPOFENOMENO_ID, EFECTO_ADVERSO, FECHA, ID_USUARIO, ROWNUM r FROM (SELECT CR, ESTADO, MUNICIPIO, TIPOFENOMENO_ID, EFECTO_ADVERSO, TO_CHAR(FECHA_AVISO, 'YYYY/MM/DD hh24:mm:ss') AS FECHA, ID_USUARIO FROM CENACOM.REPORTES ORDER BY CR DESC) WHERE ROWNUM <= ".$RegistrosAMostrarQuery." ) WHERE r > ".$RegistrosAEmpezar;
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

print '<form id="borraCR" name="borraCR" action="lista_borrar.php" method="post">';

print '<select id="cr_adios"  name="cr_adios">';
while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
		
	/*OBTIENE EL TIPO DE REPORTE*/	
	$tipoReporte=obtenerValorQuery($conOracle,'CENACOM', 'REPORTES', 'CR', $row["CR"], 'ID_TIPO_REPORTE');
	switch ($tipoReporte) {
		case 0:
			$tipoReporteL="&Uacute;nico";
			break;
		case 1:
			$tipoReporteL="Alcance";
			break;
		case 2:
			$tipoReporteL="Inicial";
			break;
		case 3:
			$tipoReporteL="Seguimiento";
			break;
		case 4:
			$tipoReporteL="Final";
			break;
		default:
			$tipoReporteL="NO";
			break;
	}
	/*FIN OBTIENE EL TIPO DE REPORTE*/
	print '<option value="'.$row["CR"].'">'.$row["CR"].' - '.$tipoReporteL.'</option>';
}
print '</select>';
print '<input type="button"  value="Eliminar" onclick="valida_borrado()" />';
print '</form>';

//******--------determinar las paginas---------******//
$query = oci_parse($conOracle, 'SELECT COUNT(*) AS VALOR FROM CENACOM.REPORTES');
oci_execute($query);
$row = oci_fetch_array($query, OCI_ASSOC);
$NroRegistros =$row["VALOR"];


$PagAnt=$PagAct-1;
$PagSig=$PagAct+1;
$PagUlt=$NroRegistros/$RegistrosAMostrar;

//verificamos residuo para ver si llevar decimales
$Res=$NroRegistros%$RegistrosAMostrar;
// si hay residuo usamos funcion floor para que me
// devuelva la parte entera, SIN REDONDEAR, y le sumamos
// una unidad para obtener la ultima pagina
if($Res>0) $PagUlt=floor($PagUlt)+1;

//desplazamiento
/*echo '<br/>';
echo '<center><div style="margin:auto;width:700px;">';
echo "<a onclick=\"Pagina('1','lista_combo_borrar.php')\">Primero </a> ";
if($PagAct>1) echo "<a onclick=\"Pagina('$PagAnt','lista_combo_borrar.php')\">Anterior </a> ";
echo "<strong>.: Pagina ".$PagAct."/".$PagUlt." :.</strong>";
if($PagAct<$PagUlt)  echo " <a onclick=\"Pagina('$PagSig','lista_combo_borrar.php')\">Siguiente </a> ";
echo "<a onclick=\"Pagina('$PagUlt','lista_combo_borrar.php')\"> Ultimo</a>";
echo '</div> </center>';*/
?>

	</div>

<?php
	cerrarConexionORACLE($conOracle);
?>




