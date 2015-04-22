<html>
<head><title></title>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/functions.js"></script>
</head>

<body>
<form id="funico" action="funico.php" name="funico" method="post">

Usuario(s) que registra(n) el reporte: <br/>
<select id="unicoAutoresTurno" onchange="SeleccionandoComboM(this, 'unicoAutores', 'autores');" title="Autores" name="unicoAutoresTurno">
<option value="0">Seleccionar turno</option>
<option value="1">MATUTINO</option>
<option value="2">VESPERTINO</option>
<option value="3">NOCTURNO</option>
<option value="4">FIN DE SEMANA Y DIAS FESTIVOS</option>
<option value="5">MANDOS MEDIOS</option>
</select>
<br>
<select id="unicoAutores" size="5" multiple="" title="Autores" name="unicoAutores[]">
<option value="0">Primero seleccione el turno...</option>
</select>

<input type="button" value="Registrar" onclick="valida()" />

</form>
</body>
</html>

