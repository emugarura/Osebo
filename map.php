<?php
require_once 'functions.php';

$id = (int)$_GET['id'];
if ($id) {
  $info = $db->query("SELECT * FROM maps WHERE id = $id");
}

if ($_POST) {
  $post = array(
    'name' => html($_POST['name']),
  );
  if ($id) {
    $db->update("maps",$post,"id = $id");
  } else {
    $db->insert("maps",$post);
    $id = $db->insert_id;
  }
  header("Location: " . URL . "maps/list/saved");
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
<title>Map | <?php echo SITENAME ?></title>
</head>
<body>
<div id="wrapper" class="container">

  <?php require_once 'include.header.php'; ?>
  
  <div id="content">
    <h2><?php echo $id ? "Edit" : "Add" ?> Map</h2>
    <div id="content-wrap">

      <form method="post" class="form-horizontal">
        <div class="control-group">
          <label class="control-label">Name</label>
          <div class="controls">
            <input class="input-xlarge" type="text" name="name" value="<?php echo $info->name ?>" />
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
