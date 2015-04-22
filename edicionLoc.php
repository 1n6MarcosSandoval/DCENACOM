<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />

<title>Edici&oacute;n</title>
<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/dojo/1.8/dijit/themes/claro/claro.css">
<link rel="stylesheet" href="images/style.css" type="text/css" />
<script src="//ajax.googleapis.com/ajax/libs/dojo/1.8.0/dojo/dojo.js" data-dojo-config='async: true, parseOnLoad: true'></script>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/editLoc.js"></script>
<script type="text/javascript" src="js/edicion.js"></script>
<script type="text/javascript" src="js/validacionInicialE.js"></script>


<script>
require(["dojo/parser", "dijit/form/DateTextBox"]);
</script>

</head>
<body>

<?php 


	function Obtenerlugar($crReal)
{	

include_once 'functions.php';
include 'vars.php';

$conOracle=conexionORACLE($HOST, $PORT, $SID, $userName, $passkey);

	/**Recuperar lugar**/
	//Estados v2 GEOR 
	$sqlContar = "select count (ID_REPORTE) AS NUMBER_OF_ROWS from CENACOM.GEOR where ID_REPORTE=".$crReal;
	$stid =  oci_parse($conOracle, $sqlContar);
	oci_define_by_name($stid, 'NUMBER_OF_ROWS', $number_of_rows);
	oci_execute($stid);
	oci_fetch($stid);
?>	


<table style="width: 100%">
	<tr>
		<td>Estado</td>
		<td>Municipio</td>
		<td>Localidad</td>
		<td></td>
	</tr>
	<tr>
		<td><?php 
		/**Recuperar Estados**/
	for($contador = 0;$contador<$number_of_rows;$contador++)
	{
		$sqlSelE= "Select ENTIDADES from CENACOM.GEOR where ID_REPORTE =".$crReal." AND ID_GEOR=".$contador;
		$stid = oci_parse($conOracle,$sqlSelE);
		if (!$stid) 
		{
			$e = oci_error($conOracle);
			trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		}
		$r = oci_execute($stid);
		if (!$r) 
		{
			$e = oci_error($stid);
			trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		}
		$ArregloContadorE = [];
		array_push ($ArregloContadorE,$contador);
//		$ArregloEdos =[]; //array_push($ArregloEdos,"Estado".$contador);

		while ($Estado = oci_fetch_array($stid,OCI_ASSOC+OCI_RETURN_NULLS)) 
		{
			foreach ($Estado as $valor)
			{
			//echo $valor;
			}
		$EstadosArray = array_combine ($ArregloContadorE,$Estado);
		//echo is_array($EstadosArray);		
		}
			//print_r ($EstadosArray);			//echo "<br/>Estado NUM:".$EstadosArray[$contador];

		foreach ($EstadosArray as $EstadoActual)
		{
			//echo $EstadoActual."<br/>";
			$estadoN = $EstadoActual;
			//echo $estadoN;
			$estado=recuperaCampo($conOracle, 'ANRO.LOCALIDADES', 'NOM_ENT', $estadoN, 'ENTIDAD');	
			print "<div id='Edo".$contador."'>".$estado."</div>";
			$query = oci_parse($conOracle, 'SELECT ENTIDAD, NOM_ENT from ANRO.LOCALIDADES ORDER BY NOM_ENT ASC');

		}	
	}
/**Fin Recuperar Estados**/		
		?></td>
		
		<td><?php 
		/**Recuperar Municipios**/
	for($contador = 0;$contador<$number_of_rows;$contador++)
	{
		$sqlSelM= "Select MUNS from CENACOM.GEOR where ID_REPORTE =".$crReal." AND ID_GEOR=".$contador;
		$stid = oci_parse($conOracle,$sqlSelM);
		if (!$stid) 
		{
			$e = oci_error($conOracle);
			trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		}
		$r = oci_execute($stid);
		if (!$r) 
		{
			$e = oci_error($stid);
			trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		}
		$ArregloContadorM = [];
		array_push ($ArregloContadorM,$contador);

		while ($Muns = oci_fetch_array($stid,OCI_ASSOC+OCI_RETURN_NULLS)) 
		{
			foreach ($Muns as $valor)
			{
			}
		$MunsArray = array_combine ($ArregloContadorM,$Muns);	
		}

		foreach ($MunsArray as $MunsActual)
		{
			$MunsN = $MunsActual;
			$Muns=recuperaCampo($conOracle, 'ANRO.LOCALIDADES', 'NOM_MUN', $MunsN, 'MUN');	
			print "<div id='Mun".$contador."'>".$Muns."</div>";
			$query = oci_parse($conOracle, 'SELECT MUN, NOM_MUN from ANRO.LOCALIDADES ORDER BY NOM_MUN ASC');
		}	
	}
/**Fin Recuperar Municipios**/
		?></td>
		
		<td><?php 
		/**Recuperar Localidades**/
	for($contador = 0;$contador<$number_of_rows;$contador++)
	{
		$sqlSelL= "Select LOCS from CENACOM.GEOR where ID_REPORTE =".$crReal." AND ID_GEOR=".$contador;
		$stid = oci_parse($conOracle,$sqlSelL);
		if (!$stid) 
		{
			$e = oci_error($conOracle);
			trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		}
		$r = oci_execute($stid);
		if (!$r) 
		{
			$e = oci_error($stid);
			trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		}
		$ArregloContadorL = [];
		array_push ($ArregloContadorL,$contador);

		while ($Locs = oci_fetch_array($stid,OCI_ASSOC+OCI_RETURN_NULLS)) 
		{
			foreach ($Locs as $valor)
			{
			}
		$LocsArray = array_combine ($ArregloContadorL,$Locs);
		}

		foreach ($LocsArray as $LocsActual)
		{
			$LocsN = $LocsActual;
			$Locs=recuperaCampo($conOracle, 'ANRO.LOCALIDADES', 'NOM_LOC', $LocsN, 'LOC');	
			print "<div id='Loc".$contador."'>".$Locs."</div>";
			print "<br/>".$Locs;
			$query = oci_parse($conOracle, 'SELECT LOC, NOM_LOC from ANRO.LOCALIDADES ORDER BY NOM_LOC ASC');
		}	
	}
/**Fin Recuperar Localidades**/	
		?></td>
		<td>
		<?php
		//Link Edicion
		for($contador = 0;$contador<$number_of_rows;$contador++)
		{
			$ArregloEdos =[]; 
			array_push($ArregloEdos,"Estado".$contador);
			foreach ($ArregloEdos as $EstadoID)
			{
		?>
			

		<?php
			}
			
		}
		
		?>		
		</td>
	</tr>
</table>
<br/>

<div id="CambioLugar">
					<!--<a  href="#Lugares" onclick="CambioLugar(<?php //echo $contador;?>);">-->
					<h4>Nota: Al momento de dar inicio a la edici&oacute;n se eliminan los registros anteriores por lo cual se debe colocar al menos un nuevo estado con su respectivo municipio y su localidad si es que la contiene</h4>
					<input type="hidden" name="crReal" id="crReal" value="<?php echo $crReal; ?>">
					<a  href="#" id="enlaceajax">Iniciar edici&oacute;n</a><br/>
						<div id="Editar">	
								
						</div>	
							
</div>
<br/>
<?php


$inicialEstadoBD=obtenerValorQuery($conOracle,'ANRO','LOCALIDADES','ENTIDAD',$estadoN,'NOM_ENT');
} //fin funcion
?>

</body>
</html>