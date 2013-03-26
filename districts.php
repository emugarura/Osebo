<?php
require_once 'functions.php';
$list = $db->query("SELECT * FROM regions ORDER BY name");

function getAlias($id) {
  global $db;
  $list = $db->query("SELECT * FROM region_alias WHERE region = $id ORDER BY name");
  if (!$list->num_rows) { 
    return false;
  }
  while ($row = $list->fetch()) {
    $return .= $row['name'] . ", ";
  }
  $return = substr($return, 0, -2);
  return $return;
}

?>
<!doctype html>
<!--[if lt IE 7]><html class="no-js lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if IE 7]><html class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if IE 8]><html class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--><html class="no-js"><!--<![endif]-->
<head>
<?php echo $head ?>
<title>Areas | <?php echo SITENAME ?></title>
</head>
<body>
<div id="wrapper" class="container">

  <?php require_once 'include.header.php'; ?>
  
  <div id="content">
    <h2>Areas</h2>
    <div id="content-wrap">

    <ul class="breadcrumb">
      <li><strong>Areas</strong></li>
    </ul>

    <table class="table table-striped">
      <tr>
        <th>#</th>
        <th>Name</th>
        <th>Aliases</th>
      </tr>
    <?php while ($row = $list->fetch()) { ?>
      <tr>
        <td><?php echo $row['id'] ?></td>
        <td><?php echo $row['name'] ?></td>
        <td><?php echo getAlias($row['id']) ?></td>
      </tr>
    <?php } ?>
    </table>

    </div>
  </div>

  <?php require_once 'include.footer.php'; ?>

</div>
<script src="js/script.js"></script>
</body>
</html>
