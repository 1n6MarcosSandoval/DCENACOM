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
	$firephp->log("Formulario de actualizacion listo para registrar datos.");
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

$finalFechaReporta=str_replace("-","/",$_POST["finalFechaReporta"]);
$finalFechaHoraReporta=$finalFechaReporta." ".$_POST["finalHoraQueReportaval"].":00";

if(!@$_POST["finalLocalidad"])
$finalLocalidad=0;
else
$finalLocalidad=$_POST["finalLocalidad"];

if(!@$_POST["finalOtroLugar"])
$finalOtroLugar="-";
else
$finalOtroLugar=$_POST["finalOtroLugar"];

$finalFechaFenomeno=str_replace("-","/",$_POST["finalFechaFenomeno"]);
$finalFechaHoraFenomeno=$finalFechaFenomeno." ".$_POST["finalHoraFinalFenomenoval"].":00";



if(@$_POST["finalRespuestaInstitucional"]){
	$finalInstitucionesLista="";
	$finalInstituciones=$_POST["finalRespuestaInstitucional"]; 
	for ($i=0;$i<count($finalInstituciones);$i++)    
	{     
		if($i){$finalInstitucionesLista=$finalInstitucionesLista.",".$finalInstituciones[$i];}else{
			$finalInstitucionesLista=$finalInstituciones[$i];
		}
	}
	$firephp->log("Respuesta institucional: ".$finalInstitucionesLista."<br/>");
}


if(@$_POST["finalAutores"]){
	$finalAutoresLista=$_POST["finalAutores"];
	$firephp->log("Autores: ".$finalAutoresLista."<br/>");
}


	$sql = "SELECT TO_CHAR(FECHA_REPORTE, 'YYYY/MM/DD hh24:mm:ss') FROM CENACOM.REPORTES WHERE CR=".$_POST["crRegistrado"];;
	$stmt=oci_parse($conOracle,$sql);
	oci_execute($stmt, OCI_DEFAULT);
	while ($raw = oci_fetch_array($stmt, OCI_ASSOC+OCI_RETURN_NULLS)) {
		foreach ($raw as $item) {
			$fechaReporte=$item;
		}
	}

