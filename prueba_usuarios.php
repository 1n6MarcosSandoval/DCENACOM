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
}elseif(@$_SESSION['nombre']!="cenacomAdmin"){
header("Location:principal.php");
}
$conOracle=conexionORACLE($HOST, $PORT, $SID, $userName, $passkey,'AL32UTF8');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

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

</head>
<body class="claro">
	
	<?php
		include 'formsMenuHead.php';
	?>

	<h1>Sistema de administraci&oacute;n de usuarios</h1>
	<br/><br/>
	<div class="left">
			<div class="lboxForm">
	<?php
		//Validar campos y actualizar DB
//////////////////////////////////////////////
if (@$_POST["Nom_usuario"]){
		$firephp->log($_POST["Nom_usuario"]);
		$Nom_usuario=sanitize_sql_string($_POST["Nom_usuario"]);
		//echo $Nom_usuario;
		$permNom="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ ";
		$permPass="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		
		if ($Nom_usuario==""){
			echo "<div class='avisoError'>Debe ingresar un nombre de usuario.</div>";
		}else{
		////////////////////////////////////////////////
		/*Validar que el nombre solo sean letras*/
			for ($i=0; $i<strlen($Nom_usuario); $i++){
				if (strpos($permNom, substr($Nom_usuario,$i,1))===false){
					echo "<div class='avisoError'>El nombre ".$Nom_usuario." no es v&aacute;lido, solo puede contener letras may&uacute;sculas y min&uacute;sculas, ingrese un nombre de usuario valido.</div>";
					echo "<br/><br/>";
					echo '<div class="footer">';
					echo "<p>Sistema Nacional de Protecci&oacute;n Civil</p>";
					echo "</div>";					
					return false;
				}
			}
		////////////////////////////////////////////////
		/*Validar que el usuario solo sean letras y numeros*/
			$firephp->log($_POST["usuario"]);
			$usuario=sanitize_sql_string($_POST["usuario"]);
			//echo $usuario;
			
			for ($i=0; $i<strlen($usuario); $i++){
				if (strpos($permPass, substr($usuario,$i,1))===false){
					echo "<div class='avisoError'>El usuario no es v&aacute;lida, solo puede contener letras may&uacute;sculas, min&uacute;sculas y n&uacute;meros ingrese otro usuario.</div>";
					echo "<br/><br/>";
					echo '<div class="footer">';
					echo "<p>Sistema Nacional de Protecci&oacute;n Civil</p>";
					echo "</div>";
					return false;
					}
			}
		////////////////////////////////////////////////
			
		////////////////////////////////////////////////
		/*Validar que el pass solo sean letras y numeros*/
			$firephp->log($_POST["pass"]);
			$pass=sanitize_sql_string($_POST["pass"]);
			//echo $pass;
			
			for ($i=0; $i<strlen($pass); $i++){
				if (strpos($permPass, substr($pass,$i,1))===false){
					echo "<div class='avisoError'>La contrase&ntilde;a no es v&aacute;lida, solo puede contener letras may&uacute;sculas, min&uacute;sculas y n&uacute;meros ingrese otra contrase&ntilde;a.</div>";
					echo "<br/><br/>";
					echo '<div class="footer">';
					echo "<p>Sistema Nacional de Protecci&oacute;n Civil</p>";
					echo "</div>";
					return false;
					}
			}
		////////////////////////////////////////////////
		/*Insertar BD*/
			$firephp->log($_POST["rol"]);
			$rol=sanitize_sql_string($_POST["rol"]);
			//echo $rol;	
			
			switch($rol){
				case 1:
					$rolR="Administrador";
					break;
				case 2:
					$rolR="Capturista";
					break;
				case 3:
					$rolR="Visualizador";
					break;
				default:
					$rolR="Visualizador";
					break;					
			}
			
			$sql1 = "INSERT INTO CENACOM.ADMINISTRADORES (USUARIO,PASSWORD,ID_PERFIL,PERFIL,NOMBRE_REP)";
			$sql2 = "VALUES ('".$usuario."','".$pass."',".$rol.",'".$rolR."','".$Nom_usuario."')";
			$sql = $sql1.$sql2;
			
			$sqlIns = oci_parse ($conOracle,$sql);
			
			if (!$sqlIns) {
				$e = oci_error($conOracle);
				trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
			}
			$resultado=oci_execute($sqlIns);
			if (!$resultado) {
				$e = oci_error($sqlIns);
				trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
			}
			
			echo "<div class='aviso'>".$Nom_usuario." con el usuarios ".$usuario." ha sido registrado, con el rol de ".$rolR.". <br/></div>";
			echo "<br/><br/>";
			echo '<div class="footer">';
			echo "<p>Sistema Nacional de Protecci&oacute;n Civil</p>";
			echo "</div>";

			return true;
		}
}

//////////////////////////////////////////////
/*Eliminar usuarios*/
if (@$_POST["UserBorrar"]){
		$firephp->log($_POST["UserBorrar"]);
		$UserBorrar=sanitize_sql_string($_POST["UserBorrar"]);
		//echo $UserBorrar;
		$sql = "Delete FROM CENACOM.ADMINISTRADORES WHERE USUARIO='".$UserBorrar."'";
		//ECHO $sql;	
			$sqlDel = oci_parse ($conOracle,$sql);
			
			if (!$sqlDel) {
				$e = oci_error($conOracle);
				trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
			}
			$resultadoD=oci_execute($sqlDel);
			if (!$resultadoD) {
				$e = oci_error($sqlDel);
				trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
			}
		echo "<div class='aviso'>El usuario ha sido eliminado. <br/></div>";
}
	?>
	
	<div id="alta">

	<form method="post" id="fusuarios" name="fusuarios" action="prueba_usuarios.php">
	Nombre:
		<input id="Nom_usuario" name="Nom_usuario" title="Nombre de usuario" maxlength="30" type="text" style="width: 350px"/>
		<br/><br/>
	Usuario:
		<input id="usuario" name="usuario" title="Nombre de usuario" maxlength="30" type="text" style="width: 350px"/>
		<br/><br/>	
	Contrase&ntilde;a:
		<input id="pass" name="pass" title="Contraseña" type="password" maxlength="10" style="width: 100px"/>
		<br/><br/>
	Rol:
		<select id="rol" title="rol" name="rol">
			<option value="2">Capturista</option>
			<option value="3">Solo podra ver el mapa</option>
			<option value="1">Administrador</option>
		</select>
		<br/><br/>
		<div id="boton">
			<input type="submit" value="Registrar"/>
		</div>
	</form>
	<br/>
	</div>		
	-----------------------------------------------------------------------------------------------------
	<br/><br/>
	<div id="borrar">
	<form method="post" id="fusuarios" name="fusuarios" action="prueba_usuarios.php">
		Usuario a eliminar:
		<?php
			$query = oci_parse($conOracle, 'SELECT USUARIO, NOMBRE_REP from CENACOM.ADMINISTRADORES ORDER BY NOMBRE_REP ASC');
			comboQueryJS_1($query, "USUARIO", "NOMBRE_REP", 'UserBorrar', "Seleccione","Usuario a eliminar");
		?>
		<br/><br/>
		<div id="boton2">
			<input type="submit" value="Eliminar"/>
		</div>
		<br/><br/>
	</form>
	<div/>

<?php
	cerrarConexionORACLE($conOracle);
?>

		</div>
	</div>	
	

		<div class="footer">
			<p>Sistema Nacional de Protecci&oacute;n Civil</p>
		</div>
		

</body>
</html>