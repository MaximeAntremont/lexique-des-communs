
// function Cache (JQueryCache, JQueryHeader, JQueryContent, JQueryFooter){
	
	// var JQcache = JQueryCache;
	// var JQheader = JQueryHeader;
	// var JQcontent = JQueryContent;
	// var JQfooter = JQueryFooter;
	// var wait = false, waitCallback = null;
	// var header = '';
	// var content = '';
	// var footer = '';
	
	// this.startWaiting = function (open){
		// if(open == true) JQcache.show();
		
		// header = '';
		// content = '.';
		// footer = '~ chargement en cours ~';
		
		// updateCache();
		
		// wait = true;
		// waiting();
	// }
	
	// this.stopWaiting = function (close){
		// if(close == true) waitCallback = function (){JQcache.hide();};
		// else if(close) waitCallback = close;
		// wait = false;
	// }
	
	// function updateCache (){
		// JQheader.html(header);
		// JQcontent.html(content);
		// JQfooter.html(footer);
	// };
		
	// function waiting (){
		
		// updateCache();
		// if(content == '...') content = '';
		// else content += '.';
		
		// if(wait) setTimeout(waiting, 1000);
		// else if(waitCallback != null) {waitCallback(JQcache, JQheader, JQcontent, JQfooter);waitCallback = null};
		
	// }
	
// }

// function Entry (tab){
	
	// var id = tab.id,
		// val = tab.val,
		// create_date = tab.create_date,
		// ressources = tab.ressources,
		// links = tab.links;
	
// }

// function printEntrysFromLetter(letters, id){
	// $("#top_left_corner #part_one").html("<h1>"+ letters[id][0]['val'] +"</h1>");
	// $("#top_left_corner #part_two").html("");
	
	// if(letters[id])
		// letters[id].forEach(function (entry){
			// $("#top_left_corner #part_two").append("<h3 id='id"+ entry.id +"' >"+ entry.val +"</h3>");
		// });
		
	// $("#top_left_corner #part_two h3").click(function(){fetchEntryData( this.id.replace("id", "") );});
// }

// function refreshIndex(){
	// $.ajax({
		// type: "POST",
		// url: "utils/getIndex.util.php",
		// dataType: "json"
	// }).done(function(data) {
		
		// var letters = [];
		// var txt = '<div id="index">';
		
		// if(data != null)
			// data.forEach(function(obj){
				// if(obj.select != null)
					// txt += '<div class="letter-on" id="char-'+ obj['char'] +'" >'+ obj['char'] +'</div>';
				// else
					// txt += '<div class="letter-off" id="char-'+ obj['char'] +'" >'+ obj['char'] +'</div>';
				// letters['char-'+obj['char']] = obj['select'];
			// });
			
		// txt += '</div>';
		// txt += '<div id="add_entry">Nouvelle entrée</div>';
		// $("#top_panel").append(txt);
		
		// $(".letter-on").click(function(){printEntrysFromLetter(letters, this.id);});
		
	// }).fail(function (a,b,c){
		// console.debug(a+" | "+b+" | "+c);
	// });
// }

// var winW = 630, winH = 460;
// var winW_m, winH_m;
// var DIAGONAL = 600;
// function detectScreen (){
	// if (document.body && document.body.offsetWidth) {
		// winW = document.body.offsetWidth;
		// winH = document.body.offsetHeight;
	// }
	// if (document.compatMode=='CSS1Compat' && document.documentElement && document.documentElement.offsetWidth ) {
		// winW = document.documentElement.offsetWidth;
		// winH = document.documentElement.offsetHeight;
	// }
	// if (window.innerWidth && window.innerHeight) {
		// winW = window.innerWidth;
		// winH = window.innerHeight;
	// }
	
	// winW -= 80;
	// winH -= 80;
	// winW_m = winW/2;
	// winH_m = winH/2;
	// DIAGONAL = Math.ceil(Math.sqrt( Math.pow(winW,2)+Math.pow(winH,2)));
// }

// function init (canvas, callback){
	// detectScreen();
	// canvas.attr('width', winW);
	// canvas.attr('height', winH);
	// canvas.css('position', 'fixed');
	// canvas.css('top', '40px');
	// canvas.css('left', '40px');
	// canvas.css('z-index', '5');
	
	// refreshIndex();
	
	// if(callback) callback();
	
// }

// function fetchEntryData(entry_id){
	// alert();
	// $.ajax({
		// type: "POST",
		// url: "utils/getEntry.util.php",
		// dataType: "json",
		// data: {entry_id: entry_id}
	// }).done(function(data) {
	
		// context.clearRect(0,0,winW,winH);
		// context.textAlign = "center"
		// context.font = '20px TEX';
		
		// var ress = [];
		
		// if(data.ressources != null){
			
			// var circle = {
				// r : (DIAGONAL/3)/2,
				// step : 2 / data.ressources.length,
				// cursor : 0,
				// center: {x: winW_m, y:winH_m}
			// }
			
			// data['ressources'].forEach(function(obj){
				
				// var x = circle.center.x + (Math.cos(circle.cursor*Math.PI) * circle.r);
				// var y = circle.center.y + (Math.sin(circle.cursor*Math.PI) * circle.r);
				
				// circle.cursor += circle.step;
				
				// context.fillText(obj.val, x, y);
				// ress[obj.id] = {x: x, y:y};
				
			// });
			
		// }
		
		// if(data.links != null)
			// data['links'].forEach(function(obj){
				
				// var from = ress[obj.from];
				// var to = ress[obj.to];
				
				// context.beginPath();
				// context.moveTo(from.x, from.y);
				// context.lineTo(to.x, to.y);
				// context.stroke();
				
			// });
		
	// }).fail(function(){
		// alert("fail !");
	// });
	
// }