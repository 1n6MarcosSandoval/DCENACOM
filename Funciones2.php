<?php
include 'functions.php';
//Conexion a BD Cenacom
$HOST1="10.2.233.164";
$PORT1=1521;
$SID1="ORCL";
$userName1="cenacom";
$passkey1="jxkGR";
//Fin Variables conexion
//Conexion a oracle
$codigo = $_POST['codigo'];
$conOracle1=conexionORACLE($HOST1, $PORT1, $SID1, $userName1, $passkey1);

//*************************
//Consulta  a la BD
$query1 = oci_parse($conOracle1, "SELECT ID_REPORTE, TO_CHAR(FECHA_AVISO, 'HH24:MI DD-MON-YYYY') AS FECHA,CLAVE_LUGAR,CR, NIVEL, EFECTO_ADVERSO, OBSERVACIONES,RESPUESTA_INSTITUCIONAL,SELECCION,VISIBLE,CLASIFICACIONFENOMENO_ID from cenacom.reportes ORDER BY ID_REPORTE DESC");
	if(!$query1){
    	$e = oci_error($conOracle1);
    	trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		print "Error en la conexion a la base de datos";
	}
	else if(!oci_execute($query1)){
    	$e = oci_error($query1);
    	trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		print "Error en el query";
	}
	else{	
		$contador=0;
	    $ConcatenaEdo="";
		$ConcatenaMun="";
		$contadorEventos=0;
		$DatosEvento="Hola";
		$DatosPoligonos=array();
		$DatosGeneralesPoligonos=array();
		$ElemtEvento = array();		
		$contadorPoligonos=0;    
	while ($row1 = oci_fetch_array($query1, OCI_ASSOC+OCI_RETURN_NULLS)) {	
	//Obtener Datos para elementos puntuales
	if($row1["VISIBLE"]==1){
			$nivel=$row1["NIVEL"];
			$efectoAdverso=$row1["EFECTO_ADVERSO"];
			$Observaciones=$row1["OBSERVACIONES"];
			$fecha=$row1["FECHA"];

			//Consulta 
			$query3 = oci_parse($conOracle1, "SELECT NOMBRE from cenacom.DEPENDENCIAS where ID in(".$row1["RESPUESTA_INSTITUCIONAL"].")");
			if(!$query3){
			$e = oci_error($conOracle1);
			trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
			print "Error en la conexion a la base de datos";
			}
			else if(!oci_execute($query3)){
				$e = oci_error($query3);
				trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
				print "Error en el query";
			}
			$dependencias=array();
			$contadorDependencias=0;
			while ($row3 = oci_fetch_array($query3, OCI_ASSOC+OCI_RETURN_NULLS)) {
				$dependencias[$contadorDependencias]=$row3["NOMBRE"];
				$contadorDependencias=$contadorDependencias+1;
			}
			
			//print $estado.'--';
			
	
			//SubConsulta a la tabla de detalle de Estados
			$DatosGeneralesPoligonos=array('efectoAdverso'=>($efectoAdverso == null) ? ' ' : $efectoAdverso,'fecha'=>($fecha == null) ? ' ' : $fecha,'dependencias'=>($dependencias == null) ? ' ' : $dependencias,'Observaciones'=>($Observaciones == null) ? ' ' : $Observaciones,'nivel'=>($nivel == null) ? ' ' : $nivel,'id_reporte'=>($row1["ID_REPORTE"] == null) ? ' ' : $row1["ID_REPORTE"]);
				$query2 = oci_parse($conOracle1, "SELECT ENTIDADES,MUNS,CLAVE_LUGAR,ID_REPORTE from cenacom.geor where ID_REPORTE=".$row1["ID_REPORTE"]);
				if(!$query2){
					$e = oci_error($conOracle1);
					trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
					print "Error en la conexion a la base de datos";
				}
				else if(!oci_execute($query2)){
					$e = oci_error($query2);
					trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
					print "Error en el query";
				}
				else{		
				
				$contadorMun=0;				
				$ArregloUbicacionPligono=array();
				while ($row2 = oci_fetch_array($query2, OCI_ASSOC+OCI_RETURN_NULLS)) {
				    if($row1["SELECCION"]=='PUNTO')
					{
					
						$latitud=recuperaCampo($conOracle1,"coordenadas","LATITUD",$row2["CLAVE_LUGAR"], "CLVE_LUGAR");
						$longitud=recuperaCampo($conOracle1,"coordenadas","LONGITUD",$row2["CLAVE_LUGAR"], "CLVE_LUGAR");
						$estado=recuperaCampo($conOracle1,"coordenadas","ESTADO",$row2["CLAVE_LUGAR"], "CLVE_LUGAR");
						$municipio=recuperaCampo($conOracle1,"coordenadas","MUNICIPIO",$row2["CLAVE_LUGAR"], "CLVE_LUGAR");
						$ElemtEvento[$contadorEventos]=array('longitud'=>($longitud == null) ? ' ' : $longitud,'latitud'=>($latitud == null) ? ' ' : $latitud,'municipio'=>($municipio == null) ? ' ' : $municipio,'estado'=>($estado == null) ? ' ' : $estado,'efectoAdverso'=>($efectoAdverso == null) ? ' ' : $efectoAdverso,'fecha'=>($fecha == null) ? ' ' : $fecha,'dependencias'=>($dependencias == null) ? ' ' : $dependencias,'Observaciones'=>($Observaciones == null) ? ' ' : $Observaciones,'nivel'=>($nivel == null) ? ' ' : $nivel,'id_reporte'=>($row1["ID_REPORTE"] == null) ? ' ' : $row1["ID_REPORTE"],'Fenomeno'=>($row1["CLASIFICACIONFENOMENO_ID"] == null) ? ' ' : $row1["CLASIFICACIONFENOMENO_ID"]);
						$contadorEventos=$contadorEventos+1;
					} 
					else if($row1["SELECCION"]=='AREA'){
					$strConca="";
					$strConcaEdo="";
					if(strlen($row2["ENTIDADES"])==1)
					{
						$strConcaEdo="0";
					}
					if(strlen($row2["MUNS"])==1){
						$strConca="00";
					}
					else if((strlen($row2["MUNS"])==2)){
						$strConca="0";
					}
					else{
						$strConca="";
					}
					
					$ArregloUbicacionPligono[$contadorMun]=array('clavemun'=>$strConca.$row2["MUNS"],'claveestado'=>$strConcaEdo.$row2["ENTIDADES"]);
				    $contadorMun=$contadorMun+1;
					   
				    }					
					
					
				}
				if($row1["SELECCION"]=='AREA'){
				$DatosPoligonos[$contadorPoligonos]=array('DatosGeneralesPol'=>$DatosGeneralesPoligonos,'UbicacionPoligonos'=>$ArregloUbicacionPligono,'nivel'=>$row1["NIVEL"]);
			    $contadorPoligonos=$contadorPoligonos+1;
				}
				
				
				
				
		}
		
		}
		}
		$DatosGeograficos = new stdClass();
		$DatosGeograficos->Eventos =$ElemtEvento;
		$DatosGeograficos->DatosPol=$DatosPoligonos;
		echo json_encode($DatosGeograficos);
		cerrarConexionORACLE($conOracle1);
		
		
		
 
		
		}
		
	//******************************************
	


//***************************

?>