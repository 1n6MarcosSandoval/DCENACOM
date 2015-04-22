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

//print "Efecto Adverso: ".$_POST["inicialEfectoAdverso"]."<br/>";

//print "Organismo que reporta: ".$_POST["inicialOrganismoReporta"]."<br/>";
$inicialFechaReporta=str_replace("-","/",$_POST["inicialFechaReporta"]);
$inicialFechaHoraReporta=$inicialFechaReporta." ".$_POST["inicialHoraQueReportaval"].":00";
//print "Fecha y hora que reporta: ".$inicialFechaHoraReporta."<br/>";

//print "Estado: ".$_POST["inicialEstado"]."<br/>";
//if(@$_POST["inicialMunicipio"])
//print "Municipio: ".$_POST["inicialMunicipio"]."<br/>";

//if(@$_POST["inicialLocalidad"]) //BORRAR

if(!@$_POST["inicialLocalidad"])
$inicialLocalidad=0;
else
$inicialLocalidad=$_POST["inicialLocalidad"];
//print "Localidad: ".$inicialLocalidad."<br/>";

if(!@$_POST["inicialOtroLugar"])
$inicialOtroLugar="-";
else
$inicialOtroLugar=$_POST["inicialOtroLugar"];
//print "Otro lugar: ".$inicialOtroLugar."<br/>";

//print "Clasificacion fenomeno: ".$_POST["inicialClasificacionFenomeno"]."<br/>";
//if(@$_POST["inicialTipoFenomeno"]);
//print "Tipo fenomeno: ".$_POST["inicialTipoFenomeno"]."<br/>";
$inicialFechaFenomeno=str_replace("-","/",$_POST["inicialFechaFenomeno"]);
$inicialFechaHoraFenomeno=$inicialFechaFenomeno." ".$_POST["inicialHoraInicialFenomenoval"].":00";
//print "Fecha y hora del fenomeno: ".$inicialFechaHoraFenomeno."<br/>";


//print "Observaciones: ".$_POST["inicialObservaciones"]."<br/>"; 

//print "Areas afectadas: ".$_POST["inicialAreasAfectadas"]."<br/>";
//print "Personas afectadas: ".$_POST["inicialPersonasAfectadas"]."<br/>";
//print "Muertos: ".$_POST["inicialMuertos"]."<br/>";
//print "Lesionados: ".$_POST["inicialLesionados"]."<br/>";
//print "Evacuados: ".$_POST["inicialEvacuados"]."<br/>";
//print "Desaparecidos: ".$_POST["inicialDesaparecidos"]."<br/>";

//print "L&iacute;neas vitales: ".$_POST["inicialLineasVitales"]."<br/>";


if(@$_POST["inicialRespuestaInstitucional"]){
	$inicialInstitucionesLista="";
	$inicialInstituciones=$_POST["inicialRespuestaInstitucional"]; 
	for ($i=0;$i<count($inicialInstituciones);$i++)    
	{     
	//print "Respuesta institucional " . $i . ": " . $inicialInstituciones[$i];
		//print "Respuesta institucional: ". $inicialInstituciones[$i]."<br/>";
		if($i){$inicialInstitucionesLista=$inicialInstitucionesLista.",".$inicialInstituciones[$i];}else{
			$inicialInstitucionesLista=$inicialInstituciones[$i];
		}
	}
	//print "Respuesta institucional: ".$inicialInstitucionesLista."<br/>";
}

//print "Links: ".$_POST["inicialLinks"]."<br/>";
/*
if(@$_POST["inicialAutores"]){
	$inicialAutoresLista="";
	$inicialAutores=$_POST["inicialAutores"]; 
	for ($i=0;$i<count($inicialAutores);$i++)    
	{     
	//print "<br> Autores " . $i . ": " . $inicialAutores[$i];
		//print "<br> Autor: ". $inicialAutores[$i]."<br/>";
		if($i){$inicialAutoresLista=$inicialAutoresLista.",".$inicialAutores[$i];}else{
			$inicialAutoresLista=$inicialAutores[$i];
		}	 
	} 
	//print "Autores: ".$inicialAutoresLista."<br/>";
}
*/

if(@$_POST["inicialAutores"]){
	$inicialAutoresLista=$_POST["inicialAutores"];
	$firephp->log("Autores: ".$inicialAutoresLista."<br/>");
}

$fechaReporte=date('Y/m/d').' '.date('H:i').":00";
//print '<br/>Fecha y hora del reporte: '.$fechaReporte."<br/>";


