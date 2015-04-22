<?php
include 'functions.php';
include 'vars.php';
require_once('FirePHPCore/FirePHP.class.php');
ob_start();
$firephp = FirePHP::getInstance(true);

session_start();
if(@$_SESSION['login'] != "si" || @$_POST["crReal"]==NULL)
{
header("Location:index.php");
exit();
}
$conOracle=conexionORACLE($HOST, $PORT, $SID, $userName, $passkey);
?>

	<?php
	$crReal=$_POST["crReal"];
	$idEvento=obtenerValorQuery($conOracle,'CENACOM', 'REPORTES', 'CR', $crReal, 'ID_EVENTO');
	$crEfectoAdverso=obtenerValorQuery($conOracle,'CENACOM', 'REPORTES', 'CR', $crReal, 'EFECTO_ADVERSO');
	$crOtroLugar=obtenerValorQuery($conOracle,'CENACOM', 'REPORTES', 'CR', $crReal, 'OTRO_LUGAR');
	$tipoReporte=obtenerValorQuery($conOracle,'CENACOM', 'REPORTES', 'CR', $crReal, 'ID_TIPO_REPORTE');
	?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<!-- <meta http-equiv="content-type" content="text/html; charset=utf-8" /> -->
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>Reportes relevantes</title>
<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/dojo/1.8/dijit/themes/claro/claro.css">
<link rel="stylesheet" href="images/style.css" type="text/css" />
<script src="//ajax.googleapis.com/ajax/libs/dojo/1.8.0/dojo/dojo.js" data-dojo-config='async: true, parseOnLoad: true'></script>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/functions.js"></script>
<script type="text/javascript" src="js/edicion.js"></script>
<script type="text/javascript" src="js/validacionInicialE.js"></script>

<script>
require(["dojo/parser", "dijit/form/DateTextBox"]);
</script>

</head>
<body class="claro">
	
	<?php
		include 'formsMenuHead.php';
	?>
	
	
<h1>Sistema de captura</h1>

		<!--IZQUIERDA -->
		<div class="left">
		
			<div class="lboxForm">
	<h2>Actualizaci&oacute;n reporte de tipo Inicial</h2>

			
<form method="post" id="finicial" name="finicial" action="finicialE.php">
	<input type="hidden" name="tipoReporte" id="tipoReporte" value="<?php print $tipoReporte; ?>">
	<input type="hidden" name="idEvento" id="idEvento" value="<?php print $idEvento; ?>">
	<input type="hidden" name="crRegistrado" id="crRegistrado" value="<?php print $crReal; ?>">
	<input type="hidden" name="crRelacionado" id="crRelacionado" value="0"> <!--HAY ERROR, no debe ser cero. OBTENER EL CR RELACIONADO-->
	<div id="inicialEfectoAdversoLeyenda">Efecto adverso:</div>	
	<textarea name="inicialEfectoAdverso" id="inicialEfectoAdverso" title="Efecto adverso" rows="3" cols="50" onKeyUp="alertNumCaracTextArea('inicialEfectoAdverso', 4000);"><?php print obtenerValorQuery($conOracle,'CENACOM', 'REPORTES', 'CR', $crReal, 'EFECTO_ADVERSO'); ?></textarea>
	<br/>
	<br/>
	Organismo que reporta: 
<?php
	$query = oci_parse($conOracle, 'SELECT ID, NOMBRE from CENACOM.DEPENDENCIAS ORDER BY NOMBRE ASC');
	comboQueryJS_1($query, "ID", "NOMBRE", 'inicialOrganismoReporta', "Seleccione","Organismo que reporta");
?>
<?php
	$inicialOrganismoReportaBD=obtenerValorQuery($conOracle,'CENACOM', 'REPORTES', 'CR', $crReal, 'ORGANISMO_AVISO');
?>
<script language="JavaScript">
	seleccionaComboSimple('inicialOrganismoReporta', <?php print $inicialOrganismoReportaBD; ?>);
</script>
	<br/>
<?php
	$elQuery="SELECT TO_CHAR(FECHA_AVISO, 'YYYY-MM-DD') AS FECHA FROM CENACOM.REPORTES WHERE CR=".$crReal;
	$query = oci_parse($conOracle, $elQuery);
	oci_execute($query);
	$row = oci_fetch_array($query, OCI_ASSOC);
	$fechaR=$row["FECHA"];
	//$firephp->log("Fecha Aviso:".$fechaR);
	
	fechaBox("Fecha en que reporta:", "inicialFechaReporta", "inicialFechaReporta", $fechaR, 'Fecha en que reporta');
?>
	<br/>
