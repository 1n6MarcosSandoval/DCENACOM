function CambiarRelieve() {
    var baseMapLayer = new esri.layers.ArcGISTiledMapServiceLayer("http://services.arcgisonline.com/arcgis/rest/services/World_Shaded_Relief/MapServer");
    map.addLayer(baseMapLayer);
}
function CambiarSatelital() {
    var baseMapLayer = new esri.layers.ArcGISTiledMapServiceLayer("http://services.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer");
    var statesLayer = new esri.layers.ArcGISTiledMapServiceLayer("http://services.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer");
    map.addLayers([baseMapLayer, statesLayer]);
}