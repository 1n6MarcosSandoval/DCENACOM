function SeleccionandoCombo_3E(combo1, combo2, combo3, comboC){
	//console.log("RAYOS")
	console.log(combo1)
	console.log(combo2)
	console.log(combo3)
	combo2 = document.getElementById(combo2); //con jquery: $("#"+combo2)[0];
	LimpiarCombo(combo2);
	combo3 = document.getElementById(combo3);
	LimpiarCombo(combo3);
	console.log(document.getElementById(combo1).value);
	//combo1.value=combo1.options[combo1.selectedIndex].value;
	if(document.getElementById(combo1).value != ""){
		combo2.disabled = true;
		combo3.disabled = true;
		$.ajax({
			type: 'get',
			dataType: 'json',
			url: 'ajax.php',
			data: {valor: document.getElementById(combo1).value, comboC: comboC},
			success: function(json){
				LlenarCombo(json, combo2);
				combo1.disabled = false;
				combo2.disabled = false;
			}
		});		
	}
}


function SeleccionandoComboLocalidadE(combo1, combo2, comboC){
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
	
}


function eliminarElemento(id){
	objeto = document.getElementById(id);	
	if (!objeto){
		alert("El elemento selecionado no existe");
	} else {
		padre = objeto.parentNode;
		padre.removeChild(objeto);
	}
}

/*-----------------------------------------------------------------------------------------------*/
function edita(idDiv,estado,tiporeporte,datos,datosImprime){
	
	//console.log(datosImprime);
	divContenido = document.getElementById(idDiv);
	switch(idDiv){
		case 'lugar':
  			if(estado){
  				escribeNuevo= "Lugar: (<a href=\"#lugar\" onclick=\"edita('lugar',0,'"+tiporeporte+"','"+datos+"','"+datosImprime+"');\">Dejar los datos originales</a>)<br/>"+escribeComboLugar(tiporeporte);
  				divContenido.innerHTML = escribeNuevo;
  			}else{
  				escribeAnterior=escribeDatosLugar(tiporeporte,datos,datosImprime);
  				divContenido.innerHTML = "Lugar: (<a href=\"#lugar\" onclick=\"edita('lugar',1,'"+tiporeporte+"','"+datos+"','"+datosImprime+"');\">Reingresar lugar</a>)<br/>"+escribeAnterior;
  			}  				
  		break;
		case 'fenomeno':
  			if(estado){
  				escribeNuevo= "Clasificación y tipo de Fenómeno: (<a href=\"#fenomeno\" onclick=\"edita('fenomeno',0,'"+tiporeporte+"','"+datos+"','"+datosImprime+"');\">Dejar los datos originales</a>)<br/>"+escribeComboFenomeno(tiporeporte);
  				divContenido.innerHTML = escribeNuevo;
  			}else{
  				escribeAnterior=escribeDatosFenomeno(tiporeporte,datos,datosImprime);
  				divContenido.innerHTML = "Lugar: (<a href=\"#fenomeno\" onclick=\"edita('fenomeno',1,'"+tiporeporte+"','"+datos+"','"+datosImprime+"');\">Reingresar fen&oacute;meno</a>)<br/>"+escribeAnterior;
  			}  				
  		break;
		case 'autores':
  			if(estado){
  				escribeNuevo= "Lugar: (<a href=\"#autores\" onclick=\"edita('autores',0,'"+tiporeporte+"','"+datos+"','"+datosImprime+"');\">Dejar los autores originales</a>)<br/>"+escribeComboAutores(tiporeporte);
  				divContenido.innerHTML = escribeNuevo;
  			}else{
  				console.log(datosImprime);			
  				escribeAnterior=escribeDatosAutores(tiporeporte,datos,datosImprime);
  				divContenido.innerHTML = "Lugar: (<a href=\"#autores\" onclick=\"edita('autores',1,'"+tiporeporte+"','"+datos+"','"+datosImprime+"');\">Reingresar autores</a>)<br/>"+escribeAnterior;
  			}  		
  		break;
		default:
  			console.log("Sin cambios...");
	}
}



function escribeDatosAutores(tiporeporte,datos,datosImprime){
	//datosImprime=datosImprime.split(",");
	//datos=datos.split(",");
	texto="Autor(es): "+datosImprime;
	return texto;
}

