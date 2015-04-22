//Funcion que cambia el formulario
//function cambiaForm(){
function cambiaForm(tipoForm){
	//alert(id.value)
	document.getElementById('cambio').innerHTML = 'Seleccionaste: '+tipoForm;
}

//Funcion que aumenta una capa
function writeThere(cuenta){
	var capa  = document.createElement("div");
	id="capa"+cuenta
	console.log(id)
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
	//alert(combo.length);
	//alert(combo.name);
	while(combo.length > 0){
		combo.remove(combo.length-1);
	}
}
function LlenarCombo(json, combo){
	combo.options[0] = new Option('Seleccionar', '');
	for(var i=0;i<json.length;i++){
		combo.options[combo.length] = new Option(json[i].data, json[i].id);
	}
}

function LlenarComboM(json, combo){
	//combo.options[0] = new Option('Seleccionar', '');
	for(var i=0;i<json.length;i++){
		combo.options[combo.length] = new Option(json[i].data, json[i].id);
	}
}

function SeleccionandoCombo_3(combo1, combo2, combo3, comboC){
	combo2 = document.getElementById(combo2); //con jquery: $("#"+combo2)[0];
	LimpiarCombo(combo2);
	combo3 = document.getElementById(combo3);
	LimpiarCombo(combo3);
	if(combo1.options[combo1.selectedIndex].value != ""){
		//alert('http://127.0.0.1/cenacom/combos01/ajax.php?valor='+combo1.options[combo1.selectedIndex].value+'comboC='+comboC)
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
	//alert('http://127.0.0.1/cenacom/combos01/ajax.php?valor='+combo2+'comboC='+comboC)
	combo2 = document.getElementById(combo2); //con jquery: $("#"+combo2)[0];
	LimpiarCombo(combo2);
	if(combo1.options[combo1.selectedIndex].value != ""){
		//alert('http://127.0.0.1/cenacom/combos01/ajax.php?valor='+combo1.options[combo1.selectedIndex].value+'comboC='+comboC)
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


function SeleccionandoComboM(combo1, combo2, comboC){
	//alert('http://127.0.0.1/cenacom/combos01/ajax.php?valor='+combo2+'comboC='+comboC)
	combo2 = document.getElementById(combo2); //con jquery: $("#"+combo2)[0];
	LimpiarCombo(combo2);
	if(combo1.options[combo1.selectedIndex].value != ""){
		//alert('http://127.0.0.1/cenacom/combos01/ajax.php?valor='+combo1.options[combo1.selectedIndex].value+'comboC='+comboC)
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
  //var lista=document.getElementById('bloque');
  var lista=document.getElementById(bloque);
  nodeCount=0
  for(i=0; i<lista.childNodes.length;i++){
  	if(lista.childNodes[i].id != undefined){
  		console.log(lista.childNodes[i].id)
  		nodeCount++
  	}
  }
  //alert(nodeCount)
  return nodeCount
}


function escribeLayerAutor(cuenta){
	var capa  = document.createElement("div");
	id="capa"+cuenta
	//console.log(id)
	capa.setAttribute("id", id);
	
	document.getElementById('autor').appendChild(capa);
    
    capa.innerHTML = 'Nueva Capa '+id+' <br/> <a href="#autores" onclick="borraLayer('+id+');">Borrar</a>';
    
    cuenta++;
    document.getElementById('agregarAutor').innerHTML = '<?php echo "Hola mundo "; ?><a href="#autores" onclick="agregarAutor(\'autor\','+cuenta+');">Agregar Autor</a>';
    
}

function escribeLayerLink(cuenta){
	var capa  = document.createElement("div");
	id='links'+cuenta
	//console.log(id)
	capa.setAttribute("id", id);

	document.getElementById('link').appendChild(capa);
	contenidoT = 'T&iacute;tulo de noticia:<input id="titulo'+id+'T" name="titulo'+id+'T" title="T&iacute;tulo de link" size="49" maxlength="990" type="text" value=""/><br/>';
    contenidoL='Link de noticia: <input id="'+id+'T" name="'+id+'T" maxlength="980" size="50" type="text" value="" title="URL del link"/>';
    contenido=contenidoT+contenidoL;
    capa.innerHTML = '<br/>'+contenido+' <a href="#links" onclick="borraLayer('+id+');">Eliminar</a> '

	cuenta++;
    document.getElementById('agregarLink').innerHTML = '<a href="#link" onclick="agregarLink(\'link\','+cuenta+');">Agregar otro link</a>';
}

function borraLayer(node){
    node.parentNode.removeChild(node);	
}

/*FIN Funciones autores */

/* Funcion checa y alerta el numero de caracteres que se estan ingresando en un textarea */
function alertNumCaracTextArea(campo, numeroMaxCaracteres){
	//alert(campo);
	//alert("pos")
	if(document.getElementById(campo).value.length >= numeroMaxCaracteres)
	{
			estilo='style="font-family:Arial; font-size:12px; border: 2px solid red; width: 416px; padding-left:6px; color:#FFFFFF; background-color:#FF0000;"';
			document.getElementById(campo+'Leyenda').innerHTML =document.getElementById(campo).title+':<div '+estilo+' >'+'Excedido m&aacute;ximo de caracter'+'</div>';
			document.getElementById('boton').innerHTML='<div '+estilo+' >'+'Existe uno o varios problemas de exceso de caracteres en el formulario</div> <input type="button"  value="Registrar" disabled="true" />';
			return false; 
	}else{
			document.getElementById(campo+'Leyenda').innerHTML = document.getElementById(campo).title+":";
			document.getElementById('boton').innerHTML='<input type="button"  value="Registrar" onclick="valida()" />';
			return true; 		
	}
}

/* FIN Funcion checa y alerta el numero de caracteres que se estan ingresando en un textarea */


function escribeLinkAlReporte(CR,tipoReporte){
	console.log(CR);
	console.log(tipoReporte);
	if(CR>0){
		switch(tipoReporte)
		{
			case "SEGUIMIENTO":
				//document.getElementById('SEGUIMIENTO').innerHTML='<a href="formSeguimiento.php?cr='+CR+'">Generar reporte</a>';
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
			default:
				aler("Sin parámetro de tipo de reporte.")
		}
		
	}else{
		document.getElementById(tipoReporte).innerHTML='';
		console.log(tipoReporte);
	}	
}



function escribePostAlReporte(CR,paginaReporte,tipoReporte){
	var nameCampo='fnuevo'+tipoReporte;
	var formCR='<form method="post" id="'+nameCampo+'" name="'+nameCampo+'" action="'+paginaReporte+'">'+'<input type="hidden" name="crReal" id="crReal" value="'+CR+'">'+'</form>';
	document.getElementById('postForm'+tipoReporte).innerHTML=formCR;



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
	//indicamos el archivo que realizar? el proceso de paginar
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
	ajax.send(null)
}
//FIN Usado por los paginadores


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

