<?php
	$db = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = 10.2.233.164)(PORT = 1521)))(CONNECT_DATA=(SID=ORCL)))";
	$c = oci_connect('cenacom','jxkGR',$db);
	$query = 'select CLASIFICACION from ANRO.CLASIFICACIONFENOMENO';
	$stmt = ociparse($c,$query);
	ociexecute($stmt);
	
	//echo '<select name = "CLASIFICACION">';
	//echo '<option value = "-1">Select:</option>';
	//while($row=oci_fetch_assoc($stmt)){
	//	echo '<option>'.$row['CLASIFICACION'].'</option>';
	//}
	//echo '<br>';
?>


<form action="test.php" method="post">
Nombre de Archivo: <input type="text" name="name" />
Su nombre: <input type="text" name="namep" />
Tipo de archivo: <input type="text" name="type" />
Dia de la semana: <input type="text" name="day" />
<select name="select">
<option value="1">x</option>
<option value="2">y</option>
</select>
<input type="submit" />
</form> 