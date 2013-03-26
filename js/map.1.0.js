// JS google maps interface, CNN.com, Matt Barringer 2011
var map
var contentString = '<div id="content">' + '</div>';
//var infowindow = new google.maps.InfoWindow();
var markers = new Array();
var kmlLayers = new Array();

/*test for custom infowindow */
var myOptions = {
	
	//alignBottom:true,
    content: contentString,
	boxClass:'cnn_infobox',
	infoBoxClearance:new google.maps.Size(10, 10),
	pixelOffset: new google.maps.Size(20, -125)

	
        };
var infobox = new InfoBox(myOptions);


function makeMap(LAT, LNG, ZOOM, DATA)
{ 
/* set up basic map with custom style and controls */
    var MyStyle = [
    {
        featureType: "road",
        elementType: "all",
        stylers: [
        {
            visibility: "simplified"
        }, {
            saturation: -98
        }, {
            lightness: 96
        }, {
            gamma: 9.5
        }]
    }, {
        featureType: "water",
        elementType: "all",
        stylers: [
        {
            visibility: "simplified"
        }, {
            saturation: -100
        }, {
            lightness: -50
        }]
    }, {
        featureType: "administrative",
        elementType: "all",
        stylers: [
        {
            visibility: "on"
        }]
    }, {
        featureType: "landscape",
        elementType: "all",
        stylers: [
        {
            visibility: "simplified"
        }, {
            saturation: -100
        }, {
            lightness: 8
        }]
    }, {
        featureType: "poi.park",
        elementType: "all",
        stylers: [
        {
            lightness: 1
        }, {
            saturation: -100
        }, {
            gamma: 3.41
        }, {
            hue: "#6eff00"
        }]
    }, {
        featureType: "poi",
        elementType: "labels",
        stylers: [
        {
            gamma: 0.01
        }, {
            lightness: 30
        }]
    }, {
        featureType: "transit",
        elementType: "all",
        stylers: [
        {
            saturation: -99
        }, {
            visibility: "on"
        }]
    }, {
        featureType: "poi.place_of_worship",
        elementType: "all",
        stylers: [
        {
            hue: "#ff0000"
        }, {
            saturation: 26
        }, {
            lightness: 67
        }, {
            gamma: 0.87
        }]
    }, {
        featureType: "road",
        elementType: "labels",
        stylers: [
        {
            visibility: "off"
        }]
    }];
    var styledMapOptions =
    {
        name: "CNN"
    }
    cnnMapType = new google.maps.StyledMapType(MyStyle, styledMapOptions);
    var latlng = new google.maps.LatLng(LAT, LNG);
    myOptions =
    {
		draggable:false,
        maxZoom: 12,
        minZoom: 4,
        zoom: 7,
        center: latlng,
        panControl: true,
        zoomControl: true,
        mapTypeControl: true,
        scaleControl: true,
        streetViewControl: false
    };
    map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
    map.mapTypes.set('CNN', cnnMapType);
    map.setMapTypeId('CNN');
	/* load placemarks */
	if(DATA){
    $.ajax(
    {
        url: DATA,
        dataType: ($.browser.msie) ? "text" : "xml",
        success: function (data)
        {
            var xml;
            if (typeof data == "string")
            {
                xml = new ActiveXObject("Microsoft.XMLDOM");
                xml.async = false;
                xml.loadXML(data);
            }
            else
            {
                xml = data;
            }
            $(xml).find("item").each(function ()
            {
                $data = $(this);
                var basic_marker = ""; // = 'images/icon.png';
                var myLatlng = new google.maps.LatLng($data.find("Xcoord").text(), $data.find("Ycoord").text());
                var type = $data.find("adHoc10").text();
                basic_marker = 'images/' + type + '.png';
                var marker = new google.maps.Marker(
                {
                    icon: basic_marker,
                    position: myLatlng,
                    map: map,
                    title: $data.find("headline").text()
                });
                
                var mycontentString = '<div style="float:right">';
                if ($data.find("adHoc1").text())
                {
                    mycontentString += '<object width="416" height="374" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" id="ep">' + '<param name="allowfullscreen" value="true" />' + '<param name="allowscriptaccess" value="always" />' + '<param name="wmode" value="transparent" />' + '<param name="movie" value="http://i.cdn.turner.com/cnn/.element/apps/cvp/3.0/swf/cnn_416x234_embed.swf?context=embed&videoId=' + $data.find("adHoc1").text() + '" />' + '<param name="bgcolor" value="#000000" />' + '<embed src="http://i.cdn.turner.com/cnn/.element/apps/cvp/3.0/swf/cnn_416x234_embed.swf?context=embed&videoId=' + $data.find("adHoc1").text() + '" type="application/x-shockwave-flash" bgcolor="#000000" allowfullscreen="true" allowscriptaccess="always" width="416" wmode="transparent" height="374">' + '</embed>' + '</object>';
                    mycontentString += '</div>';
					mycontentString += '<div class="arrow"></div>'
                }
                else
                {
                    if ($data.find("image").text())
                    {	
						var mycontentString = '<div style="width:510px">';
                        mycontentString += '<div style="float:left;width:250px; margin-top: 10px;"><img src=' + $data.find("image").text() + ' style="float:left;padding:0px 10px 10px 10px;"></div>';
                    }
                    
                    mycontentString += '<div style="float:right;width:220px; margin-top: 10px;"><h4>' + $data.find("headline").text() + '</h4><p>' + '' + $data.find("blurb").text() + '</p>';
                    
                    if ($data.find("related1").text())
                    {
                        mycontentString += '<p><a href="' + $data.find("related1").attr('url') + '" target="_parent">' + $data.find("related1").text() + '</a></p>';
                    }
                    mycontentString += '</div></div>';
					mycontentString += '<div class="arrow"></div>'
                }
				marker.mycontentString = mycontentString;
				var obj =
                {
                    location: myLatlng,
                    number: type,
                    m: marker
                };
                markers.push(obj);
                marker.setMap(map);
                //add event to call infowindow
				
                google.maps.event.addListener(marker, 'click', function ()
                {
                    infobox.setContent(mycontentString);
                    infobox.open(map, marker);
                });
            });
        }
    });
	}
} 

