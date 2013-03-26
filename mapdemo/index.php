<?php

require_once '../functions.php';
$topics = $db->query("SELECT * FROM topics ORDER BY name");

?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

  <title>Osebo - Ghana Map</title>

  <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />

  <link rel="stylesheet" href="css/bootstrap.css" />
  <link rel="stylesheet" href="css/bootstrap-responsive.css" />
  <link rel="stylesheet" href="css/styles.css?force_reload" />
  <script src="js/modernizr.js"></script>

</head>
<body>
  <section id="main">

    <!-- MAP CANVAS -->

    <div id="map"></div>

    <a href="#" id="show-filters" rel="tooltip" class="btn btn-primary">
      <span class="icon-wrench icon-white"></span>
      Filter controls
    </a>

    <!-- CONTROLS -->

    <div id="controls">

      <div id="left-controls" class="pull-left">


        <div class="block">
          <h3 class="block-title"><span class="icon-list"></span> Indicators</h3>
          <div class="block-content">

          <!-- INDICATORS -->


<ul id="indicator" class="kml nav nav-list">
<?php 
while ($row = $topics->fetch()) { 
$topic = $row['id'];
$indicators = $db->query("SELECT * FROM indicators WHERE category IS NULL AND topic = $topic AND active = 1 ORDER BY id");
?>
	<li class="open" data-key="topic<?php echo $row['topic'] ?>"><a href="#"><?php echo $row['name'] ?></a>
		<ul class="nav nav-list">
		<?php while ($row = $indicators->fetch()) { ?>
			<?php $sub_list = $db->query("SELECT * FROM indicators WHERE category = {$row['id']} AND active = 1 ORDER BY id"); ?>
			<li class="open" data-key="<?php echo $row['id'] ?>"><a href="#"><?php echo $row['name'] ?></a>
			<?php if ($sub_list->num_rows) { ?>
				<ul class="nav nav-list">
				<?php while ($subrow = $sub_list->fetch()) { ?>
					<?php $sub_sub_list = $db->query("SELECT * FROM indicators WHERE category = {$subrow['id']} AND active = 1 ORDER BY id"); ?>
					<?php if (!$sub_sub_list->num_rows) { ?>
						<li data-key="<?php echo $subrow['id'] ?>"><a href="#"><?php echo $subrow['name'] ?></a></li>
					<?php } else { ?>
						<li class="open" data-key="<?php echo $row['id'] ?>"><a href="#"><?php echo $subrow['name'] ?></a>
							<ul class="nav nav-list">
							<?php while ($subsubrow = $sub_sub_list->fetch()) { ?>
								<li data-key="<?php echo $subsubrow['id'] ?>"><a href="#"><?php echo $subsubrow['name'] ?></a></li>
							<?php } ?>
							</ul>
						</li>
					<?php } ?>
				<?php } ?>
				</ul>
			<?php } ?>
			</li>
		<?php } ?>
		</ul>
	</li>
<?php } ?>
</ul>

            <!-- / -->

          </div>
        </div>

        <div class="block" id="submit">
          <div class="block-content">
            <a href="#" class="btn btn-primary" id="filter"><span class="icon-search icon-white"></span> Filter</a>
            <a href="#" class="btn" id="hide-controls"><span class="icon-share-alt"></span> Hide controls</a>
          </div>
        </div>

      </div>

        <div id="right-controls" class="pull-left">
          <div class="block">

          <h3 class="block-title"><span class="icon-filter"></span> Filter</h3>
          <div class="block-content">

            <!-- DIVISIONS -->

            <div id="divisions" class="kml btn-group" data-toggle="buttons-radio">
              <button data-key="1" type="button" class="btn active">Regions</button>
              <button data-key="2" type="button" class="btn">Districts</button>
            </div>

            <!-- / -->

            <!-- GENDER -->

            <div id="gender" class="kml btn-group" data-toggle="buttons-radio">
              <button data-key="0" type="button" id="gender-all" class="btn active">All</button>
              <button data-key="M" type="button" id="gender-male" class="btn">Male</button>
              <button data-key="F" type="button" id="gender-female" class="btn">Female</button>
            </div>

            <!-- / -->

          </div>

          </div>

        <div class="block">
          <h3 class="block-title"><span class="icon-calendar"></span> Year</h3>
          <div class="block-content">

            <!-- YEAR -->
            
            <ul id="year" class="nav kml nav-list">
              <li data-key="2009"><a href="#">2009</a></li>
              <li data-key="2010"><a href="#">2010</a></li>
              <li data-key="2011" class="active"><a href="#">2011</a></li>
    <!--<li data-key="4"><a href="#">2012</a></li>-->
            </ul>

            <!-- / -->

          </div>
        </div>
        

          <h3 class="block-title"><span class="icon-eye-open"></span> Show</h3>
          <div class="block-content">

            <!-- SHOW -->

            <ul class="nav nav-list">
              <li data-key="1">
                <label class="checkbox">
                  <input type="checkbox" name="show" id="regional_borders" class="kml" data-key="1" value="1" />
                  Regional borders
                </label>
              </li>
              <li data-key="2">
                <label class="checkbox">
                  <input type="checkbox" name="show" id="major_cities" class="kml" data-key="1" value="1" />
                  Major cities
                </label>
              </li>
              <li data-key="3">
                <label class="checkbox">
                  <input type="checkbox" name="show" id="cfs" class="kml" data-key="1" value="" />
                  CFS
                </label>
              </li>
              <li data-key="4">
                <label class="checkbox">
                  <input type="checkbox" name="show" id="hiv_alert" class="kml" data-key="1" value="" />
                  HIV alert
                </label>
              </li>
              <li data-key="5">
                <label class="checkbox">
                  <input type="checkbox" name="show" id="sports" class="kml" data-key="1" value="" />
                  Sports in education
                </label>
              </li>
            </ul>

            <!-- / -->

          </div>

  <!--
          <h3 class="block-title"><span class="icon-align-left"></span> Show districts by</h3>
          <div class="block-content">


            <ul id="districts" class="kml nav nav-list">
              <li class="active" data-key="1">
                <a href="#">
                  All
                </a>
              </li>
              <li>
                <a href="#">
                  Unemployment
                </a>
                <ul class="nav nav-list">
                  <li data-key="2.1"><a href="#">Higher than average</a></li>
                  <li data-key="2.2" class="active"><a href="#">Average</a></li>
                  <li data-key="2.3"><a href="#">Lower than average</a></li>
                </ul>
              </li>
              <li>
                <a href="#">
                  Urbanization
                </a>
                <ul class="nav nav-list">
                  <li data-key="3.1"><a href="#">Higher than average</a></li>
                  <li data-key="3.2"><a href="#">Average</a></li>
                  <li data-key="3.3"><a href="#">Lower than average</a></li>
                </ul>
              </li>
              <li>
                <a href="#">
                  Income
                </a>
                <ul class="nav nav-list">
                  <li data-key="4.1"><a href="#">Higher than average</a></li>
                  <li data-key="4.2"><a href="#">Average</a></li>
                  <li data-key="4.3"><a href="#">Lower than average</a></li>
                </ul>
              </li> 
            </ul>


          </div>

  -->

        </div>
      </div>

  <div id="legends">
  <div class="block">
    <h3 class="block-title"><span class="icon-map-marker"></span> <span id="legend-title">Legends</span></h3>
    <div class="block-content">
      <ul id="legend-list">
	  </ul>
	  <div id="legend-bottom">
	  </div>
    </div>
  </div>
  </div>

  </section>
  
  <!-- JAVASCRIPT -->
  <script src="js/jquery.js"></script>   
  <script src="js/bootstrap.js"></script>   
  <script src="//maps.googleapis.com/maps/api/js?sensor=false&key=AIzaSyCJWcXmZaY0jGtupHNTlDqOybKUzH3k4TA
