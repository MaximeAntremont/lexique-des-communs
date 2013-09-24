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
	var selecting = false;
	
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
		autoRefresh : true,
		delay : 2,
		autoClear : true
	});
	var anim = new GPUFrame (false);
	
	init($('#canvas'), function (){cache_panel.stopWaiting(true);});
	
	
	$(window).resize(function (){
		var oldMiddle = {x: winW_m,y: winH_m};
		detectScreen();
		var offset = {x: winW_m-oldMiddle.x, y: winH_m-oldMiddle.y};
		
		$('#canvas').attr('width', winW);
		$('#canvas').attr('height', winH);
		$('#canvas').css('position', 'fixed');
		$('#canvas').css('top', '40px');
		$('#canvas').css('left', '40px');
		$('#canvas').css('z-index', '5');
		
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
					obj.draw(ctx);
					
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

	$('#canvas').mouseup(function(e){
		// console.debug('mouseClicked: '+mouseClicked);
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
					
				}else{
					obj.alpha(0.3);
				}
								 
			});
			
			if(ressource_selected == null){
				$("#right_panel #addTrend").css('color', 'rgb(200,200,200)');
				$("#right_panel #subTrend").css('color', 'rgb(200,200,200)');
				$("#right_panel #addAlert").css('color', 'rgb(200,200,200)');
				
				ressources.forEach(function(objB){objB.alpha(0.5);});
				ressource_selected = null;
				linksToDraw = [];
				printRessourceInfos();
				
			}else{
				// ressource_selected.backgroundColor('rgb(140,100,200)');
				drawLinks();
				
				$("#right_panel #addTrend").css('color', 'rgb(160,160,160)');
				$("#right_panel #subTrend").css('color', 'rgb(160,160,160)');
				$("#right_panel #addAlert").css('color', 'rgb(160,160,160)');
				printRessourceInfos();
				
				if(selecting){
					cache_panel.modify('<span>Nouveau Lien</span>', '<select id="link_type" name="link_type" ><option value="0" selected>conflitctuel</option><option value="100">implicite</option><option value="200">explicite</option><option value="300">direct</option></select>',
										'<div><span class="cliquable">Annuler</span><span class="cliquable">Ajouter</span></div>');
					cache_panel.open();
					
					var isAddRequest = false;
					$(".cliquable").click(function(){
						if($(this).html() == "Annuler"){
							cache_panel.close();
						}
						if($(this).html() == "Ajouter" && !isAddRequest){
							isAddRequest = true;
							sendNewLink ($('#link_type').val(), function(){
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
	
	$('#canvas').mousemove(function(e){
		if(ressources.length > 0){
			var isNoOver = true;
			ressources.forEach(function(obj){
			
				if(obj.visible() && obj.isOver({x: e.pageX-40, y: e.pageY-40})){
					
					$('#canvas').css('cursor', 'pointer');
					isNoOver = false;
				}
								 
			});
			if(isNoOver) $(this).css('cursor', 'default');
		}
		if(mouseClicked && ressource_selected != null){
			if(marges.mouseMovePressInteration >= marges.mouseMovePressLimit){
				var cursor = {x: e.pageX-40, y: e.pageY-40};
				selecting = true;
				screen.draw(gpu.getFrame(), true);
				context.beginPath();
				context.moveTo(ressource_selected.top_left_center.x, ressource_selected.top_left_center.y);
				context.lineTo(cursor.x, cursor.y);
				context.stroke();
			}else{
				marges.mouseMovePressInteration++;
			}
		}else{
			selecting = false;
		}
	});
	
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
	
	$('#right_panel #addAlert').click(function (){
		if(ressource_selected instanceof Ressource){
			$.ajax({
				type: "POST",
				url: "utils/incrementRessource_alert.util.php",
				data: {ress_id: ressource_selected.id()},
				dataType: "json"
			}).done(function(data) {
				if(data['return']){
					
					cache_panel.modify('alerte de contenu','Merci de nous avoir alerté.','');
					cache_panel.open();
					setTimeout(function(){cache_panel.close();},1000);
					
				}
			}).fail(function(a,b,c){alert(a+", "+b+", "+c);});
		}
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
	function printEntrysFromLetter(letters, id){
		$("#top_left_corner #part_one").html("<h1>"+ id.replace("char-", "") +"</h1>");
		$("#top_left_corner #part_two").html("");
		
		if(letters[id])
			letters[id].forEach(function (entry){
				$("#top_left_corner #part_two").append("<h3 id='id"+ entry.id +"' >"+ entry.val +"</h3>");
			});
			
		$("#top_left_corner #part_two h3").click(function(){
			cache_panel.startWaiting(true);
			fetchEntryData( this.id.replace("id", ""), cache_panel.stopWaiting(true) );
			window.location.hash = this.id.replace("id", "");
		});
	}
	
	
	
	function printRessourceInfos (){
		
		if(ressource_selected instanceof Ressource){
			$("#top_right_corner #type").html( returnType(ressource_selected.type()) );
			$("#top_right_corner #category").html( "catégorie: "+ressource_selected.category_id() );
			$("#top_right_corner #val").html( ressource_selected.val() );
			$("#top_right_corner").show();
			$("#right_panel #addTrend").show();
			$("#right_panel #subTrend").show();
			$("#right_panel #addAlert").show();
		}else{
			$("#top_right_corner #val").html("");
			$("#top_right_corner").hide();
			$("#right_panel #addTrend").hide();
			$("#right_panel #subTrend").hide();
			$("#right_panel #addAlert").hide();
		}
		
	}
	
	function drawLinks (refresh){
		if(ressource_selected != null && ressources.length > 0){
			
			linksToDraw = [];
			
			//recherche de liens arrivants + opacitée (s'il faut)
			ressources.forEach(function(ress){
			
				if(ress != ressource_selected){
				
					var linked = false;
					
					ress.getLinks().forEach(function (link){
					
						if(link.to() == ressource_selected.id()){
							linksToDraw.push(link);
							linked = true;
						}
						
					});
					
					if(linked) ress.alpha(0.7);
					else ress.alpha(0.3);
					
				}else{ress.alpha(1);}
			
			});
			
			ressource_selected.getLinks().forEach(function (link){
			
				ressources[link.to()].alpha(0.7);
				linksToDraw.push(link);
			
			});
			
			// if(refresh)screen.draw(gpu.getFrame(), true);
			
			linksToDraw.forEach(function (link){
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
	
	function refreshIndex(callback){
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
					
					LETTERS['char-'+obj['char']] = obj['select'];
					if(obj['select'] && obj['select'].length > 0)
						obj['select'].forEach(function(o){ENTRYS.push(o);});
				});
				
			txt += '</div>';
			txt += '<div id="add_entry">Nouvelle entrée</div>';
			txt += '<div id="add_ressource">Nouvelle ressource</div>';
			$("#top_panel").html("");
			$("#top_panel").append(txt);
			
			$(".letter-on").click(function(){
				printEntrysFromLetter(letters, this.id);
			});
			
			$("#add_entry").click(function(){
				cache_panel.modify('<span>Nouvelle entrée</span>', '<input type="text" id="input_entry_val" placeholder="Nom de l\'entrée" />', '<div><span class="cliquable">Annuler</span><span class="cliquable">Ajouter</span></div>');
				cache_panel.open();
				$(".cliquable").click(function(){
					if($(this).html() == "Annuler"){
						cache_panel.close();
					}
					if($(this).html() == "Ajouter"){
						sendNewEntry ($('#input_entry_val').val(), function(){
							setTimeout(function(){cache_panel.close();}, 1000);
							refreshIndex();
							$("#top_left_corner #part_one, #top_left_corner #part_two").html("");
						});
					}
				});
			});
			
			$("#add_ressource").click(function(){
				var isAddRequest = false;
				
				if(entry_selected_id != null && entry_selected_id > 0){
				
					cache_panel.modify('<span>Nouvelle ressource</span>', '<input type="text" id="input_ress_val" placeholder="Contenu de la ressource" />',
					'<div><span class="cliquable">Annuler</span><span class="cliquable">Ajouter</span></div>');
					cache_panel.open();
					
					$(".cliquable").click(function(){
						if($(this).html() == "Annuler"){
							cache_panel.close();
						}
						if($(this).html() == "Ajouter" && !isAddRequest){
							isAddRequest = true;
							sendNewRessource ($('#input_ress_val').val(), function(){
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
						
						if(l > 20 && temp.prop("tagName") == "INPUT"){
							
							$("#input_ress_val").replaceWith( '<textarea id="input_ress_val">'+ val +'</textarea>' );
							$("#input_ress_val").selectRange(21);
							
						}else if(l <= 20 && temp.prop("tagName") == "TEXTAREA"){
						
							$("#input_ress_val").replaceWith( '<input type="text" id="input_ress_val" placeholder="Contenu de la ressource" value="'+ val +'" />' );
							$("#input_ress_val").selectRange(20);
							
						}
					});
				}
				
			});
			
			
			
			if(callback) callback();
			
		}).fail(function (a,b,c){
			console.debug(a+" | "+b+" | "+c);
		});
		
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
	
	function sendNewRessource (ress_val, callback){
		
		$.ajax({
			type: "POST",
			url: "utils/addRessource.util.php",
			data : {ress_val: ress_val, ress_entry_id: entry_selected_id},
			dataType: "json"
		}).done(function(data) {
			
			if(data['return']){
				cache_panel.content("Ressource sauvegardée !");
				if(callback) callback();
			}
			
		}).fail(function (a, b, c){
			// cache_panel.content( '<input type="text" placeholder="Nom de l\'entrée" />' + '<div>Erreur de communication</div>' );
			alert(a+", "+b+", "+c);
		});
		
	}
	
	function sendNewLink (link_type, callback){
		
		$.ajax({
			type: "POST",
			url: "utils/addLink.util.php",
			data : {
				link_type: link_type,
				link_from: lastRessource_selected.id(),
				link_to: ressource_selected.id(),
				link_entry_id: entry_selected_id
			},
			dataType: "json"
		}).done(function(data) {
			
			if(data['return']){
				cache_panel.content("Lien sauvegardé !");
				if(callback) callback();
			}
			
		}).fail(function (a, b, c){
			alert(a+", "+b+", "+c);
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
		
		screen.setFrame(anim);
		gpu.addCanvas(screen);
		var recall = true;
		refreshIndex(function(){
		
			var hash = window.location.hash.substring(1);

			if(hash != "" && hash > 0) {
				if(ENTRYS != null)
					ENTRYS.forEach(function(obj){
						if(obj.id == hash){
							recall = false;
							printEntrysFromLetter(LETTERS, "char-"+obj['val'].charAt(0).toUpperCase());
							fetchEntryData(obj.id, cache_panel.stopWaiting(true));
						}
					});
				
			}
			
			
		});
		
		$('#pause').click(function(){
			screen.isAutoRefresh(false);
		});
		$('#start').click(function(){
			screen.restart(gpu.getFrame());
		});
		
		if(recall && callback) callback();
		
	}

	
	
	
	
	
	
	
	
	
	
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
			
			
			
			if(callback) callback();
			
		}).fail(function(){
			alert("fail !");
		});
		
	}
	
});