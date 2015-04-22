<?php
include 'functions.php';
include 'vars.php';
$conOracle=conexionORACLE($HOST, $PORT, $SID, $userName, $passkey);
?>


<?php
print "Tipo de reporte: ".$_POST["tipoReporte"]."<br/>";

if(@$_POST["crRelacionado"])
print "CR relacionado: ".$_POST["crRelacionado"]."<br/>";

print "Efecto Adverso: ".$_POST["unicoEfectoAdverso"]."<br/>";

print "Organismo que reporta: ".$_POST["unicoOrganismoReporta"]."<br/>";
$unicoFechaReporta=str_replace("-","/",$_POST["unicoFechaReporta"]);
$unicoFechaHoraReporta=$unicoFechaReporta." ".$_POST["unicoHoraQueReportaval"].":00";
print "Fecha y hora que reporta: ".$unicoFechaHoraReporta."<br/>";

print "Estado: ".$_POST["unicoEstado"]."<br/>";
if(@$_POST["unicoMunicipio"])
print "Municipio: ".$_POST["unicoMunicipio"]."<br/>";
if(@$_POST["unicoLocalidad"])
if(!@$_POST["unicoLocalidad"])
$unicoLocalidad=0;
else
$unicoLocalidad=$_POST["unicoLocalidad"];
print "Localidad: ".$unicoLocalidad."<br/>";

if(!@$_POST["unicoOtroLugar"])
$unicoOtroLugar="-";
else
$unicoOtroLugar=$_POST["unicoOtroLugar"];
print "Otro lugar: ".$unicoOtroLugar."<br/>";

print "Clasificacion fenomeno: ".$_POST["unicoClasificacionFenomeno"]."<br/>";
if(@$_POST["unicoTipoFenomeno"]);
print "Tipo fenomeno: ".$_POST["unicoTipoFenomeno"]."<br/>";
$unicoFechaFenomeno=str_replace("-","/",$_POST["unicoFechaFenomeno"]);
$unicoFechaHoraFenomeno=$unicoFechaFenomeno." ".$_POST["unicoHoraInicialFenomenoval"].":00";
print "Fecha y hora del fenomeno: ".$unicoFechaHoraFenomeno."<br/>";


print "Observaciones: ".$_POST["unicoObservaciones"]."<br/>"; 

print "Areas afectadas: ".$_POST["unicoAreasAfectadas"]."<br/>";
print "Personas afectadas: ".$_POST["unicoPersonasAfectadas"]."<br/>";
print "Muertos: ".$_POST["unicoMuertos"]."<br/>";
print "Lesionados: ".$_POST["unicoLesionados"]."<br/>";
print "Evacuados: ".$_POST["unicoEvacuados"]."<br/>";
print "Desaparecidos: ".$_POST["unicoDesaparecidos"]."<br/>";

print "L&iacute;neas vitales: ".$_POST["unicoLineasVitales"]."<br/>";


if(@$_POST["unicoRespuestaInstitucional"]){
	$unicoInstitucionesLista="";
	$unicoInstituciones=$_POST["unicoRespuestaInstitucional"]; 
	for ($i=0;$i<count($unicoInstituciones);$i++)    
	{     
	//print "Respuesta institucional " . $i . ": " . $unicoInstituciones[$i];
		//print "Respuesta institucional: ". $unicoInstituciones[$i]."<br/>";
		if($i){$unicoInstitucionesLista=$unicoInstitucionesLista.",".$unicoInstituciones[$i];}else{
			$unicoInstitucionesLista=$unicoInstituciones[$i];
		}
	}
	print "Respuesta institucional: ".$unicoInstitucionesLista."<br/>";
}

print "Links: ".$_POST["unicoLinks"]."<br/>";

if(@$_POST["unicoAutores"]){
	$unicoAutoresLista="";
	$unicoAutores=$_POST["unicoAutores"]; 
	for ($i=0;$i<count($unicoAutores);$i++)    
	{     
	//print "<br> Autores " . $i . ": " . $unicoAutores[$i];
		//print "<br> Autor: ". $unicoAutores[$i]."<br/>";
		if($i){$unicoAutoresLista=$unicoAutoresLista.",".$unicoAutores[$i];}else{
			$unicoAutoresLista=$unicoAutores[$i];
		}	 
	} 
	print "Autores: ".$unicoAutoresLista."<br/>";
}


$fechaReporte=date('Y/m/d').' '.date('H:i').":00";
print '<br/>Fecha y hora del reporte: '.$fechaReporte."<br/>";


print "CR registrado: ";
$query = oci_parse($conOracle, 'SELECT MAX(CR) AS VALOR FROM CENACOM.REPORTES');
oci_execute($query);
$row = oci_fetch_array($query, OCI_ASSOC);
$CRregistrado=$row["VALOR"]+1;
print $CRregistrado;

print '<br/>';


print "Id Reporte: ";
$query = oci_parse($conOracle, 'SELECT MAX(ID_REPORTE) AS VALOR FROM CENACOM.REPORTES');
oci_execute($query);
$row = oci_fetch_array($query, OCI_ASSOC);
$idReporte=$row["VALOR"]+1;
print $idReporte;

?>


<?php
//ESCRIBE EN LA BASE DE DATOS
/*
$sql="insert into REPORTES(Url_ID,Url_Name,Anchor_Text,Description)VALUES( 9,".'{$url_name}'.",".'{$anchor_text}'.",".'{$description}'.")";
$result=oci_parse($db,$sql1);
oci_execute($result);

 */

