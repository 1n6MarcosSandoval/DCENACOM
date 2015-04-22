<?php
include 'functions.php';
include 'vars.php';
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





	<div id="contenido">

<div id="listado">
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

	if(@$_GET['orden']){
		$ordenQuery=sanitize_sql_string(@$_GET['orden']);
		switch ($ordenQuery) {
			case 'nombre':
				$queryS="SELECT ID_USUARIO, NOMBRE, APELLIDO, CORREO, TURNO FROM (SELECT ID_USUARIO, NOMBRE, APELLIDO, CORREO, TURNO, ROWNUM r FROM (SELECT ID_USUARIO, NOMBRE, APELLIDO, CORREO, TURNO FROM CENACOM.usuarios_cenacom ORDER BY NOMBRE ASC) WHERE ROWNUM <= ".$RegistrosAMostrarQuery." ) WHERE r > ".$RegistrosAEmpezar;
				$valorAjax_Orden="nombre";
				break;
			case 'turno':
				$queryS="SELECT ID_USUARIO, NOMBRE, APELLIDO, CORREO, TURNO FROM (SELECT ID_USUARIO, NOMBRE, APELLIDO, CORREO, TURNO, ROWNUM r FROM (SELECT ID_USUARIO, NOMBRE, APELLIDO, CORREO, TURNO FROM CENACOM.usuarios_cenacom ORDER BY TURNO DESC) WHERE ROWNUM <= ".$RegistrosAMostrarQuery." ) WHERE r > ".$RegistrosAEmpezar;
				$valorAjax_Orden="turno";
				break;
			default:
				
				break;
		}

	}else{
		$queryS="SELECT ID_USUARIO, NOMBRE, APELLIDO, CORREO, TURNO FROM (SELECT ID_USUARIO, NOMBRE, APELLIDO, CORREO, TURNO, ROWNUM r FROM (SELECT ID_USUARIO, NOMBRE, APELLIDO, CORREO, TURNO FROM CENACOM.usuarios_cenacom ORDER BY TURNO DESC) WHERE ROWNUM <= ".$RegistrosAMostrarQuery." ) WHERE r > ".$RegistrosAEmpezar;
		$valorAjax_Orden="nombre";
	}

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

echo "<table border='1px' cellpadding='4px' width='760px'>";
	echo "<tr>";
	echo '<td bgcolor="#D8D8D8" width="40%"> <center><a href="http://www.incendiosmovil.gob.mx/dcenacom/prueba_usuarios.php?orden=nombre">Nombre</a></center></td>';
	//echo '<td bgcolor="#D8D8D8" width="25%"> <center>Apellido paterno</center></td>';
	//echo '<td bgcolor="#D8D8D8" width="40%"> <center>E-mail</center></td>';
	echo '<td bgcolor="#D8D8D8" width="20%"><center><a href="http://www.incendiosmovil.gob.mx/dcenacom/prueba_usuarios.php?orden=turno">Turno</a></center></td>';
	echo '</tr>';
while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
	echo "<tr>";
	//echo "<td>".$row["NOMBRE"]." ".$row["APELLIDO"]."</td>";
	echo "<td> <a id='Edita' title='Editar usuario' href=\"#editaUsuario\" onclick='muestra_capa(\"editaUsuario\");editaUsuario(".$row["ID_USUARIO"].");return false;'>".$row["NOMBRE"]." ".$row["APELLIDO"]."</a></td>";
	$turno=obtenerValorQuery($conOracle,'CENACOM', 'TURNOS', 'ID_TURNO', $row["TURNO"], 'NOMBRE');
	echo "<td>".$turno."</td>";
	echo "</tr>";
}
echo "</table>";
//******--------determinar las paginas---------******//
$query = oci_parse($conOracle, 'SELECT COUNT(*) AS VALOR FROM CENACOM.USUARIOS_CENACOM');
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
echo "<a onclick=\"PaginaConParametro('1','lista_pag_usuarios.php?orden=".$valorAjax_Orden."')\">Primero </a> ";
if($PagAct>1) echo "<a onclick=\"PaginaConParametro('$PagAnt','lista_pag_usuarios.php?orden=".$valorAjax_Orden."')\">Anterior </a> ";
echo "<strong>.: Pagina ".$PagAct."/".$PagUlt." :.</strong>";
if($PagAct<$PagUlt)  echo " <a onclick=\"PaginaConParametro('$PagSig','lista_pag_usuarios.php?orden=".$valorAjax_Orden."')\">Siguiente </a> ";
echo "<a onclick=\"PaginaConParametro('$PagUlt','lista_pag_usuarios.php?orden=".$valorAjax_Orden."')\"> Ultimo</a>";
echo '</div> </center>';
?>

</div>
<br/>
<br/>

	</div>

<?php
	cerrarConexionORACLE($conOracle);
?>

