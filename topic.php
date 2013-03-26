<?php
require_once 'functions.php';
$id = (int)$_GET['id'];

$info = $db->query("SELECT * FROM indicators WHERE id = $id");
$topics = $db->query("SELECT * FROM topics ORDER BY name");

if ($_POST) {
  $topic = $_POST['topic'] ? (int)$_POST['topic'] : "NULL";
  $db->query("UPDATE indicators SET topic = $topic WHERE id = $id LIMIT 1");
  header("Location: list.php?topic=1");
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
<title>Set Topic | <?php echo SITENAME ?></title>
</head>
<body>
<div id="wrapper" class="container">

  <?php require_once 'include.header.php'; ?>
  
  <div id="content">
    <h2>Set Topic</h2>
    <div id="content-wrap">

      <p>Indicator: <?php echo $info->name ?></p>

      <form method="post">
      
        <div class="control-group">
          <label class="control-label">Topic</label>
          <div class="controls">
            <select name="topic">
              <option value=""></option>
              <?php while ($row = $topics->fetch()) { ?>
              <option value="<?php echo $row['id'] ?>"<?php if ($row['id'] == $info->topic) { echo ' selected'; } ?>><?php echo $row['name'] ?></option>
            <?php } ?>
            </select>
          </div>
        </div>

      <div class="form-actions">
        <button type="submit" class="btn btn-primary" name="meta" value="1">
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
