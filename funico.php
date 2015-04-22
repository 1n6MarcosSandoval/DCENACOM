<?php
include 'functions.php';
include 'vars.php';
include '\XML\CreatorXML.php';
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

$unicoFechaReporta=str_replace("-","/",$_POST["unicoFechaReporta"]);
$unicoFechaHoraReporta=$unicoFechaReporta." ".$_POST["unicoHoraQueReportaval"].":00";

if(!@$_POST["unicoLocalidad"])
$unicoLocalidad=0;
else
$unicoLocalidad=$_POST["unicoLocalidad"];

if(!@$_POST["otroslugares"])
$unicoOtroLugar="-";
else
$unicoOtroLugar=$_POST["otroslugares"];

$unicoFechaFenomeno=str_replace("-","/",$_POST["unicoFechaFenomeno"]);
$unicoFechaHoraFenomeno=$unicoFechaFenomeno." ".$_POST["unicoHoraInicialFenomenoval"].":00";


if(@$_POST["unicoRespuestaInstitucional"]){
	$unicoInstitucionesLista="";
	$unicoInstituciones=$_POST["unicoRespuestaInstitucional"]; 
	for ($i=0;$i<count($unicoInstituciones);$i++)    
	{     

		if($i){$unicoInstitucionesLista=$unicoInstitucionesLista.",".$unicoInstituciones[$i];}else{
			$unicoInstitucionesLista=$unicoInstituciones[$i];
		}
	}
	$firephp->log("Respuesta institucional: ".$unicoInstitucionesLista."<br/>");
}


if(@$_POST["unicoAutores"]){
	$unicoAutoresLista=$_POST["unicoAutores"];
	$firephp->log("Autores: ".$unicoAutoresLista."<br/>");
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

$fechaReporteSQL="to_date('".$fechaReporte."','yyyy/mm/dd hh24:mi:ss')";
$unicoFechaHoraReportaSQL="to_date('".$unicoFechaHoraReporta."','yyyy/mm/dd hh24:mi:ss')"; 
$unicoFechaHoraFenomenoSQL="to_date('".$unicoFechaHoraFenomeno."','yyyy/mm/dd hh24:mi:ss')";  

//Obtener Usuario

$sqlUsuario = "select NOMBRE_REP from cenacom.ADMINISTRADORES where USUARIO = '".$_SESSION['nombre']."'";
$stid = oci_parse($conOracle,$sqlUsuario);
oci_execute($stid);
while ($row = oci_fetch_array($stid, OCI_ASSOC)) {
	$UsuarioRegistra = $row["NOMBRE_REP"];
}
//Fin Usuario


//ESCRIBE EN LA BASE DE DATOS INSERT

$sql01="INSERT INTO CENACOM.EVENTO(ID_EVENTO,NOMBRE_EVENTO,ESTADO_EVENTO,FECHA_INICIO,FECHA_FIN,DANOS_MATERIALES,DEPENDENCIAS_PARTICIPANTES, OBSERVACIONES, DECLARATORIA,NIVEL)";
$sql02="VALUES(";
$sql04=")";
$sql03=$idEvento.","."'UNICO'".","."'1'".",".$unicoFechaHoraReportaSQL.",".$unicoFechaHoraReportaSQL.",'".$_POST["unicoDanosMaterialesEvento"]."','".$unicoInstitucionesLista."','".$_POST["unicoObservaciones"]."','".$_POST["unicoDeclaratoria"]."','".$_POST["unicoNivel"]."'";
$sql0=$sql01.$sql02.$sql03.$sql04;
//echo $sql0;


$sql1="INSERT INTO CENACOM.REPORTES(ID_REPORTE, CR, FECHA_REPORTE, EFECTO_ADVERSO, FECHA_AVISO, ORGANISMO_AVISO, AREAS_AFECTADAS, PERSONAS_AFECTADAS, MUERTOS, LESIONADOS, DESAPARECIDOS, EVACUADOS, LINEAS_VITALES, INFRAESTRUCTURA_DANADA, OBSERVACIONES, RESPUESTA_INSTITUCIONAL, LINK, ID_USUARIO, ID_EVENTO, ID_TIPO_REPORTE, CLASIFICACIONFENOMENO_ID, ESTADO, MUNICIPIO, LOCALIDAD, TIPOFENOMENO_ID, OTRO_LUGAR, CR_RELACIONADO, FECHA_INICIO_FENOMENO, FECHA_FINAL_FENOMENO, LINK_TITULO, NIVEL, AUTOR, SELECCION, ATENDIDO,FENOMENOMAYORAFECTACION,TIPOFENOMENOMAYORAFECTACION,AUTO_QUITAR,VISIBLE)";
$sql2="VALUES(";
$sql4=")";
$sql31=$idReporte.",".$CRregistrado.",".$fechaReporteSQL.",'".$_POST["unicoEfectoAdverso"]."',".$unicoFechaHoraReportaSQL.",'";
$sql32=$_POST["unicoOrganismoReporta"]."' ,'".$_POST["unicoAreasAfectadas"]."' ,'".$_POST["unicoPersonasAfectadas"]."' ,";
$sql33=$_POST["unicoMuertos"]." ,".$_POST["unicoLesionados"]." ,".$_POST["unicoDesaparecidos"]." ,".$_POST["unicoEvacuados"]." ,'";
$sql34=$_POST["unicoLineasVitales"]."' ,'".$_POST["unicoInfraestructura"]."','".$_POST["unicoObservaciones"]."' ,'";
$sql35=$unicoInstitucionesLista."' ,'".$_POST["unicoLinks"]."' ,'".$UsuarioRegistra."',".$idEvento." ,".$_POST["tipoReporte"].",'";
$sql36=$_POST["unicoClasificacionFenomeno"]."',".$_POST["Estado0"].",".$_POST["Municipio0"].",".$_POST["Localidad0"].",";
$sql37=$_POST["unicoTipoFenomeno"].",'".$unicoOtroLugar."' ,"."0"." ,".$unicoFechaHoraFenomenoSQL." ,".$unicoFechaHoraFenomenoSQL.",'".$_POST["unicoTituloLinks"]."','".$_POST["unicoNivel"]."','".$_POST["AUTOR"]."','".$_POST["SELECCION"]."',".$_POST["ATENDIDO"].",'".$_POST["FENOMENOMAYORAFECTACION"]."','".$_POST["TIPOFENOMENOMAYORAFECTACION"]."',".$_POST["AUTO_QUITAR"].",1";
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
	
			$sql101 = "INSERT INTO CENACOM.GEOR(ID_REPORTE,ENTIDADES,MUNS,LOCS, ID_GEOR,NIVEL)";
			$sql102 = "VALUES(";
			$sql104 = ")";
			$sql103 = $idReporte.",'".$_POST["Estado".$contador]."','".$_POST["Municipio".$contador]."','".$_POST["Localidad".$contador]."',".$contador.",".$_POST["unicoNivel"];
			$sql100 = $sql101.$sql102.$sql103.$sql104;
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


			$queryEdo= "UPDATE CENACOM.GEOR SET CLAVE_LUGAR='".$lugar."' WHERE ID_REPORTE = ".$CRregistrado." AND ID_GEOR=".$contadorAE;
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
	echo 'Los datos <b>se registraron correctamente</b> en la base de datos. <br/>';
}else{
	echo 'Hubo un error en el registro con la base de datos. <br/>';	
}

