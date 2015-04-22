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
$servidor ="/DCENACOM/";
?>

<html>
<head>
<meta charset="utf-8">
<title>Visualizar Archivos</title>
</head>
<body>
<?php
include 'arriba.php';
?>
		<!--IZQUIERDA -->
		<div class="left">
			<div class="left_articles">
				<h2>Archivos Realacionados al reporte</h2>
			</div>

<table width="300px" border="1" bordercolor="#333333" align="center">
	<tr style="background-color:#CCCCCC; text-align:center;">
    	<td><strong>Archivos</strong></td>
    </tr>
    <?php 
	$conOracle1=conexionORACLE($HOST, $PORT, $SID, $userName, $passkey);
	$Consulta =  oci_parse($conOracle1, "SELECT NOMBRE_ARCHIVO, CARPETA||NOMBRE AS DIR FROM ARCHIVOS WHERE IDREPORTE =".$_POST['CR']."");
	
	if(!$Consulta)
	{
		$e = oci_error($conOracle1);
		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		print "Error en la conexion a la base de datos";
	}
	else if(!oci_execute($Consulta)){
		$e = oci_error($Consulta);
		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		print "Error en el query";
	}
	else{
		$nrows = oci_fetch_all($Consulta, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
		if($nrows>0)
		{
			for( $i =0;$i<$nrows;$i++)
			{
				$row = $res[$i];
				?>
                <tr>
                    <td><?php echo "<a href='".$servidor.$row['DIR']."' target='_blank'>".$row['NOMBRE_ARCHIVO']."</a>";?></td>
                </tr>
                <?php
			}
		}
	}
	?>
	</table>
	</div>
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