<?php
	$elQuery="SELECT TO_CHAR(FECHA_AVISO, 'hh24') AS HORA FROM CENACOM.REPORTES WHERE CR=".$crReal;
	$query = oci_parse($conOracle, $elQuery);
	oci_execute($query);
	$row = oci_fetch_array($query, OCI_ASSOC);
	$horaR=$row["HORA"];
	
	$elQuery="SELECT TO_CHAR(FECHA_AVISO, 'mi') AS MINUTOS FROM CENACOM.REPORTES WHERE CR=".$crReal;
	$query = oci_parse($conOracle, $elQuery);
	oci_execute($query);
	$row = oci_fetch_array($query, OCI_ASSOC);
	$minutosR=$row["MINUTOS"];
	
	horaUpdate("Hora que reporta:", "inicialHoraQueReporta", "inicialHoraQueReporta", 'Hora que reporta', $horaR,$minutosR);
	
?>
	<br/>
	
	<div id="lugar" name="lugar">
	
<?php

	//Obtiene el lugar capturado anteriormente
	$estadoN= obtenerValorQuery($conOracle,'CENACOM', 'REPORTES', 'CR', $crReal, 'ESTADO');
	$estado=recuperaCampo($conOracle, 'ANRO.LOCALIDADES', 'NOM_ENT', $estadoN, 'ENTIDAD');	
	
	$municipioN= obtenerValorQuery($conOracle,'CENACOM', 'REPORTES', 'CR', $crReal, 'MUNICIPIO');
	$query = oci_parse($conOracle, 'SELECT NOM_MUN from ANRO.LOCALIDADES WHERE ENTIDAD = '.$estadoN.' AND MUN ='.$municipioN.' GROUP BY NOM_MUN');
	oci_execute($query);
	$row = oci_fetch_array($query, OCI_ASSOC);
	$municipio= $row["NOM_MUN"];
	
	$parametroLugarImprime=$estado.",".$municipio;
	
	$localidadN= obtenerValorQuery($conOracle,'CENACOM', 'REPORTES', 'CR', $crReal, 'LOCALIDAD');
	if($localidadN!="0"){		
		$query = oci_parse($conOracle, 'SELECT NOM_LOC FROM ANRO.LOCALIDADES WHERE ENTIDAD='.$estadoN.' and MUN='.$municipioN.' and LOC='.$localidadN);
		oci_execute($query);
		$row = oci_fetch_array($query, OCI_ASSOC);
		$localidad= $row["NOM_LOC"];
	}else{
		$localidad="0";
	}
	$parametroLugarImprime=$parametroLugarImprime.",".$localidad;

	$lugar= obtenerValorQuery($conOracle,'CENACOM', 'REPORTES', 'CR', $crReal, 'OTRO_LUGAR');
	$parametroLugarImprime=$parametroLugarImprime.",".$lugar;
	
	$parametroIdLugar=$estadoN.",".$municipioN.",".$localidadN;

?>

Lugar: (<a href="#lugar" onclick="edita('lugar',1,'inicial','<?php print $parametroIdLugar."','"; print $parametroLugarImprime; ?>');">Reingresar lugar</a>)<br/>
<?php

	print "Estado: ".$estado;
	print '<input type="hidden" name="inicialEstado" id="inicialEstado" value="'.$estadoN.'">';
	print "<br/>";
	
	print "Municipio: ".$municipio;
	print '<input type="hidden" name="inicialMunicipio" id="inicialMunicipio" value="'.$municipioN.'">';
	print "<br/>";
	
	if($localidadN!="0"){
		print "Localidad: ".$localidad;
		print "<br/>";
	}else{
		print "Localidad: -";
		print "<br/>";
	}
	print '<input type="hidden" name="inicialLocalidad" id="inicialLocalidad" value="'.$localidadN.'">';
	
	print "Lugar: ".$lugar;
	print "<br/>";
	
	print '<input type="hidden" name="inicialOtroLugar" id="inicialOtroLugar" value="'.$lugar.'">';
?>	
	</div>
	<br/>
	<br/>
	<div id="fenomeno" name="fenomeno">

<?php

	//Obtiene el fenomeno capturado anteriormente
	$clasificacionN= obtenerValorQuery($conOracle,'CENACOM', 'REPORTES', 'CR', $crReal, 'CLASIFICACIONFENOMENO_ID');
	$clasificacion=recuperaCampo($conOracle, 'ANRO.CLASIFICACIONFENOMENO', 'CLASIFICACION', "'".$clasificacionN."'", 'ID');	
	
	$fenomenoN= obtenerValorQuery($conOracle,'CENACOM', 'REPORTES', 'CR', $crReal, 'TIPOFENOMENO_ID');
	$fenomeno=recuperaCampo($conOracle, 'CENACOM.TIPO_FENOMENO', 'NOMBRE', $fenomenoN, 'ID_FENOMENO');
	
	$parametroIdFenomeno=$clasificacionN.",".$fenomenoN;
	$parametroFenomenoImprime=$clasificacion.",".$fenomeno;
	
