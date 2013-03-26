
<?php
require_once 'functions.php';
$id = (int)$_GET['id'];
$lang = 'es';

$info = $db->query("
    SELECT content.* 
    FROM pages
    JOIN content ON pages.id = content.page
    WHERE pages.id = $id AND content.language = '$lang' AND content.status = 'active'");
?>
<!doctype html>
<!--[if lt IE 7]><html class="no-js lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if IE 7]><html class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if IE 8]><html class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--><html class="no-js"><!--<![endif]-->
<head>
<?php echo $head ?>
<title><?php echo $info->title ?> | <?php echo SITENAME ?></title>
</head>
<body>
<div id="wrapper" class="container<?php if (defined("CMS_LOGIN")) { echo ' editing'; }; ?>">

  <?php
  if (defined("CMS_LOGIN")) {  
    require_once 'include.validated.php';
  }
  ?>

  <?php require_once 'include.header.php'; ?>
  
  <div id="content">
    <h2 id="title" ><?php echo $info->title ?></h2>
    <div id="content-wrap">
      <?php echo str_replace("../", "", $info->content);  ?>
    </div>
  </div>

  <?php  if (defined("CMS_LOGIN")) { ?>
   <p id="public"><label>Public:</label>
   <input type="checkbox" name="status" value="1" <?php echo ($info->status == 'active') ? 'checked': ''; ?>></p>
  <?php } ?>

  <?php require_once 'include.footer.php'; ?>

</div>
<script src="js/script.js"></script>
</body>
</html>
