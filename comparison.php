<?php
require_once 'functions.php';

$id = (int)$_GET['id'];
if ($id) {
  $info = $db->query("SELECT * FROM comparisons WHERE id = $id");
}

if ($_POST) {
  $post = array(
    'name' => html($_POST['name']),
  );
  if ($id) {
    $db->update("comparisons",$post,"id = $id");
    $db->query("DELETE FROM comparison_maps WHERE comparison = $id");
  } else {
    $db->insert("comparisons",$post);
    $id = $db->insert_id;
  }
  foreach ($_POST['maps'] as $value) {
    $map = (int)$value;
    $post = array(
      'comparison' => $id,
      'map' => $value,
    );
    $db->insert("comparison_maps",$post);
  }
  header("Location: " . URL . "comparisons/list/saved");
  exit();
}

$list = $db->query("SELECT * FROM maps ORDER BY name");
$selected = $db->query("SELECT map FROM comparison_maps WHERE comparison = $id");

while ($row = $selected->fetch()) {
  $maps_selected[$row['map']] = true;
}

?>
<!doctype html>
<!--[if lt IE 7]><html class="no-js lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if IE 7]><html class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if IE 8]><html class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--><html class="no-js"><!--<![endif]-->
<head>
<?php echo $head ?>
<title>Comparison | <?php echo SITENAME ?></title>
</head>
<body>
<div id="wrapper" class="container">

  <?php require_once 'include.header.php'; ?>
  
  <div id="content">
    <h2><?php echo $id ? "Edit" : "Add" ?> Comparison</h2>
    <div id="content-wrap">

      <form method="post" class="form-horizontal">
        <div class="control-group">
          <label class="control-label">Name</label>
          <div class="controls">
            <input class="input-xlarge" type="text" name="name" value="<?php echo $info->name ?>" />
          </div>
        </div>

        <div class="control-group">
          <label class="control-label">Maps</label>
          <div class="controls">
            <select name="maps[]" multiple size="8">
              <?php while ($row = $list->fetch()) { ?>
              <option value="<?php echo $row['id'] ?>"<?php if ($maps_selected[$row['id']]) { echo ' selected'; } ?>>
                <?php echo $row['name'] ?>
              </option>
              <?php } ?>
            </select> Use CTRL to select several maps
          </div>
        </div>

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
