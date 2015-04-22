<?php
$db = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = 10.2.233.164)(PORT = 1521)))(CONNECT_DATA=(SID=ORCL)))";
$c1 = oci_connect('cenacom','jxkGR',$db); 

if (!$c1) {
   $m = oci_error();
   print htmlentities($m['message']);
   $s = oci_parse($c, 'select * from f_hidro');
	oci_execute($s);
	oci_fetch_all($s, $res);
	echo "<pre>\n";
	var_dump($res);
	echo "</pre>\n";
   exit;
}
else {
   echo "Connected to Oracle!";
   oci_close($c1);
}

?>