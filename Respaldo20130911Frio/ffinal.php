<?php
include 'functions.php';
include 'vars.php';
require_once('FirePHPCore/FirePHP.class.php');
ob_start();
$firephp = FirePHP::getInstance(true);

//Valida que la secion de usuario sea correcta
session_start();
if(@$_SESSION['login'] != "si")
{
header("Location:index.php");
exit();
}

?>

<?php
//Valida el llenado de datos previo
if(@$_POST["tipoReporte"]==NULL)
{
	header("Location:index.php");
	exit();
}else{
	$firephp->log("Formulario listo para registrar datos.");
}

$conOracle=conexionORACLE($HOST, $PORT, $SID, $userName, $passkey);
?>

<?php
include 'arriba.php';
?>


		<!--IZQUIERDA -->
		<div class="left">
			<div class="left_articles">
				<h2>Sistema de captura</h2>
			</div>
						
			<div class="lt"></div>
			<div class="lbox">
				<h2>Registro de reporte.</h2>


<?php
//print "Tipo de reporte: ".$_POST["tipoReporte"]."<br/>";

//if(@$_POST["crRelacionado"])
//print "CR relacionado: ".$_POST["crRelacionado"]."<br/>";

//print "Efecto Adverso: ".$_POST["finalEfectoAdverso"]."<br/>";

//print "Organismo que reporta: ".$_POST["finalOrganismoReporta"]."<br/>";
$finalFechaReporta=str_replace("-","/",$_POST["finalFechaReporta"]);
$finalFechaHoraReporta=$finalFechaReporta." ".$_POST["finalHoraQueReportaval"].":00";
//print "Fecha y hora que reporta: ".$finalFechaHoraReporta."<br/>";

//print "Estado: ".$_POST["finalEstado"]."<br/>";
//if(@$_POST["finalMunicipio"])
//print "Municipio: ".$_POST["finalMunicipio"]."<br/>";
//if(@$_POST["finalLocalidad"])
if(!@$_POST["finalLocalidad"])
$finalLocalidad=0;
else
$finalLocalidad=$_POST["finalLocalidad"];
//print "Localidad: ".$finalLocalidad."<br/>";

if(!@$_POST["finalOtroLugar"])
$finalOtroLugar="-";
else
$finalOtroLugar=$_POST["finalOtroLugar"];
//print "Otro lugar: ".$finalOtroLugar."<br/>";

//print "Clasificacion fenomeno: ".$_POST["finalClasificacionFenomeno"]."<br/>";
//if(@$_POST["finalTipoFenomeno"]);
//print "Tipo fenomeno: ".$_POST["finalTipoFenomeno"]."<br/>";


$finalFechaFenomeno=str_replace("-","/",$_POST["finalFechaFenomeno"]);
$finalFechaHoraFenomeno=$finalFechaFenomeno." ".$_POST["finalHoraInicialFenomenoval"].":00";
//print "Fecha y hora del fenomeno: ".$finalFechaHoraFenomeno."<br/>";


$finalFechaFinalFenomeno=str_replace("-","/",$_POST["finalFechaFinalFenomeno"]);
$finalFechaHoraFinalFenomeno=$finalFechaFinalFenomeno." ".$_POST["finalHoraFinFenomenoval"].":00";
//print "Fecha y hora final del fenomeno: ".$finalFechaHoraFinalFenomeno."<br/>";




//print "Observaciones: ".$_POST["finalObservaciones"]."<br/>"; 

//print "Areas afectadas: ".$_POST["finalAreasAfectadas"]."<br/>";
//print "Personas afectadas: ".$_POST["finalPersonasAfectadas"]."<br/>";
//print "Muertos: ".$_POST["finalMuertos"]."<br/>";
//print "Lesionados: ".$_POST["finalLesionados"]."<br/>";
//print "Evacuados: ".$_POST["finalEvacuados"]."<br/>";
//print "Desaparecidos: ".$_POST["finalDesaparecidos"]."<br/>";

//print "L&iacute;neas vitales: ".$_POST["finalLineasVitales"]."<br/>";


