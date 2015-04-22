function noNulo(campoId){
	document.getElementById(campoId).value=document.getElementById(campoId).value.trim();
	if(document.getElementById(campoId).value==""){
		alert('Se necesita un valor. En '+document.getElementById(campoId).title);
		document.getElementById(campoId).focus();
		return 1;
	}else{
		return 0;
	}
}


function noNumero(campoId){
	if(isNaN(document.getElementById(campoId).value)){
		alert('Se necesita un valor Entero. En '+document.getElementById(campoId).title);
		document.getElementById(campoId).focus();
		return 1;
	}else{
		return 0;		
	}
}

function noNegativo(campoId){
	if(document.getElementById(campoId).value<1){
		alert('Se necesita un valor mayor a cero. En '+document.getElementById(campoId).title);
		document.getElementById(campoId).focus();
		return 1;
	}else{
		return 0;
	}
}

function valida(){


	if(noNulo('BuscarRepo')){
		return 0;
	}else if(noNumero('BuscarRepo')){
		return 0;
	}else if(noNegativo('BuscarRepo')){
		return 0;
	}
	document.lista_reportes.submit();

}
