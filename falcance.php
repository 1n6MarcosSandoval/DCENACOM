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
//print "Tipo de reporte: ".$_POST["tipoReporte"]."<br/>";

//if(@$_POST["crRelacionado"])
//print "CR relacionado: ".$_POST["crRelacionado"]."<br/>";

//print "Efecto Adverso: ".$_POST["alcanceEfectoAdverso"]."<br/>";

//print "Organismo que reporta: ".$_POST["alcanceOrganismoReporta"]."<br/>";
$alcanceFechaReporta=str_replace("-","/",$_POST["alcanceFechaReporta"]);
$alcanceFechaHoraReporta=$alcanceFechaReporta." ".$_POST["alcanceHoraQueReportaval"].":00";
//print "Fecha y hora que reporta: ".$alcanceFechaHoraReporta."<br/>";

//print "Clasificacion fenomeno: ".$_POST["alcanceClasificacionFenomeno"]."<br/>";

//print "Tipo fenomeno: ".$_POST["alcanceTipoFenomeno"]."<br/>";
$alcanceFechaFenomeno=str_replace("-","/",$_POST["alcanceFechaFenomeno"]);
$alcanceFechaHoraFenomeno=$alcanceFechaFenomeno." ".$_POST["alcanceHoraInicialFenomenoval"].":00";
//print "Fecha y hora del fenomeno: ".$alcanceFechaHoraFenomeno."<br/>";

if(!@$_POST["alcanceOtroLugar"])
$alcanceOtroLugar="-";
else
$alcanceOtroLugar=$_POST["alcanceOtroLugar"];

if(@$_POST["alcanceRespuestaInstitucional"]){
	$alcanceInstitucionesLista="";
	$alcanceInstituciones=$_POST["alcanceRespuestaInstitucional"]; 
	for ($i=0;$i<count($alcanceInstituciones);$i++)    
	{     
	//print "Respuesta institucional " . $i . ": " . $alcanceInstituciones[$i];
		//print "Respuesta institucional: ". $alcanceInstituciones[$i]."<br/>";
		if($i){$alcanceInstitucionesLista=$alcanceInstitucionesLista.",".$alcanceInstituciones[$i];}else{
			$alcanceInstitucionesLista=$alcanceInstituciones[$i];
		}
	}
	//print "Respuesta institucional: ".$alcanceInstitucionesLista."<br/>";
}


