<?php
require_once 'functions.php';

$selected_indicator = $db->query("SELECT * FROM indicators WHERE id = $indicator");
if ($selected_indicator->category) {
  $category = $db->query("SELECT * FROM indicators WHERE id = {$selected_indicator->category}");
}
if ($selected_indicator->subcategory) {
  $subcategory = $db->query("SELECT * FROM indicators WHERE id = {$selected_indicator->subcategory}");
}
?>
<ul class="breadcrumb">

  <li><a href="list.php">Indicators</a><span class="divider">/</span></li>
  <?php if ($selected_indicator->subcategory) { ?>
    <li>
      <a href="list.php?category=<?php echo $selected_indicator->subcategory ?>"><?php echo $subcategory->name ?></a>
      <span class="divider">/</span>
    </li>

  <?php } ?>
  <?php if ($selected_indicator->category) { ?>
    <li>
      <a href="list.php?category=<?php echo $selected_indicator->category ?>&amp;sub=<?php echo $selected_indicator->subcategory ?>"><?php echo $category->name ?></a>
      <span class="divider">/</span>
    </li>
  <?php } ?>
  <li><strong><?php echo $selected_indicator->name ?></strong></li>

</ul>
