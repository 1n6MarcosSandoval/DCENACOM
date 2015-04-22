<?php


/* Funcion de conexion a la base de datos de Oracle */
function conexionORACLE($HOST, $PORT, $SID, $userName, $passkey) {
	$db = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = $HOST)(PORT = $PORT)))(CONNECT_DATA=(SID=$SID)))";
	$c = oci_connect($userName,$passkey,$db); 
	if (!$c) {
   		$m = oci_error();
  		print htmlentities($m['message']);
   		exit;
		}
		else {
   			//echo "Connected to Oracle!<br/>";
			return $c;			
   			}		
}
/* FIN Funcion de conexion a la base de datos de Oracle */

/* Funcion de cerrar conexion a la base de datos de Oracle */
function cerrarConexionORACLE($c) {
oci_close($c);
}
/* Funcion de cerrar conexion a la base de datos de Oracle */



/* Funcion que que devuelve un valor desde el resultado de un query */
function obtenerValorQuery($conexionBD,$bd, $tabla, $campoQuery, $valorCampoQuery, $campoObtiene){	
	$query = oci_parse($conexionBD, 'SELECT '.$campoObtiene.' from '.$bd.'.'.$tabla.' WHERE '.$campoQuery.'='.$valorCampoQuery);	
	oci_execute($query);
	$row = oci_fetch_array($query, OCI_ASSOC);
	return $row[$campoObtiene];
}
/* FIN Funcion que que devuelve un valor desde el resultado de un query */

/* Funcion que que devuelve un valor de fecha en formato dd/mm/yyyy/ hh24:mi:ss desde el resultado de un query */
function obtenerValorFecha($conexionBD,$bd, $tabla, $campoQuery, $valorCampoQuery, $campoObtiene){
	$elQuery="select to_char(".$campoObtiene.", 'yyyy\"/\"mm\"/\"dd hh24:mi:ss') as  \"fecha\" from ".$bd.".".$tabla." WHERE ".$campoQuery."=".$valorCampoQuery;
	$query = oci_parse($conexionBD, $elQuery);
	oci_execute($query);
	$row = oci_fetch_array($query, OCI_ASSOC);
	return $row["fecha"];
}
/* FIN Funcion que que devuelve un valor de fecha en formato dd/mm/yyyy/ hh24:mi:ss desde el resultado de un query */

function eliminaCaracteresRepetidos($cadena){
	$separaCadena=explode(",", $cadena);
	$limpiaCadena=array_unique($separaCadena);
	$cadenaLimpia=implode(",", $limpiaCadena);
	return $cadenaLimpia;
	
}


/* Funcion que hace una tabla desde el recultado de un query */
function tablaQuery($query){	
	oci_execute($query);
	print '<table border="1">';
	while ($row = oci_fetch_array($query, OCI_NUM+OCI_RETURN_NULLS)) {
		print '<tr>';
		foreach ($row as $item)
			print '<td>'.htmlentities($item).'</td>';
		print '</tr>';
	}
	print '</table>';
}
/* FIN Funcion que hace una tabla desde el recultado de un query */

/* Funcion que que genera un combo desde el resultado de un query */
function comboQuery($query, $campoIndice, $campoImprime, $idCombo){	
	oci_execute($query);
	print '<select id="'.$idCombo.'" name="'.$idCombo.'">';	
	while ($row = oci_fetch_array($query, MYSQL_ASSOC)) {
		print '<option value="'.$row[$campoIndice].'">'.$row[$campoImprime].'</option>';
	}
	print '</select>';

}
/* FIN Funcion que que genera un combo desde el resultado de un query */


/* Funcion que que genera un combo de Turnos de Autores desde el resultado de un query */
function comboQueryTurnosAutores($query, $campoIndice, $campoImprime, $idCombo){	
	oci_execute($query);
	print '<select id="'.$idCombo.'" name="'.$idCombo.'">';	
	while ($row = oci_fetch_array($query, MYSQL_ASSOC)) {
		print '<option value="'.$row[$campoIndice].'">'.$row[$campoImprime].'</option>';
	}
	print '</select>';

}
/* FIN Funcion que que genera un combo desde el resultado de un query */