if(@$_POST["alcanceAutores"]){
	$alcanceAutoresLista=$_POST["alcanceAutores"];
	$firephp->log("Autores: ".$alcanceAutoresLista."<br/>");
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
$alcanceFechaHoraReportaSQL="to_date('".$alcanceFechaHoraReporta."','yyyy/mm/dd hh24:mi:ss')"; 
$alcanceFechaHoraFenomenoSQL="to_date('".$alcanceFechaHoraFenomeno."','yyyy/mm/dd hh24:mi:ss')";

$institucionesListaEvento=obtenerValorQuery($conOracle,'CENACOM', 'EVENTO', 'ID_EVENTO', $idEvento, 'DEPENDENCIAS_PARTICIPANTES');
$alcanceInstitucionesListaEvento=eliminaCaracteresRepetidos($alcanceInstitucionesLista.",".$institucionesListaEvento);
//echo '<br/><br/>';
//echo $alcanceInstitucionesListaEvento;

//Obtener Usuario

$sqlUsuario = "select NOMBRE_REP from cenacom.ADMINISTRADORES where USUARIO = '".$_SESSION['nombre']."'";
$stid = oci_parse($conOracle,$sqlUsuario);
oci_execute($stid);
while ($row = oci_fetch_array($stid, OCI_ASSOC)) {
	$UsuarioRegistra = $row["NOMBRE_REP"];
}
//Fin Usuario

$sql01="UPDATE CENACOM.EVENTO SET NOMBRE_EVENTO='ALCANCE', ESTADO_EVENTO=0, DANOS_MATERIALES="."'".$_POST["alcanceDanosMaterialesEvento"]."', DEPENDENCIAS_PARTICIPANTES='".$alcanceInstitucionesListaEvento."',"." OBSERVACIONES='".$_POST["alcanceObservacionesEvento"]."', DECLARATORIA ='".$_POST["alcanceDeclaratoria"]."', NIVEL = '".$_POST["NIVEL"]."'";
$sql02="WHERE ID_EVENTO=".$idEvento;
$sql0=$sql01.$sql02;
 
 
 
/*
echo $sql0;
*/

$sql1="INSERT INTO CENACOM.REPORTES(ID_REPORTE, CR, FECHA_REPORTE, EFECTO_ADVERSO, FECHA_AVISO, ORGANISMO_AVISO, AREAS_AFECTADAS, PERSONAS_AFECTADAS, MUERTOS, LESIONADOS, DESAPARECIDOS, EVACUADOS, LINEAS_VITALES, INFRAESTRUCTURA_DANADA, OBSERVACIONES, RESPUESTA_INSTITUCIONAL, LINK, ID_USUARIO, ID_EVENTO, ID_TIPO_REPORTE, CLASIFICACIONFENOMENO_ID, ESTADO, MUNICIPIO, LOCALIDAD, TIPOFENOMENO_ID, OTRO_LUGAR, CR_RELACIONADO, FECHA_INICIO_FENOMENO, FECHA_FINAL_FENOMENO,LINK_TITULO, NIVEL, AUTOR, SELECCION, ATENDIDO,FENOMENOMAYORAFECTACION,TIPOFENOMENOMAYORAFECTACION,AUTO_QUITAR,VISIBLE)";
$sql2="VALUES(";
$sql4=")";
$sql31=$idReporte." ,".$CRregistrado." ,".$fechaReporteSQL.",'".$_POST["alcanceEfectoAdverso"]."' ,".$alcanceFechaHoraReportaSQL." ,'";
$sql32=$_POST["alcanceOrganismoReporta"]."' ,'".$_POST["alcanceAreasAfectadas"]."' ,'".$_POST["alcancePersonasAfectadas"]."' ,";
$sql33=$_POST["alcanceMuertos"]." ,".$_POST["alcanceLesionados"]." ,".$_POST["alcanceDesaparecidos"]." ,".$_POST["alcanceEvacuados"]." ,'";
$sql34=$_POST["alcanceLineasVitales"]."' ,'".$_POST["alcanceInfraestructura"]."','".$_POST["alcanceObservaciones"]."' ,'";
$sql35=$alcanceInstitucionesLista."' ,'".$_POST["alcanceLinks"]."' ,'".$UsuarioRegistra."',".$idEvento." ,".$_POST["tipoReporte"].",'".$_POST["alcanceClasificacionFenomeno"]."',";
$sql36=$_POST["Estado0"].",".$_POST["Municipio0"].",".$_POST["Localidad0"].",";
$sql37=$_POST["alcanceTipoFenomeno"].",'".$alcanceOtroLugar."' ,".$_POST["crRelacionado"]." ,".$alcanceFechaHoraFenomenoSQL." ,".$alcanceFechaHoraFenomenoSQL.", '".$_POST["alcanceTituloLinks"]."','";
$sql38 = $_POST["NIVEL"]."','".$_POST["AUTOR"]."','".$_POST["SELECCION"]."',".$_POST["ATENDIDO"].",'".$_POST["FENOMENOMAYORAFECTACION"]."','".$_POST["TIPOFENOMENOMAYORAFECTACION"]."',".$_POST["AUTO_QUITAR"].",1";
$sql3=$sql31.$sql32.$sql33.$sql34.$sql35.$sql36.$sql37.$sql38; 
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
			$sql103 = $idReporte.",'".$_POST["Estado".$contador]."','".$_POST["Municipio".$contador]."','".$_POST["Localidad".$contador]."',".$contador.",".$_POST["NIVEL"];
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
	echo 'Los datos se registraron correctamente en la base de datos. <br/>';
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
	$observaciones = $_POST["alcanceObservaciones"];
	$EfectoAdverso = $_POST["alcanceEfectoAdverso"];
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

	<?php
		include 'derecha0.php';
	?>
		<div class="footer">
			<p>Sistema Nacional de Protecci&oacute;n Civil</p>
		</div>
	</div>
</body>
</html>