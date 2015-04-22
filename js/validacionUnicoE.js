function sustituyeComaLink(campoId){
	link=document.getElementById(campoId).value;
	nuevaURL = link.replace(',', '%2C');
	document.getElementById(campoId).value=nuevaURL;
}


function valuesAnidados_nivel2(bloque, sufijo, idCampoForm1, idCampoForm2){
	/* Esta función rescata los valores de nodos dentro de otro con identificador 'bloque'. 
	 * Los nodos hijos de 'bloque' deben de heredar en su identificador el mismo nombre mas un 'sufijo'.
	 * bloque->nodoHijo->nodoHijoSufijo 
	 * el parámeto idCampoForm es el id del campo del formulario donde se escribira la union, separados por ','
	 * de los elementos del bloque*/
	var linksUnion="";
	var titulolinksUnion="";
	var lista=document.getElementById(bloque);
	for(i=0; i<lista.childNodes.length;i++){
		if(lista.childNodes[i].id != undefined){

	 		campoId=lista.childNodes[i].id.substring(0,5)+lista.childNodes[i].id.substring(5)+sufijo;
	 		titulocampoId="titulo"+campoId;
	 		console.log(campoId)
	 		console.log(titulocampoId)
	 		console.log(document.getElementById(titulocampoId).value)
	 		//Sustituye la coma en el Link:	 		
	 		sustituyeComaLink(campoId);
	 		if(document.getElementById(campoId).value.trim()!="" && document.getElementById(titulocampoId).value.trim()!=""){
	 			linksUnion=linksUnion+document.getElementById(campoId).value.trim()+",";
	 			titulolinksUnion=titulolinksUnion+document.getElementById(titulocampoId).value.trim()+",";
	 		}else{
	 			if(document.getElementById(campoId).value.trim()!="" && document.getElementById(titulocampoId).value.trim()==""){
	 				var textoAlerta='¡Link escrito pero sin Título!\nNo se admiten campos de Links mutuamente vacios\n';
	 				textoAlerta=utf8_decode(textoAlerta);
	 				alert(document.getElementById(titulocampoId).title+": "+textoAlerta);
	 				document.getElementById(titulocampoId).focus();
	 				return 0;
	 			}else if(document.getElementById(campoId).value.trim()=="" && document.getElementById(titulocampoId).value.trim()!=""){
	 				var textoAlerta='¡Título escrito pero son Link!\nNo se admiten campos de Links mutuamente vacios\n';
	 				textoAlerta=utf8_decode(textoAlerta);
	 				alert(document.getElementById(campoId).title+": "+textoAlerta);
	 				document.getElementById(campoId).focus();
	 				return 0;
	 			}
	 		}
		}
	}
	document.getElementById(idCampoForm1).value=linksUnion;
	document.getElementById(idCampoForm2).value=titulolinksUnion;
	console.log(document.getElementById(idCampoForm1).value);
	console.log(document.getElementById(idCampoForm2).value);
	return 1;
}

/* Funcion Valida Fecha */
    // El Formato es dd/mm/aaaa
    function validarFecha(campoId){
    	var fechaActual= new Date();   
    	
        var Fecha= new String(document.getElementById(campoId).value)   // Crea un string  
        // Cadena Anio  
        var Ano= new String(Fecha.substring(Fecha.lastIndexOf("/")+1,Fecha.length))  
        // Cadena Mes  
        var Mes= new String(Fecha.substring(Fecha.indexOf("/")+1,Fecha.lastIndexOf("/")))
        // Cadena Dia  
        var Dia= new String(Fecha.substring(0,Fecha.indexOf("/")))
        //alert(Dia);

        // Valido el anio. ESPECIAL: Primero revisa si es 31 de Dic o 1 de Enero para permitir dar de alta algun
        //evento del anio anterior
        month=parseFloat(fechaActual.getMonth())+1;
        day=fechaActual.getDate();
       if(month==12 && day==31 || month==1 && day==1){
/*
        if (isNaN(Ano) || Ano.length<4 || parseFloat(Ano)<fechaActual.getFullYear()-1 || parseFloat(Ano)>fechaActual.getFullYear()){  
			alert('Error en fecha posterior, mayor o formato incorrecto.\n'+document.getElementById(campoId).title);			
			document.getElementById(campoId).focus();
            return false
        }
*/

        if (isNaN(Ano) || Ano.length<4){  
			alert('Error en fecha posterior, mayor o formato incorrecto.\n'+document.getElementById(campoId).title);			
			document.getElementById(campoId).focus();
            return false
        }

        }else{
/*
        if (isNaN(Ano) || Ano.length<4 || parseFloat(Ano)!=fechaActual.getFullYear()){  
			alert('Error en fecha posterior, mayor o formato incorrecto.\n'+document.getElementById(campoId).title);			
			document.getElementById(campoId).focus();
            return false  
        }
*/
        if (isNaN(Ano) || Ano.length<4){  
			alert('Error en fecha posterior, mayor o formato incorrecto.\n'+document.getElementById(campoId).title);			
			document.getElementById(campoId).focus();
            return false  
        }

        }
        // Valido el Mes  
        if (isNaN(Mes) || parseFloat(Mes)<1 || parseFloat(Mes)>12){  
			alert('Error en formato de fecha. '+document.getElementById(campoId).title);			
			document.getElementById(campoId).focus();  
            return false  
        }
        // Valido el Dia  
        if (isNaN(Dia) || parseInt(Dia, 10)<1 || parseInt(Dia, 10)>31){  
			alert('Error en formato de fecha. '+document.getElementById(campoId).title);
			document.getElementById(campoId).focus();  
            return false  
        }
        if (Mes==4 || Mes==6 || Mes==9 || Mes==11 || Mes==2) {  
            if (Mes==2 && Dia > 28 || Dia>30) {  
			alert('Error en formato de fecha. '+document.getElementById(campoId).title);
			document.getElementById(campoId).focus();
                return false  
            }
        }
          
      return true    
    }  
