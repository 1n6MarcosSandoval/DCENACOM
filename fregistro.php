<?php
include 'functions.php';
include 'vars.php';
include 'xmlCreator.php';
include 'enviaremail.php';
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


$FechaReporta=str_replace("-","/",$_POST["FechaReporta"]);
$FechaHoraReporta=$FechaReporta." ".$_POST["HoraQueReportaval"].":00";


if(!@$_POST["Localidad"])
$Localidad=0;
else
$Localidad=$_POST["Localidad"];
if(!@$_POST["OtroLugar"])
$OtroLugar="-";
else
$OtroLugar=$_POST["OtroLugar"];
$FechaFenomeno=str_replace("-","/",$_POST["FechaFenomeno"]);
$FechaHoraFenomeno=$inicialFechaFenomeno." ".$_POST["HoraInicialFenomenoval"].":00";

if(@$_POST["RespuestaInstitucional"]){
	$InstitucionesLista="";
	$Instituciones=$_POST["RespuestaInstitucional"]; 
	for ($i=0;$i<count($Instituciones);$i++)    
	{     
		if($i){$InstitucionesLista=$InstitucionesLista.",".$Instituciones[$i];}else{
			$InstitucionesLista=$Instituciones[$i];
		}
	}
}

if(@$_POST["Autores"]){
	$inicialAutoresLista=$_POST["Autores"];
	$firephp->log("Autores: ".$inicialAutoresLista."<br/>");
}
$fechaReporte=date('Y/m/d').' '.date('H:i').":00";

$query = oci_parse($conOracle, 'SELECT MAX(CR) AS VALOR FROM CENACOM.REPORTES');
oci_execute($query);
$row = oci_fetch_array($query, OCI_ASSOC);
$CRregistrado=@$row["VALOR"]+1;

$query = oci_parse($conOracle, 'SELECT MAX(ID_REPORTE) AS VALOR FROM CENACOM.REPORTES');
oci_execute($query);
$row = oci_fetch_array($query, OCI_ASSOC);
$idReporte=@$row["VALOR"]+1;

$query = oci_parse($conOracle, 'SELECT MAX(ID_EVENTO) AS VALOR FROM CENACOM.EVENTO');
oci_execute($query);
$row = oci_fetch_array($query, OCI_ASSOC);
$idEvento=@$row["VALOR"]+1;

?>

<?php
//ESCRIBE EN LA BASE DE DATOS

$fechaReporteSQL="to_date('".$fechaReporte."','yyyy/mm/dd hh24:mi:ss')";
$FechaHoraReportaSQL="to_date('".$FechaHoraReporta."','yyyy/mm/dd hh24:mi:ss')";
$FechaHoraFenomenoSQL="to_date('".$FechaHoraFenomeno."','yyyy/mm/dd hh24:mi:ss')";  

//Obtener Usuario
$sqlUsuario = "select NOMBRE_REP from cenacom.ADMINISTRADORES where USUARIO = '".$_SESSION['nombre']."'";
$stid = oci_parse($conOracle,$sqlUsuario);
oci_execute($stid);
while ($row = oci_fetch_array($stid, OCI_ASSOC)) {
	$UsuarioRegistra = $row["NOMBRE_REP"];
}
//Fin Usuario

//ESCRIBE QUERY DEL EVENTO
$sql01="INSERT INTO CENACOM.EVENTO(ID_EVENTO,NOMBRE_EVENTO,ESTADO_EVENTO,FECHA_INICIO,FECHA_FIN,DANOS_MATERIALES,DEPENDENCIAS_PARTICIPANTES, OBSERVACIONES, DECLARATORIA, NIVEL)";
$sql02="VALUES(";
$sql04=")";
$sql03=$idEvento.","."'INICIAL'".","."'1'".",".$FechaHoraReportaSQL.",".$FechaHoraReportaSQL.",'".$_POST["DanosMaterialesEvento"]."','".$InstitucionesLista."','".$_POST["Observaciones"]."','".$_POST["DECLARATORIA"]."','".$_POST["Nivel"]."'";

$sql0=$sql01.$sql02.$sql03.$sql04;
//echo $sql0;

