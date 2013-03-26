<?php
require_once '../functions.php';
$get = $_SERVER['QUERY_STRING'];
$content = file_get_contents(URL . "mapdemo/kml.php?" . $get);

$file = PATH . "media/map.kmz";

$zip = new ZipArchive;
$res = $zip->open($file, ZipArchive::CREATE);
$zip->addFromString('map.kml', $content);
$zip->close();
header('Content-type: application/vnd.google-earth.kmz');
header('Content-Length: ' . filesize($file));
readfile($file);
unlink($file); 

?>
