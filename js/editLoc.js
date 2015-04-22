
	$(document).ready(function(){
/*		$("#enlaceajax").click(function(evento){
		evento.preventDefault();
		$("#Editar").load("que.php?crReal=23");
		});*/

    $("#enlaceajax").click(function(evento){
        evento.preventDefault();
        var crReal = $("#crReal").val();

        $("#Editar").load("que.php", {crReal: crReal});
    });
})