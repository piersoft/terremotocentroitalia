<?php
function write($file,$string){
$fh = fopen($file, "w");
       if($fh==false)
           die("unable to create file");
       fputs ($fh, $string,strlen($string));
       echo $string;
       $success = fclose($fh);

   if ($success) {
      //  echo "File successfully closed!\n";
   } else {
        echo "Error on closing!\n";
   }

}

$indirizzo="https://raw.githubusercontent.com/emergenzeHack/terremotocentro/master/_data/issues.csv";
//$homepage=file_get_contents($indirizzo);
//echo $homepage;


$csv1 = array_map('str_getcsv', file($indirizzo));
//echo $csv1[0][0];
$count1 = 0;
$i=0;
$features = array();

foreach($csv1 as $csv11=>$data1){

  if(strpos($csv1[$csv11][6],'.') !== false){
//  echo $csv1[$csv11][6];
  $count1 = $count1+1;


  if ($count1 >2)
  $features[] = array(
          'type' => 'Feature',
          'geometry' => array('type' => 'Point', 'coordinates' => array((float)$data1[6],(float)$data1[5])),
          'properties' => array('url' => $data1[0],'id' => $data1[1], 'updated_at' => $data1[2],'created_at' => $data1[3],'title' =>$data1[4],'labels' =>$data1[7],'milestone' =>$data1[8],'image' =>$data1[9],'body' =>json_encode($data1[11]),'state' =>$data1[12])
          );
}
}
$allfeatures = array('type' => 'FeatureCollection', 'features' => $features);
$geostring=json_encode($allfeatures, JSON_PRETTY_PRINT);
//echo $stop_id." ".$stop_code." ".$stop_name." ".$stop_desc." ".$lat." ".$lon;
echo $geostring;


//$file = "json/mappaf.json";
//write($file,$geostring);


 ?>