function escribeComboAutores(tiporeporte){
	texto='<select id="'+tiporeporte+'AutoresTurnoC" onchange="SeleccionandoComboM(this, \''+tiporeporte+'AutoresC\', \'autores\');" title="Autores" name="'+tiporeporte+'AutoresTurnoC">';
	texto=texto+'<option value="0">Seleccionar turno</option>';
	texto=texto+'<option value="1">MATUTINO</option>';
	texto=texto+'<option value="2">VESPERTINO</option>';
	texto=texto+'<option value="3">NOCTURNO</option>';
	texto=texto+'<option value="4">FIN DE SEMANA Y DIAS FESTIVOS</option>';
	texto=texto+'<option value="5">MANDOS MEDIOS</option>';
	texto=texto+'</select>';
	texto=texto+'<br>';
	texto=texto+'<select id="'+tiporeporte+'AutoresC" size="5" multiple="" title="Autores" name="'+tiporeporte+'AutoresC[]">';
	texto=texto+'<option value="0">Primero seleccione el turno...</option>';
	texto=texto+'</select>';	
	return texto;
}

function escribeComboFenomeno(tiporeporte){
	cadena1='<select id="'+tiporeporte+'ClasificacionFenomeno" onchange="SeleccionandoCombo(this, \''+tiporeporte+'TipoFenomeno\', \'fenomeno\');" title="Clasificación y tipo de fénomeno" name="'+tiporeporte+'ClasificacionFenomeno">';
	cadena2='<option value="0">Seleccionar</option>';
	cadena3='<option value="GEO">Geológico</option>';
	cadena4='<option value="HIDRO">Hidrometeorológico</option>';
	cadena5='<option value="QUIM">Químico</option>';
	cadena6='<option value="SAN">Sanitario</option>';
	cadena7='<option value="SOCIO">Sociorganizativo</option>';
	cadena8='</select>';
	cadena9='<select id="'+tiporeporte+'TipoFenomeno" title="Clasificación y tipo de fénomeno" name="'+tiporeporte+'TipoFenomeno"></select>';

	cadena=cadena1+cadena2+cadena3+cadena4+cadena5+cadena6+cadena7+cadena8+cadena9;
	return cadena;	
}



function escribeDatosFenomeno(tiporeporte,datos,datosImprime){
	datosImprime=datosImprime.split(",");
	datos=datos.split(",");
	texto='Clasificaci&oacute;n del fenómeno: '+datosImprime[0];
	texto=texto+'<input id="'+tiporeporte+'ClasificacionFenomeno" type="hidden" value="'+datos[0]+'" name="'+tiporeporte+'ClasificacionFenomeno"><br>';
	texto=texto+'Tipo de fen&oacute;meno: '+datosImprime[1];
	texto=texto+'<input id="'+tiporeporte+'TipoFenomeno" type="hidden" value="'+datos[1]+'" name="'+tiporeporte+'TipoFenomeno">';
	
	return texto;
}

function escribeDatosLugar(tiporeporte,datos,datosImprime){
	eliminarElemento(tiporeporte+'Estado');
	eliminarElemento(tiporeporte+'Municipio');
	eliminarElemento(tiporeporte+'Localidad');
	eliminarElemento(tiporeporte+'OtroLugar');
	lugares=datosImprime.split(",");
	lugaresId=datos.split(",");
  	console.log(lugares);	
  	  	
	texto="Estado: "+lugares[0];
	campoEstado='<input type="hidden" name="'+tiporeporte+'Estado" id="'+tiporeporte+'Estado" value="'+lugaresId[0]+'">';
	texto=texto+campoEstado;
	
	texto=texto+"<br/>Municipio: "+lugares[1];
	campoMunicipio='<input type="hidden" name="'+tiporeporte+'Municipio" id="'+tiporeporte+'Municipio" value="'+lugaresId[1]+'">';
	texto=texto+campoMunicipio;
	
	if(lugares[2]!="-"){
		texto=texto+"<br/>Localidad: "+lugares[2];
		campoLocalidad='<input type="hidden" name="'+tiporeporte+'Localidad" id="'+tiporeporte+'Estado" value="'+lugaresId[2]+'">';
		texto=texto+campoLocalidad;
	}else{
		campoLocalidad='<input type="hidden" name="'+tiporeporte+'Localidad" id="'+tiporeporte+'Localidad" value="0">';
		texto=texto+campoLocalidad;
	}
		
	campoOtroLugar='<input type="hidden" name="'+tiporeporte+'OtroLugar" id="'+tiporeporte+'OtroLugar" value="'+lugares[3]+'">';
	//texto=texto+"<br/>Otro lugar: "+lugares[3];
	texto=texto+campoOtroLugar;
	
	return texto;
}


