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