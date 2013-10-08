$(function(){
	
	
	/*
		0 - dashboard
		1 - lexiques
		2 - users
		3 - own
		4 - rapports de bugs
	*/
	
	var MODE = 0;
	var LEXIQUE_SELECTED = null;
	var ENTRY_SELECTED = null;
	var RESSOURCE_SELECTED = null;
	
	
	/****************************************************************************
										EVENTS
	*****************************************************************************/
	
	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	$('body').on('click', '#left_panel #content .listSelector', function(){
	
		var lex = $(this);
		$('#left_panel #content .listSelector').css('background-color', 'rgb(245,245,245)');
		lex.css('background-color', 'rgb(230,230,230)');
		
		if(lex.attr("todo") == null) return true; //Si rien n'est spécifié.
		else var todo = lex.attr("todo");
		
		
		
		
		//******************************Accueil (Dashboard)
		//******************************Accueil (Dashboard)
		if(MODE == 0){ 
			
			if(todo == "sectionLexique"){
			
				$('#left_panel #header').html('<h1>GESTION</h1><h3>Lexiques</h3>');
				$('#left_panel #content').load('parts/lexiques.left.content.php');
				$('#middle_panel #content').load('parts/lexiques.list.php');
				$('#right_panel #content').html('');
				MODE = 1;
				
			}else if(todo == "sectionUsers"){
			
				$('#left_panel #header').html('<h1>GESTION</h1><h3>Comptes</h3>');
				$('#left_panel #content').load('parts/user.left.content.php', function(){
					$('#middle_panel #content').load('parts/user.middle.list.php', function (){
						$('#right_panel #content').html('');
						MODE = 2;
					});
				});

			}else if(todo == "sectionLogs"){
			
				$('#left_panel #header').html('<h1>GESTION</h1><h3>Rapports de Bug</h3>');
				$('#left_panel #content').html('');
				$('#left_panel #content').load('parts/logs.left.list.lexiques.php', function(){
					$('#middle_panel #content').html('');
					$('#right_panel #content').html('');
					MODE = 4;
				});

			}else if(todo == "sectionOwn"){
			
				$('#middle_panel #content').html('');
				$('#right_panel #content').load('parts/user.right.printOne.php', {user_id: $(this).attr("user_id")});

			}
			
			
			
			
		//****************************************Users
		//****************************************Users
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
				
				
				
			}
			
			
			
			
		//****************************************Lexiques
		//****************************************Lexiques
		}else if(MODE == 1){ 
			
			if(todo == "createLexique"){
				
				LEXIQUE_SELECTED = null;
				$('#middle_panel #content').html('<div class="list"><h3>Création d\'un lexique</h3></div>');
				$('#right_panel #content').load('parts/lexique.right.createOne.php');
				
			}else if(todo == "refresh"){
				
				$('#right_panel #content').html('');
				$('#middle_panel #content').html('');
				$('#middle_panel #content').load('parts/lexiques.list.php');
				LEXIQUE_SELECTED = null;
				MODE = 1;
				
			}
			
			
			
			
		//****************************************Logs
		//****************************************Logs
		}else if(MODE == 4){ 
			
			if(todo == "printLogs"){
				
				if(lex.attr("lexique_id") != null){
					LEXIQUE_SELECTED = lex.attr("lexique_id");
					$('#middle_panel #content').html('');
					$('#middle_panel #content').load('parts/logs.middle.list.php', {lexique_id: LEXIQUE_SELECTED});
					$('#right_panel #content').html('');
				}
				
			}
			
		}
	
	
	
		if(todo == "dashboard"){
			$('#left_panel #header').load('parts/dashboard.header.php');
			$('#left_panel #content').load('parts/dashboard.content.php');
			$('#middle_panel #content').html('');
			$('#right_panel #content').html('');
			LEXIQUE_SELECTED = null;
			MODE = 0;
		}
		
		
	});
	
	
	
	
	
	

	
	
	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	$('body').on('click', '#middle_panel #content .listSelector', function(){
		
		var lex = $(this);
		$('#middle_panel #content .listSelector').css('background-color', 'rgb(240,240,240)');
		lex.css('background-color', 'rgb(230,230,230)');
		
		if(lex.attr("todo") == null) return true; //Si rien n'est spécifié.
		else var todo = lex.attr("todo");
		
		
		
		//******************************Accueil (Dashboard)
		//******************************Accueil (Dashboard)
		if(MODE == 0){ 
			
			if(false){
				
				
				
			}else if(false){
				
				
				
			}
			
			
			
			
		//****************************************Users
		//****************************************Users
		}else if(MODE == 2){ 
			
			if(todo == "printUser"){
				
				$('#right_panel #content').html(''); 
				$('#right_panel #content').load('parts/user.right.printOne.php', {user_id: $(this).attr("user_id")});
				
			}
			
			
			
			
		//****************************************Lexiques
		//****************************************Lexiques
		}else if(MODE == 1){ 
			
			if(todo == "printLexique" && $(this).attr("lexique_id")){
				
				$('#right_panel #content').html('');
				LEXIQUE_SELECTED = $(this).attr("lexique_id");
				$('#right_panel #content').load('parts/lexiques.printOne.php', {lexique_id: LEXIQUE_SELECTED});
				
			}else if(todo == "printEntry"){
				
				$('#right_panel #content').html('');
				ENTRY_SELECTED = $(this).attr("entry_id");
				$('#right_panel #content').load('parts/entry.right.printOne.php', {lexique_id: LEXIQUE_SELECTED, entry_id: $(this).attr("entry_id")});
				
			}
			
			
			
			
		//****************************************Logs
		//****************************************Logs
		}else if(MODE == 4){ 
			
			if(todo == "printLog"){
				
				if(lex.attr("log_id") != null && LEXIQUE_SELECTED != null){
					$('#right_panel #content').load('parts/logs.right.printLog.php', {lexique_id: LEXIQUE_SELECTED, log_id:lex.attr("log_id")});
				}
				
			}else if(false){
				
				
				
			}
			
		}
		
	});
	
	
	
		
		
		
	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * ** * * * * * * * * * * * * * * * * * * * * *
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *  * * * * * * * * * * * * * * * * * * */
	$('body').on('click', '#right_panel #content .listSelector', function(){
		
		var lex = $(this);
		$('#right_panel #content .listSelector').css('background-color', 'rgb(240,240,240)');
		lex.css('background-color', 'rgb(230,230,230)');
		
		if(lex.attr("todo") == null) return true; //Si rien n'est spécifié.
		else var todo = lex.attr("todo");
		
		
		
		//******************************Accueil (Dashboard)
		//******************************Accueil (Dashboard)
		if(MODE == 0){ 
			
			if(false){
				
				
				
			}
			
			
		//****************************************Users
		//****************************************Users
		}else if(MODE == 2){
			
			if(todo == "changeType"){
			
				$('#right_panel #content').load('parts/user.changeType.php', 
					{
						user_id: $(this).attr('user_id'),
						user_type: $('#right_panel #content #input_user_type').val()
					}
				);
				
			}else if(todo == "changePass"){
				
				$('#right_panel #content').load('parts/user.changePass.php', 
					{
						user_id: $(this).attr('user_id'),
						user_old_pass: $('#right_panel #content #input_user_pass1').val(),
						user_new_pass: $('#right_panel #content #input_user_pass2').val(),
						user_new_pass2: $('#right_panel #content #input_user_pass3').val()
					}
				);
				
			}else if(todo == "deleteUser"){
			
				$('#right_panel #content').load('parts/user.delete.php', 
					{
						user_id: $(this).attr('user_id')
					}
				);
				
			}else if(todo == "confirmDeleteUser"){
			
				$('#right_panel #content').load('parts/user.confirm.delete.php', 
					{
						user_id: $(this).attr('user_id')
					}
				);
				
			}else if(todo == "abordDeleteUser"){
			
				$('#right_panel #content').html('<div class="list"><h3>Annulé !</h3></div>');
				
			}
			
			
			
			
			
		//****************************************Lexiques
		//****************************************Lexiques
		}else if(MODE == 1){ 
			
			if(todo == "toggleActivity"){
				
				$('#right_panel #content').load('parts/lexique.toggleActivity.php', {lexique_id: LEXIQUE_SELECTED});
				
			}else if(todo == "deleteLexique"){
				
				$('#right_panel #content').html(
					'<div class="list"><h3>Êtes-vous sûr de vouloir supprimer ce Lexique ?</h3></div>'+
					'<div todo="confirmDeleteLexique" class="listSelector"><h3>Oui !</h3></div>'+
					'<div todo="abordDeleteLexique" class="listSelector"><h3>Heu... Non.</h3></div>'
				);
				
			}else if(todo == "confirmDeleteLexique"){
				
				$('#right_panel #content').load('parts/lexique.delete.php', {lexique_id: LEXIQUE_SELECTED});
				LEXIQUE_SELECTED = null;
				
			}else if(todo == "abordDeleteLexique"){
				
				$('#right_panel #content').html('<div class="list"><h3>Ouf, on a encore eu de la chance !</h3></div>');
				LEXIQUE_SELECTED = null;
				
			}else if(todo == "submitNewLexique"){
				
				$('#right_panel #content').load('util/createNewLexique.php', 
					{lexique_name: $('#lex_name').val(),
					lexique_attr: $('#lex_attr').val()}
				);
				LEXIQUE_SELECTED = null;
				
			}else if(todo == "printEntrys"){
				
				$('#right_panel #content').html("");
				$('#middle_panel #content').load('parts/entry.middle.list.php', 
					{lexique_id: LEXIQUE_SELECTED}
				);
			}else if(todo == "printRessource"){
				
				$('#right_panel #content').html('');
				RESSOURCE_SELECTED = $(this).attr("ress_id");
				$('#right_panel #content').load('parts/ress.right.printOne.php', {lexique_id: LEXIQUE_SELECTED, ress_id: $(this).attr("ress_id")});
				
			}else if(todo == "deleteEntry"){
				
				$('#right_panel #content').html(
					'<div class="list"><h3>Houla... Vous voulez supprimer cette entrée ?</h3></div>'+
					'<div todo="confirmDeleteEntry" class="listSelector"><h3>Pour sûr !</h3></div>'+
					'<div todo="abordDeleteEntry" class="listSelector"><h3>Je vais encore réfléchir...</h3></div>'
				);
			}else if(todo == "confirmDeleteEntry"){
				
				$('#right_panel #content').load('parts/entry.delete.php', {lexique_id: ENTRY_SELECTED, entry_id: ENTRY_SELECTED});
				$('#middle_panel #content').html("");
				$('#middle_panel #content').load('parts/entry.middle.list.php', 
					{lexique_id: LEXIQUE_SELECTED}
				);
				ENTRY_SELECTED = null;
				
			}else if(todo == "abordDeleteEntry"){
				
				$('#right_panel #content').html('<div class="list"><h3>Pas de soucis, prennez votre temps.</h3></div>');
				ENTRY_SELECTED = null;
				
			}else if(todo == "deleteRessource"){
				
				RESSOURCE_SELECTED = $(this).attr("ress_id");
				$('#right_panel #content').html(
					'<div class="list"><h3>Cette ressource ne vous plait vraiment pas ? :o</h3></div>'+
					'<div todo="confirmDeleteRessource" class="listSelector"><h3>Oui, je veux l\'enlever.</h3></div>'+
					'<div todo="abordDeleteRessource" class="listSelector"><h3>Tout compte fait, elle est pas si mauvaise.</h3></div>'
				);
				
			}else if(todo == "confirmDeleteRessource"){
				
				$('#right_panel #content').load('parts/ress.delete.php', {lexique_id: ENTRY_SELECTED, entry_id: ENTRY_SELECTED, ress_id: RESSOURCE_SELECTED});
				RESSOURCE_SELECTED = null;
				
			}else if(todo == "abordDeleteRessource"){
				
				$('#right_panel #content').html('<div class="list"><h3>Ben alors, vous voyez !</h3></div>');
				RESSOURCE_SELECTED = null;
				
			}
			
			
			
			
		//****************************************Logs
		//****************************************Logs
		}else if(MODE == 4){ 
			
			if(false){
				
				
				
			}
			
		}
		
	});
	
	
	
	
});