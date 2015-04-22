<?php
include 'functions.php';
include 'vars.php';
include 'ortografia.php';
session_start();
if(@$_SESSION['login'] != "si")
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
<!--<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />-->
<title>Reportes relevantes</title>
<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/dojo/1.8/dijit/themes/claro/claro.css">
<link rel="stylesheet" href="images/style.css" type="text/css" />
<script src="//ajax.googleapis.com/ajax/libs/dojo/1.8.0/dojo/dojo.js" data-dojo-config='async: true, parseOnLoad: true'></script>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/functions.js"></script>
<script type="text/javascript" src="js/validacionUnico.js"></script>
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

			
<form method="post" id="funico" name="funico" action="funico.php">
	<input type="hidden" name="tipoReporte" id="tipoReporte" value="0">
	<input type="hidden" name="crRelacionado" id="crRelacionado" value="0">
	<div id="unicoEfectoAdversoLeyenda">Efecto adverso:</div>	
	<textarea name="unicoEfectoAdverso" id="unicoEfectoAdverso" title="Efecto adverso" rows="3" cols="50" onKeyUp="alertNumCaracTextArea('unicoEfectoAdverso', 4000);"></textarea>
	<br/>
	<br/>

	Organismo que reporta: 
<?php

	$query = oci_parse($conOracle, 'SELECT ID, NOMBRE from CENACOM.DEPENDENCIAS ORDER BY NOMBRE ASC');
	comboQueryJS_1($query, "ID", "NOMBRE", 'unicoOrganismoReporta', "Seleccione","Organismo que reporta");
?>
	<br/>

	
<?php
	fechaBox("Fecha en que reporta:", "unicoFechaReporta", "unicoFechaReporta", date('Y-m-d'), 'Fecha en que reporta');
?>
	<br/>
<?php
	hora("Hora que reporta:", "unicoHoraQueReporta", "unicoHoraQueReporta", 'Hora que reporta');
?>
	<br/>

<!--Varios Lugares-->
	Selecci&oacute;n de &Aacute;rea o punto a visualizar: <br/>
	<select id="SELECCION"  name="SELECCION" title="SELECCION">
	<option value="PUNTO">PUNTO</option>
	<option value="AREA">&Aacute;REA</option>
	</select>

	<br/><br/>
	Lugar del incidente:
	<br/><br/>

	<div id="agregarLugar"><a href="#Lugar" onclick="agregarLugar('lugar',0)">Agregar Lugar</a></div>
	<div id="lugar">
		<div id = "lugar0">
		</div>
	</div>
<!-- Fin Varios Lugares-->
	<br/>

	<br/>
	<br/>
	Clasificaci&oacute;n y tipo de f&eacute;nomeno:
<?php
	$query = oci_parse($conOracle, 'SELECT ID, CLASIFICACION from ANRO.CLASIFICACIONFENOMENO ORDER BY ID');
	comboQueryJS($query,"ID", 'CLASIFICACION', 'unicoClasificacionFenomeno', 'unicoTipoFenomeno', 'fenomeno', 'Seleccionar','Clasificaci&oacute;n y tipo de f&eacute;nomeno');
?>
	<br/>
	Fen&oacute;meno de Mayor Afectac&iacute;on: 
	<br/>
<?php
	$query = oci_parse($conOracle, 'SELECT ID, CLASIFICACION from ANRO.CLASIFICACIONFENOMENO ORDER BY ID');
	comboQueryJS($query,"ID", 'CLASIFICACION', 'FENOMENOMAYORAFECTACION', 'TIPOFENOMENOMAYORAFECTACION', 'fenomeno', 'Seleccionar','Clasificaci&oacute;n y tipo de f&eacute;nomeno');
?>	
	<br/><br/>
<?php
	fechaBox("Fecha del fen&oacute;meno:", "unicoFechaFenomeno", "unicoFechaFenomeno", date('Y-m-d'), 'Fecha del fen&oacute;meno');
?>
	<br/>
<?php
	hora("Hora inicial del fen&oacute;meno:", "unicoHoraInicialFenomeno", "unicoHoraInicialFenomeno",'Hora inicial del fen&oacute;meno' );
?>
	<br/>
	Nivel: <br/>
