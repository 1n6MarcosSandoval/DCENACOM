<?php
include 'functions.php';
include 'vars.php';
include 'xml\CreatorXML.php';
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

$inicialFechaReporta=str_replace("-","/",$_POST["inicialFechaReporta"]);
$inicialFechaHoraReporta=$inicialFechaReporta." ".$_POST["inicialHoraQueReportaval"].":00";

if(!@$_POST["inicialLocalidad"])
$inicialLocalidad=0;
else
$inicialLocalidad=$_POST["inicialLocalidad"];

if(!@$_POST["inicialOtroLugar"])
$inicialOtroLugar="-";
else
$inicialOtroLugar=$_POST["inicialOtroLugar"];

$inicialFechaFenomeno=str_replace("-","/",$_POST["inicialFechaFenomeno"]);
$inicialFechaHoraFenomeno=$inicialFechaFenomeno." ".$_POST["inicialHoraInicialFenomenoval"].":00";



if(@$_POST["inicialRespuestaInstitucional"]){
	$inicialInstitucionesLista="";
	$inicialInstituciones=$_POST["inicialRespuestaInstitucional"]; 
	for ($i=0;$i<count($inicialInstituciones);$i++)    
	{     
		if($i){$inicialInstitucionesLista=$inicialInstitucionesLista.",".$inicialInstituciones[$i];}else{
			$inicialInstitucionesLista=$inicialInstituciones[$i];
		}
	}
	$firephp->log("Respuesta institucional: ".$inicialInstitucionesLista."<br/>");
}


if(@$_POST["inicialAutores"]){
	$inicialAutoresLista=$_POST["inicialAutores"];
	$firephp->log("Autores: ".$inicialAutoresLista."<br/>");
}


	$sql = "SELECT TO_CHAR(FECHA_REPORTE, 'YYYY/MM/DD hh24:mm:ss') FROM CENACOM.REPORTES WHERE CR=".$_POST["crRegistrado"];;
	$stmt=oci_parse($conOracle,$sql);
	oci_execute($stmt, OCI_DEFAULT);
	while ($raw = oci_fetch_array($stmt, OCI_ASSOC+OCI_RETURN_NULLS)) {
		foreach ($raw as $item) {
			$fechaReporte=$item;
		}
	}