?>
	Clasificaci&oacute;n y tipo de Fen&oacute;meno: (<a href="#fenomeno" onclick="edita('fenomeno',1,'inicial','<?php print $parametroIdFenomeno."','"; print $parametroFenomenoImprime; ?>');">Reingresar fen&oacute;meno</a>)
	<br/>

<?php
	print "Clasificaci&oacute;n del fen&oacute;meno: ".$clasificacion;
	print '<input type="hidden" name="inicialClasificacionFenomeno" id="inicialClasificacionFenomeno" value="'.$clasificacionN.'"><br/>';
	print "Tipo de fen&oacute;meno: ".$fenomeno;
	print '<input type="hidden" name="inicialTipoFenomeno" id="inicialTipoFenomeno" value="'.$fenomenoN.'">';
?>
	</div>
	<br/>
<?php

	$elQuery="SELECT TO_CHAR(FECHA_INICIO_FENOMENO, 'YYYY-MM-DD') AS FECHA FROM CENACOM.REPORTES WHERE CR=".$crReal;
	$query = oci_parse($conOracle, $elQuery);
	oci_execute($query);
	$row = oci_fetch_array($query, OCI_ASSOC);
	$fechaF=$row["FECHA"];
	//$firephp->log("Fecha Aviso:".$fechaR);

	fechaBox("Fecha del fen&oacute;meno:", "inicialFechaFenomeno", "inicialFechaFenomeno", $fechaF, 'Fecha del fen&oacute;meno');
?>
	<br/>
<?php

	$elQuery="SELECT TO_CHAR(FECHA_INICIO_FENOMENO, 'hh24') AS HORA FROM CENACOM.REPORTES WHERE CR=".$crReal;
	$query = oci_parse($conOracle, $elQuery);
	oci_execute($query);
	$row = oci_fetch_array($query, OCI_ASSOC);
	$horaF=$row["HORA"];
		
	$elQuery="SELECT TO_CHAR(FECHA_INICIO_FENOMENO, 'mi') AS MINUTOS FROM CENACOM.REPORTES WHERE CR=".$crReal;
	$query = oci_parse($conOracle, $elQuery);
	oci_execute($query);
	$row = oci_fetch_array($query, OCI_ASSOC);
	$minutosF=$row["MINUTOS"];
		
	horaUpdate("Hora inicial del fen&oacute;meno:", "inicialHoraInicialFenomeno", "inicialHoraInicialFenomeno", 'Hora inicial del fen&oacute;meno', $horaF, $minutosF);
?>
	<br/>
	<br/>
	<div id="inicialAreasAfectadasLeyenda">&Aacute;reas afectadas:</div>
	<textarea id="inicialAreasAfectadas" name="inicialAreasAfectadas" title="&Aacute;reas afectadas" rows="3" cols="50" onKeyUp="alertNumCaracTextArea('inicialAreasAfectadas', 4000);"><?php print obtenerValorQuery($conOracle,'CENACOM', 'REPORTES', 'CR', $crReal, 'AREAS_AFECTADAS'); ?></textarea>	
	<br/>
	<br/>
	Personas afectadas:<br/>
	<input id="inicialPersonasAfectadas" name="inicialPersonasAfectadas" title="Personas afectadas" size="66" maxlength="999" type="text" value="<?php print obtenerValorQuery($conOracle,'CENACOM', 'REPORTES', 'CR', $crReal, 'PERSONAS_AFECTADAS'); ?>"/>
	<br/>
	<br/>	
	Muertos: <input id="inicialMuertos" name="inicialMuertos" title="Muertos" maxlength="7" size="4" type="text" value="<?php print obtenerValorQuery($conOracle,'CENACOM', 'REPORTES', 'CR', $crReal, 'MUERTOS'); ?>"/>
	<br/>
	Lesionados: <input id="inicialLesionados" name="inicialLesionados" title="Lesionados" maxlength="7" size="4" type="text" value="<?php print obtenerValorQuery($conOracle,'CENACOM', 'REPORTES', 'CR', $crReal, 'LESIONADOS'); ?>"/>
	<br/>
	Evacuados: <input id="inicialEvacuados" name="inicialEvacuados" title="Evacuados" maxlength="7" size="4" type="text" value="<?php print obtenerValorQuery($conOracle,'CENACOM', 'REPORTES', 'CR', $crReal, 'EVACUADOS'); ?>"/>
	<br/>
	Desaparecidos: <input id="inicialDesaparecidos" name="inicialDesaparecidos" title="Desaparecidos" maxlength="7" size="4" type="text" value="<?php print obtenerValorQuery($conOracle,'CENACOM', 'REPORTES', 'CR', $crReal, 'DESAPARECIDOS'); ?>"/>	
	<br/><br/>
	<div id="inicialLineasVitalesLeyenda">L&iacute;neas vitales:</div>
	<textarea id="inicialLineasVitales" name="inicialLineasVitales" title="L&iacute;neas vit&aacute;les" rows="3" cols="50" onKeyUp="alertNumCaracTextArea('inicialLineasVitales', 4000);"><?php print obtenerValorQuery($conOracle,'CENACOM', 'REPORTES', 'CR', $crReal, 'LINEAS_VITALES'); ?></textarea>
	<br/><br/>
	<div id="inicialInfraestructuraLeyenda">Infraestructura da&ntilde;ada:</div>
	<textarea id="inicialInfraestructura" name="inicialInfraestructura" title="Infraestructura da&ntilde;ada" rows="3" cols="50" onKeyUp="alertNumCaracTextArea('inicialInfraestructura',4000);"><?php print obtenerValorQuery($conOracle,'CENACOM', 'REPORTES', 'CR', $crReal, 'INFRAESTRUCTURA_DANADA'); ?></textarea>
	<br/>
	<br/>
	Respuesta institucional: <br/>
