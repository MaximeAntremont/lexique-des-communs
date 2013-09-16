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
					alert(data['id']);
				}).fail(function(){
					alert("fail ?");
				});
				
			});
			
		});
		
	</script>
</html>