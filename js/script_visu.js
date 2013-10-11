$(function(){
	var gpu = new GPU(true);
	var winW = 630, winH = 460;
	var winW_m, winH_m;
	var DIAGONAL = 600;
	var ENTRYS = [];
	var LETTERS = [];
	var entry_selected_id;
	var entry_selected;
	var ressource_selected;
	var zoomFactor = 1;
	var zoom = 0;
	var mouseClicked = false;
	var lastRessource_selected;
	var linksToDraw = [];
	var linkSelected = null;
	var selecting = false;
	var IMAGES = {};
	
	$.fn.selectRange = function(start, end) {
		if(!end) end = start;
		return this.each(function() {
			if (this.setSelectionRange) {
				this.focus();
				this.setSelectionRange(start, end);
			} else if (this.createTextRange) {
				var range = this.createTextRange();
				range.collapse(true);
				range.moveEnd('character', end);
				range.moveStart('character', start);
				range.select();
			}
		});
	};
	
	marges = {
		'mouseMovePressInteration': 0,
		'mouseMovePressLimit': 20,
		'zoomUp' : 0.7,
		'zoomDown' : -0.7
	};
	
	constantes = {
		'delaiApparitionMots' : 100
	};
	
	var CTRL = false;
	var cache_panel = new Cache($('#cache_panel'),
	$('#cache_panel #header'),
	$('#cache_panel #content'),
	$('#cache_panel #footer'));
	var ressources = [];
	
	cache_panel.startWaiting();
	
	var canvas = document.getElementById('canvas');
	var context = canvas.getContext('2d');
	
	var screen = new GPUScreen({
		canvas : canvas,
		autoRefresh : false,
		delay : 2,
		autoClear : true
	});
	var anim = new GPUFrame (false);
	
	init($('#canvas'), function (){
		cache_panel.stopWaiting(function(){
			$('#cache_panel').hide();
			$("#entrys").show();
		})
	;});
	
	
	$(window).on('resize orientationchange', function (){
		var oldMiddle = {x: winW_m,y: winH_m};
		detectScreen();
		var offset = {x: winW_m-oldMiddle.x, y: winH_m-oldMiddle.y};
		
		$('#canvas').attr('width', winW);
		$('#canvas').attr('height', winH);
		$('#canvas').css('position', 'fixed');
		$('#canvas').css('top', '40px');
		$('#canvas').css('left', '40px');
		$('#canvas').css('z-index', '5');
		$('#entrys .content').css('width', winW);
		$('#entrys .content').css('height', winH-40);
		
		if(ressources.length > 0)
			ressources.forEach(function(obj){
				obj.x( obj.x() + offset.x );
				obj.y( obj.y() + offset.y );
			});
		
		screen.draw(gpu.getFrame(), true);
	});
	
	
	
	
/************************************************************
							FRAME
*************************************************************/
	anim.write(function (canvas, ctx, frame, vars){
		
		var movement = 0;
		
		if(ressources.length > 0){ //on dessine les ressources
			ressources.forEach(function(obj){
				
				if(obj.visible()){
				
					var dir = new Vector (0,0);
					
					ressources.forEach(function(objB){
						if(obj != objB && objB.visible()){
							
							var RpathTo = new Vector(objB.x(), objB.y(), obj.x(), obj.y());
							var translate = new Vector (0,0);
							
							var spaces = obj.spaceBeetween( objB );
							
							if(	spaces.x >= 5 || spaces.y >= 5){
							}else{
								RpathTo.resize(2);
								if(spaces.x < 5) translate.x(RpathTo.x());
								if(spaces.y < 5) translate.y(RpathTo.y()*2);
								movement = 1;
							}
							dir.add(translate);
						
						}
						
					});
					
					dir.resize(2);
					obj.direction(dir.toArray());
					
					obj.move();
					obj.draw(ctx, IMAGES);
					
				}
			});
			if(movement == 0) movement = -1;
		}
		
		if(linksToDraw.length > 0) //on dessine les liens
			linksToDraw.forEach(function (link){
				link.draw(context, ressources);
			});
			
		// console.debug(frame);
		if(movement == -1 && cursor >= cursorRefs.length) screen.stop();
	});
	
	
	var cursor = 0;
	var cursorRefs = [];
	function showSlowly (){
		if(cursor < cursorRefs.length){
			ressources[cursorRefs[cursor]].visible(true);
			cursor++;
			setTimeout(showSlowly, constantes.delaiApparitionMots);
		}
	}
	
	
	
	
	
	
	
	
	
/************************************************************
							EVENTS
*************************************************************/
	
	
	$('#canvas').mousedown(function(){
		// console.debug('mouseClicked: '+mouseClicked);
		mouseClicked = true;
	});
	
	var holdCords = [];
	
	// $(document).on('vmousedown', function(event){
		// holdCords.holdX = event.pageX;
		// holdCords.holdY = event.pageY;
	// });

	$('#canvas').on('taphold', function(e){
		
		if(selecting) return;
		
		e.pageX = holdCords.holdX;
		e.pageY = holdCords.holdY;
		
		lastRessource_selected = ressource_selected;
		ressource_selected = null;
		marges.mouseMovePressInteration = 0;
		
		if(ressources.length > 0){
		
			ressources.forEach(function(obj){
			
				if(ressource_selected == null
					&& obj.visible()
					&& obj.isOver({x: e.pageX-40, y: e.pageY-40})){
					ressource_selected = obj;
					obj.alpha(0.7);
					
				}else{
					obj.alpha(0.3);
				}
								 
			});
			
			if(ressource_selected == null){
				
				ressources.forEach(function(objB){objB.alpha(0.5);});
				ressource_selected = null;
				linksToDraw = [];
				printRessourceInfos();
				
			}else{
				drawLinks();
				printRessourceInfos();
				
				if(ressource_selected != lastRessource_selected && !selecting){
					var isAddRequest = false;
					cache_panel.modify(
						'<span>Nouveau Lien</span>',
						'<select id="sliderTypology" style="width: 350px;margin-left:15px;margin-top:20px;" >'
							+'<option value="1" >Accord/Désaccord</option>'
							+'<option value="2" >Inclusion/Exclusion</option>'
						+'</select>'
						+ '<div id="sliderValue" style="width: 350px;margin-left:15px;margin-top:20px;" ></div>',
						'<div><span class="cliquable">Annuler</span><span class="cliquable">Ajouter</span></div>');
					
					$( "#sliderValue" ).slider({
						step: 0,
						min: -100, 
						value: 0,
						max: 100
					}).css('background-color', 'rgb(180, 180, 180)');
					
					cache_panel.open();
					
					$(".cliquable").click(function(){
						if($(this).html() == "Annuler"){
							cache_panel.close();
						}
						if($(this).html() == "Ajouter" && !isAddRequest){
							isAddRequest = true;
							sendNewLink ($('#sliderTypology').val(), $('#sliderValue').slider( "value" ), function(){
								setTimeout(function(){
									fetchEntryData( entry_selected_id, function(){
										cache_panel.stopWaiting(true);
										cache_panel.close();
									});
								}, 1000);
							});
						}
						
					});
				}
			}
			
			screen.draw(gpu.getFrame(), true);
			
		}
		
	});
	
	$('#canvas').on('vmouseup', function(e){
		// console.debug(e.pageX+', '+e.pageY);
		mouseClicked = false;
		lastRessource_selected = ressource_selected;
		ressource_selected = null;
		marges.mouseMovePressInteration = 0;


		
		if(ressources.length > 0){
		
			ressources.forEach(function(obj){
			
				if(ressource_selected == null
					&& obj.visible()
					&& obj.isOver({x: e.pageX-40, y: e.pageY-40})){
					
					ressource_selected = obj;
					obj.alpha(0.7);
					$('#icons_list').hide();
					$('#link_list').show();
					
				}else{
					obj.alpha(0.3);
				}
								 
			});
			
			if(ressource_selected == null){
				
				ressources.forEach(function(objB){objB.alpha(0.5);});
				ressource_selected = null;
				linksToDraw = [];
				printRessourceInfos();
				$('#icons_list').show();
				$('#link_list').hide();
				
			}else{
				drawLinks();
				printRessourceInfos();
				
				if(selecting){
					var isAddRequest = false;
					cache_panel.modify(
						'<span>Nouveau Lien</span>',
						'<select id="sliderTypology" style="width: 350px;margin-left:15px;margin-top:20px;" >'
							+'<option value="1" >Accord/Désaccord</option>'
							+'<option value="2" >Inclusion/Exclusion</option>'
						+'</select>'
						+ '<div id="sliderValue" style="width: 350px;margin-left:15px;margin-top:20px;" ></div>',
						'<div><span class="cliquable">Annuler</span><span class="cliquable">Ajouter</span></div>');
					
					$( "#sliderValue" ).slider({
						step: 0,
						min: -100, 
						value: 0,
						max: 100
					}).css('background-color', 'rgb(180, 180, 180)');
					
					cache_panel.open();
					
					$(".cliquable").click(function(){
						if($(this).html() == "Annuler"){
							cache_panel.close();
						}
						if($(this).html() == "Ajouter" && !isAddRequest){
							isAddRequest = true;
							sendNewLink ($('#sliderTypology').val(), $('#sliderValue').slider( "value" ), function(){
								setTimeout(function(){
									fetchEntryData( entry_selected_id, function(){
										cache_panel.stopWaiting(true);
										cache_panel.close();
									});
								}, 1000);
							});
						}
						
					});
				}
			}
			
			screen.draw(gpu.getFrame(), true);
			
		}
		
	});
	
	
	
	
	//Changement de couleur du fond du slider de création des liens
	$( "#cache_panel" ).on( "change", "#sliderTypology", function() {
		
		
		var sliderTypology = $(this);
		var typ = sliderTypology.val();
		// var infoTypology = $('#infoTypology');
		var sliderValue = $( "#sliderValue" );
		
		switch(typ*1){
			case 1:
				// sliderTypology.css('background-color', 'rgb(21, 234, 21)');
				sliderValue.css('background-color', 'rgb(180, 180, 180)');
				sliderValue.slider("value", 0);
				break;
			case 2:
				// sliderTypology.css('background-color', 'rgb(227, 7, 7)');
				sliderValue.css('background-color', 'rgb(180, 180, 180)');
				sliderValue.slider("value", 0);
				break;
		}
		
	});
	
	//Changement de couleur du fond du slider de création des liens
	$( "#cache_panel" ).on( "slide", "#sliderValue", function( event, ui ) {
		
		var value = ui.value/100;
		var defaut = 180;
		var sliderValue = $( this );
		var typ = $('#sliderTypology').val();
		
		if(typ == 1){
			
			if(value > 0) sliderValue.css('background-color', 'rgb('+ Math.ceil(222+ ((defaut-222)*(1-value)) ) +', '+ Math.ceil(2+ ((defaut-2)*(1-value)) ) +', '+ Math.ceil(2+ ((defaut-2)*(1-value)) ) +')');
			else if(value < 0) sliderValue.css('background-color', 'rgb('+ Math.ceil(21+ ((defaut-21)*(1+value)) ) +', '+ Math.ceil(234+ ((defaut-234)*(1+value)) ) +', '+ Math.ceil(21+ ((defaut-21)*(1+value)) ) +')');
			else  sliderValue.css('background-color', 'rgb('+ defaut +','+ defaut +','+ defaut +')');
			
		}else if(typ == 2){
			
			if(value > 0) sliderValue.css('background-color', 'rgb('+ Math.ceil(28+ ((defaut-28)*(1-value)) ) +', '+ Math.ceil(28+ ((defaut-28)*(1-value)) ) +', '+ Math.ceil(28+ ((defaut-28)*(1-value)) ) +')');
			else if(value < 0) sliderValue.css('background-color', 'rgb('+ Math.ceil(85+ ((defaut-85)*(1+value)) ) +', '+ Math.ceil(171+ ((defaut-171)*(1+value)) ) +', '+ Math.ceil(192+ ((defaut-192)*(1+value)) ) +')');
			else  sliderValue.css('background-color', 'rgb('+ defaut +','+ defaut +','+ defaut +')');
			
		}
		
		return;
		
	});
	
	
	
	//gère les mouvements de la souris sur le canvas
	$('#canvas').on("mousemove vmousemove",function(e){
		holdCords.holdX = e.pageX;
		holdCords.holdY = e.pageY;
		if(ressources.length > 0){
			var isNoOver = true;
			ressources.forEach(function(obj){
			
				if(obj.visible() && obj.isOver({x: e.pageX-40, y: e.pageY-40})){
					
					$('#canvas').css('cursor', 'pointer');
					isNoOver = false;
				}
								 
			});
			if(isNoOver) $(this).css('cursor', 'crosshair');
		}
		if(mouseClicked && ressource_selected != null){
			if(marges.mouseMovePressInteration >= marges.mouseMovePressLimit){
				var cursor = {x: e.pageX-40, y: e.pageY-40};
				selecting = true;
				screen.draw(gpu.getFrame(), true);
				context.strokeStyle = "rgb(0,0,0)";
				context.globalAlpha = 1;
				context.beginPath();
				context.moveTo(ressource_selected.x(), ressource_selected.y());
				context.lineTo(cursor.x, cursor.y);
				context.stroke();
			}else{
				marges.mouseMovePressInteration++;
			}
		}else{
			selecting = false;
		}
	});
	
	
	
	//ajout de réputation d'une ressource
	$('#right_panel #addTrend').click(function (){
		if(ressource_selected instanceof Ressource){
			$.ajax({
				type: "POST",
				url: "utils/incrementRessource_trend.util.php",
				data: {ress_id: ressource_selected.id()},
				dataType: "json"
			}).done(function(data) {
				if(data['return']){
					ressource_selected.radius( ressource_selected.radius()+1 );
					screen.restart(gpu.getFrame());
				}
			}).fail(function(a,b,c){alert(a+", "+b+", "+c);});
		}
	});
	
	
	
	//retrait de réputation d'une ressource
	$('#right_panel #subTrend').click(function (){
		if(ressource_selected instanceof Ressource){
			$.ajax({
				type: "POST",
				url: "utils/decrementRessource_trend.util.php",
				data: {ress_id: ressource_selected.id()},
				dataType: "json"
			}).done(function(data) {
				if(data['return']){
					ressource_selected.radius( ressource_selected.radius()-1 );
					screen.restart(gpu.getFrame());
				}
			}).fail(function(a,b,c){alert(a+", "+b+", "+c);});
		}
	});
	
	
	
	//Gestion de l'alerte de bugs
	$('#bottom_panel #alertBug').click(function (){
		var isAddRequest = false;
			
		cache_panel.modify('<span>Rapporter un Bug</span>', '<input type="text" id="input_log_val" placeholder="Expliquez votre problème..." />',
		'<div><span class="cliquable">Annuler</span><span class="cliquable">Ajouter</span></div>');
		cache_panel.open();
		
		$(".cliquable").click(function(){
			if($(this).html() == "Annuler"){
				cache_panel.close();
			}
			if($(this).html() == "Ajouter" && !isAddRequest){
				isAddRequest = true;
				
				$.ajax({
					type: "POST",
					url: "utils/addBug.util.php",
					dataType: "json",
					data:{
						log_val: $('#input_log_val').val(),
						log_entry_id: (entry_selected_id != null && entry_selected_id > 0) ? entry_selected_id : null
					}
				}).done(function(data) {
					cache_panel.content("Merci de votre aide.<br/>Nous tenterons de résoudre cela dans le plus bref délai possibles.");
					setTimeout(function(){cache_panel.close();}, 3000);
				}).fail(function(a,b,c){
					alert(a+", "+b+", "+c);
				});
			}
			
		});
		
		$("body").on("keyup", "#input_log_val", function (){
			
			if(cursor < cursorRefs.length) return;
			
			var temp = $(this);
			var val = temp.val();
			var l = val.length;
			
			if(l > 20 && temp.prop("tagName") == "INPUT"){
				
				$("#input_log_val").replaceWith( '<textarea id="input_log_val">'+ val +'</textarea>' );
				$("#input_log_val").selectRange(21);
				
			}else if(l <= 20 && temp.prop("tagName") == "TEXTAREA"){
			
				$("#input_log_val").replaceWith( '<input type="text" id="input_log_val" placeholder="Contenu de la ressource" value="'+ val +'" />' );
				$("#input_log_val").selectRange(20);
				
			}
		});
	});
	
	
	
	//Ajout d'une ressource
	$("body").on("click", "#add_ressource", function(){
		var isAddRequest = false;
		
		if(entry_selected_id != null && entry_selected_id > 0){
		
			cache_panel.modify(
				'<span>Nouvelle ressource</span>',
				
				'<input type="text" style="display: none;" id="input_ress_titre" placeholder="Titre" maxlength=20 /><input type="text" id="input_ress_val" placeholder="Contenu de la ressource" autofocus />',
				
				'<div><span class="cliquable">Annuler</span><span class="cliquable">Ajouter</span></div>');
			cache_panel.open();
		
			$("#input_ress_val").focus();
			
			$(".cliquable").click(function(){
				if($(this).html() == "Annuler"){
					cache_panel.close();
				}
				if($(this).html() == "Ajouter" && !isAddRequest){
					isAddRequest = true;
					sendNewRessource ($('#input_ress_val').val(), $('#input_ress_titre').val(), function(){
						setTimeout(function(){
							fetchEntryData( entry_selected_id, function(){
								cache_panel.stopWaiting(true);
								cache_panel.close();
							});
						}, 1000);
					});
				}
				
			});
			
			$("body").on("keyup", "#input_ress_val", function (){
				
				if(cursor < cursorRefs.length) return;
				
				var temp = $(this);
				var val = temp.val();
				var l = val.length;
				
				if(l > 19 && temp.prop("tagName") == "INPUT"){
					
					$("#input_ress_val").replaceWith( '<textarea id="input_ress_val">'+ val +'</textarea>' );
					$("#input_ress_val").css('height', '165px');
					$("#input_ress_titre").show();
					$("#input_ress_val").selectRange(21);
					
				}else if(l <= 19 && temp.prop("tagName") == "TEXTAREA"){
				
					$("#input_ress_val").replaceWith( '<input type="text" id="input_ress_val" placeholder="Contenu de la ressource" value="'+ val +'" />' );
					$("#input_ress_val").css('height', '22px');
					$("#input_ress_titre").hide();
					$("#input_ress_val").selectRange(20);
					
				}
			});
		}
		
	});
	
	
	
	$("body").on("click", "#add_entry", function(){
	
		var isAddRequest = false;
		
		cache_panel.modify('<span>Nouvelle entrée</span>', '<input type="text" id="input_entry_val" placeholder="Nom de l\'entrée"/>', '<div><span class="cliquable">Annuler</span><span class="cliquable">Ajouter</span></div>');
		cache_panel.open();
		$("#entrys").hide();
		
		$("#input_entry_val").focus();
		
		$(".cliquable").click(function(){
			if($(this).html() == "Annuler"){
				cache_panel.close();
			}
			if($(this).html() == "Ajouter" && !isAddRequest){
				isAddRequest = true;
				sendNewEntry ($('#input_entry_val').val(), function(){
					getEntrys(true, function(){cache_panel.close();});
				});
			}
		});
	});
	
	
	
	$("body").on("click", "#share", function(){
		$.ajax({
			type: "POST",
			url: "utils/getURL.util.php",
			data: {entry_id: entry_selected_id},
			dataType: "json"
		}).done(function(data) {
			if(data['url']){
				alert(data['url']);
			}
		}).fail(function(a,b,c){console.debug(a+", "+b+", "+c);});
	});
	
	
	
	$("body").on("click", "#showLinks", function(){
		$('#top_right_corner #val').html("");
		linksToDraw.forEach(function(link){
			link.alpha(0);
			$('#top_right_corner #val').append('<div class="linkPrinted" idLink="'+ link.id() +'" from="'+ link.from() +'" to="'+ link.to() +'" >'+ ( (link.id() == link.from())? "o -> " : "o <- ") + ressources[ (link.to() != ressource_selected.id()) ? link.to() : link.from() ].shortName()+ '</div>');
		});
		
		screen.draw(gpu.getFrame(), true);
	});
	
	
	
	$("body").on("mouseenter", ".linkPrinted", function(){
		var id = $(this).attr('idLink');
		linksToDraw.forEach(function(link){
			if(id == link.id() || (linkSelected != null && link.id() == linkSelected.id()) ){ link.alpha(0.6);
			}else link.alpha(0);
		});
		
		screen.draw(gpu.getFrame(), true);
	});
	
	
	
	$("body").on("click", ".linkPrinted", function(){
		var id = $(this).attr('idLink');
		
		linksToDraw.forEach(function(link){
			if(id == link.id()){ link.alpha(0.6); linkSelected = link;
			}else link.alpha(0);
		});
		screen.draw(gpu.getFrame(), true);
	});
	
	
	
	$("body").on("mouseleave", "#top_right_corner", function(){
		linksToDraw.forEach(function(link){
			if(linkSelected != null && linkSelected.id() == link.id()) link.alpha(0.6);
			else link.alpha(0);
		});
		screen.draw(gpu.getFrame(), true);
	});
	
	
	
	$('#bottom_panel #infos').click(function (){
	
		cache_panel.modify('<span>Note de versions</span>', '', '<div><span class="cliquable">Fermer</span></div>');
		cache_panel.open();
		
		$.ajax({
			type: "POST",
			url: "version.html",
			dataType: "html"
		}).done(function(data) {
			cache_panel.content(data);
		}).fail(function(a,b,c){
			alert(a+", "+b+", "+c);
		});
		
		
		$(".cliquable").click(function(){
			if($(this).html() == "Fermer"){
				cache_panel.close();
			}
		});
		
	});
	
	
	
	$("body").on("click", "#entrys .content h3", function(){
		$('#entrys').hide();
		cache_panel.startWaiting(true);
		fetchEntryData( this.id.replace("id", ""), cache_panel.stopWaiting(true) );
		window.location.hash = this.id.replace("id", "");
		$("#add_ressource").hide();
	});
	
	
	
	
	
	$("body").on("click", "#entrys .close", function(){
		$('#entrys').hide();
	});
	
	
	
	
	$("body").on("click", "#index", function(){
		getEntrys(false, function(){$('#entrys').show();});
	});
	
	
	
	
	
	$(document).keydown(function (e){
		
		if(e.keyCode == 17) CTRL = true;
		if(e.keyCode == 39 && CTRL && cursor >= cursorRefs.length){
			if(zoom <= marges.zoomUp) zoomFactor += 0.05;
			calculZoom();
		}
		if(e.keyCode == 37 && CTRL && cursor >= cursorRefs.length){
			if(zoom >= marges.zoomDown) zoomFactor -= 0.05;
			calculZoom();
		}
		
	});
	
	
	
	$('#addZoom').click(function (){
		
		if(cursor >= cursorRefs.length){
			if(zoom <= marges.zoomUp) zoomFactor += 0.05;
			calculZoom();
		}
		
	});
	
	
	
	$('#subZoom').click(function (){
		
		if(cursor >= cursorRefs.length){
			if(zoom >= marges.zoomDown) zoomFactor -= 0.05;
			calculZoom();
		}
		
	});
	
	
	
	
	$(document).keyup(function (e){
		
		if(e.keyCode == 17) CTRL = false;
		
	});
	
	
	
	
	
	
/************************************************************
					printEntrysFromLetter
*************************************************************/
	function loadImages(sources, callback) {
		IMAGES = {};
		var loadedImages = 0;
		var numImages = 0;
		// get num of sources
		for(var src in sources) {
			numImages++;
		}
		for(var src in sources) {
			IMAGES[src] = new Image();
			IMAGES[src].onload = function() {
				if(++loadedImages >= numImages) {
					callback(IMAGES);
				}
			};
			IMAGES[src].src = sources[src];
		}
	}
	  
	function printEntrysFromLetter(letters, id){
		$("#top_left_corner #part_one").html("<h1>"+ id.replace("char-", "") +"</h1>");
		$("#top_left_corner #part_two").html("");
		
		if(letters[id])
			letters[id].forEach(function (entry){
				$("#top_left_corner #part_two").append("<h3 id='id"+ entry.id +"' >"+ entry.val +"</h3>");
			});
	}
	
	
	
	function printRessourceInfos (){
		
		if(ressource_selected instanceof Ressource){
			// $("#top_right_corner #type").html( returnType(ressource_selected.type()) );
			// $("#top_right_corner #category").html( "catégorie: "+ressource_selected.category_id() );
			$("#top_right_corner #val").html( '<div style="width:100%;text-align: left;">'+ressource_selected.val()+'</div>' );
			$("#top_right_corner").show();
			$("#right_panel #addTrend").show();
			$("#right_panel #subTrend").show();
			$("#right_panel #addAlert").show();
			$("#right_panel #showLinks").show();
		}else{
			$("#top_right_corner #val").html("");
			$("#top_right_corner").hide();
			$("#right_panel #addTrend").hide();
			$("#right_panel #subTrend").hide();
			$("#right_panel #addAlert").hide();
			$("#right_panel #showLinks").hide();
		}
		
	}
	
	function drawLinks (refresh){
		if(ressource_selected != null && ressources.length > 0){
			
			linksToDraw = [];
			var linksToFactorize = [];
			// console.debug("-------------");
			
			//recherche de liens arrivants + opacitée (s'il faut)
			ressources.forEach(function(ress){
			
				if(ress != ressource_selected){
				
					var linked = false;
					
					ress.getLinks().forEach(function (link){
					
						if(link.to() == ressource_selected.id()){
							// linksToDraw.push(link);
							// console.debug("ress "+ress.id());
							if(linksToFactorize[ress.id()] == null) linksToFactorize[ress.id()] = [];
							linksToFactorize[ress.id()].push(link);
							linked = true;
						}
						
					});
					
					if(linked) ress.alpha(0.7);
					else ress.alpha(0.1);
					
				}else{ress.alpha(1);}
			
			});
			
			ressource_selected.getLinks().forEach(function (link){
			
				if(ressources[link.to()] != undefined){ 
					ressources[link.to()].alpha(0.7);
					// console.debug("to "+link.to());
					if(linksToFactorize[link.to()] == null) linksToFactorize[link.to()] = [];
					linksToFactorize[link.to()].push(link);
				}
			
			});
			
			// if(refresh)screen.draw(gpu.getFrame(), true);
			
			// console.debug("lenght total: " + linksToFactorize.length);
			if(linksToFactorize.length > 0)
				linksToFactorize.forEach(function (array){
					
					var linksLength = linksToDraw.length;
					var linksFactor = 0.5;
					var isPositive = true;
					
					if(array.length > 0)
						array.forEach(function (link){
							// console.debug(link.from()+" -> "+link.to());
							link.factor( (isPositive) ? linksFactor : -linksFactor );
							linksFactor -= (isPositive) ? 0 : 0.5/linksLength;
							isPositive = (isPositive) ? false : true;
							linksToDraw.push(link);
						});
					
				});
			
				
			linksToDraw.forEach(function (link){
				link.alpha(0.6);
				link.draw(context, ressources);
			});
			
		}
	}
	
	function calculZoom (){
	
		ressources.forEach(function (obj){
			
			obj.radius( obj.radius()*zoomFactor );
			obj.calculWidth(context);
			var pos = obj.getPos();
			var vect = new Vector(pos.x, pos.y, winW_m, winH_m);
			vect.x( vect.x()*(1-zoomFactor) );
			vect.y( vect.y()*(1-zoomFactor) );
			
			obj.move(vect.toArray());
			
		});
		screen.draw(gpu.getFrame(), true);
		zoom += zoomFactor-1;
		zoomFactor = 1;
	
	}
	
	function returnType (type){
		switch(type){
			case 100: return "[vidéo]";break;
			case 101: return "[vidéo] viméo";break;
			case 102: return "[vidéo] youtube";break;
			
			case 201: return "[mot]";break;
			
			case 500: return "[lien]";break;
			
			default: return "undefined: "+type;
		}
	}
	
	
	
/************************************************************
						REFRESH INDEX
*************************************************************/
	
	function getEntrys (force, callback){
		
		
			if(ENTRYS.length == 0 || ENTRYS == null || force == true){
				
				$.ajax({
					type: "POST",
					url: "utils/getEntrys.util.php",
					dataType: "json"
				}).done(function(data) {
				
					var txt = "";
					ENTRYS = [];
					$("#entrys .content").html("");
					
					if(data != null && data.length > 0)
						data.forEach(function (entry){
							ENTRYS.push(entry);
							$("#entrys .content").append("<h3 id='id"+ entry.id +"' >"+ entry.val +"</h3>");
						});
						
					if(callback) callback();
					
				}).fail(function (a,b,c){
					console.debug(a+" | "+b+" | "+c);
				});
				
			}else{
				
				$("#entrys .content").html('');
				if(ENTRYS.length > 0)
					ENTRYS.forEach(function (entry){
						$("#entrys .content").append("<h3 id='id"+ entry.id +"' >"+ entry.val +"</h3>");
					});
				
				if(callback) callback();
			}
			
	}
	
	
	
	
	
	
	
	
	/************************************************************
							sendNewEntry
	*************************************************************/
	
	function sendNewEntry (entry_val, callback){
		$.ajax({
			type: "POST",
			url: "utils/addEntry.util.php",
			data : {entry_val: entry_val},
			dataType: "json"
		}).done(function(data) {
			
			if(data['return']){
				cache_panel.content("Entrée sauvegardée !");
				if(callback) callback();
			}else
				cache_panel.content( '<input type="text" placeholder="Nom de l\'entrée" />' + '<div>Impossible d\'ajouter</div>' );
		}).fail(function (a, b, c){
			cache_panel.content( '<input type="text" placeholder="Nom de l\'entrée" />' + '<div>Erreur de communication</div>' );
		});
	}
	
	function sendNewRessource (ress_val, ress_titre, callback){
		
		$.ajax({
			type: "POST",
			url: "utils/addRessource.util.php",
			data : {ress_val: ress_val, ress_titre: ress_titre, ress_entry_id: entry_selected_id},
			dataType: "json"
		}).done(function(data) {
			
			if(data['return']){
				cache_panel.content("Ressource sauvegardée !");
				if(callback) callback();
			}
			
		}).fail(function (a, b, c){
			// cache_panel.content( '<input type="text" placeholder="Nom de l\'entrée" />' + '<div>Erreur de communication</div>' );
			console.debug(a+", "+b+", "+c);
		});
		
	}
	
	function sendNewLink (link_type, link_val, callback){
		
		if(link_type < 1 || link_type > 4) return false;
		
		$.ajax({
			type: "POST",
			url: "utils/addLink.util.php",
			data : {
				link_type: link_type,
				link_val: link_val,
				link_from: lastRessource_selected.id(),
				link_to: ressource_selected.id(),
				link_entry_id: entry_selected_id
			},
			dataType: "json"
		}).done(function(data) {
			
			if(data['return']){
				cache_panel.content("Lien sauvegardé !");
				if(callback) callback();
				return true;
			}else return false;
			
		}).fail(function (a, b, c){
			alert(a+", "+b+", "+c);
			return false;
		});
		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	/************************************************************
							detectScreen
	*************************************************************/
	
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
	
	
	
	
	
	
	
	
	
	
	
	/************************************************************
							init
	*************************************************************/
	
	function init (canvas, callback){
		detectScreen();
		canvas.attr('width', winW);
		canvas.attr('height', winH);
		canvas.css('position', 'fixed');
		canvas.css('top', '40px');
		canvas.css('left', '40px');
		canvas.css('z-index', '5');
		$('#entrys .content').css('width', winW);
		$('#entrys .content').css('height', winH-40);
		
		screen.setFrame(anim);
		gpu.addCanvas(screen);
		var recall = true;
		var sources = {
			image: 'img/image.png',
			video: 'img/video.png',
			link:  'img/link-cloud.png',
			text:  'img/text.png',
			audio:  'img/audio.png'
		};
		
		loadImages(sources, function(){
			
			getEntrys(true, function(){
			
				var hash = window.location.hash.substring(1);

				
				if(hash != "" && hash > 0) {
				
					if(ENTRYS != null)
						ENTRYS.forEach(function(obj){
							if(obj.id == hash){
								recall = false;
								fetchEntryData(obj.id, cache_panel.stopWaiting(true));
							}
						});
				}else{
					getEntrys(false);
				}
				
			});
			
			if(recall && callback) callback();
			
		});
		
		
	}

	/************************************************************
							legend
	*************************************************************/
	
	$('#icons_list').hide();
	$('#link_list').hide();
		
	
	/************************************************************
							fetchEntryData
	*************************************************************/
	
	function fetchEntryData(entry_id, callback){
	
		entry_selected_id = entry_id;
		if(ENTRYS != null)
			ENTRYS.forEach(function(obj){
				if(obj.id == entry_id){
					$('#top_left_corner #part_one').html("<h1>"+ obj['val'] +"</h1>");
					$('title').text("Encyclopétrie - "+obj['val']);
				}
			});
		
		$.ajax({
			type: "POST",
			url: "utils/getEntry.util.php",
			dataType: "json",
			data: {entry_id: entry_id}
		}).done(function(data) {
			
			$("#top_right_corner #type").html( "" );
			$("#top_right_corner #category").html( "" );
			$("#top_right_corner #val").html( "" );
			$("#top_right_corner").hide();
			$("#right_panel #addTrend").hide();
			$("#right_panel #subTrend").hide();
			$("#right_panel #addAlert").hide();
			
			ressources = [];
			cursorRefs = [];
			linksToDraw = [];
			
			var r = 10;
			var l = data['ressources'];
			// zoom = 0;
			
			if(data.ressources != null){
				data['ressources'].forEach(function(obj){
					
					var ress = new Ressource(obj, context);
					ress.alpha(0.5);
					if(zoom != 0)
						ress.radius( ((ress.trend()*1)+12)*(zoom+1) );
					else
						ress.radius( ((ress.trend()*1)+12) );
					
					var rand = Math.random();
					
					ress.x( winW_m 
						+ ( Math.cos( rand*Math.PI )*r )
					);
					ress.y( winH_m 
						+ ( Math.sin( rand*Math.PI )*r )
					);
					
					ress.calculWidth(context);
					ressources[ress.id()] = ress;
					cursorRefs.push(ress.id());
					
				});
				if(data.links != null){
					data['links'].forEach(function(obj){
						
						var link = new Link(obj);
						if(ressources[obj.from]) ressources[obj.from].addLink(link);
						
					});
				}
				cursor = 0;
				showSlowly();
				if(!screen.isAutoRefresh()) screen.restart(gpu.getFrame());
			}else{
				screen.clear();
				screen.stop();
			}
			
			$("#add_ressource").show();
			
			if(callback) callback();
			
		}).fail(function(){
			alert("fail !");
		});
		
	}
	
});