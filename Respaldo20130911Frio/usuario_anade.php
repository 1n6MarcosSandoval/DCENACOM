<?php

include 'functions.php';
include 'vars.php';
require_once('FirePHPCore/FirePHP.class.php');
ob_start();
$firephp = FirePHP::getInstance(true);

//Valida que la secion de usuario sea correcta
session_start();
if(@$_SESSION['login'] != "si")
{
header("Location:index.php");
exit();
}elseif(@$_SESSION['nombre']!="cenacomAdmin"){
header("Location:principal.php");
}

$conOracle=conexionORACLE($HOST, $PORT, $SID, $userName, $passkey);


//var_dump($_POST);

if(@$_POST["operacion"]=="anade"){
	$query = oci_parse($conOracle, 'SELECT MAX(ID_USUARIO) AS VALOR FROM CENACOM.USUARIOS_CENACOM');
	oci_execute($query);
	$row = oci_fetch_array($query, OCI_ASSOC);
	$IDregistrar=$row["VALOR"]+1;	
		
	
	$sql01="INSERT INTO CENACOM.USUARIOS_CENACOM(ID_USUARIO, NOMBRE, APELLIDO, CORREO, USUARIO, PASSW, TURNO)";
	$sql02="VALUES(".$IDregistrar.", '".$_POST["nombre"]."', '".$_POST["apellido"]."', '".$_POST["correo"]."', '', '',".$_POST["turno"].")";
	
	$sql=$sql01.$sql02;
	//print $sql;
	
	
	//ESCRIBE EN LA BD QUERY DE NUEVO USUARIO
	$escribeOracle=oci_parse($conOracle,$sql);
	if (!$escribeOracle) {
    	$e = oci_error($conOracle);
    	trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}
	$resultadoEnOracle=oci_execute($escribeOracle);
	if (!$resultadoEnOracle) {
    	$e = oci_error($escribeOracle);
    	trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}	
	
	if($resultadoEnOracle){
		echo '<div class="aviso">Los datos <b>se guardaron correctamente</b> en la base de datos. 
		<input type="button" value="Actualizar lista" onclick=\'PaginaConParametro("1","lista_pag_usuarios.php?orden=nombre");\'>
		</div>';
		
	}else{
		echo '<div class="avisoError">Hubo un error con el guardado en la base de datos.
		<input type="button" value="Cerrar" onclick=\'muestra_oculta_capa("actualizaUsuario");\'>
		</div>';
	}
}

	cerrarConexionORACLE($conOracle);
?>


</script>