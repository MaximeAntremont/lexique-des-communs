$(function(){
	
	var canvas = document.getElementById('canvas');
	var context = canvas.getContext('2d');
	var winW = 630, winH = 460;
	var winW_m, winH_m;
	var DIAGONAL = 600;

	function detectScreen (){
		if (document.body && document.body.offsetWidth) {
			winW = document.body.offsetWidth;
			winH = document.body.offsetHeight;
		}
		if (document.compatMode=='CSS1Compat' && document.documentElement && document.documentElement.offsetWidth ) {
			winW = document.documentElement.offsetWidth;
			winH = document.documentElement.offsetHeight;
		}
		if (window.innerWidth && window.innerHeight) {
			winW = window.innerWidth;
			winH = window.innerHeight;
		}
		
		winW -= 80;
		winH -= 80;
		winW_m = winW/2;
		winH_m = winH/2;
		DIAGONAL = Math.ceil(Math.sqrt( Math.pow(winW,2)+Math.pow(winH,2)));
	}

	detectScreen();
	$('#canvas').attr('width', winW);
	$('#canvas').attr('height', winH);
	$('#canvas').css('position', 'fixed');
	$('#canvas').css('top', '40px');
	$('#canvas').css('left', '40px');
	$('#canvas').css('z-index', '5');
	
	$(window).resize(function (){
		
		detectScreen();
		$('#canvas').attr('width', winW);
		$('#canvas').attr('height', winH);
		$('#canvas').css('position', 'fixed');
		$('#canvas').css('top', '40px');
		$('#canvas').css('left', '40px');
		$('#canvas').css('z-index', '5');
		
	});
	
	var cache_panel = new Cache($('#cache_panel'),$('#cache_panel #header'),$('#cache_panel #content'),$('#cache_panel #footer'));
	
	cache_panel.startWaiting(true);
	$.ajax({
		type: "POST",
		url: "utils/getIndex.util.php",
		dataType: "json"
	}).done(function(data) {
		
		var letters = [];
		var txt = '<div id="index">';
		
		if(data != null)
			data.forEach(function(obj){
				if(obj.select != null)
					txt += '<div class="letter-on" id="char-'+ obj['char'] +'" >'+ obj['char'] +'</div>';
				else
					txt += '<div class="letter-off" id="char-'+ obj['char'] +'" >'+ obj['char'] +'</div>';
				letters['char-'+obj['char']] = obj['select'];
			});
			
		txt += "</div>";
		$("#top_panel").append(txt);
		
		$(".letter-on").click(function(){
			// var letter = this.id.replace("cahr-", "");
			$("#top_left_corner #part_one").html("<h1>"+ letters[this.id][0]['val'] +"</h1>");
			$("#top_left_corner #part_two").html("");
			
			if(letters[this.id])
				letters[this.id].forEach(function (entry){
					$("#top_left_corner #part_two").append("<h3 id='id"+ entry.id +"' >"+ entry.val +"</h3>");
				});
			
		});
		
		
		cache_panel.stopWaiting(true);
	}).fail(function (a,b,c){
		console.debug(a+" | "+b+" | "+c);
	});
	
	
	
	
	$(".entryClick").click(function (){
	
		var entry_id = this.id.replace("id", "");
		
		$.ajax({
			type: "POST",
			url: "utils/getEntry.util.php",
			dataType: "json",
			data: {entry_id: entry_id}
		}).done(function(data) {
		
			context.clearRect(0,0,winW,winH);
			context.textAlign = "center"
			context.font = '20px TEX';
			
			var ress = [];
			
			if(data.ressources != null){
				
				var circle = {
					r : (DIAGONAL/3)/2,
					step : 2 / data.ressources.length,
					cursor : 0,
					center: {x: winW_m, y:winH_m}
				}
				
				data['ressources'].forEach(function(obj){
					
					// var x = (Math.random() > 0.5) ? (Math.random()*300)+winW_m : (winW_m-(Math.random()*300));
					// var y = (Math.random() > 0.5) ? (Math.random()*300)+winH_m : (winH_m-(Math.random()*300));
					
					var x = circle.center.x + (Math.cos(circle.cursor*Math.PI) * circle.r);
					var y = circle.center.y + (Math.sin(circle.cursor*Math.PI) * circle.r);
					
					circle.cursor += circle.step;
					
					context.fillText(obj.val, x, y);
					ress[obj.id] = {x: x, y:y};
					
				});
				
			}
			
			if(data.links != null)
				data['links'].forEach(function(obj){
					
					var from = ress[obj.from];
					var to = ress[obj.to];
					
					context.beginPath();
					context.moveTo(from.x, from.y);
					context.lineTo(to.x, to.y);
					context.stroke();
					
				});
			// else links = "-null<br/>";
			
			// $('#test').html(
				// "id: "+ data.id +"<br/>"+
				// "val: "+ data.val +"<br/>"+
				// "ressources: <br/>"+ress+
				// "<br/>links: <br/>"+links
			// );
			
		}).fail(function(){
			alert("fail !");
		});
		
	});
	
});