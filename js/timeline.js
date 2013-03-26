//initial settings
var setupWidth = 980;
var barWidth = 6;
var count = -1;
var h = 180 * 80;
var items;
var intro;
//deals with months if needed
var months = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sept","Oct","Nov","Dec"];

function doHighlight(){
	
	$('.graph_bar').css('background','#f0f0f0');
	$('#'+count).css('background','#ff0000');
	
}

function arrows(){
	var a;
	(intro.length>0)?a=0:a=1;
	if(count>(0-1)+a){
		$('#left_arrow').show();
	}else{
		$('#left_arrow').hide();
	}
	if(count<items.length-a-1){
		$('#right_arrow').show();
	}else{
		$('#right_arrow').hide();
	}
	
}
function init_timeline(){
	$.ajax(
    {
        url: 'timeline.data.xml',
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
			
			var v = 0;
			//sort by date order (acending atm)
			items =  $(xml).find("point");
			intro =  $(xml).find("intro");
			//items.sort(function(a, b){
     		//	return (parseInt($(a).find("date").attr("year")) - parseInt($(b).find("date").attr("year")));
  			//});
			//get date range for spacing etc
			var startDate = new Date(parseInt(items.first().find("date").attr("year")),parseInt(items.first().find("date").attr("month"))-1,1);
			//end date we round up to first month of next year to allow spacing
			if(parseInt(items.last().find("date").attr("month"))-1 == 11){
			var endDate = new Date(parseInt(items.last().find("date").attr("year"))+1,0,1);	
			}else{
			var endDate = new Date(parseInt(items.last().find("date").attr("year")),parseInt(items.last().find("date").attr("month")),1);	
			}
			
			var range = (endDate.getTime()-startDate.getTime());
			var countYears = (endDate.getFullYear()-startDate.getFullYear());
			var countMonths = ((countYears+1)*12)-startDate.getMonth()-(12-endDate.getMonth());
			for(var z =0; z < countMonths; z++){
				$('#graph_holder').append('<div class="graph_spacer" style="left:'+z * (980 / (countMonths))+'px"></div>');	
				var d;
				if(z>12){
						d = new Date(startDate.getFullYear()+1,startDate.getMonth()+(z-12));
				}else{
					d = new Date(startDate.getFullYear(),startDate.getMonth()+z);
				}
				$('#graph_holder').append('<div class="graph_date" style="left:'+z * (980 / (countMonths))+'px">'+months[d.getMonth()]+' '+d.getFullYear()+'</div>');		

			}
			
			//for labelling
			var midDate = new Date(startDate.getTime()+(range/2));
			//add start and end and mid dates to graph
			/*if(countYears>1){
			$('#graph_holder').append('<div id="startDate" class="graph_date" style="left:0px">'+months[startDate.getMonth()]+', '+startDate.getFullYear()+'</div>');		
			$('#graph_holder').append('<div id="midDate" class="graph_date" style="left:440px">'+months[midDate.getMonth()]+', '+midDate.getFullYear()+'</div>');		
			$('#graph_holder').append('<div id="endDate" class="graph_date" style="right:0px">'+months[endDate.getMonth()]+', '+endDate.getFullYear()+'</div>');
			}else{
			//if we are only dealing with a 1 year span we need to label months as well	
			$('#graph_holder').append('<div id="startDate" class="graph_date" style="left:0px">'+months[startDate.getMonth()]+' '+startDate.getFullYear()+'</div>');		
			$('#graph_holder').append('<div id="midDate" class="graph_date" style="left:440px">'+months[midDate.getMonth()]+', '+midDate.getFullYear()+'</div>');		
			$('#graph_holder').append('<div id="endDate" class="graph_date" style="right:0px">'+months[endDate.getMonth()]+' '+endDate.getFullYear()+'</div>');
			}*/
			//set the width of each marker if it is not defined
			if(!barWidth){
				barWidth = 980/((endDate.getFullYear()-startDate.getFullYear()/12));
			}
			//make intro panel if exists
			if(intro.length>0){
				
				var a = '<li>';
				a +='<div class="timeline_item">'
				a +='  <div class="timeline_item_image"><img src="'+intro.find("image").text()+'" /></div>'
				a +='  <div class="timeline_item_blurb_holder">'
				a +='    <div class="timeline_item_blurb_title">'+intro.find("date").text()+'</div>'
				a +='    <div class="timeline_item_blurb_body">'+intro.find("blurb").text()+'</div>'
				if(intro.find("footer").text()){
					a +='    <div class="timeline_item_blurb_footer">'+intro.find("footer").text()+'</div>'
				}else{
					a +='    <div class="timeline_item_blurb_footer"></div>'
				}
				a +='  </div>'
				a +='</div>'
				a +='</li>'
				
				$('#graph_list').append(a);
				
			}
			//itterate through items
           items.each(function ()
            {
				
				$data = $(this);						
				var myDate = new Date(parseInt($data.find("date").attr("year")),parseInt($data.find("date").attr("month"))-1,parseInt($data.find("date").attr("day")));
				var left = (myDate.getTime()-startDate)*((setupWidth-barWidth)/range)+(barWidth/2);
				var a = '<li>';
				a +='<div class="timeline_item">'
				a +='  <div class="timeline_item_image"><img src="'+$data.find("image").text()+'" /></div>'
				a +='  <div class="timeline_item_blurb_holder">'
				a +='    <div class="timeline_item_blurb_title">'+$data.find("date").text()+'</div>'
				a +='    <div class="timeline_item_blurb_body">'+$data.find("blurb").text()+'</div>'
				if($data.find("footer").text()){
					a +='    <div class="timeline_item_blurb_footer">'+$data.find("footer").text()+'</div>'
				}else{
					a +='    <div class="timeline_item_blurb_footer"></div>'
				}
				a +='  </div>'
				a +='</div>'
				a +='</li>'
				if($data.find("blurb").text()){
				$('#graph_list').append(a);
				$('#graph_holder').append('<div id="'+v+'" class="graph_bar" style="width:'+barWidth+'px;left:'+left+'px"><span>'+months[myDate.getMonth()]+' '+myDate.getDate()+', '+myDate.getFullYear()+'</span></div>');		
				v++;
				}else{
				$('#graph_holder').append('<div class="graph_bar_inactive" style="width:'+barWidth+'px;left:'+left+'px""></div>');		
				}
			});
			//$('#0').css('background','#ff0000');
			$('.graph_bar').click(function(){
				count = $(this).attr('id');
				$('.graph_bar').css('background','#f0f0f0');
				$(this).css('background','#ff0000');
				if(intro.length>0){
				$('#mover').animate({marginLeft: 50-($(this).attr('id')*881)-881 }, 500);
				}else{
				$('#mover').animate({marginLeft: 50-($(this).attr('id')*881) }, 500);

				}
				arrows();
			});
		}
		
		
	});
	
	$('#left_arrow').hide();
	function left(){
	var a;
	(intro.length>0)?a=0:a=1;
		if(count>(0-1)+a){
		$('#left_arrow').show();
		$('#right_arrow').show();
		count--;
		doHighlight();
		$('#mover').animate({marginLeft: '+=881'}, 500);
		}else{
		$('#left_arrow').hide();	
		}
	arrows();
	}
	$('#left_arrow').click(left);
	function right(){
		var a;
	(intro.length>0)?a=0:a=1;
	
		if(count<items.length-a-1){
			$('#right_arrow').show();
			$('#left_arrow').show();
		count++;
		doHighlight();
		$('#mover').animate({marginLeft: '-=881'}, 500);
		}else{
		$('#right_arrow').hide();	
		}
		arrows();
	}
	$('#right_arrow').click(right);
	
	
	
$('html').keypress(function(event) {
	if(event.keyCode==37){
	left();	
	}
	if(event.keyCode==39){
		right();
	}
  
});
}
