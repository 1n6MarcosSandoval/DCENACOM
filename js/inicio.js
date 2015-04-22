var map, basemapGallery, home, geoLocate, ventanaBoletin;
var visible = []; var today = new Date(); var count = 6; var globalCount = 0;

$(document).ready(function(){	
	dojo.ready(init);
	
				/*ventanaBoletin = $("#boletin").kendoWindow({
        actions: ["Close"],
        draggable: true,
        modal: false,
        resizable: false,
        width: 420,
		maxHeight: 280,
		position: {
    top: 340,
    left: 880
  },
        title: "Boletín Informativo"
    }).data("kendoWindow");*/
});

function init() {
//    esri.config.defaults.io.corsEnabledServers.push("sampleserver1a.arcgisonline.com");
//    esri.config.defaults.io.corsEnabledServers.push("sampleserver6.arcgisonline.com");
    var popupOptions = {
        'marginLeft': '20',
        'marginTop': '20'
    };
//    var popup = new esri.dijit.Popup(popupOptions, dojo.create("div"));
//    var startExtent = new esri.geometry.Extent({ "xmin": -13685209.43, "ymin": 1316856.96, "xmax": -9282436.60, "ymax": 4253038.85, "spatialReference": { "wkid": 102100} });
//    map = new esri.Map("map", {
//        basemap: "hybrid",
//        extent: startExtent,
//        logo: false,
//        slider: true,
//        sliderPosition: "top-left",
//        sliderStyle: "large",
//        showAttribution: false,
//        fadeOnZoom: true,
//        force3DTransforms: true,
//        navigationMode: "css-transforms",
//        infoWindow: popup
//    });

    map = new esri.Map("map", {
        center: [-56.049, 38.485],
        zoom: 3,
        basemap: "streets"
    });

    //municipios();
    CrearPoint();
	dojo.connect(map, "onLoad", function () {
		dojo.connect(dijit.byId("map"), "resize", map, map.resize);
		//eventosRecientes();
		timer();
	});

    dojo.connect(map, "onUpdateStart", function () {
        esri.show(dojo.byId("loading"));
        map.disableMapNavigation();
        map.disablePan();
        return false;
    });

    dojo.connect(map, "onUpdateEnd", function () {
        esri.hide(dojo.byId("loading"));
        map.enableMapNavigation();
        map.enablePan();
        return false;
    });
}

function addBasemapGalleryMenu(){
	 var cp = new dijit.layout.ContentPane({
        id: 'basemapGallery',
        style: "max-height:448px;width:380px;"
    });
	
	var basemapGallery = new esri.dijit.BasemapGallery({
        showArcGISBasemaps: true,
        map: map
    }, dojo.create('div'));
	
	cp.set('content', basemapGallery.domNode);
	
	var button = new dijit.form.DropDownButton({
        label: "Menu Mapas",
        id: "basemapBtn",
        iconClass: "esriBasemapIcon",
        dropDown: cp
    });

    dojo.byId('webmap-toolbar-center').appendChild(button.domNode);

    dojo.connect(basemapGallery, "onSelectionChange", function () {
        //close the basemap window when an item is selected
        dijit.byId('basemapBtn').closeDropDown();
    });
	
    basemapGallery.startup();
	
}

function navigateStack(label) {
    //display the left panel if its hidden
    showLeftPanel();

    //select the appropriate container 
    dijit.byId('stackContainer').selectChild(label);

    //toggleToolbarButtons(buttonLabel);
}

function showLeftPanel() {
    //display the left panel if hidden
    var leftPaneWidth = dojo.style(dojo.byId("leftPane"), "width");
    if (leftPaneWidth === 0) {
        if (false) {
            dojo.style(dojo.byId("leftPane"), "width", 250);
        } else {
            dojo.style(dojo.byId("leftPane"), "width", 250 + "px");
        }
        dijit.byId("mainWindow").resize();
    }
}

function hideLeftPanel() {
    //close the left panel when x button is clicked
    var leftPaneWidth = dojo.style(dojo.byId("leftPane"), "width");
    if (leftPaneWidth === 0) {
        leftPaneWidth = 250;
    }
    dojo.style(dojo.byId("leftPane"), "width", "0px");
    dijit.byId('mainWindow').resize();
    //resizeMap();
    //uncheck the edit, detail and legend buttons
}

function eventosRecientes(){
	var fechaActual=kendo.toString(today,"yyyy-MM-dd");
	
	var graphicsLayer = new esri.layers.GraphicsLayer();
	map.addLayer(graphicsLayer);
    var queryTask = new esri.tasks.QueryTask("http://anr.losmapas.info:6080/arcgis/rest/services/ServiciosTiempoReal/SismosTiempoReal/FeatureServer/0");
    var query = new esri.tasks.Query();
    query.returnGeometry = true;
    query.outSpatialReference = map.spatialReference;
    query.outFields = ['*'];
	query.orderByFields = ["HORA DESC"];
    query.where = "FECHA > '2014-06-19' AND HORA > '00:00:00'";

    queryTask.execute(query);

    dojo.connect(queryTask, "onComplete", function (featureSet) {

		var consulta = featureSet.features;
		 var symbol = new esri.symbol.SimpleMarkerSymbol(esri.symbol.SimpleMarkerSymbol.STYLE_CIRCLE, 13, new esri.symbol.SimpleLineSymbol(esri.symbol.SimpleLineSymbol.STYLE_SOLID, new dojo.Color([0, 0, 0, 1]), 2), new dojo.Color([255, 255, 0, 1]));
		for (var i = 0; i < 10; i++) {
			var graphic = consulta[i];
			graphic.setSymbol(symbol);
            graphicsLayer.add(graphic);
		}
        datosVentanaEventos(consulta);
    });
	 }
	 
