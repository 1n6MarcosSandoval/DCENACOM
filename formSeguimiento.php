<?php
include 'functions.php';
include 'vars.php';
include 'ortografia.php';

session_start();
if(@$_SESSION['login'] != "si"  || @$_POST["crReal"]==NULL)
{
header("Location:index.php");
exit();
}


$conOracle=conexionORACLE($HOST, $PORT, $SID, $userName, $passkey);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
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
	$seguimientoEstado=obtenerValorQuery($conOracle,'CENACOM', 'GEOR', 'ID_REPORTE', $crReal, 'ENTIDADES');
	$seguimientoMunicipio=obtenerValorQuery($conOracle,'CENACOM', 'GEOR', 'ID_REPORTE', $crReal, 'MUNS');
	$seguimientoLocalidad=obtenerValorQuery($conOracle,'CENACOM', 'GEOR', 'ID_REPORTE', $crReal, 'LOCS');
	$seguimientoClasificacionFenomeno=obtenerValorQuery($conOracle,'CENACOM', 'REPORTES', 'CR', $crReal, 'CLASIFICACIONFENOMENO_ID');
	$seguimientoTipoFenomeno=obtenerValorQuery($conOracle,'CENACOM', 'REPORTES', 'CR', $crReal, 'TIPOFENOMENO_ID');
	$seguimientoFechaFenomenoM=obtenerValorFecha($conOracle,'CENACOM', 'REPORTES', 'CR', $crReal, 'FECHA_INICIO_FENOMENO');
	$seguimientoFechaFenomeno = substr($seguimientoFechaFenomenoM, 0, 11);
	$seguimientoHoraInicialFenomenoval = substr($seguimientoFechaFenomenoM, 11, -3);
	
?>
	<br/>
<!-- Varios Lugares-->
	Selecci&oacute;n de &Aacute;rea o punto a visualizar: <br/>
	<select id="SELECCION"  name="SELECCION" title="SELECCION">
	<option value="PUNTO">PUNTO</option>
	<option value="AREA">&Aacute;REA</option>
	</select>
	<br/>
	<br/>
	Lugar del incidente:
	<br/>
	<br/>
	<div id="agregarLugar"><a href="#Lugar" onclick="agregarLugar('lugar',0)">Agregar Lugar</a></div>
	<div id="lugar">
		<div id = "lugar0">
		</div>
	</div>
	<br/>
<!-- Fin Varios Lugares-->

	<input type="hidden" name="seguimientoClasificacionFenomeno" id="seguimientoClasificacionFenomeno" value="<?php echo $seguimientoClasificacionFenomeno; ?>">
	<input type="hidden" name="seguimientoTipoFenomeno" id="seguimientoTipoFenomeno" value="<?php echo $seguimientoTipoFenomeno; ?>">
	<input type="hidden" name="seguimientoFechaFenomeno" id="seguimientoFechaFenomeno" value="<?php echo $seguimientoFechaFenomeno; ?>">
	<input type="hidden" name="seguimientoHoraInicialFenomenoval" id="seguimientoHoraInicialFenomenoval" value="<?php echo $seguimientoHoraInicialFenomenoval; ?>">
	<br/>
	
	
<!--Fenomenos-->
	Clasificaci&oacute;n y tipo de f&eacute;nomeno:<br/>
<?php
	$query = oci_parse($conOracle, 'SELECT ID, CLASIFICACION from ANRO.CLASIFICACIONFENOMENO ORDER BY ID');
	comboQueryJS($query,"ID", 'CLASIFICACION', 'inicialClasificacionFenomeno', 'inicialTipoFenomeno', 'fenomeno', 'Seleccionar','Clasificaci&oacute;n y tipo de f&eacute;nomeno');
?>

	<br/>
	Fen&oacute;meno de Mayor Afectac&iacute;on: 
	<br/>
<?php
	$query = oci_parse($conOracle, 'SELECT ID, CLASIFICACION from ANRO.CLASIFICACIONFENOMENO ORDER BY ID');
	comboQueryJS($query,"ID", 'CLASIFICACION', 'FENOMENOMAYORAFECTACION', 'TIPOFENOMENOMAYORAFECTACION', 'fenomeno', 'Seleccionar','Clasificaci&oacute;n y tipo de f&eacute;nomeno');
?>	
	<!--Fin Fenomenos-->
	<br/><br/>
<?php
	fechaBox("Fecha del fen&oacute;meno:", "FechaFenomeno", "FechaFenomeno", date('Y-m-d'), 'Fecha del fen&oacute;meno');
?>
	<br/>
<?php
	hora("Hora inicial del fen&oacute;meno:", "HoraFenomeno", "HoraInicialFenomeno",'Hora inicial del fen&oacute;meno' );
?>
	<br/>
	Nivel: <br/>
<?php
	$query = oci_parse($conOracle,'SELECT ID, NIVEL from CENACOM.NIVEL ');
	comboQueryNivel($query,"ID","NIVEL","NIVEL","Seleccione","Nivel");
?>	
<br/><br/>	
	
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


<!--Autor-->
	<div id="inicialAutorLeyenda">Fuente: </div>
	<textarea id="AUTOR" name="AUTOR" title="AUTOR" onk onKeyUp="alertNumCaracTextArea('AUTOR',100);"></textarea>
	<br/><br/>
<!--Fin Autor-->

	
<?php
	$seguimientoObservacionesEvento=obtenerValorQuery($conOracle,'CENACOM', 'EVENTO', 'ID_EVENTO', $idEvento, 'OBSERVACIONES');
?>
	 Obervaciones Generales del evento: <br/>
	<textarea readonly id="seguimientoObservacionesEvento" name="seguimientoObservacionesEvento" title="Obervaciones Generales del evento" rows="3" cols="50"><?php echo $seguimientoObservacionesEvento; ?></textarea>	
<?php
	$seguimientoDanosMaterialesEvento=obtenerValorQuery($conOracle,'CENACOM', 'EVENTO', 'ID_EVENTO', $idEvento, 'DANOS_MATERIALES');
?>
	<br/>Da&ntilde;os Materiales Generales del evento: <br/>
	<textarea readonly  id="seguimientoDanosMaterialesEvento" name="seguimientoDanosMaterialesEvento" title="Daños Materiales Generales del evento" rows="3" cols="50"><?php echo $seguimientoDanosMaterialesEvento; ?></textarea>
	<br/>Declaratoria: <br/>
	<select style="visibility;" id="seguimientoDeclaratoria"  name="seguimientoDeclaratoria" title="Declaratoria">
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
	<br/><br/>
	
		Visible de manera automatico: 
<!--Para que un evento solo sea visible por tiempo propuesto 5 hrs-->
	<select id="AUTO_QUITAR" name="AUTO_QUITAR" title="AUTO_QUITAR">
		<option value=0>No</option>
		<option value=1>Si</option>
	</select>
	<br/><br/>	
	
		Atendido:
<!--Para que un evento se pinte la no ser atendido y desaparesca al ser atendido-->		
	<select id="ATENDIDO" name="ATENDIDO" title="ATENDIDO">
		<option value=0>No</option>
		<option value=1>Si</option>
	</select>
	<br/><br/>
	
	<?php
	ortografia();
	?>
	
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
			
			$query = oci_parse($conOracle, 'SELECT NOM_MUN from ANRO.LOCALIDADES WHERE ENTIDAD = '.$seguimientoEstado.' AND MUN ='.$seguimientoMunicipio.' GROUP BY NOM_MUN');
			oci_execute($query);
			$row = oci_fetch_array($query, OCI_ASSOC);
			$dato= $row["NOM_MUN"];
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