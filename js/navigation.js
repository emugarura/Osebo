var polys = [];
function doOverview(num){
	for(var i = 1; i < 6; i++){
		
		if(i == num){
	$("#tab_"+num).removeClass("tab");
	$("#tab_"+num).addClass("tab_inactive");
		}else{
	
	$("#tab_"+i).removeClass("tab_inactive");
	$("#tab_"+i).addClass("tab");
		}
	}
	
	panToMarker(num);
};
function sort_by(field, reverse, primer){

   reverse = (reverse) ? -1 : 1;

   return function(a,b){

       a = a[field];
       b = b[field];

       if (typeof(primer) != 'undefined'){
           a = primer(a);
           b = primer(b);
       }

       if (a<b) return reverse * -1;
       if (a>b) return reverse * 1;
       return 0;

   }
}

function initialize() {
	$("#timeliner").hide();
	init_timeline();
data.sort(sort_by('name', false, function(a){return a.toUpperCase()}));


	// call makeMap( map center lattitude, longditude, initial zoom level, URL of data file)
		makeMap(7.6, 0.6, 10);
		
		var latlng = new google.maps.LatLng(8.5, 8.6);
    var myOptions =
    {
        maxZoom: 12,
    		minZoom: 10,
        zoom: 10,
		    draggable:false,
        center: latlng,
        panControl: false,
        zoomControl: true,
        mapTypeControl: true,
        scaleControl: true,
        streetViewControl: false
    };
	//place holder for button functionality	
		
	$("#tab_1").click(function(){
		
		$("#holder").delay(510).fadeIn(500);
		$("#key").delay(510).fadeIn(500);
		$("#keyLink").delay(510).fadeIn(500);
		$("#timeliner").fadeOut(500);
	});
	$("#tab_2").click(function(){
		$("#holder").fadeOut(500);
		$("#key").fadeOut(500);
		$("#keyLink").fadeOut(500);
		$("#timeliner").delay(510).fadeIn(500);
	});
	
	
	$("#tab_1").mouseover(function(){
		$('#drop_down').stop(false,true).slideDown();
		
		$('#tab_1').mouseleave(function(){
			$('#drop_down').stop(false,true).slideUp();
		});
	});
	
	//$("#tab_1").mouseout(function(){
	//		$('#drop_down').slideUp();
	//});
	
	
	

	
	outlineCountries();
	
	$('#content_pane').animate({"left": "380"}, 0);

	$('#drop_down').css('width',$('#tab_1').width()+20);
	$('.drop_down_item').css('width',$('#tab_1').width()+20);
	

}

function outlineCountries(){
	$.each(data,function(){
		
		var info = this;
		
		$('#drop_down').append('<div class="drop_down_item" id="'+this.name.split(" ").join("_")+'">'+this.name+'</div>');
		$.each(this.outlines,function(){
				decodePath(this,info);
		});
		
	});
		$('#drop_down').slideUp(0);

}


// Decode an encoded levels string into an array of levels.
function decodeLevels(encodedLevelsString) {
  var decodedLevels = [];

  for (var i = 0; i < encodedLevelsString.length; ++i) {
    var level = encodedLevelsString.charCodeAt(i) - 63;
    decodedLevels.push(level);
  }

  return decodedLevels;
}

// Decode the supplied encoded polyline.
function decodePath(path,data) {
  var encodedPolyline = path;
  var encodedLevels = "";

  if (encodedPolyline.length==0) {
    return;
  }

  var decodedPath = google.maps.geometry.encoding.decodePath(encodedPolyline);
  var decodedLevels = decodeLevels(encodedLevels);
  
 var polyOptions = {
	 clickable:true,
	  path: decodedPath,
	  levels:decodedLevels,
	  strokeColor: '#000',
    strokeOpacity: 0.7,
    strokeWeight: 1,
	  fillColor:data.color,
	  fillOpacity:0.7
    }
	//add it to the map */
    var line = new google.maps.Polygon(polyOptions);
	line.setMap(map);
	var line2 = new google.maps.Polygon(polyOptions);
	
	google.maps.event.addListener(line, 'click', function(){content(data)});
	google.maps.event.addListener(line2, 'click', function(){content(data)});
	temp_name = '#'+data.name.split(' ').join('_');
	
	$(temp_name).click(function(){
		content(data);
		$('#drop_down').slideUp(0);

		});
	
	google.maps.event.addListener(line, 'mouseover', function(){
		line.setOptions({
			fillOpacity:0.9
		});
	});
	google.maps.event.addListener(line, 'mouseout', function(){
		line.setOptions({
			fillOpacity:0.7
		});
	});
	google.maps.event.addListener(line2, 'mouseover', function(){
		line2.setOptions({
			fillOpacity:0.9
		});
		line.setOptions({
			fillOpacity:0.9
		});
	});
	google.maps.event.addListener(line2, 'mouseout', function(){
		line2.setOptions({
			fillOpacity:0.7
		});
		line.setOptions({
			fillOpacity:0.7
		});
	});
	$(temp_name).mouseenter(function(){

		line2.setOptions({
			fillOpacity:0.9
		});
		line.setOptions({
			fillOpacity:0.9
		});
	});
	$(temp_name).mouseleave(function(){
		line2.setOptions({
			fillOpacity:0.7
		});
		line.setOptions({
			fillOpacity:0.7
		});
	});
			
	
}
function content(data){
		x = data.latLng.split(',');
					map.panTo(new google.maps.LatLng(x[0], x[1]));
					$('#content_pane').stop().animate({"left": "380"}, 200,function(){
						$('#content_pane').delay(200).html("<div id='close_button'><img src='img/close_button.png'></div><h1>"+data.name+"</h1><p>"+data.blurb+"</p>");  	
						$('#close_button').click(function(){
							map.panTo(new google.maps.LatLng(23.483400654325642, 21.62109375));
							$('#content_pane').animate({"left": "380"}, 200);
						});
						});
						
					$('#content_pane').animate({"left": "0"}, 200).delay(200);
	};
