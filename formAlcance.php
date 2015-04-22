<?php
include 'functions.php';
include 'vars.php';
include 'ortografia.php';
session_start();
if(@$_SESSION['login'] != "si" || @$_POST["crReal"]==NULL)
{
header("Location:index.php");
exit();
}
$conOracle=conexionORACLE($HOST, $PORT, $SID, $userName, $passkey,'AL32UTF8');

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
<script type="text/javascript" src="js/validacionAlcance.js"></script>
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
	<h2>Reporte de tipo Alcance a <?php echo $crReal; ?></h2>



<form method="post" id="falcance" name="falcance" action="falcance.php">
	<input type="hidden" name="tipoReporte" id="tipoReporte" value="1">
	<input type="hidden" name="crRelacionado" id="crRelacionado" value="<?php echo $crReal; ?>">
	<input type="hidden" name="eventoRelacionado" id="eventoRelacionado" value="<?php echo $idEvento; ?>">
	<div id="alcanceEfectoAdversoLeyenda">Efecto adverso:</div>	
	<textarea name="alcanceEfectoAdverso" id="alcanceEfectoAdverso" title="Efecto adverso" rows="3" cols="50" onKeyUp="alertNumCaracTextArea('alcanceEfectoAdverso', 4000);"></textarea>
	<br/>
	<br/>

	Organismo que reporta: 
<?php
	$query = oci_parse($conOracle, 'SELECT ID, NOMBRE from CENACOM.DEPENDENCIAS ORDER BY NOMBRE ASC');
	comboQueryJS_1($query, "ID", "NOMBRE", 'alcanceOrganismoReporta', "Seleccione","Organismo que reporta");
?>
	<br/>
<?php
	fechaBox("Fecha en que reporta:", "alcanceFechaReporta", "alcanceFechaReporta", date('Y-m-d'), 'Fecha en que reporta');
?>
	<br/>
<?php
	hora("Hora que reporta:", "alcanceHoraQueReporta", "alcanceHoraQueReporta", 'Hora que reporta');
?>

<?php
	
	
	$alcanceEstado=obtenerValorQuery($conOracle,'CENACOM', 'REPORTES', 'CR', $crReal, 'ESTADO');
	$alcanceMunicipio=obtenerValorQuery($conOracle,'CENACOM', 'REPORTES', 'ID_REPORTE', $crReal, 'MUNICIPIO');
	$alcanceLocalidad=obtenerValorQuery($conOracle,'CENACOM', 'REPORTES', 'ID_REPORTE', $crReal, 'LOCALIDAD');
	
	
	$alcanceClasificacionFenomeno=obtenerValorQuery($conOracle,'CENACOM', 'REPORTES', 'CR', $crReal, 'CLASIFICACIONFENOMENO_ID');
	$alcanceTipoFenomeno=obtenerValorQuery($conOracle,'CENACOM', 'REPORTES', 'CR', $crReal, 'TIPOFENOMENO_ID');
	$alcanceFechaFenomenoM=obtenerValorFecha($conOracle,'CENACOM', 'REPORTES', 'CR', $crReal, 'FECHA_INICIO_FENOMENO');
	$alcanceFechaFenomeno = substr($alcanceFechaFenomenoM, 0, 11);
	$alcanceHoraInicialFenomenoval = substr($alcanceFechaFenomenoM, 11, -3);
	
?>

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
	
	<input type="hidden" name="alcanceClasificacionFenomeno" id="alcanceClasificacionFenomeno" value="<?php echo $alcanceClasificacionFenomeno; ?>">
	<input type="hidden" name="alcanceTipoFenomeno" id="alcanceTipoFenomeno" value="<?php echo $alcanceTipoFenomeno; ?>">
	<input type="hidden" name="alcanceFechaFenomeno" id="alcanceFechaFenomeno" value="<?php echo $alcanceFechaFenomeno; ?>">
	<input type="hidden" name="alcanceHoraInicialFenomenoval" id="alcanceHoraInicialFenomenoval" value="<?php echo $alcanceHoraInicialFenomenoval; ?>">
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
	fechaBox("Fecha del fen&oacute;meno:", "alcanceFechaFenomeno", "alcanceFechaFenomeno", date('Y-m-d'), 'Fecha del fen&oacute;meno');
?>
	<br/>
<?php
	hora("Hora inicial del fen&oacute;meno:", "HorainicialFenomeno", "alcanceHoraInicialFenomeno",'Hora inicial del fen&oacute;meno' );
?>
	<br/>
	Nivel: <br/>
<?php
	$query = oci_parse($conOracle,'SELECT ID, NIVEL from CENACOM.NIVEL ');
	comboQueryNivel($query,"ID","NIVEL","NIVEL","Seleccione","Nivel");
?>