function datosVentanaEventos(response) {
    var arreglo = [];
    for (var i = 0; i < 10; i++) {
        arreglo.push(response[i]);
    }
	var dataSource = new kendo.data.DataSource({
        data: arreglo
    });

    $("#listView").kendoListView({
        dataSource: dataSource,
        selectable: "single",
        template: kendo.template($("#template").html()),
        change: function () {
            var data = dataSource.view(),
                    selected = $.map(this.select(), function (item) {
                        return data[$(item).index()].attributes.GUID;
                    });
            item = selected.join(", ");
            mostrar(item);			
        }
    });
}

function mostrar(evento) {
    try{
        map.graphics.clear();
        dojo.require("esri.tasks.geometry");
        var hour;
        if (globalCount == 0) {
            hour = 9;
            globalCount2 = 2;
        }
        else if (globalCount == 1) {
            hour = 3;
            globalCount2 = 0;
        }
        else if (globalCount == 2) {
            hour = 6;
            globalCount2 = 1;
        }
        var queryTask = new esri.tasks.QueryTask("http://proyectomexico.com/ArcGIS/rest/services/MunicipiosPrecipitacion/MapServer/" + globalCount2);
        var query = new esri.tasks.Query();
        query.returnGeometry = true;
        query.outSpatialReference = map.spatialReference;
        query.geometry = evento.mapPoint;
        query.outFields = ["FID", "MUNICIPIO", "Precipitac"];
        //     query.where = "FID=" + 44 + "";
        query.where = "1=1";
        queryTask.execute(query);

        var infoTemplate = new esri.InfoTemplate();
        infoTemplate.setTitle("EVENTO: ${FID}");
        var d = new Date();
        var day = d.getDate();
        var month = d.getMonth() + 1;
        var year = d.getFullYear();
        var infoTemplate = new esri.InfoTemplate();
        infoTemplate.setTitle("EVENTO: ${FID}");
        var d = new Date();
        var day = d.getDate();
        var month = d.getMonth() + 1;
        var year = d.getFullYear();


        infoTemplate.setContent(
               "<b>MUNICIPIO: </b>${MUNICIPIO}<br/>"
               + "<b>PRECIPITAC: </b>${Precipitac}<br/>"
               + "<b>FECHA/HORA: </b>" + day + "/" + month + "/" + year + " - " + hour + ":00<br/>");
        map.infoWindow.resize(350, 100);

        dojo.connect(queryTask, "onComplete", function (featureSet) {
            var consulta = featureSet.features;
            var symbol = new esri.symbol.SimpleMarkerSymbol(esri.symbol.SimpleMarkerSymbol.STYLE_CIRCLE, 12, new esri.symbol.SimpleLineSymbol(esri.symbol.SimpleLineSymbol.STYLE_DASHDOT, new dojo.Color([0, 0, 0, 0]), 5), new dojo.Color([0, 0, 255, 1]));
            for (var i = 0; i < consulta.length; i++) {
                // window.alert(consulta.length.toString());
                var graphic = consulta[i];
                graphic.setSymbol(symbol);
                graphic.setInfoTemplate(infoTemplate);
                map.graphics.add(graphic);
                //map.centerAndZoom(graphic.geometry, 9);
            }
            //ventanaBoletin.open();
            //$("#boletin").show();
        });

        dojo.connect(queryTask, "onError", function (error) {
            window.alert(error);
        });
    }
    catch (err) {
        window.alert(err.message);
    }
}

function municipios() {
	     var layerMunicipios = new esri.layers.FeatureLayer("http://proyectomexico.com/ArcGIS/rest/services/MunicipiosPrecipitacion/MapServer/" + globalCount);
	     map.addLayer(layerMunicipios);
	     //dojo.connect(layerMunicipios, 'onClick', function () {
	     //                mostrar('44');
    //});
	     dojo.connect(layerMunicipios, 'onClick', mostrar);

	     
	     //window.alert(globalCount);
	     if (globalCount == 0) {
	         globalCount = 1;
	     }
	     else if (globalCount == 1) {
	         globalCount = 2;
	     }
	     else if (globalCount == 2) {
	         globalCount = 0;
	     }
}
function timer() {
	 setInterval(function(){
	     count = count - 1;
			if (count < 0)
			{					
			    count = 6;
			    for (var j = 0, jl = map.graphicsLayerIds.length; j < jl; j++) {
			        currentGraphicsLayer = map.getLayer(map.graphicsLayerIds[j]);
			        map.removeLayer(currentGraphicsLayer);
			    }
				municipios();
				//var listView = $("#listView").data("kendoListView");
				//var dataSource = new kendo.data.DataSource({
				//	data: [ ]
				//});
				//listView.setDataSource(dataSource);
				//listView.refresh();
				
				//eventosRecientes();
				return false;
			}

//			document.getElementById("timer").innerHTML="Próxima actualización en: " + count + " segundos"; // watch for spelling
		},1000);
}


function CrearPoint() {
        require([
       "esri/map", "esri/geometry/Point", "esri/symbols/PictureMarkerSymbol", "esri/graphic"
    ],
    function (Map, Point, PictureMarkerSymbol, Graphic) {
        var pt = new Point({ "x": -122.65, "y": 45.53, "spatialReference": { "wkid": 4326} });
        var markerSymbol = new PictureMarkerSymbol('img/Simbolos/Alertas_rojo.gif', 30, 30);
        var graphic = new Graphic(pt, markerSymbol);
        map.graphics.add(graphic);
    });
}