//ESCRIBE QUERY DEL REPORTE
$sql1="INSERT INTO CENACOM.REPORTES(ID_REPORTE, CR, FECHA_REPORTE, EFECTO_ADVERSO, FECHA_AVISO, ORGANISMO_AVISO, AREAS_AFECTADAS, PERSONAS_AFECTADAS, MUERTOS, LESIONADOS, DESAPARECIDOS, EVACUADOS, LINEAS_VITALES, INFRAESTRUCTURA_DANADA, OBSERVACIONES, RESPUESTA_INSTITUCIONAL, LINK, ID_USUARIO, ID_EVENTO, ID_TIPO_REPORTE, CLASIFICACIONFENOMENO_ID, ESTADO, MUNICIPIO, LOCALIDAD, TURNO, TIPOFENOMENO_ID, OTRO_LUGAR, CR_RELACIONADO, FECHA_INICIO_FENOMENO, LINK_TITULO, NIVEL, AUTOR, SELECCION, ATENDIDO,FENOMENOMAYORAFECTACION,TIPOFENOMENOMAYORAFECTACION)";
$sql2="VALUES(";
$sql4=")";
$sql31=$idReporte.",".$CRregistrado.",".$fechaReporteSQL.",'".$_POST["inicialEfectoAdverso"]."',".$inicialFechaHoraReportaSQL.",'";
$sql32=$_POST["inicialOrganismoReporta"]."','".$_POST["inicialAreasAfectadas"]."','".$_POST["inicialPersonasAfectadas"]."',";
$sql33=$_POST["inicialMuertos"].",".$_POST["inicialLesionados"].",".$_POST["inicialDesaparecidos"].",".$_POST["inicialEvacuados"].",'";
$sql34=$_POST["inicialLineasVitales"]."','".$_POST["inicialInfraestructura"]."','".$_POST["inicialObservaciones"]."' ,'";
$sql35=$inicialInstitucionesLista."','".$_POST["inicialLinks"]."','".$_SESSION['nombre']."',".$idEvento.",".$_POST["tipoReporte"].",'".$_POST["inicialClasificacionFenomeno"]."',";
$sql36=$_POST["Estado0"].",".$_POST["Municipio0"].",".$inicialLocalidad.",";
$sql37=$_POST["inicialAutoresTurno"].",".$_POST["inicialTipoFenomeno"].",'".$inicialOtroLugar."',"."0".",".$inicialFechaHoraFenomenoSQL.",'".$_POST["inicialTituloLinks"]."','".$_POST["inicialNivel"]."','".$_POST["AUTOR"]."','".$_POST["SELECCION"]."',".$_POST["ATENDIDO"].",'".$_POST["FENOMENOMAYORAFECTACION"]."','".$_POST["TIPOFENOMENOMAYORAFECTACION"]."'";
$sql3=$sql31.$sql32.$sql33.$sql34.$sql35.$sql36.$sql37;
$sql=$sql1.$sql2.$sql3.$sql4;
//echo $sql;

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

//Escribe en la BD GEOR si se capturo el reporte

//*****Guarda los estados en la para BD.GEOR****
$PostF = array ();
$Insertar = array ();
	foreach($_POST as $nombre_campo => $valor){
			$PostF[$nombre_campo] = $valor;
	}		
	for($contador = 0;$contador<=(count($PostF)-39);$contador++){
	
		if(isset($_POST["Estado".$contador])){
	
			$sql101 = "INSERT INTO CENACOM.GEOR(ID_REPORTE,ENTIDADES,MUNS,LOCS, ID_GEOR)";
			$sql102 = "VALUES(";
			$sql104 = ")";
			$sql103 = $idReporte.",'".$_POST["Estado".$contador]."','".$_POST["Municipio".$contador]."','".$_POST["Localidad".$contador]."',".$contador;
			$sql100 = $sql101.$sql102.$sql103.$sql104;
			//echo $sql100;

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
	/* FIN ACTUALIZA LA BD */	
/************************* FIN ACTUALIZA LA CLAVE DEL LUGAR*******************/


if($resultadoEnOracleEvento && $resultadoEnOracleReporte){
	echo '<h3>Reporte '.$CRregistrado.' registrado</h3>';
	echo 'Los datos se registraron correctamente en la base de datos. <br/>';
	echo 'En el <a href="lista.php"> Listado de Reportes</a> se puede descargar el reporte en PDF, en Word y los archivos adjuntos a este reporte';
}else{
	echo 'Hubo un error en el registro con la base de datos. <br/>';	
}

$ID_REPORTEF = $CRregistrado;
		/*XML*/
			//CrearGeorss();
		/*Fin XML*/
		/*Email*/
			mandarEmail();
		/*Fin Email*/

?>

<?php
	cerrarConexionORACLE($conOracle);
?>
<form method="post" id="CargarImagen" name="CargarImagen" action="CargarImagen.php">
<input type = "hidden" name = "ID_REPORTEF" id = "ID_REPORTEF" value = "<?php echo $ID_REPORTEF; ?>">
<input type="submit" value="Cargar Archivo" name="CargarImagen"/>
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