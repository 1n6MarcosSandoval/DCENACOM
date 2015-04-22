<?php
session_start();
if(@$_SESSION['login'] != "si")
{
header("Location:index.php");
exit();
}
include 'arriba.php';


?>

<div>
	..::<a href="baseDatosExcel.php"> Descarga de la base de datos </a>::..

</div>
		<br/>
	
	<div id="contenido">
		<?php 
		include('lista_paginador.php'); 
		?>		
	</div>
</div>


		<div class="footer">
			<p>Sistema Nacional de Protecci&oacute;n Civil</p>
		</div>

</body>
</html>