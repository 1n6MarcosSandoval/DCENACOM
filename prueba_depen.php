<?php
include 'functions.php';
include 'vars.php';
require_once('FirePHPCore/FirePHP.class.php');
ob_start();
$firephp = FirePHP::getInstance(true);
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

	<h2>Administraci&oacute;n</h2>
	<h3>Organismo que reporta</h3>




<?php
/********** Para agregar un organismo ******/
if(@$_POST["insOrganismo"]){
	
	$firephp->log($_POST["insOrganismo"]);
	$valor=sanitize_sql_string($_POST["insOrganismo"]);
	
	$queryCheca = oci_parse($conOracle, "SELECT ID FROM CENACOM.dependencias where nombre='".$valor."'");
	oci_execute($queryCheca);
	$row_organismo_existe = oci_fetch_array($queryCheca, OCI_NUM);
	
	$firephp->log($row_organismo_existe[0], 'Mensaje del query');
	//echo $row_organismo_existe[0];
	
	if($row_organismo_existe[0]){
		echo "<div class='avisoError'>No se puede agregar. La dependencia ya existe en la base de datos.</div>";
	}else{

		$query = oci_parse($conOracle, 'SELECT MAX(ID) AS VALOR FROM CENACOM.DEPENDENCIAS');
		oci_execute($query);
		$row = oci_fetch_array($query, OCI_ASSOC);
		$IDregistrar=@$row["VALOR"]+1;	
	
		
		$sql1="INSERT INTO CENACOM.DEPENDENCIAS(ID, NOMBRE)";
		$sql2="VALUES(".$IDregistrar.",'".$valor."')";
		$sql=$sql1.$sql2;

	
		//ESCRIBE EN LA BD QUERY DE DEPENDENCIAS
		$escribeOracleDependencia=oci_parse($conOracle,$sql);
		if (!$escribeOracleDependencia) {
    		$e = oci_error($conOracle);
    		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		}
		$resultadoEnOracleDependencia=oci_execute($escribeOracleDependencia);
		if (!$resultadoEnOracleDependencia) {
	    	$e = oci_error($escribeOracleDependencia);
    		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		}
		if($resultadoEnOracleDependencia){
			echo '<div class="aviso"><b>Dependencia: '.$valor.' registrada.</b><br/>';
			echo 'Los datos se registraron correctamente en la base de datos. <br/></div>';
		}else{
			echo '<div class="avisoError">Hubo un error en el registro con la base de datos. <br/></div>';	
		}
	}
}
/******FIN Para agregar un organismo ******/

/********** Para eliminar un organismo ******/
if(@$_POST["borraOrganismo"]){
	$valor=sanitize_sql_string($_POST["borraOrganismo"]);
	
	$query = oci_parse($conOracle, 'select id_reporte from CENACOM.reportes where organismo_aviso='.$valor);
	oci_execute($query);
	$row_organismo_aviso = oci_fetch_array($query, OCI_NUM);
	
	$query = oci_parse($conOracle, "select respuesta_institucional from CENACOM.reportes where respuesta_institucional like '%".$valor."%'");
	oci_execute($query);
	$row_respuesta_institucional = oci_fetch_array($query, OCI_NUM);
	
	
	if($row_organismo_aviso[0]>0 || strlen($row_respuesta_institucional[0])>0){
		echo "<div class='avisoError'>No se puede borrar ya que uno o m&aacute;s registros utilizan este valor en la base de datos.</div>";
	}else{
		$sql="DELETE FROM CENACOM.DEPENDENCIAS WHERE ID=".$valor;		
	
		//BORRA EN LA BD QUERY DE DEPENDENCIAS
		$escribeOracleDependencia=oci_parse($conOracle,$sql);
		if (!$escribeOracleDependencia) {
    		$e = oci_error($conOracle);
    		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		}
		$resultadoEnOracleDependencia=oci_execute($escribeOracleDependencia);
		if (!$resultadoEnOracleDependencia) {
    		$e = oci_error($escribeOracleDependencia);
    		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		}

		if($resultadoEnOracleDependencia){
			echo '<b>Dependencia borrada.</b><br/>';
			echo "<div class='aviso'>Los datos se borarron correctamente en la base de datos. <br/></div>";
		}else{
			echo "<div class='avisoError'>Hubo un error en el borrado con la base de datos. <br/></div>";	
		}		
	}
	
	

}
/******FIN Para eliminar un organismo ******/

?>


	
	<div id="contenedor">

		<div class="izq50">
			Agregar Organismo:
			<br/>
			
			<form method="post" id="fadmin" name="fadmin" action="prueba_depen.php">
			<input id="insOrganismo" name="insOrganismo" title="Organismo" maxlength="100" size="50" type="text"/>
			<div id="boton1">				
				<input type="submit"  value="Registrar"/>
				<!-- <input type="button"  value="Registrar" onclick="valida()" /> -->
			</div>
			</form>
		</div>
	
		<div class="der50">
			Eliminar organismo::
			<br/>
			
			<form method="post" id="fadmin" name="fadmin" action="prueba_depen.php">
<?php
	$query = oci_parse($conOracle, 'SELECT ID, NOMBRE from CENACOM.DEPENDENCIAS ORDER BY NOMBRE ASC');
	//comboMultiple($query, "ID", "NOMBRE", 'OrganismoReporta', "Seleccione","Organismo que reporta")
	
	comboQueryJS_1($query, "ID", "NOMBRE", 'borraOrganismo', "Seleccione","Organismo que reporta");
?>
			<div id="boton2">				
				<input type="submit"  value="Eliminar" />
				<!-- <input type="button"  value="Registrar" onclick="valida()" /> -->
			</div>

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