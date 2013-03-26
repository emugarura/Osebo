<?php
require_once 'functions.php';

if ($_GET['delete']) {
  $delete = (int)$_GET['delete'];
  $db->query("UPDATE indicators SET active = 0 WHERE id = $delete LIMIT 1");
  $print = "Subgroup was deleted";
}

if ($_GET['topic']) {
  $print = "Topic information was saved";
}

if ($_GET['saved']) {
  $print = "Information was saved";
}

$category = $_GET['category'] ? "= " . (int)$_GET['category'] : "IS NULL";
$sub = $_GET['sub'] ? "= " . (int)$_GET['sub'] : "IS NULL";

$list = $db->query("SELECT * FROM indicators WHERE active = 1 AND category $category AND subcategory $sub ORDER BY name");

if ((int)$_GET['category']) {
  $info = $db->query("SELECT * FROM indicators WHERE id = " . (int)$_GET['category']);
}

if ((int)$_GET['sub']) {
  $subinfo = $db->query("SELECT * FROM indicators WHERE id = " . (int)$_GET['sub']);
}

?>
<!doctype html>
<!--[if lt IE 7]><html class="no-js lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if IE 7]><html class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if IE 8]><html class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--><html class="no-js"><!--<![endif]-->
<head>
<?php echo $head ?>
<title>Indicators | <?php echo SITENAME ?></title>
</head>
<body>
<div id="wrapper" class="container">

  <?php require_once 'include.header.php'; ?>
  
  <div id="content">
    <h2><?php echo $_GET['category'] ? "Subgroups" : "Indicators" ?></h2>
    <div id="content-wrap">

    <ul class="breadcrumb">

      <?php if (!$_GET['category']) { ?>
        <li><strong>Indicators</strong></li>
      <?php } else { ?>
      <li><a href="list.php">Indicators</a><span class="divider">/</span></li>
      <?php } ?>
      <?php if ((int)$_GET['sub']) { ?>
        <li><a href="list.php?category=<?php echo (int)$_GET['sub']; ?>"><?php echo $subinfo->name ?></a>
          <span class="divider">/</span>
        </li>
      <?php } ?>
      <?php if ($category) { ?>
        <li><strong><?php echo $info->name ?></strong></li>
      <?php } ?>

    </ul>

    <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>

    <?php if ($list->num_rows) { ?>

     <table class="table table-striped">
       <tr>
         <th>Name</th>
         <th>Data</th>
         <th>Legend</th>
         <th>Actions</th>
       </tr>
     <?php while ($row = $list->fetch()) { ?>
       <tr>
         <?php if (!$row['category']) { ?>
           <td><a href="list.php?category=<?php echo $row['id'] ?>"><?php echo $row['name'] ?></a></td>
         <?php } elseif (!$row['subcategory']) { ?>
           <td><a href="list.php?category=<?php echo $row['id'] ?>&amp;sub=<?php echo $row['category'] ?>"><?php echo $row['name'] ?></a></td>
         <?php } else { ?>
            <td><?php echo $row['name'] ?></td>
         <?php } ?>
         <td><a href="data.select.php?id=<?php echo $row['id'] ?>" class="btn">New Data</a>
         <a href="data.view.php?id=<?php echo $row['id'] ?>" class="btn">View Data</a>
         </td>
         <td><a href="legends.php?id=<?php echo $row['id'] ?>" class="btn">Legend</a></td>
         <td>
          <a href="indicator.php?category=<?php echo $row['id'] ?>&amp;subcategory=<?php echo $row['category'] ?>" class="btn">Add subgroup</a>
          <?php if (!$_GET['category']) { ?>
            <a class="btn" href="topic.php?id=<?php echo $row['id'] ?>">Set topic</a>
          <?php } ?>
          <a onclick="javascript:return confirm('Are you sure you want to delete this indicator?')" href="list/delete/<?php echo $row['id'] ?>" class="btn btn-danger">Delete</a>
        </td>
       </tr>
     <?php } ?>
     </table>

     <?php } else { ?>
      <div class="alert">No subgroup found</div>
     <?php } ?>

	<?php if($_GET["category"]){ ?>
     <p><a href="indicator.php?category=<?php echo (int)$_GET["category"] ?>" class="btn">New Subgroup</a></p>
	<?php } else  { ?>
     <p><a href="indicator/new" class="btn">New Indicator</a></p>
	<?php } ?>


    </div>
  </div>

  <?php require_once 'include.footer.php'; ?>

</div>
<script src="js/script.js"></script>
</body>
</html>
