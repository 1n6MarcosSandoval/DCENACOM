//Funcion que cambia el formulario
//function cambiaForm(){
function cambiaForm(tipoForm){
	document.getElementById('cambio').innerHTML = 'Seleccionaste: '+tipoForm;
}

//Funcion que aumenta una capa
function writeThere(cuenta){
	var capa  = document.createElement("div");
	id="capa"+cuenta;
	//console.log(id)
	capa.setAttribute("id", id);
	
	document.getElementById('capas').appendChild(capa);
    
    capa.innerHTML = 'Nueva Capa '+id+' <br/> <a href="#" onclick="removeThere('+id+');">Borrar</a>';
    
    cuenta++;
    document.getElementById('link').innerHTML = '<?php echo "Hola mundo "; ?><a href="#" onclick="writeThere('+cuenta+');">Agregar Capa</a>';
    
}

//Funcion que borra una capa
function removeThere(node){
    node.parentNode.removeChild(node);	
}

/* Funciones COMBO */
function LimpiarCombo(combo){
	while(combo.length > 0){
		combo.remove(combo.length-1);
	}
}
function LlenarCombo(json, combo){
	//combo.options[0].value = new Option('Seleccionar', '');
	combo.options[0] = new Option('Seleccionar', '0');
	for(var i=0;i<json.length;i++){
		combo.options[combo.length] = new Option(json[i].data, json[i].id);
	}
}

function LlenarComboM(json, combo){
	for(var i=0;i<json.length;i++){
		combo.options[combo.length] = new Option(json[i].data, json[i].id);
	}
}

function SeleccionandoCombo_3(combo1, combo2, combo3, comboC){
/*	console.log(combo1)
	console.log(combo2)
	console.log(combo3)
*/
	combo2 = document.getElementById(combo2); //con jquery: $("#"+combo2)[0];
	LimpiarCombo(combo2);
	combo3 = document.getElementById(combo3);
	LimpiarCombo(combo3);
	//console.log(combo1.options[combo1.selectedIndex].value);
	combo1.value=combo1.options[combo1.selectedIndex].value;
	if(combo1.options[combo1.selectedIndex].value != ""){
		combo2.disabled = true;
		combo3.disabled = true;
		$.ajax({
			type: 'get',
			dataType: 'json',
			url: 'ajax.php',
			data: {valor: combo1.options[combo1.selectedIndex].value, comboC: comboC},
			success: function(json){
				LlenarCombo(json, combo2);
				combo1.disabled = false;
				combo2.disabled = false;
			}
		});		
	}
}




function SeleccionandoCombo(combo1, combo2, comboC){
	combo2 = document.getElementById(combo2); //con jquery: $("#"+combo2)[0];
	LimpiarCombo(combo2);
	if(combo1.options[combo1.selectedIndex].value != ""){
		combo2.disabled = true;
		$.ajax({
			type: 'get',
			dataType: 'json',
			url: 'ajax.php',
			data: {valor: combo1.options[combo1.selectedIndex].value, comboC: comboC},
			success: function(json){
				LlenarCombo(json, combo2);
				combo1.disabled = false;
				combo2.disabled = false;
			}
		});
	}
	
}
/* FUNCION ORIGINAL
function SeleccionandoComboLocalidad(combo1, combo2, comboC){
	var valoresCombo = new Array();
	combo2 = document.getElementById(combo2); //con jquery: $("#"+combo2)[0];
	LimpiarCombo(combo2);
	if(document.getElementById("unicoMunicipio") || document.getElementById("inicialMunicipio")){
		combo2.disabled = true;
		if(document.getElementById('unicoEstado')){
			valoresCombo[0]=document.getElementById("unicoEstado").value;
			valoresCombo[1]=document.getElementById("unicoMunicipio").value;
		}
		if(document.getElementById('inicialEstado')){
			valoresCombo[0]=document.getElementById("inicialEstado").value;
			valoresCombo[1]=document.getElementById("inicialMunicipio").value;
		}
		
		
		//console.log(valoresCombo);
		$.ajax({
			type: 'get',
			dataType: 'json',
			url: 'ajax.php',
			data: {valor: valoresCombo, comboC: comboC},
			success: function(json){
				LlenarCombo(json, combo2);
				combo1.disabled = false;
				combo2.disabled = false;
			}
		});
	}
	
}*/