<?php
	$query = oci_parse($conOracle, 'SELECT ID, NOMBRE from CENACOM.DEPENDENCIAS ORDER BY NOMBRE ASC');
	comboMultiple($query, "ID", "NOMBRE", 'inicialRespuestaInstitucional', 'Respuesta institucional');
?>
<?php
	$inicialRespuestaInstitucionalBD=obtenerValorQuery($conOracle,'CENACOM', 'REPORTES', 'CR', $crReal, 'RESPUESTA_INSTITUCIONAL');
?>
	<script language="JavaScript">
		seleccionaComboMultiple("inicialRespuestaInstitucional", <?php print '"'.$inicialRespuestaInstitucionalBD.'"'; ?>);
	</script>
	
	<br/>	
	<br/>
	<div id="inicialObservacionesLeyenda">Observaciones:</div>
	<textarea id="inicialObservaciones" name="inicialObservaciones" title="Observaciones" rows="3" cols="50" onKeyUp="alertNumCaracTextArea('inicialObservaciones', 4000);"><?php print obtenerValorQuery($conOracle,'CENACOM', 'REPORTES', 'CR', $crReal, 'OBSERVACIONES'); ?></textarea>	
	<br/>
	<br/>
	
<!-- Funciones y datos anadiendo layers -->	
<?php
	$query = oci_parse($conOracle, "SELECT COUNT(ID_USUARIO) AS CUENTA from CENACOM.USUARIOS_CENACOM");
	$numAutores=contarRegistros($query);
?>
<script language="JavaScript">
	function agregarLink(bloque, cuenta){
		numeroLinks=4;
		linkForm=cuentaElementosBloque(bloque);
		if(linkForm >= numeroLinks){
			alert("Solo se pueden agregar "+numeroLinks+" Links")
		}else{
			escribeLayerLink(cuenta)
		}
	}
	
</script>
<!-- FIN Funciones y datos anadiendo layers -->
<?php
	$LinksBD=obtenerValorQuery($conOracle,'CENACOM', 'REPORTES', 'CR', $crReal, 'LINK');
	$links = explode(",", $LinksBD);
	$numLinks=sizeof($links)-1; //Al ultimo del campo en la BD se tiene "," por lo que marca otro link de más, pero vacio.
	if($numLinks>0){
		$LinksTituloBD=obtenerValorQuery($conOracle,'CENACOM', 'REPORTES', 'CR', $crReal, 'LINK_TITULO');
		$linksTitulo = explode(",", $LinksTituloBD);
	}		
	//$firephp->log($links);
	//$firephp->log($numLinks);	
?>
<div id="agregarLink"><a href="#links" onclick="agregarLink('link', <?php if($numLinks>0){ print $numLinks;}else{ print "1";} ?>);">Agregar otro link</a></div>
	<div id="link" >
		<div id="links0">
		T&iacute;tulo de noticia:
		<input id="titulolinks0T" name="titulolinks0T" title="T&iacute;tulo de link" size="49" maxlength="990" type="text" value="<?php if($numLinks>0){ print $linksTitulo[0];} ?>"/>
		<br/>
		Link de noticia: <input id="links0T" name="links0T" title="URL del link" maxlength="980" size="50" type="text" value="<?php if($numLinks>0){ print $links[0];} ?>"/>
		</div>
