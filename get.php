<?php
$indirizzo="http://emergency.geosdi.org/geoserver/terremotocentroitalia/ows?service=WFS&version=1.0.0&request=GetFeature&typeName=terremotocentroitalia:telegram_bot_mirror_new&maxFeatures=5000&outputFormat=application%2Fjson";
$homepage=file_get_contents($indirizzo);
echo $homepage;
 ?>
