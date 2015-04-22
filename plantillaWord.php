<?php
include_once 'phpWordCreator.php';
include 'functions.php';

$HOST1="10.2.233.164";
$PORT1=1521;
$SID1="ORCL";
$userName1="cenacom";
$passkey1="jxkGR";

$HOST2="10.2.233.164";
$PORT2=1521;
$SID2="ORCL";
$userName2="anro";
$passkey2="Anro1234";


$tbl = '';
$ExisteReporte= true;
$IdReporte;
$FechaReporte;
$IdsEstados="";
$Estados="";
$IdsMunicipios="";
$Municipios="";
$Clasificacion;
$Tipo;
$TipoClasificacionF="";
$FenomenoMayorAfectacion="";
$TipoFenomenoMayorAfectacion="";
$EfectoAdverso;
$FechaFenomeno;
$PersonasAfectadas;
$Muertos;
$Lesionados;
$Desaparecidos;
$DanosMateriales="";
$ResumenDanos;
$Observaciones;
$RespuestaInstitucional;
$OrganismoReporta;
$Autor;
$Usuario="";
$IDUsuario="";
$Declaratoria="";
if($_GET['CR']=='')
{
	$tbl = '<table><tr style="background-color:#CCCCCC;"><td align="left">No se recibio el reporte a consultar.</td></tr></table>';
}
else if(!is_numeric($_GET['CR']))
{
	$tbl = '<table><tr style="background-color:#CCCCCC;"><td align="left">El número de reporte es incorrecto.</td></tr></table>';
}
else{
	$conOracle1=conexionORACLE($HOST1, $PORT1, $SID1, $userName1, $passkey1);
	$conOracle2=conexionORACLE($HOST2, $PORT2, $SID2, $userName2, $passkey2);
	$query1 =  oci_parse($conOracle1, "SELECT id_reporte, TO_CHAR( fecha_reporte,'DD/MM/YYYY HH24:MI:SS') as fechaRep,clasificacionfenomeno_id, tipofenomeno_id, fenomenomayorafectacion,tipofenomenomayorafectacion, efecto_adverso, TO_CHAR( fecha_inicio_fenomeno,'DD/MM/YYYY HH24:MI:SS') as FechIniFen, personas_afectadas, muertos, lesionados, desaparecidos, infraestructura_danada , observaciones, respuesta_institucional, organismo_aviso, autor, id_usuario FROM CENACOM.REPORTES WHERE ID_REPORTE=".$_GET['CR']."");
	
	if(!$query1){
			$e = oci_error($conOracle1);
			trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
			print "Error en la conexion a la base de datos";
	}
	else if(!oci_execute($query1)){
			$e = oci_error($query1);
			trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
			print "Error en el query";
	}
	else{
		$nrows = oci_fetch_all($query1, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
		if($nrows>0)
		{
			$row1 = $res[0];
			$IdReporte=$row1['ID_REPORTE'];
			$FechaReporte=$row1['FECHAREP'];
			$Clasificacion=$row1['CLASIFICACIONFENOMENO_ID'];
			$Tipo=$row1['TIPOFENOMENO_ID'];
			$FenomenoMayorAfectacion=$row1['FENOMENOMAYORAFECTACION'];
			$TipoFenomenoMayorAfectacion=$row1['TIPOFENOMENOMAYORAFECTACION'];
			$EfectoAdverso=$row1['EFECTO_ADVERSO'];
			$FechaFenomeno=$row1['FECHINIFEN'];
			$PersonasAfectadas=$row1['PERSONAS_AFECTADAS'];
			$Muertos=$row1['MUERTOS'];
			$Lesionados=$row1['LESIONADOS'];
			$Desaparecidos=$row1['DESAPARECIDOS'];
			$ResumenDanos=$row1['INFRAESTRUCTURA_DANADA'];
			$Observaciones=$row1['OBSERVACIONES'];
			$IDRespuestaInstitucional=$row1['RESPUESTA_INSTITUCIONAL'];
			$RespuestaInstitucional='';
			$IDOrganismoReporta=$row1['ORGANISMO_AVISO'];
			$OrganismoReporta='';
			$Autor=$row1['AUTOR'];
			$IDUsuario = $row1['ID_USUARIO'];
		}else
			$ExisteReporte= false;
	}
	
	if($ExisteReporte)
	{
		if($FenomenoMayorAfectacion != "" && !is_numeric($FenomenoMayorAfectacion)){
			$query1 =  oci_parse($conOracle1, "SELECT NOMBRE FROM TIPO_FENOMENO WHERE ID_FENOMENO=".$TipoFenomenoMayorAfectacion);
			
			if(!$query1){
					$e = oci_error($conOracle1);
					trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
					print "Error en la conexion a la base de datos";
			}
			else if(!oci_execute($query1)){
					$e = oci_error($query1);
					trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
					print "Error en el query";
			}
			else{
				$nrows = oci_fetch_all($query1, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
				if($nrows>0)
				{
					$row1 = $res[0];
					$FenomenoMayorAfectacion =$FenomenoMayorAfectacion.'-'.$row1['NOMBRE'];
				}
			}
		}
		if($Tipo!=""){
			$query1 =  oci_parse($conOracle1, "SELECT NOMBRE FROM TIPO_FENOMENO WHERE ID_FENOMENO=".$Tipo);
			
			if(!$query1){
					$e = oci_error($conOracle1);
					trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
					print "Error en la conexion a la base de datos";
			}
			else if(!oci_execute($query1)){
					$e = oci_error($query1);
					trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
					print "Error en el query";
			}
			else{
				$nrows = oci_fetch_all($query1, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
				if($nrows>0)
				{
					$row1 = $res[0];
					$TipoClasificacionF =$Clasificacion.'-'.$row1['NOMBRE'];
				}
			}
		}
		else
			$TipoClasificacionF =$Clasificacion;
		
		if($IDUsuario!="" && is_numeric($IDUsuario ))
		{
			$query1 =  oci_parse($conOracle1, "SELECT NOMBRE, APELLIDO FROM USUARIOS_CENACOM WHERE ID_USUARIO=".$IDUsuario);
			
			if(!$query1){
					$e = oci_error($conOracle1);
					trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
					print "Error en la conexion a la base de datos";
			}
			else if(!oci_execute($query1)){
					$e = oci_error($query1);
					trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
					print "Error en el query";
			}
			else{
				$nrows = oci_fetch_all($query1, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
				$row1 = $res[0];
				$Usuario =$row1['NOMBRE'].' '.$row1['APELLIDO'];
			}
		}else
			$Usuario=$IDUsuario;
		
		$query1 =  oci_parse($conOracle1, "SELECT DANOS_MATERIALES, DECLARATORIA FROM EVENTO WHERE ID_EVENTO=".$IdReporte);
		
		if(!$query1){
				$e = oci_error($conOracle1);
				trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
				print "Error en la conexion a la base de datos";
		}
		else if(!oci_execute($query1)){
				$e = oci_error($query1);
				trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
				print "Error en el query";
		}
		else{
			$nrows = oci_fetch_all($query1, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
			if($nrows>0)
			{
				$row1 = $res[0];
				$DanosMateriales =$row1['DANOS_MATERIALES'];
				$Declaratoria =$row1['DECLARATORIA'];
			}
			else
			{
				$DanosMateriales='';
				$Declaratoria ='';
			}
		}
		
		if($IDOrganismoReporta != ''){
			$query1 =  oci_parse($conOracle1, "SELECT NOMBRE FROM DEPENDENCIAS WHERE ID=".$IDOrganismoReporta);
			
			if(!$query1){
					$e = oci_error($conOracle1);
					trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
					print "Error en la conexion a la base de datos";
			}
			else if(!oci_execute($query1)){
					$e = oci_error($query1);
					trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
					print "Error en el query";
			}
			else{
				$nrows = oci_fetch_all($query1, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
				if($nrows>0)
				{
					$row1 = $res[0];
					$OrganismoReporta =$row1['NOMBRE'];
				}
				else
					$OrganismoReporta ="";
			}
		}
		
		if($IDRespuestaInstitucional !=""){
			$query1 =  oci_parse($conOracle1, "SELECT NOMBRE FROM DEPENDENCIAS WHERE ID IN (".$IDRespuestaInstitucional.")");
			
			if(!$query1){
					$e = oci_error($conOracle1);
					trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
					print "Error en la conexion a la base de datos";
			}
			else if(!oci_execute($query1)){
					$e = oci_error($query1);
					trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
					print "Error en el query";
			}
			else{
				$nrows = oci_fetch_all($query1, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
				for( $i =0;$i<$nrows;$i++)
				{
					$row1 = $res[$i];
					if($RespuestaInstitucional=="")
						$RespuestaInstitucional =$row1['NOMBRE'];
					else
						$RespuestaInstitucional = $RespuestaInstitucional.", ".$row1['NOMBRE'];
				}
			}
		}
		
		$query1 =  oci_parse($conOracle1, "SELECT DISTINCT ENTIDADES FROM GEOR WHERE ID_REPORTE =".$IdReporte);
		if(!$query1){
				$e = oci_error($conOracle1);
				trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
				print "Error en la conexion a la base de datos";
		}
		else if(!oci_execute($query1)){
				$e = oci_error($query1);
				trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
				print "Error en el query";
		}
		else{
			$nrows = oci_fetch_all($query1, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
			for( $i =0;$i<$nrows;$i++)
			{
				$row1 = $res[$i];
				if($IdsEstados =="")
					$IdsEstados =$row1['ENTIDADES'];
				else
					$IdsEstados = $IdsEstados.", ".$row1['ENTIDADES'];
			}
		}
		
		if($IdsEstados !="")
		{
			$query2 =  oci_parse($conOracle2, "SELECT ENDECLARATORIA FROM ESTADOS WHERE CLAVE IN (".$IdsEstados.")");
			if(!$query2){
					$e = oci_error($conOracle2);
					trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
					print "Error en la conexion a la base de datos";
			}
			else if(!oci_execute($query2)){
					$e = oci_error($query2);
					trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
					print "Error en el query";
			}
			else{
				$nrows2 = oci_fetch_all($query2, $res2, null, null, OCI_FETCHSTATEMENT_BY_ROW);
				for( $i =0;$i<$nrows2;$i++)
				{
					$row2 = $res2[$i];
					if($Estados =="")
						$Estados =$row2['ENDECLARATORIA'];
					else
						$Estados = $Estados.", ".$row2['ENDECLARATORIA'];
				}
			}
		}
		
		
		$query1 =  oci_parse($conOracle1, "SELECT DISTINCT ENTIDADES||'_' ||MUNS AS CLAVE FROM GEOR WHERE ID_REPORTE =".$IdReporte);
		if(!$query1){
				$e = oci_error($conOracle1);
				trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
				print "Error en la conexion a la base de datos";
		}
		else if(!oci_execute($query1)){
				$e = oci_error($query1);
				trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
				print "Error en el query";
		}
		else{
			$nrows = oci_fetch_all($query1, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
			for( $i =0;$i<$nrows;$i++)
			{
				$row1 = $res[$i];
				if($IdsMunicipios =="")
					$IdsMunicipios ="'".$row1['CLAVE']."'";
				else
					$IdsMunicipios = $IdsMunicipios.", '".$row1['CLAVE']."'";
			}
		}
		
		if($IdsMunicipios !="")
		{
			$query2 =  oci_parse($conOracle2, "SELECT NOM_MUN FROM(SELECT DISTINCT ENTIDAD ||'_' ||MUN AS CLAVE,NOM_MUN  FROM LOCALIDADES) WHERE CLAVE IN (".$IdsMunicipios.")");
			if(!$query2){
					$e = oci_error($conOracle2);
					trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
					print "Error en la conexion a la base de datos";
			}
			else if(!oci_execute($query2)){
					$e = oci_error($query2);
					trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
					print "Error en el query";
			}
			else{
				$nrows2 = oci_fetch_all($query2, $res2, null, null, OCI_FETCHSTATEMENT_BY_ROW);
				for( $i =0;$i<$nrows2;$i++)
				{
					$row2 = $res2[$i];
					if($Municipios =="")
						$Municipios =$row2['NOM_MUN'];
					else
						$Municipios = $Municipios.", ".$row2['NOM_MUN'];
				}
			}
		}
		// New Word document
		$phpWord = new \PhpOffice\PhpWord\PhpWord();
		
		// Variables
		$section = $phpWord->addSection(array('headerHeight' => 10,'footerHeight' => 10,'marginTop' => 1000,'marginLeft' => 1000,'marginRight' => 1000,'marginBottom' => 1000));// Remote image
		$logo = 'resources/Cenacom.png';
		$section->addImage($logo,array(
			   'width' => 650,
				'marginTop' => 0,
				'marginLeft' => 0,
				'wrappingStyle' => 'behind'
			));
		
		//Styles
		$styleTable = array('borderSize' => 0, 'borderColor' => '000000');
		$styleCell = array('valign' => 'center');
		$styleHeaderCell = array('valign' => 'center', 'bgColor' => 'CCCCCC');
		$table = $section->addTable('ReportesTabla');
		
		$fontStyle = array('name' => 'Times New Roman', 'size' => 11,'bold' => true);
		$fontStyleNormal = array('name' => 'Times New Roman', 'size' => 11);
		$paragraphStyle = array('lineHeight ' => 1.5,'spaceAfter'=>0,'spaceBefore'=>0);
		
		$table->addRow();
		$table->addCell(90000, $styleHeaderCell)->addText('Algo',$fontStyle,$paragraphStyle);

		
		$table->addRow();
		$table->addCell(90000, $styleHeaderCell)->addText('Reporte Número:',$fontStyle,$paragraphStyle);
		$table->addRow();
		$table->addCell(90000, $styleCell)->addText($IdReporte,$fontStyleNormal,$paragraphStyle);
		
		$table->addRow();
		$table->addCell(90000, $styleHeaderCell)->addText('Fecha que se reporta:',$fontStyle,$paragraphStyle);
		$table->addRow();
		$table->addCell(90000, $styleCell)->addText($FechaReporte,$fontStyleNormal,$paragraphStyle);
		
		$table->addRow();
		$table->addCell(90000, $styleHeaderCell)->addText('Fecha del fenómeno:',$fontStyle,$paragraphStyle);
		$table->addRow();
		$table->addCell(90000, $styleCell)->addText($FechaFenomeno,$fontStyleNormal,$paragraphStyle);		
		
		$table->addRow();
		$table->addCell(90000, $styleHeaderCell)->addText('Estado(s):',$fontStyle,$paragraphStyle);
		$table->addRow();
		$table->addCell(90000, $styleCell)->addText($Estados,$fontStyleNormal,$paragraphStyle);
		
		$table->addRow();
		$table->addCell(90000, $styleHeaderCell)->addText('Municipio(s):',$fontStyle,$paragraphStyle);
		$table->addRow();
		$table->addCell(90000, $styleCell)->addText($Municipios,$fontStyleNormal,$paragraphStyle);
		
		$table->addRow();
		$table->addCell(90000, $styleHeaderCell)->addText('Fenómeno (clasificación y tipo):',$fontStyle,$paragraphStyle);
		$table->addRow();
		$table->addCell(90000, $styleCell)->addText($TipoClasificacionF,$fontStyleNormal,$paragraphStyle);
		
		$table->addRow();
		$table->addCell(90000, $styleHeaderCell)->addText('Fenómeno de Mayor Afectación:',$fontStyle,$paragraphStyle);
		$table->addRow();
		$table->addCell(90000, $styleCell)->addText($FenomenoMayorAfectacion,$fontStyleNormal,$paragraphStyle);
		
		$table->addRow();
		$table->addCell(90000, $styleHeaderCell)->addText('Efecto Adverso:',$fontStyle,$paragraphStyle);
		$table->addRow();
		$table->addCell(90000, $styleCell)->addText($EfectoAdverso,$fontStyleNormal,$paragraphStyle);

		$table->addRow();
		$table->addCell(90000, $styleHeaderCell)->addText('Personas afectadas:',$fontStyle,$paragraphStyle);
		$table->addRow();
		$table->addCell(90000, $styleCell)->addText($PersonasAfectadas,$fontStyleNormal,$paragraphStyle);
		
		$table->addRow();
		$table->addCell(90000, $styleHeaderCell)->addText('Muertos:',$fontStyle,$paragraphStyle);
		$table->addRow();
		$table->addCell(90000, $styleCell)->addText($Muertos,$fontStyleNormal,$paragraphStyle);
		
		$table->addRow();
		$table->addCell(90000, $styleHeaderCell)->addText('Lesionados:',$fontStyle,$paragraphStyle);
		$table->addRow();
		$table->addCell(90000, $styleCell)->addText($Lesionados,$fontStyleNormal,$paragraphStyle);
		
		$table->addRow();
		$table->addCell(90000, $styleHeaderCell)->addText('Desaparecidos:',$fontStyle,$paragraphStyle);
		$table->addRow();
		$table->addCell(90000, $styleCell)->addText($Desaparecidos,$fontStyleNormal,$paragraphStyle);
		
		$table->addRow();
		$table->addCell(90000, $styleHeaderCell)->addText('Daños Materiales:',$fontStyle,$paragraphStyle);
		$table->addRow();
		$table->addCell(90000, $styleCell)->addText($DanosMateriales,$fontStyleNormal,$paragraphStyle);
		
		$table->addRow();
		$table->addCell(90000, $styleHeaderCell)->addText('Resumen de daños:',$fontStyle,$paragraphStyle);
		$table->addRow();
		$table->addCell(90000, $styleCell)->addText($ResumenDanos,$fontStyleNormal,$paragraphStyle);
		
		$table->addRow();
		$table->addCell(90000, $styleHeaderCell)->addText('Observaciones:',$fontStyle,$paragraphStyle);
		$table->addRow();
		$table->addCell(90000, $styleCell)->addText($Observaciones,$fontStyleNormal,$paragraphStyle);
		
		$table->addRow();
		$table->addCell(90000, $styleHeaderCell)->addText('Respuesta Institucional:',$fontStyle,$paragraphStyle);
		$table->addRow();
		$table->addCell(90000, $styleCell)->addText($RespuestaInstitucional,$fontStyleNormal,$paragraphStyle);
		
		$table->addRow();
		$table->addCell(90000, $styleHeaderCell)->addText('Organismo que reporta:',$fontStyle,$paragraphStyle);
		$table->addRow();
		$table->addCell(90000, $styleCell)->addText($OrganismoReporta,$fontStyleNormal,$paragraphStyle);
		
		$table->addRow();
		$table->addCell(90000, $styleHeaderCell)->addText('Fuente:',$fontStyle,$paragraphStyle);
		$table->addRow();
		$table->addCell(90000, $styleCell)->addText($Autor,$fontStyleNormal,$paragraphStyle);
		
		$table->addRow();
		$table->addCell(90000, $styleHeaderCell)->addText('Usuario que Registra el reporte:',$fontStyle,$paragraphStyle);
		$table->addRow();
		$table->addCell(90000, $styleCell)->addText($Usuario,$fontStyleNormal,$paragraphStyle);
		
		$table->addRow();
		$table->addCell(90000, $styleHeaderCell)->addText('Declaratoria:',$fontStyle,$paragraphStyle);
		$table->addRow();
		$table->addCell(90000, $styleCell)->addText($Declaratoria,$fontStyleNormal,$paragraphStyle);
		
		$table->addRow();
		$table->addCell(90000, $styleHeaderCell)->addText('Telefonos de contacto:',$fontStyle,$paragraphStyle);
		$table->addRow();
		$table->addCell(90000, $styleCell)->addText('088',$fontStyleNormal,$paragraphStyle);
		$table->addRow();
		$table->addCell(90000, $styleCell)->addText('01 800 0041 300 ',$fontStyleNormal,$paragraphStyle);
		
		// Save file
		//echo write($phpWord, basename(__FILE__, '.php'), $writers);
		$file = 'Reporte.docx';
		header("Content-Description: File Transfer");
		header('Content-Disposition: attachment; filename="' . $file . '"');
		header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
		header('Content-Transfer-Encoding: binary');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Expires: 0');
		$xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
		$xmlWriter->save("php://output");
	}
	cerrarConexionORACLE($conOracle1);
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////

?>