?>
<form method="post" id="CargarImagen" name="CargarImagen" action="CargarImagen.php">
<input type = "hidden" name = "ID_REPORTEF" id = "ID_REPORTEF" value = "<?php echo $ID_REPORTEF; ?>">
<input type="submit" value="Cargar Archivo" name="CargarImagen"/>
			</div>
		</div>
<?php
$ID_REPORTEF = $CRregistrado;

/*Variables Correo*/
	$observaciones = $_POST["unicoObservaciones"];
	$EfectoAdverso = $_POST["unicoEfectoAdverso"];
	/*Recuperar Estado*/
		$queryE = oci_parse($conOracle, "SELECT NOM_ENT AS VALOR_ from ANRO.LOCALIDADES WHERE ENTIDAD = ".$_POST["Estado0"]." GROUP BY NOM_ENT");
		oci_execute($queryE);
			$row = oci_fetch_array($queryE, OCI_NUM);
			$estado = $row[0];
	/*Fin Recuperar Estado*/
	/*Recuperar MUNICIPIO*/
		$queryE = oci_parse($conOracle, "SELECT NOM_MUN AS VALOR_ from ANRO.LOCALIDADES WHERE MUN = ".$_POST["Estado0"]." GROUP BY NOM_MUN");
		oci_execute($queryE);
			$row = oci_fetch_array($queryE, OCI_NUM);	
			$municipio = $row[0];
	/*Fin Recuperar MUNICIPIO*//*Fin Variables Correo*/

		/*XML*/
			CrearGeorss();
		/*Fin XML*/
		/*Email*/
			mandarEmail($CRregistrado,$observaciones,$estado,$municipio,$EfectoAdverso);
		/*Fin Email*/

	cerrarConexionORACLE($conOracle);
?>

		<!-- FIN DE IZQUIERDA -->	
		include 'derecha0.php';
	?>
		<div class="footer">
			<p>Sistema Nacional de Protecci&oacute;n Civil</p>
		</div>
	</div>
</body>
</html>