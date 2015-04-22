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

//print "Efecto Adverso: ".$_POST["seguimientoEfectoAdverso"]."<br/>";

//print "Organismo que reporta: ".$_POST["seguimientoOrganismoReporta"]."<br/>";
$seguimientoFechaReporta=str_replace("-","/",$_POST["seguimientoFechaReporta"]);
$seguimientoFechaHoraReporta=$seguimientoFechaReporta." ".$_POST["seguimientoHoraQueReportaval"].":00";
//print "Fecha y hora que reporta: ".$seguimientoFechaHoraReporta."<br/>";

//print "Estado: ".$_POST["seguimientoEstado"]."<br/>";
//if(@$_POST["seguimientoMunicipio"])
//print "Municipio: ".$_POST["seguimientoMunicipio"]."<br/>";
//if(@$_POST["seguimientoLocalidad"])
if(!@$_POST["seguimientoLocalidad"])
$seguimientoLocalidad=0;
else
$seguimientoLocalidad=$_POST["seguimientoLocalidad"];
//print "Localidad: ".$seguimientoLocalidad."<br/>";

if(!@$_POST["seguimientoOtroLugar"])
$seguimientoOtroLugar="-";
else
$seguimientoOtroLugar=$_POST["seguimientoOtroLugar"];
//print "Otro lugar: ".$seguimientoOtroLugar."<br/>";

//print "Clasificacion fenomeno: ".$_POST["seguimientoClasificacionFenomeno"]."<br/>";
//if(@$_POST["seguimientoTipoFenomeno"]);
//print "Tipo fenomeno: ".$_POST["seguimientoTipoFenomeno"]."<br/>";
$seguimientoFechaFenomeno=str_replace("-","/",$_POST["seguimientoFechaFenomeno"]);
$seguimientoFechaHoraFenomeno=$seguimientoFechaFenomeno." ".$_POST["seguimientoHoraInicialFenomenoval"].":00";
//print "Fecha y hora del fenomeno: ".$seguimientoFechaHoraFenomeno."<br/>";


//print "Observaciones: ".$_POST["seguimientoObservaciones"]."<br/>"; 

//print "Areas afectadas: ".$_POST["seguimientoAreasAfectadas"]."<br/>";
//print "Personas afectadas: ".$_POST["seguimientoPersonasAfectadas"]."<br/>";
//print "Muertos: ".$_POST["seguimientoMuertos"]."<br/>";
//print "Lesionados: ".$_POST["seguimientoLesionados"]."<br/>";
//print "Evacuados: ".$_POST["seguimientoEvacuados"]."<br/>";
//print "Desaparecidos: ".$_POST["seguimientoDesaparecidos"]."<br/>";

//print "L&iacute;neas vitales: ".$_POST["seguimientoLineasVitales"]."<br/>";


if(@$_POST["seguimientoRespuestaInstitucional"]){
	$seguimientoInstitucionesLista="";
	$seguimientoInstituciones=$_POST["seguimientoRespuestaInstitucional"]; 
	for ($i=0;$i<count($seguimientoInstituciones);$i++)    
	{     
	//print "Respuesta institucional " . $i . ": " . $seguimientoInstituciones[$i];
		//print "Respuesta institucional: ". $seguimientoInstituciones[$i]."<br/>";
		if($i){$seguimientoInstitucionesLista=$seguimientoInstitucionesLista.",".$seguimientoInstituciones[$i];}else{
			$seguimientoInstitucionesLista=$seguimientoInstituciones[$i];
		}
	}
	//print "Respuesta institucional: ".$seguimientoInstitucionesLista."<br/>";
}

//print "Links: ".$_POST["seguimientoLinks"]."<br/>";

