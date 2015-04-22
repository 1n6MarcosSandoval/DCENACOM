<?php
include 'functions.php';
include 'vars.php';
session_start();
if(@$_SESSION['login'] != "si")
{
header("Location:index.php");
exit();
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

//print "Estado: ".$_POST["alcanceEstado"]."<br/>";
//if(@$_POST["alcanceMunicipio"])
//print "Municipio: ".$_POST["alcanceMunicipio"]."<br/>";
//if(@$_POST["alcanceLocalidad"])
if(!@$_POST["alcanceLocalidad"])
$alcanceLocalidad=0;
else
$alcanceLocalidad=$_POST["alcanceLocalidad"];
//print "Localidad: ".$alcanceLocalidad."<br/>";

if(!@$_POST["alcanceOtroLugar"])
$alcanceOtroLugar="-";
else
$alcanceOtroLugar=$_POST["alcanceOtroLugar"];
//print "Otro lugar: ".$alcanceOtroLugar."<br/>";

//print "Clasificacion fenomeno: ".$_POST["alcanceClasificacionFenomeno"]."<br/>";
//if(@$_POST["alcanceTipoFenomeno"]);
//print "Tipo fenomeno: ".$_POST["alcanceTipoFenomeno"]."<br/>";
$alcanceFechaFenomeno=str_replace("-","/",$_POST["alcanceFechaFenomeno"]);
$alcanceFechaHoraFenomeno=$alcanceFechaFenomeno." ".$_POST["alcanceHoraInicialFenomenoval"].":00";
//print "Fecha y hora del fenomeno: ".$alcanceFechaHoraFenomeno."<br/>";


//print "Observaciones: ".$_POST["alcanceObservaciones"]."<br/>"; 

//print "Areas afectadas: ".$_POST["alcanceAreasAfectadas"]."<br/>";
//print "Personas afectadas: ".$_POST["alcancePersonasAfectadas"]."<br/>";
//print "Muertos: ".$_POST["alcanceMuertos"]."<br/>";
//print "Lesionados: ".$_POST["alcanceLesionados"]."<br/>";
//print "Evacuados: ".$_POST["alcanceEvacuados"]."<br/>";
//print "Desaparecidos: ".$_POST["alcanceDesaparecidos"]."<br/>";

//print "L&iacute;neas vitales: ".$_POST["alcanceLineasVitales"]."<br/>";


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

//print "Links: ".$_POST["alcanceLinks"]."<br/>";

if(@$_POST["alcanceAutores"]){
	$alcanceAutoresLista="";
	$alcanceAutores=$_POST["alcanceAutores"]; 
	for ($i=0;$i<count($alcanceAutores);$i++)    
	{     
	//print "<br> Autores " . $i . ": " . $alcanceAutores[$i];
		//print "<br> Autor: ". $alcanceAutores[$i]."<br/>";
		if($i){$alcanceAutoresLista=$alcanceAutoresLista.",".$alcanceAutores[$i];}else{
			$alcanceAutoresLista=$alcanceAutores[$i];
		}	 
	} 
	//print "Autores: ".$alcanceAutoresLista."<br/>";
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

$sql01="UPDATE CENACOM.EVENTO SET NOMBRE_EVENTO='ALCANCE', ESTADO_EVENTO=0, DANOS_MATERIALES="."'".$_POST["alcanceDanosMaterialesEvento"]."', DEPENDENCIAS_PARTICIPANTES='".$alcanceInstitucionesListaEvento."',"." OBSERVACIONES='".$_POST["alcanceObservacionesEvento"]."', DECLARATORIA ='".$_POST["alcanceDeclaratoria"]."' ";
$sql02="WHERE ID_EVENTO=".$idEvento;
$sql0=$sql01.$sql02;
 
 
 
 
/*
echo '<br/><br/>';
echo $sql0;
*/

$sql1="INSERT INTO CENACOM.REPORTES(ID_REPORTE, CR, FECHA_REPORTE, EFECTO_ADVERSO, FECHA_AVISO, ORGANISMO_AVISO, AREAS_AFECTADAS, PERSONAS_AFECTADAS, MUERTOS, LESIONADOS, DESAPARECIDOS, EVACUADOS, LINEAS_VITALES, INFRAESTRUCTURA_DANADA, OBSERVACIONES, RESPUESTA_INSTITUCIONAL, LINK, ID_USUARIO, ID_EVENTO, ID_TIPO_REPORTE, CLASIFICACIONFENOMENO_ID, ESTADO, MUNICIPIO, LOCALIDAD, X, Y, TURNO, TIPOFENOMENO_ID, OTRO_LUGAR, CR_RELACIONADO, FECHA_INICIO_FENOMENO, FECHA_FINAL_FENOMENO,LINK_TITULO)";
$sql2="VALUES(";
$sql4=")";
$sql31=$idReporte." ,".$CRregistrado." ,".$fechaReporteSQL.",'".$_POST["alcanceEfectoAdverso"]."' ,".$alcanceFechaHoraReportaSQL." ,'";
$sql32=$_POST["alcanceOrganismoReporta"]."' ,'".$_POST["alcanceAreasAfectadas"]."' ,'".$_POST["alcancePersonasAfectadas"]."' ,";
$sql33=$_POST["alcanceMuertos"]." ,".$_POST["alcanceLesionados"]." ,".$_POST["alcanceDesaparecidos"]." ,".$_POST["alcanceEvacuados"]." ,'";
$sql34=$_POST["alcanceLineasVitales"]."' ,'".$_POST["alcanceInfraestructura"]."','".$_POST["alcanceObservaciones"]."' ,'";
$sql35=$alcanceInstitucionesLista."' ,'".$_POST["alcanceLinks"]."' ,'".$alcanceAutoresLista."',".$idEvento." ,".$_POST["tipoReporte"].",'";
$sql36=$_POST["alcanceClasificacionFenomeno"]."',".$_POST["alcanceEstado"].",".$_POST["alcanceMunicipio"].",".$alcanceLocalidad.",";
$sql37="0"." ,"."0"." ,".$_POST["alcanceAutoresTurno"].",".$_POST["alcanceTipoFenomeno"].",'".$alcanceOtroLugar."' ,".$_POST["crRelacionado"]." ,".$alcanceFechaHoraFenomenoSQL." ,".$alcanceFechaHoraFenomenoSQL.", '".$_POST["alcanceTituloLinks"]."'";
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

	//ESCRIBE EN LA BD QUERY DEL REPORTE si se capturo el Eento
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
	echo 'Los datos se registraron correctamente en la base de datos. <br/>';
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
echo 'EFECTO_ADVERSO:' .$_POST["alcanceEfectoAdverso"].'<br/>';
echo 'FECHA_AVISO:' .$alcanceFechaHoraReporta.'<br/>';
echo 'ORGANISMO_AVISO:' .$_POST["alcanceOrganismoReporta"].'<br/>';
echo 'AREAS_AFECTADAS:' .$_POST["alcanceAreasAfectadas"].'<br/>';
echo 'PERSONAS_AFECTADAS:' .$_POST["alcancePersonasAfectadas"].'<br/>';
echo 'MUERTOS:' .$_POST["alcanceMuertos"].'<br/>';
echo 'LESIONADOS:' .$_POST["alcanceLesionados"].'<br/>';
echo 'DESAPARECIDOS:' .$_POST["alcanceDesaparecidos"].'<br/>';
echo 'EVACUADOS:' .$_POST["alcanceEvacuados"].'<br/>';
echo 'LINEAS_VITALES:' .$_POST["alcanceLineasVitales"].'<br/>';
echo 'INFRAESTRUCTURA_DANADA:' .$_POST["alcanceInfraestructura"].'<br/>';
echo 'OBSERVACIONES:' .$_POST["alcanceObservaciones"].'<br/>';
echo 'RESPUESTA_INSTITUCIONAL:' .$alcanceInstitucionesLista.'<br/>';
echo 'LINK:' .$_POST["alcanceLinks"].'<br/>';
echo 'ID_USUARIO:' .$alcanceAutoresLista.'<br/>';
echo 'ID_EVENTO:' ."1".'<br/>';
echo 'ID_TIPO_REPORTE:' .$_POST["tipoReporte"].'<br/>';
echo 'CLASIFICACIONFENOMENO_ID:' .$_POST["alcanceClasificacionFenomeno"].'<br/>';
echo 'ESTADO:' .$_POST["alcanceEstado"].'<br/>';
echo 'MUNICIPIO:' .$_POST["alcanceMunicipio"].'<br/>';
echo 'LOCALIDAD:' .$alcanceLocalidad.'<br/>';
echo 'X:' ."0".'<br/>';
echo 'Y:' ."0".'<br/>';
echo 'TURNO:' .$_POST["alcanceAutoresTurno"].'<br/>';
echo 'TIPOFENOMENO_ID:' .$_POST["alcanceTipoFenomeno"].'<br/>';
echo 'OTRO_LUGAR:' .$alcanceOtroLugar.'<br/>';
echo 'CR_RELACIONADO:' ."0".'<br/>';
echo 'FECHA_INICIO_FENOMENO'.$alcanceFechaHoraFenomenoSQL;

*/
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