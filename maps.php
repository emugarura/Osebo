<?php
require_once 'functions.php';

if ($_GET['delete']) {
  $delete = (int)$_GET['delete'];
  $db->query("UPDATE maps SET active = 0 WHERE id = $delete LIMIT 1");
  $print = "Map was deleted";
}

$list = $db->query("SELECT * FROM maps WHERE active = 1 ORDER BY name");

if ($_GET['saved']) {
  $print = "Information was saved";
}

?>
<!doctype html>
<!--[if lt IE 7]><html class="no-js lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if IE 7]><html class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if IE 8]><html class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--><html class="no-js"><!--<![endif]-->
<head>
<?php echo $head ?>
<title>Maps | <?php echo SITENAME ?></title>
</head>
<body>
<div id="wrapper" class="container">

  <?php require_once 'include.header.php'; ?>
  
  <div id="content">
    <h2>Maps</h2>
    <div id="content-wrap">

    <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

    <?php if ($list->num_rows) { ?>

     <table class="table table-striped">
       <tr>
         <th>Name</th>
         <th>Indicators</th>
         <th>Datapoints</th>
         <th>Delete</th>
       </tr>
     <?php while ($row = $list->fetch()) { ?>
       <tr>
         <td><a href="map/edit/<?php echo $row['id'] ?>"><?php echo $row['name'] ?></a></td>
         <td><a href="indicators/<?php echo $row['id'] ?>" class="btn">Indicators</a></td>
         <td><a href="datapoints/<?php echo $row['id'] ?>" class="btn">Datapoints</a></td>
         <td><a href="maps/delete/<?php echo $row['id'] ?>" class="btn">Delete</a></td>
       </tr>
     <?php } ?>
     </table>

     <?php } else { ?>
      <div class="alert">No maps found</div>
     <?php } ?>

     <p><a href="map/new" class="btn">New Map</a></p>

    </div>
  </div>

  <?php require_once 'include.footer.php'; ?>

</div>
<script src="js/script.js"></script>
</body>
</html>