if(@$_POST["seguimientoAutores"]){
	$seguimientoAutoresLista="";
	$seguimientoAutores=$_POST["seguimientoAutores"]; 
	for ($i=0;$i<count($seguimientoAutores);$i++)    
	{     
	//print "<br> Autores " . $i . ": " . $seguimientoAutores[$i];
		//print "<br> Autor: ". $seguimientoAutores[$i]."<br/>";
		if($i){$seguimientoAutoresLista=$seguimientoAutoresLista.",".$seguimientoAutores[$i];}else{
			$seguimientoAutoresLista=$seguimientoAutores[$i];
		}	 
	} 
	//print "Autores: ".$seguimientoAutoresLista."<br/>";
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
$seguimientoFechaHoraReportaSQL="to_date('".$seguimientoFechaHoraReporta."','yyyy/mm/dd hh24:mi:ss')"; 
$seguimientoFechaHoraFenomenoSQL="to_date('".$seguimientoFechaHoraFenomeno."','yyyy/mm/dd hh24:mi:ss')";
 


$institucionesListaEvento=obtenerValorQuery($conOracle,'CENACOM', 'EVENTO', 'ID_EVENTO', $idEvento, 'DEPENDENCIAS_PARTICIPANTES');
$seguimientoInstitucionesListaEvento=eliminaCaracteresRepetidos($seguimientoInstitucionesLista.",".$institucionesListaEvento);
//echo '<br/><br/>';
//echo $seguimientoInstitucionesListaEvento;

$sql01="UPDATE CENACOM.EVENTO SET NOMBRE_EVENTO='SEGUIMIENTO', DANOS_MATERIALES="."'".$_POST["seguimientoDanosMaterialesEvento"]."', DEPENDENCIAS_PARTICIPANTES='".$seguimientoInstitucionesListaEvento."',"." OBSERVACIONES='".$_POST["seguimientoObservacionesEvento"]."', DECLARATORIA ='".$_POST["seguimientoDeclaratoria"]."' ";
$sql02="WHERE ID_EVENTO=".$idEvento;
$sql0=$sql01.$sql02;

/*
echo '<br/><br/>';
echo $sql0;
*/

$sql1="INSERT INTO CENACOM.REPORTES(ID_REPORTE, CR, FECHA_REPORTE, EFECTO_ADVERSO, FECHA_AVISO, ORGANISMO_AVISO, AREAS_AFECTADAS, PERSONAS_AFECTADAS, MUERTOS, LESIONADOS, DESAPARECIDOS, EVACUADOS, LINEAS_VITALES, INFRAESTRUCTURA_DANADA, OBSERVACIONES, RESPUESTA_INSTITUCIONAL, LINK, ID_USUARIO, ID_EVENTO, ID_TIPO_REPORTE, CLASIFICACIONFENOMENO_ID, ESTADO, MUNICIPIO, LOCALIDAD, X, Y, TURNO, TIPOFENOMENO_ID, OTRO_LUGAR, CR_RELACIONADO, FECHA_INICIO_FENOMENO,LINK_TITULO)";
$sql2="VALUES(";
$sql4=")";
$sql31=$idReporte." ,".$CRregistrado." ,".$fechaReporteSQL.",'".$_POST["seguimientoEfectoAdverso"]."' ,".$seguimientoFechaHoraReportaSQL." ,'";
$sql32=$_POST["seguimientoOrganismoReporta"]."' ,'".$_POST["seguimientoAreasAfectadas"]."' ,'".$_POST["seguimientoPersonasAfectadas"]."' ,";
$sql33=$_POST["seguimientoMuertos"]." ,".$_POST["seguimientoLesionados"]." ,".$_POST["seguimientoDesaparecidos"]." ,".$_POST["seguimientoEvacuados"]." ,'";
$sql34=$_POST["seguimientoLineasVitales"]."' ,'".$_POST["seguimientoInfraestructura"]."','".$_POST["seguimientoObservaciones"]."' ,'";
$sql35=$seguimientoInstitucionesLista."' ,'".$_POST["seguimientoLinks"]."' ,'".$seguimientoAutoresLista."',".$idEvento." ,".$_POST["tipoReporte"].",'";
$sql36=$_POST["seguimientoClasificacionFenomeno"]."',".$_POST["seguimientoEstado"].",".$_POST["seguimientoMunicipio"].",".$seguimientoLocalidad.",";
$sql37="0"." ,"."0"." ,".$_POST["seguimientoAutoresTurno"].",".$_POST["seguimientoTipoFenomeno"].",'".$seguimientoOtroLugar."' ,".$_POST["crRelacionado"]." ,".$seguimientoFechaHoraFenomenoSQL.", '".$_POST["seguimientoTituloLinks"]."'";
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
echo 'EFECTO_ADVERSO:' .$_POST["seguimientoEfectoAdverso"].'<br/>';
echo 'FECHA_AVISO:' .$seguimientoFechaHoraReporta.'<br/>';
echo 'ORGANISMO_AVISO:' .$_POST["seguimientoOrganismoReporta"].'<br/>';
echo 'AREAS_AFECTADAS:' .$_POST["seguimientoAreasAfectadas"].'<br/>';
echo 'PERSONAS_AFECTADAS:' .$_POST["seguimientoPersonasAfectadas"].'<br/>';
echo 'MUERTOS:' .$_POST["seguimientoMuertos"].'<br/>';
echo 'LESIONADOS:' .$_POST["seguimientoLesionados"].'<br/>';
echo 'DESAPARECIDOS:' .$_POST["seguimientoDesaparecidos"].'<br/>';
echo 'EVACUADOS:' .$_POST["seguimientoEvacuados"].'<br/>';
echo 'LINEAS_VITALES:' .$_POST["seguimientoLineasVitales"].'<br/>';
echo 'INFRAESTRUCTURA_DANADA:' .$_POST["seguimientoInfraestructura"].'<br/>';
echo 'OBSERVACIONES:' .$_POST["seguimientoObservaciones"].'<br/>';
echo 'RESPUESTA_INSTITUCIONAL:' .$seguimientoInstitucionesLista.'<br/>';
echo 'LINK:' .$_POST["seguimientoLinks"].'<br/>';
echo 'ID_USUARIO:' .$seguimientoAutoresLista.'<br/>';
echo 'ID_EVENTO:' ."1".'<br/>';
echo 'ID_TIPO_REPORTE:' .$_POST["tipoReporte"].'<br/>';
echo 'CLASIFICACIONFENOMENO_ID:' .$_POST["seguimientoClasificacionFenomeno"].'<br/>';
echo 'ESTADO:' .$_POST["seguimientoEstado"].'<br/>';
echo 'MUNICIPIO:' .$_POST["seguimientoMunicipio"].'<br/>';
echo 'LOCALIDAD:' .$seguimientoLocalidad.'<br/>';
echo 'X:' ."0".'<br/>';
echo 'Y:' ."0".'<br/>';
echo 'TURNO:' .$_POST["seguimientoAutoresTurno"].'<br/>';
echo 'TIPOFENOMENO_ID:' .$_POST["seguimientoTipoFenomeno"].'<br/>';
echo 'OTRO_LUGAR:' .$seguimientoOtroLugar.'<br/>';
echo 'CR_RELACIONADO:' ."0".'<br/>';
echo 'FECHA_INICIO_FENOMENO'.$seguimientoFechaHoraFenomenoSQL;

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