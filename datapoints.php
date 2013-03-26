<?php
require_once 'functions.php';

$id = (int)$_GET['id'];
$map = $db->query("SELECT * FROM maps WHERE id = $id");

if ($_GET['delete']) {
  $delete = (int)$_GET['delete'];
  $db->query("DELETE FROM indicatorpoints WHERE id = $delete LIMIT 1");
  $print = "Datapoint was deleted";
}

if ($_GET['saved']) {
  $print = "Information was saved";
}

$list = $db->query("SELECT i.*, regions.name, indicators.indicator
FROM indicatorpoints i 
  JOIN regions ON i.region = regions.id
  JOIN indicators ON i.indicator = indicators.id
WHERE indicators.map = $id
ORDER BY regions.name, indicators.indicator
");

?>
<!doctype html>
<!--[if lt IE 7]><html class="no-js lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if IE 7]><html class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if IE 8]><html class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--><html class="no-js"><!--<![endif]-->
<head>
<?php echo $head ?>
<title>Datapoints | <?php echo $map->name ?> | <?php echo SITENAME ?></title>
</head>
<body>
<div id="wrapper" class="container">

  <?php require_once 'include.header.php'; ?>
  
  <div id="content">
    <h2>Datapoints | Map <?php echo $map->name ?></h2>
    <div id="content-wrap">

    <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

    <?php if ($list->num_rows) { ?>

     <table class="table table-striped">
       <tr>
         <th>Name</th>
         <th>Indicator</th>
         <th>Value</th>
         <th>Delete</th>
       </tr>
     <?php while ($row = $list->fetch()) { ?>
       <tr>
         <td><a href="datapoint/edit/<?php echo $row['id'] ?>"><?php echo $row['name'] ?></a></td>
         <td><?php echo $row['indicator'] ?></td>
         <td><?php echo $row['value'] ?></td>
         <td><a href="datapoints/delete/<?php echo $id ?>/<?php echo $row['id'] ?>" class="btn">Delete</a></td>
       </tr>
     <?php } ?>
     </table>

     <?php } else { ?>
      <div class="alert">No datapoints found</div>
     <?php } ?>

     <p>
      <a href="datapoint/map/<?php echo $id ?>" class="btn">New Datapoint</a>
      <a href="loader.php?map=<?php echo $id ?>" class="btn">Mass Data Loader</a>
    </p>

    </div>
  </div>

  <?php require_once 'include.footer.php'; ?>

</div>
<script src="js/script.js"></script>
</body>
</html>
