<?php

header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=BASE_DE_DATOS_CASOS_RELEVANTES.xls");
header("Pragma: no-cache");
header("Expires: 0");

include 'functions.php';
include 'vars.php';
session_start();
if(@$_SESSION['login'] != "si")
{
header("Location:index.php");
exit();
}
$conOracle=conexionORACLE($HOST, $PORT, $SID, $userName, $passkey);


// Prepare the statement Lista de eventos
$stidE = oci_parse($conOracle, 'SELECT ID_EVENTO FROM CENACOM.EVENTO ORDER BY ID_EVENTO');
if (!$stidE) {
    $e = oci_error($conOracle);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

// Perform the logic of the query Lista de eventos
$rE = oci_execute($stidE);
if (!$rE) {
    $e = oci_error($stidE);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

?>


        <table border="1">             
            <tr>   
                <th>CR</th>
				<th>Inicio</th>   
				<th>T&eacute;rmino</th>   
                <th>Da&ntilde;os materiales</th> 
				<th>Fen&oacute;meno</th> 
				<th>Tipo de Fen&oacute;meno</th> 
				<th>Nivel</th>
				<th>Entidad</th> 
				<th>Municipio</th> 
				<th>Comunidad / Ejido</th> 
				<th>Muertos</th> 
				<th>Lesionados</th> 
				<th>Evacuados</th> 
				<th>Desaparecidos</th> 
				<th>Dependencias participantes</th>
				<th>Observaciones</th>                    
				<th>Declaratoria</th>
            </tr>
           
<?php
while ($rowE = oci_fetch_array($stidE, OCI_ASSOC+OCI_RETURN_NULLS)) {	
?>

<?php
// Prepare the statement
$stid = oci_parse($conOracle, 'SELECT * FROM CENACOM.REPORTES WHERE ID_EVENTO='.$rowE["ID_EVENTO"].' ORDER BY CR');
if (!$stid) {
    $e = oci_error($conOracle);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

// Perform the logic of the query
$r = oci_execute($stid);
if (!$r) {
    $e = oci_error($stid);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

// Prepare the statement 2
$cadenaValores="SUM(MUERTOS) AS MUERTOS, SUM(LESIONADOS) AS LESIONADOS, SUM(DESAPARECIDOS) AS DESAPARECIDOS, SUM(EVACUADOS) AS EVACUADOS";
$stid2 = oci_parse($conOracle, 'SELECT '.$cadenaValores.' FROM CENACOM.REPORTES WHERE ID_EVENTO='.$rowE["ID_EVENTO"].' GROUP BY ID_EVENTO');
if (!$stid2) {
    $e = oci_error($conOracle);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

// Perform the logic of the query 2
$r2 = oci_execute($stid2);
if (!$r2) {
    $e = oci_error($stid);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

// Prepare the statement 3
$stid3 = oci_parse($conOracle, 'SELECT * FROM CENACOM.EVENTO WHERE ID_EVENTO='.$rowE["ID_EVENTO"]);
if (!$stid3) {
    $e = oci_error($conOracle);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

// Perform the logic of the query 3
$r3 = oci_execute($stid3);
if (!$r3) {
    $e = oci_error($stid3);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
?>           
           
           
           
           
           
				<tr>
                <td align="center">
					<?php $dato = '';
							$i=0;
							while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
								//print $row[CR].', ';
		
								$dato[$i] = $row["CR"];
								$tipo[$i] = $row["ID_TIPO_REPORTE"];
								$i++;
								
								$entidad = recuperaCampo($conOracle, 'ANRO.LOCALIDADES', 'NOM_ENT', $row["ESTADO"], 'ENTIDAD');
								$municipio = recuperaCampo($conOracle, 'ANRO.LOCALIDADES', 'NOM_MUN', $row["MUNICIPIO"], 'MUN');
								if($row["LOCALIDAD"]!=NULL){
									$comunidad=recuperaCampo($conOracle, 'ANRO.LOCALIDADES', 'NOM_LOC', $row["LOCALIDAD"], 'LOC');	
								}elseif($row["OTRO_LUGAR"]!=NULL){
									$comunidad=$row["OTRO_LUGAR"];
								}else{$comunidad="";}
								
								//print $row["CLASIFICACIONFENOMENO_ID"];
								$campoCla="'".$row["CLASIFICACIONFENOMENO_ID"]."'";
								$claseFenomeno=recuperaCampo($conOracle, 'ANRO.CLASIFICACIONFENOMENO', 'CLASIFICACION', $campoCla, 'ID');
								$tipoFenomeno=recuperaCampo($conOracle, 'CENACOM.TIPO_FENOMENO', 'NOMBRE', $row["TIPOFENOMENO_ID"], 'ID_FENOMENO');
								
							}
							$i=0;
							for($i=0 ; $i < (sizeof($dato)-2) ; $i++)
								print $dato[$i]. ", ";					
							
							if(sizeof($dato)>1){
								if($dato[$i+1]!=NULL && ($tipo[$i+1]==4 || $tipo[$i+1]==1) )
									print $dato[$i]. ' y ' . $dato[$i+1];
									
								else if($dato[$i+1]!=NULL && ($tipo[$i+1]!=4 || $tipo[$i+1]!=1) )
									print $dato[$i]. ', ' . $dato[$i+1];
							}else
								print$dato[$i];
							
							
							
							
					?>
				</td>

				<?php while ($row3 = oci_fetch_array($stid3, OCI_ASSOC+OCI_RETURN_NULLS)) { ?>
				
				<td align="center">
				<?php print $row3["FECHA_INICIO"]; ?>
				</td>	

				<td align="center">
				<?php print $row3["FECHA_FIN"]; ?>
				</td>					
				
				<td>
				<?php print $row3["DANOS_MATERIALES"]; ?>
				</td>					
				
				<td align="center">
				<?php print $claseFenomeno; ?>
				</td>	

				<td align="center">
				<?php print $tipoFenomeno; ?>
				</td>	

				<td align="center">
				<?php print $entidad; ?>
				</td>	

				<td align="center">
				<?php print $municipio;?>
				</td>	
				
				<td align="center">
				<?php print $comunidad; ?>
				</td>	
				
					<?php while ($row2 = oci_fetch_array($stid2, OCI_ASSOC+OCI_RETURN_NULLS)) { ?>
				
				<td align="center">
				<?php print $row2["MUERTOS"]; ?>
				</td>	
				
				<td align="center">
				<?php print $row2["LESIONADOS"]; ?>
				</td>	
				
				<td align="center">
				<?php print $row2["EVACUADOS"]; ?>
				</td>	
				
				<td align="center">
				<?php print $row2["DESAPARECIDOS"]; ?>				
				</td>	
				
				
					<?php } ?>
				
				
				<td>
				<?php
				$myArray = explode(',', $row3["DEPENDENCIAS_PARTICIPANTES"]);
				$dato = '';
				$i=0;
				for ($i = 0; $i < (sizeof($myArray)-1); $i++)
					$dato = $dato . recuperaCampo($conOracle, 'CENACOM.DEPENDENCIAS', 'NOMBRE', $myArray[$i], 'ID'). ', '; 
				$dato = $dato . recuperaCampo($conOracle, 'CENACOM.DEPENDENCIAS', 'NOMBRE', $myArray[$i], 'ID'); 
				print $dato;				
				?>
				</td>
				
				<td>
				<?php print $row3["OBSERVACIONES"]; ?>
				</td>	
				
				<td>
				<?php print $row3["DECLARATORIA"]; ?>
				</td>	
			
				<?php } ?>
                 
			</tr>
            
<?php } ?>
            
        </table>
