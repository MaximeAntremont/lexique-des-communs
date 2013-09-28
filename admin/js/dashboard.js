$(function(){
	
	
	
	
	/****************************************************************************
										EVENTS
	*****************************************************************************/
	
	$('body').on('click', '#dashboard', function(){
		
		$('#lef_panel #header h3').load('parts/dashboard.header.php');
		$('#lef_panel #content').load('parts/dashboard.content.php');
		$('#middle_panel').html('');
		$('#right_panel').html('');
		
	});
	
	$('body').on('click', '#gestionLexiques', function(){
		
		$('#left_panel #header').html('<h1>GESTION</h1><h3>Lexiques</h3>');
		$('#lef_panel #content').load('parts/gestion.lexique.content.php');
		$('#middle_panel').html('parts/gestion.lexique.list.php');
		$('#right_panel').html('');
		// alert();
		
	});
	
	$('body').on('click', '#gestionUsers', function(){
		
	});
	$('body').on('click', '#gestionOwn', function(){
		
	});

	
	
	
	
	
	
	
});