//print "CR registrado: ";
$query = oci_parse($conOracle, 'SELECT MAX(CR) AS VALOR FROM CENACOM.REPORTES');
oci_execute($query);
$row = oci_fetch_array($query, OCI_ASSOC);
$CRregistrado=@$row["VALOR"]+1;
//print $CRregistrado;

//print '<br/>';

//print "Id Reporte: ";
$query = oci_parse($conOracle, 'SELECT MAX(ID_REPORTE) AS VALOR FROM CENACOM.REPORTES');
oci_execute($query);
$row = oci_fetch_array($query, OCI_ASSOC);
$idReporte=@$row["VALOR"]+1;
//print $idReporte;

//print '<br/>';
//print "Evento: ";
$query = oci_parse($conOracle, 'SELECT MAX(ID_EVENTO) AS VALOR FROM CENACOM.EVENTO');
oci_execute($query);
$row = oci_fetch_array($query, OCI_ASSOC);
$idEvento=@$row["VALOR"]+1;
//print $idEvento;

?>
<?php
/*Arreglo multiples estados y municipios*/

/*
// Se evalúa como true ya que $var está definida
if (isset($var)) {
    echo '$var está definida a pesar que está vacía';
}

	foreach ($_POST as $campo => $valor){
		$$campo = $valor;
	}

*/

	echo $_POST["Estado0"];
	echo $_POST["Estado1"];
	echo $_POST["Municipio0"];
	echo $_POST["Municipio1"];

/*	foreach($_POST as $nombre_campo => $valor){
			//echo "nombre: $nombre_campo es $valor";
			$PostF[$nombre_campo] = $valor;
			//$asignacion = "\$" . $nombre_campo . "='" . $valor . "';"; 
			//ARRAY_PUSH($PostF,$asignacion);
	}
	for($contador = 0;$contador<=(count($PostF)-32);$contador++){
		$EstadoC = $PostF["Estado".$contador];
		if (isset($EstadoC)){
			ARRAY_PUSH($EstadoA,$EstadoC);
		}
	}
	print_r ($EstadoA);
	//print_r ($PostF);*/

?>

<?php
//ESCRIBE EN LA BASE DE DATOS


$fechaReporteSQL="to_date('".$fechaReporte."','yyyy/mm/dd hh24:mi:ss')";
$inicialFechaHoraReportaSQL="to_date('".$inicialFechaHoraReporta."','yyyy/mm/dd hh24:mi:ss')";
$inicialFechaHoraFenomenoSQL="to_date('".$inicialFechaHoraFenomeno."','yyyy/mm/dd hh24:mi:ss')";  


//Query GEOR

$sql100 = "INSERT INTO CENACOM.GEOR(ID_REPORTE,ENTIDADES,MUNS,LOCS)";
$sql110 = "VALUES(";
$sql130 = ")";
$sql120 = $idReporte.

//ESCRIBE QUERY DEL EVENTO

$sql01="INSERT INTO CENACOM.EVENTO(ID_EVENTO,NOMBRE_EVENTO,ESTADO_EVENTO,FECHA_INICIO,FECHA_FIN,DANOS_MATERIALES,DEPENDENCIAS_PARTICIPANTES, OBSERVACIONES, DECLARATORIA,NIVEL)";
$sql02="VALUES(";
$sql04=")";
$sql03=$idEvento.","."'INICIAL'".","."'1'".",".$inicialFechaHoraReportaSQL.",".$inicialFechaHoraReportaSQL.",'".$_POST["inicialDanosMaterialesEvento"]."','".$inicialInstitucionesLista."','".$_POST["inicialObservaciones"]."','"."NO"."','".$_POST["inicialNivel"]."'";
//$sql03=$idEvento.","."'INICIAL'".","."'1'".",".$inicialFechaHoraReportaSQL.",".$inicialFechaHoraReportaSQL.",'"."Sin resumen."."','".$inicialInstitucionesLista."','"."Sin resumen"."','"."NO"."','".$_POST["inicialNivel"]."'";
$sql0=$sql01.$sql02.$sql03.$sql04;

//echo '<br/><br/>';
//echo $sql0;


//ESCRIBE QUERY DEL REPORTE

