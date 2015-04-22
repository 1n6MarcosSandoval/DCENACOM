<?php
session_start();
if(@$_SESSION['login'] != "si")
{
header("Location:index.php");
exit();
}
//include 'arriba.php';
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
<script type="text/javascript" src="js/validacionUsuario.js"></script>
<script>
require(["dojo/parser", "dijit/form/DateTextBox"]);
</script>

</head>
<body class="claro">
	
	<?php
		include 'formsMenuHead.php';
	?>
		<br/>
<a id='Anadir' title='A&ntilde;adir' href="#nuevoUsuario"  onclick='muestra_oculta_capa("nuevoUsuario");anadirUsuario();return false;'>A&ntilde;adir usuario</a>
<div id="nuevoUsuario" name="nuevoUsuario" class="editaUsuario" style="display: none;"></div> 
		
	<div id="contenido">
		<?php 
			include('lista_pag_usuarios.php'); 
		?>		
	</div>

<div id="editaUsuario" class="editaUsuario" style="display: none;"></div>


		<div class="footer">
			<p>Sistema Nacional de Protecci&oacute;n Civil</p>
		</div>

</body>
</html>