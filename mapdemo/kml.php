<?php
require_once '../functions.php';
header('Content-type: application/vnd.google-earth.kml+xml');
$indicator = (int)$_GET['indicator'];
$year = (int)$_GET['year'];
$gender = $_GET['gender'];

if ($year) {
  $sql .= " AND year = $year";
}
if ($gender) {
  $sql .= " AND gender = " . mysql_clean($_GET['gender']);
} else {
  $sql .= " AND gender IS NULL ";
}
$part = (int)$_GET['part'];
$offset = $part == 2 ? 100 : 0;

$list = $db->query("SELECT regions.id, year,gender,value,regions.name AS region,
  regions.coordinates
FROM indicatorpoints AS i 
JOIN regions ON i.region = regions.id
WHERE i.indicator = $indicator 
  $sql
GROUP BY regions.id
ORDER BY year, gender, regions.name, i.id
LIMIT 100 OFFSET $offset
");

$info = $db->query("SELECT * FROM indicators WHERE id = $indicator");

$legends = $db->query("SELECT * FROM legends WHERE indicator = $indicator");

if (!$legends->num_rows) {
  if ($info->subcategory) {
    $legends = $db->query("SELECT * FROM legends WHERE indicator = {$info->subcategory}");
    if (!$legends->num_rows) {
      $retry = true;
    }
  }
  if (!$info->subcategory && $info->category || $retry && $info->category) {
    $legends = $db->query("SELECT * FROM legends WHERE indicator = {$info->category}");
  }
}

?>
<?xml version="1.0" encoding="UTF-8"?>
<kml xmlns="http://www.opengis.net/kml/2.2" xmlns:gx="http://www.google.com/kml/ext/2.2" xmlns:kml="http://www.opengis.net/kml/2.2" xmlns:atom="http://www.w3.org/2005/Atom">
<Document>
	<name>Regions</name>
	<open>1</open>
	<description><![CDATA[Map: <?php echo $info->name ?>]]></description>

	<!-- style tag contains style specifications for a polygon -->
	<Style id="unclassified">
		<LineStyle>
			<color>00333333</color>
      <width>1</width>
		</LineStyle>
		<PolyStyle>
			<!-- remember color format is alpha blue green red -->
			<color>e0abab33</color>
		</PolyStyle>
	</Style>

  <?php while ($row = $legends->fetch()) { $legend_range[$row['id']] = array($row['from'], $row['to']); ?>
    <Style id="legend<?php echo $row['id'] ?>">
      <LineStyle>
        <color>e0333333</color>
        <width>1</width>
      </LineStyle>
      <PolyStyle>
        <!-- remember color format is alpha blue green red -->
        <color>e0<?php $c = $row['color'];
        echo $c[4] . $c[5] . $c[2] . $c[3] . $c[0] . $c[1];
        ?></color>
      </PolyStyle>
    </Style>
  <?php } ?>

	<!-- next style goes here -->
	
	<!-- placemark contains a polygon and its coordinates -->
  <?php while ($row = $list->fetch()) { ?>
  <?php
    $legend = false;
    foreach ($legend_range as $key => $value) {
      $min = $value[0];
      $max = $value[1];
      if ($row['value'] >= $min && $row['value'] <= $max) {
        $legend = "legend" . $key;
      }
    }
  ?>
	<Placemark>
		<name><?php echo $row['region'] ?></name> <!-- region name -->
    <description><![CDATA[<?php echo $row['value'] ?>]]></description>
		<styleUrl>#<?php echo $legend ? $legend : "unclassified"; ?></styleUrl>
		<Polygon>
			<outerBoundaryIs>
				<LinearRing>
					<tessellate>1</tessellate>
					<coordinates><?php echo $row['coordinates'] ?></coordinates>
				</LinearRing>
			</outerBoundaryIs>
		</Polygon>
	</Placemark>
  <?php } ?>
	
	<!-- you repeat a placemark for every region / district -->
</Document>
</kml>