<br/><br/>


	<div id="alcanceAreasAfectadasLeyenda">&Aacute;reas afectadas:</div>
	<textarea id="alcanceAreasAfectadas" name="alcanceAreasAfectadas" title="&Aacute;reas afectadas" rows="3" cols="50" onKeyUp="alertNumCaracTextArea('alcanceAreasAfectadas', 4000);"></textarea>	
	<br/>
	<br/>
	Personas afectadas:<br/>
	<input id="alcancePersonasAfectadas" name="alcancePersonasAfectadas" title="Personas afectadas" size="66" maxlength="999" type="text" value=""/>
	<br/>
	<br/>	
	Muertos: <input id="alcanceMuertos" name="alcanceMuertos" title="Muertos" maxlength="7" size="4" type="text" value="0"/>
	<br/>
	Lesionados: <input id="alcanceLesionados" name="alcanceLesionados" title="Lesionados" maxlength="7" size="4" type="text" value="0"/>
	<br/>
	Evacuados: <input id="alcanceEvacuados" name="alcanceEvacuados" title="Evacuados" maxlength="7" size="4" type="text" value="0"/>
	<br/>
	Desaparecidos: <input id="alcanceDesaparecidos" name="alcanceDesaparecidos" title="Desaparecidos" maxlength="7" size="4" type="text" value="0"/>	
	<br/><br/>
	<div id="alcanceLineasVitalesLeyenda">L&iacute;neas vitales:</div>
	<textarea id="alcanceLineasVitales" name="alcanceLineasVitales" title="L&iacute;neas vit&aacute;les" rows="3" cols="50" onKeyUp="alertNumCaracTextArea('alcanceLineasVitales', 4000);"></textarea>	
	<br/><br/>
	<div id="alcanceInfraestructuraLeyenda">Infraestructura da&ntilde;ada:</div>
	<textarea id="alcanceInfraestructura" name="alcanceInfraestructura" title="Infraestructura da&ntilde;ada" rows="3" cols="50" onKeyUp="alertNumCaracTextArea('alcanceInfraestructura',4000);"></textarea>
	<br/>
	<br/>
	Respuesta institucional: <br/>
<?php
	$query = oci_parse($conOracle, 'SELECT ID, NOMBRE from CENACOM.DEPENDENCIAS ORDER BY NOMBRE ASC');
	comboMultiple($query, "ID", "NOMBRE", 'alcanceRespuestaInstitucional', 'Respuesta institucional');
?>
	
	<br/>	
	<br/>
	<div id="alcanceObservacionesLeyenda">Observaciones:</div>
	<textarea id="alcanceObservaciones" name="alcanceObservaciones" title="Observaciones" rows="3" cols="50" onKeyUp="alertNumCaracTextArea('alcanceObservaciones', 4000);"></textarea>	
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
	<input type="hidden" name="alcanceLinks" id="alcanceLinks">
	<input type="hidden" name="alcanceTituloLinks" id="alcanceTituloLinks">
<br/>
<br/>
<!--Autor-->
	<div id="inicialAutorLeyenda">Fuente: </div>
	<textarea id="AUTOR" name="AUTOR" title="AUTOR" onk onKeyUp="alertNumCaracTextArea('AUTOR',100);"></textarea>
	<br/><br/>
<!--Fin Autor-->


<?php
	$alcanceObservacionesEvento=obtenerValorQuery($conOracle,'CENACOM', 'EVENTO', 'ID_EVENTO', $idEvento, 'OBSERVACIONES');
?>
	<div id="alcanceObservacionesEventoLeyenda">Observaciones Generales del evento:</div>
	<textarea readonly id="alcanceObservacionesEvento" name="alcanceObservacionesEvento" title="Obervaciones Generales del evento" rows="3" cols="50" onKeyUp="alertNumCaracTextArea('alcanceObservacionesEvento', 4000);"><?php echo $alcanceObservacionesEvento; ?></textarea>	
	<br/>
	<br/>
<?php
	$alcanceDanosMaterialesEvento=obtenerValorQuery($conOracle,'CENACOM', 'EVENTO', 'ID_EVENTO', $idEvento, 'DANOS_MATERIALES');
?>
	<div id="alcanceDanosMaterialesEventoLeyenda">Da&ntilde;os Materiales Generales del evento:</div> 
	<textarea readonly id="alcanceDanosMaterialesEvento" name="alcanceDanosMaterialesEvento" title="Da&ntilde;os Materiales Generales del evento" rows="3" cols="50" onk onKeyUp="alertNumCaracTextArea('alcanceDanosMaterialesEvento', 4000);"><?php echo $alcanceDanosMaterialesEvento; ?></textarea>
	<br/>
	<br/>
	<div id="alcanceDeclaratoriaLeyenda"> Declaratoria:</div>
	<select id="alcanceDeclaratoria"  name="alcanceDeclaratoria" title="Declaratoria">
<?php
	$alcanceDeclaratoria=obtenerValorQuery($conOracle,'CENACOM', 'EVENTO', 'ID_EVENTO', $idEvento, 'DECLARATORIA');
	if($alcanceDeclaratoria=="NO"){
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
			$dato = recuperaCampo($conOracle, 'CENACOM.TIPO_FENOMENO', 'NOMBRE', $alcanceTipoFenomeno, 'ID_FENOMENO');
			echo "<b>Fen&oacute;meno:</b> ".$dato;
			echo "<br>";
			$dato = recuperaCampo($conOracle, 'ANRO.LOCALIDADES', 'NOM_ENT', $alcanceEstado, 'ENTIDAD');
			echo "<b>Estado:</b> ".$dato;
			echo "<br>";

			$query = oci_parse($conOracle, 'SELECT NOM_MUN from ANRO.LOCALIDADES WHERE ENTIDAD = '.$alcanceEstado.' AND MUN ='.$alcanceMunicipio.' GROUP BY NOM_MUN');
			oci_execute($query);
			$row = oci_fetch_array($query, OCI_ASSOC);
			$dato= $row["NOM_MUN"];
			echo "<b>Municipio:</b> ".$dato;
			echo "<br>";
			
			$dato = $alcanceFechaFenomenoM;
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