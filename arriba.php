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

<script>
require(["dojo/parser", "dijit/form/DateTextBox"]);
</script>

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-37128328-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>



</head>
<body class="claro">
	
	<div class="content">
		<div class="header_right">
			<P ALIGN=right><b>Usuario: <?php print $_SESSION['nombre']; ?></b></p>	
			<div class="bar">
				<ul>
					<!--<li class="slogan">Menu:</li>-->
					<li ><a href="principal.php" accesskey="b">Inicio</a></li>
					<li><a href="lista.php" accesskey="a">Listado de reportes</a></li>
					<li><a href="cerrar_sesion.php"><b> Salir </b></a></li>
				</ul>
			</div>
		</div>
			<br/><br/>
		<div class="logo">
			<img src="images/cenacom.png" height="230" width="600"/>
		</div>
		
		<div class="search_field">
			<!--
			<form method="post" action="?">
				<p><span class="grey">Buscar noticias:</span> <span class="search">inhabilitado tmp</span>&nbsp;&nbsp; <input type="text" name="search" class="search" /> <input type="submit" value="Play!" class="button" /></p>
			</form>
			-->
		</div>
		
		<div class="newsletter">
			<div class="nombreSistema">CENACOM Ver. 2</div>
		</div>
		
		<div class="subheader">
			<p>Centro Nacional de Prevenci&oacute;n de Desastres<br />Centro Nacional de Comunicaciones</p>
		</div>