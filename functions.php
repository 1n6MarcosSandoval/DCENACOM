<?php
header('Content-type: text/html; charset=utf-8');

include_once 'ses.php';
	ses(); 

/* Funcion de conexion a la base de datos de Oracle */
function conexionORACLE($HOST, $PORT, $SID, $userName, $passkey) {
	$db = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = $HOST)(PORT = $PORT)))(CONNECT_DATA=(SID=$SID)))";
	$c = oci_connect($userName,$passkey,$db,'AL32UTF8');
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
function obtenerValorQuery0($conexionBD,$bd, $tabla, $campoQuery, $valorCampoQuery, $campoObtiene){
	//oci_field_is_null ( $query , $campoObtiene);
	//$query = oci_parse($conexionBD, 'SELECT NVL('.$campoObtiene.', \' \') from '.$bd.'.'.$tabla.' WHERE '.$campoQuery.'='.$valorCampoQuery);
	$query = oci_parse($conexionBD, 'SELECT '.$campoObtiene.' from '.$bd.'.'.$tabla.' WHERE '.$campoQuery.'='.$valorCampoQuery);
	//echo 'SELECT NVL('.$campoObtiene.', \' \') from '.$bd.'.'.$tabla.' WHERE '.$campoQuery.'='.$valorCampoQuery;
	//$a=oci_field_is_null ( $query , $campoObtiene);
	//$a=OCIColumnIsNULL( $query , $campoObtiene);
	if(!$query){
		return "";
	}else{
		$resultado=oci_execute($query);
		if(!$resultado){
			return "";
		}else{		
			$row = oci_fetch_array($query, OCI_ASSOC);
			return $row[$campoObtiene];
		}
	}
}
/* FIN Funcion que que devuelve un valor desde el resultado de un query */

/* Funcion que que devuelve un valor desde el resultado de un query */
function obtenerValorQuery($conexionBD,$bd, $tabla, $campoQuery, $valorCampoQuery, $campoObtiene){
	$query = oci_parse($conexionBD, 'SELECT '.$campoObtiene.' from '.$bd.'.'.$tabla.' WHERE '.$campoQuery.'='.$valorCampoQuery);
	oci_execute($query);
	$row = oci_fetch_array($query, OCI_ASSOC);
	$valor=@$row[$campoObtiene]; //con el @ revisa si el dato existe
	return $valor;
	
}
/* FIN Funcion que que devuelve un valor desde el resultado de un query */


/* Funcion que que devuelve un valor desde el resultado de un query */
function obtenerValorQuery1($conexionBD,$bd, $tabla, $campoQuery, $valorCampoQuery, $campoObtiene){
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
	while ($row = oci_fetch_array($query, OCI_ASSOC)) {
		print '<option value="'.$row[$campoIndice].'">'.$row[$campoImprime].'</option>';
	}
	print '</select>';

}
/* FIN Funcion que que genera un combo desde el resultado de un query */

/* Funcion que que genera un combo desde el resultado de un query */
function comboQuerySelecValor($query, $campoIndice, $campoImprime, $idValorSeleccionado, $idCombo){	
	oci_execute($query);
	print '<select id="'.$idCombo.'" name="'.$idCombo.'">';	
	while ($row = oci_fetch_array($query, OCI_ASSOC)) {
		if($idValorSeleccionado==$row[$campoIndice]){
			print '<option value="'.$row[$campoIndice].'" selected="selected">'.$row[$campoImprime].'</option>';
		}else{
			print '<option value="'.$row[$campoIndice].'">'.$row[$campoImprime].'</option>';
		}
		//echo  $row[$campoIndice];
		//echo $row[$campoImprime];
	}
	print '</select>';

}
/* FIN Funcion que que genera un combo desde el resultado de un query */

/* Funcion que que genera un combo desde el resultado de un query */
function comboQuerySelecDisabledValor($query, $campoIndice, $campoImprime, $idValorSeleccionado, $idCombo){	
	oci_execute($query);
	print '<select id="'.$idCombo.'" name="'.$idCombo.'" disabled="disabled">';	
	while ($row = oci_fetch_array($query, OCI_ASSOC)) {
		if($idValorSeleccionado==$row[$campoIndice]){
			print '<option value="'.$row[$campoIndice].'" selected="selected">'.$row[$campoImprime].'</option>';
		}else{
			print '<option value="'.$row[$campoIndice].'">'.$row[$campoImprime].'</option>';
		}
		//echo  $row[$campoIndice];
		//echo $row[$campoImprime];
	}
	print '</select>';

}
/* FIN Funcion que que genera un combo desde el resultado de un query */



