<?php
session_start();

require_once('FirePHPCore/FirePHP.class.php');
ob_start();
$firephp = FirePHP::getInstance(true);

if(@$_SESSION['login'] != "si")
{
header("Location:index.php");
exit();
}

include 'functions.php';
include 'vars.php';

$conOracle=conexionORACLE($HOST, $PORT, $SID, $userName, $passkey);
?>



<html>
<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<title>Subir archivo</title>
</head>
<body>
<?php
include 'arriba.php';
?>
		<!--IZQUIERDA -->
		<div class="left">
			<div class="left_articles">
				<h2>Sistema de captura</h2>
			</div>
						
<?php	if(isset($_POST["ID_REPORTEF"]  )){
		$ReporteID = $_POST["ID_REPORTEF"];
?>
			<form enctype="multipart/form-data" action="SubirImagen.php" method="POST">
			<p>Tama&ntilde;o m&aacute;ximo del archivo 2 MB
			<p>Reporte:<?php echo $ReporteID ?>
			<input type = "hidden" name = "reporte" id = "reporte" value = "<?php echo $ReporteID; ?>">
			<br/><input name="archivo" type="file" />
			<input type="submit" value="Subir archivo" />
			</form>
		</div>
<?php 	}else{
			header('Location: principal.php');
		}
 ?>
<!--Fin IZQUIERDA-->			 


		<?php
		if($_SESSION['nombre']=="cenacomAdmin")
			include 'derechaAdmin.php';
		else
			include 'derecha0.php';
			cerrarConexionORACLE($conOracle);
		?>	
		<div class="footer">
			<p>Sistema Nacional de Protecci&oacute;n Civil</p>
		</div>
</body>
</html>