if(@$_POST["finalRespuestaInstitucional"]){
	$finalInstitucionesLista="";
	$finalInstituciones=$_POST["finalRespuestaInstitucional"]; 
	for ($i=0;$i<count($finalInstituciones);$i++)    
	{     
	//print "Respuesta institucional " . $i . ": " . $finalInstituciones[$i];
		//print "Respuesta institucional: ". $finalInstituciones[$i]."<br/>";
		if($i){$finalInstitucionesLista=$finalInstitucionesLista.",".$finalInstituciones[$i];}else{
			$finalInstitucionesLista=$finalInstituciones[$i];
		}
	}
	//print "Respuesta institucional: ".$finalInstitucionesLista."<br/>";
}

//print "Links: ".$_POST["finalLinks"]."<br/>";
/*
if(@$_POST["finalAutores"]){
	$finalAutoresLista="";
	$finalAutores=$_POST["finalAutores"]; 
	for ($i=0;$i<count($finalAutores);$i++)    
	{     
	//print "<br> Autores " . $i . ": " . $finalAutores[$i];
		//print "<br> Autor: ". $finalAutores[$i]."<br/>";
		if($i){$finalAutoresLista=$finalAutoresLista.",".$finalAutores[$i];}else{
			$finalAutoresLista=$finalAutores[$i];
		}	 
	} 
	//print "Autores: ".$finalAutoresLista."<br/>";
}
*/
if(@$_POST["finalAutores"]){
	$finalAutoresLista=$_POST["finalAutores"];
	$firephp->log("Autores: ".$finalAutoresLista."<br/>");
}

$fechaReporte=date('Y/m/d').' '.date('H:i').":00";
//print '<br/>Fecha y hora del reporte: '.$fechaReporte."<br/>";


//print "CR registrado: ";
$query = oci_parse($conOracle, 'SELECT MAX(CR) AS VALOR FROM CENACOM.REPORTES');
oci_execute($query);
$row = oci_fetch_array($query, OCI_ASSOC);
$CRregistrado=$row["VALOR"]+1;
//print $CRregistrado;

//print '<br/>';


//print "Id Reporte: ";
$query = oci_parse($conOracle, 'SELECT MAX(ID_REPORTE) AS VALOR FROM CENACOM.REPORTES');
oci_execute($query);
$row = oci_fetch_array($query, OCI_ASSOC);
$idReporte=$row["VALOR"]+1;
//print $idReporte;

//print '<br/>';
//print "Evento: ";
$idEvento=$_POST["eventoRelacionado"];
//print $idEvento;


?>


<?php
//ESCRIBE EN LA BASE DE DATOS

$fechaReporteSQL="to_date('".$fechaReporte."','yyyy/mm/dd hh24:mi:ss')";
$finalFechaHoraReportaSQL="to_date('".$finalFechaHoraReporta."','yyyy/mm/dd hh24:mi:ss')"; 
$finalFechaHoraFenomenoSQL="to_date('".$finalFechaHoraFenomeno."','yyyy/mm/dd hh24:mi:ss')";
$finalFechaHoraFinalFenomenoSQL="to_date('".$finalFechaHoraFinalFenomeno."','yyyy/mm/dd hh24:mi:ss')"; 


$institucionesListaEvento=obtenerValorQuery($conOracle,'CENACOM', 'EVENTO', 'ID_EVENTO', $idEvento, 'DEPENDENCIAS_PARTICIPANTES');
$finalInstitucionesListaEvento=eliminaCaracteresRepetidos($finalInstitucionesLista.",".$institucionesListaEvento);
//echo '<br/><br/>';
//echo $finalInstitucionesListaEvento;

$sql01="UPDATE CENACOM.EVENTO SET NOMBRE_EVENTO='FINAL', DANOS_MATERIALES="."'".$_POST["finalDanosMaterialesEvento"]."', DEPENDENCIAS_PARTICIPANTES='".$finalInstitucionesListaEvento."',"." OBSERVACIONES='".$_POST["finalObservacionesEvento"]."', DECLARATORIA ='".$_POST["finalDeclaratoria"]."', ESTADO_EVENTO=0";
$sql02=" WHERE ID_EVENTO=".$idEvento;
$sql0=$sql01.$sql02;
/*
echo '<br/><br/>';
echo $sql0;
*/

