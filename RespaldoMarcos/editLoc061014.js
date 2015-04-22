function CambioLugar(contador)
{
	id='Estado'+contador;
	//console.log("Id: %s",id); //console.log("contador: %s",contador);
	div1 = '<div id = "'+id+'">';
	si = '<select style="width: 100px" name="seleccion" idEstado='+id+'>';
	s0='<option idEdoSel="0" onclick="PasarEdo(0);" value="0">Seleccionar</option>';
	s1='<option idEdoSel="1" onclick="PasarEdo(1);" value="1">Aguascalientes</option>';
	s2='<option idEdoSel="2" onclick="PasarEdo(2);" value="2">Baja California</option>';	
	s3='<option idEdoSel="3" onclick="PasarEdo(3);" value="3">Baja California Sur</option>';
	s4='<option idEdoSel="4" onclick="PasarEdo(4);" value="4">Campeche</option>';
	s5='<option idEdoSel="5" onclick="PasarEdo(5);" value="5">Coahuila de Zaragoza</option>';
	s6='<option idEdoSel="6" onclick="PasarEdo(6);" value="6">Colima</option>';
	s7='<option idEdoSel="7" onclick="PasarEdo(7);" value="7">Chiapas</option>';
	s8='<option idEdoSel="8" onclick="PasarEdo(8);" value="8">Chihuahua</option>';
	s9='<option idEdoSel="9" onclick="PasarEdo(9);" value="9">Distrito Federal</option>';
	s10='<option idEdoSel="10" onclick="PasarEdo(10);" value="10">Durango</option>';
	s11='<option idEdoSel="11" onclick="PasarEdo(11);" value="11">Guanajuato</option>';
	s12='<option idEdoSel="12" onclick="PasarEdo(12);" value="12">Guerrero</option>';
	s13='<option idEdoSel="13" onclick="PasarEdo(13);" value="13">Hidalgo</option>';
	s14='<option idEdoSel="14" onclick="PasarEdo(14);" value="14">Jalisco</option>';
	s15='<option idEdoSel="15" onclick="PasarEdo(15);" value="15">México</option>';
	s16='<option idEdoSel="16" onclick="PasarEdo(16);" value="16">Michoacán de Ocampo</option>';
	s17='<option idEdoSel="17" onclick="PasarEdo(17);" value="17">Morelos</option>';
	s18='<option idEdoSel="18" onclick="PasarEdo(18);" value="18">Nayarit</option>';
	s19='<option idEdoSel="19" onclick="PasarEdo(19);" value="19">Nuevo León</option>';
	s20='<option idEdoSel="20" onclick="PasarEdo(20);" value="20">Oaxaca</option>';
	s21='<option idEdoSel="21" onclick="PasarEdo(21);" value="21">Puebla</option>';
	s22='<option idEdoSel="22" onclick="PasarEdo(22);" value="22">Querétaro</option>';
	s23='<option idEdoSel="23" onclick="PasarEdo(23);" value="23">Quintana Roo</option>';
	s24='<option idEdoSel="24" onclick="PasarEdo(24);" value="24">San Luis Potosí</option>';
	s25='<option idEdoSel="25" onclick="PasarEdo(25);" value="25">Sinaloa</option>';
	s26='<option idEdoSel="26" onclick="PasarEdo(26);" value="26">Sonora</option>';
	s27='<option idEdoSel="27" onclick="PasarEdo(27);" value="27">Tabasco</option>';
	s28='<option idEdoSel="28" onclick="PasarEdo(28);" value="28">Tamaulipas</option>';
	s29='<option idEdoSel="29" onclick="PasarEdo(29);" value="29">Tlaxcala</option>';
	s30='<option idEdoSel="30" onclick="PasarEdo(30);" value="30">Veracruz de Ignacio de la Llave</option>';
	s31='<option idEdoSel="31" onclick="PasarEdo(31);" value="31">Yucatán</option>';
	s32='<option idEdoSel="32" onclick="PasarEdo(32);" value="32">Zacatecas</option>';
	sf = '</select>';
	seleccion = si+s0+s1+s2+s3+s4+s5+s6+s7+s8+s9+s10+s11+s12+s13+s14+s15+s16+s17+s18+s19+s20+s21+s22+s23+s24+s25+s26+s27+s28+s29+s30+s31+s32+sf;
	document.getElementById(id).innerHTML = div;


}

function PasarEdo(edoSelec)
{
	i = edoSelec;
	console.log ("i: %s",i);
	//document.getElementById("idEdoSel").innerHTML = "Exito";
	
	//Query 
/*	for (edo=0;edo<=32;edo++)
	{
	var iden1 = document.getElementById('idEstado');
	var iden2 = iden1.getElementsByName
	document.getElementsByTagName("id").innerHTML = "<br/>Si ya";
	}*/
}

function CambioLugar2(contador)
{
	idEdoSel='Estado'+contador;
	//console.log ("Edo=%s",idEdoSel);
	
	$.ajax
	({
		type : "get",
		url : "que.php",
		dataType : "html",
		data: {"id" : idEdoSel},
		success : function (data,tesStatus,jqxhr)
		{
			alert ("OK");
			$("").html(data);
			
		},
		error: function(data)
		{
			alert(data['nombre']);
		}
	});
}