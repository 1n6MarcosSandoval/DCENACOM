
function valuesAnidados_nivel2(bloque, sufijo, idCampoForm){
	/* Esta función rescata los valores de nodos dentro de otro con identificador 'bloque'. 
	 * Los nodos hijos de 'bloque' deben de heredar en su identificador el mismo nombre mas un 'sufijo'.
	 * bloque->nodoHijo->nodoHijoSufijo 
	 * el parámeto idCampoForm es el id del campo del formulario donde se escribira la union, separados por ','
	 * de los elementos del bloque*/
	var linksUnion="";
	var lista=document.getElementById(bloque);
	for(i=0; i<lista.childNodes.length;i++){
		if(lista.childNodes[i].id != undefined){
  			//console.log(lista.childNodes[i].id)
  			//console.log(lista.childNodes[i].id.substring(0,5))
  			//console.log(lista.childNodes[i].id.substring(5))
  			//console.log('links'+lista.childNodes[i].id.substring(5)+sufijo)
	 		//console.log(document.getElementById('links'+lista.childNodes[i].id.substring(5)+sufijo).value)
	 		//console.log(document.getElementById(lista.childNodes[i].id.substring(0,5)+lista.childNodes[i].id.substring(5)+sufijo).value);
	 		if(document.getElementById(lista.childNodes[i].id.substring(0,5)+lista.childNodes[i].id.substring(5)+sufijo).value.trim()!=""){
	 			linksUnion=linksUnion+document.getElementById(lista.childNodes[i].id.substring(0,5)+lista.childNodes[i].id.substring(5)+sufijo).value.trim()+",";
	 		}
		}
	}
	//console.log(linksUnion);
	document.getElementById(idCampoForm).value=linksUnion;
	console.log(document.getElementById(idCampoForm).value);
}

/* Funcion Valida Fecha */
    // El Formato es dd/mm/aaaa  

    function validarFecha(campoId){
    	var fechaActual= new Date();   
    	//alert(fechaActual.getDate());
    	//alert(fechaActual.getFullYear());
    	
        var Fecha= new String(document.getElementById(campoId).value)   // Crea un string  
        // Cadena Anio  
        var Ano= new String(Fecha.substring(Fecha.lastIndexOf("/")+1,Fecha.length))  
        //alert(Ano);
        // Cadena Mes  
        var Mes= new String(Fecha.substring(Fecha.indexOf("/")+1,Fecha.lastIndexOf("/")))
        //alert(Mes);
        // Cadena Dia  
        var Dia= new String(Fecha.substring(0,Fecha.indexOf("/")))
        //alert(Dia);
      
        // Valido el anio. ESPECIAL: Primero revisa si es 31 de Dic o 1 de Enero para permitir dar de alta algun
        //evento del anio anterior
        month=parseFloat(fechaActual.getMonth())+1;
        day=fechaActual.getDate();
       if(month==12 && day==31 || month==1 && day==1){
        	
        if (isNaN(Ano) || Ano.length<4 || parseFloat(Ano)<fechaActual.getFullYear()-1 || parseFloat(Ano)>fechaActual.getFullYear()){  
			alert('Error en fecha posterior, mayor o formato incorrecto.\n'+document.getElementById(campoId).title);			
			document.getElementById(campoId).focus();
            return false  
        }
        
        }else{
       
        if (isNaN(Ano) || Ano.length<4 || parseFloat(Ano)!=fechaActual.getFullYear()){  
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
          
      //para que envie los datos, quitar las  2 lineas siguientes  
      //alert("Fecha correcta.")  
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

function valida(){
	valuesAnidados_nivel2('link','T','seguimientoLinks');	
	/*
	//No nulos
	
	if(noNulo('seguimientoEfectoAdverso')){
		return 0;
	}
	if(noNulo('seguimientoAreasAfectadas')){
		return 0;
	}
	if(noNulo('seguimientoObservaciones')){
		return 0;
	}
	if(noNulo('seguimientoAutores')){
		return 0;
	}

	//FIN No nulos
	
	//No cero

	if(noNulo('seguimientoEfectoAdverso')){
		return 0;
	}
	if(noNulo('seguimientoAreasAfectadas')){
		return 0;
	}
	if(noNulo('seguimientoObservaciones')){
		return 0;
	}
	if(noNulo('seguimientoDanosMateriales')){
		return 0;
	}
	
	//FIN No nulos


	//No Entero
	
	if(noNumero('seguimientoPersonasAfectadas')){		
		return 0;
	}else if(noNegativo('seguimientoPersonasAfectadas')){
		return 0;
	}
	
	
	if(noNumero('seguimientoMuertos')){
		return 0;
	}else if(noNegativo('seguimientoMuertos')){
		return 0;
	}
	
	if(noNumero('seguimientoLesionados')){
		return 0;
	}else if(noNegativo('seguimientoLesionados')){
		return 0;
	}
	
	if(noNumero('seguimientoEvacuados')){
		return 0;
	}else if(noNegativo('seguimientoEvacuados')){
		return 0;
	}
	
	if(noNumero('seguimientoDesaparecidos')){
		return 0;
	}else if(noNegativo('seguimientoDesaparecidos')){
		return 0;
	}
	
	//FIN No Entero
	
	//No Cero	
	
	if(noCero('seguimientoEstado')){
		return 0;
	}
	
	if(noCero('seguimientoMunicipio')){
		return 0;
	}

	if(noCero('seguimientoClasificacionFenomeno')){
		return 0;
	}
	
	if(noCero('seguimientoTipoFenomeno')){
		return 0;
	}
	
	if(noCero('seguimientoRespuestaInstitucional')){
		return 0;
	}
	
	//FIN No Cero
	
	//Validacion de formato de fecha
	if(!validarFecha('seguimientoFechaReporta')){
		return 0;
	}
	if(!validarFecha('seguimientoFechaFenomeno')){
		return 0;
	}
	//FIN Validacion de formato de fecha
	
	//Validacion de formato de Hora
	if(!validarHora('seguimientoHoraQueReportaval')){
		return 0;
	}
	if(!validarHora('seguimientoHoraseguimientoFenomenoval')){
		return 0;
	}
	//FIN Validacion de formato de Hora
	
	*/
	document.fseguimiento.submit();

}
