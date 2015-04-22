<?php
include 'functions.php';
include 'vars.php';
/*
require_once('FirePHPCore/FirePHP.class.php');
ob_start();
$firephp = FirePHP::getInstance(true);
 */

//Valida que la secion de usuario sea correcta
session_start();
if(@$_SESSION['login'] != "si")
{
header("Location:index.php");
exit();
}

$conOracle=conexionORACLE($HOST, $PORT, $SID, $userName, $passkey);
?>

<html>
<head>
	<title>Nothing else matter...</title>
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/functions.js"></script>
	<script language="JavaScript">
	/*FUNCIONES QUE RESCATAN LOS VALORES DE LOS COMBOS*/
	
function contruyeClaveLugar(clvEstado,clvMunicipio,clvLocalidad){
	
	console.log("Edo="+clvEstado)
	console.log("Mun="+clvMunicipio)
	console.log("Loc="+clvLocalidad)
	//Estado
	console.log("tamanioEdo="+clvEstado.length)
	switch(clvEstado.length){
		case 1:
			clvEstadoN="0"+clvEstado;
		break;
		default:
			clvEstadoN=clvEstado;
	}
	
	//Municipio
	tamanioMun=clvMunicipio.length;
	console.log("tamanioMun="+tamanioMun)
	if(clvMunicipio!=0){
		tamanioMun=clvMunicipio.length;
	}else{
		tamanioMun=3;
	}
	
	switch(tamanioMun){
		case 1:
			clvMunicipioN="00"+clvMunicipio;
		break;
		case 2:
			clvMunicipioN="0"+clvMunicipio;
		break;
		default:
			clvMunicipioN="001";
	}
	
	
	//Localidad
	if(clvLocalidad!=0){
		tamanioLoc=clvLocalidad.length;
	}else{
		tamanioLoc=5;
	}
	
	tamanioLoc=clvLocalidad.length;
	console.log("tamanioLoc="+tamanioLoc)
	switch(tamanioLoc){
		case 1:
			clvLocalidadN="000"+clvLocalidad;
		break;
		case 2:
			clvLocalidadN="00"+clvLocalidad;
		break;
		case 3:
			clvLocalidadN="0"+clvLocalidad;
		break;
		case 4:
			clvLocalidadN=clvLocalidad;
		break;
		default:
			clvLocalidadN='0001';
	}
	
	return clvEstadoN+clvMunicipioN+clvLocalidadN;
}

//function valuesAnidados(bloque, idCampoForm1){
function valuesAnidadosLugares(bloque){
	var lugaresUnion="";
	var lista=document.getElementById(bloque);
	for(i=0; i<lista.childNodes.length;i++){
		if(lista.childNodes[i].id != undefined){
	 		//campoId=lista.childNodes[i].id.substring(0,5)+lista.childNodes[i].id.substring(5)+sufijo;
	 		//titulocampoId="titulo"+campoId;
	 		/*
	 		console.log(campoId)
	 		console.log(titulocampoId)
	 		console.log(document.getElementById(titulocampoId).value)
	 		*/
	 		//console.log(lista.childNodes[i].id);
	 		//console.log(lista.childNodes[i].id.substr(7));
	 		numeroDeLugar=lista.childNodes[i].id.substr(7);
	 		console.log('Estado: '+document.getElementById('Estado'+numeroDeLugar).value)
	 		console.log('Municipio: '+document.getElementById('Municipio'+numeroDeLugar).value)
	 		console.log('Localidad: '+document.getElementById('Localidad'+numeroDeLugar).value)
	 		construyeClave=contruyeClaveLugar(document.getElementById('Estado'+numeroDeLugar).value,document.getElementById('Municipio'+numeroDeLugar).value,document.getElementById('Localidad'+numeroDeLugar).value)
	 		console.log(construyeClave)
		}
	}
	//document.getElementById(idCampoForm1).value=linksUnion;
	//console.log(document.getElementById(idCampoForm1).value);
	//return 1;
}

	/*FIN FUNCIONES QUE RESCATAN LOS VALORES DE LOS COMBOS*/
	
	
	/* FUNCIONALIDAD DE lugares */
	function cuentaElementosBloque(bloque){
		var lista=document.getElementById(bloque);
		nodeCount=0
		for(i=0; i<lista.childNodes.length;i++){
			if(lista.childNodes[i].id != undefined){
			nodeCount++
			}
		}
	return nodeCount
	}

function escribeLayerLugar(cuenta){
	var capa  = document.createElement("div");
	id='lugares'+cuenta
	capa.setAttribute("id", id);

	document.getElementById('lugar').appendChild(capa);
	cuenta++;
    document.getElementById('agregarLugar').innerHTML = '<a href="#lugar" onclick="agregarLugar(\'lugar\','+cuenta+');">Agregar otro lugar</a>';
    ajaxEstados(id, cuenta-1)
    
}

function agregarLugar(bloque, cuenta){
		numeroLugares=10;		
		lugarForm=cuentaElementosBloque(bloque);
		if(lugarForm == numeroLugares){
			alert("Solo se pueden agregar "+numeroLugares+" Lugares")
		}else{
			escribeLayerLugar(cuenta)
		}		
	}

function borraLayer(node){
    node.parentNode.removeChild(node);
}

function ajaxEstados(divNombre, valor){
	//donde se mostraran los registros
	divContenido = document.getElementById(divNombre);

	borrado='<br/><a href="#lugares" onclick="borraLayer('+divNombre+');">Eliminar</a><br /><br />';
	ajax=objetoAjax();
	//uso del medoto GET
	//indicamos el archivo que realizara el proceso de paginar
	//junto con un valor que representa el nro de pagina
	//console.log(ajax.open("GET", +"ajax.php?comboC=estados"));
	ajax.open("GET", "ajax.php?valor="+valor+"&comboC=estados");
	//ajax.open("GET", seccion+"&pag="+nropagina);
	divContenido.innerHTML= '<img src="anim.gif">';
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			//mostrar resultados en esta capa
			divContenido.innerHTML = ajax.responseText+borrado
		}
	}
	//como hacemos uso del metodo GET
	//colocamos null ya que enviamos 
	//el valor por la url ?pag=nropagina
	ajax.send(null)
}

/* FIN FUNCIONALIDAD DE lugares */


/* FUNCIONALIDAD DE VALIDACION DE lugares */

/* FIN FUNCIONALIDAD DE VALIDACION DE lugares */

</script>
</head>
<body>

	<div id="agregarLugar"><a href="#lugares" onclick="agregarLugar('lugar', 1);">Agregar otro lugar</a></div>
	<div id="lugar" >
		<div id="lugares0">
	<?php
		$query = oci_parse($conOracle, 'SELECT ENTIDAD, NOM_ENT from ANRO.LOCALIDADES GROUP BY ENTIDAD, NOM_ENT ORDER BY ENTIDAD');
		//comboQueryJS_3c($query,"ENTIDAD", 'NOM_ENT', 'unicoEstado', 'unicolugar', 'unicoLocalidad', 'lugar', 'localidad', 'Seleccionar', 'Lugar');
		comboQueryJS_3c($query,"ENTIDAD", 'NOM_ENT', 'Estado0', 'Municipio0', 'Localidad0', 'municipio', 'localidad', 'Seleccionar', 'Lugar');
	?>	
		<br /><br />
		</div>
	</div>
	<input type="hidden" name="lugares" id="lugares">


</body>
</html> 