/* Funcion que que genera un select multiple de Autores Usuarios del sistema CENACOM */
function comboMultiple($query, $id, $idImprime, $idCombo, $titulo){	
	oci_execute($query);
	print '<select name="'.$idCombo.'[]" id="'.$idCombo.'" title="'.$titulo.'" multiple="" size=12>';	
	while ($row = oci_fetch_array($query, OCI_ASSOC)) {
		print '<option value="'.$row[$id].'">'.$row[$idImprime].'</option>';
	}
	print '</select>';
}
/* FIN Funcion que que genera un select multiple desde el resultado de un query */


/* Funcion que que genera un select multiple de Autores Usuarios del sistema CENACOM */
function comboMultipleAutores($query, $idCombo, $titulo){	
	oci_execute($query);
	print '<select name="'.$idCombo.'[]" id="'.$idCombo.'" multiple="" size=12 title="'.$titulo.'">';	
	while ($row = oci_fetch_array($query, MYSQL_ASSOC)) {
		print '<option value="'.$row['ID_USUARIO'].'">'.$row['NOMBRE']." ".$row['APELLIDO'].'</option>';
	}
	print '</select>';

}
/* FIN Funcion que que genera un select multiple desde el resultado de un query */


/* Funcion que que genera un select multiple desde el resultado de un query */
function comboMultipleQuery($query, $campoIndice, $campoImprime, $idCombo){	
	oci_execute($query);
	print '<select id="'.$idCombo.'"  name="'.$idCombo.'" multiple="multiple">';	
	while ($row = oci_fetch_array($query, MYSQL_ASSOC)) {
		print '<option value="'.$row[$campoIndice].'">'.$row[$campoImprime].'</option>';
	}
	print '</select>';

}
/* FIN Funcion que que genera un select multiple de Autores Usuarios del sistema CENACOM */


/* Funcion que que genera un combo desde el resultado de un query, parametros del query, campo que
 *  indexa el elemento de la lista, campo que se vera en el combo, id del combo de origen,y palabra clave del combo;*/
function comboQueryJS_1($query, $campoIndice, $campoImprime, $idComboOrigen, $datoInicial,$titulo){	
	oci_execute($query);
	print '<select id="'.$idComboOrigen.'"  name="'.$idComboOrigen.'" title="'.$titulo.'">';
	print '<option value="0">'.$datoInicial.'</option>';
	while ($row = oci_fetch_array($query, MYSQL_ASSOC)) {
		print '<option value="'.$row[$campoIndice].'">'.$row[$campoImprime].'</option>';
	}
	print '</select>';

}
/* FIN Funcion que que genera un combo desde el resultado de un query*/



/* Funcion que que genera dos combos desde el resultado de un query, parametros del query, campo que
 *  indexa el elemento de la lista, campo que se vera en el combo, id del combo de origen, id del combo a generar,
 *  y palabra clave del combo; y llama una funcion JavaScript definida
 * 
 * 	$query = oci_parse($conOracle, 'SELECT ID, CLASIFICACION from ANRO.CLASIFICACIONFENOMENO ORDER BY ID');
	comboQueryJS($query,"ID", 'CLASIFICACION', 'unicoClasificacionFenomeno', 'unicoTipoFenomeno', 'fenomeno', 'Seleccionar');
 * 
 * */
function comboQueryJS($query, $campoIndice, $campoImprime, $idComboOrigen, $idComboDestino, $idComboC, $datoInicial, $titulo){	
	oci_execute($query);
	print '<select id="'.$idComboOrigen.'"  name="'.$idComboOrigen.'" title="'.$titulo.'" onchange="SeleccionandoCombo(this, \''.$idComboDestino.'\', \''.$idComboC.'\');">';
	print '<option value="0">'.$datoInicial.'</option>';
	while ($row = oci_fetch_array($query, MYSQL_ASSOC)) {
		print '<option value="'.$row[$campoIndice].'">'.$row[$campoImprime].'</option>';
	}
	print '</select>';
	
	print '<select id="'.$idComboDestino.'"  name="'.$idComboDestino.'" title="'.$titulo.'" ></select>';
}
/* FIN Funcion que que genera un combo desde el resultado de un query*/


/* Funcion que que genera dos combos (uno simple y otro multiple) desde el resultado de un query, parametros del query, campo que
 *  indexa el elemento de la lista, campo que se vera en el combo, id del combo de origen, id del combo a generar,
 *  y palabra clave del combo; y llama una funcion JavaScript definida * 
 */
