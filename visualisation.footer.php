	</body>
	<script>
		
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
				
				winW_m = winW/2;
				winH_m = winH/2;
				DIAGONAL = Math.ceil(Math.sqrt( Math.pow(winW,2)+Math.pow(winH,2)));
			}

			detectScreen();

			$('#canvas').attr('width', winW-30);
			$('#canvas').attr('height', winH-30);
			
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
		
	</script>
</html>