$finalFechaHoraReportaSQL="to_date('".$finalFechaHoraReporta."','yyyy/mm/dd hh24:mi:ss')"; 
$finalFechaHoraFenomenoSQL="to_date('".$finalFechaHoraFenomeno."','yyyy/mm/dd hh24:mi:ss')";  
$fechaReporteSQL="to_date('".$fechaReporte."','yyyy/mm/dd hh24:mi:ss')";


    $cr_in=$_POST["crRegistrado"];
    $ID_REPORTE=$_POST["crRegistrado"];
    $FECHA_REPORTE=$fechaReporteSQL;
    $EFECTO_ADVERSO=$_POST["finalEfectoAdverso"];
    $FECHA_AVISO=$finalFechaHoraReportaSQL;
    $ORGANISMO_AVISO=$_POST["finalOrganismoReporta"];
    $AREAS_AFECTADAS=$_POST["finalAreasAfectadas"];
    $PERSONAS_AFECTADAS=$_POST["finalPersonasAfectadas"];
    $MUERTOS=$_POST["finalMuertos"];
    $LESIONADOS=$_POST["finalLesionados"];
    $DESAPARECIDOS=$_POST["finalDesaparecidos"];
    $EVACUADOS=$_POST["finalEvacuados"];
    $LINEAS_VITALES=$_POST["finalLineasVitales"];
    $INFRAESTRUCTURA_DANADA=$_POST["finalInfraestructura"];
    $OBSERVACIONES=$_POST["finalObservaciones"];
    $RESPUESTA_INSTITUCIONAL=$finalInstitucionesLista;
    $LINK=$_POST["finalLinks"];
    $ID_USUARIO=$finalAutoresLista;
    $ID_EVENTO=$_POST["idEvento"];
    $ID_TIPO_REPORTE=$_POST["tipoReporte"];
    $CLASIFICACIONFENOMENO_ID=$_POST["finalClasificacionFenomeno"];
    $ESTADO=$_POST["finalEstado"];
    $MUNICIPIO=$_POST["finalMunicipio"];
    $LOCALIDAD=$finalLocalidad;
    $X='0';
    $Y='0';
    $TURNO=$_POST["finalAutoresTurno"];
    $TIPOFENOMENO_ID=$_POST["finalTipoFenomeno"];
    $OTRO_LUGAR=$finalOtroLugar;
    $CR_RELACIONADO=$_POST["crRelacionado"];
    $FECHA_INICIO_FENOMENO=$finalFechaHoraFenomenoSQL;
    $FECHA_FINAL_FENOMENO=$finalFechaHoraFenomenoSQL;
    $LINK_TITULO=$_POST["finalTituloLinks"];

	$query1="UPDATE CENACOM.REPORTES SET ID_REPORTE=".$ID_REPORTE.", FECHA_REPORTE=".$FECHA_REPORTE.", EFECTO_ADVERSO='".$EFECTO_ADVERSO."', ";
	$query2="FECHA_AVISO=".$FECHA_AVISO.", ORGANISMO_AVISO='".$ORGANISMO_AVISO."', AREAS_AFECTADAS='".$AREAS_AFECTADAS."', ";
	$query3="PERSONAS_AFECTADAS='".$PERSONAS_AFECTADAS."', MUERTOS=".$MUERTOS.", LESIONADOS=".$LESIONADOS.", DESAPARECIDOS=".$DESAPARECIDOS.", EVACUADOS=".$EVACUADOS.", ";
	$query4="LINEAS_VITALES='".$LINEAS_VITALES."', INFRAESTRUCTURA_DANADA='".$INFRAESTRUCTURA_DANADA."', OBSERVACIONES='".$OBSERVACIONES."', RESPUESTA_INSTITUCIONAL='".$RESPUESTA_INSTITUCIONAL."', ";
	$query5="LINK='".$LINK."', ID_USUARIO='".$ID_USUARIO."', ID_EVENTO=".$ID_EVENTO.", ID_TIPO_REPORTE=".$ID_TIPO_REPORTE.", ";
	$query6="CLASIFICACIONFENOMENO_ID='".$CLASIFICACIONFENOMENO_ID."', ESTADO=".$ESTADO.", MUNICIPIO=".$MUNICIPIO.", LOCALIDAD=".$LOCALIDAD.", ";
	$query7="X=".$X.", Y=".$Y.", TURNO=".$TURNO.", TIPOFENOMENO_ID=".$TIPOFENOMENO_ID.", OTRO_LUGAR='".$OTRO_LUGAR."', ";
	$query8="CR_RELACIONADO=".$CR_RELACIONADO.", FECHA_INICIO_FENOMENO=".$FECHA_INICIO_FENOMENO.", FECHA_FINAL_FENOMENO=".$FECHA_FINAL_FENOMENO.", ";
	$query9="LINK_TITULO='".$LINK_TITULO."' WHERE CR = ".$cr_in;
	$sql=$query1.$query2.$query3.$query4.$query5.$query6.$query7.$query8.$query9;
	//print "<br/>".$sql."<br/>";

	
	$institucionesListaEvento=obtenerValorQuery($conOracle,'CENACOM', 'EVENTO', 'ID_EVENTO', $_POST["idEvento"], 'DEPENDENCIAS_PARTICIPANTES');
	$finalInstitucionesListaEvento=eliminaCaracteresRepetidos($finalInstitucionesLista.",".$institucionesListaEvento);

	$sql01="UPDATE CENACOM.EVENTO SET NOMBRE_EVENTO='final', DANOS_MATERIALES="."'".$_POST["finalDanosMaterialesEvento"]."', DEPENDENCIAS_PARTICIPANTES='".$finalInstitucionesListaEvento."',"." OBSERVACIONES='".$_POST["finalObservacionesEvento"]."', DECLARATORIA ='".$_POST["finalDeclaratoria"]."' ";
	$sql02="WHERE ID_EVENTO=".$_POST["idEvento"];
	$sql0=$sql01.$sql02;


	$sqlE="UPDATE CENACOM.REPORTES SET FECHA_FINAL_FENOMENO=".$FECHA_FINAL_FENOMENO." WHERE ID_EVENTO=".$_POST["idEvento"];

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
	
	
	//Actualiza las fechas en los reportes relacionados
	$escribeOracleFecha=oci_parse($conOracle,$sqlE);
	if (!$escribeOracleFecha) {
    	$e = oci_error($conOracle);
    	trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}
	$resultadoEnOracleFecha=oci_execute($escribeOracleFecha);
	if (!$resultadoEnOracleFecha) {
    	$e = oci_error($escribeOracleFecha);
    	trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}
	
if($resultadoEnOracleReporte&&$resultadoEnOracleEvento&&$resultadoEnOracleFecha){
	echo '<h3>Reporte '.$_POST["crRegistrado"].' Actualizado</h3>';
	echo 'Los datos <b>se actualizaron correctamente</b> en la base de datos. <br/>';
	echo 'Archivo de Microsoft Word listo para descarga: <a href="visual_download.php?cr='.$_POST["crRegistrado"].'"> Clic para descargar </a> <br/>';
}else{
	echo 'Hubo un error con la actualizaci&oacute;n en la base de datos. <br/>';	
}

/***************************** ACTUALIZA LA CLAVE DEL LUGAR *******************/

$lugar=cadenaLugar($_POST["finalEstado"], $_POST["finalMunicipio"], $finalLocalidad);
//echo $lugar;

	/*ACTUALIZA LA BD */
	$queryEdo="UPDATE CENACOM.REPORTES SET CLAVE_LUGAR='".$lugar."' WHERE CR=".$cr_in;
	
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

/************************* FIN ACTUALIZA LA CLAVE DEL LUGAR *******************/



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