<?php

//Valida que la seccion de usuario sea correcta
session_start();
if(@$_SESSION['login'] != "si")
{
	$canservero="NO Pasa";
	if($_SESSION['login'] == "usuarioMapa"){
		$canservero="Pasa";
	}else{
		$canservero="NO Pasa";
	}
}else{
	$canservero="Pasa";
}

if($canservero!="Pasa"){
	header("Location:index.php");
	exit();
}

include 'functions.php';
include 'vars.php';

$conOracle=conexionORACLE($HOST, $PORT, $SID, $userName, $passkey);
$llave=llave();

$linkGEORSS="http://www.incendiosmovil.gob.mx/dcenacom/georss.php?value=".$llave;

//ESCRIBE LA LLAVE DE SEGURIDAD PARA VISUALIZAR EL GEORSS
$sql0="UPDATE CENACOM.GEORSS SET CLAVE='".$llave."' WHERE ID_LINK=1";

$escribeOracleGEORSS=oci_parse($conOracle,$sql0);
if (!$escribeOracleGEORSS) {
    $e = oci_error($conOracle);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
$resultadoEnOracleGEORSS=oci_execute($escribeOracleGEORSS);
if (!$resultadoEnOracleGEORSS) {
    $e = oci_error($escribeOracleGEORSS);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}


?>

<!doctype html>
<html>
<head>
<meta http-equiv="refresh" content="300">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=7, IE=9, IE=10">
<meta name="viewport" content="initial-scale=1, maximum-scale=1,user-scalable=no">
<title></title>

<link rel="stylesheet" href="http://serverapi.arcgisonline.com/jsapi/arcgis/3.5/js/dojo/dijit/themes/tundra/tundra.css">
<link rel="stylesheet" href="http://serverapi.arcgisonline.com/jsapi/arcgis/3.5/js/esri/css/esri.css">
<style>
  html, body { height: 100%; width: 100%; margin: 0; padding: 0; }
  #map { margin: 0; padding: 0; }
  #meta {
    position: absolute;
    left: 20px;
    bottom: 20px;
    width: 25em;
    height: 9em;
    z-index: 40;
    background: #fff;
    color: #777;
    padding: 5px;
    border: 2px solid #666;
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    border-radius: 5px; 
    font-family: arial;
    font-size: 0.9em;
  }
  #meta h3 {
    color: #666;
    font-size: 1.1em;
    padding: 0px;
    margin: 0px;
    display: inline-block;
    
  }
  #loading { 
    float: right;
  }
</style>

<script language="javascript" type="text/javascript">

function MostrarFecha()
{
var nombres_dias = new Array("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado")
var nombres_meses = new Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre")
var fecha_actual = new Date()

dia_mes = fecha_actual.getDate() //dia del mes
dia_semana = fecha_actual.getDay() //dia de la semana
mes = fecha_actual.getMonth() + 1
anio = fecha_actual.getFullYear()

var fechaHora = new Date();
var horas = fechaHora.getHours();
var minutos = fechaHora.getMinutes();
var segundos = fechaHora.getSeconds();
var sufijo = 'AM';

if(horas > 12) {
horas = horas-12;
sufijo = 'PM';
}

if(horas < 10) { horas = '0' + horas; }
if(minutos < 10) { minutos = '0' + minutos; }
if(segundos < 10) { segundos = '0' + segundos; }

//escribe en pagina

document.write(nombres_dias[dia_semana] + ", " + dia_mes + " de " + nombres_meses[mes - 1] + " de " + anio + ", "+ horas + ":"+minutos + " " + sufijo)
}

</script>