function panToMarker(num){
	//map.panTo(markers[num-1].location);
	infobox.setContent(markers[num-1].m.mycontentString);
  infobox.open(map, markers[num-1].m);
};
/* function to zoom to certain set of markers */
function bounds(CAT)
{
    var bounds = new google.maps.LatLngBounds();
    for (i = 0; i < markers.length; i++)
    {
        if (CAT)
        {
            if (markers[i].number == CAT)
            {
                bounds.extend(markers[i].location);
            }
        }
        else
        {
            bounds.extend(markers[i].location);
        }
    }
    map.fitBounds(bounds);
} 

/*filter markers*/
function filterMarkers(CAT)
{
    if (CAT)
    {
        for (var i = 0; i < markers.length; i++)
        {
            if (markers[i].number == CAT)
            {
                if (markers[i].m.getVisible() == false)
                {
                    markers[i].m.setVisible(true);
                   // markers[i].m.setAnimation(google.maps.Animation.DROP);
                }
                else
                {
                    markers[i].m.setVisible(false);
                }
            }
        }
    }
    else
    {
        for (var i = 0; i < markers.length; i++)
        { 
			/* set all marker to visible */
            markers[i].m.setVisible(true);
         //   markers[i].m.setAnimation(google.maps.Animation.DROP);
        }
    }
} 
function showAllMarkers(){
	 for (var i = 0; i < markers.length; i++)
        { 
			/* set all marker to visible */
            markers[i].m.setVisible(true);
         //   markers[i].m.setAnimation(google.maps.Animation.DROP);
        }
}
function hideAllMarkers(){
	 for (var i = 0; i < markers.length; i++)
        { 
			/* set all marker to visible */
            markers[i].m.setVisible(false);
         //   markers[i].m.setAnimation(google.maps.Animation.DROP);
        }
}
/*kml functions*/
function addKml(NAME, URL, MOUSEENABLED)
{
	
	if(MOUSEENABLED==null){
	var MOUSEENABLED = true;	
	}
    var alreadyActive = false;
    for (var i = 0; i < kmlLayers.length; i++)
    {
        if (kmlLayers[i].name == NAME)
        {
            alreadyActive = true;
            kmlLayers[i].layer.setMap();
            kmlLayers.splice(i, 1);
            break;
        }
    }
    if (alreadyActive == false)
    { /* add it to map and store in kml list so we can remove it */
        var temp = new google.maps.KmlLayer(URL,{preserveViewport:true,suppressInfoWindows:true,clickable:MOUSEENABLED});
        kmlLayers.push(
        {
            name: NAME,
            layer: temp
        });
		//call custom infowindow to stop multiple ones glitch
		 google.maps.event.addListener(temp, 'click', function(kmlEvent) {
			 
			 
				  mycontentString = kmlEvent.featureData.infoWindowHtml;
				  infobox.setContent('<div style="float:right;padding:0px 10px 10px 10px;margin:0px">'+mycontentString+'</div><div class="arrow"></div>');
				  infobox.setPosition(kmlEvent.latLng);
				  var pos = kmlEvent.position;
				  infobox.open(map,pos);
			 
		 });
		
        temp.setMap(map);
		
		
    }
}
function showLayer(LAYER){
	for (var i = 0; i < kmlLayers.length; i++)
    {
        if (kmlLayers[i].name == LAYER)
        {
            kmlLayers[i].layer.setMap(map);
            break;
        }
    }
}
function hideLayer(LAYER){
	for (var i = 0; i < kmlLayers.length; i++)
    {
        if (kmlLayers[i].name == LAYER)
        {
            kmlLayers[i].layer.setMap();
            break;
        }
    }
}
