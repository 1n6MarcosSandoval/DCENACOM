<?php
include 'functions.php';
include 'vars.php';
session_start();
if(@$_SESSION['login'] != "si")
{
header("Location:index.php");
exit();
}
?>
	<div id="contenido">

<?php
$RegistrosAMostrar=10;

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
$queryS="SELECT CR, ESTADO, MUNICIPIO, TIPOFENOMENO_ID, EFECTO_ADVERSO, FECHA, ID_USUARIO FROM (SELECT CR, ESTADO, MUNICIPIO, TIPOFENOMENO_ID, EFECTO_ADVERSO, FECHA, ID_USUARIO, ROWNUM r FROM (SELECT CR, ESTADO, MUNICIPIO, TIPOFENOMENO_ID, EFECTO_ADVERSO, TO_CHAR(FECHA_AVISO, 'YYYY/MM/DD hh24:mm:ss') AS FECHA, ID_USUARIO FROM CENACOM.REPORTES ORDER BY CR DESC) WHERE ROWNUM <= ".$RegistrosAMostrarQuery." ) WHERE r > ".$RegistrosAEmpezar;
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




echo "<table border='1px' cellpadding='4px' width='760px'>";
	echo "<tr>";
	echo '<td bgcolor="#D8D8D8" width="5%"> <center>CR</center></td>';
	echo '<td bgcolor="#D8D8D8" width="30%"> <center>Lugar</center></td>';
	echo '<td bgcolor="#D8D8D8" width="20%"> <center>Fen&oacute;meno</center></td>';
	echo '<td bgcolor="#D8D8D8" width="15%"><center>Fecha en que se reporta</center></td>';
	//echo '<td bgcolor="#D8D8D8" width="27%"> <center>Efecto adverso</center></td>';	
	echo '<td bgcolor="#D8D8D8" width="30%"> <center>Autor(es)</center></td>';
	echo '</tr>';
while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
	echo "<tr>";
	echo "<td> <a href=\"visual_download.php?cr=".$row["CR"]."\">".$row["CR"]."</td>";

	if($row["ESTADO"]!=NULL){
		$myArray = explode(',', $row["ESTADO"]);
		$datoE = '';
		$i=0;
		for ($i = 0; $i < (sizeof($myArray)-1); $i++)
			$datoE = $datoE . recuperaCampo($conOracle, 'ANRO.LOCALIDADES', 'NOM_ENT', $myArray[$i], 'ENTIDAD'). ', '; 
		$datoE = $datoE . recuperaCampo($conOracle, 'ANRO.LOCALIDADES', 'NOM_ENT', $myArray[$i], 'ENTIDAD'); 
	}		
	if($row["MUNICIPIO"]!=NULL){
		$myArray = explode(',', $row["MUNICIPIO"]);
		$datoM = '';
		$i=0;
		for ($i = 0; $i < (sizeof($myArray)-1); $i++)
			$datoM = $datoM . recuperaCampo($conOracle, 'ANRO.LOCALIDADES', 'NOM_MUN', $myArray[$i], 'MUN'). ', '; 
		$datoM = $datoM . recuperaCampo($conOracle, 'ANRO.LOCALIDADES', 'NOM_MUN', $myArray[$i], 'MUN'); 
	}
	echo "<td> ".$datoM.", ".$datoE."</td>";

	if($row["TIPOFENOMENO_ID"]!=NULL){
		$myArray = explode(',', $row["TIPOFENOMENO_ID"]);
		$datoF = '';
		$i=0;
		for ($i = 0; $i < (sizeof($myArray)-1); $i++)
			$datoF = $datoF . recuperaCampo($conOracle, 'CENACOM.TIPO_FENOMENO', 'NOMBRE', $myArray[$i], 'ID_FENOMENO'). ', '; 
		$datoF = $datoF . recuperaCampo($conOracle, 'CENACOM.TIPO_FENOMENO', 'NOMBRE', $myArray[$i], 'ID_FENOMENO'); 
	}

	echo "<td>".$datoF."</td>";
	echo "<td>".$row["FECHA"]."</td>";
	
	if($row["ID_USUARIO"]!=NULL){
		$myArray = explode(',', $row["ID_USUARIO"]);
		$datoA = '';
		$i=0;
		for ($i = 0; $i < (sizeof($myArray)-1); $i++)
			$datoA = $datoA . recuperaCampoS($conOracle, 'CENACOM.USUARIOS_CENACOM', 'NOMBRE', 'APELLIDO', $myArray[$i], 'ID_USUARIO'). ', '; 
		$datoA = $datoA . recuperaCampoS($conOracle, 'CENACOM.USUARIOS_CENACOM', 'NOMBRE', 'APELLIDO', $myArray[$i], 'ID_USUARIO'); 
	echo "<td>".$datoA."</td>";
	}
	
	echo "</tr>";
}
echo "</table>";
//******--------determinar las p?ginas---------******//
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
echo '<br/>';
echo '<center><div style="margin:auto;width:700px;">';
echo "<a onclick=\"Pagina('1','lista_paginador.php')\">Primero </a> ";
if($PagAct>1) echo "<a onclick=\"Pagina('$PagAnt','lista_paginador.php')\">Anterior </a> ";
echo "<strong>.: Pagina ".$PagAct."/".$PagUlt." :.</strong>";
if($PagAct<$PagUlt)  echo " <a onclick=\"Pagina('$PagSig','lista_paginador.php')\">Siguiente </a> ";
echo "<a onclick=\"Pagina('$PagUlt','lista_paginador.php')\"> Ultimo</a>";
echo '</div> </center>';
?>


	</div>

<?php
	cerrarConexionORACLE($conOracle);
?>