function SeleccionandoComboLocalidad(comboEstado,comboMunicipio, combo1, combo2, comboC){
	//console.log(comboEstado)
	//console.log(comboMunicipio)
	var valoresCombo = new Array();
	combo2 = document.getElementById(combo2); //con jquery: $("#"+combo2)[0];
	LimpiarCombo(combo2);
	
	valoresCombo[0]=document.getElementById(comboEstado).value;
	valoresCombo[1]=document.getElementById(comboMunicipio).value;
		$.ajax({
			type: 'get',
			dataType: 'json',
			url: 'ajax.php',
			data: {valor: valoresCombo, comboC: comboC},
			success: function(json){
				LlenarCombo(json, combo2);
				combo1.disabled = false;
				combo2.disabled = false;
			}
		});
	
	
}


function SeleccionandoComboM(combo1, combo2, comboC){
	combo2 = document.getElementById(combo2); //con jquery: $("#"+combo2)[0];
	LimpiarCombo(combo2);
	if(combo1.options[combo1.selectedIndex].value != ""){
		combo2.disabled = true;
		$.ajax({
			type: 'get',
			dataType: 'json',
			url: 'ajax.php',
			data: {valor: combo1.options[combo1.selectedIndex].value, comboC: comboC},
			success: function(json){
				LlenarComboM(json, combo2);
				combo1.disabled = false;
				combo2.disabled = false;
			}
		});
	}
}


function SeleccionandoCombo0(combo1, combo2){
	combo2 = document.getElementById(combo2); //con jquery: $("#"+combo2)[0];
	//alert(combo2);
	LimpiarCombo(combo2);
	if(combo1.options[combo1.selectedIndex].value != ""){
		combo1.disabled = true;
		combo2.disabled = true;
		$.ajax({
			type: 'get',
			dataType: 'json',
			url: 'ajax.php',
			data: {valor: combo1.options[combo1.selectedIndex].value},
			success: function(json){
				LlenarCombo(json, combo2);
				combo1.disabled = false;
				combo2.disabled = false;
			}
		});
	}
}

  function Seleccionar(combo){
      var indice = combo.selectedIndex;
      var valor = combo.options[combo.selectedIndex].text;
      alert(indice);
      alert(valor);
      
      //Guardamos en un hidden
      document.getElementById('<%=HiddenField1.ClientId%>').value = valor;
  }
/*FIN Funciones COMBO */

/*Funciones autores */
function cuentaElementosBloque(bloque){
  var lista=document.getElementById(bloque);
  nodeCount=0;
  for(i=0; i<lista.childNodes.length;i++){
  	if(lista.childNodes[i].id != undefined){
  		nodeCount++;
  	}
  }
  return nodeCount;
}


function escribeLayerAutor(cuenta){
	var capa  = document.createElement("div");
	id="capa"+cuenta;
	capa.setAttribute("id", id);	
	document.getElementById('autor').appendChild(capa);    
    capa.innerHTML = 'Nueva Capa '+id+' <br/> <a href="#autores" onclick="borraLayer('+id+');">Borrar</a>';    
    cuenta++;
    document.getElementById('agregarAutor').innerHTML = '<?php //echo "Hola mundo "; ?><a href="#autores" onclick="agregarAutor(\'autor\','+cuenta+');">Agregar Autor</a>';
}

function escribeLayerLink(cuenta){
	var capa  = document.createElement("div");
	id='links'+cuenta;
	capa.setAttribute("id", id);

	document.getElementById('link').appendChild(capa);
	contenidoT = 'T&iacute;tulo de noticia:<input id="titulo'+id+'T" name="titulo'+id+'T" title="T&iacute;tulo de link" size="49" maxlength="990" type="text" value=""/><br/>';
    contenidoL='Link de noticia: <input id="'+id+'T" name="'+id+'T" maxlength="980" size="50" type="text" value="" title="URL del link"/>';
    contenido=contenidoT+contenidoL;
    capa.innerHTML = '<br/>'+contenido+' <a href="#links" onclick="borraLayer('+id+');">Eliminar</a> ';

	cuenta++;
    document.getElementById('agregarLink').innerHTML = '<a href="#link" onclick="agregarLink(\'link\','+cuenta+');">Agregar otro link</a>';
}

function borraLayer(node){
    node.parentNode.removeChild(node);
}

/*FIN Funciones autores */

