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
<script type="text/javascript" src="js/validacionUnicoE.js"></script>
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
	<h2>Reporte de tipo &Uacute;nico</h2>

			
<form method="post" id="funico" name="funico" action="funicoE.php">
	<input type="hidden" name="tipoReporte" id="tipoReporte" value="<?php print $tipoReporte; ?>">
	<input type="hidden" name="idEvento" id="idEvento" value="<?php print $idEvento; ?>">
	<input type="hidden" name="crRegistrado" id="crRegistrado" value="<?php print $crReal; ?>">
	<input type="hidden" name="crRelacionado" id="crRelacionado" value="0">
	<div id="unicoEfectoAdversoLeyenda">Efecto adverso:</div>	
	<textarea name="unicoEfectoAdverso" id="unicoEfectoAdverso" title="Efecto adverso" rows="3" cols="50" onKeyUp="alertNumCaracTextArea('unicoEfectoAdverso', 4000);"><?php print obtenerValorQuery($conOracle,'CENACOM', 'REPORTES', 'CR', $crReal, 'EFECTO_ADVERSO'); ?></textarea>
	<br/>
	<br/>
	Organismo que reporta: 
<?php
	$query = oci_parse($conOracle, 'SELECT ID, NOMBRE from CENACOM.DEPENDENCIAS ORDER BY NOMBRE ASC');
	comboQueryJS_1($query, "ID", "NOMBRE", 'unicoOrganismoReporta', "Seleccione","Organismo que reporta");
?>
<?php
	$unicoOrganismoReportaBD=obtenerValorQuery($conOracle,'CENACOM', 'REPORTES', 'CR', $crReal, 'ORGANISMO_AVISO');
?>
<script language="JavaScript">
	seleccionaComboSimple('unicoOrganismoReporta', <?php print $unicoOrganismoReportaBD; ?>);
</script>
	<br/>
<?php
	$elQuery="SELECT TO_CHAR(FECHA_AVISO, 'YYYY-MM-DD') AS FECHA FROM CENACOM.REPORTES WHERE CR=".$crReal;
	$query = oci_parse($conOracle, $elQuery);
	oci_execute($query);
	$row = oci_fetch_array($query, OCI_ASSOC);
	$fechaR=$row["FECHA"];
	//$firephp->log("Fecha Aviso:".$fechaR);
	
	fechaBox("Fecha en que reporta:", "unicoFechaReporta", "unicoFechaReporta", $fechaR, 'Fecha en que reporta');
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
	
	horaUpdate("Hora que reporta:", "unicoHoraQueReporta", "unicoHoraQueReporta", 'Hora que reporta', $horaR,$minutosR);
	
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

Lugar: (<a href="#lugar" onclick="edita('lugar',1,'unico','<?php print $parametroIdLugar."','"; print $parametroLugarImprime; ?>');">Reingresar lugar</a>)<br/>
<?php

	print "Estado: ".$estado;
	print "<br/>";
	
	print "Municipio: ".$municipio;
	print "<br/>";
	
	if($localidadN!="0"){
		print "Localidad: ".$localidad;
		print "<br/>";
	}else{
		print "Localidad: -";
		print "<br/>";
	}	
	print "Lugar: ".$lugar;
	print "<br/>";
	
	print '<input type="hidden" name="unicoOtroLugar" id="unicoOtroLugar" value="'.$lugar.'">';
	print '<input type="hidden" name="unicoEstado" id="unicoEstado" value="'.$estadoN.'">';
	print '<input type="hidden" name="unicoMunicipio" id="unicoMunicipio" value="'.$municipioN.'">';
	print '<input type="hidden" name="unicoLocalidad" id="unicoLocalidad" value="'.$localidadN.'">';
	
?>
	<!--
	<input type="hidden" name="unicoEstado" id="unicoEstado" value="<?php print $estadoN;?>">
	<input type="hidden" name="unicoMunicipio" id="unicoMunicipio" value="<?php print  $municipioN;?>>">
	<input type="hidden" name="unicoLocalidad" id="unicoLocalidad" value="<?php print $localidadN;?>">
	-->
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
	Clasificaci&oacute;n y tipo de Fen&oacute;meno: (<a href="#fenomeno" onclick="edita('fenomeno',1,'unico','<?php print $parametroIdFenomeno."','"; print $parametroFenomenoImprime; ?>');">Reingresar fen&oacute;meno</a>)
	<br/>

<?php
	print "Clasificaci&oacute;n del fen&oacute;meno: ".$clasificacion;
	print '<input type="hidden" name="unicoClasificacionFenomeno" id="unicoClasificacionFenomeno" value="'.$clasificacionN.'"><br/>';
	print "Tipo de fen&oacute;meno: ".$fenomeno;
	print '<input type="hidden" name="unicoTipoFenomeno" id="unicoTipoFenomeno" value="'.$fenomenoN.'">';
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

	fechaBox("Fecha del fen&oacute;meno:", "unicoFechaFenomeno", "unicoFechaFenomeno", $fechaF, 'Fecha del fen&oacute;meno');
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
		
	horaUpdate("Hora inicial del fen&oacute;meno:", "unicoHoraInicialFenomeno", "unicoHoraInicialFenomeno", 'Hora inicial del fen&oacute;meno', $horaF, $minutosF);
