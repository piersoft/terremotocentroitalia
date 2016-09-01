<?php
$suff="";
$string="";
  $file ='/usr/www/piersoft/terremotocentro/segnalazioni.csv';
if ($_GET["giorno"]=="oggi"){
	$string="&giorno=oggi";
	$suff="1";
	$file = '/usr/www/piersoft/terremotocentro/segnalazioni1.csv';
}
	$indirizzo ="https://terremotocentroitalia.mmilesi.ml/?get-csv=false".$string;
  $homepage1 = file_get_contents($indirizzo);
//	$homepage1=str_replace("lon","lng",$homepage1);
	$homepage1=str_replace("\",\"",";",$homepage1);
	$homepage1=str_replace("\"","",$homepage1);

  file_put_contents($file, $homepage1);
//echo $homepage1;
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
		<script src="leaflet.geocsv-src.js"></script>
		<script src="leaflet-hash.js"></script>
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
</br>Segnalazioni <a href="index.php?giorno=oggi"><b>ODIERNE</b></a> o <b><a href="index.php">PROGRESSIVE</a></b> Map by @piersoft
</div>

<script>

$.ajax ({
	type:'GET',
	cache: false,
	dataType:'text',
	url:'segnalazioni<?php echo $suff; ?>.csv',
   error: function() {
     alert('Non riesco a caricare i dati');
   },
	success: function(csv) {
      var cluster = new L.MarkerClusterGroup();
		console.log('segnalazioni<?php echo $suff; ?>.csv');
		bankias.addData(csv);
		cluster.addLayer(bankias);
		mapa.fitBounds(cluster.getBounds());
		mapa.addLayer(cluster);

	},
   complete: function() {
      $('#cargando').delay(500).fadeOut('slow');

   }

});


//;$(function() {

var mapa = L.map('mapa', {attributionControl:true}).setView([40.46, -3.75], 5);

L.tileLayer('http://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png', {maxZoom: 19, attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>, Tiles <a href="http://hot.openstreetmap.org/" target="_blank">Humanitarian OpenStreetMap Team</a>'}).addTo(mapa);
var hash = new L.Hash(mapa);
var customicon = 'pinrossoe.png';

var bankias = L.geoCsv(null, {
	onEachFeature: function (feature, layer) {
		var popup = '';
popup +='<b>ID:</b> '+feature.properties.id+'</br>';
popup +='<img src="'+feature.properties.url+'" width="200">';
popup += '<br /><b>Descrizione:</b><br />'+feature.properties.description+'<br />';
popup += '<b>inviata alle: </b>'+feature.properties.time+'<br />';
if (feature.properties.telegram_username !=""){
popup += '<b>dall\'utente: </b><a href="http://telegram.me/'+feature.properties.telegram_username+'" target="_blank">'+feature.properties.telegram_username+'</a><br />';
}
if (feature.properties.github_issue !=""){
popup += '<a href="'+feature.properties.github_issue+'" target="_blank"><b>Segui la segnalazione</b></a><br />';
}
		for (var clave in feature.properties) {

		//	var title = bankias.getPropertyTitle(clave);
		//	popup += '<b>'+title+'</b><br />'+feature.properties[clave]+'<br /><br />';

		}
		layer.bindPopup(popup);
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
	firstLineTitles: true
});


//});
</script>

	</body>
</html>
