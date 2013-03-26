<?php
require_once 'functions.php';

if ($_GET['delete']) {
  $delete = (int)$_GET['delete'];
  $db->query("UPDATE comparisons SET active = 0 WHERE id = $delete LIMIT 1");
  $print = "Comparison was deleted";
}

$list = $db->query("SELECT * FROM comparisons ORDER BY name");

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
<title>Comparisons | <?php echo SITENAME ?></title>
</head>
<body>
<div id="wrapper" class="container">

  <?php require_once 'include.header.php'; ?>
  
  <div id="content">
    <h2>Comparisons</h2>
    <div id="content-wrap">

    <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

    <?php if ($list->num_rows) { ?>

     <table class="table table-striped">
       <tr>
         <th>Name</th>
         <th>Delete</th>
       </tr>
     <?php while ($row = $list->fetch()) { ?>
       <tr>
         <td><a href="comparison/edit/<?php echo $row['id'] ?>"><?php echo $row['name'] ?></a></td>
         <td><a href="comparisons/delete/<?php echo $row['id'] ?>" class="btn">Delete</a></td>
       </tr>
     <?php } ?>
     </table>

     <?php } else { ?>
      <div class="alert">No comparisons found</div>
     <?php } ?>

     <p><a href="comparison/new" class="btn">New Comparison</a></p>

    </div>
  </div>

  <?php require_once 'include.footer.php'; ?>

</div>
<script src="js/script.js"></script>
</body>
</html>
