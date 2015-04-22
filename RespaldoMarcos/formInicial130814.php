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
<script type="text/javascript" src="js/validacionInicial.js"></script>
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
	<h2>Reporte de tipo Inicial</h2>
	

<form method="post" id="finicial" name="finicial" action="finicial.php">
	<input type="hidden" name="tipoReporte" id="tipoReporte" value="2">
	<input type="hidden" name="crRelacionado" id="crRelacionado" value="0">
	<div id="inicialEfectoAdversoLeyenda">Efecto adverso:</div>	
	<textarea name="inicialEfectoAdverso" id="inicialEfectoAdverso" title="Efecto adverso" rows="3" cols="50" onKeyUp="alertNumCaracTextArea('inicialEfectoAdverso', 4000);"></textarea>
	<br/>
	<br/>

	Organismo que reporta: 
<?php
	$query = oci_parse($conOracle, 'SELECT ID, NOMBRE from CENACOM.DEPENDENCIAS ORDER BY NOMBRE ASC');
	comboQueryJS_1($query, "ID", "NOMBRE", 'inicialOrganismoReporta', "Seleccione","Organismo que reporta");
?>
	<br/>
<?php
	fechaBox("Fecha en que reporta:", "inicialFechaReporta", "inicialFechaReporta", date('Y-m-d'), 'Fecha en que reporta');
?>
	<br/>
<?php
	hora("Hora que reporta:", "inicialHoraQueReporta", "inicialHoraQueReporta", 'Hora que reporta');
?>
	<br/>
	Lugar del reporte
	<br/>
	Estado:
<?php
	/*$query = oci_parse($conOracle,'SELECT DISTINCT ENTIDAD, NOM_ENT FROM ANRO.LOCALIDADES ORDER BY ENTIDAD ASC');
	comboQueryEstado ($query,"ENTIDAD","NOM_ENT","inicialEstado","Seleccione","Estado");*/
?>
	<br/>
<?php
	$query = oci_parse($conOracle, 'SELECT ENTIDAD, NOM_ENT from ANRO.LOCALIDADES GROUP BY ENTIDAD, NOM_ENT ORDER BY ENTIDAD');
	comboQueryJSMultipleMun($query,'ENTIDAD', 'NOM_ENT', 'inicialEstado', 'inicialMunicipio', 'estado','Seleccionar');
?>	
	
<!-- Varios Lugares-->

<?php
	//$sqlMun1='SELECT MUN,NOM_MUN FROM ANRO.LOCALIDADES WHERE ENTIDAD=';
	//$sqlMun2=$_POST[inicialEstado];
	//$sqlMun3=' ORDER BY MUN ASC';
	//$sqlMun=$sqlMun1.$sqlMun2.$sqlMun3;
	//$query = oci_parse($conOracle,sqlMun);
	//comboQueryEstado ($query,"ENTIDAD","NOM_ENT","inicialEstado","Seleccione","Estado");
?>
	

<?php
	
	
	/*$query = oci_parse($conOracle, 'SELECT ENTIDAD, NOM_ENT from ANRO.LOCALIDADES GROUP BY ENTIDAD, NOM_ENT ORDER BY ENTIDAD');
	comboQueryJS_3c($query,"ENTIDAD", 'NOM_ENT', 'inicialEstado', 'inicialMunicipio', 'inicialLocalidad', 'municipio', 'localidad', 'Seleccionar', 'Lugar');*/
?>	

	<br/>
	Otro: <input id="inicialOtroLugar" name="inicialOtroLugar" maxlength="50" type="text" value=""/>
	<br/>
	<br/> 
	
	



<!-- Fin Varios Lugares-->
	<br/>
	Clasificación y tipo de fénomeno:
<?php
	$query = oci_parse($conOracle, 'SELECT ID, CLASIFICACION from ANRO.CLASIFICACIONFENOMENO ORDER BY ID');
	comboQueryJS($query,"ID", 'CLASIFICACION', 'inicialClasificacionFenomeno', 'inicialTipoFenomeno', 'fenomeno', 'Seleccionar','Clasificaci&oacute;n y tipo de f&eacute;nomeno');
?>
	<br/>
<?php
	fechaBox("Fecha del fen&oacute;meno:", "inicialFechaFenomeno", "inicialFechaFenomeno", date('Y-m-d'), 'Fecha del fen&oacute;meno');
?>
	<br/>
<?php
	hora("Hora inicial del fen&oacute;meno:", "inicialHoraInicialFenomeno", "inicialHoraInicialFenomeno",'Hora inicial del fen&oacute;meno' );
?>
	<br/>
	Nivel: 
<?php
	$query = oci_parse($conOracle,'SELECT ID, NIVEL from CENACOM.NIVEL ');
	comboQueryNivel($query,"ID","NIVEL","inicialNivel","Seleccione","Nivel");
