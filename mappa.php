<?php
?>
<!DOCTYPE html>
<html>
<head>
    <title>Geocoder</title>
    <meta charset="utf-8" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="http://joker-x.github.io/Leaflet.geoCSV/lib/leaflet.css" />
    <!--[if lte IE 8]> <link rel="stylesheet" href="http://joker-x.github.io/Leaflet.geoCSV/lib/leaflet.ie.css" />  <![endif]-->
    <script src="http://joker-x.github.io/Leaflet.geoCSV/lib/leaflet.js"></script>

    <!-- MarkerCluster https://github.com/danzel/Leaflet.markercluster -->
    <link rel="stylesheet" href="http://joker-x.github.io/Leaflet.geoCSV/lib/MarkerCluster.css" />
    <link rel="stylesheet" href="http://joker-x.github.io/Leaflet.geoCSV/lib/MarkerCluster.Default.css" />
    <!--[if lte IE 8]> <link rel="stylesheet" href="http://joker-x.github.io/Leaflet.geoCSV/lib/MarkerCluster.Default.ie.css" /> <![endif]-->
    <script src="http://joker-x.github.io/Leaflet.geoCSV/lib/leaflet.markercluster-src.js"></script>


    <script src="src/leaflet-search.js"></script>
    <link rel="stylesheet" href="src/leaflet-search.css" />
    <!-- jQuery 1.8.3: http://jquery.com/ -->
    <script src="http://joker-x.github.io/Leaflet.geoCSV/lib/jquery.js"></script>


 <style>
 .search-input {
 	font-family:Courier
 }
 .search-input,
 .leaflet-control-search {
 	max-width:400px;
 }
 </style>

    <style type="text/css">
        #maplecce {

        }
 #map {
           position:fixed;
        top:0;
        right:0;
        left:0;
        bottom:0;
        }
    </style>
</head>
<body>

    <div id="map" ></div>
  <script>

           var map = L.map('map', {
            revealOSMControl: true,
            revealOSMControlOptions: {
                queryTemplate: '[out:json];(node(around:{radius},{lat},{lng})[name];way(around:{radius},{lat},{lng})[name][highway];);out body qt 1;'
            },
            zoomControl: true
        }).setView([42.6297405,13.2896061], 16);

        var osm=L.tileLayer('http://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png', {maxZoom: 19, attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>, Tiles <a href="http://hot.openstreetmap.org/" target="_blank">Humanitarian OpenStreetMap Team</a> powered by @piersoft'}).addTo(map);
        var esi = L.tileLayer.wms("http://mapwarper.net/maps/wms/15512?request=GetCapabilities&service=WMS&version=1.1.1?", {
           layers: 'rv1',
           format: 'image/jpeg',attribution: '<a href="http://mapwarper.net/maps/15512#Preview_Map_tab" target="_blank">MapWraper with image uploaded by Napo from ESI<b>after</b> the earthquake</a> | <a href="http://openstreetmap.org">OSM</a> powered by @piersoft'});

     map.addControl( new L.Control.Search({
			url: 'http://nominatim.openstreetmap.org/search?format=json&q={s}',
			jsonpParam: 'json_callback',
			propertyName: 'display_name',
			propertyLoc: ['lat','lon'],
			circleLocation: true,
			markerLocation: false,
			autoType: false,
			autoCollapse: true,
			minLength: 2,
			zoom:18
		}) );


 var realvista = L.tileLayer.wms("http://213.215.135.196/reflector/open/service?", {
		layers: 'rv1',
		format: 'image/jpeg',attribution: '<a href="http://www.realvista.it/website/Joomla/" target="_blank">RealVista &copy; CC-BY Tiles</a> | <a href="http://openstreetmap.org">OSM</a> contr.s'
	});


myIcon = L.icon({
  iconUrl: 'pinverde.png',
iconSize: [32,32]
});


 var marker = L.marker([42.6297405,13.2896061],
        {draggable: true, icon:myIcon}   );
	       marker.on('dragend', function(event){
            var marker = event.target;
            var position = marker.getLatLng();
            var addr='http://nominatim.openstreetmap.org/reverse?format=json&email=piersoft2@gmail.com&lat='+position.lat+'&lon=' + position.lng+'&zoom=18&addressdetails=1';

          //  addr_search(position.lat,position.lng);
            $.getJSON(addr, function(data) {
              var indirizzo=data.display_name;
              console.log(indirizzo);
              console.log(position.lat+','+position.lng);
              document.getElementById("#us3-address").value = indirizzo;
              document.getElementById("#us3-lat").value = position.lat;
              document.getElementById("#us3-lon").value = position.lng;
            });
   });
    map.addLayer(marker);


var layerControl = new L.Control.Layers({
		'OSM Humanitarian': osm,
		'Satellite pre terremoto': realvista,
    'Satellite post terremoto': esi
	}, null, {position: 'topright'});

layerControl.addTo(map);
    </script>

</body>
</html>
