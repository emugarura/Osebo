<?php
require_once 'functions.php';
$data = $_POST['data'];
$indicator = (int)$_GET['indicator'];

if ($_POST) {
  $print = "Data has been imported<br />";
  $explode = explode("\n", $data);
  foreach ($explode as $value) {
    $information = explode("\t", $value);
    $information[$key] = trim($value);
    $region = html($information[0]);
    $get_region = $db->query("SELECT * FROM regions WHERE name = $region LIMIT 1");
    if (!$get_region->num_rows) {
      $get_region = $db->query("SELECT region AS id FROM region_alias WHERE name = $region LIMIT 1");
    }
    if ($get_region->num_rows) {
      $region = $get_region->id;
      $count = 0;
      $data = (float)$information[1];
      if ($data != 0) {
        $post = array(
          'indicator' => $indicator,
          'region' => $region,
          'year' => (int)$_POST['year'],
          'gender' => $_POST['gender'] ? mysql_clean($_POST['gender']) : "NULL",
          'value' => $data,
        );
        $db->insert("indicatorpoints",$post);
        $success ++;
      }
    } else {
      $print .= "This region was not recognized: $region<br />";
    }
  }
  $print .= "In total, $success records have been successfuly imported <br />";
}
$years = range(date("Y"),2008);
$genders = array("M", "F");

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

    <?php require_once 'breadcrumbs.php'; ?>

      <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>
      <form method="post">
        <div class="control-group">
          <label class="control-label">Year</label>
          <div class="controls">
            <select name="year">
              <?php foreach ($years as $key) { ?>
              <option value="<?php echo $key ?>">
                <?php echo $key ?>
              </option>
            <?php } ?>
            </select>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Gender</label>
          <div class="controls">
            <select name="gender">
              <option value="">ALL</option>
              <?php foreach ($genders as $key) { ?>
              <option value="<?php echo $key ?>">
                <?php echo $key ?>
              </option>
            <?php } ?>
            </select>
          </div>
        </div>
        
        <p><label>Data</label><textarea name="data"
        placeholder="Please copy and paste the data here. District first column, followed by the value. "></textarea></p>
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