$inicialFechaHoraReportaSQL="to_date('".$inicialFechaHoraReporta."','yyyy/mm/dd hh24:mi:ss')"; 
$inicialFechaHoraFenomenoSQL="to_date('".$inicialFechaHoraFenomeno."','yyyy/mm/dd hh24:mi:ss')";  
$fechaReporteSQL="to_date('".$fechaReporte."','yyyy/mm/dd hh24:mi:ss')";
	

    $cr_in=$_POST["crRegistrado"];
    $ID_REPORTE=$_POST["crRegistrado"];
    $FECHA_REPORTE=$fechaReporteSQL;
    $EFECTO_ADVERSO=$_POST["inicialEfectoAdverso"];
    $FECHA_AVISO=$inicialFechaHoraReportaSQL;
    $ORGANISMO_AVISO=$_POST["inicialOrganismoReporta"];
    $AREAS_AFECTADAS=$_POST["inicialAreasAfectadas"];
    $PERSONAS_AFECTADAS=$_POST["inicialPersonasAfectadas"];
    $MUERTOS=$_POST["inicialMuertos"];
    $LESIONADOS=$_POST["inicialLesionados"];
    $DESAPARECIDOS=$_POST["inicialDesaparecidos"];
    $EVACUADOS=$_POST["inicialEvacuados"];
    $LINEAS_VITALES=$_POST["inicialLineasVitales"];
    $INFRAESTRUCTURA_DANADA=$_POST["inicialInfraestructura"];
    $OBSERVACIONES=$_POST["inicialObservaciones"];
    $RESPUESTA_INSTITUCIONAL=$inicialInstitucionesLista;
    $LINK=$_POST["inicialLinks"];
    $ID_USUARIO=$inicialAutoresLista;
    $ID_EVENTO=$_POST["idEvento"];
    $ID_TIPO_REPORTE=$_POST["tipoReporte"];
    $CLASIFICACIONFENOMENO_ID=$_POST["inicialClasificacionFenomeno"];
    $ESTADO=$_POST["inicialEstado"];
    $MUNICIPIO=$_POST["inicialMunicipio"];
    $LOCALIDAD=$inicialLocalidad;
    $X='0';
    $Y='0';
    $TURNO=$_POST["inicialAutoresTurno"];
    $TIPOFENOMENO_ID=$_POST["inicialTipoFenomeno"];
    $OTRO_LUGAR=$inicialOtroLugar;
    $CR_RELACIONADO=$_POST["crRelacionado"];
    $FECHA_INICIO_FENOMENO=$inicialFechaHoraFenomenoSQL;
    $FECHA_FINAL_FENOMENO=$inicialFechaHoraFenomenoSQL;
    $LINK_TITULO=$_POST["inicialTituloLinks"];
	$NIVEL = $_POST ["NIVEL"];
	$ATENDIDO = $_POST ["ATENDIDO"];

	$query1="UPDATE CENACOM.REPORTES SET ID_REPORTE=".$ID_REPORTE.", FECHA_REPORTE=".$FECHA_REPORTE.", EFECTO_ADVERSO='".$EFECTO_ADVERSO."', ";
	$query2="FECHA_AVISO=".$FECHA_AVISO.", ORGANISMO_AVISO='".$ORGANISMO_AVISO."', AREAS_AFECTADAS='".$AREAS_AFECTADAS."', ";
	$query3="PERSONAS_AFECTADAS='".$PERSONAS_AFECTADAS."', MUERTOS=".$MUERTOS.", LESIONADOS=".$LESIONADOS.", DESAPARECIDOS=".$DESAPARECIDOS.", EVACUADOS=".$EVACUADOS.", ";
	$query4="LINEAS_VITALES='".$LINEAS_VITALES."', INFRAESTRUCTURA_DANADA='".$INFRAESTRUCTURA_DANADA."', OBSERVACIONES='".$OBSERVACIONES."', RESPUESTA_INSTITUCIONAL='".$RESPUESTA_INSTITUCIONAL."', ";
	$query5="LINK='".$LINK."', ID_USUARIO='".$ID_USUARIO."', ID_EVENTO=".$ID_EVENTO.", ID_TIPO_REPORTE=".$ID_TIPO_REPORTE.", ";
	$query6="CLASIFICACIONFENOMENO_ID='".$CLASIFICACIONFENOMENO_ID."', ESTADO=".$ESTADO.", MUNICIPIO=".$MUNICIPIO.", LOCALIDAD=".$LOCALIDAD.", ";
	$query7="X=".$X.", Y=".$Y.", TURNO=".$TURNO.", TIPOFENOMENO_ID=".$TIPOFENOMENO_ID.", OTRO_LUGAR='".$OTRO_LUGAR."', ";
	$query8="CR_RELACIONADO=".$CR_RELACIONADO.", FECHA_INICIO_FENOMENO=".$FECHA_INICIO_FENOMENO.", FECHA_FINAL_FENOMENO=".$FECHA_FINAL_FENOMENO.", ";
	$query9="LINK_TITULO='".$LINK_TITULO;
	
	$query10 = "NIVEL=".$NIVEL." ATENDIDO=".$ATENDIDO." WHERE CR = ".$cr_in;
	$sql=$query1.$query2.$query3.$query4.$query5.$query6.$query7.$query8.$query9;
	//print "<br/>".$sql."<br/>";
	
	$sqlE0="UPDATE CENACOM.REPORTES SET FECHA_INICIO_FENOMENO=".$FECHA_INICIO_FENOMENO.", ESTADO=".$ESTADO.", MUNICIPIO=".$MUNICIPIO.", LOCALIDAD=".$LOCALIDAD.", ";
	$sqlE1="TIPOFENOMENO_ID=".$TIPOFENOMENO_ID.", OTRO_LUGAR='".$OTRO_LUGAR."', ";
	$sqlE2="CLASIFICACIONFENOMENO_ID='".$CLASIFICACIONFENOMENO_ID."' WHERE ID_EVENTO=".$_POST["idEvento"];
	$sqlE=$sqlE0.$sqlE1.$sqlE2;
	
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

	//Actualiza las fechas en los reportes relacionados al evento
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


