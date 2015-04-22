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
<title>Fen&oacute;menos</title>
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
	<h3>Fen&oacute;menos</h3>


<?php
/********** Para agregar un Fenómeno ******/
if(@$_POST["insFenomeno"]){
	
	$firephp->log($_POST["insFenomeno"]);
	$valor=sanitize_sql_string($_POST["insFenomeno"]);
	//echo $valor;
	
	$queryCheca = oci_parse($conOracle, "SELECT NOMBRE FROM CENACOM.TIPO_FENOMENO where nombre='".$valor."'");
	oci_execute($queryCheca);
	$row_fenomeno_existe = oci_fetch_array($queryCheca, OCI_NUM);
	
	$firephp->log($row_fenomeno_existe[0], 'Mensaje del query');
	//echo $row_fenomeno_existe[0];
	
	if($row_fenomeno_existe[0]){
		echo "<div class='avisoError'>No se puede agregar. La fen&oacute;meno ".$valor." ya existe en la base de datos.</div>";
	}else{
		$valorF = $_POST["FenomenoADD"];
		//echo $valorF;
		switch ($valorF) {
/******************************************************************************************/		
		/*Geológicos*/
		case "GEO":
			/*Regresar el ultimo valor insertado en la DB del fenomeno*/
			$ID_Fen="GEO";
			$query = oci_parse($conOracle, 'SELECT MAX(ID_F_GEO) AS VALOR FROM CENACOM.F_GEO');
			oci_execute($query);
			$row = oci_fetch_array($query, OCI_ASSOC);
			$id_F=@$row["VALOR"]+1;
			$Clave = "G".$id_F;
			/*Fin Regresar el ultimo valor insertado en la DB del fenomeno*/
/*************************************************************************************/
			/*Inserta en la DB del Fenómeno*/
			$sqlF1="INSERT INTO CENACOM.F_GEO(ID_F_GEO,CLAVE,NOMBRE,CLASIFICACIONFENOMENO_ID)";
			$sqlF2="VALUES(".$id_F.",'".$Clave."','".$valor."','".$ID_Fen."')";
			$sqlF=$sqlF1.$sqlF2;
			
			$escribeOracleFenomenoTabla=oci_parse($conOracle,$sqlF);
		if (!$escribeOracleFenomenoTabla) {
    		$e = oci_error($conOracle);
    		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
			}
		$resultadoEnOracleFenomenoTabla=oci_execute($escribeOracleFenomenoTabla);
		if (!$resultadoEnOracleFenomenoTabla) {
	    	$e = oci_error($escribeOracleFenomenoTabla);
    		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
			}
		if($resultadoEnOracleFenomenoTabla){
			echo '<div class="aviso"><b>Fen&oacute;meno: '.$valor.' registrado en Geol&oacute;icos.</b><br/>';
			echo 'Los datos se registraron correctamente en la base de datos. <br/></div>';
		}else{
			echo '<div class="avisoError">Hubo un error en el registro con la base de datos. <br/></div>';	
			}
			/*Fin Inserta en la DB del Fenomeno*/
/******************************************************************************************/
			/*Regresar el ultimo valor insertado en la DB de los fenómenos*/
			$query = oci_parse($conOracle, 'SELECT MAX(ID_FENOMENO) AS VALOR_TF FROM CENACOM.TIPO_FENOMENO');
			oci_execute($query);
			$row = oci_fetch_array($query, OCI_ASSOC);
			$id_tf=@$row["VALOR_TF"]+1;
			/*Fin Regresar el ultimo valor insertado en la DB del fenómenos*/
/******************************************************************************************/
			/*Inserta en la DB Tipo de Fenómeno*/
			$sql1="INSERT INTO CENACOM.TIPO_FENOMENO(ID_FENOMENO,CLAVE,NOMBRE,CLASIFICACIONFENOMENO_ID)";
			$sql2="VALUES(".$id_tf.",'".$Clave."','".$valor."','".$ID_Fen."')";
			$sql=$sql1.$sql2;
			
			$escribeOracleFenomeno=oci_parse($conOracle,$sql);
		if (!$escribeOracleFenomeno) {
    		$e = oci_error($conOracle);
    		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
			}
		$resultadoEnOracleFenomeno=oci_execute($escribeOracleFenomeno);
		if (!$resultadoEnOracleFenomeno) {
	    	$e = oci_error($escribeOracleFenomeno);
    		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
			}
		if($resultadoEnOracleFenomenoTabla){
		}else{
			echo '<div class="avisoError">Hubo un error en el registro con la base de datos. <br/></div>';	
			}
			/*Fin Inserta en la DB del Fenómeno*/
/******************************************************************************************/
			/*Validar en amblas tablas insert correcto*/
			
			/*Fin Validar en amblas tablas insert correcto*/

/******************************************************************************************/
		/*Fin Geológicos*/	
			break;
		case "HIDRO":
			$ID_Fen="HIDRO";
			$query = oci_parse($conOracle, 'SELECT MAX(ID_F_HIDRO) AS VALOR FROM CENACOM.F_HIDRO');
			oci_execute($query);
			$row = oci_fetch_array($query, OCI_ASSOC);
			$id_F=@$row["VALOR"]+1;
			$Clave = "H".$id_F;
			/*Fin Regresar el ultimo valor insertado en la DB del fenomeno*/
/*************************************************************************************/
			/*Inserta en la DB del Fenómeno*/
			$sqlF1="INSERT INTO CENACOM.F_HIDRO(ID_F_HIDRO,CLAVE,NOMBRE,CLASIFICACIONFENOMENO_ID)";
			$sqlF2="VALUES(".$id_F.",'".$Clave."','".$valor."','".$ID_Fen."')";
			$sqlF=$sqlF1.$sqlF2;
			
			$escribeOracleFenomenoTabla=oci_parse($conOracle,$sqlF);
		if (!$escribeOracleFenomenoTabla) {
    		$e = oci_error($conOracle);
    		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
			}
		$resultadoEnOracleFenomenoTabla=oci_execute($escribeOracleFenomenoTabla);
		if (!$resultadoEnOracleFenomenoTabla) {
	    	$e = oci_error($escribeOracleFenomenoTabla);
    		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
			}
		if($resultadoEnOracleFenomenoTabla){
			echo '<div class="aviso"><b>Fen&oacute;meno: '.$valor.' registrado en Hidrometeorol&oacute;gicos.</b><br/>';
			echo 'Los datos se registraron correctamente en la base de datos. <br/></div>';
		}else{
			echo '<div class="avisoError">Hubo un error en el registro con la base de datos. <br/></div>';	
			}
			/*Fin Inserta en la DB del Fenomeno*/
/******************************************************************************************/
			/*Regresar el ultimo valor insertado en la DB de los fenómenos*/
			$query = oci_parse($conOracle, 'SELECT MAX(ID_FENOMENO) AS VALOR_TF FROM CENACOM.TIPO_FENOMENO');
			oci_execute($query);
			$row = oci_fetch_array($query, OCI_ASSOC);
			$id_tf=@$row["VALOR_TF"]+1;
			/*Fin Regresar el ultimo valor insertado en la DB del fenómenos*/
/******************************************************************************************/
			/*Inserta en la DB Tipo de Fenómeno*/
			$sql1="INSERT INTO CENACOM.TIPO_FENOMENO(ID_FENOMENO,CLAVE,NOMBRE,CLASIFICACIONFENOMENO_ID)";
			$sql2="VALUES(".$id_tf.",'".$Clave."','".$valor."','".$ID_Fen."')";
			$sql=$sql1.$sql2;
			
			$escribeOracleFenomeno=oci_parse($conOracle,$sql);
		if (!$escribeOracleFenomeno) {
    		$e = oci_error($conOracle);
    		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
			}
		$resultadoEnOracleFenomeno=oci_execute($escribeOracleFenomeno);
		if (!$resultadoEnOracleFenomeno) {
	    	$e = oci_error($escribeOracleFenomeno);
    		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
			}
		if($resultadoEnOracleFenomenoTabla){
		}else{
			echo '<div class="avisoError">Hubo un error en el registro con la base de datos. <br/></div>';	
			}
			/*Fin Inserta en la DB del Fenómeno*/
/******************************************************************************************/
			/*Validar en amblas tablas insert correcto*/
			
			/*Fin Validar en amblas tablas insert correcto*/

/******************************************************************************************/
		/*Fin Hidrometeorológicos*/	
			break;
		case "QUIM":
			$ID_Fen="QUIM";
			$query = oci_parse($conOracle, 'SELECT MAX(ID_F_QUIM) AS VALOR FROM CENACOM.F_QUIM');
			oci_execute($query);
			$row = oci_fetch_array($query, OCI_ASSOC);
			$id_F=@$row["VALOR"]+1;
			$Clave = "Q".$id_F;
			/*Fin Regresar el ultimo valor insertado en la DB del fenomeno*/
/*************************************************************************************/
			/*Inserta en la DB del Fenómeno*/
			$sqlF1="INSERT INTO CENACOM.F_QUIM(ID_F_QUIM,CLAVE,NOMBRE,CLASIFICACIONFENOMENO_ID)";
			$sqlF2="VALUES(".$id_F.",'".$Clave."','".$valor."','".$ID_Fen."')";
			$sqlF=$sqlF1.$sqlF2;
			
			$escribeOracleFenomenoTabla=oci_parse($conOracle,$sqlF);
		if (!$escribeOracleFenomenoTabla) {
    		$e = oci_error($conOracle);
    		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
			}
		$resultadoEnOracleFenomenoTabla=oci_execute($escribeOracleFenomenoTabla);
		if (!$resultadoEnOracleFenomenoTabla) {
	    	$e = oci_error($escribeOracleFenomenoTabla);
    		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
			}
		if($resultadoEnOracleFenomenoTabla){
			echo '<div class="aviso"><b>Fen&oacute;meno: '.$valor.' registrado en Qu&iacute;mico-Tecnol&oacute;gico.</b><br/>';
			echo 'Los datos se registraron correctamente en la base de datos. <br/></div>';
		}else{
			echo '<div class="avisoError">Hubo un error en el registro con la base de datos. <br/></div>';	
			}
			/*Fin Inserta en la DB del Fenomeno*/
/******************************************************************************************/
			/*Regresar el ultimo valor insertado en la DB de los fenómenos*/
			$query = oci_parse($conOracle, 'SELECT MAX(ID_FENOMENO) AS VALOR_TF FROM CENACOM.TIPO_FENOMENO');
			oci_execute($query);
			$row = oci_fetch_array($query, OCI_ASSOC);
			$id_tf=@$row["VALOR_TF"]+1;
			/*Fin Regresar el ultimo valor insertado en la DB del fenómenos*/
/******************************************************************************************/
			/*Inserta en la DB Tipo de Fenómeno*/
			$sql1="INSERT INTO CENACOM.TIPO_FENOMENO(ID_FENOMENO,CLAVE,NOMBRE,CLASIFICACIONFENOMENO_ID)";
			$sql2="VALUES(".$id_tf.",'".$Clave."','".$valor."','".$ID_Fen."')";
			$sql=$sql1.$sql2;
			
			$escribeOracleFenomeno=oci_parse($conOracle,$sql);
		if (!$escribeOracleFenomeno) {
    		$e = oci_error($conOracle);
    		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
			}
		$resultadoEnOracleFenomeno=oci_execute($escribeOracleFenomeno);
		if (!$resultadoEnOracleFenomeno) {
	    	$e = oci_error($escribeOracleFenomeno);
    		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
			}
		if($resultadoEnOracleFenomenoTabla){
		}else{
			echo '<div class="avisoError">Hubo un error en el registro con la base de datos. <br/></div>';	
			}
			/*Fin Inserta en la DB del Fenómeno*/
/******************************************************************************************/
			/*Validar en amblas tablas insert correcto*/
			
			/*Fin Validar en amblas tablas insert correcto*/

/******************************************************************************************/
		/*Fin Quimicos*/	
			break;
		case "SAN":
			$ID_Fen="SAN";
			$query = oci_parse($conOracle, 'SELECT MAX(ID_F_SAN) AS VALOR FROM CENACOM.F_SAN');
			oci_execute($query);
			$row = oci_fetch_array($query, OCI_ASSOC);
			$id_F=@$row["VALOR"]+1;
			$Clave = "S".$id_F;
			/*Fin Regresar el ultimo valor insertado en la DB del fenomeno*/
/*************************************************************************************/
			/*Inserta en la DB del Fenómeno*/
			$sqlF1="INSERT INTO CENACOM.F_SAN(ID_F_SAN,CLAVE,NOMBRE,CLASIFICACIONFENOMENO_ID)";
			$sqlF2="VALUES(".$id_F.",'".$Clave."','".$valor."','".$ID_Fen."')";
			$sqlF=$sqlF1.$sqlF2;
			
			$escribeOracleFenomenoTabla=oci_parse($conOracle,$sqlF);
		if (!$escribeOracleFenomenoTabla) {
    		$e = oci_error($conOracle);
    		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
			}
		$resultadoEnOracleFenomenoTabla=oci_execute($escribeOracleFenomenoTabla);
		if (!$resultadoEnOracleFenomenoTabla) {
	    	$e = oci_error($escribeOracleFenomenoTabla);
    		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
			}
		if($resultadoEnOracleFenomenoTabla){
			echo '<div class="aviso"><b>Fen&oacute;meno: '.$valor.' registrado en Sanitario-Ecol&oacute;gicos</b><br/>';
			echo 'Los datos se registraron correctamente en la base de datos . <br/></div>';
		}else{
			echo '<div class="avisoError">Hubo un error en el registro con la base de datos. <br/></div>';	
			}
			/*Fin Inserta en la DB del Fenomeno*/
/******************************************************************************************/
			/*Regresar el ultimo valor insertado en la DB de los fenómenos*/
			$query = oci_parse($conOracle, 'SELECT MAX(ID_FENOMENO) AS VALOR_TF FROM CENACOM.TIPO_FENOMENO');
			oci_execute($query);
			$row = oci_fetch_array($query, OCI_ASSOC);
			$id_tf=@$row["VALOR_TF"]+1;
			/*Fin Regresar el ultimo valor insertado en la DB del fenómenos*/
/******************************************************************************************/
			/*Inserta en la DB Tipo de Fenómeno*/
			$sql1="INSERT INTO CENACOM.TIPO_FENOMENO(ID_FENOMENO,CLAVE,NOMBRE,CLASIFICACIONFENOMENO_ID)";
			$sql2="VALUES(".$id_tf.",'".$Clave."','".$valor."','".$ID_Fen."')";
			$sql=$sql1.$sql2;
			
			$escribeOracleFenomeno=oci_parse($conOracle,$sql);
		if (!$escribeOracleFenomeno) {
    		$e = oci_error($conOracle);
    		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
			}
		$resultadoEnOracleFenomeno=oci_execute($escribeOracleFenomeno);
		if (!$resultadoEnOracleFenomeno) {
	    	$e = oci_error($escribeOracleFenomeno);
    		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
			}
		if($resultadoEnOracleFenomenoTabla){
		}else{
			echo '<div class="avisoError">Hubo un error en el registro con la base de datos. <br/></div>';	
			}
			/*Fin Inserta en la DB del Fenómeno*/
/******************************************************************************************/
			/*Validar en amblas tablas insert correcto*/
			
			/*Fin Validar en amblas tablas insert correcto*/

/******************************************************************************************/
		/*Fin Quimicos*/	
			break;
		case "SOCIO":
			$ID_Fen="SOCIO";
			$query = oci_parse($conOracle, 'SELECT MAX(ID_F_SOCIO) AS VALOR FROM CENACOM.F_SOCIO');
			oci_execute($query);
			$row = oci_fetch_array($query, OCI_ASSOC);
			$id_F=@$row["VALOR"]+1;
			$Clave = "A".$id_F;
			/*Fin Regresar el ultimo valor insertado en la DB del fenomeno*/
/*************************************************************************************/
			/*Inserta en la DB del Fenómeno*/
			$sqlF1="INSERT INTO CENACOM.F_SOCIO(ID_F_SOCIO,CLAVE,NOMBRE,CLASIFICACIONFENOMENO_ID)";
			$sqlF2="VALUES(".$id_F.",'".$Clave."','".$valor."','".$ID_Fen."')";
			$sqlF=$sqlF1.$sqlF2;
			
			$escribeOracleFenomenoTabla=oci_parse($conOracle,$sqlF);
		if (!$escribeOracleFenomenoTabla) {
    		$e = oci_error($conOracle);
    		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
			}
		$resultadoEnOracleFenomenoTabla=oci_execute($escribeOracleFenomenoTabla);
		if (!$resultadoEnOracleFenomenoTabla) {
	    	$e = oci_error($escribeOracleFenomenoTabla);
    		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
			}
		if($resultadoEnOracleFenomenoTabla){
			echo '<div class="aviso"><b>Fen&oacute;meno: '.$valor.' registrado en Socio-Organizativos.</b><br/>';
			echo 'Los datos se registraron correctamente en la base de datos. <br/></div>';
		}else{
			echo '<div class="avisoError">Hubo un error en el registro con la base de datos. <br/></div>';	
			}
			/*Fin Inserta en la DB del Fenomeno*/
/******************************************************************************************/
			/*Regresar el ultimo valor insertado en la DB de los fenómenos*/
			$query = oci_parse($conOracle, 'SELECT MAX(ID_FENOMENO) AS VALOR_TF FROM CENACOM.TIPO_FENOMENO');
			oci_execute($query);
			$row = oci_fetch_array($query, OCI_ASSOC);
			$id_tf=@$row["VALOR_TF"]+1;
			/*Fin Regresar el ultimo valor insertado en la DB del fenómenos*/
/******************************************************************************************/
			/*Inserta en la DB Tipo de Fenómeno*/
			$sql1="INSERT INTO CENACOM.TIPO_FENOMENO(ID_FENOMENO,CLAVE,NOMBRE,CLASIFICACIONFENOMENO_ID)";
			$sql2="VALUES(".$id_tf.",'".$Clave."','".$valor."','".$ID_Fen."')";
			$sql=$sql1.$sql2;
			
			$escribeOracleFenomeno=oci_parse($conOracle,$sql);
		if (!$escribeOracleFenomeno) {
    		$e = oci_error($conOracle);
    		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
			}
		$resultadoEnOracleFenomeno=oci_execute($escribeOracleFenomeno);
		if (!$resultadoEnOracleFenomeno) {
	    	$e = oci_error($escribeOracleFenomeno);
    		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
			}
		if($resultadoEnOracleFenomenoTabla){
		}else{
			echo '<div class="avisoError">Hubo un error en el registro con la base de datos. <br/></div>';	
			}
			/*Fin Inserta en la DB del Fenómeno*/
/******************************************************************************************/
			/*Validar en amblas tablas insert correcto*/
			
			/*Fin Validar en amblas tablas insert correcto*/

/******************************************************************************************/
		/*Fin Quimicos*/				
			break;
		default:
			$ID_Fen="Debe seleccionar un tipo de fen&oacute;meno primero";
			break;
		}
		//echo $ID_Fen;
		
		}
		
	
}
	