/* Funcion checa y alerta el numero de caracteres que se estan ingresando en un textarea */
function alertNumCaracTextArea(campo, numeroMaxCaracteres){
		if(document.getElementById(campo).value.length >= numeroMaxCaracteres)
		{
				estilo='style="font-family:Arial; font-size:12px; border: 2px solid red; width: 416px; padding-left:6px; color:#FFFFFF; background-color:#FF0000;"';
				//document.getElementById(campo+'Leyenda').innerHTML =document.getElementById(campo).title+':<div '+estilo+' >'+'Excedido m&aacute;ximo de caracter'+'</div>';
				document.getElementById(campo+'Leyenda').innerHTML =document.getElementById(campo).title+':<div '+estilo+' id="Error" >'+'Excedido m&aacute;ximo de caracter'+'</div>';
				document.getElementById('boton').innerHTML='<div '+estilo+'>'+'Existe uno o varios problemas de exceso de caracteres en el formulario</div> <input type="button"  value="Registrar" disabled="true" />';
				//document.getElementById('boton').innerHTML='<div '+estilo+' id="'+campo+'Error">'+'Existe uno o varios problemas de exceso de caracteres en el formulario</div> <input type="button"  value="Registrar" disabled="true" />';
				return false; 
		}else{
			
				document.getElementById(campo+'Leyenda').innerHTML = document.getElementById(campo).title+":";
			if(!document.getElementById('Error')){
				document.getElementById('boton').innerHTML='<input type="button"  value="Registrar" onclick="valida()" />';
			}
			return true;
		}
	
	
}

/* FIN Funcion checa y alerta el numero de caracteres que se estan ingresando en un textarea */


function escribeLinkAlReporte(CR,tipoReporte){
	if(CR>0){
		switch(tipoReporte)
		{
			case "SEGUIMIENTO":
				escribePostAlReporte(CR,'formSeguimiento.php', tipoReporte);
				document.getElementById('SEGUIMIENTO').innerHTML='<a href="#" onclick="document.fnuevo'+tipoReporte+'.submit();">Generar reporte</a>';
			break;
			case "FINAL":
				escribePostAlReporte(CR,'formFinal.php', tipoReporte);
				document.getElementById('FINAL').innerHTML='<a href="#" onclick="document.fnuevo'+tipoReporte+'.submit();">Generar reporte</a>';
			break;
			case "ALCANCE":
				escribePostAlReporte(CR,'formAlcance.php', tipoReporte);
				document.getElementById('ALCANCE').innerHTML='<a href="#" onclick="document.fnuevo'+tipoReporte+'.submit();">Generar reporte</a>';
			break;
			case "ALCANCE2":
				escribePostAlReporte(CR,'formAlcance.php', tipoReporte);
				document.getElementById('ALCANCE2').innerHTML='<a href="#" onclick="document.fnuevo'+tipoReporte+'.submit();">Generar reporte</a>';
			break;
			default:
				aler("Sin parámetro de tipo de reporte.");
		}
		
	}else{
		document.getElementById(tipoReporte).innerHTML='';
	}	
}


function escribePostAlReporte(CR,paginaReporte,tipoReporte){
	var nameCampo='fnuevo'+tipoReporte;
	var formCR='<form method="post" id="'+nameCampo+'" name="'+nameCampo+'" action="'+paginaReporte+'">'+'<input type="hidden" name="crReal" id="crReal" value="'+CR+'">'+'</form>';
	document.getElementById('postForm'+tipoReporte).innerHTML=formCR;
}


function escribeLinkAlReporteE(CR,tipoReporte){
	if(CR>0){
		switch(tipoReporte)
		{
			case "UNICO":
				escribePostAlReporteE(CR,'formUnicoE.php', tipoReporte);
//				document.getElementById('UNICO').innerHTML='<a href="#" onclick="document.fnuevo'+tipoReporte+'.submit();">Generar reporte</a>';
			break;
			case "INICIAL":
				escribePostAlReporteE(CR,'formInicialE.php', tipoReporte);
//				document.getElementById('INICIAL').innerHTML='<a href="#" onclick="document.fnuevo'+tipoReporte+'.submit();">Generar reporte</a>';
			break;
			case "SEGUIMIENTO":
				escribePostAlReporteE(CR,'formSeguimientoE.php', tipoReporte);
//				document.getElementById('SEGUIMIENTO').innerHTML='<a href="#" onclick="document.fnuevo'+tipoReporte+'.submit();">Generar reporte</a>';
			break;
			case "FINAL":
				escribePostAlReporteE(CR,'formFinalE.php', tipoReporte);
//				document.getElementById('FINAL').innerHTML='<a href="#" onclick="document.fnuevo'+tipoReporte+'.submit();">Generar reporte</a>';
			break;
			case "ALCANCE":
				escribePostAlReporteE(CR,'formAlcanceE.php', tipoReporte);
//				document.getElementById('ALCANCE').innerHTML='<a href="#" onclick="document.fnuevo'+tipoReporte+'.submit();">Generar reporte</a>';
			break;
			default:
				aler("Sin parámetro de tipo de reporte.");
		}
	}else{
		document.getElementById(tipoReporte).innerHTML='';
	}
}

