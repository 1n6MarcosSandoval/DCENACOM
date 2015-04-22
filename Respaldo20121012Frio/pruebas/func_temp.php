<?php

/*FUNCION QUE A PARTIR DE CIERTA INFORMACION RECUPERA UN CAMPO DE UNA TABLA DADA*/
function recuperaCampo($conOr, $tabla, $campoO, $datoI, $campoI){
	$stid = oci_parse($conOr, 'SELECT '. $campoO . ' FROM ' . $tabla . ' WHERE ' . $campoI . '=' . $datoI);
	oci_execute($stid, OCI_DEFAULT);
	//oci_fetch_all($stid, $res);
	while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
		foreach ($row as $item) {
			return $item;
		}
	}
}

function recuperaCampoS($conOr, $tabla, $campoOu, $campoOd, $datoI, $campoI){
	$stid = oci_parse($conOr, 'SELECT '. $campoOu . ' FROM ' . $tabla . ' WHERE ' . $campoI . '=' . $datoI);
	oci_execute($stid, OCI_DEFAULT);
	$res='';
	//oci_fetch_all($stid, $res);
	while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
		foreach ($row as $item) {
			$res = $res.$item;
		}
	}
	$stid = oci_parse($conOr, 'SELECT '. $campoOd . ' FROM ' . $tabla . ' WHERE ' . $campoI . '=' . $datoI);
	oci_execute($stid, OCI_DEFAULT);
	//oci_fetch_all($stid, $res);
	while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
		foreach ($row as $item) {
			$res = $res.' '.$item;
		}
	}
	return $res;
}

?>