/* FIN Funcion Valida Fecha */


/* Funcion Valida Hora */
function validarHora(campoId){
	var Hora= document.getElementById(campoId).value;  
    var hora= parseFloat(Hora.substring(0,Hora.indexOf(":")));
    var minuto= parseFloat(Hora.substring(Hora.lastIndexOf(":")+1,Hora.length));
   
    if(hora<0 || hora>23 || minuto<0 || minuto>59){
		alert('Error en formtato de fecha.\n'+document.getElementById(campoId).title);
		campoHora=campoId.substring(0,campoId.length-3);
		document.getElementById(campoHora).focus();
		return false;
    }
    return true;
}
/* FIN Funcion Valida Fecha */


function noNulo(campoId){
	document.getElementById(campoId).value=document.getElementById(campoId).value.trim();
	if(document.getElementById(campoId).value==""){
		alert('Se necesita un valor. '+document.getElementById(campoId).title);
		document.getElementById(campoId).focus();
		return 1;
	}else{
		return 0;
	}
}

function noNuloAutor(campoId){
	if(document.getElementById(campoId).value==""){
		alert('Se necesita un valor. '+document.getElementById(campoId).title);
		document.getElementById(campoId).focus();
		return 1;
	}else{
		return 0;
	}
}

function noNumero(campoId){
	if(isNaN(document.getElementById(campoId).value)){
		alert('Se necesita un valor Entero. '+document.getElementById(campoId).title);
		document.getElementById(campoId).focus();
		return 1;
	}else{
		return 0;		
	}
}

function noNegativo(campoId){
	if(document.getElementById(campoId).value<0){
		alert('Se necesita un valor mayor o igual a cero. '+document.getElementById(campoId).title);
		document.getElementById(campoId).focus();
		return 1;
	}else{
		return 0;
	}
}


function noCero(campoId){
	if(document.getElementById(campoId).value==0){
		alert('Se necesita seleccionar un valor. '+document.getElementById(campoId).title);
		document.getElementById(campoId).focus();
		return 1;
	}else{
		return 0;
	}
}

function autoresLista(){
	var arrayAutores = new Array();
	var cadenaAutores = new String("");
	j=0;
	for(i=0;i<funico.unicoAutoresC.options.length;i++){
    	if(funico.unicoAutoresC[i].selected==true){
        	arrayAutores[j]=funico.unicoAutoresC[i].value;
        	j++;
    	}    
	}
	cadenaAutores=arrayAutores.join();	
	document.getElementById('unicoAutores').value=cadenaAutores;	
}

function valida(){	
	//Construye cadena de links y valida cada uno
	validaLinks=valuesAnidados_nivel2('link','T','unicoLinks', 'unicoTituloLinks');
	if(!validaLinks){
		return 0;		
	}

	//No nulos
	if(document.getElementById('unicoAutoresC')){
		if(noNuloAutor('unicoAutoresC')){
			return 0;
		}else{
			//Construye la lista de autores en el campo alcanceAutores
			autoresLista();
			//Recupera el turno de los autores
			document.getElementById('unicoAutoresTurno').value=document.getElementById('unicoAutoresTurnoC').value;
		}	
	}
	if(noNulo('unicoAutores')){
			return 0;
		}	
	if(noNulo('unicoEfectoAdverso')){
		return 0;
	}
	if(noNulo('unicoObservaciones')){
		return 0;
	}
	if(noNulo('unicoDanosMaterialesEvento')){
		return 0;
	}	
	//FIN No nulos


	//No Entero	
	if(noNumero('unicoMuertos')){
		return 0;
	}else if(noNegativo('unicoMuertos')){
		return 0;
	}
	
	if(noNumero('unicoLesionados')){
		return 0;
	}else if(noNegativo('unicoLesionados')){
		return 0;
	}
	if(noNumero('unicoEvacuados')){
		return 0;
	}else if(noNegativo('unicoEvacuados')){
		return 0;
	}
	
	if(noNumero('unicoDesaparecidos')){
		return 0;
	}else if(noNegativo('unicoDesaparecidos')){
		return 0;
	}
	
	//FIN No Entero
	
	//No Cero	
	
	if(noCero('unicoEstado')){
		return 0;
	}
	
	if(noCero('unicoMunicipio')){
		return 0;
	}

	if(noCero('unicoClasificacionFenomeno')){
		return 0;
	}
	
	if(noCero('unicoTipoFenomeno')){
		return 0;
	}
	
	if(noCero('unicoRespuestaInstitucional')){
		return 0;
	}
	if(noCero('unicoOrganismoReporta')){
		return 0;
	}
	
	//FIN No Cero
	
	//Validacion de formato de fecha
	if(!validarFecha('unicoFechaReporta')){
		return 0;
	}
	if(!validarFecha('unicoFechaFenomeno')){
		return 0;
	}
	//FIN Validacion de formato de fecha
	
	//Validacion de formato de Hora
	if(!validarHora('unicoHoraQueReportaval')){
		return 0;
	}
	if(!validarHora('unicoHoraInicialFenomenoval')){
		return 0;
	}
	//FIN Validacion de formato de Hora
	
	document.funico.submit();

}