$sql1="INSERT INTO CENACOM.REPORTES(ID_REPORTE, CR, FECHA_REPORTE, EFECTO_ADVERSO, FECHA_AVISO, ORGANISMO_AVISO, AREAS_AFECTADAS, PERSONAS_AFECTADAS, MUERTOS, LESIONADOS, DESAPARECIDOS, EVACUADOS, LINEAS_VITALES, INFRAESTRUCTURA_DANADA, OBSERVACIONES, RESPUESTA_INSTITUCIONAL, LINK, ID_USUARIO, ID_EVENTO, ID_TIPO_REPORTE, CLASIFICACIONFENOMENO_ID, ESTADO, MUNICIPIO, LOCALIDAD, X, Y, TURNO, TIPOFENOMENO_ID, OTRO_LUGAR, CR_RELACIONADO, FECHA_INICIO_FENOMENO, FECHA_FINAL_FENOMENO,LINK_TITULO)";
$sql2="VALUES(";
$sql4=")";
$sql31=$idReporte." ,".$CRregistrado." ,".$fechaReporteSQL.",'".$_POST["finalEfectoAdverso"]."' ,".$finalFechaHoraReportaSQL." ,'";
$sql32=$_POST["finalOrganismoReporta"]."' ,'".$_POST["finalAreasAfectadas"]."' ,'".$_POST["finalPersonasAfectadas"]."' ,";
$sql33=$_POST["finalMuertos"]." ,".$_POST["finalLesionados"]." ,".$_POST["finalDesaparecidos"]." ,".$_POST["finalEvacuados"]." ,'";
$sql34=$_POST["finalLineasVitales"]."' ,'".$_POST["finalInfraestructura"]."','".$_POST["finalObservaciones"]."' ,'";
$sql35=$finalInstitucionesLista."' ,'".$_POST["finalLinks"]."' ,'".$finalAutoresLista."',".$idEvento." ,".$_POST["tipoReporte"].",'";
$sql36=$_POST["finalClasificacionFenomeno"]."',".$_POST["finalEstado"].",".$_POST["finalMunicipio"].",".$finalLocalidad.",";
$sql37="0"." ,"."0"." ,".$_POST["finalAutoresTurno"].",".$_POST["finalTipoFenomeno"].",'".$finalOtroLugar."' ,".$_POST["crRelacionado"]." ,".$finalFechaHoraFenomenoSQL." ,".$finalFechaHoraFinalFenomenoSQL.", '".$_POST["finalTituloLinks"]."'";
$sql3=$sql31.$sql32.$sql33.$sql34.$sql35.$sql36.$sql37; 
$sql=$sql1.$sql2.$sql3.$sql4;

/*
echo '<br/><br/>';
echo $sql;
*/


