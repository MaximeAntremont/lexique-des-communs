	</body>
	<script>
		
		$(function(){
			
			$(".entryClick").click(function (){
				
				var entry_id = this.id.replace("id", "");
				$.ajax({
					type: "POST",
					url: "utils/getEntry.util.php",
					dataType: "json",
					data: {entry_id: entry_id}
				}).done(function(data) {
					
					var ress = "";
					var links = "";
					
					if(data.ressources != null)
					data['ressources'].forEach(function(obj){
						ress += "- id: "+obj.id+"<br/>";
						ress += "----- val: "+obj.val+"<br/>";
						ress += "----- type: "+obj.type+"<br/>";
						ress += "----- trend: "+obj.trend+"<br/>";
						ress += "----- alert: "+obj.alert+"<br/>";
						ress += "----- category_id: "+obj.category_id+"<br/>";
						ress += "----- create_date: "+obj.create_date+"<br/>";
						// ress += "<br/>";
					});
					else ress = "-null<br/>";
					
					if(data.links != null)
						data['links'].forEach(function(obj){
							links += "- id: "+obj.id+"<br/>";
							links += "----- val: "+obj.val+"<br/>";
							links += "----- from: "+obj.from+"<br/>";
							links += "----- to: "+obj.to+"<br/>";
							links += "----- type: "+obj.type+"<br/>";
							// links += "<br/>";
						});
					else links = "-null<br/>";
					
					$('#test').html(
						"id: "+ data.id +"<br/>"+
						"val: "+ data.val +"<br/>"+
						"ressources: <br/>"+ress+
						"<br/>links: <br/>"+links
					);
					
				}).fail(function(){
					alert("fail !");
				});
				
			});
			
		});
		
	</script>
</html>