function escribePostAlReporteE(CR,paginaReporte,tipoReporte){
	var nameCampo='fnuevo'+tipoReporte;
	var formCR='<form method="post" id="'+nameCampo+'" name="'+nameCampo+'" action="'+paginaReporte+'">'+'<input type="hidden" name="crReal" id="crReal" value="'+CR+'">'+'</form>';
	//console.log(nameCampo);
	document.getElementById('postForm'+tipoReporte).innerHTML=formCR;
	document.getElementById(nameCampo).submit();
}

//Usado por los paginadores
function objetoAjax(){
	var xmlhttp=false;
	try {
		xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
	} catch (e) {
		try {
		   xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		} catch (E) {
			xmlhttp = false;
  		}
	}

	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
		xmlhttp = new XMLHttpRequest();
	}
	return xmlhttp;
}

function Pagina(nropagina, seccion){
	//donde se mostrar? los registros
	divContenido = document.getElementById('contenido');
	//alert(seccion+"?pag="+nropagina);
	ajax=objetoAjax();
	//uso del medoto GET
	//indicamos el archivo que realizara el proceso de paginar
	//junto con un valor que representa el nro de pagina
	ajax.open("GET", seccion+"?pag="+nropagina);
	//ajax.open("GET", seccion+"&pag="+nropagina);
	divContenido.innerHTML= '<img src="anim.gif">';
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			//mostrar resultados en esta capa
			divContenido.innerHTML = ajax.responseText
		}
	}
	//como hacemos uso del metodo GET
	//colocamos null ya que enviamos 
	//el valor por la url ?pag=nropagina
	ajax.send(null);
}

function PaginaConParametro(nropagina, seccion){
	//donde se mostrar? los registros
	divContenido = document.getElementById('contenido');
	//alert(seccion+"?pag="+nropagina);
	ajaxParam=objetoAjax();
	//uso del medoto GET
	//indicamos el archivo que realizar? el proceso de paginar
	//junto con un valor que representa el nro de pagina
	ajaxParam.open("GET", seccion+"&pag="+nropagina);
	//ajax.open("GET", seccion+"&pag="+nropagina);
	divContenido.innerHTML= '<img src="anim.gif">';
	ajaxParam.onreadystatechange=function() {
		if (ajaxParam.readyState==4) {
			//mostrar resultados en esta capa
			divContenido.innerHTML = ajaxParam.responseText;
		}
	}
	//como hacemos uso del metodo GET
	//colocamos null ya que enviamos 
	//el valor por la url ?pag=nropagina
	ajaxParam.send(null)
}

function editaUsuario(idUsuario){
	divContenido = document.getElementById('editaUsuario');
	ajax=objetoAjax();
	
	//ajax.open("GET", editausuario.php+"?usuario="+idUsuario);
	ajax.open("GET", "editausuario.php?usuario="+idUsuario);
	divContenido.innerHTML= '<img src="anim.gif">';
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			//mostrar resultados en esta capa
			divContenido.innerHTML = ajax.responseText;
		}
	}
	//como hacemos uso del metodo GET
	//colocamos null ya que enviamos 
	ajax.send(null);
}

function actualizaUsuario(){
	divContenido = document.getElementById('actualizaUsuario');
	
	nombre=document.getElementById('edicionNombre').value;
	apellido=document.getElementById('edicionApellido').value;
	correo=document.getElementById('edicionCorreo').value;
	turno=document.getElementById('edicionTurno').value;
	usuario=document.getElementById('usuario').value;
	
	ajax=objetoAjax();
	
	ajax.open("POST", "usuario_actualiza.php",true);
	divContenido.innerHTML= '<img src="anim.gif">';
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			//mostrar resultados en esta capa
			divContenido.innerHTML = ajax.responseText;
		}
	}

	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("operacion=actualiza"+"&nombre="+nombre+"&apellido="+apellido+"&correo="+correo+"&turno="+turno+"&usuario="+usuario);

}

function eliminaUsuario(){
	divContenido = document.getElementById('actualizaUsuario');
	usuario=document.getElementById('usuarioElimina').value;
	
	ajax=objetoAjax();
	
	ajax.open("POST", "usuario_actualiza.php",true);
	divContenido.innerHTML= '<img src="anim.gif">';
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			//mostrar resultados en esta capa
			divContenido.innerHTML = ajax.responseText;
		}
	}

	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("operacion=elimina"+"&usuario="+usuario);

}


