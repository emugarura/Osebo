<?php
require_once 'functions.php';

$file = file_get_contents("masterlist.csv");

$explode = explode("\n", $file);

foreach ($explode as $value) {
  $string = explode(",", $value);
  $name = $string[0];
  $string = explode("<coordinates>", $value);
  $string = explode("</coordinates>", $string[1]);
  $coordinates = $string[0];
  $name = mysql_clean($name);
  $check = $db->query("SELECT id FROM regions WHERE name = $name");
  if ($check->id) {
    $post = array(
      'coordinates' => mysql_clean($coordinates),
    );
    $db->update("regions",$post,"id = {$check->id}");
  }
}

?>
