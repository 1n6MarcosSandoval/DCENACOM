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
	require 'Archivo.php';
$conOracle=conexionORACLE($HOST, $PORT, $SID, $userName, $passkey);
?>
<html>
<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
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
<?php
if(isset($_POST["reporte"]  )){
	if($_FILES['archivo']['name']!="" ){
		$indice = 0;
		$nombre_carpeta = 'ArchivosReporte/'.$_POST['reporte'];
		if(!is_dir($nombre_carpeta)){ 
			mkdir($nombre_carpeta, 0777, true);
			chmod($nombre_carpeta, 0777);

			$contador = fopen("ArchivosReporte/".$_POST['reporte']."/contador.txt","a+"); // contador 
			fwrite($contador,$indice, 100); 
			fclose($contador); 
		}else
		{
			$contador = fopen("ArchivosReporte/".$_POST['reporte']."/contador.txt","r"); // contador 
			$count = fread($contador, 100); 
			$indice = ($count + 1); 
			fclose($contador); 
			$contador = fopen("ArchivosReporte/".$_POST['reporte']."/contador.txt","w"); 
			fwrite($contador, $indice, 100); 
			fclose($contador); 
		}
		$Documentos = array("doc","docx","pdf","ppt","pptx","rar","xlsx","xls","jpg","png");
		$Imagenes = array("jpg","png");
		
		$ArrayNombre = explode('.',$_FILES['archivo']['name']);
		$extension= $ArrayNombre [1];
		
		$tipo ='';
		if(in_array($extension,$Documentos))
			$tipo = 'Documento';
		else if(in_array($extension,$Imagenes))
			$tipo = 'Imagen';
		else
			$tipo = 'No permitido';
		
		
		$a=$_POST['reporte'].'_'.$indice.'.'.$extension;
		$d="ArchivosReporte/".$_POST['reporte']."/";	
		$e=array("doc","docx","pdf","ppt","pptx","rar","xlsx","xls","jpg","png");
		$t=$_FILES['archivo']['size'];
		$tmp=$_FILES['archivo']['tmp_name'];
		$Archivo = new Archivo($a,$d,$e,$t,$tmp);	
		$u = $Archivo->upLoadFile();

		$instruccion ="INSERT INTO ARCHIVOS (IDREPORTE, CARPETA, NOMBRE, TIPO,NOMBRE_ARCHIVO) VALUES (".$_POST['reporte'].",'".$d."','".$a."','".$tipo."','".$_FILES['archivo']['name']."')";
		$query1 =  oci_parse($conOracle,$instruccion );
		
		oci_execute($query1);
		cerrarConexionORACLE($conOracle);
	}
}
?>				
		<p>El archivo ha sido almacenado con &eacute;xito
		</div>	
<!--Fin IZQUIERDA-->	

		<?php
		if($_SESSION['nombre']=="cenacomAdmin")
			include 'derechaAdmin.php';
		else
			include 'derecha0.php';

		?>	
		<div class="footer">
			<p>Sistema Nacional de Protecci&oacute;n Civil</p>
		</div>

</body>
</html>