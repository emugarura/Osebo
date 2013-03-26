<?php
require_once 'functions.php';

if ($_POST) {
  $post = array(
    'name' => html($_POST['name']),
    'category' => $_GET['category'] ? (int)$_GET['category'] : "NULL",
    'subcategory' => $_GET['subcategory'] ? (int)$_GET['subcategory'] : "NULL",
  );
  if ($id) {
    $db->update("indicators",$post,"id = $id");
  } else {
    $db->insert("indicators",$post);
    $id = $db->insert_id;
  }
  header("Location: " . URL . "list.php");
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
<title>Indicator | <?php echo SITENAME ?></title>
</head>
<body>
<div id="wrapper" class="container">

  <?php require_once 'include.header.php'; ?>
  
  <div id="content">
    <h2>Indicator</h2>

    <div id="content-wrap">

    <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

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

  <?php if ($list->num_rows) { ?>
  <h2>Indicators</h2>
  <table class="table table-striped">
    <tr>
      <th>Indicator</th>
      <th>Delete</th>
    </tr>
  <?php while ($row = $list->fetch()) { ?>
    <tr>
      <td><?php echo $row['indicator'] ?></td>
      <td><a href="indicators/delete/<?php echo $map ?>/<?php echo $row['id'] ?>" class="btn">Delete</a></td>
    </tr>
  <?php } ?>
  </table>
  <?php } ?>

  <?php require_once 'include.footer.php'; ?>

</div>
<script src="js/script.js"></script>
</body>
</html>
