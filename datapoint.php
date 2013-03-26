<?php
require_once 'functions.php';

$id = (int)$_GET['id'];
$map = (int)$_GET['map'];
if ($id) {
  $info = $db->query("SELECT ip.*, indicators.map FROM indicatorpoints ip
  JOIN indicators ON ip.indicator = indicators.id WHERE ip.id = $id");
  $map = (int)$info->map;
}

$regions = $db->query("SELECT * FROM regions ORDER BY name");
$indicators = $db->query("SELECT * FROM indicators WHERE map = $map ORDER BY indicator");

if ($_POST) {
  $region = (int)$_POST['region'];
  foreach ($_POST['indicator'] as $key => $value) {
    $post = array(
      'indicator' => (int)$key,
      'region' => $region,
      'value' => (float)$value,
    );
    $db->insert("indicatorpoints",$post);
  }
  header("Location: " . URL . "datapoints/$map");
  exit();
}

?>
<!doctype html>
<!--[if lt IE 7]><html class="no-js lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if IE 7]><html class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if IE 8]><html class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--><html class="no-js"><!--<![endif]-->
<head>
<?php echo $head ?>
<title>Datapoint | <?php echo SITENAME ?></title>
</head>
<body>
<div id="wrapper" class="container">

  <?php require_once 'include.header.php'; ?>
  
  <div id="content">
    <h2><?php echo $id ? "Edit" : "Add" ?> Datapoint</h2>
    <div id="content-wrap">

      <form method="post" class="form-horizontal">

        <div class="control-group">
          <label class="control-label">Region</label>
          <div class="controls">
            <select name="region">
              <?php while ($row = $regions->fetch()) { ?>
              <option value="<?php echo $row['id'] ?>"
                <?php if ($row['id'] == $info->region) { echo ' selected'; } ?>><?php echo $row['name'] ?></option>
            <?php } ?>
            </select>
          </div>
        </div>

        <?php while ($row = $indicators->fetch()) { ?>
          <div class="control-group">
            <label class="control-label"><?php echo $row['indicator'] ?></label>
            <div class="controls">
              <input class="input-xlarge" type="text" name="indicator[<?php echo $row['id'] ?>]" \
                value="<?php echo $info->name ?>" />
            </div>
          </div>
        <?php } ?>

      <div class="form-actions">
        <button type="submit" class="btn btn-primary">
          Save
        </button>
        <input type="hidden" name="id" value="<?php echo $id ?>" />
      </div>

      </form>

    </div>
  </div>

  <?php require_once 'include.footer.php'; ?>

</div>
<script src="js/script.js"></script>
</body>
</html>