if($resultadoEnOracleReporte&&$resultadoEnOracleFecha){
	echo '<h3>Reporte '.$_POST["crRegistrado"].' Actualizado</h3>';
	echo 'Los datos <b>se actualizaron correctamente</b> en la base de datos. <br/>';
}else{
	echo 'Hubo un error con la actualizaci&oacute;n en la base de datos. <br/>';	
}

//Escribe en la BD GEOR si se capturo el reporte

//*****Guarda los estados en la para BD.GEOR****
$PostF = array ();
$Insertar = array ();
	foreach($_POST as $nombre_campo => $valor){
			$PostF[$nombre_campo] = $valor;
	}		
	//$conta = count($PostF);
	//echo $conta;
	for($contador = 0;$contador<=(count($PostF)-39);$contador++){
	
		if(isset($_POST["Estado".$contador])){
	
			$sql101 = "INSERT INTO CENACOM.GEOR(ID_REPORTE,ENTIDADES,MUNS,LOCS, ID_GEOR)";
			$sql102 = "VALUES(";
			$sql104 = ")";
			$sql103 = $idReporte.",'".$_POST["Estado".$contador]."','".$_POST["Municipio".$contador]."','".$_POST["Localidad".$contador]."',".$contador;
			$sql100 = $sql101.$sql102.$sql103.$sql104;
			//echo $sql100;
			//ARRAY_PUSH($Insertar,$sql100);
			
			//print_r ($Insertar);

			if($resultadoEnOracleReporte){

				$escribeOracleGeoR=oci_parse($conOracle,$sql100);
				if (!$escribeOracleGeoR) {
					$e = oci_error($conOracle);
					trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
				}
				$resultadoEnOracleGeoR=oci_execute($escribeOracleGeoR);
				if (!$resultadoEnOracleGeoR) {
					$e = oci_error($escribeOracleGeoR);
					trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
				}
	
			}
		}
	}
/***Fin guarda los estados en la para BD.GEOR***/


/***************************** ACTUALIZA LA CLAVE DEL LUGAR *******************/
$PostAE = array ();
$InsertarAE = array ();

	foreach($_POST as $nombre_campo => $valor){
			$PostAE[$nombre_campo] = $valor;
	}		
	//$conta = count($PostAE);
	//echo $conta;
	for($contadorAE = 0;$contadorAE<=(count($PostAE)-39);$contadorAE++){
	
		if(isset($_POST["Estado".$contadorAE])){
			$lugar=cadenaLugar($_POST["Estado".$contadorAE], $_POST["Municipio".$contadorAE], $_POST["Localidad".$contadorAE]);
			//echo $lugar;

			$queryEdo= "UPDATE CENACOM.GEOR SET CLAVE_LUGAR='".$lugar."' WHERE ID_REPORTE = ".$CRregistrado." AND ID_GEOR=".$contadorAE;
			//echo $queryEdo;
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
		}
	}
/************************* FIN ACTUALIZA LA CLAVE DEL LUGAR*******************/	

cerrarConexionORACLE($conOracle);
?>
			</div>
		</div>

<?php
$ID_REPORTEF = $CRregistrado;
		/*XML*/
			CrearGeorss();
		/*Fin XML*/
		/*Email*/
			//mandarEmail();
		/*Fin Email*/


	cerrarConexionORACLE($conOracle);
?>
		
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