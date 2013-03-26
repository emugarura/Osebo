<?php 

$colors = array("41961A","6AD9A6","BFFFFF","61AEFD","1C19D7");

$kml = file_get_contents("kml/districs.kml");

$polygons = explode("<Placemark>",$kml);
echo array_shift($polygons);

foreach($polygons as $key => $polygon) {
	$n = rand(1,5) - 1;
	echo '<Style id="Basic_'.$key.'_Style">
	<LineStyle>
		<color>4c000000</color>
		<width>1</width>
	</LineStyle>
	<PolyStyle>
		<color>e6'.$colors[$n].'</color>
	</PolyStyle>
</Style>'."\n";
}

foreach($polygons as $key => $polygon) {
	$polygon = str_replace("#BasicStyle","#Basic_".$key."_Style",$polygon);
	echo "<Placemark>$polygon \n";
}

?>
