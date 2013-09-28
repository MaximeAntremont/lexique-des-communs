$(function(){
	
	
	var MODE = 0;
	/*
		0 - dashboard = 
		1 - lexiques
		2 - users
		2 - own
	*/
	var LEXIQUE_SELECTED = null;
	
	
	/****************************************************************************
										EVENTS
	*****************************************************************************/
	
	$('body').on('click', '#dashboard', function(){
		
		$('#left_panel #header').load('parts/dashboard.header.php');
		$('#left_panel #content').load('parts/dashboard.content.php');
		$('#middle_panel #content').html('');
		$('#right_panel #content').html('');
		
	});
	
	$('body').on('click', '#gestionLexiques', function(){
		
		$('#left_panel #header').html('<h1>GESTION</h1><h3>Lexiques</h3>');
		$('#left_panel #content').load('parts/gestion.lexiques.left.content.php');
		$('#middle_panel #content').load('parts/gestion.lexiques.list.php');
		$('#right_panel #content').html('');
		
		MODE = 1;
		
	});
	
	$('body').on('click', '#middle_panel #content .listSelector', function(){
		var lex = $(this);
		lex.css('background-color', 'rgb(230,230,230)');
		if(lex.attr("lexique") != null && MODE == 1){
			
			$('#right_panel #content').load('parts/lexiques.printOne.php', {attr: lex.attr('lexique')});
			// });
			
		}
		
	});
	
	$('body').on('click', '#gestionUsers', function(){
		
	});
	$('body').on('click', '#gestionOwn', function(){
		
	});

	
	
	
	
	
	
	
});