"></script>
  <script type="text/javascript">
  $(function(){
	  var mapOptions = {
      center: new google.maps.LatLng(8.397, 1.944),
      minZoom:5,
      zoom: 7,
      maxZoom:9,
      mapTypeControl:false,
      mapTypeId: google.maps.MapTypeId.ROADMAP
	  };

	  var map = new google.maps.Map(document.getElementById("map"), mapOptions);

	  var kmlLayer,
		  kmlLayerRegions = new google.maps.KmlLayer("<?php echo URL ?>/mapdemo/kml/regions.kml?reload",{ preserveViewport: true }),
		  kmlLayerMajorCities = new google.maps.KmlLayer("<?php echo URL ?>/mapdemo/kml/major_cities.kml",{ preserveViewport: true }),
		  kmlLayer2;

	  var kml = $(".kml");
	  var keys = {};
	  var legends = $("#legends");
	  var legend_title = $("#legend-title");
	  var legend_bottom = $("#legend-bottom");
	  var legend_list = $("#legend-list");
	  var major_cities_checkbox = $("#major_cities");
	  var regional_borders_checkbox = $("#regional_borders");
	  var filter = $("#filter");

	  //Indicator menu, active inactive states
	  $("#indicator li").click(function(evento){
      $("#indicator").find(".active").removeClass("active");
      $(this).addClass("active");
      evento.stopPropagation();
	  })

	  //Year menu, active inactive states
	  $("#year li").click(function(){
      $("#year").find(".active").removeClass("active");
      $(this).addClass("active");
	  })

	  filter.click(function(e){
		  filter.addClass("disabled").html("<img src='img/loader.gif?forcereload' /> Loading...");

		//Get all the controls values
		kml.each(function(){
		  var id = $(this).attr("id");
		  var value = $(this).find(".active").data("key");                      
		  keys[id] = value ? value : 0;
		});

		$.ajax({
		  url:"process.php",
		  data: keys,
		  dataType: "json",
		  success: function(data){
			//Hide legends box
			legends.slideUp(500,function(){
				legend_list.html("");

				//Populate legends box
				for (legend in data.legends) {
				  legend_list.append(
					$("<li />").text(data.legends[legend].value)
					.prepend(
					  $("<span />").css("background", data.legends[legend].color)
					)
				  );	
				}

				// Add title and bottom text to legend box
				legend_title.text((data.top_legend == "" ? "Legends" : data.top_legend));
				legend_bottom.text(data.bottom_legend);

				//Show legends box
				legends.slideDown();
		  });
      if(kmlLayer){
			  kmlLayer.setMap(null);	
      }

      if(kmlLayer2){
			  kmlLayer2.setMap(null);	
      }

			kmlLayer = new google.maps.KmlLayer(data.kml_url_1,{ preserveViewport: true });
			kmlLayer.setMap(map)

			kmlLayer2 = new google.maps.KmlLayer(data.kml_url_2,{ preserveViewport: true });
			kmlLayer2.setMap(map)

			google.maps.event.addListener(kmlLayer2, "metadata_changed", function() {

				kmlLayerMajorCities.setMap(null);
				kmlLayerRegions.setMap(null);
				
				if(regional_borders_checkbox.is(":checked")){
					kmlLayerRegions.setMap(map);
				}

				if(major_cities_checkbox.is(":checked")){
					kmlLayerMajorCities.setMap(map);
				}

				filter.removeClass("disabled").html('<span class="icon-search icon-white"></span> Filter');

			});
		  }
		})

		//prevent page jump
		e.preventDefault();
	  });

	  /* SHOW / HIDE controls and tooltip stuff */

	  $("#show-filters").click(function(){
		$("#controls").show(0).animate({right: "0"},500);
		$("#legends").animate({right: "620px"},500);
	  });

	  var tooltip_shown   = false;

	  var destroyTooltip  = function () {
		$("#show-filters").tooltip('destroy');
	  }

	  $("#show-filters").tooltip({ 
		title: "Click here to change the filtering options.", 
		placement: 'top',
		trigger: 'manual'
	  });

	  $("#hide-controls").click(function(){
		$("#legends").animate({right: "20px"},500);
		$("#controls").animate({right: "-600px"},500,function () {
		$(this).hide(0);
	  });

	  $("#show-filters").tooltip('show');
		setTimeout(destroyTooltip,4000);
	  });

	  $("#indicator a").click(function(evento){
		evento.preventDefault();
	  });

	  $("#indicator .open a").click(function(evento){
		$(this).parent().find("ul:first").slideToggle();
	  });

  });
  </script> 
</body>
</html>
