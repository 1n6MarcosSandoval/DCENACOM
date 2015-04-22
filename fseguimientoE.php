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

$seguimientoFechaReporta=str_replace("-","/",$_POST["seguimientoFechaReporta"]);
$seguimientoFechaHoraReporta=$seguimientoFechaReporta." ".$_POST["seguimientoHoraQueReportaval"].":00";

if(!@$_POST["seguimientoLocalidad"])
$seguimientoLocalidad=0;
else
$seguimientoLocalidad=$_POST["seguimientoLocalidad"];

if(!@$_POST["seguimientoOtroLugar"])
$seguimientoOtroLugar="-";
else
$seguimientoOtroLugar=$_POST["seguimientoOtroLugar"];

$seguimientoFechaFenomeno=str_replace("-","/",$_POST["seguimientoFechaFenomeno"]);
$seguimientoFechaHoraFenomeno=$seguimientoFechaFenomeno." ".$_POST["seguimientoHoraInicialFenomenoval"].":00";



if(@$_POST["seguimientoRespuestaInstitucional"]){
	$seguimientoInstitucionesLista="";
	$seguimientoInstituciones=$_POST["seguimientoRespuestaInstitucional"]; 
	for ($i=0;$i<count($seguimientoInstituciones);$i++)    
	{     
		if($i){$seguimientoInstitucionesLista=$seguimientoInstitucionesLista.",".$seguimientoInstituciones[$i];}else{
			$seguimientoInstitucionesLista=$seguimientoInstituciones[$i];
		}
	}
	$firephp->log("Respuesta institucional: ".$seguimientoInstitucionesLista."<br/>");
}


if(@$_POST["seguimientoAutores"]){
	$seguimientoAutoresLista=$_POST["seguimientoAutores"];
	$firephp->log("Autores: ".$seguimientoAutoresLista."<br/>");
}


	$sql = "SELECT TO_CHAR(FECHA_REPORTE, 'YYYY/MM/DD hh24:mm:ss') FROM CENACOM.REPORTES WHERE CR=".$_POST["crRegistrado"];;
	$stmt=oci_parse($conOracle,$sql);
	oci_execute($stmt, OCI_DEFAULT);
	while ($raw = oci_fetch_array($stmt, OCI_ASSOC+OCI_RETURN_NULLS)) {
		foreach ($raw as $item) {
			$fechaReporte=$item;
		}
	}

$seguimientoFechaHoraReportaSQL="to_date('".$seguimientoFechaHoraReporta."','yyyy/mm/dd hh24:mi:ss')"; 
$seguimientoFechaHoraFenomenoSQL="to_date('".$seguimientoFechaHoraFenomeno."','yyyy/mm/dd hh24:mi:ss')";  
$fechaReporteSQL="to_date('".$fechaReporte."','yyyy/mm/dd hh24:mi:ss')";


    $cr_in=$_POST["crRegistrado"];
    $ID_REPORTE=$_POST["crRegistrado"];
    $FECHA_REPORTE=$fechaReporteSQL;
    $EFECTO_ADVERSO=$_POST["seguimientoEfectoAdverso"];
    $FECHA_AVISO=$seguimientoFechaHoraReportaSQL;
    $ORGANISMO_AVISO=$_POST["seguimientoOrganismoReporta"];
    $AREAS_AFECTADAS=$_POST["seguimientoAreasAfectadas"];
    $PERSONAS_AFECTADAS=$_POST["seguimientoPersonasAfectadas"];
    $MUERTOS=$_POST["seguimientoMuertos"];
    $LESIONADOS=$_POST["seguimientoLesionados"];
    $DESAPARECIDOS=$_POST["seguimientoDesaparecidos"];
    $EVACUADOS=$_POST["seguimientoEvacuados"];
    $LINEAS_VITALES=$_POST["seguimientoLineasVitales"];
    $INFRAESTRUCTURA_DANADA=$_POST["seguimientoInfraestructura"];
    $OBSERVACIONES=$_POST["seguimientoObservaciones"];
    $RESPUESTA_INSTITUCIONAL=$seguimientoInstitucionesLista;
    $LINK=$_POST["seguimientoLinks"];
    $ID_USUARIO=$seguimientoAutoresLista;
    $ID_EVENTO=$_POST["idEvento"];
    $ID_TIPO_REPORTE=$_POST["tipoReporte"];
    $CLASIFICACIONFENOMENO_ID=$_POST["seguimientoClasificacionFenomeno"];
    $ESTADO=$_POST["seguimientoEstado"];
    $MUNICIPIO=$_POST["seguimientoMunicipio"];
    $LOCALIDAD=$seguimientoLocalidad;
    $X='0';
    $Y='0';
    $TURNO=$_POST["seguimientoAutoresTurno"];
    $TIPOFENOMENO_ID=$_POST["seguimientoTipoFenomeno"];
    $OTRO_LUGAR=$seguimientoOtroLugar;
    $CR_RELACIONADO=$_POST["crRelacionado"];
    $FECHA_INICIO_FENOMENO=$seguimientoFechaHoraFenomenoSQL;
    $FECHA_FINAL_FENOMENO=$seguimientoFechaHoraFenomenoSQL;
    $LINK_TITULO=$_POST["seguimientoTituloLinks"];

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
	


if($resultadoEnOracleReporte){
	echo '<h3>Reporte '.$_POST["crRegistrado"].' Actualizado</h3>';
	echo 'Los datos <b>se actualizaron correctamente</b> en la base de datos. <br/>';
	echo 'Archivo de Microsoft Word listo para descarga: <a href="visual_download.php?cr='.$_POST["crRegistrado"].'"> Clic para descargar </a> <br/>';
}else{
	echo 'Hubo un error con la actualizaci&oacute;n en la base de datos. <br/>';	
}

/***************************** ACTUALIZA LA CLAVE DEL LUGAR *******************/

$lugar=cadenaLugar($_POST["seguimientoEstado"], $_POST["seguimientoMunicipio"], $seguimientoLocalidad);
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

/************************* FIN ACTUALIZA LA CLAVE DEL LUGAR*******************/



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