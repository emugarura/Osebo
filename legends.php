<?php
require_once 'functions.php';

$id = (int)$_GET['id'];
$meta_info = $db->query("SELECT * FROM legends_meta WHERE indicator = $id");

if ($meta_info->num_rows) {
  $meta = $meta_info->id;
}

if ($_GET['delete']) {
  $delete = (int)$_GET['delete'];
  $db->query("DELETE FROM legends WHERE id = $delete LIMIT 1");
  $print = "Legend deleted";
}
if ($_POST['meta']) { 
  $post = array(
    'label' => html($_POST['label']),
    'description' => html($_POST['description']),
    'indicator' => $id,
  );
  $print = "Information was saved";
  if ($meta) {
    $db->update("legends_meta",$post,"id = $meta");
  } else {
    $db->insert("legends_meta",$post);
  }
} elseif ($_POST) {
  $post = array(
    'from' => (float)$_POST['from'],
    'to' => (float)$_POST['to'],
    'color' => html($_POST['color']),
    'indicator' => (int)$_POST['id'],
    'label' => html($_POST['label']),
  );
  $db->insert("legends",$post);
  $print = "Legend was added";
}

$info = $db->query("SELECT * FROM indicators WHERE id = $id");
$list = $db->query("SELECT * FROM legends WHERE indicator = $id ORDER BY `from`");
$indicator = $id;

$meta_info = $db->query("SELECT * FROM legends_meta WHERE indicator = $id");
?>
<!doctype html>
<!--[if lt IE 7]><html class="no-js lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if IE 7]><html class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if IE 8]><html class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--><html class="no-js"><!--<![endif]-->
<head>
<?php echo $head ?>
<title>Legend | <?php echo SITENAME ?></title>
<style type="text/css">
.red,.orange,.green,.yellow,.lightgreen {
  padding:6px;
  width:auto;
  display: inline-block;
  list-style:none;
}
.green {
  background: #1A9641;
}
.lightgreen {
  background: #A6D96A;
}
.yellow {
  background: #FFFFBF;
}
.orange {
  background: #FDAE61;
}
.red {
  background: #D7191C;
}
.box {
  display: inline-block;
  padding:4px;
  color: #fff;
}
.jumpdown {
  margin-top:100px;
}
#samples li {
  cursor:pointer;
}
</style>
<script type="text/javascript">
$(function(){
  $("#samples li").click(function(){
    var color = $(this).data("color");
    $("#color").val(color);
  });

});
</script>
</head>
<body>
<div id="wrapper" class="container">

  <?php require_once 'include.header.php'; ?>
  
  <div id="content">
    <h2>Legend</h2>

    <div id="content-wrap">

    <?php require_once 'breadcrumbs.php'; ?>

    <?php if ($print) { echo "<div class=\"alert alert-success\">$print</div>"; } ?>
    <table class="table table-striped">
      <tr>
        <th>Label</th>
        <th>From</th>
        <th>To</th>
        <th>Color</th>
        <th>Delete</th>
      </tr>
    <?php while ($row = $list->fetch()) { ?>
      <tr>
        <td><?php echo $row['label'] ?></td>
        <td><?php echo $row['from'] ?></td>
        <td><?php echo $row['to'] ?></td>
        <td><div class="box" style="background:#<?php echo $row['color'] ?>">#<?php echo $row['color'] ?></div></td>
        <td><a href="legends.php?id=<?php echo $id ?>&amp;delete=<?php echo $row['id'] ?>" class="btn">Delete</a></td>
      </tr>
    <?php } ?>
    </table>

    <form method="post" class="form form-horizontal">
      <fieldset>
        <legend>Add New Color</legend>

        <div class="control-group">
          <label class="control-label">Label</label>
          <div class="controls">
            <input class="input-large" type="text" name="label" />
          </div>
        </div>

        <div class="control-group">
          <label class="control-label">From</label>
          <div class="controls">
            <input class="input-large" type="text" name="from" />
          </div>
        </div>

        <div class="control-group">
          <label class="control-label">To</label>
          <div class="controls">
            <input class="input-large" type="text" name="to" />
          </div>
        </div>

        <div class="control-group">
          <label class="control-label">Color</label>
          <div class="controls">
            #<input class="input-large" id="color" type="text" name="color" placeholder="Hex code like 112233" />
          </div>
        </div>

      <div class="form-actions">
        <button type="submit" class="btn btn-primary">
          Save
        </button>
        <input type="hidden" name="id" value="<?php echo $id ?>" />
      </div>

        
      </fieldset>
    </form>


    <h2>Sample Color Codes</h2>
    <ul id="samples">
      <li class="red" data-color="D7191C">#D7191C</li>
      <li class="orange" data-color="FDAE61">#FDAE61</li>
      <li class="yellow" data-color="FFFFBF">#FFFFBF</li>
      <li class="lightgreen" data-color="A6D96A">#A6D96A</li>
      <li class="green" data-color="1A9641">#1A9641</li>
      <li><a href="http://www.colorpicker.com/">Color Picker</a></li>
    </ul>

    <h1 class="jumpdown">Legend Meta Info</h1>

    <form method="post" class="form form-horizontal">
    
      <div class="control-group">
        <label class="control-label">Legend Label</label>
        <div class="controls">
          <input type="text" name="label" value="<?php echo $meta_info->label ?>" />
        </div>
      </div>

      <div class="control-group">
        <label class="control-label">Description</label>
        <div class="controls">
          <input type="text" name="description" class="input-xxlarge" value="<?php echo $meta_info->description ?>" />
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