/* Funcion que que genera un combo desde el resultado de un query seleccionando un valor predeterminado y agrega una funcion en onchange*/
function comboQueryFuncion($query, $campoIndice, $campoImprime, $idCombo, $datoInicial, $funcionJS, $titulo){	
	oci_execute($query);
	print '<select id="'.$idCombo.'"  name="'.$idCombo.'" onchange="'.$funcionJS.'" title="'.$titulo.'">';
	print '<option value="0">'.$datoInicial.'</option>';
	while ($row = oci_fetch_array($query, OCI_ASSOC)) {
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
	print '<select name="'.$idCombo.'[]" id="'.$idCombo.'" title="'.$titulo.'" multiple="NO" size=12>';	
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

/*Nivel*/
function comboQueryNivel($query, $campoIndice, $campoImprime, $idComboOrigen, $datoInicial,$titulo){	
	oci_execute($query);
	print '<select id="'.$idComboOrigen.'"  name="'.$idComboOrigen.'" title="'.$titulo.'">';
	print '<option value="0">'.$datoInicial.'</option>';
	while ($row = oci_fetch_array($query, MYSQL_ASSOC)) {
		print '<option value="'.$row[$campoIndice].'">'.$row[$campoImprime].'</option>';
	}
	print '</select>';

}
/*Fin Nivel*/

/*Estado*/
function comboQueryEstado($query, $campoIndiceEstado, $campoImprimeEstado, $idComboOrigenEstado, $datoInicialEstado,$tituloEstado){	
	oci_execute($query);
	print '<select id="'.$idComboOrigenEstado.'"  name="'.$idComboOrigenEstado.'" title="'.$tituloEstado.'">';
	print '<option value="0">'.$datoInicialEstado.'</option>';
	while ($row = oci_fetch_array($query, MYSQL_ASSOC)) {
		print '<option value="'.$row[$campoIndiceEstado].'">'.$row[$campoImprimeEstado].'</option>';
	}
	print '</select>';

}
/* Fin Estado*/



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
/****Query Turno-Autor****/
function comboQueryJSMultiple($query, $campoIndice, $campoImprime, $idComboOrigen, $idComboDestino, $idComboC, $datoInicial, $titulo){	
	oci_execute($query);
	print '<select id="'.$idComboOrigen.'"  name="'.$idComboOrigen.'" title="'.$titulo.'" onchange="SeleccionandoComboM(this, \''.$idComboDestino.'\', \''.$idComboC.'\');">';
	print '<option value="0">'.$datoInicial.'</option>';
	while ($row = oci_fetch_array($query, OCI_ASSOC)) {
		print '<option value="'.$row[$campoIndice].'">'.$row[$campoImprime].'</option>';
	}
	print '</select>';
	print '<br/>';
	print '<select name="'.$idComboDestino.'[]" multiple="multiple" id="'.$idComboDestino.'"  title="'.$titulo.'" size=5><option value="0">Primero seleccione el turno...</option></select>';
}
/****Fin Query Turno-Autor****/
/******************************************/
/****Query Estado-MUNICIPIO****/

/****Fin Query Estado-MUNICIPIO****/
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
 
         /* ESTA FUNCION ES LA ORIGINAL
function comboQueryJS_3c($query, $campoIndice, $campoImprime, $idComboOrigen, $idComboDestino1, $idComboDestino2, $idCombo1, $idCombo2, $datoInicial, $titulo){	
	oci_execute($query);
	print '<select id="'.$idComboOrigen.'"  name="'.$idComboOrigen.'" title="'.$titulo.'" onchange="SeleccionandoCombo_3(this, \''.$idComboDestino1.'\', \''.$idComboDestino2.'\', \''.$idCombo1.'\');">';
	print '<option value="0">'.$datoInicial.'</option>';
	while ($row = oci_fetch_array($query, MYSQL_ASSOC)) {
		print '<option value="'.$row[$campoIndice].'">'.$row[$campoImprime].'</option>';
	}
	print '</select>';	
	print '<br/>';
	//print '<select id="'.$idComboDestino1.'"  name="'.$idComboDestino1.'" onchange="SeleccionandoCombo(this, \''.$idComboDestino2.'\', \''.$idCombo2.'\');"></select>';
	print '<select id="'.$idComboDestino1.'"  name="'.$idComboDestino1.'" onchange="SeleccionandoComboLocalidad(this, \''.$idComboDestino2.'\', \''.$idCombo2.'\');"></select>';
	print '<br/>';
	print '<select id="'.$idComboDestino2.'"  name="'.$idComboDestino2.'" ></select>';	
	
}
		  * 
		  */
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
	//print '<select id="'.$idComboDestino1.'"  name="'.$idComboDestino1.'" onchange="SeleccionandoCombo(this, \''.$idComboDestino2.'\', \''.$idCombo2.'\');"></select>';
	print '<select id="'.$idComboDestino1.'"  name="'.$idComboDestino1.'" onchange="SeleccionandoComboLocalidad(\''.$idComboOrigen.'\',\''.$idComboDestino1.'\', this, \''.$idComboDestino2.'\', \''.$idCombo2.'\');"></select>';
	print '<br/>';
	print '<select id="'.$idComboDestino2.'"  name="'.$idComboDestino2.'" ></select>';	
	
}

function comboEstado2($query, $campoIndice, $campoImprime, $idComboOrigen, $idComboDestino1, $idCombo1, $datoInicial, $titulo){	
	oci_execute($query);
	print '<select id="'.$idComboOrigen.'"  name="'.$idComboOrigen.'" title="'.$titulo.'" onchange="SeleccionandoCombo_3(this, \''.$idComboDestino1.'\', \''.$idCombo1.'\');">';
	print '<option value="0">'.$datoInicial.'</option>';
	while ($row = oci_fetch_array($query, MYSQL_ASSOC)) {
		print '<option value="'.$row[$campoIndice].'">'.$row[$campoImprime].'</option>';
	}
	print '</select>';	
	print '<br/>';
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


function horaUpdate($leyenda, $name, $id, $titulo, $hora, $minuto){
	//$hora=date('H');
	//$minuto=date('i');
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
	oci_execute($stid, OCI_DEFAULT);
	$res='';
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

function queryRecuperaCampo($conOr, $query){
	$stid = oci_parse($conOr, $query);
	oci_execute($stid, OCI_DEFAULT);
	//oci_fetch_all($stid, $res);
	while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
		foreach ($row as $item) {
			return $item;
		}
	}
}

function cadenaLugar($edo, $mun, $loc){
	
	/*ESTADO*/
	$edo_tamanio=strlen($edo);	
	switch ($edo_tamanio) {
		case 1:
			$nuevo_edo="0".$edo;
			break;
		default:
			$nuevo_edo=$edo;
			break;
	}
	/*FIN ESTADO*/	

	/*MUNICIPIO*/
	$mun_tamanio=strlen($mun);
	
	switch ($mun_tamanio) {
		case 1:
			$nuevo_mun="00".$mun;
			break;
		case 2:
			$nuevo_mun="0".$mun;
			break;
		default:
			$nuevo_mun=$mun;
			break;
	}
	/*FIN MUNICIPIO*/
	
	/*LOCALIDAD*/
	$loc_tamanio=strlen($loc);
	if($loc=="0"){
		$loc_tamanio=5;
	}
	
	switch ($loc_tamanio) {
		case 1:
			$nuevo_loc="000".$loc;
			break;
		case 2:
			$nuevo_loc="00".$loc;
			break;
		case 3:
			$nuevo_loc="0".$loc;
			break;
		case 4:
			$nuevo_loc=$loc;
			break;
		default:
			$nuevo_loc='0001';
			break;
	}

	return $nuevo_edo.$nuevo_mun.$nuevo_loc;
	/*FIN LOCALIDAD*/
	
}

/*LLAVE PARA EL GEORSS*/
function llave(){
$clave="";

for ($i=0; $i < 80; $i++) { 
	$numero_aleatorio = rand(1,37);
	
switch ($numero_aleatorio) {
	case 1:
		$clave=$clave."a";			
	break;
	case 2:
		$clave=$clave."b";			
	break;
	case 3:
		$clave=$clave."c";			
	break;
	case 4:
		$clave=$clave."d";			
	break;	
	case 5:
		$clave=$clave."e";			
	break;	
	case 6:
		$clave=$clave."f";			
	break;
	case 7:
		$clave=$clave."g";			
	break;	
	case 8:
		$clave=$clave."h";			
	break;	
	case 9:
		$clave=$clave."i";			
	break;	
	case 10:
		$clave=$clave."j";			
	break;	
	case 11:
		$clave=$clave."k";			
	break;	
	case 12:
		$clave=$clave."l";			
	break;	
	case 13:
		$clave=$clave."m";			
	break;	
	case 14:
		$clave=$clave."n";			
	break;	
	case 15:
		$clave=$clave."o";			
	break;	
	case 16:
		$clave=$clave."p";			
	break;	
	case 17:
		$clave=$clave."q";			
	break;	
	case 18:
		$clave=$clave."r";			
	break;	
	case 19:
		$clave=$clave."s";			
	break;	
	case 20:
		$clave=$clave."t";			
	break;	
	case 21:
		$clave=$clave."u";			
	break;	
	case 22:
		$clave=$clave."v";			
	break;	
	case 23:
		$clave=$clave."w";			
	break;	
	case 24:
		$clave=$clave."x";			
	break;	
	case 25:
		$clave=$clave."y";			
	break;	
	case 26:
		$clave=$clave."z";			
	break;	
	case 27:
		$clave=$clave."1";			
	break;	
	case 28:
		$clave=$clave."2";			
	break;	
	case 29:
		$clave=$clave."3";			
	break;	
	case 30:
		$clave=$clave."4";			
	break;	
	case 31:
		$clave=$clave."5";			
	break;	
	case 32:
		$clave=$clave."6";			
	break;	
	case 33:
		$clave=$clave."7";			
	break;	
	case 34:
		$clave=$clave."8";			
	break;	
	case 35:
		$clave=$clave."9";			
	break;	

	default:
		$clave=$clave;
	break;
}

}

return $clave;
}
/*FIN LLAVE PARA EL GEORSS*/

?>