$fechaReporteSQL="to_date('".$fechaReporte."','yyyy/mm/dd hh24:mi:ss')";
$unicoFechaHoraReportaSQL="to_date('".$unicoFechaHoraReporta."','yyyy/mm/dd hh24:mi:ss')";

 
$sql1="INSERT INTO CENACOM.REPORTES(ID_REPORTE, CR, FECHA_REPORTE, EFECTO_ADVERSO, FECHA_AVISO, ORGANISMO_AVISO, AREAS_AFECTADAS, PERSONAS_AFECTADAS, MUERTOS, LESIONADOS, DESAPARECIDOS, EVACUADOS, LINEAS_VITALES, INFRAESTRUCTURA_DANADA, OBSERVACIONES, RESPUESTA_INSTITUCIONAL, LINK, ID_USUARIO, ID_EVENTO, ID_TIPO_REPORTE, CLASIFICACIONFENOMENO_ID, ESTADO, MUNICIPIO, LOCALIDAD, X, Y, TURNO, TIPOFENOMENO_ID, OTRO_LUGAR, CR_RELACIONADO)";
$sql2="VALUES(";
$sql4=")";
$sql31=$idReporte." ,".$CRregistrado." ,".$fechaReporteSQL.",'".$_POST["unicoEfectoAdverso"]."' ,".$unicoFechaHoraReportaSQL." ,'";
$sql32=$_POST["unicoOrganismoReporta"]."' ,'".$_POST["unicoAreasAfectadas"]."' ,'".$_POST["unicoPersonasAfectadas"]."' ,";
$sql33=$_POST["unicoMuertos"]." ,".$_POST["unicoLesionados"]." ,".$_POST["unicoDesaparecidos"]." ,".$_POST["unicoEvacuados"]." ,'";
$sql34=$_POST["unicoLineasVitales"]."' ,'".$_POST["unicoInfraestructura"]."','".$_POST["unicoObservaciones"]."' ,'";
$sql35=$unicoInstitucionesLista."' ,'".$_POST["unicoLinks"]."' ,'".$unicoAutoresLista."',"."1"." ,".$_POST["tipoReporte"].",'";
$sql36=$_POST["unicoClasificacionFenomeno"]."',".$_POST["unicoEstado"].",".$_POST["unicoMunicipio"].",".$unicoLocalidad.",";
$sql37="0"." ,"."0"." ,".$_POST["unicoAutoresTurno"].",".$_POST["unicoTipoFenomeno"].",'".$unicoOtroLugar."' ,"."0";



$sql3=$sql31.$sql32.$sql33.$sql34.$sql35.$sql36.$sql37; 

$sql=$sql1.$sql2.$sql3.$sql4;

/*
$escribeOracle=oci_parse($conOracle,$sql);
oci_execute($escribeOracle);
*/


echo '<br/><br/>';
echo $sql3;

echo '<br/><br/>';
echo $sql;
echo '<br/><br/>';

echo 'ID_REPORTE:' .$idReporte.'<br/>';
echo 'CR:' .$CRregistrado.'<br/>';
echo 'FECHA_REPORTE:' .$fechaReporte.'<br/>';
echo 'EFECTO_ADVERSO:' .$_POST["unicoEfectoAdverso"].'<br/>';
echo 'FECHA_AVISO:' .$unicoFechaHoraReporta.'<br/>';
echo 'ORGANISMO_AVISO:' .$_POST["unicoOrganismoReporta"].'<br/>';
echo 'AREAS_AFECTADAS:' .$_POST["unicoAreasAfectadas"].'<br/>';
echo 'PERSONAS_AFECTADAS:' .$_POST["unicoPersonasAfectadas"].'<br/>';
echo 'MUERTOS:' .$_POST["unicoMuertos"].'<br/>';
echo 'LESIONADOS:' .$_POST["unicoLesionados"].'<br/>';
echo 'DESAPARECIDOS:' .$_POST["unicoDesaparecidos"].'<br/>';
echo 'EVACUADOS:' .$_POST["unicoEvacuados"].'<br/>';
echo 'LINEAS_VITALES:' .$_POST["unicoLineasVitales"].'<br/>';
echo 'INFRAESTRUCTURA_DANADA:' .$_POST["unicoInfraestructura"].'<br/>';
echo 'OBSERVACIONES:' .$_POST["unicoObservaciones"].'<br/>';
echo 'RESPUESTA_INSTITUCIONAL:' .$unicoInstitucionesLista.'<br/>';
echo 'LINK:' .$_POST["unicoLinks"].'<br/>';
echo 'ID_USUARIO:' .$unicoAutoresLista.'<br/>';
echo 'ID_EVENTO:' ."1".'<br/>';
echo 'ID_TIPO_REPORTE:' .$_POST["tipoReporte"].'<br/>';
echo 'CLASIFICACIONFENOMENO_ID:' .$_POST["unicoClasificacionFenomeno"].'<br/>';
echo 'ESTADO:' .$_POST["unicoEstado"].'<br/>';
echo 'MUNICIPIO:' .$_POST["unicoMunicipio"].'<br/>';
echo 'LOCALIDAD:' .$unicoLocalidad.'<br/>';
echo 'X:' ."0".'<br/>';
echo 'Y:' ."0".'<br/>';
echo 'TURNO:' .$_POST["unicoAutoresTurno"].'<br/>';
echo 'TIPOFENOMENO_ID:' .$_POST["unicoTipoFenomeno"].'<br/>';
echo 'OTRO_LUGAR:' .$unicoOtroLugar.'<br/>';
echo 'CR_RELACIONADO:' ."0".'<br/>';



?>


<?php
	cerrarConexionORACLE($conOracle);
?>