function comboQueryJSMultiple($query, $campoIndice, $campoImprime, $idComboOrigen, $idComboDestino, $idComboC, $datoInicial, $titulo){	
	oci_execute($query);
	print '<select id="'.$idComboOrigen.'"  name="'.$idComboOrigen.'" title="'.$titulo.'" onchange="SeleccionandoComboM(this, \''.$idComboDestino.'\', \''.$idComboC.'\');">';
	print '<option value="0">'.$datoInicial.'</option>';
	while ($row = oci_fetch_array($query, OCI_ASSOC)) {
		print '<option value="'.$row[$campoIndice].'">'.$row[$campoImprime].'</option>';
	}
	print '</select>';
	print '<br/>';
	print '<select id="'.$idComboDestino.'"  name="'.$idComboDestino.'[]" title="'.$titulo.'" multiple="" size=5><option value="0">Primero seleccione el turno...</option></select>';
}
/* FIN Funcion que que genera un combo desde el resultado de un query*/




/* Funcion que que genera un combo desde el resultado de un query; parametros:  query, campo que
 *  indexa el elemento de la lista, campo que se vera en el combo, combo de origen, combo a generar, combo siguiente a generar,
 *  palabra clave del combo, dato inicial del combo a generar; y llama una funcion JavaScript definida*/
function comboQueryJS_3($query, $campoIndice, $campoImprime, $idComboOrigen, $idComboDestino1, $idComboDestino2, $idComboC, $datoInicial){	
	oci_execute($query);
	print '<select id="'.$idComboOrigen.'"  name="'.$idComboOrigen.'" onchange="SeleccionandoCombo_3(this, \''.$idComboDestino1.'\', \''.$idComboDestino2.'\', \''.$idComboC.'\');">';
	print '<option value="0">'.$datoInicial.'</option>';
	while ($row = oci_fetch_array($query, MYSQL_ASSOC)) {
		print '<option value="'.$row[$campoIndice].'">'.$row[$campoImprime].'</option>';
	}
	print '</select>';	
}
/* FIN Funcion que que genera un combo desde el resultado de un query*/

/* Funcion que que genera un combo desde el resultado de un query; parametros:  query, campo que
 *  indexa el elemento de la lista, campo que se vera en el combo, combo de origen, combo a generar, combo siguiente a generar,
 *  palabra clave del combo, dato inicial del combo a generar; y llama una funcion JavaScript definida*/
         
function comboQueryJS_3c($query, $campoIndice, $campoImprime, $idComboOrigen, $idComboDestino1, $idComboDestino2, $idCombo1, $idCombo2, $datoInicial, $titulo){	
	oci_execute($query);
	print '<select id="'.$idComboOrigen.'"  name="'.$idComboOrigen.'" title="'.$titulo.'" onchange="SeleccionandoCombo_3(this, \''.$idComboDestino1.'\', \''.$idComboDestino2.'\', \''.$idCombo1.'\');">';
	print '<option value="0">'.$datoInicial.'</option>';
	while ($row = oci_fetch_array($query, MYSQL_ASSOC)) {
		print '<option value="'.$row[$campoIndice].'">'.$row[$campoImprime].'</option>';
	}
	print '</select>';	
	print '<br/>';
	print '<select id="'.$idComboDestino1.'"  name="'.$idComboDestino1.'" onchange="SeleccionandoCombo(this, \''.$idComboDestino2.'\', \''.$idCombo2.'\');"></select>';
	print '<br/>';
	print '<select id="'.$idComboDestino2.'"  name="'.$idComboDestino2.'" ></select>';	
	
}
/* FIN Funcion que que genera un combo desde el resultado de un query*/


/* Funcion que busca una cadena en un caracter */
function encuentraCadena($cadenaEncontrar, $cadenaBuscar){
	$existencia = strripos($cadenaEncontrar, $cadena); //insensible a mayusculas y minusculas
	if (!$existencia) {
		    return 0;
	} else {
		    return 1;
	}
}
/* FIN Funcion que busca una cadena en un caracter */

/*FUNCIONES DE CALENDARIOS Y FECHAS HORAS*/