?>
	<br/>
	<br/>
	<div id="unicoAreasAfectadasLeyenda">&Aacute;reas afectadas:</div>
	<textarea id="unicoAreasAfectadas" name="unicoAreasAfectadas" title="&Aacute;reas afectadas" rows="3" cols="50" onKeyUp="alertNumCaracTextArea('unicoAreasAfectadas', 4000);"><?php print obtenerValorQuery($conOracle,'CENACOM', 'REPORTES', 'CR', $crReal, 'AREAS_AFECTADAS'); ?></textarea>	
	<br/>
	<br/>
	Personas afectadas:<br/>
	<input id="unicoPersonasAfectadas" name="unicoPersonasAfectadas" title="Personas afectadas" size="66" maxlength="999" type="text" value="<?php print obtenerValorQuery($conOracle,'CENACOM', 'REPORTES', 'CR', $crReal, 'PERSONAS_AFECTADAS'); ?>"/>
	<br/>
	<br/>	
	Muertos: <input id="unicoMuertos" name="unicoMuertos" title="Muertos" maxlength="7" size="4" type="text" value="<?php print obtenerValorQuery($conOracle,'CENACOM', 'REPORTES', 'CR', $crReal, 'MUERTOS'); ?>"/>
	<br/>
	Lesionados: <input id="unicoLesionados" name="unicoLesionados" title="Lesionados" maxlength="7" size="4" type="text" value="<?php print obtenerValorQuery($conOracle,'CENACOM', 'REPORTES', 'CR', $crReal, 'LESIONADOS'); ?>"/>
	<br/>
	Evacuados: <input id="unicoEvacuados" name="unicoEvacuados" title="Evacuados" maxlength="7" size="4" type="text" value="<?php print obtenerValorQuery($conOracle,'CENACOM', 'REPORTES', 'CR', $crReal, 'EVACUADOS'); ?>"/>
	<br/>
	Desaparecidos: <input id="unicoDesaparecidos" name="unicoDesaparecidos" title="Desaparecidos" maxlength="7" size="4" type="text" value="<?php print obtenerValorQuery($conOracle,'CENACOM', 'REPORTES', 'CR', $crReal, 'DESAPARECIDOS'); ?>"/>	
	<br/><br/>
	<div id="unicoLineasVitalesLeyenda">L&iacute;neas vitales:</div>
	<textarea id="unicoLineasVitales" name="unicoLineasVitales" title="L&iacute;neas vit&aacute;les" rows="3" cols="50" onKeyUp="alertNumCaracTextArea('unicoLineasVitales', 4000);"><?php print obtenerValorQuery($conOracle,'CENACOM', 'REPORTES', 'CR', $crReal, 'LINEAS_VITALES'); ?></textarea>
	<br/><br/>
	<div id="unicoInfraestructuraLeyenda">Infraestructura da&ntilde;ada:</div>
	<textarea id="unicoInfraestructura" name="unicoInfraestructura" title="Infraestructura da&ntilde;ada" rows="3" cols="50" onKeyUp="alertNumCaracTextArea('unicoInfraestructura',4000);"><?php print obtenerValorQuery($conOracle,'CENACOM', 'REPORTES', 'CR', $crReal, 'INFRAESTRUCTURA_DANADA'); ?></textarea>
	<br/>
	<br/>
	Respuesta institucional: <br/>
<?php
	$query = oci_parse($conOracle, 'SELECT ID, NOMBRE from CENACOM.DEPENDENCIAS ORDER BY NOMBRE ASC');
	comboMultiple($query, "ID", "NOMBRE", 'unicoRespuestaInstitucional', 'Respuesta institucional');
?>
<?php
	$unicoRespuestaInstitucionalBD=obtenerValorQuery($conOracle,'CENACOM', 'REPORTES', 'CR', $crReal, 'RESPUESTA_INSTITUCIONAL');
?>
	<script language="JavaScript">
		seleccionaComboMultiple("unicoRespuestaInstitucional", <?php print '"'.$unicoRespuestaInstitucionalBD.'"'; ?>);
	</script>
	
	<br/>	
	<br/>
	<div id="unicoObservacionesLeyenda">Observaciones:</div>
	<textarea id="unicoObservaciones" name="unicoObservaciones" title="Observaciones" rows="3" cols="50" onKeyUp="alertNumCaracTextArea('unicoObservaciones', 4000);"><?php print obtenerValorQuery($conOracle,'CENACOM', 'REPORTES', 'CR', $crReal, 'OBSERVACIONES'); ?></textarea>	
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
	<input type="hidden" name="unicoLinks" id="unicoLinks">
	<input type="hidden" name="unicoTituloLinks" id="unicoTituloLinks">
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
	Autor(es) actuales: (<a href="#autores" onclick="edita('autores',1,'unico','<?php print $usuarios."','"; print $dato; ?>');">Reingresar autores</a>)<br/>
<?php
		print $dato;
?>	
	</div>
	<input type="hidden" name="unicoAutores" id="unicoAutores" title="unicoAutores hidden" value="<?php print $usuarios; ?>">
	<input type="hidden" name="unicoAutoresTurno" id="unicoAutoresTurno" title="unicoAutoresTurno hidden" value="<?php print $turno; ?>">
	<br/>	
	<br/>
	<div id="unicoDanosMaterialesEventoLeyenda">Resum&eacute;n de da&ntilde;os materiales del evento: </div> 
	<textarea id="unicoDanosMaterialesEvento" name="unicoDanosMaterialesEvento" title="Resum&eacute;n de da&ntilde;os materiales" rows="3" cols="50" onk onKeyUp="alertNumCaracTextArea('unicoDanosMaterialesEvento', 4000);"><?php print obtenerValorQuery($conOracle,'CENACOM', 'EVENTO', 'ID_EVENTO', $idEvento, 'DANOS_MATERIALES'); ?></textarea>	
	<br/>
	<br/>
	Declaratoria: <br/>
	<select id="unicoDeclaratoria"  name="unicoDeclaratoria" title="Declaratoria">
<?php
	$unicoDeclaratoria=obtenerValorQuery($conOracle,'CENACOM', 'EVENTO', 'ID_EVENTO', $idEvento, 'DECLARATORIA');
	if($unicoDeclaratoria=="NO"){
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