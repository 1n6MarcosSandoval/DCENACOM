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

$alcanceFechaReporta=str_replace("-","/",$_POST["alcanceFechaReporta"]);
$alcanceFechaHoraReporta=$alcanceFechaReporta." ".$_POST["alcanceHoraQueReportaval"].":00";

if(!@$_POST["alcanceLocalidad"])
$alcanceLocalidad=0;
else
$alcanceLocalidad=$_POST["alcanceLocalidad"];

if(!@$_POST["alcanceOtroLugar"])
$alcanceOtroLugar="-";
else
$alcanceOtroLugar=$_POST["alcanceOtroLugar"];

$alcanceFechaFenomeno=str_replace("-","/",$_POST["alcanceFechaFenomeno"]);
$alcanceFechaHoraFenomeno=$alcanceFechaFenomeno." ".$_POST["alcanceHoraInicialFenomenoval"].":00";



if(@$_POST["alcanceRespuestaInstitucional"]){
	$alcanceInstitucionesLista="";
	$alcanceInstituciones=$_POST["alcanceRespuestaInstitucional"]; 
	for ($i=0;$i<count($alcanceInstituciones);$i++)    
	{     
		if($i){$alcanceInstitucionesLista=$alcanceInstitucionesLista.",".$alcanceInstituciones[$i];}else{
			$alcanceInstitucionesLista=$alcanceInstituciones[$i];
		}
	}
	$firephp->log("Respuesta institucional: ".$alcanceInstitucionesLista."<br/>");
}


if(@$_POST["alcanceAutores"]){
	$alcanceAutoresLista=$_POST["alcanceAutores"];
	$firephp->log("Autores: ".$alcanceAutoresLista."<br/>");
}


	$sql = "SELECT TO_CHAR(FECHA_REPORTE, 'YYYY/MM/DD hh24:mm:ss') FROM CENACOM.REPORTES WHERE CR=".$_POST["crRegistrado"];;
	$stmt=oci_parse($conOracle,$sql);
	oci_execute($stmt, OCI_DEFAULT);
	while ($raw = oci_fetch_array($stmt, OCI_ASSOC+OCI_RETURN_NULLS)) {
		foreach ($raw as $item) {
			$fechaReporte=$item;
		}
	}

$alcanceFechaHoraReportaSQL="to_date('".$alcanceFechaHoraReporta."','yyyy/mm/dd hh24:mi:ss')"; 
$alcanceFechaHoraFenomenoSQL="to_date('".$alcanceFechaHoraFenomeno."','yyyy/mm/dd hh24:mi:ss')";  
$fechaReporteSQL="to_date('".$fechaReporte."','yyyy/mm/dd hh24:mi:ss')";


    $cr_in=$_POST["crRegistrado"];
    $ID_REPORTE=$_POST["crRegistrado"];
    $FECHA_REPORTE=$fechaReporteSQL;
    $EFECTO_ADVERSO=$_POST["alcanceEfectoAdverso"];
    $FECHA_AVISO=$alcanceFechaHoraReportaSQL;
    $ORGANISMO_AVISO=$_POST["alcanceOrganismoReporta"];
    $AREAS_AFECTADAS=$_POST["alcanceAreasAfectadas"];
    $PERSONAS_AFECTADAS=$_POST["alcancePersonasAfectadas"];
    $MUERTOS=$_POST["alcanceMuertos"];
    $LESIONADOS=$_POST["alcanceLesionados"];
    $DESAPARECIDOS=$_POST["alcanceDesaparecidos"];
    $EVACUADOS=$_POST["alcanceEvacuados"];
    $LINEAS_VITALES=$_POST["alcanceLineasVitales"];
    $INFRAESTRUCTURA_DANADA=$_POST["alcanceInfraestructura"];
    $OBSERVACIONES=$_POST["alcanceObservaciones"];
    $RESPUESTA_INSTITUCIONAL=$alcanceInstitucionesLista;
    $LINK=$_POST["alcanceLinks"];
    $ID_USUARIO=$alcanceAutoresLista;
    $ID_EVENTO=$_POST["idEvento"];
    $ID_TIPO_REPORTE=$_POST["tipoReporte"];
    $CLASIFICACIONFENOMENO_ID=$_POST["alcanceClasificacionFenomeno"];
    $ESTADO=$_POST["alcanceEstado"];
    $MUNICIPIO=$_POST["alcanceMunicipio"];
    $LOCALIDAD=$alcanceLocalidad;
    $X='0';
    $Y='0';
    $TURNO=$_POST["alcanceAutoresTurno"];
    $TIPOFENOMENO_ID=$_POST["alcanceTipoFenomeno"];
    $OTRO_LUGAR=$alcanceOtroLugar;
    $CR_RELACIONADO=$_POST["crRelacionado"];
    $FECHA_INICIO_FENOMENO=$alcanceFechaHoraFenomenoSQL;
    $FECHA_FINAL_FENOMENO=$alcanceFechaHoraFenomenoSQL;
    $LINK_TITULO=$_POST["alcanceTituloLinks"];

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
	$alcanceInstitucionesListaEvento=eliminaCaracteresRepetidos($alcanceInstitucionesLista.",".$institucionesListaEvento);

	$sql01="UPDATE CENACOM.EVENTO SET NOMBRE_EVENTO='alcance', DANOS_MATERIALES="."'".$_POST["alcanceDanosMaterialesEvento"]."', DEPENDENCIAS_PARTICIPANTES='".$alcanceInstitucionesListaEvento."',"." OBSERVACIONES='".$_POST["alcanceObservacionesEvento"]."', DECLARATORIA ='".$_POST["alcanceDeclaratoria"]."' ";
	$sql02="WHERE ID_EVENTO=".$_POST["idEvento"];
	$sql0=$sql01.$sql02;

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
	


if($resultadoEnOracleReporte){
	echo '<h3>Reporte '.$_POST["crRegistrado"].' Actualizado</h3>';
	echo 'Los datos <b>se actualizaron correctamente</b> en la base de datos. <br/>';
	echo 'Archivo de Microsoft Word listo para descarga: <a href="visual_download.php?cr='.$_POST["crRegistrado"].'"> Clic para descargar </a> <br/>';
}else{
	echo 'Hubo un error con la actualizaci&oacute;n en la base de datos. <br/>';	
}

/***************************** ACTUALIZA LA CLAVE DEL LUGAR *******************/

$lugar=cadenaLugar($_POST["alcanceEstado"], $_POST["alcanceMunicipio"], $alcanceLocalidad);
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