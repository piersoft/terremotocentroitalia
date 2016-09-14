<?php
$idpass="";
if ($_GET["id"]){
$idpass=$_GET["id"];
}

?>
<!DOCTYPE html>
<html lang="it">
	<head>
		<meta charset="utf-8">
		<title>Terremoto Centro</title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
		<meta property="og:image" content="http://www.piersoft.it/terremotocentro/terremotocentroitalia.png"/>
      <!-- Leaflet 0.5: https://github.com/CloudMade/Leaflet-->
		<link rel="stylesheet" href="http://joker-x.github.io/Leaflet.geoCSV/lib/leaflet.css" />
		<!--[if lte IE 8]> <link rel="stylesheet" href="http://joker-x.github.io/Leaflet.geoCSV/lib/leaflet.ie.css" />  <![endif]-->
		<script src="http://joker-x.github.io/Leaflet.geoCSV/lib/leaflet.js"></script>

		<!-- MarkerCluster https://github.com/danzel/Leaflet.markercluster -->
		<link rel="stylesheet" href="http://joker-x.github.io/Leaflet.geoCSV/lib/MarkerCluster.css" />
		<link rel="stylesheet" href="http://joker-x.github.io/Leaflet.geoCSV/lib/MarkerCluster.Default.css" />
		<!--[if lte IE 8]> <link rel="stylesheet" href="http://joker-x.github.io/Leaflet.geoCSV/lib/MarkerCluster.Default.ie.css" /> <![endif]-->
		<script src="http://joker-x.github.io/Leaflet.geoCSV/lib/leaflet.markercluster-src.js"></script>

		<!-- GeoCSV: https://github.com/joker-x/Leaflet.geoCSV -->
		<script src="leaflet.geocsv-src2.js"></script>
		<script src="leaflet-hash.js"></script>
    <script src="src/leaflet-search2.js"></script>
    <link rel="stylesheet" href="src/leaflet-search.css" />
		<!-- jQuery 1.8.3: http://jquery.com/ -->
		<script src="http://joker-x.github.io/Leaflet.geoCSV/lib/jquery.js"></script>

		<style>
		html, body, #mapa {
			margin: 0;
			padding: 0;
			width: 100%;
			height: 100%;
			font-family: Arial, sans-serif;
			font-color: #38383;
		}

    .search-input {
    	font-family:Courier
    }
    .search-input,
    .leaflet-control-search {
    	max-width:400px;
    }

		#botonera {
			position:fixed;
			top:10px;
			left:50px;
			z-index: 2;
		}

		#cargando {
			position:fixed;
			top:0;
			left:0;
			width:100%;
			height:100%;
			background-color:#666;
			color:#fff;
			font-size:2em;
			padding:20% 40%;
			z-index:10;
		}

		.boton {
			border: 1px solid #96d1f8;
			background: #65a9d7;
			background: -webkit-gradient(linear, left top, left bottom, from(#3e779d), to(#65a9d7));
			background: -webkit-linear-gradient(top, #3e779d, #65a9d7);
			background: -moz-linear-gradient(top, #3e779d, #65a9d7);
			background: -ms-linear-gradient(top, #3e779d, #65a9d7);
			background: -o-linear-gradient(top, #3e779d, #65a9d7);
			padding: 12px 24px;
			-webkit-border-radius: 10px;
			-moz-border-radius: 10px;
			border-radius: 10px;
			-webkit-box-shadow: rgba(0,0,0,1) 0 1px 0;
			-moz-box-shadow: rgba(0,0,0,1) 0 1px 0;
			box-shadow: rgba(0,0,0,1) 0 1px 0;
			text-shadow: rgba(0,0,0,.4) 0 1px 0;
			color: white;
			font-size: 17px;
			/*font-family: Helvetica, Arial, Sans-Serif;*/
			text-decoration: none;
			vertical-align: middle;
		}
		.boton:hover {
			border-top-color: #28597a;
			background: #28597a;
			color: #ccc;
		}
		.boton:active {
			border-top-color: #1b435e;
			background: #1b435e;
		}
#infodiv{
       position:fixed;
        right:2px;
        bottom:20px;
	font-size: 12px;
        z-index:9999;
        border-radius: 10px;
        -moz-border-radius: 10px;
        -webkit-border-radius: 10px;
        border: 2px solid #808080;
        background-color:#fff;
        padding:5px;
        box-shadow: 0 3px 14px rgba(0,0,0,0.4)
}
		</style>
	</head>
	<body>
		<div id="mapa"></div>
		<div id="cargando">Loading data...</div>

<div id="infodiv" style="leaflet-popup-content-wrapper">
<b>Mappa delle segnalazioni per il progetto <a href="http://terremotocentroitalia.info/">Terremoto del Centro Italia</a></b>
</div>

<script>
var cluster=new L.MarkerClusterGroup();
var dataLayer = new L.geoJson();
var dataLayer2 = new L.geoJson();
var idpass="<?php echo $idpass ?>";

$.ajax ({
	type:'GET',
	cache: false,
	dataType:'text',
	url:'https://raw.githubusercontent.com/emergenzeHack/terremotocentro/master/_data/issues.csv',
   error: function() {
     alert('Non riesco a caricare i dati');
   },
	success: function(csv) {


		//console.log('segnalazioni<?php echo $suff; ?>.csv');
  //  console.log(csv);
    var substring = "1";
    //console.log(csv.indexOf(substring) !== -1);
    if (csv.indexOf(substring) === -1) {
      alert('Non ci sono segnalazioni odierne');
    }else{
//		bankias.addData(csv);
//		cluster.addLayer(bankias);
	//	mapa.fitBounds(cluster.getBounds());
	//	mapa.addLayer(cluster);

  }
	},
   complete: function() {
      $('#cargando').delay(500).fadeOut('slow');
   }

});


var mapa = L.map('mapa', {attributionControl:true}).setView([42.6463, 13.3068], 11);
var prccEarthquakesLayer = L.tileLayer('http://{s}.tiles.mapbox.com/v3/bclc-apec.map-rslgvy56/{z}/{x}/{y}.png', {maxZoom: 8,
		attribution: 'Map &copy; Pacific Rim Coordination Center (PRCC).  Certain data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>'
	});
var osm=L.tileLayer('http://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png', {maxZoom: 19, attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>, Tiles <a href="http://hot.openstreetmap.org/" target="_blank">Humanitarian OpenStreetMap Team</a> powered by @piersoft'}).addTo(mapa);
var realvista = L.tileLayer.wms("http://213.215.135.196/reflector/open/service?", {
   layers: 'rv1',
   format: 'image/jpeg',attribution: '<a href="http://www.realvista.it/website/Joomla/" target="_blank">RealVista &copy; CC-BY-SA Tiles</a> | <a href="http://openstreetmap.org">OSM</a> powered by @piersoft'});
   var esi = L.tileLayer.wms("http://mapwarper.net/maps/wms/15512?request=GetCapabilities&service=WMS&version=1.1.1?", {
      layers: 'rv1',
      format: 'image/jpeg',attribution: '<a href="http://mapwarper.net/maps/15512#Preview_Map_tab" target="_blank">MapWraper with image uploaded by Napo from ESI<b>after</b> the earthquake</a> | <a href="http://openstreetmap.org">OSM</a> powered by @piersoft'});



   var overlays = {
       "<font style=\"color: #ffffff; background-color: red\">Segnalazioni utenti</font>": dataLayer2,
       "<font style=\"color: #ffffff; background-color: blue\">Unità Comando Locale</font>":dataLayer
     };

 var layerControl = new L.Control.Layers({
 		'Humanitarian OSM Team': osm,
 		'Satellite pre Terremoto': realvista,
    'ESI post Terremoto': esi,
    'EarthquakesLayer':prccEarthquakesLayer
 	},overlays,{collapsed: false},{position: 'topright'});

 layerControl.addTo(mapa);
 dataLayer.addTo(mapa);
 dataLayer2.addTo(mapa);

var hash = new L.Hash(mapa);
mapa.addControl( new L.Control.Search({
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
var customicon = 'pinrossoe.png';


function onEachFeature(feature, layer) {
    // does this feature have a property named popupContent?
    var popupString = '<div class="popup">';
                        for (var k in feature.properties) {
                            var v = feature.properties[k];
                            popupString += '<b>'+k + '</b>: ' + v + '<br />';
                        }
      popupString += '</div>';
      layer.bindPopup(popupString);
};

function addDataToMapUCL(data, mapa) {
  var dataLayer1 = L.geoJson(data,{
        onEachFeature: function(feature, layer) {
          var popupString = '<div class="popup">';
            popupString += '<b>Nome: </b>: ' + feature.properties.NAME + '<br />';
            popupString += '<b>Posizione: </b>: ' + feature.properties.POSIZIONE + '<br />';
            popupString += '<b>Recapito: </b>: ' + feature.properties.RECAPITO + '<br />';
            popupString += '<b>Mail: </b>: ' + feature.properties.MAIL + '<br />';

                            //  for (var k in feature.properties) {
                            //      var v = feature.properties[k];
                            //      popupString += '<b>'+k + '</b>: ' + v + '<br />';
                            //  }
            popupString += '</div>';
            layer.bindPopup(popupString);

          }
        });
    dataLayer1.addTo(dataLayer);
}

$.getJSON("zone_competenza_ucl.geojson", function(data) { addDataToMapUCL(data, mapa); });


function addDataToMap(data, mapa) {
  var dataLayer1 = L.geoJson(data,{
        onEachFeature: function(feature, layer) {
      var popup = '<div class="popup">';
      popup +='<b>ID:</b> '+feature.properties.id+'</br>';
      if (feature.properties.image !=""){
      popup +='<a href="'+feature.properties.image+'" target="_blank"><img src="'+feature.properties.image+'" width="200"></a>';
      popup +='</br>(<i>clicca l\'immagine per ingrandirla</i>)<br />';
      }
      popup += '<b>Descrizione:</b><br />'+feature.properties.title+'<br />';
      popup += '<b>inviata alle: </b>'+feature.properties.created_at+'<br />';
      popup += '<b>aggiornata alle: </b>'+feature.properties.updated_at+'<br />';
      if (feature.properties.body !=""){
      //  console.log(feature.properties.body);
      var firstvariable = "telegram_user:";
      var secondvariable = "descrizione"
      var regExString = "(?="+firstvariable+").*?(?="+secondvariable+")";
      var test = feature.properties.body;
      var testRE = test.match(regExString);

      console.log("new text: " + testRE);

  if (testRE !=null)  popup += '<b>dall\'utente Telegram: </b><a href="http://telegram.me/'+testRE+'" target="_blank">'+testRE+'</a><br />';
      }
      if (feature.properties.url !=""){
      popup += '<a href="'+feature.properties.url+'" target="_blank"><b>Segui la segnalazione</b></a><br />';
      }
            popup += '</div>';
            layer.bindPopup(popup);
            if (idpass !=""){

              if (feature.properties.id == idpass){
                mapa.setView([feature.geometry.coordinates[1],feature.geometry.coordinates[0]],17)

                mapa.on("zoomend", function(){

                layer.bindPopup(popup).openPopup();
                console.log(idpass + " "+feature.properties.id);
                layer.bindPopup(popup).openPopup();
              });
              mapa.setView([feature.geometry.coordinates[1],feature.geometry.coordinates[0]],18)

              }
            }

          },

        	pointToLayer: function (feature, latlng) {

        		return L.marker(latlng, {
        			icon:L.icon({
        				iconUrl: customicon,
        				shadowUrl: 'marker-shadow.png',
        				iconSize: [20,20],
        				shadowSize:   [30, 30],
        				shadowAnchor: [10, 18]
        			})
        		});
        	},
        	firstLineTitles: true,

        });
    dataLayer1.addTo(dataLayer2);
}
$.getJSON("http://www.piersoft.it/terremotocentro/get.php", function(data) { addDataToMap(data, mapa); });



dataLayer.setZIndex(1);
dataLayer2.setZIndex(2);
</script>

	</body>
</html>
