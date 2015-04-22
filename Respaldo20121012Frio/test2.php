<?php
include 'functions.php';
include 'vars.php';

$conOracle=conexionORACLE($HOST, $PORT, $SID, $userName, $passkey);
$ide=$_GET["ide"];
// Prepare the statement
$stid = oci_parse($conOracle, 'SELECT CR FROM CENACOM.REPORTES WHERE ID_EVENTO='.$ide. ' ORDER BY CR');
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
$dato = '';
$i=0;
while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
		//print $row[CR].', ';
		
		$dato[$i] = $row[CR];
		$i++;
		
}
$i=0;
for($i=0 ; $i < (sizeof($dato)-2) ; $i++)
	print $dato[$i]. ", ";


if($dato[$i+1]!=NULL)
	print $dato[$i]. ' y ' . $dato[$i+1];
							
else
	print $dato[$i];


?>