?>
	<br/>
	<div id="inicialAreasAfectadasLeyenda">Áreas afectadas:</div>
	<textarea id="inicialAreasAfectadas" name="inicialAreasAfectadas" title="&Aacute;reas afectadas" rows="3" cols="50" onKeyUp="alertNumCaracTextArea('inicialAreasAfectadas', 4000);"></textarea>		
	<br/>
	<br/>
	Personas afectadas:<br/>
	<input id="inicialPersonasAfectadas" name="inicialPersonasAfectadas" title="Personas afectadas" size="66" maxlength="999" type="text" value=""/>
	<br/>
	<br/>	
	Muertos: <input id="inicialMuertos" name="inicialMuertos" title="Muertos" maxlength="7" size="4" type="text" value="0"/>
	<br/>
	Lesionados: <input id="inicialLesionados" name="inicialLesionados" title="Lesionados" maxlength="7" size="4" type="text" value="0"/>
	<br/>
	Evacuados: <input id="inicialEvacuados" name="inicialEvacuados" title="Evacuados" maxlength="7" size="4" type="text" value="0"/>
	<br/>
	Desaparecidos: <input id="inicialDesaparecidos" name="inicialDesaparecidos" title="Desaparecidos" maxlength="7" size="4" type="text" value="0"/>	
	<br/><br/>
	<div id="inicialLineasVitalesLeyenda">Líneas vitales:</div>
	<textarea id="inicialLineasVitales" name="inicialLineasVitales" title="L&iacute;neas vit&aacute;les" rows="3" cols="50" onKeyUp="alertNumCaracTextArea('inicialLineasVitales', 4000);"></textarea>
	<br/><br/>
	<div id="inicialInfraestructuraLeyenda">Infraestructura dañada:</div>
	<textarea id="inicialInfraestructura" name="inicialInfraestructura" title="Infraestructura da&ntilde;ada" rows="3" cols="50" onKeyUp="alertNumCaracTextArea('inicialInfraestructura',4000);"></textarea>
	<br/>
	<br/>
	Respuesta institucional: <br/>
<?php
	$query = oci_parse($conOracle, 'SELECT ID, NOMBRE from CENACOM.DEPENDENCIAS ORDER BY NOMBRE ASC');
	comboMultiple($query, "ID", "NOMBRE", 'inicialRespuestaInstitucional', 'Respuesta institucional');
?>
	
	<br/>	
	<br/>
	<div id="inicialObservacionesLeyenda">Observaciones:</div>
	<textarea id="inicialObservaciones" name="inicialObservaciones" title="Observaciones" rows="3" cols="50" onKeyUp="alertNumCaracTextArea('inicialObservaciones', 4000);"></textarea>	
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


	<div id="agregarLink"><a href="#links" onclick="agregarLink('link', 1);">
		Agregar otro link</a></div>
	<div id="link" >
		<div id="links0">
			Título de noticia:
		<input id="titulolinks0T" name="titulolinks0T" title="T&iacute;tulo de link" size="49" maxlength="990" type="text" value=""/>
		<br/>
			Link de noticia: <input id="links0T" name="links0T" title="URL del link" maxlength="980" size="50" type="text" value=""/>
		</div>	
	</div>
	<a name="links"></a>	
	<input type="hidden" name="inicialLinks" id="inicialLinks">
	<input type="hidden" name="inicialTituloLinks" id="inicialTituloLinks">
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
	//comboMultipleAutores($query, 'inicialAutores', 'Autores');
	$query = oci_parse($conOracle, 'SELECT ID_TURNO, NOMBRE from CENACOM.TURNOS ORDER BY ID_TURNO');
	comboQueryJSMultiple($query,"ID_TURNO", 'NOMBRE', 'inicialAutoresTurnoC', 'inicialAutoresC', 'autores', 'Seleccionar turno','Autores');
?>
	<input type="hidden" name="inicialAutores" id="inicialAutores" value="0">
	<input type="hidden" name="inicialAutoresTurno" id="inicialAutoresTurno" title="inicialAutoresTurno hidden" value="0">
	<br/>	
	<br/>
	<div id="inicialDanosMaterialesEventoLeyenda">Resum&eacute;n de da&ntilde;os materiales: </div> 
	<textarea id="inicialDanosMaterialesEvento" name="inicialDanosMaterialesEvento" title="Resum&eacute;n de da&ntilde;os materiales" rows="3" cols="50" onk onKeyUp="alertNumCaracTextArea('inicialDanosMaterialesEvento', 4000);"></textarea>	
	
	<div id="boton">
		<input type="button"  value="Registrar" onclick="valida()" />
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
			<p>Sistema Nacional de Protección Civil</p>
		</div>
	</div>
</body>
</html>