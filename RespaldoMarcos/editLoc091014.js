function CambioLugar(contador)
{
	id='Estado'+contador;

	new Ajax.Updater
	(
		'Editar',
		'que.php',{
		method : 'post',
		evalScripts : true,
		}
	);
	


}
/*
        new Ajax.Updater(
             'listaResponsable',
            ruta_local+'quejas/listaResponsable', {
                method: 'post',
                evalScripts: true,
		onComplete: function(respuesta) {
                    if(respuesta.responseText == 0){
                        alert('Ocurrio un error al registrar al responsable');
                    }
                },
                parameters : {
                    idKioscoGeneral : idKioscoGeneral
                }
            }
        );
    }
	
/*
	$.ajax
	({
		type : "post",
		url : "que.php",
		dataType : "html",
		data: {"id" : idEdoSel},
		success : function (data)
		{
			//alert ("OK");
			$("#Lugares").html();	
		},
		error: function(data)
		{
			alert(data['nombre']);
		}
	});
*/