/******FIN Para agregar un fenómeno ******/

/********** Para eliminar un fenómeno ******/

	if (@$_POST["TipoFenomeno"]){
		$TipoFenomeno= $_POST["TipoFenomeno"];
		//echo $TipoFenomeno;
	/*Nombre del fenómeno*/
		$query = oci_parse($conOracle, 'SELECT NOMBRE AS VALOR_FB FROM CENACOM.TIPO_FENOMENO where ID_FENOMENO='.$TipoFenomeno);
		oci_execute($query);
			$row = oci_fetch_array($query, OCI_NUM);
			$NomFenDel = $row[0];
			//echo $NomFenDel;
	/* Fin Nombre del fenómeno*/
	/******************************************/
	/*Reportes con el fenómeno*/
		$queryCont = oci_parse($conOracle, 'SELECT COUNT(*) FROM CENACOM.REPORTES where TIPOFENOMENO_ID='.$TipoFenomeno.' OR TIPOFENOMENOMAYORAFECTACION='.$TipoFenomeno);
		oci_execute($queryCont);
			$row_Rep_Del = oci_fetch_array($queryCont, OCI_NUM);
			$ValFenDel = $row_Rep_Del[0];
		if ($ValFenDel > 0){
			echo "<div class='avisoError'>El fen&oacute;meno ".$NomFenDel." no se puede borrar ya que existen  registros con dicho fen&oacute;meno<br/></div>";
			/* Fin Reportes con el fenómeno*/	
		}else{
///////////////////////////////////////////////////////////////////////////////////////////////		
			//echo "se puede borrar";
			$query = oci_parse($conOracle, 'SELECT CLASIFICACIONFENOMENO_ID AS VALOR_TFB FROM CENACOM.TIPO_FENOMENO where ID_FENOMENO='.$TipoFenomeno);
			oci_execute($query);
				$row = oci_fetch_array($query, OCI_NUM);
				$TipoFenDel = $row[0];
			$SqlDel = "";
////////////////////////////////////////////////////////////////
			if ($TipoFenDel == "GEO"){

				$SqlDel = "DELETE FROM CENACOM.F_GEO WHERE NOMBRE='".$NomFenDel."'";
				//echo $SqlDel;

				//BORRA EN LA BD QUERY DE DB Fenómeno
				$BorrarOracleFenomenoTabla=oci_parse($conOracle,$SqlDel);
				if (!$BorrarOracleFenomenoTabla) {
					$e = oci_error($conOracle);
					trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
				}
				$resultadoEnOracleFenomenoTabla=oci_execute($BorrarOracleFenomenoTabla);
				if (!$resultadoEnOracleFenomenoTabla) {
					$e = oci_error($BorrarOracleFenomenoTabla);
					trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
				}

				if($resultadoEnOracleFenomenoTabla){
				
					$SqlDel = "DELETE FROM CENACOM.TIPO_FENOMENO WHERE ID_FENOMENO=".$TipoFenomeno;
					//echo $SqlDel;
//////////////////////////////////////////////////////////////////////////				
				//BORRA EN LA BD QUERY DE DB Fenómeno
						$BorrarOracleFenomenoTabla=oci_parse($conOracle,$SqlDel);
						if (!$BorrarOracleFenomenoTabla) {
								$e = oci_error($conOracle);
								trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
						}
						$resultadoEnOracleFenomenoTabla=oci_execute($BorrarOracleFenomenoTabla);
						if (!$resultadoEnOracleFenomenoTabla) {
							$e = oci_error($BorrarOracleFenomenoTabla);
							trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
						}				
				
					
					echo '<b>Dependencia borrada.</b><br/>';
					echo "<div class='aviso'>Los datos se borarron correctamente en la base de datos. <br/></div>";
					}else{
							echo "<div class='avisoError'>Hubo un error en el borrado con la base de datos. <br/></div>";	
					}
/////////////////////////////////////////////////////////////////////////////					
			}elseif($TipoFenDel == "HIDRO"){
				$SqlDel = "DELETE FROM CENACOM.F_HIDRO WHERE NOMBRE='".$NomFenDel."'";
				//echo $SqlDel;

				//BORRA EN LA BD QUERY DE DB Fenómeno
				$BorrarOracleFenomenoTabla=oci_parse($conOracle,$SqlDel);
				if (!$BorrarOracleFenomenoTabla) {
					$e = oci_error($conOracle);
					trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
				}
				$resultadoEnOracleFenomenoTabla=oci_execute($BorrarOracleFenomenoTabla);
				if (!$resultadoEnOracleFenomenoTabla) {
					$e = oci_error($BorrarOracleFenomenoTabla);
					trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
				}

				if($resultadoEnOracleFenomenoTabla){
				
					$SqlDel = "DELETE FROM CENACOM.TIPO_FENOMENO WHERE ID_FENOMENO=".$TipoFenomeno;
					//echo $SqlDel;
//////////////////////////////////////////////////////////////////////////				
				//BORRA EN LA BD QUERY DE DB Fenómeno
						$BorrarOracleFenomenoTabla=oci_parse($conOracle,$SqlDel);
						if (!$BorrarOracleFenomenoTabla) {
								$e = oci_error($conOracle);
								trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
						}
						$resultadoEnOracleFenomenoTabla=oci_execute($BorrarOracleFenomenoTabla);
						if (!$resultadoEnOracleFenomenoTabla) {
							$e = oci_error($BorrarOracleFenomenoTabla);
							trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
						}				
				
					
					echo '<b>Dependencia borrada.</b><br/>';
					echo "<div class='aviso'>Los datos se borarron correctamente en la base de datos. <br/></div>";
					}else{
							echo "<div class='avisoError'>Hubo un error en el borrado con la base de datos. <br/></div>";	
						}
//////////////////////////////////////////////////////////////////////////////	
				}elseif($TipoFenDel == "QUIM"){
				$SqlDel = "DELETE FROM CENACOM.F_QUIM WHERE NOMBRE='".$NomFenDel."'";
				//echo $SqlDel;

				//BORRA EN LA BD QUERY DE DB Fenómeno
				$BorrarOracleFenomenoTabla=oci_parse($conOracle,$SqlDel);
				if (!$BorrarOracleFenomenoTabla) {
					$e = oci_error($conOracle);
					trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
				}
				$resultadoEnOracleFenomenoTabla=oci_execute($BorrarOracleFenomenoTabla);
				if (!$resultadoEnOracleFenomenoTabla) {
					$e = oci_error($BorrarOracleFenomenoTabla);
					trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
				}

				if($resultadoEnOracleFenomenoTabla){
				
					$SqlDel = "DELETE FROM CENACOM.TIPO_FENOMENO WHERE ID_FENOMENO=".$TipoFenomeno;
					//echo $SqlDel;
//////////////////////////////////////////////////////////////////////////				
				//BORRA EN LA BD QUERY DE DB Fenómeno
						$BorrarOracleFenomenoTabla=oci_parse($conOracle,$SqlDel);
						if (!$BorrarOracleFenomenoTabla) {
								$e = oci_error($conOracle);
								trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
						}
						$resultadoEnOracleFenomenoTabla=oci_execute($BorrarOracleFenomenoTabla);
						if (!$resultadoEnOracleFenomenoTabla) {
							$e = oci_error($BorrarOracleFenomenoTabla);
							trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
						}				
				
					
					echo '<b>Dependencia borrada.</b><br/>';
					echo "<div class='aviso'>Los datos se borarron correctamente en la base de datos. <br/></div>";
					}else{
							echo "<div class='avisoError'>Hubo un error en el borrado con la base de datos. <br/></div>";	
						}
//////////////////////////////////////////////////////////////////////////////	
				}elseif($TipoFenDel == "SAN"){
				$SqlDel = "DELETE FROM CENACOM.F_SAN WHERE NOMBRE='".$NomFenDel."'";
				//echo $SqlDel;

				//BORRA EN LA BD QUERY DE DB Fenómeno
				$BorrarOracleFenomenoTabla=oci_parse($conOracle,$SqlDel);
				if (!$BorrarOracleFenomenoTabla) {
					$e = oci_error($conOracle);
					trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
				}
				$resultadoEnOracleFenomenoTabla=oci_execute($BorrarOracleFenomenoTabla);
				if (!$resultadoEnOracleFenomenoTabla) {
					$e = oci_error($BorrarOracleFenomenoTabla);
					trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
				}

				if($resultadoEnOracleFenomenoTabla){
				
					$SqlDel = "DELETE FROM CENACOM.TIPO_FENOMENO WHERE ID_FENOMENO=".$TipoFenomeno;
					//echo $SqlDel;
//////////////////////////////////////////////////////////////////////////				
				//BORRA EN LA BD QUERY DE DB Fenómeno
						$BorrarOracleFenomenoTabla=oci_parse($conOracle,$SqlDel);
						if (!$BorrarOracleFenomenoTabla) {
								$e = oci_error($conOracle);
								trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
						}
						$resultadoEnOracleFenomenoTabla=oci_execute($BorrarOracleFenomenoTabla);
						if (!$resultadoEnOracleFenomenoTabla) {
							$e = oci_error($BorrarOracleFenomenoTabla);
							trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
						}				
				
					
					echo '<b>Dependencia borrada.</b><br/>';
					echo "<div class='aviso'>Los datos se borarron correctamente en la base de datos. <br/></div>";
					}else{
							echo "<div class='avisoError'>Hubo un error en el borrado con la base de datos. <br/></div>";	
//////////////////////////////////////////////////////////////////////////////	
					}
				}elseif($TipoFenDel == "SOCIO"){
				$SqlDel = "DELETE FROM CENACOM.F_SOCIO WHERE NOMBRE='".$NomFenDel."'";
				//echo $SqlDel;

				//BORRA EN LA BD QUERY DE DB Fenómeno
				$BorrarOracleFenomenoTabla=oci_parse($conOracle,$SqlDel);
				if (!$BorrarOracleFenomenoTabla) {
					$e = oci_error($conOracle);
					trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
				}
				$resultadoEnOracleFenomenoTabla=oci_execute($BorrarOracleFenomenoTabla);
				if (!$resultadoEnOracleFenomenoTabla) {
					$e = oci_error($BorrarOracleFenomenoTabla);
					trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
				}

				if($resultadoEnOracleFenomenoTabla){
				
					$SqlDel = "DELETE FROM CENACOM.TIPO_FENOMENO WHERE ID_FENOMENO=".$TipoFenomeno;
					//echo $SqlDel;
//////////////////////////////////////////////////////////////////////////				
				//BORRA EN LA BD QUERY DE DB Fenómeno
						$BorrarOracleFenomenoTabla=oci_parse($conOracle,$SqlDel);
						if (!$BorrarOracleFenomenoTabla) {
								$e = oci_error($conOracle);
								trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
						}
						$resultadoEnOracleFenomenoTabla=oci_execute($BorrarOracleFenomenoTabla);
						if (!$resultadoEnOracleFenomenoTabla) {
							$e = oci_error($BorrarOracleFenomenoTabla);
							trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
						}				
				
					
					echo '<b>Dependencia borrada.</b><br/>';
					echo "<div class='aviso'>Los datos se borarron correctamente en la base de datos. <br/></div>";
					}else{
							echo "<div class='avisoError'>Hubo un error en el borrado con la base de datos. <br/></div>";	
//////////////////////////////////////////////////////////////////////////////	
					}	
				}
			}
		}	