//ESCRIBE EN LA BD QUERY DEL EVENTO
$escribeOracleEvento=oci_parse($conOracle,$sql0);
if (!$escribeOracleEvento) {
    $e = oci_error($conOracle);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
$resultadoEnOracleEvento=oci_execute($escribeOracleEvento);
if (!$resultadoEnOracleEvento) {
    $e = oci_error($escribeOracleEvento);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

	//ESCRIBE EN LA BD QUERY DEL REPORTE si se capturo el Evento
if($resultadoEnOracleEvento){
	$escribeOracleReporte=oci_parse($conOracle,$sql);
	if (!$escribeOracleReporte) {
    	$e = oci_error($conOracle);
    	trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}
	$resultadoEnOracleReporte=oci_execute($escribeOracleReporte);
	if (!$resultadoEnOracleReporte) {
    	$e = oci_error($escribeOracleReporte);
    	trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}
	
}

if($resultadoEnOracleEvento && $resultadoEnOracleReporte){
	echo '<h3>Reporte '.$CRregistrado.' registrado</h3>';
	echo 'Los datos <b>se registraron correctamente</b> en la base de datos. <br/>';
	echo 'Archivo de Microsoft Word listo para descarga: <a href="visual_download.php?cr='.$CRregistrado.'"> Clic para descargar </a> <br/>';
}else{
	echo 'Hubo un error en el registro con la base de datos. <br/>';	
}


/*

echo '<br/><br/>';
echo $sql3;
echo '<br/><br/>';
echo $sql;
echo '<br/><br/>';
echo 'ID_REPORTE:' .$idReporte.'<br/>';
echo 'CR:' .$CRregistrado.'<br/>';
echo 'FECHA_REPORTE:' .$fechaReporte.'<br/>';
echo 'EFECTO_ADVERSO:' .$_POST["finalEfectoAdverso"].'<br/>';
echo 'FECHA_AVISO:' .$finalFechaHoraReporta.'<br/>';
echo 'ORGANISMO_AVISO:' .$_POST["finalOrganismoReporta"].'<br/>';
echo 'AREAS_AFECTADAS:' .$_POST["finalAreasAfectadas"].'<br/>';
echo 'PERSONAS_AFECTADAS:' .$_POST["finalPersonasAfectadas"].'<br/>';
echo 'MUERTOS:' .$_POST["finalMuertos"].'<br/>';
echo 'LESIONADOS:' .$_POST["finalLesionados"].'<br/>';
echo 'DESAPARECIDOS:' .$_POST["finalDesaparecidos"].'<br/>';
echo 'EVACUADOS:' .$_POST["finalEvacuados"].'<br/>';
echo 'LINEAS_VITALES:' .$_POST["finalLineasVitales"].'<br/>';
echo 'INFRAESTRUCTURA_DANADA:' .$_POST["finalInfraestructura"].'<br/>';
echo 'OBSERVACIONES:' .$_POST["finalObservaciones"].'<br/>';
echo 'RESPUESTA_INSTITUCIONAL:' .$finalInstitucionesLista.'<br/>';
echo 'LINK:' .$_POST["finalLinks"].'<br/>';
echo 'ID_USUARIO:' .$finalAutoresLista.'<br/>';
echo 'ID_EVENTO:' ."1".'<br/>';
echo 'ID_TIPO_REPORTE:' .$_POST["tipoReporte"].'<br/>';
echo 'CLASIFICACIONFENOMENO_ID:' .$_POST["finalClasificacionFenomeno"].'<br/>';
echo 'ESTADO:' .$_POST["finalEstado"].'<br/>';
echo 'MUNICIPIO:' .$_POST["finalMunicipio"].'<br/>';
echo 'LOCALIDAD:' .$finalLocalidad.'<br/>';
echo 'X:' ."0".'<br/>';
echo 'Y:' ."0".'<br/>';
echo 'TURNO:' .$_POST["finalAutoresTurno"].'<br/>';
echo 'TIPOFENOMENO_ID:' .$_POST["finalTipoFenomeno"].'<br/>';
echo 'OTRO_LUGAR:' .$finalOtroLugar.'<br/>';
echo 'CR_RELACIONADO:' ."0".'<br/>';
echo 'FECHA_INICIO_FENOMENO'.$finalFechaHoraFenomenoSQL;
*/

/***************************** ACTUALIZA LA CLAVE DEL LUGAR *******************/

$lugar=cadenaLugar($_POST["finalEstado"], $_POST["finalMunicipio"], $finalLocalidad);
//echo $lugar;

	/*ACTUALIZA LA BD */
	$queryEdo="UPDATE CENACOM.REPORTES SET CLAVE_LUGAR='".$lugar."' WHERE CR=".$CRregistrado;
	
	$escribeOracleAct=oci_parse($conOracle,$queryEdo);
	if (!$escribeOracleAct) {
    	$e = oci_error($conOracle);
    	trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}
	$resultadoEnOracleAct=oci_execute($escribeOracleAct);
	if (!$resultadoEnOracleAct) {
    	$e = oci_error($escribeOracleAct);
    	trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}
/*
	if($resultadoEnOracleAct){
		echo '<h3>Reporte Actualizado</h3>';
	}else{
		echo 'Hubo un error con la actualizaci&oacute;n en la base de datos. <br/>';	
	}
 */ 
	/* FIN ACTUALIZA LA BD */

/************************* FIN ACTUALIZA LA CLAVE DEL LUGAR*******************/



?>

<?php
	cerrarConexionORACLE($conOracle);
?>

			</div>
		</div>
		<!-- FIN DE IZQUIERDA -->	

	<?php
		include 'derecha0.php';
	?>
		<div class="footer">
			<p>Sistema Nacional de Protecci&oacute;n Civil</p>
		</div>
	</div>
</body>
</html>