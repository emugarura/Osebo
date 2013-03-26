<?php
require_once 'functions.php';
$data = $_POST['data'];
$map = (int)$_GET['map'];
$indicators = $db->query("SELECT * FROM indicators WHERE map = $map ORDER BY id");
if ($_POST) {
  $print = "Data has been imported<br />";
  $explode = explode("\n", $data);
  foreach ($explode as $value) {
    $information = explode("\t", $value);
    $information[$key] = trim($value);
    $region = html($information[0]);
    $get_region = $db->query("SELECT * FROM regions WHERE name = $region LIMIT 1");
    if ($get_region->num_rows) {
      $region = $get_region->id;
      $count = 0;
      while ($row = $indicators->fetch()) {
        $count++;
        $indicator = $row['id'];
        if ($information[$count] != 0) {
          $post = array(
            'indicator' => $indicator,
            'region' => $region,
            'value' => $information[$count],
          );
          $db->insert("indicatorpoints",$post);
          $success ++;
        }
      }
      $indicators->reset();
    } else {
      $print .= "This region was not recognized: $region<br />";
    }
  }
  $print .= "In total, $success records have been successfuly imported <br />";
}
?>
<!doctype html>
<!--[if lt IE 7]><html class="no-js lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if IE 7]><html class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if IE 8]><html class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--><html class="no-js"><!--<![endif]-->
<head>
<?php echo $head ?>
<title>Data Loader | <?php echo SITENAME ?></title>
</head>
<style type="text/css">
textarea{width:600px;height:800px;}
</style>
<body>
<div id="wrapper" class="container">

  <?php require_once 'include.header.php'; ?>
  
  <div id="content">
    <h2>Data Loader</h2>
    <div id="content-wrap">

      <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>
      <form method="post">
        <p><label>Data</label><textarea name="data"
        placeholder="Please copy and paste the data here. Use this column order:
<?php while ($row = $indicators->fetch()) { echo $row['indicator'] . " "; } ?>"></textarea></p>
        <div class="form-actions">
          <button type="submit" class="btn btn-primary">
            Save
          </button>
        </div>
      </form>
    

    </div>
  </div>

  <?php require_once 'include.footer.php'; ?>

</div>
<script src="js/script.js"></script>
</body>
</html>
