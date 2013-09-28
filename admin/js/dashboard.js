$(function(){
	
	
	var MODE = 0;
	/*
		0 - dashboard = 
		1 - lexiques
		2 - users
		3 - own
	*/
	var LEXIQUE_SELECTED = null;
	
	
	/****************************************************************************
										EVENTS
	*****************************************************************************/
	
	
	$('body').on('click', '#middle_panel #content .listSelector', function(){
	
		var lex = $(this);
		$('#middle_panel #content .listSelector').css('background-color', 'none');
		lex.css('background-color', 'rgb(230,230,230)');
		
		
		
		if(lex.attr("lexique") != null && MODE == 1){
			$('#right_panel #content').load('parts/lexiques.printOne.php', {attr: lex.attr('lexique')});
			
		}else if(lex.attr("user_id") != null && MODE == 2){
			$('#right_panel #content').load('parts/comptes.right.printOne.php', {user_id: lex.attr('user_id')});
			
		}
		
	});
	
	$('body').on('click', '#left_panel #content .listSelector', function(){
	
		var lex = $(this);
		// $('#left_panel #content .listSelector').css('background-color', 'rgb(240,240,240)');
		$('#left_panel #content .listSelector').css('background-color', 'none');
		lex.css('background-color', 'rgb(230,230,230)');
		
		if(lex.attr("todo") == null) return true; //Si rien n'est spécifié.
		else var todo = lex.attr("todo");
		
		if(MODE == 0){ //Accueil (Dashboard)
			
			if(todo == "sectionLexique"){
			
				$('#left_panel #header').html('<h1>GESTION</h1><h3>Lexiques</h3>');
				$('#left_panel #content').load('parts/gestion.lexiques.left.content.php');
				$('#middle_panel #content').load('parts/gestion.lexiques.list.php');
				$('#right_panel #content').html('');
				MODE = 1;
				
			}else if(todo == "sectionUsers"){
			
				$('#left_panel #header').html('<h1>GESTION</h1><h3>Comptes</h3>');
				$('#left_panel #content').load('parts/comptes.left.content.php', function(){
					$('#middle_panel #content').load('parts/comptes.middle.list.php', function (){
						$('#right_panel #content').html('');
						MODE = 2;
					});
				});

			}else{
			
				$('#left_panel #header').load('parts/dashboard.header.php');
				$('#left_panel #content').load('parts/dashboard.content.php');
				$('#middle_panel #content').html('');
				$('#right_panel #content').html('');
				MODE = 0;
				
			}
			
		}else if(MODE == 2){
			
			if(todo == "newUser"){
				
				$('#middle_panel #content').html('<div class="list"><h3>Nouvel utilisateur</h3></div>');
				$('#right_panel #content').html(
					'<form action="util/addUser.php" method="POST" style="margin-top:20px;">'+
						'<input type="text" name="user_name" placeholder="username" />'+
						'<input type="password" name="user_pass1" placeholder="mot de passe" />'+
						'<input type="password" name="user_pass2" placeholder="mot de passe" />'+
						'<select name="user_type">'+
							'<option value="21">Modérateur</option>'+
							'<option value="10">Rédacteur</option>'+
						'</select>'+
						'<input type="submit" value="Créer" />'+
					'</form>'
				);
				
			}else if(todo == "sectionUsers"){
				
				
				
			}else{
			
				$('#left_panel #header').load('parts/dashboard.header.php');
				$('#left_panel #content').load('parts/dashboard.content.php');
				$('#middle_panel #content').html('');
				$('#right_panel #content').html('');
				MODE = 0;
				
			}
			
		}else if(MODE == 1){
			
			if(todo == "sectionLexique"){
				
				
				
			}else if(todo == "sectionUsers"){
				
				
				
			}else{
			
				$('#left_panel #header').load('parts/dashboard.header.php');
				$('#left_panel #content').load('parts/dashboard.content.php');
				$('#middle_panel #content').html('');
				$('#right_panel #content').html('');
				MODE = 0;
				
			}
			
		}
		
		if(todo == "dashboard"){
			$('#left_panel #header').load('parts/dashboard.header.php');
			$('#left_panel #content').load('parts/dashboard.content.php');
			$('#middle_panel #content').html('');
			$('#right_panel #content').html('');
			MODE = 0;
		}
		
		
	});

	
	
	
	
	
	
	
});