<script>var dojoConfig = { parseOnLoad: true };</script>
<!--<script src="http://serverapi.arcgisonline.com/jsapi/arcgis/3.6/"></script>-->
<script src="http://js.arcgis.com/3.6/"></script>
<script>
  dojo.require("dijit.layout.BorderContainer");
  dojo.require("dijit.layout.ContentPane");
  dojo.require("esri.map");
  dojo.require("esri.layers.GeoRSSLayer");
  dojo.require("esri.symbols.SimpleMarkerSymbol")
  var map, georss, georssUrl, template, layers;
  function init() {
    var ext = new esri.geometry.Extent({"xmin":-13207534.78,"ymin":1322528.68,"xmax":-9538557.43,"ymax":4135411.32,"spatialReference":{"wkid":102100}});
    //var ext = new esri.geometry.Extent({"xmin":-13207534.78,"ymin":1322528.68,"xmax":-9538557.43,"ymax":4135411.32,"spatialReference":{"wkid":102119}});
    
    //var ext = esri.geometry.geographicToWebMercator(new esri.geometry.Extent(-144.13, 7.98, -52.76, 68.89, new esri.SpatialReference({wkid: 4326})));
    //var ext = esri.geometry.geographicToWebMercator(new esri.geometry.Extent(-144.13, 7.98, -52.76, 68.89, new esri.SpatialReference({wkid: 4483})));
    
          map = new esri.Map("map", {
            //basemap: "satellite",
            basemap: "hybrid",
            //center: [-116.96, 33.184],
            extent: ext,
            zoom: 5
          });  
    
    //map = new esri.Map("map",{ extent: ext });
    //var basemap2 = new esri.layers.ArcGISTiledMapServiceLayer("http://services.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer");
    //map.addLayer(basemap2);
    //var basemap1 = new esri.layers.ArcGISTiledMapServiceLayer("http://services.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer");
    //map.addLayer(basemap1);

    //georssUrl = "http://www.ssn.unam.mx/jsp/ultimos_sismosT.xml";
    //georssUrl = "http://earthquake.usgs.gov/earthquakes/feed/v1.0/summary/2.5_day.atom";
    georssUrl = "<?php print $linkGEORSS;?>";
    // other examples of GeoRSS feeds:
    // var georssUrl = "http://geocommons.com/overlays/188692.atom"; // U.S. Breweries in 2009 http://geocommons.com/overlays/188692
    // var georssUrl = "http://geocommons.com/overlays/116926.atom"; // S.F. and East Bay Breweries http://geocommons.com/overlays/116926
    georss = new esri.layers.GeoRSSLayer(georssUrl); 
    map.addLayer(georss);
    dojo.connect(georss, "onLoad", function() {
      dojo.style(dojo.byId("loading"), "display", "none");
      // create an info template
      //template = new esri.InfoTemplate("${name}", "${description}");
	template = new esri.InfoTemplate();
	template.setTitle("${title}");
	template.setContent("${description}<br/> </b><a href=\"${link}\" target=_blank> Más información </a><br/>");
      // set the info template for the feature layers that make up the GeoRSS layer
      // the GeoRSS layer contains one feature layer for each geometry type
      layers = georss.getFeatureLayers();

     
      
      //console.log(layers.type)
      dojo.forEach(layers, function(l) {
        l.setInfoTemplate(template);
		
		/*
		if (l.type = 'esri.geometry.Point'){
			console.log(l.type);
			//var renderer = new esri.renderer.ClassBreaksRenderer(symbol);
			//l.setRenderer(renderer);
       	}
        */
        
      });
    });
  }
  dojo.ready(init);
  
  setTimeout(function(){
  	document.getElementById("numeroReportes").innerHTML="Se muestran los últimos "+georss.items.length+" reportes registrados en la base de datos.";
  	},10000);
  
</script>
</head>

<body class="tundra">
<div data-dojo-type="dijit.layout.BorderContainer" data-dojo-props="design:'headline',gutters:false" style="width: 100%; height: 100%; margin: 0;">
  <div id="map" data-dojo-type="dijit.layout.ContentPane" data-dojo-props="region:'center'">
    <div id="meta">
      <span id="loading"><img src="http://dl.dropbox.com/u/2654618/loading_black.gif" /></span>
      <table>
      	<tr>
      		<td>
      			<img src="images/logoPCF.jpg" />
      		</td>
      		<td>
      			<h3>CENACOM</h3>
      			<br />
      			<b>Mapa de reportes relevantes</b>
      			<br />
      			<div id="numeroReportes">Se muestran los últimos reportes registrados en la base de datos.</div>
      			<br />
      			Última actualización visual:
      			<br />
      			<script language="JavaScript" type="text/javascript">
					MostrarFecha();
				</script>
      		</td>
      	</tr>
      </table>
      
 
    <div>
  </div>
</div>


<?php cerrarConexionORACLE($conOracle); ?>
</body>
</html>
