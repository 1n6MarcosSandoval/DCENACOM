<html>
<head>
<script type="text/javascript">
function selecciona(id)
{
  id=document.getElementById(id);
  for (i=0; ele = id.options[i]; i++)
   {
    ele.selected = true;
   }
}
</script>
</head>
<body>
<form method="post" action="">
<select multiple name="sel[]" id="sel">
<option value="0">cero</option>
<option value="1">uno</option>
<option value="2">dos</option>
</select>
<input type="button" name="submit" value="Selecciona" onclick="selecciona('sel')" />
</body>
</html> 