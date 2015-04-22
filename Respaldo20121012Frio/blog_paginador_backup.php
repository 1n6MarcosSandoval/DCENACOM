
<?php
require('./conexion.php');
$RegistrosAMostrar=15;

//estos valores los recibo por GET
if(isset($_GET['pag'])){
	$RegistrosAEmpezar=($_GET['pag']-1)*$RegistrosAMostrar;
	$PagAct=$_GET['pag'];
//caso contrario los iniciamos
}else{
	$RegistrosAEmpezar=0;
	$PagAct=1;
	
}
$Resultado=mysql_query("SELECT blogTitulo, blogAutor, blogFecha, blogCategoria, id FROM blog ORDER BY blogFecha DESC LIMIT $RegistrosAEmpezar, $RegistrosAMostrar",$con);
echo "<table border='1px' cellpadding='4px' width='590px'>";
	echo "<tr>";
	echo '<td bgcolor="#D8D8D8" width="30%"> <center><h3>T&iacute;tulo</h3></center></td>';
	echo '<td bgcolor="#D8D8D8" width="12%"><center><h3>Categor&iacute;a</h3></center></td>';
	echo '<td bgcolor="#D8D8D8" width="26%"><center><h3>Fecha</h3></center></td>';
	echo '<td bgcolor="#D8D8D8" width="20%"><center><h3>Autor</h3></center></td>';
	echo '<td bgcolor="#D8D8D8" width="12%"><center><h3>Editar</h3></center></td>';
	echo '</tr>';
while($MostrarFila=mysql_fetch_array($Resultado)){
	echo "<tr>";
	echo "<td>".$MostrarFila['blogTitulo']."</td>";
	echo '<td>'.$MostrarFila['blogCategoria'].'</td>';
	echo "<td>".$MostrarFila['blogFecha']."</td>";
	echo "<td>".$MostrarFila['blogAutor']."</td>";
	echo '<td> <a href="blog_editar.php?id='.$MostrarFila['id'].'"> Editar</a></td>'; 
	echo "</tr>";
}
echo "</table>";
//******--------determinar las p�ginas---------******//
$NroRegistros=mysql_num_rows(mysql_query("SELECT * FROM blog",$con));

$PagAnt=$PagAct-1;
$PagSig=$PagAct+1;
$PagUlt=$NroRegistros/$RegistrosAMostrar;

//verificamos residuo para ver si llevar decimales
$Res=$NroRegistros%$RegistrosAMostrar;
// si hay residuo usamos funcion floor para que me
// devuelva la parte entera, SIN REDONDEAR, y le sumamos
// una unidad para obtener la ultima pagina
if($Res>0) $PagUlt=floor($PagUlt)+1;

//desplazamiento
echo '<br/>';
echo '<center><div style="margin:auto;width:700px;">';
echo "<a onclick=\"Pagina('1','blog_paginador.php')\">Primero </a> ";
if($PagAct>1) echo "<a onclick=\"Pagina('$PagAnt','blog_paginador.php')\">Anterior </a> ";
echo "<strong>.: Pagina ".$PagAct."/".$PagUlt." :.</strong>";
if($PagAct<$PagUlt)  echo " <a onclick=\"Pagina('$PagSig','blog_paginador.php')\">Siguiente </a> ";
echo "<a onclick=\"Pagina('$PagUlt','blog_paginador.php')\"> Ultimo</a>";
echo '</div> </center>';
?>