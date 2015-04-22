<?php

//Valida que la seccion de usuario sea correcta
/*session_start();
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
}*/

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
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1,user-scalable=no">
<title></title>
<script type="text/javascript" src="js/MapaBase.js"></script>
<link href="css/Botones.css" rel="stylesheet" />
<link rel="stylesheet" href="http://serverapi.arcgisonline.com/jsapi/arcgis/3.5/js/dojo/dijit/themes/tundra/tundra.css">
<link rel="stylesheet" href="http://serverapi.arcgisonline.com/jsapi/arcgis/3.5/js/esri/css/esri.css">
<style>
  html, body { height: 100%; width: 100%; margin: 0; padding: 0; }
  #map {
  margin:0;
  height:100%;
  }
  #meta {
    position: absolute;
    left: 20px;
    bottom: 20px;
    width: 25em;
    height: 1em;
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



<script src="http://js.arcgis.com/3.10/"></script>
<script>

  var map;
  var georss;
  var banderaMapTip=false;
  require([
    "esri/map", 
    "esri/layers/GeoRSSLayer", 
	"esri/renderers/UniqueValueRenderer",
	"esri/symbols/PictureMarkerSymbol",
    "esri/InfoTemplate",
    "dojo/parser", 
    "dojo/_base/array", 
    "dojo/dom-style", 
    "dijit/layout/BorderContainer", 
    "dijit/layout/ContentPane", 
    "dojo/domReady!"
  ], function(
    Map, 
    GeoRSSLayer,
	UniqueValueRenderer,
	PictureMarkerSymbol,
    InfoTemplate,
    parser, 
    arrayUtils, 
    domStyle
  ) {
 
        function init(){
              map = new esri.Map("map",{ 
                basemap: "hybrid", 
                center: [-102.12,21.56],
                zoom: 6
              });

              // create layout dijits
              parser.parse();

              var georssUrl = "<?php print $linkGEORSS;?>";

			 georss = new esri.layers.GeoRSSLayer(georssUrl,{id:"georssReportes"});

              georss.on("load", function() {
                domStyle.set("loading", "display", "none");
				var symbol_rojo = new esri.symbol.PictureMarkerSymbol("images/Alertas_rojo.gif", 45, 45);
				var symbol_naranja = new esri.symbol.PictureMarkerSymbol("images/Alertas_naranja.gif", 40, 40);
				var symbol_amarillo = new esri.symbol.PictureMarkerSymbol("images/Alertas_amarillo.gif", 35, 35);
                var renderer = new UniqueValueRenderer(symbol_rojo, "NIVEL");
				renderer.addValue("alto", symbol_rojo);
				renderer.addValue("medio", symbol_naranja);
				renderer.addValue("bajo", symbol_amarillo);

                // create an info template
                //  var template = new InfoTemplate("${name}", "${description}<br/>");
                // set the info template for the feature layers that make up the GeoRSS layer
                // the GeoRSS layer contains one feature layer for each geometry type
                var layers = georss.getFeatureLayers();
                arrayUtils.forEach(layers, function(l) {
				if (l.type = 'esri.geometry.Point'){
				  //l.setInfoTemplate(template);
				  l.setRenderer(renderer);
				 }
                });
              });

               map.addLayer(georss);
			   //createMapTip();
        }
         
function createMapTip() {
    var dialog;
    require([
      "esri/lang", "dojo/dom-style",
      "dijit/TooltipDialog", "dijit/popup", "dojo/domReady!"
    ], function (
      esriLang, domStyle,
      TooltipDialog, dijitPopup
    ) {
        dialog = new TooltipDialog({
            //id: "tooltipDialog",
            style: "position: absolute; width: 250px; font: normal normal normal 10pt Helvetica;z-index:100"
        });
        dialog.startup();

        georss.on("click", function (evt) {
            if (banderaMapTip == false) {
                var myRand = Math.floor(Math.random() * 2) + 1;
                if (myRand == 1) {
                    var t = "<b>{${name}}</b><hr><b>></b>>${description} <br>"
                }
                else {
                    var t = "<b>${name}}</b><hr><b>></b>>${description} <br>";
                }

                var content = esriLang.substitute(evt.graphic.attributes, t);

                dialog.setContent(content);
                domStyle.set(dialog.domNode, "opacity", 0.75);
                dijitPopup.open({
                    popup: dialog,
                    x: evt.pageX,
                    y: evt.pageY
                });
                banderaMapTip = true;
            }
            else {
                dijitPopup.close(dialog);
                banderaMapTip = false;
            }
        });
    });
}
        init();

    });
  
  setTimeout(function(){
  	document.getElementById("numeroReportes").innerHTML="Se muestran los últimos reportes registrados en la base de datos.";
  	},10000);
  
</script>
</head>

<body class="tundra">
<div data-dojo-type="dijit.layout.BorderContainer" data-dojo-props="design:'headline',gutters:false" style="width: 100%; height: 100%; margin: 0;">
  <div id="map" data-dojo-type="dijit.layout.ContentPane" data-dojo-props="region:'center'">
  <img alt="" src="img/banner/banner1.jpg" width="100%" height="12%"/>
  <div id="MapaBase" style=" position:absolute; float:left; margin-top:5px; margin-left:80px; z-index:150;">
                        <button type="button"  style="opacity:0.7;" onclick="CambiarRelieve()">Calle</button> 
                       <button type="button" style="opacity:0.7;" onclick="CambiarSatelital()">Satelital</button>
                    </div>
    <div id="meta">
      <span id="loading"><img src="http://dl.dropbox.com/u/2654618/loading_black.gif" /></span>
      <table>
      	<tr>
      		<td>
      			Actualización:
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
