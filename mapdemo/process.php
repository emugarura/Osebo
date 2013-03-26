<?php 
require_once '../functions.php';
$indicator = (int)$_GET['indicator'];
$info = $db->query("SELECT * FROM indicators WHERE id = $indicator");
$legends = $db->query("SELECT * FROM legends WHERE indicator = $indicator");
$meta_info = $db->query("SELECT * FROM legends_meta WHERE indicator = $indicator");

if (!$legends->num_rows) {
  if ($info->subcategory) {
    $legends = $db->query("SELECT * FROM legends WHERE indicator = {$info->subcategory}");
    $meta_info = $db->query("SELECT * FROM legends_meta WHERE indicator = {$info->subcategory}");
    if (!$legends->num_rows) {
      $retry = true;
    }
  }
  if (!$info->subcategory && $info->category || $retry && $info->category) {
    $legends = $db->query("SELECT * FROM legends WHERE indicator = {$info->category}");
    $meta_info = $db->query("SELECT * FROM legends_meta WHERE indicator = {$info->category}");
  }
}
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

$counter = $db->query("SELECT regions.id, year,gender,value,regions.name AS region,
  regions.coordinates
FROM indicatorpoints AS i 
JOIN regions ON i.region = regions.id
WHERE i.indicator = $indicator 
  $sql");
$random = rand(0,100);
$kml_url = URL . "mapdemo/kmz.php?random=$random&" . $_SERVER['QUERY_STRING'];

$result = array(
	"kml_url_1" => $kml_url . "&part=1",
  "top_legend" => $meta_info->label ? $meta_info->label : '',
  "bottom_legend" => $meta_info->description ? $meta_info->description : "",
);
if ($counter->num_rows > 100) {
	$result["kml_url_2"] = $kml_url . "&part=2";
}
while ($row = $legends->fetch()) {
  $result["legends"][$row['id']] = 
    array(
      'color' => "#" . $row['color'],
      'value' => $row['label'] ? $row['label'] : "From " . $row['from'] . " to " . $row['to'],
    );
}

echo json_encode($result)

?>
