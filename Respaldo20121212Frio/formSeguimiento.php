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
<script type="text/javascript" src="js/validacionSeguimiento.js"></script>
<script>
require(["dojo/parser", "dijit/form/DateTextBox"]);
</script>

</head>
<body class="claro">
	
	<?php
	include 'formsMenuHead.php';
	?>

	<?php
		$crReal=$_POST["crReal"];
		$idEvento=obtenerValorQuery($conOracle,'CENACOM', 'REPORTES', 'CR', $crReal, 'ID_EVENTO');
	?>		
	
<h1>Sistema de captura</h1>

		<!--IZQUIERDA -->
		<div class="left">
		
			<div class="lboxForm">
	<h2>Reporte de tipo Seguimiento a <?php echo $crReal; ?></h2>


<form method="post" id="fseguimiento" name="fseguimiento" action="fseguimiento.php">
	<input type="hidden" name="tipoReporte" id="tipoReporte" value="3">
	<input type="hidden" name="crRelacionado" id="crRelacionado" value="<?php echo $crReal; ?>">
	<input type="hidden" name="eventoRelacionado" id="eventoRelacionado" value="<?php echo $idEvento; ?>">
	<div id="seguimientoEfectoAdversoLeyenda">Efecto adverso:</div>	
	<textarea name="seguimientoEfectoAdverso" id="seguimientoEfectoAdverso" title="Efecto adverso" rows="3" cols="50" onKeyUp="alertNumCaracTextArea('seguimientoEfectoAdverso', 4000);"></textarea>
	<br/>
	<br/>

	Organismo que reporta: 
<?php
	$query = oci_parse($conOracle, 'SELECT ID, NOMBRE from CENACOM.DEPENDENCIAS ORDER BY NOMBRE ASC');
	comboQueryJS_1($query, "ID", "NOMBRE", 'seguimientoOrganismoReporta', "Seleccione","Organismo que reporta");
?>
	<br/>
<?php
	fechaBox("Fecha en que reporta:", "seguimientoFechaReporta", "seguimientoFechaReporta", date('Y-m-d'), 'Fecha en que reporta');
?>
	<br/>
<?php
	hora("Hora que reporta:", "seguimientoHoraQueReporta", "seguimientoHoraQueReporta", 'Hora que reporta');
?>

<?php
	$seguimientoEstado=obtenerValorQuery($conOracle,'CENACOM', 'REPORTES', 'CR', $crReal, 'ESTADO');
	$seguimientoMunicipio=obtenerValorQuery($conOracle,'CENACOM', 'REPORTES', 'CR', $crReal, 'MUNICIPIO');
	$seguimientoLocalidad=obtenerValorQuery($conOracle,'CENACOM', 'REPORTES', 'CR', $crReal, 'LOCALIDAD');
	$seguimientoOtroLugar=obtenerValorQuery($conOracle,'CENACOM', 'REPORTES', 'CR', $crReal, 'OTRO_LUGAR');
	$seguimientoClasificacionFenomeno=obtenerValorQuery($conOracle,'CENACOM', 'REPORTES', 'CR', $crReal, 'CLASIFICACIONFENOMENO_ID');
	$seguimientoTipoFenomeno=obtenerValorQuery($conOracle,'CENACOM', 'REPORTES', 'CR', $crReal, 'TIPOFENOMENO_ID');
	$seguimientoFechaFenomenoM=obtenerValorFecha($conOracle,'CENACOM', 'REPORTES', 'CR', $crReal, 'FECHA_INICIO_FENOMENO');
	$seguimientoFechaFenomeno = substr($seguimientoFechaFenomenoM, 0, 11);
	$seguimientoHoraInicialFenomenoval = substr($seguimientoFechaFenomenoM, 11, -3);
	