<?php
	$query = oci_parse($conOracle,'SELECT ID, NIVEL from CENACOM.NIVEL ');
	comboQueryNivel($query,"ID","NIVEL","unicoNivel","Seleccione","Nivel");
?>
	
	<br/>
	<div id="unicoAreasAfectadasLeyenda">&Aacute;reas afectadas:</div>
	<textarea id="unicoAreasAfectadas" name="unicoAreasAfectadas" title="&Aacute;reas afectadas" rows="3" cols="50" onKeyUp="alertNumCaracTextArea('unicoAreasAfectadas', 4000);"></textarea>	
	<br/>
	<br/>
	Personas afectadas:<br/>
	<input id="unicoPersonasAfectadas" name="unicoPersonasAfectadas" title="Personas afectadas" size="66" maxlength="999" type="text" value=""/>
	<br/>
	<br/>	
	Muertos: <input id="unicoMuertos" name="unicoMuertos" title="Muertos" maxlength="7" size="4" type="text" value="0"/>
	<br/>
	Lesionados: <input id="unicoLesionados" name="unicoLesionados" title="Lesionados" maxlength="7" size="4" type="text" value="0"/>
	<br/>
	Evacuados: <input id="unicoEvacuados" name="unicoEvacuados" title="Evacuados" maxlength="7" size="4" type="text" value="0"/>
	<br/>
	Desaparecidos: <input id="unicoDesaparecidos" name="unicoDesaparecidos" title="Desaparecidos" maxlength="7" size="4" type="text" value="0"/>	
	<br/><br/>
	<div id="unicoLineasVitalesLeyenda">L&iacute;neas vitales:</div>
	<textarea id="unicoLineasVitales" name="unicoLineasVitales" title="L&iacute;neas vit&aacute;les" rows="3" cols="50" onKeyUp="alertNumCaracTextArea('unicoLineasVitales', 4000);"></textarea>	<br/><br/>
	<div id="unicoInfraestructuraLeyenda">Infraestructura da&ntilde;ada:</div>
	<textarea id="unicoInfraestructura" name="unicoInfraestructura" title="Infraestructura da&ntilde;ada" rows="3" cols="50" onKeyUp="alertNumCaracTextArea('unicoInfraestructura',4000);"></textarea>	<br/>
	<br/>
	Respuesta institucional: <br/>
<?php
	$query = oci_parse($conOracle, 'SELECT ID, NOMBRE from CENACOM.DEPENDENCIAS ORDER BY NOMBRE ASC');
	comboMultiple($query, "ID", "NOMBRE", 'unicoRespuestaInstitucional', 'Respuesta institucional');
?>
	
	<br/>	
	<br/>
	<div id="unicoObservacionesLeyenda">Observaciones:</div>
	<textarea id="unicoObservaciones" name="unicoObservaciones" title="Observaciones" rows="3" cols="50" onKeyUp="alertNumCaracTextArea('unicoObservaciones', 4000);"></textarea>	
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
	<input type="hidden" name="unicoLinks" id="unicoLinks">
	<input type="hidden" name="unicoTituloLinks" id="unicoTituloLinks">
<br/>
<br/>

	<div id="inicialAutorLeyenda">Fuente: </div>
	<textarea id="AUTOR" name="AUTOR" title="AUTOR" onk onKeyUp="alertNumCaracTextArea('AUTOR',100);"></textarea>
	<br/><br/>

	<input type="hidden" name="unicoAutores" id="unicoAutores" value="0">
	<input type="hidden" name="unicoAutoresTurno" id="unicoAutoresTurno" title="unicoAutoresTurno hidden" value="0">
	<br/>
	<div id="unicoDanosMaterialesEventoLeyenda">Resum&eacute;n de da&ntilde;os materiales: </div> 
	<textarea id="unicoDanosMaterialesEvento" name="unicoDanosMaterialesEvento" title="Resum&eacute;n de da&ntilde;os materiales" rows="3" cols="50" onk onKeyUp="alertNumCaracTextArea('unicoDanosMaterialesEvento', 4000);"></textarea>	
	<br/>
	<br/>
	Declaratoria: 
	<select id="unicoDeclaratoria"  name="unicoDeclaratoria" title="Declaratoria">
	<option value="NO">No</option>
	<option value="SI">Si</option>
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