function fechaBox($leyenda, $name, $id, $value, $titulo){
	print '<label for="'.$name.'">'.$leyenda.'</label>
			<input type="text" name="'.$name.'" id="'.$id.'" title="'.$titulo.'" value="'.$value.'"
			data-dojo-type="dijit/form/DateTextBox" required="true" />';
}

function fechasBoxes($leyendaInicial, $nameInicial, $idInicial, $leyendaFinal, $nameFinal, $idFinal){
	print '
	
<label for="'.$idInicial.'">'.$leyendaInicial.'</label>
<input data-dojo-id="'.$idInicial.'" type="text" name="'.$nameInicial.'" data-dojo-type="dijit/form/DateTextBox" required="true"
    onChange="'.$idFinal.'.constraints.min = arguments[0];" />
    
<label for="'.$idFinal.'">'.$leyendaFinal.'</label>
<input data-dojo-id="'.$idFinal.'" type="text" name="'.$nameFinal.'" data-dojo-type="dijit/form/DateTextBox" required="true"
    onChange="'.$idInicial.'.constraints.max = arguments[0];" />
	
	';
}


function hora($leyenda, $name, $id, $titulo){
	$hora=date('H');
	$minuto=date('i');
	print '
<label for="'.$id.'">'.$leyenda.'</label>
<input type="text" name="'.$name.'" id="'.$id.'" title="'.$titulo.'" value="T'.$hora.':'.$minuto.':00"
    data-dojo-type="dijit/form/TimeTextBox"
    onChange="require([\'dojo/dom\'], function(dom){dom.byId(\''.$id.'val\').value=dom.byId(\''.$id.'\').value.toString().replace(/.*1970\s(\S+).*/,\'T$1\')})"
    required="true" />
<br/><input type="hidden" id="'.$id.'val" name="'.$id.'val" title="'.$titulo.'" value="'.$hora.':'.$minuto.'" readonly="readonly"/>
	
		';
}

/*FIN DE FUNCIONES DE CALENDARIOS Y FECHAS HORAS*/
function contarRegistros($query){
	oci_execute($query);
	$row = oci_fetch_array($query, OCI_ASSOC);
	return $row['CUENTA'];
	}


//FUNCIONES DE GERARDO

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
	if (!$stid) {
		$e = oci_error($conOracle);
		print "error de conexión";
		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}

	// Perform the logic of the query
	$r = oci_execute($stid);
	if (!$r) {
		$e = oci_error($stid);
		print "error de ejecución";
		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}
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

function consultaORACLEPass($conOracle,$campoSelect, $campoWhere, $user1, $pass1, $tablePass){
	$password='';
	$valor=$pass1;
	$pass1=sanitize_paranoid_string($valor,5,20);
	print $pass1;
	$valor=$user1;
	$user1=sanitize_sql_string($valor,8,25);
	$password=$password.recuperaCampo($conOracle,$tablePass,$campoSelect,$valor,$campoWhere);
	print $password;
	if($password==$pass1){return "correcto";}
	else{return "incorrecto";}
	}

function sanitize_paranoid_string($string, $min='', $max=''){
	$string = preg_replace("/[^a-zA-Z0-9]/", "", $string);
	$len = strlen($string);
	if((($min != '') && ($len < $min)) || (($max != '') && ($len > $max)))
		return "incorrecto";
	return $string;
}

function sanitize_sql_string($string, $min='', $max=''){
	$string = nice_addslashes($string); //gz
	$pattern = "/;/"; // jp
	$replacement = "";
	$len = strlen($string);
	if((($min != '') && ($len < $min)) || (($max != '') && ($len > $max)))
		return "incorrecto";
	return preg_replace($pattern, $replacement, $string);
}

function nice_addslashes($string){
	// if magic quotes is on the string is already quoted, just return it
	if('MAGIC_QUOTES')
		return $string;
	else
		return addslashes($string);
}
//FIN FUNCIONES DE GERARDO


function filtroCaracteresXML($valor){
	$str=$valor;
	$str = str_replace( '&', '&amp;', $str );
	$str = str_replace( '<', '&lt;',  $str);
	$str = str_replace( '>', '&gt;',  $str);
	$str = str_replace( '\'', '&apos;', $str);
	return $str;
}







?>