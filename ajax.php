<?php
include 'functions.php';
include 'vars.php';
session_start();

require_once('FirePHPCore/FirePHP.class.php');
ob_start();
$firephp = FirePHP::getInstance(true);

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

if($_combo=='estados'){
	$query = oci_parse($conOracle, 'SELECT ENTIDAD, NOM_ENT from ANRO.LOCALIDADES GROUP BY ENTIDAD, NOM_ENT ORDER BY ENTIDAD');
	comboQueryJS_3c($query,"ENTIDAD", 'NOM_ENT', 'Estado'.$_valor, 'Municipio'.$_valor, 'Localidad'.$_valor, 'municipio', 'localidad', 'Seleccionar', 'Lugar');
	
}elseif($_combo=='municipio'){
	//$firephp->log("Valor en municipio: ".$_valor);
	$query = oci_parse($conOracle, 'SELECT MUN, NOM_MUN from ANRO.LOCALIDADES WHERE ENTIDAD='.$_valor.' GROUP BY NOM_MUN, MUN ORDER BY MUN');
	oci_execute($query);
	while ($row = oci_fetch_array($query, OCI_ASSOC)) {
		//$_arreglo[] = array('id' => $_valor.$row['MUN'], 'data' => $row['NOM_MUN']);
		$_arreglo[] = array('id' => $row['MUN'], 'data' => ($row['NOM_MUN']));
	}
	echo json_encode($_arreglo);
}elseif($_combo=='localidad'){
	$idEstado=$_valor[0];
	$idMunicipio=$_valor[1];
	$query = oci_parse($conOracle, 'SELECT LOC, NOM_LOC from ANRO.LOCALIDADES WHERE ENTIDAD='.$idEstado.' AND MUN='.$idMunicipio.' ORDER BY NOM_LOC');
	//$firephp->log("Query: ".'SELECT LOC, NOM_LOC from ANRO.LOCALIDADES WHERE ENTIDAD='.$idEstado.' AND MUN='.$idMunicipio.' ORDER BY NOM_LOC');
	oci_execute($query);
	while ($row = oci_fetch_array($query, OCI_ASSOC)) {
		$_arreglo[] = array('id' => $row['LOC'], 'data' => ($row['NOM_LOC']));
	}
	echo json_encode($_arreglo);
	
}elseif($_combo=='fenomeno'){
	$query = oci_parse($conOracle, "SELECT ID_FENOMENO, NOMBRE from CENACOM.TIPO_FENOMENO WHERE CLASIFICACIONFENOMENO_ID='".$_valor."'");
	oci_execute($query);
	while ($row = oci_fetch_array($query, OCI_ASSOC)) {
		$_arreglo[] = array('id' => $row['ID_FENOMENO'], 'data' => ($row['NOMBRE']));
	}
	echo json_encode($_arreglo);	
}elseif($_combo=='autores'){
	$query = oci_parse($conOracle, "SELECT ID_USUARIO, NOMBRE, APELLIDO from CENACOM.USUARIOS_CENACOM WHERE TURNO='".$_valor."' ORDER BY ID_USUARIO");
	oci_execute($query);
	while ($row = oci_fetch_array($query, OCI_ASSOC+OCI_RETURN_NULLS)) {
		$_arreglo[] = array('id' => $row['ID_USUARIO'], 'data' => ($row['NOMBRE'])." ".($row['APELLIDO']));
	}
	echo json_encode($_arreglo);	
}

else{
		$_arreglo[] = array('id' => 1, 'data' => 'Sin valor');
}

?>
