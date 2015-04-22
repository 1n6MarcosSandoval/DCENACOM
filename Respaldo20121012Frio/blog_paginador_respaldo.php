<?php
require('./vars.php');
require('./functions.php');
//include 'functions.php';
//include 'vars.php';
$RegistrosAMostrar=2;

//estos valores los recibo por GET
if(isset($_GET['pag'])){
	$RegistrosAEmpezar=($_GET['pag']-1)*$RegistrosAMostrar;
	$PagAct=$_GET['pag'];
//caso contrario los iniciamos
}else{
	$RegistrosAEmpezar=0;
	$PagAct=1;	
}
echo $RegistrosAEmpezar;

$conOracle=conexionORACLE($HOST, $PORT, $SID, $userName, $passkey);

//$stid = oci_parse($conOracle, 'SELECT * FROM CENACOM.REPORTES WHERE ROWNUM BETWEEN '. $RegistrosAEmpezar.' AND '.$RegistrosAMostrar. ' ORDER BY CR DESC');

$queryS="SELECT CR FROM (SELECT CR, ROWNUM r FROM (SELECT CR FROM CENACOM.REPORTES ORDER BY CR DESC) WHERE ROWNUM <= ".$RegistrosAMostrar." ) WHERE r > ".$RegistrosAEmpezar;
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




echo "<table border='1px' cellpadding='4px' width='590px'>";
	echo "<tr>";
	echo '<td bgcolor="#D8D8D8" width="10%"> <center><h3>CR</h3></center></td>';
	echo '<td bgcolor="#D8D8D8" width="12%"><center><h3>Fecha Inicio</h3></center></td>';
	//echo '<td bgcolor="#D8D8D8" width="26%"><center><h3>Fecha</h3></center></td>';
	//echo '<td bgcolor="#D8D8D8" width="20%"><center><h3>Autor</h3></center></td>';
	//echo '<td bgcolor="#D8D8D8" width="12%"><center><h3>Editar</h3></center></td>';
	echo '</tr>';
while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
	echo "<tr>";
	echo "<td>".$row[CR]."</td>";
	echo "<td>".$row[FECHA_INICIO_FENOMENO]."</td>";
	//echo "<td>".$row['blogFecha']."</td>";
	//echo "<td>".$row['blogAutor']."</td>";
	//echo '<td> <a href="blog_editar.php?id='.$row['id'].'"> Editar</a></td>'; 
	echo "</tr>";
}
echo "</table>";
//******--------determinar las p?ginas---------******//
$query = oci_parse($conOracle, 'SELECT COUNT(*) AS VALOR FROM CENACOM.REPORTES');
oci_execute($query);
$row = oci_fetch_array($query, OCI_ASSOC);
$NroRegistros =$row["VALOR"]+1;
//print $NroRegistros ;

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
echo "<a onclick=\"Pagina('1','blog_paginador.php')\">Primero </a> ";
if($PagAct>1) echo "<a onclick=\"Pagina('$PagAnt','blog_paginador.php')\">Anterior </a> ";
echo "<strong>.: Pagina ".$PagAct."/".$PagUlt." :.</strong>";
if($PagAct<$PagUlt)  echo " <a onclick=\"Pagina('$PagSig','blog_paginador.php')\">Siguiente </a> ";
echo "<a onclick=\"Pagina('$PagUlt','blog_paginador.php')\"> Ultimo</a>";
echo '</div> </center>';
?>
