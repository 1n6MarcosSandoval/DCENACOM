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
	console.log(id)
	capa.setAttribute("id", id);
	
	document.getElementById('autor').appendChild(capa);
    
    capa.innerHTML = 'Nueva Capa '+id+' <br/> <a href="#autores" onclick="borraLayer('+id+');">Borrar</a>';
    
    cuenta++;
    document.getElementById('agregarAutor').innerHTML = '<?php echo "Hola mundo "; ?><a href="#autores" onclick="agregarAutor(\'autor\','+cuenta+');">Agregar Autor</a>';
    
}

function escribeLayerLink(cuenta){
	var capa  = document.createElement("div");
	id='links'+cuenta
	console.log(id)
	capa.setAttribute("id", id);

	document.getElementById('link').appendChild(capa);
    contenido='Link de noticia: <input id="'+id+'T" name="'+id+'T" maxlength="2000" size="50" type="text" value=""/>';
    capa.innerHTML = contenido+' <a href="#links" onclick="borraLayer('+id+');">Eliminar</a> '

	cuenta++;
    document.getElementById('agregarLink').innerHTML = '<a href="#link" onclick="agregarLink(\'link\','+cuenta+');">Agregar otro link</a>';
}

function borraLayer(node){
    node.parentNode.removeChild(node);	
}

/*FIN Funciones autores */


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