/******FIN Para eliminar un Fenómeno ******/

?>


	
	<div id="contenedor">

		<div class="izq50">
			Agregar Fen&oacute;meno:
			<br/>
			
			<form method="post" id="fadmin" name="fadmin" action="add_fenom.php">
			<select id="'FenomenoADD" title="FenomenoADD" name="FenomenoADD">
				<option value="0">Seleccionar</option>
				<option value="GEO">Geol&oacute;gico</option>
				<option value="HIDRO">Hidrometeorol&oacute;gico</option>
				<option value="QUIM">Qu&iacute;mico-Tecnol&oacute;gico</option>
				<option value="SAN">Sanitario-Ecol&oacute;gicos</option>
				<option value="SOCIO">Socio-Organizativos</option>
			</select>

			
			
			<input id="insFenomeno" name="insFenomeno" title="insFenomeno" maxlength="100" size="50" type="text"/>
			<div id="boton1">				
				<input type="submit"  value="Registrar"/>
			</div>
			</form>
		</div>
	
		<div class="der50">
			Eliminar Fen&oacute;meno:
			<br/>
			<br/>
			<form method="post" id="fadmin" name="fadmin" action="add_fenom.php">
<?php
	$query = oci_parse($conOracle, 'SELECT ID, CLASIFICACION from ANRO.CLASIFICACIONFENOMENO ORDER BY ID');
	comboQueryJS($query,"ID", 'CLASIFICACION', 'ClasificacionFenomeno', 'TipoFenomeno', 'fenomeno', 'Seleccionar','Clasificaci&oacute;n y tipo de f&eacute;nomeno');
?>
			<div id="boton2">				
				<input type="submit"  value="Eliminar" />
			</div>
			</form>

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