$sql1="INSERT INTO CENACOM.REPORTES(ID_REPORTE, CR, FECHA_REPORTE, EFECTO_ADVERSO, FECHA_AVISO, ORGANISMO_AVISO, AREAS_AFECTADAS, PERSONAS_AFECTADAS, MUERTOS, LESIONADOS, DESAPARECIDOS, EVACUADOS, LINEAS_VITALES, INFRAESTRUCTURA_DANADA, OBSERVACIONES, RESPUESTA_INSTITUCIONAL, LINK, ID_USUARIO, ID_EVENTO, ID_TIPO_REPORTE, CLASIFICACIONFENOMENO_ID, X, Y, TURNO, TIPOFENOMENO_ID, OTRO_LUGAR, CR_RELACIONADO, FECHA_INICIO_FENOMENO, LINK_TITULO, NIVEL)";
$sql2="VALUES(";
$sql4=")";
$sql31=$idReporte.",".$CRregistrado.",".$fechaReporteSQL.",'".$_POST["inicialEfectoAdverso"]."',".$inicialFechaHoraReportaSQL.",'";
$sql32=$_POST["inicialOrganismoReporta"]."','".$_POST["inicialAreasAfectadas"]."','".$_POST["inicialPersonasAfectadas"]."',";
$sql33=$_POST["inicialMuertos"].",".$_POST["inicialLesionados"].",".$_POST["inicialDesaparecidos"].",".$_POST["inicialEvacuados"].",'";
$sql34=$_POST["inicialLineasVitales"]."','".$_POST["inicialInfraestructura"]."','".$_POST["inicialObservaciones"]."' ,'";
$sql35=$inicialInstitucionesLista."','".$_POST["inicialLinks"]."','".$inicialAutoresLista."',".$idEvento.",".$_POST["tipoReporte"].",'";
$sql36="0".","."0".",".$_POST["inicialAutoresTurno"].",".$_POST["inicialTipoFenomeno"].",'".$inicialOtroLugar."',"."0".",".$inicialFechaHoraFenomenoSQL.",'".$_POST["inicialTituloLinks"]."','".$_POST["inicialNivel"]."'";
$sql3=$sql31.$sql32.$sql33.$sql34.$sql35.$sql36; 
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
echo 'EFECTO_ADVERSO:' .$_POST["inicialEfectoAdverso"].'<br/>';
echo 'FECHA_AVISO:' .$inicialFechaHoraReporta.'<br/>';
echo 'ORGANISMO_AVISO:' .$_POST["inicialOrganismoReporta"].'<br/>';
echo 'AREAS_AFECTADAS:' .$_POST["inicialAreasAfectadas"].'<br/>';
echo 'PERSONAS_AFECTADAS:' .$_POST["inicialPersonasAfectadas"].'<br/>';
echo 'MUERTOS:' .$_POST["inicialMuertos"].'<br/>';
echo 'LESIONADOS:' .$_POST["inicialLesionados"].'<br/>';
echo 'DESAPARECIDOS:' .$_POST["inicialDesaparecidos"].'<br/>';
echo 'EVACUADOS:' .$_POST["inicialEvacuados"].'<br/>';
echo 'LINEAS_VITALES:' .$_POST["inicialLineasVitales"].'<br/>';
echo 'INFRAESTRUCTURA_DANADA:' .$_POST["inicialInfraestructura"].'<br/>';
echo 'OBSERVACIONES:' .$_POST["inicialObservaciones"].'<br/>';
echo 'RESPUESTA_INSTITUCIONAL:' .$inicialInstitucionesLista.'<br/>';
echo 'LINK:' .$_POST["inicialLinks"].'<br/>';
echo 'ID_USUARIO:' .$inicialAutoresLista.'<br/>';
echo 'ID_EVENTO:' ."1".'<br/>';
echo 'ID_TIPO_REPORTE:' .$_POST["tipoReporte"].'<br/>';
echo 'CLASIFICACIONFENOMENO_ID:' .$_POST["inicialClasificacionFenomeno"].'<br/>';
echo 'ESTADO:' .$_POST["inicialEstado"].'<br/>';
echo 'MUNICIPIO:' .$_POST["inicialMunicipio"].'<br/>';
echo 'LOCALIDAD:' .$inicialLocalidad.'<br/>';
echo 'X:' ."0".'<br/>';
echo 'Y:' ."0".'<br/>';
echo 'TURNO:' .$_POST["inicialAutoresTurno"].'<br/>';
echo 'TIPOFENOMENO_ID:' .$_POST["inicialTipoFenomeno"].'<br/>';
echo 'OTRO_LUGAR:' .$inicialOtroLugar.'<br/>';
echo 'CR_RELACIONADO:' ."0".'<br/>';

*/

/***************************** ACTUALIZA LA CLAVE DEL LUGAR *******************/

$lugar=cadenaLugar($_POST["inicialEstado"], $_POST["inicialMunicipio"], $inicialLocalidad);
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