?>
	<br/>
	<input type="hidden" name="seguimientoEstado" id="seguimientoEstado" value="<?php echo $seguimientoEstado; ?>">
	<input type="hidden" name="seguimientoMunicipio" id="seguimientoMunicipio" value="<?php echo $seguimientoMunicipio; ?>">
	<input type="hidden" name="seguimientoLocalidad" id="seguimientoEstado" value="<?php echo $seguimientoLocalidad; ?>">
	<input type="hidden" name="seguimientoOtroLugar" id="seguimientoEstado" value="<?php echo $seguimientoOtroLugar; ?>">
	<input type="hidden" name="seguimientoClasificacionFenomeno" id="seguimientoClasificacionFenomeno" value="<?php echo $seguimientoClasificacionFenomeno; ?>">
	<input type="hidden" name="seguimientoTipoFenomeno" id="seguimientoTipoFenomeno" value="<?php echo $seguimientoTipoFenomeno; ?>">
	<input type="hidden" name="seguimientoFechaFenomeno" id="seguimientoFechaFenomeno" value="<?php echo $seguimientoFechaFenomeno; ?>">
	<input type="hidden" name="seguimientoHoraInicialFenomenoval" id="seguimientoHoraInicialFenomenoval" value="<?php echo $seguimientoHoraInicialFenomenoval; ?>">
	<br/>
	<div id="seguimientoAreasAfectadasLeyenda">&Aacute;reas afectadas:</div>
	<textarea id="seguimientoAreasAfectadas" name="seguimientoAreasAfectadas" title="&Aacute;reas afectadas" rows="3" cols="50" onKeyUp="alertNumCaracTextArea('seguimientoAreasAfectadas', 4000);"></textarea>		
	<br/>
	<br/>
	Personas afectadas:<br/>
	<input id="seguimientoPersonasAfectadas" name="seguimientoPersonasAfectadas" title="Personas afectadas" size="66" maxlength="999" type="text" value=""/>
	<br/>
	<br/>	
	Muertos: <input id="seguimientoMuertos" name="seguimientoMuertos" title="Muertos" maxlength="7" size="4" type="text" value="0"/>
	<br/>
	Lesionados: <input id="seguimientoLesionados" name="seguimientoLesionados" title="Lesionados" maxlength="7" size="4" type="text" value="0"/>
	<br/>
	Evacuados: <input id="seguimientoEvacuados" name="seguimientoEvacuados" title="Evacuados" maxlength="7" size="4" type="text" value="0"/>
	<br/>
	Desaparecidos: <input id="seguimientoDesaparecidos" name="seguimientoDesaparecidos" title="Desaparecidos" maxlength="7" size="4" type="text" value="0"/>	
	<br/><br/>
	<div id="seguimientoLineasVitalesLeyenda">L&iacute;neas vitales:</div>
	<textarea id="seguimientoLineasVitales" name="seguimientoLineasVitales" title="L&iacute;neas vit&aacute;les" rows="3" cols="50" onKeyUp="alertNumCaracTextArea('seguimientoLineasVitales', 4000);"></textarea>	
	<br/><br/>
	<div id="seguimientoInfraestructuraLeyenda">Infraestructura da&ntilde;ada:</div>
	<textarea id="seguimientoInfraestructura" name="seguimientoInfraestructura" title="Infraestructura da&ntilde;ada" rows="3" cols="50" onKeyUp="alertNumCaracTextArea('seguimientoInfraestructura',4000);"></textarea>
	<br/>
	<br/>
	Respuesta institucional: <br/>
<?php
	$query = oci_parse($conOracle, 'SELECT ID, NOMBRE from CENACOM.DEPENDENCIAS ORDER BY NOMBRE ASC');
	comboMultiple($query, "ID", "NOMBRE", 'seguimientoRespuestaInstitucional', 'Respuesta institucional');
?>
	
	<br/>	
	<br/>
	<div id="seguimientoObservacionesLeyenda">Observaciones:</div>
	<textarea id="seguimientoObservaciones" name="seguimientoObservaciones" title="Observaciones" rows="3" cols="50" onKeyUp="alertNumCaracTextArea('seguimientoObservaciones', 4000);"></textarea>		
	<br/>
	<br/>
	
<!-- Funciones y datos anadiendo layers -->	
<?php
	$query = oci_parse($conOracle, "SELECT COUNT(ID_USUARIO) AS CUENTA from CENACOM.USUARIOS_CENACOM");
	$numAutores=contarRegistros($query);
?>
<script language="JavaScript">
	function agregarAutor(bloque, cuenta){
		autoresBD=<?php print $numAutores; ?>;
		autoresForm=cuentaElementosBloque(bloque);
		if(autoresBD == autoresForm){
			alert("No se pueden agregar m&aacute;s autores")
		}else{
			escribeLayerAutor(cuenta)
		}		
	}
	
	function agregarLink(bloque, cuenta){
		numeroLinks=4;		
		linkForm=cuentaElementosBloque(bloque);
		if(linkForm == numeroLinks){
			alert("Solo se pueden agregar "+numeroLinks+" Links")
		}else{
			escribeLayerLink(cuenta)
		}		
	}	
	