function escribeComboLugar(tiporeporte){
cadena1='<select id="'+tiporeporte+'Estado" onchange="SeleccionandoCombo_3E(\''+tiporeporte+'Estado\', \''+tiporeporte+'Municipio\', \''+tiporeporte+'Localidad\', \'municipio\');" title="Lugar" name="'+tiporeporte+'Estado">';
//cadena1='<select id="'+tiporeporte+'EstadoC" onchange="SeleccionandoCombo_3(this, \''+tiporeporte+'MunicipioC\', \''+tiporeporte+'LocalidadC\', \'municipio\');" title="Lugar" name="'+tiporeporte+'EstadoC">';
//cadena1='<select id="'+tiporeporte+'Estado" onchange="SeleccionandoCombo_3(value, \''+tiporeporte+'Municipio\', \''+tiporeporte+'Localidad\', \'municipio\');" title="Lugar" name="'+tiporeporte+'Estado">';
cadena2='<option value="0">Seleccionar</option>';
cadena3='<option value="1">Aguascalientes</option>';
cadena4='<option value="2">Baja California</option>';
cadena5='<option value="3">Baja California Sur</option>';
cadena6='<option value="4">Campeche</option>';
cadena7='<option value="5">Coahuila de Zaragoza</option>';
cadena8='<option value="6">Colima</option>';
cadena9='<option value="7">Chiapas</option>';
cadena10='<option value="8">Chihuahua</option>';
cadena11='<option value="9">Mexico</option>';
cadena12='<option value="10">Durango</option>';
cadena13='<option value="11">Guanajuato</option>';
cadena14='<option value="12">Guerrero</option>';
cadena15='<option value="13">Hidalgo</option>';
cadena16='<option value="13">Mexico</option>';
cadena17='<option value="14">Jalisco</option>';
cadena18='<option value="15">Mexico</option>';
cadena19='<option value="16">Michoacan de Ocampo</option>';
cadena20='<option value="17">Morelos</option>';
cadena21='<option value="18">Nayarit</option>';
cadena22='<option value="19">Nuevo Leon</option>';
cadena23='<option value="20">Oaxaca</option>';
cadena24='<option value="21">Puebla</option>';
cadena25='<option value="22">Queretaro</option>';
cadena26='<option value="23">Quintana Roo</option>';
cadena27='<option value="24">San Luis Potosi</option>';
cadena28='<option value="25">Sinaloa</option>';
cadena29='<option value="26">Sonora</option>';
cadena30='<option value="27">Tabasco</option>';
cadena31='<option value="28">Tamaulipas</option>';
cadena32='<option value="29">Tlaxcala</option>';
cadena33='<option value="30">Veracruz de Ignacio de la Llave</option>';
cadena34='<option value="31">Yucatan</option>';
cadena35='<option value="32">Zacatecas</option>';
cadena36='</select><br>';
cadena37='<select id="'+tiporeporte+'Municipio" onchange="SeleccionandoComboLocalidadE(\''+tiporeporte+'Municipio\', \''+tiporeporte+'Localidad\', \'localidad\');" name="'+tiporeporte+'Municipio"></select><br>';
cadena38='<select id="'+tiporeporte+'Localidad" name="'+tiporeporte+'Localidad"></select><br>';
cadena39='Otro: <input id="'+tiporeporte+'OtroLugar" type="text" value="" maxlength="1000" name="'+tiporeporte+'OtroLugar">';
cadena=cadena1+cadena2+cadena3+cadena4+cadena5+cadena6+cadena7+cadena8+cadena9+cadena10+cadena11+cadena12+cadena13+cadena14+cadena15+cadena16+cadena17+cadena18+cadena19+cadena20+cadena21+cadena22+cadena23+cadena24+cadena25+cadena26+cadena27+cadena28+cadena29+cadena30+cadena31+cadena32+cadena33+cadena34+cadena35+cadena36+cadena37+cadena38+cadena39;
return cadena;
}