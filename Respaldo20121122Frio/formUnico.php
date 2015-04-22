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
	//$query = oci_parse($conOracle, 'SELECT ID, NOMBRE from CENACOM.DEPENDENCIAS ORDER BY ID');
	//comboMultiple($query, "ID", "NOMBRE", 'unicoOrganismoReporta');
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
	Lugar:
<?php
	$query = oci_parse($conOracle, 'SELECT ENTIDAD, NOM_ENT from ANRO.LOCALIDADES GROUP BY ENTIDAD, NOM_ENT ORDER BY ENTIDAD');
	comboQueryJS_3c($query,"ENTIDAD", 'NOM_ENT', 'unicoEstado', 'unicoMunicipio', 'unicoLocalidad', 'municipio', 'localidad', 'Seleccionar', 'Lugar');
?>	
	<br/>
	Otro: <input id="unicoOtroLugar" name="unicoOtroLugar" maxlength="1000" type="text" value=""/>
	<br/>
	<br/>
	Clasificaci&oacute;n y tipo de f&eacute;nomeno:
<?php
	$query = oci_parse($conOracle, 'SELECT ID, CLASIFICACION from ANRO.CLASIFICACIONFENOMENO ORDER BY ID');
	comboQueryJS($query,"ID", 'CLASIFICACION', 'unicoClasificacionFenomeno', 'unicoTipoFenomeno', 'fenomeno', 'Seleccionar','Clasificaci&oacute;n y tipo de f&eacute;nomeno');
?>
	<br/>
<?php
	fechaBox("Fecha del fen&oacute;meno:", "unicoFechaFenomeno", "unicoFechaFenomeno", date('Y-m-d'), 'Fecha del fen&oacute;meno');
?>
	<br/>
<?php
	hora("Hora inicial del fen&oacute;meno:", "unicoHoraInicialFenomeno", "unicoHoraInicialFenomeno",'Hora inicial del fen&oacute;meno' );
?>
	<br/>
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
<!-- Para agregar autores de forma de capas	
<div id="autor"></div>
<a name="autores"></a>
<div id="agregarAutor"><a href="#autores" onclick="agregarAutor('autor', 0);">Agregar Autor</a></div>
FIN Para agregar autores de forma de capas -->
Usuario(s) que registra(n) el reporte: <br/>
<?php
	//$query = oci_parse($conOracle, 'SELECT ID_USUARIO, NOMBRE, APELLIDO from CENACOM.USUARIOS_CENACOM ORDER BY ID_USUARIO');
	//comboMultipleAutores($query, 'unicoAutores', 'Autores');
	$query = oci_parse($conOracle, 'SELECT ID_TURNO, NOMBRE from CENACOM.TURNOS ORDER BY ID_TURNO');
	comboQueryJSMultiple($query,"ID_TURNO", 'NOMBRE', 'unicoAutoresTurno', 'unicoAutores', 'autores', 'Seleccionar turno','Autores');
	
	
?>
	<br/>	
	<br/>
	<div id="unicoDanosMaterialesEventoLeyenda">Resum&eacute;n de da&ntilde;os materiales: </div> 
	<textarea id="unicoDanosMaterialesEvento" name="unicoDanosMaterialesEvento" title="Resum&eacute;n de da&ntilde;os materiales" rows="3" cols="50" onk onKeyUp="alertNumCaracTextArea('unicoDanosMaterialesEvento', 4000);"></textarea>	
	<br/>
	<br/>
	Declaratoria: <br/>
	<select id="unicoDeclaratoria"  name="unicoDeclaratoria" title="Declaratoria">
	<option value="NO">No</option>
	<option value="SI">Si</option>
	</select>
	<br/>
	<br/>

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