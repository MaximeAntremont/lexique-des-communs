$(function(){
	var gpu = new GPU(true);
	var winW = 630, winH = 460;
	var winW_m, winH_m;
	var DIAGONAL = 600;
	var ENTRYS = [];
	var LETTERS = [];
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
		delay : 3,
		autoClear : true
	});
	var anim = new GPUFrame (false);
	
	init($('#canvas'), function (){cache_panel.stopWaiting(true);});
	
	
	$(window).resize(function (){
		detectScreen();
		$('#canvas').attr('width', winW);
		$('#canvas').attr('height', winH);
		$('#canvas').css('position', 'fixed');
		$('#canvas').css('top', '40px');
		$('#canvas').css('left', '40px');
		$('#canvas').css('z-index', '5');
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
			trend = tab.trend,
			type = tab.type,
			entry_id = tab.entry_id,
			alert = tab.alert;
		
		var x = 0,
			y = 0,
			w = 0,
			font = 0,
			r = 0,
			direction = {x:0,y:0},
			vitesse = 1,
			visible = false,
			alpha = 0.5;
		
		this.id = function (value){
			if(value != null) id = value;
			return id;
		};
		this.val = function (value){
			if(value != null) val = value;
			return val;
		};
		this.create_date = function (value){
			if(value != null) create_date = value;
			return create_date;
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
			if(value != null) x = value;
			return x;
		};
		this.y = function (value){
			if(value != null) y = value;
			return y;
		};
		this.w = function (value){
			if(value != null) w = value;
			return w;
		};
		this.radius = function (value){
			if(value != null) r = value;
			return r;
		};
		this.font = function (value){
			if(value != null) font = value;
			return font;
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
			ctx.arc(x, y, r, 0, 2 * Math.PI, false);
			ctx.fillStyle = 'black';
			ctx.fill();
		}
		
		this.distanceTo = function (obj){
			if(obj instanceof Ressource){
				
				return Math.sqrt( Math.pow(obj.x() - x, 2) + Math.pow(obj.y() - y, 2) ) - (obj.radius() + r);
				
			}
		}
		
		this.direction = function (val){
			if(val != null) direction = val;
			return direction;
		}
		
		this.move = function (){
			
			x += direction.x*vitesse;
			y += direction.y*vitesse;
			
		};
		
		this.getPos = function (){
			return {x: x, y: y};
		};
		
		this.isOver = function (mouse){
			return (normeVector( {x: mouse.x-x, y: mouse.y-y}) < r) ? true : false;
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
					
					ressources.forEach(function(objBis){
						if(obj != objBis && objBis.visible() && obj.distanceTo(objBis) <= 1){
							var tempV = resizeVector( getVector( objBis.getPos(), pos ) , 1);
							dir = addVector(dir, tempV);
							movement = 1;
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
		if(movement == -1 && cursor >= ressources.length) screen.isAutoRefresh(false);
	});
	
	var cursor = 0;
	function showSlowly (){
		
		ressources[cursor].visible(true);
		cursor++;
		if(cursor < ressources.length)
			setTimeout(showSlowly, 100);
		
	}
	
	$('#canvas').mousemove(function(e){
		if(ressources.length > 0){
			ressources.forEach(function(obj){
				var temp = obj.isOver({x: e.pageX-40, y: e.pageY-40});
				if(obj.visible() && temp && obj.alpha() == 0.5){
					
					obj.alpha(0.8);
					ressource_selected = obj;
					screen.draw(gpu.getFrame(), true);
					
				}else if(!temp){
					
					obj.alpha(0.5);
					
				}
				
				//TODO partie à optimiser ( pour le screen.draw(//,//); )
				 
			});
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