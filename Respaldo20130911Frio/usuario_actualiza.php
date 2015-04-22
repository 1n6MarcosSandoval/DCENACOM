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
?>



<?php
//$firephp->log($_POST["operacion"], 'Valor de operacion');
//	var_dump($_POST);

//	echo '<script languaje="JavaScript">if (document.getElementById("actualizaUsuario")){ ';
//	echo "document.getElementById('actualizaUsuario').style.display = (document.getElementById('actualizaUsuario')style.display == 'none') ? 'block' : 'none';";
//	echo '}<script>';


if($_POST["operacion"]=="actualiza"){
	//$firephp->log($_POST["operacion"], 'Desde Actualiza');
	$sql01="UPDATE CENACOM.USUARIOS_CENACOM SET NOMBRE='".$_POST["nombre"]."', APELLIDO='".$_POST["apellido"]."', CORREO='".$_POST["correo"]."', TURNO='".$_POST["turno"]."' ";
	$sql02="WHERE ID_USUARIO=".$_POST["usuario"];
	$sql=$sql01.$sql02;
	
	//ESCRIBE EN LA BD QUERY DE ACTUALIZACION DE USUARIO
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
		echo '<div class="aviso">Los datos <b>se actualizaron correctamente</b> en la base de datos.
		<input type="button" value="Cerrar" onclick=\'muestra_oculta_capa("editaUsuario");\'>
		</div>';
	}else{
		echo '<div class="avisoError">Hubo un error con la actualizaci&oacute;n en la base de datos.
			<input type="button" value="Cerrar" onclick=\'muestra_oculta_capa("editaUsuario");\'>
			</div>';	
	}

}

if($_POST["operacion"]=="elimina"){
	//$firephp->log($_POST["operacion"], 'Desde Elimina');
	
	$query = oci_parse($conOracle, "select cr from CENACOM.reportes where id_usuario=".$_POST["usuario"]);
	oci_execute($query);
	$row_usuario_existe = oci_fetch_array($query, OCI_NUM);
	//$firephp->log($row_usuario_existe[0], 'Haber si elimina');
	if($row_usuario_existe[0]>0){
		echo '<div class="avisoError">';
		echo "No se puede borrar ya que uno o m&aacute;s registros utilizan este valor en la base de datos.";
		echo '</div>';
	}else{
		$sql="DELETE from cenacom.usuarios_cenacom where id_usuario=".$_POST["usuario"];
	
		//ESCRIBE EN LA BD QUERY DE ELIMINACION DE USUARIO
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
			echo '<div class="aviso">Los datos <b>se borraron correctamente</b> en la base de datos.
			<input type="button" value="Actualizar lista" onclick=\'PaginaConParametro("1","lista_pag_usuarios.php?orden=nombre");\'>
			<input type="button" value="Cerrar" onclick=\'muestra_oculta_capa("editaUsuario");\'>
			</div>';
		}else{
			echo '<div class="avisoError">Hubo un error en el borrado en la base de datos.
			<input type="button" value="Cerrar" onclick=\'muestra_oculta_capa("editaUsuario");\'>
			</div>';	
		}	

	}
}


	
cerrarConexionORACLE($conOracle);
?>