<?php
require('./variables.php');
function consultaSQLPass($campoSelect, $campoWhere, $user1, $pass1, $tablePass){
$password="No";
$valor=$pass1;
$pass1=filtroSQL($valor);
$valor=$user1;
$user1=filtroSQL($valor);
$password=consultaSQLGetValor($campoSelect, $campoWhere, $user1, $tablePass);
if($password==$pass1){return "correcto";}
else{return "incorrecto";}
}



function consultaSQLGetValor($campoSelect, $campoWhere, $valor, $tabla){
$consulta = "SELECT ".$campoSelect." from ".$tabla." where ".$campoWhere." = '".$valor."'";
$resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
$linea = mysql_fetch_array($resultado, MYSQL_ASSOC);
return $linea[$campoSelect];
}



 if((@$_POST["amigo"])&&(@$_POST["claveAmigo"])){
   $canservero=consultaSQLPass("pass", "usuario", $_POST["amigo"], $_POST["claveAmigo"], "usuarios_dir");
	if($canservero!="correcto"){
		$_SESSION['tmptxt']="Tu no pasaraaaaaaaas!";
		$_SESSION['login'] = "no";		
	}else{
	 	$_SESSION['login'] = "si";
     	$_SESSION['nombre'] = $_POST["amigo"];
	 	header("Location:principal.php");
	}



?>