</script>
<!-- FIN Funciones y datos anadiendo layers -->


	<div id="agregarLink"><a href="#links" onclick="agregarLink('link', 1);">Agregar otro link</a></div>
	<div id="link" >
		<div id="links0">
		T&iacute;tulo de noticia:
		<input id="titulolinks0T" name="titulolinks0T" title="T&iacute;tulo de link" size="49" maxlength="990" type="text" value=""/>
		<br/>
		Link de noticia: <input id="links0T" name="links0T" title="URL del link" maxlength="980" size="50" type="text" value=""/>
		</div>	
	</div>
	<a name="links"></a>	
	<input type="hidden" name="seguimientoLinks" id="seguimientoLinks">
	<input type="hidden" name="seguimientoTituloLinks" id="seguimientoTituloLinks">
<br/>
<br/>
<!-- Para agregar autores de forma de capas	
<div id="autor"></div>
<a name="autores"></a>
<div id="agregarAutor"><a href="#autores" onclick="agregarAutor('autor', 0);">Agregar Autor</a></div>
FIN Para agregar autores de forma de capas -->
Usuario(s) que registra(n) el reporte: <br/>
<?php
	//$query = oci_parse($conOracle, 'SELECT ID_USUARIO, NOMBRE, APELLIDO from CENACOM.USUARIOS_CENACOM ORDER BY ID_USUARIO');
	//comboMultipleAutores($query, 'seguimientoAutores', 'Autores');
	$query = oci_parse($conOracle, 'SELECT ID_TURNO, NOMBRE from CENACOM.TURNOS ORDER BY ID_TURNO');
	comboQueryJSMultiple($query,"ID_TURNO", 'NOMBRE', 'seguimientoAutoresTurno', 'seguimientoAutores', 'autores', 'Seleccionar turno','Autores');
	
	
?>

<?php
	$seguimientoObservacionesEvento=obtenerValorQuery($conOracle,'CENACOM', 'EVENTO', 'ID_EVENTO', $idEvento, 'OBSERVACIONES');
?>
	<!-- Obervaciones Generales del evento: <br/> --> 
	<textarea style="visibility:hidden;"  id="seguimientoObservacionesEvento" name="seguimientoObservacionesEvento" title="Obervaciones Generales del evento" rows="3" cols="50"><?php echo $seguimientoObservacionesEvento; ?></textarea>	
<?php
	$seguimientoDanosMaterialesEvento=obtenerValorQuery($conOracle,'CENACOM', 'EVENTO', 'ID_EVENTO', $idEvento, 'DANOS_MATERIALES');
?>
	<!-- Da&ntilde;os Materiales Generales del evento: <br/> -->
	<textarea style="visibility:hidden;"  id="seguimientoDanosMaterialesEvento" name="seguimientoDanosMaterialesEvento" title="Daños Materiales Generales del evento" rows="3" cols="50"><?php echo $seguimientoDanosMaterialesEvento; ?></textarea>
	<!--Declaratoria: <br/> -->
	<select style="visibility:hidden;" id="seguimientoDeclaratoria"  name="seguimientoDeclaratoria" title="Declaratoria">
<?php
	$seguimientoDeclaratoria=obtenerValorQuery($conOracle,'CENACOM', 'EVENTO', 'ID_EVENTO', $idEvento, 'DECLARATORIA');
	if($seguimientoDeclaratoria=="NO"){
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
	<div id="boton">
		<input type="button"  value="Registrar" onclick="valida()" />
	</div>
</form>

			</div>
		</div>
		<!-- FIN DE IZQUIERDA -->
		
	<?php
		//include 'derechaForm.php';
	?>	
	
		<div class="right">
						
			<div class="rt"></div>
			<div class="right_articles">
			<?php 
			$dato = recuperaCampo($conOracle, 'CENACOM.TIPO_FENOMENO', 'NOMBRE', $seguimientoTipoFenomeno, 'ID_FENOMENO');
			echo "<b>Fen&oacute;meno:</b> ".$dato;
			echo "<br>";
			$dato = recuperaCampo($conOracle, 'ANRO.LOCALIDADES', 'NOM_ENT', $seguimientoEstado, 'ENTIDAD');
			echo "<b>Estado:</b> ".$dato;
			echo "<br>";
			$dato = recuperaCampo($conOracle, 'ANRO.LOCALIDADES', 'NOM_MUN', $seguimientoMunicipio, 'MUN');
			echo "<b>Municipio:</b> ".$dato;
			echo "<br>";
			$dato = $seguimientoFechaFenomenoM;
			echo "<b>Fecha:</b> ".$dato;
			
			?>
			</div>
		</div>	
	
<?php
	cerrarConexionORACLE($conOracle);
?>

		<div class="footer">
			<p>Sistema Nacional de Protecci&oacute;n Civil</p>
		</div>
	</div>
</body>
</html>