function anadirUsuario(){
	divContenido = document.getElementById('nuevoUsuario');
	ajaxAnade=objetoAjax();
	
	ajaxAnade.open("POST", "anadirusuario.php",true);
	divContenido.innerHTML= '<img src="anim.gif">';
	ajaxAnade.onreadystatechange=function() {
		if (ajaxAnade.readyState==4) {
			//mostrar resultados en esta capa
			divContenido.innerHTML = ajaxAnade.responseText;
		}
	}

	ajaxAnade.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajaxAnade.send(null);
	
}


function anadeUsuario(){
	
	divContenido = document.getElementById('anadirUsuario');
	
	nombre=document.getElementById('anadirNombre').value;
	apellido=document.getElementById('anadirApellido').value;
	correo=document.getElementById('anadirCorreo').value;
	turno=document.getElementById('anadirTurno').value;
	
	ajaxAnade=objetoAjax();
	
	ajaxAnade.open("POST", "usuario_anade.php", true);
	divContenido.innerHTML= '<img src="anim.gif">';
	ajaxAnade.onreadystatechange=function() {

		if (ajaxAnade.readyState==4) {
			//mostrar resultados en esta capa
			divContenido.innerHTML = ajaxAnade.responseText;
		}
	}

	ajaxAnade.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajaxAnade.send("operacion=anade"+"&nombre="+nombre+"&apellido="+apellido+"&correo="+correo+"&turno="+turno);
	//ajax.send(null);
	
	document.getElementById('anadirNombre').value="";
	document.getElementById('anadirApellido').value="";
	document.getElementById('anadirCorreo').value="";
}



//FIN Usado por los paginadores
function checaVacio(valor, mensaje){
	if(valor==""){
		alert(mensaje);
		return 0;
	}
	return 1;
}




//Funciones de formato UTF8

function utf8_encode (argString) {
  // http://kevin.vanzonneveld.net
  // +   original by: Webtoolkit.info (http://www.webtoolkit.info/)
  // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +   improved by: sowberry
  // +    tweaked by: Jack
  // +   bugfixed by: Onno Marsman
  // +   improved by: Yves Sucaet
  // +   bugfixed by: Onno Marsman
  // +   bugfixed by: Ulrich
  // +   bugfixed by: Rafal Kukawski
  // +   improved by: kirilloid
  // *     example 1: utf8_encode('Kevin van Zonneveld');
  // *     returns 1: 'Kevin van Zonneveld'

  if (argString === null || typeof argString === "undefined") {
    return "";
  }

  var string = (argString + ''); // .replace(/\r\n/g, "\n").replace(/\r/g, "\n");
  var utftext = '',
    start, end, stringl = 0;

  start = end = 0;
  stringl = string.length;
  for (var n = 0; n < stringl; n++) {
    var c1 = string.charCodeAt(n);
    var enc = null;

    if (c1 < 128) {
      end++;
    } else if (c1 > 127 && c1 < 2048) {
      enc = String.fromCharCode((c1 >> 6) | 192, (c1 & 63) | 128);
    } else {
      enc = String.fromCharCode((c1 >> 12) | 224, ((c1 >> 6) & 63) | 128, (c1 & 63) | 128);
    }
    if (enc !== null) {
      if (end > start) {
        utftext += string.slice(start, end);
      }
      utftext += enc;
      start = end = n + 1;
    }
  }

  if (end > start) {
    utftext += string.slice(start, stringl);
  }

  return utftext;
}


