$(function(){
	var gpu = new GPU(true);
	var winW = 630, winH = 460;
	var winW_m, winH_m;
	var DIAGONAL = 600;
	var ENTRYS = [];
	var LETTERS = [];
	var entry_selected_id;
	var entry_selected;
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
						CACHE
*************************************************************/

	function Cache (JQueryCache, JQueryHeader, JQueryContent, JQueryFooter){
		
		var JQcache = JQueryCache;
		var JQheader = JQueryHeader;
		var JQcontent = JQueryContent;
		var JQfooter = JQueryFooter;
		var wait = false, waitCallback = null;
		var header = '';
		var content = '';
		var footer = '';
		
		this.startWaiting = function (open){
			if(open == true) JQcache.show();
			
			header = '';
			content = '.';
			footer = '~ chargement en cours ~';
			
			updateCache();
			
			wait = true;
			waiting();
		}
		
		this.stopWaiting = function (close){
			if(close == true) waitCallback = function (){JQcache.hide();};
			else if(close) waitCallback = close;
			wait = false;
		}
		
		this.open = function (){
			JQcache.show();
		}
		
		this.close = function (){
			JQcache.hide();
		}
		
		this.header = function (val){
			if(val != null){
				header = val;
				JQheader.html(header);
			}
			return header;
		}
		
		this.content = function (val){
			if(val != null){
				content = val;
				JQcontent.html(content);
			}
			return content;
		}
		
		this.footer = function (val){
			if(val != null){
				footer = val;
				JQfooter.html(footer);
			}
			return footer;
		}
		
		this.modify = function (top, mid, bot){
			if(top != null && mid != null && bot != null){
				header = top;
				content = mid;
				footer = bot;
				updateCache();
				return true;
			}
			return false;
		}
		
		function updateCache (){
			JQheader.html(header);
			JQcontent.html(content);
			JQfooter.html(footer);
		};
			
		function waiting (){
			
			updateCache();
			if(content == '...') content = '';
			else content += '.';
			
			if(wait) setTimeout(waiting, 1000);
			else if(waitCallback != null) {waitCallback(JQcache, JQheader, JQcontent, JQfooter);waitCallback = null};
			
		}
		
	}
	
	
	
	
	
/************************************************************
						  Ressource
*************************************************************/
	function Ressource (tab){
		
		var id = tab.id,
			val = tab.val,
			create_date = tab.create_date,
			trend = ((tab.trend > 20) ? 20 : tab.trend),
			type = tab.type,
			entry_id = tab.entry_id,
			category_id = tab.category_id,
			alert = tab.alert;
		
		var center = {x:0,y:0},
			width = 0,
			r = 0,
			direction = {x:0,y:0},
			vitesse = 1,
			visible = false,
			alpha = 0.5;
		this.top_left_center = {x: 0, y: 0};
		
		this.id = function (value){
			if(value != null) id = value;
			return id;
		};
		this.val = function (value){
			if(value != null) val = value;
			return val;
		};
		this.calculWidth = function (ctx){
			ctx.font = r+'px TEX';
			ctx.textAlign = 'center';
			ctx.fillStyle = 'black';
			var metrics = ctx.measureText(val);
			width = metrics.width;
		};
		this.width = function (val){
			if(val != null) width = value;
			return width;
		};
		this.height = function (){
			return r;
		};
		this.create_date = function (value){
			if(value != null) create_date = value;
			return create_date;
		};
		this.category_id = function (value){
			if(value != null) category_id = value;
			return category_id;
		};
		this.trend = function (value){
			if(value != null) trend = value;
			return trend;
		};
		this.type = function (value){
			if(value != null) type = value;
			return type;
		};
		this.entry_id = function (value){
			if(value != null) entry_id = value;
			return entry_id;
		};
		this.alert = function (value){
			if(value != null) alert = value;
			return alert;
		};
		
		this.x = function (value){
			if(value != null){
				center.x = value;
				this.top_left_center.x = value-(width/2);
			}
			return center.x;
		};
		this.y = function (value){
			if(value != null){
				center.y = value;
				this.top_left_center.y = value-(r/2);
			}
			return center.y;
		};
		this.w = function (value){
			if(value != null) w = value;
			return w;
		};
		this.radius = function (value){
			if(value != null) r = value;
			return r;
		};
		this.visible = function (value){
			if(value != null) visible = value;
			return visible;
		};
		this.alpha = function (value){
			if(value != null) alpha = value;
			return alpha;
		};
		
		this.draw = function (ctx){
			context.globalAlpha = alpha;
			ctx.beginPath();
			ctx.font = r+'px TEX';
			ctx.textAlign = 'center';
			ctx.fillStyle = 'black';
			ctx.textBaseline = 'middle';
			ctx.fillText(val, center.x, center.y);
			ctx.fill();
		}
		
		this.distanceTo = function (obj){
			if(obj instanceof Ressource){
				
				return Math.sqrt( Math.pow(obj.x() - center.x, 2) + Math.pow(obj.y() - center.y, 2) ) - (obj.radius() + r);
				
			}
		}
		
		this.spaceBeetween = function (obj){
			if(obj instanceof Ressource){
				
				var v = getVector({x:center.x,y:center.y}, obj.getPos());
				var space = {x:0,y:0};
				
				space.x = Math.abs( v.x ) - ( (obj.width() + width)/2 );
				space.y = Math.abs( v.y ) - ( (obj.height() + r)/2 );
				
				return space;
				
			}
		}
		
		this.direction = function (val){
			if(val != null) direction = val;
			return direction;
		}
		
		this.move = function (){
			
			center.x += direction.x*vitesse;
			center.y += direction.y*vitesse;
			
			this.top_left_center.x = center.x - (width/2);
			this.top_left_center.y = center.y - (r/2);
			
		};
		
		this.getPos = function (){
			return center;
		};
		
		this.isOver = function (mouse){
			return (mouse.x >= this.top_left_center.x 
			&& mouse.x <= this.top_left_center.x + width 
			&& mouse.y >= this.top_left_center.y 
			&& mouse.y <= this.top_left_center.y + r) ? true : false;
		};
		
	}
	
	
	
	
	
	
/************************************************************
							VECTORS
*************************************************************/
	
	function normeVector (v){
		return Math.sqrt( Math.pow(v.x,2) + Math.pow(v.y,2) );
	}
	
	function addVector(a, b){
		return {x: b.x+a.x, y: b.y+a.y};
	}
	
	function resizeVector(v, factor){
		var norme = normeVector(v);
		if(v.x == 0 && v.y == 0)
			return {
				x: 0,
				y: 0
			};
		else
			return {
				x: (v.x*factor)/norme,
				y: (v.y*factor)/norme
			};
	}
	
	function getVector (a, b){
		return {x: b.x-a.x, y: b.y-a.y};
	}
	
	
	
	
	
	
/************************************************************
							FRAME
*************************************************************/
	anim.write(function (canvas, ctx, frame, vars){
		
		var movement = 0;
		
		if(ressources.length > 0){
			ressources.forEach(function(obj){
				
				if(obj.visible()){
				
					var pos = obj.getPos();			
					var dir = {x:0,y:0};
					
					ressources.forEach(function(objB){
						if(obj != objB && objB.visible()){
							
							// var pathTo = getVector(pos, objB.getPos());
							var RpathTo = getVector(objB.getPos(), pos);
							// var pathToB = {x: Math.abs( pathTo.x ), y: Math.abs( pathTo.y )};
							var translate = {x:0,y:0};
							
							var spaces = obj.spaceBeetween( objB );
							
							if(	spaces.x >= 5 || spaces.y >= 5){
								// translate = resizeVector( getVector(obj.getPos(), {x: winW_m, y: winH_m}) ,0.01);
							}else{
								RpathTo = resizeVector(RpathTo, 2);
								if(spaces.x < 5) translate.x = RpathTo.x;
								if(spaces.y < 5) translate.y = RpathTo.y*2;
								movement = 1;
							}
							dir = addVector(dir, translate);
						
						}
						
					});
					
					dir = resizeVector(dir, 2);
					obj.direction(dir);
					
					obj.move();
					obj.draw(ctx);
					
				}
			});
			if(movement == 0) movement = -1;
		}
		
		console.debug(frame);
		if(movement == -1 && cursor >= ressources.length) screen.stop();
	});
	
	var cursor = 0;
	function showSlowly (){
		
		ressources[cursor].visible(true);
		cursor++;
		if(cursor < ressources.length)
			setTimeout(showSlowly, 200);
		
	}
	
	$('#canvas').mousemove(function(e){
		if(ressources.length > 0){
			var isNoOver = true;
			ressources.forEach(function(obj){
			
				if(obj.visible() && obj.isOver({x: e.pageX-40, y: e.pageY-40})){
					
					// console.debug(obj.top_left_center.x);
					$('#canvas').css('cursor', 'pointer');
					isNoOver = false;
				}
								 
			});
			if(isNoOver) $(this).css('cursor', 'default');
		}
	});
	
	$('#canvas').click(function(e){
		if(ressources.length > 0){
			var isNoOver = true;
			ressources.forEach(function(obj){
			
				if(obj.visible() && obj.isOver({x: e.pageX-40, y: e.pageY-40})){
					
					ressource_selected = obj;
					$("#right_panel #addTrend").css('color', 'rgb(160,160,160)');
					$("#right_panel #subTrend").css('color', 'rgb(160,160,160)');
					$("#right_panel #addAlert").css('color', 'rgb(160,160,160)');
					obj.alpha(0.8);
					isNoOver = false;
					printRessourceInfos();
					
				}else{
					obj.alpha(0.5);
				}
								 
			});
			if(isNoOver){
				$("#right_panel #addTrend").css('color', 'rgb(200,200,200)');
				$("#right_panel #subTrend").css('color', 'rgb(200,200,200)');
				$("#right_panel #addAlert").css('color', 'rgb(200,200,200)');
				
				ressources.forEach(function(objB){objB.alpha(0.5);});
				ressource_selected = null;
				printRessourceInfos();
				
			}
			screen.draw(gpu.getFrame(), true);
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
					setTimeout(function(){cache_panel.close();},2000);
					
				}
			}).fail(function(a,b,c){alert(a+", "+b+", "+c);});
		}
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
			$("#top_right_corner #type").html(ressource_selected.type());
			$("#top_right_corner #category").html(ressource_selected.category_id());
			$("#top_right_corner #val").html(ressource_selected.val());
			$("#top_right_corner").show();
		}else{
			$("#top_right_corner #val").html("");
			$("#top_right_corner").hide();
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
							setTimeout(function(){cache_panel.close();}, 2000);
							refreshIndex();
							$("#top_left_corner #part_one, #top_left_corner #part_two").html("");
						});
					}
				});
				
			});
			
			$("#add_ressource").click(function(){
				if(entry_selected_id != null && entry_selected_id > 0){
				
					cache_panel.modify('<span>Nouvelle ressource</span>', '<input type="text" id="input_ress_val" placeholder="Contenu de la ressource" />',
					'<div><span class="cliquable">Annuler</span><span class="cliquable">Ajouter</span></div>');
					cache_panel.open();
					
					$(".cliquable").click(function(){
						if($(this).html() == "Annuler"){
							cache_panel.close();
						}
						if($(this).html() == "Ajouter"){
							sendNewRessource ($('#input_ress_val').val(), function(){
								setTimeout(function(){fetchEntryData( entry_selected_id, function(){cache_panel.stopWaiting(true);} );}, 2000);
								
							});
							// cache_panel.close();
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
						
			ressources = [];
			var r = 10;
			var l = data['ressources'];
			
			if(data.ressources != null){
				data['ressources'].forEach(function(obj){
					
					var ress = new Ressource(obj);
					
					ress.radius( (ress.trend()*1)+10 );
					
					var rand = Math.random();
					
					ress.x( winW_m 
						+ ( Math.cos( rand*Math.PI )*r )
					);
					ress.y( winH_m 
						+ ( Math.sin( rand*Math.PI )*r )
					);
					
					ress.calculWidth(context);
					ressources.push( ress );
					
				});
				cursor = 0;
				showSlowly();
				if(!screen.isAutoRefresh()) screen.restart(gpu.getFrame());
			}else{
				screen.stop();
			}
			
			
			
			if(callback) callback();
			
		}).fail(function(){
			alert("fail !");
		});
		
	}
	
	// screen.setFrame(anim);
	// GPU.addCanvas(screen);
	
});