<?php
if($numLinks>1){
	for($i=1;$i<$numLinks;$i++){
?>
		<div id="links<?php print $i; ?>">
		<br/>
		T&iacute;tulo de noticia:
		<input id="titulolinks<?php print $i; ?>T" name="titulolinks<?php print $i; ?>T" title="T&iacute;tulo de link" size="49" maxlength="990" type="text" value="<?php print $linksTitulo[$i]; ?>"/>
		<br/>
		Link de noticia: <input id="links<?php print $i; ?>T" name="links<?php print $i; ?>T" title="URL del link" maxlength="980" size="50" type="text" value="<?php print $links[$i]; ?>"/>
		<a href="#links" onclick="borraLayer(<?php print "links".$i; ?>);">Eliminar</a>
		</div>
<?php
	}
}
?>
	</div>

	
	<a name="links"></a>	
	<input type="hidden" name="inicialLinks" id="inicialLinks">
	<input type="hidden" name="inicialTituloLinks" id="inicialTituloLinks">
	<br/>
	<br/>
	<div id="autores" name="autores">

<?php
		$usuarios=obtenerValorQuery($conOracle,'CENACOM', 'REPORTES', 'CR', $crReal, 'ID_USUARIO');
		$turno=obtenerValorQuery($conOracle,'CENACOM', 'REPORTES', 'CR', $crReal, 'TURNO');
		//$firephp->log("Autores ".$usuarios);
		$myArray = explode(',', $usuarios);		
		$dato="";
		for ($i = 0; $i < (sizeof($myArray)-1); $i++)
			$dato = $dato . recuperaCampoS($conOracle, 'CENACOM.USUARIOS_CENACOM', 'NOMBRE', 'APELLIDO', $myArray[$i], 'ID_USUARIO'). ', '; 
		$dato = $dato . recuperaCampoS($conOracle, 'CENACOM.USUARIOS_CENACOM', 'NOMBRE', 'APELLIDO', $myArray[$i], 'ID_USUARIO'); 
?>
	Autor(es) actuales: (<a href="#autores" onclick="edita('autores',1,'inicial','<?php print $usuarios."','"; print $dato; ?>');">Reingresar autores</a>)<br/>
<?php
		print $dato;
?>	
	</div>
	<input type="hidden" name="inicialAutores" id="inicialAutores" title="inicialAutores hidden" value="<?php print $usuarios; ?>">
	<input type="hidden" name="inicialAutoresTurno" id="inicialAutoresTurno" title="inicialAutoresTurno hidden" value="<?php print $turno; ?>">
	<br/>	
	<br/>
<!--	
	<div id="inicialDanosMaterialesEventoLeyenda">Resum&eacute;n de da&ntilde;os materiales del evento: </div> 
	<textarea id="inicialDanosMaterialesEvento" name="inicialDanosMaterialesEvento" title="Resum&eacute;n de da&ntilde;os materiales" rows="3" cols="50" onk onKeyUp="alertNumCaracTextArea('inicialDanosMaterialesEvento', 4000);"><?php print obtenerValorQuery($conOracle,'CENACOM', 'EVENTO', 'ID_EVENTO', $idEvento, 'DANOS_MATERIALES'); ?></textarea>	
	<br/>
	<br/>
	Declaratoria: <br/>
	<select id="inicialDeclaratoria"  name="inicialDeclaratoria" title="Declaratoria">
<?php
	$inicialDeclaratoria=obtenerValorQuery($conOracle,'CENACOM', 'EVENTO', 'ID_EVENTO', $idEvento, 'DECLARATORIA');
	if($inicialDeclaratoria=="NO"){
?>
	<option value="NO">No</option>
	<option value="SI">Si</option>
<?php
	}else{
?>
	<option value="SI">Si</option>
	<option value="NO">No</option>
<?php
	}
?>	
	</select>
	<br/>
	<br/>
--></br/>
	<input type="hidden" name="edicion" id="edicion" title="edicion" value="SI">
	<div id="boton">
	<input type="button" value="Registrar" onclick="valida()" />
	</div>
	
</form>


<?php
	cerrarConexionORACLE($conOracle);
?>
			</div>
		</div>
		<!-- FIN DE IZQUIERDA -->
	<?php
		include 'derechaForm.php';
	?>	
		<div class="footer">
			<p>Sistema Nacional de Protecci&oacute;n Civil</p>
		</div>
	</div>
</body>
</html>