function utf8_decode (str_data) {
  // http://kevin.vanzonneveld.net
  // +   original by: Webtoolkit.info (http://www.webtoolkit.info/)
  // +      input by: Aman Gupta
  // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +   improved by: Norman "zEh" Fuchs
  // +   bugfixed by: hitwork
  // +   bugfixed by: Onno Marsman
  // +      input by: Brett Zamir (http://brett-zamir.me)
  // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // *     example 1: utf8_decode('Kevin van Zonneveld');
  // *     returns 1: 'Kevin van Zonneveld'
  var tmp_arr = [],
    i = 0,
    ac = 0,
    c1 = 0,
    c2 = 0,
    c3 = 0;
  str_data += '';
  while (i < str_data.length) {
    c1 = str_data.charCodeAt(i);
    if (c1 < 128) {
      tmp_arr[ac++] = String.fromCharCode(c1);
      i++;
    } else if (c1 > 191 && c1 < 224) {
      c2 = str_data.charCodeAt(i + 1);
      tmp_arr[ac++] = String.fromCharCode(((c1 & 31) << 6) | (c2 & 63));
      i += 2;
    } else {
      c2 = str_data.charCodeAt(i + 1);
      c3 = str_data.charCodeAt(i + 2);
      tmp_arr[ac++] = String.fromCharCode(((c1 & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
      i += 3;
    }
  }
  return tmp_arr.join('');
}
//FIN Funciones de formato UTF8

//FUNCIONES DE EDICION
//Para seleccion de opciones en combo simple
function seleccionaComboSimple(Combo, opcion){
	idCombo=document.getElementById(Combo);
  	for (i=0; opciones = idCombo.options[i]; i++){
   		if(idCombo.options[i].value==opcion)
    		opciones.selected = true;
   }
}
//Para seleccion de opciones en combo multiple
function seleccionaComboMultiple(Combo, opcionesArray){
	var valores = opcionesArray.split(',');
	idCombo=document.getElementById(Combo);
	for(j=0;j<valores.length;j++){
  		for (i=0; opciones = idCombo.options[i]; i++){
   			if(idCombo.options[i].value==valores[j]){
    			opciones.selected = true;
    		}
    	}
   }
}


function activaDesactivaCampo(formNombreCampo){

	if(document.getElementById(formNombreCampo).disabled){
		document.getElementById(formNombreCampo).removeAttribute('disabled');
	}else{
		document.getElementById(formNombreCampo).setAttribute('disabled', 'disabled');
	}

}


function muestra_oculta_capa(id){
if (document.getElementById){ //se obtiene el id
var el = document.getElementById(id); //se define la variable "el" igual a nuestro div
el.style.display = (el.style.display == 'none') ? 'block' : 'none'; //damos un atributo display:none que oculta el div
}
}

function muestra_capa(id){
	if (document.getElementById(id).style.display=="none"){ //se necesita que tenga el estilo display
		document.getElementById(id).style.display="block";
	}
}

/****** FUNCIONES DE LUGAR **********/

function valuesAnidadosLugares(bloque){
	var otrosLugaresUnion="";
	var lugaresUnion="";
	var construyeClave="";
	var lista=document.getElementById(bloque);
	for(i=0; i<lista.childNodes.length;i++){
		if(lista.childNodes[i].id != undefined){
	 		numeroDeLugar=lista.childNodes[i].id.substr(7);
	 		
	 		
	 		if(document.getElementById('Estado'+numeroDeLugar).value=="" || document.getElementById('Estado'+numeroDeLugar).value==0){
	 			alert("Se necesita lugar");
	 			document.getElementById('Estado'+numeroDeLugar).focus();
	 			return 0;
	 		}
	 		
	 		construyeClave=contruyeClaveLugar(document.getElementById('Estado'+numeroDeLugar).value,document.getElementById('Municipio'+numeroDeLugar).value,document.getElementById('Localidad'+numeroDeLugar).value);
 		
			if(lugaresUnion==""){
				lugaresUnion=construyeClave;
			}else{
				lugaresUnion=lugaresUnion+","+construyeClave;
			}
			
		
		if(trim(document.getElementById('OtroLugar'+numeroDeLugar).value)!=""){			
			if(otrosLugaresUnion==""){
				otrosLugaresUnion=document.getElementById('OtroLugar'+numeroDeLugar).value;
			}else{
				otrosLugaresUnion=otrosLugaresUnion+"+"+document.getElementById('OtroLugar'+numeroDeLugar).value;
			}
		}
		}
	}
	if(lugaresUnion=""){
		alert("Faltan lugares");
		document.getElementById('lugar').focus();
		return 0;
	}else{
	document.getElementById('lugares').value=lugaresUnion;	
	document.getElementById('otroslugares').value=otrosLugaresUnion;
	}	
	return 1;
}



function contruyeClaveLugar(clvEstado,clvMunicipio,clvLocalidad){

	switch(clvEstado.length){
		case 1:
			clvEstadoN="0"+clvEstado;
		break;
		default:
			clvEstadoN=clvEstado;
	}
	
	//Municipio
	tamanioMun=clvMunicipio.length;
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

function sustituyePuntoycomaPorComa(campoId){
	cadena=document.getElementById(campoId).value;
	nuevaCadena = cadena.replace(';', ',');
	document.getElementById(campoId).value=nuevaCadena;
}

function sustituyeComaPorPuntoycoma(cadena){
	nuevaCadena = cadena.replace(',', ';');
	return nuevaCadena;
}

function trim (myString){
return myString.replace(/^\s+/g,'').replace(/\s+$/g,'');
}



function cabecerasEstatales(valorEdo, valor){
	var Mun=10;
	//console.log("valorEdo: "+valorEdo);
	//console.log(valor);
	switch(valorEdo){
		case "1":
			Mun=1;
		break;
		case "2":
			Mun=2;
		break;
		case "3":
			Mun=3;
		break;
		case "4":
			Mun=2;
		break;
		case "5":
			Mun=30;	
		break;
		case "6":
			Mun=2;
		break;
		case "7":
			Mun=101;
		break;
		case "8":
			Mun=19;
		break;
		case "9":
			Mun=15;
		break;
		case "10":
			Mun=5;
		break;
		case "11":
			Mun=15;
		break;
		case "12":
			Mun=29;
		break;
		case "13":
			Mun=48;
		break;
		case "14":
			Mun=39;
		break;
		case "15":
			Mun=106;
		break;
		case "16":
			Mun=53;
		break;
		case "17":
			Mun=7;
		break;
		case "18":
			Mun=17;
		break;
		case "19":
			Mun=39;
		break;
		case "20":
			Mun=67;
		break;
		case "21":
			Mun=114;
		break;
		case "22":
			Mun=14;
		break;
		case "23":
			Mun=4;
		break;
		case "24":
			Mun=28;
		break;
		case "25":
			Mun=6;
		break;
		case "26":
			Mun=30;
		break;
		case "27":
			Mun=4;
		break;
		case "28":
			Mun=41;
		break;
		case "29":
			Mun=33;
		break;
		case "30":
			Mun=87;
		break;
		case "31":
			Mun=50;
		break;
		case "32":
			Mun=56;
		break;
		default:
			Mun=1;
	}
	//console.log("Municipio: "+Mun);
	document.getElementById("Municipio"+valor).value=Mun;
	document.getElementById("Localidad"+valor).value=1;
	

}

function ajaxEstados(divNombre, valor, tipo){
	//donde se mostraran los registros
	divContenido = document.getElementById(divNombre);
	escrituraEdoDiv='<select id="Estado'+valor+'" title="Lugar" name="Estado'+valor+'" onchange="cabecerasEstatales(value, '+valor+')">';
	escrituraEdoDiv=escrituraEdoDiv+'<option value="0">Sleleccionar</option>';
	escrituraEdoDiv=escrituraEdoDiv+'<option value="1">Aguascalientes</option>';
	escrituraEdoDiv=escrituraEdoDiv+'<option value="2">Baja California</option>';
	escrituraEdoDiv=escrituraEdoDiv+'<option value="3">Baja California Sur</option>';
	escrituraEdoDiv=escrituraEdoDiv+'<option value="4">Campeche</option>';
	escrituraEdoDiv=escrituraEdoDiv+'<option value="5">Coahuila de Zaragoza</option>';
	escrituraEdoDiv=escrituraEdoDiv+'<option value="6">Colima</option>';
	escrituraEdoDiv=escrituraEdoDiv+'<option value="7">Chiapas</option>';
	escrituraEdoDiv=escrituraEdoDiv+'<option value="8">Chihuahua</option>';
	escrituraEdoDiv=escrituraEdoDiv+'<option value="9">Mexico</option>';
	escrituraEdoDiv=escrituraEdoDiv+'<option value="10">Durango</option>';
	escrituraEdoDiv=escrituraEdoDiv+'<option value="11">Guanajuato</option>';
	escrituraEdoDiv=escrituraEdoDiv+'<option value="12">Guerrero</option>';
	escrituraEdoDiv=escrituraEdoDiv+'<option value="13">Hidalgo</option>';
	escrituraEdoDiv=escrituraEdoDiv+'<option value="13">Mexico</option>';
	escrituraEdoDiv=escrituraEdoDiv+'<option value="14">Jalisco</option>';
	escrituraEdoDiv=escrituraEdoDiv+'<option value="15">Mexico</option>';
	escrituraEdoDiv=escrituraEdoDiv+'<option value="16">Michoacan de Ocampo</option>';
	escrituraEdoDiv=escrituraEdoDiv+'<option value="17">Morelos</option>';
	escrituraEdoDiv=escrituraEdoDiv+'<option value="18">Nayarit</option>';
	escrituraEdoDiv=escrituraEdoDiv+'<option value="19">Nuevo Leon</option>';
	escrituraEdoDiv=escrituraEdoDiv+'<option value="20">Oaxaca</option>';
	escrituraEdoDiv=escrituraEdoDiv+'<option value="21">Puebla</option>';
	escrituraEdoDiv=escrituraEdoDiv+'<option value="22">Queretaro</option>';
	escrituraEdoDiv=escrituraEdoDiv+'<option value="23">Quintana Roo</option>';
	escrituraEdoDiv=escrituraEdoDiv+'<option value="24">San Luis Potosi</option>';
	escrituraEdoDiv=escrituraEdoDiv+'<option value="25">Sinaloa</option>';
	escrituraEdoDiv=escrituraEdoDiv+'<option value="26">Sonora</option>';
	escrituraEdoDiv=escrituraEdoDiv+'<option value="27">Tabasco</option>';
	escrituraEdoDiv=escrituraEdoDiv+'<option value="28">Tamaulipas</option>';
	escrituraEdoDiv=escrituraEdoDiv+'<option value="29">Tlaxcala</option>';
	escrituraEdoDiv=escrituraEdoDiv+'<option value="30">Veracruz de Ignacio de la Llave</option>';
	escrituraEdoDiv=escrituraEdoDiv+'<option value="31">Yucatan</option>';
	escrituraEdoDiv=escrituraEdoDiv+'<option value="32">Zacatecas</option>';
	escrituraEdoDiv=escrituraEdoDiv+'</select>';
	escrituraEdoDiv=escrituraEdoDiv+'<br>';
	escrituraEdoDiv=escrituraEdoDiv+'Otro:<input id="OtroLugar'+valor+'" name="OtroLugar'+valor+'" maxlength="500" type="text" title="Otro Lugar." value=""/>'; //maxlength=500 implica solo 8 lugares ya que el tamanio del campo es de 4000 Bytes
	escrituraEdoDiv=escrituraEdoDiv+'<input type="hidden" name="Municipio'+valor+'" id="Municipio'+valor+'" value="1">';
	escrituraEdoDiv=escrituraEdoDiv+'<input type="hidden" name="Localidad'+valor+'" id="Localidad'+valor+'" value="1">';

	borrado='<br/><a href="#lugares" onclick="borraLayer('+divNombre+');">Eliminar</a><br /><br />';
	
	if(tipo=='manual'){
		divContenido.innerHTML = escrituraEdoDiv+borrado;
	}else{
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
				divContenido.innerHTML = ajax.responseText+'<br/>Otro:<input id="OtroLugar'+valor+'" type="text" value="" maxlength="500" name="OtroLugar'+valor+'"></input>'+borrado //maxlength=500 implica solo 8 lugares ya que el tamanio del campo es de 4000 Bytes
			}
		}
	//como hacemos uso del metodo GET
	//colocamos null ya que enviamos 
	//el valor por la url ?pag=nropagina
		ajax.send(null);
	}
	
}

function escribeLayerLugar(cuenta, tipo){
	var capa  = document.createElement("div");
	id='lugares'+cuenta;
	capa.setAttribute("id", id);

	document.getElementById('lugar').appendChild(capa);
	cuenta++;
    document.getElementById('agregarLugar').innerHTML = '<a href="#lugar" onclick="agregarLugar(\'lugar\','+cuenta+');"> Agregar lugar</a>'+' <a href="#lugar" onclick="agregarLugarManual(\'lugar\','+cuenta+');"> Agregar lugar simplificado</a><br/><br/>';
    
    ajaxEstados(id, cuenta-1, tipo);
    
}

function agregarLugar(bloque, cuenta){
		numeroLugares=10;//maxlength=500 implica solo 8 lugares ya que el tamanio del campo es de 4000 Bytes		
		lugarForm=cuentaElementosBloque(bloque);
		if(lugarForm == numeroLugares){
			alert("Solo se pueden agregar "+numeroLugares+" Lugares");
		}else{
			escribeLayerLugar(cuenta, 'deResgistros');
		}
}

function agregarLugarManual(bloque, cuenta){
		numeroLugares=10;//maxlength=500 implica solo 8 lugares ya que el tamanio del campo es de 4000 Bytes
		lugarForm=cuentaElementosBloque(bloque);
		if(lugarForm == numeroLugares){
			alert("Solo se pueden agregar "+numeroLugares+" Lugares");
		}else{
			escribeLayerLugar(cuenta,'manual');
		}		
}

/***FIN FUNCIONES DE LUGAR **********/


