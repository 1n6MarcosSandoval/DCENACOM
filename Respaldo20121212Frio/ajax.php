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
?>


<?php
$_valor = $_GET['valor'];
$_combo = $_GET['comboC'];

if($_combo=='municipio'){
$query = oci_parse($conOracle, 'SELECT MUN, NOM_MUN from ANRO.LOCALIDADES WHERE ENTIDAD='.$_valor.' GROUP BY NOM_MUN, MUN ORDER BY MUN');
	oci_execute($query);
	while ($row = oci_fetch_array($query, MYSQL_ASSOC)) {
		$_arreglo[] = array('id' => $_valor.$row['MUN'], 'data' => $row['NOM_MUN']);
	}
	echo json_encode($_arreglo);
}elseif($_combo=='localidad'){
$query = oci_parse($conOracle, 'SELECT LOC, NOM_LOC from ANRO.LOCALIDADES WHERE ENTIDAD='.$_valor[0].' AND MUN='.$_valor[1].' ORDER BY NOM_LOC');
	oci_execute($query);
	while ($row = oci_fetch_array($query, MYSQL_ASSOC)) {
		$_arreglo[] = array('id' => $row['LOC'], 'data' => $row['NOM_LOC']);

	}
	echo json_encode($_arreglo);
	
}elseif($_combo=='fenomeno'){
	$query = oci_parse($conOracle, "SELECT ID_FENOMENO, NOMBRE from CENACOM.TIPO_FENOMENO WHERE CLASIFICACIONFENOMENO_ID='".$_valor."'");
	oci_execute($query);
	while ($row = oci_fetch_array($query, MYSQL_ASSOC)) {
		$_arreglo[] = array('id' => $row['ID_FENOMENO'], 'data' => $row['NOMBRE']);
	}
	echo json_encode($_arreglo);	
}elseif($_combo=='autores'){
	$query = oci_parse($conOracle, "SELECT ID_USUARIO, NOMBRE, APELLIDO from CENACOM.USUARIOS_CENACOM WHERE TURNO='".$_valor."' ORDER BY ID_USUARIO");
	oci_execute($query);
	while ($row = oci_fetch_array($query, OCI_ASSOC+OCI_RETURN_NULLS)) {
		$_arreglo[] = array('id' => $row['ID_USUARIO'], 'data' => $row['NOMBRE']." ".$row['APELLIDO']);
	}
	echo json_encode($_arreglo);	
}

else{
		$_arreglo[] = array('id' => 1, 'data' => 'Sin valor');
}

?>
