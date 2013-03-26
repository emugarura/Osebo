<?php
require_once 'functions.php';
$id = (int)$_GET['id'];
$indicator = $id;

if ($_GET['delete']) {
  $delete = (int)$_GET['delete'];
  $db->query("DELETE FROM indicatorpoints WHERE indicator = $id AND year = $delete");
  $print = "Data was removed";
}

$list = $db->query("SELECT year,gender,value,regions.name AS region
FROM indicatorpoints AS i 
JOIN regions ON i.region = regions.id
WHERE i.indicator = $id ORDER BY year, gender, regions.name, i.id");

$info = $db->query("SELECT * FROM indicators WHERE id = $id");
?>
<!doctype html>
<!--[if lt IE 7]><html class="no-js lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if IE 7]><html class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if IE 8]><html class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--><html class="no-js"><!--<![endif]-->
<head>
<?php echo $head ?>
<title>View Data | <?php echo SITENAME ?></title>
</head>
<body>
<div id="wrapper" class="container">

  <?php require_once 'include.header.php'; ?>
  
  <div id="content">
    <h2>View Data: <?php echo $info->name ?></h2>
    <div id="content-wrap">

    <?php require_once 'breadcrumbs.php'; ?>

    <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

      <p><strong><?php echo $list->num_rows ?></strong> records found</p>
      <?php while ($row = $list->fetch()) { ?>
      <?php if ($year != $row['year']) { ?>
      <?php if ($year) { ?>
        </table>
      <p><a href="data.view.php?id=<?php echo $id ?>&amp;delete=<?php echo $year ?>" class="btn btn-danger">Remove all of <?php echo $year ?></a></p>
      <?php } ?>
      <h2><?php echo $row['year'] ?></h2>
      <table class="table table-striped">
        <tr>
          <th>Region</th>
          <th>Year</th>
          <th>Gender</th>
          <th>Value</th>
        </tr>
      <?php } $year = $row['year']; ?>

        <tr>
          <td><?php echo $row['region'] ?></td>
          <td><?php echo $row['year'] ?></td>
          <td><?php echo $row['gender'] ? $row['gender'] : "ALL" ?></td>
          <td><?php echo $row['value'] ?></td>
        </tr>
      <?php } ?>
      </table>
      <p><a href="data.view.php?id=<?php echo $id ?>&amp;delete=<?php echo $year ?>" class="btn btn-danger">Remove all of <?php echo $year ?></a></p>

    </div>
  </div>

  <?php require_once 'include.footer.php'; ?>

</div>
<script src="js/script.js"></script>
</body>
</html>
