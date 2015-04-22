<?php
require_once('/TCPDF/tcpdf.php');
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
		
		$tbl = '<table> <tr style="background-color:#CCCCCC;"><td align="left">Reporte Número:</td></tr><tr><td align="left">'.$IdReporte.'</td></tr><tr style="background-color:#CCCCCC;"><td align="left">Fecha que se reporta:</td></tr><tr><td align="left">'.$FechaReporte.'</td></tr><tr style="background-color:#CCCCCC;"><td align="left">Estado(s):</td></tr><tr><td align="left">'.$Estados.'</td></tr><tr style="background-color:#CCCCCC;"><td align="left">Municipio(s):</td></tr><tr><td align="left">'.$Municipios.'</td></tr><tr style="background-color:#CCCCCC;"><td align="left">Fenómeno (clasificación y tipo):</td></tr><tr><td align="left">'.$TipoClasificacionF.'</td></tr><tr style="background-color:#CCCCCC;"><td align="left">Fenómeno de Mayor Afectación:</td></tr><tr><td align="left">'.$FenomenoMayorAfectacion.'</td></tr><tr style="background-color:#CCCCCC;"><td align="left">Efecto Adverso:</td></tr><tr><td align="left">'.$EfectoAdverso.'</td></tr><tr style="background-color:#CCCCCC;"><td align="left">Fecha del fenómeno:</td></tr><tr><td align="left">'.$FechaFenomeno.'</td></tr><tr style="background-color:#CCCCCC;"><td align="left">Personas afectadas:</td></tr><tr><td align="left">'.$PersonasAfectadas.'</td></tr><tr style="background-color:#CCCCCC;"><td align="left">Muertos:</td></tr><tr><td align="left">'.$Muertos.'</td></tr><tr style="background-color:#CCCCCC;"><td align="left">Lesionados:</td></tr><tr><td align="left">'.$Lesionados.'</td></tr><tr style="background-color:#CCCCCC;"><td align="left">Desaparecidos:</td></tr><tr><td align="left">'.$Desaparecidos.'</td></tr><tr style="background-color:#CCCCCC;"><td align="left">Daños Materiales:</td></tr><tr><td align="left">'.$DanosMateriales.'</td></tr><tr style="background-color:#CCCCCC;"><td align="left">Resumen de daños:</td></tr><tr><td align="left">'.$ResumenDanos.'</td></tr><tr style="background-color:#CCCCCC;"><td align="left">Observaciones:</td></tr><tr><td align="left">'.$Observaciones.'</td></tr><tr style="background-color:#CCCCCC;"><td align="left">Respuesta Institucional:</td></tr><tr><td align="left">'.$RespuestaInstitucional.'</td></tr><tr style="background-color:#CCCCCC;"><td align="left">Organismo que reporta:</td></tr><tr><td align="left">'.$OrganismoReporta.'</td></tr><tr style="background-color:#CCCCCC;"><td align="left">Autor:</td></tr><tr><td align="left">'.$Autor.'</td></tr><tr style="background-color:#CCCCCC;"><td align="left">Usuario que Registra el reporte:</td></tr><tr><td align="left">'.$Usuario.'</td></tr><tr style="background-color:#CCCCCC;"><td align="left">Declaratoria:</td></tr><tr><td align="left">'.$Declaratoria.'</td></tr></table>';
	}else
		$tbl = '<table><tr style="background-color:#CCCCCC;"><td align="left">El reporte a consultar no se encontró en la Base de Datos.</td></tr></table>';
	cerrarConexionORACLE($conOracle1);
}

$pdf = new TCPDF('P', 'mm', 'Letter', true, 'UTF-8', false,false);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

$pdf->SetDefaultMonospacedFont('courier');

// set margins
$pdf->SetMargins(10,15,10);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 25);

// set image scale factor
$pdf->setImageScale(1);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// set font
$pdf->SetFont('times', '', 11.5);

// add a page
$pdf->AddPage();

	$pdf->Image('/images/cenacom1.png', 10, 8, 185, 35, 'PNG', '', '', false, 300, '', false, false, 0, false, false, false);
	$pdf->Write(0, '        ', '', 0, '', true, 0, false, false, 0);
	$pdf->Write(0, '        ', '', 0, '', true, 0, false, false, 0);
	$pdf->Write(0, '        ', '', 0, 'center', true, 0, false, false, 0);
	$pdf->Write(0, '        ', '', 0, '', true, 0, false, false, 0);
	$pdf->Write(0, '        ', '', 0, '', true, 0, false, false, 0);
	$pdf->Write(0, '        ', '', 0, '', true, 0, false, false, 0);
	$pdf->writeHTML($tbl, false, false, false, false,'center');
	$pdf->Output('Reporte.pdf', 'I');



?>