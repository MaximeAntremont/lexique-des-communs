/*************************************************************************
**************************************************************************
********************************* VECTOR */

function Vector (xa,ya,xb,yb){
	
	var x = 0,
		y = 0;
	
	if(xb != null && yb != null){ x=xb-xa; y=yb-ya; }else{ x=xa; y=ya;}
	
	this.x = function(val){if(val != null)x=val;return x;};
	this.y = function(val){if(val != null)y=val;return y;};
	this.getNorme = function (){return Math.sqrt( Math.pow(x,2) + Math.pow(y,2) );};
	this.add = function (vector){if(vector instanceof Vector){x=vector.x()+x; y=vector.y()+y;}};
	this.toArray = function(){return {x:x,y:y}};
	this.resize = function(factor){
		var norme = Math.sqrt(Math.pow(x,2)+Math.pow(y,2));
		if(x != 0 && y != 0){
			x = (x*factor)/norme;
			y = (y*factor)/norme;
		}
	};
	
}


/************************************************************
CACHE - CACHE - CACHE - CACHE - CACHE - CACHE - CACHE - CACHE
*************************************************************/

function Cache (JQueryCache, JQueryHeader, JQueryContent, JQueryFooter){	
	var JQcache = JQueryCache,
		JQheader = JQueryHeader,
		JQcontent = JQueryContent,
		JQfooter = JQueryFooter,
		wait = false, waitCallback = null,
		header = '',
		content = '',
		footer = '';
	
	this.startWaiting = function (open){
	
		if(open == true) JQcache.show();
		
		header = '';
		content = '.';
		footer = '~ chargement en cours ~';
		updateCache();
		
		$('body').css('cursor', 'wait');
		
		wait = true;
		waiting();
		
	};
	
	this.stopWaiting = function (close){
	
		if(close == true) waitCallback = function (){JQcache.hide();};
		else if(close) waitCallback = close;
		
		$('body').css('cursor', 'default');
		
		wait = false;
		
	};
	
	this.open = function (){JQcache.show();};
	
	this.close = function (){JQcache.hide();};
	
	this.header = function (val){
		if(val != null){
			header = val;
			JQheader.html(header);
		}
		return header;
	};
	
	this.content = function (val){
		if(val != null){
			content = val;
			JQcontent.html(content);
		}
		return content;
	};
	
	this.footer = function (val){
		if(val != null){
			footer = val;
			JQfooter.html(footer);
		}
		return footer;
	};
	
	this.modify = function (top, mid, bot){
		if(top != null && mid != null && bot != null){
		
			header = top;
			content = mid;
			footer = bot;
			
			updateCache();
			return true;
		}
		return false;
	};
	
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
		else if(waitCallback != null) {
			waitCallback(JQcache, JQheader, JQcontent, JQfooter);
			waitCallback = null
		}
		
	};
	
}






/************************************************************
					LINK
*************************************************************/

function Link (tab){
		
	var id = tab.id,
		val = tab.val,
		create_date = tab.create_date,
		from = tab.from,
		to = tab.to,
		type = tab.type,
		alpha = tab.alpha || 0.6,
		factor = tab.factor || 0.5;
	var color = typeToColor(type);
	
	var toDraw = false;
	
	this.id = function (val){
		if(val != null) id = val;
		return id;
	};
	this.val = function (valu){
		if(valu != null) val = valu;
		return val;
	};
	this.create_date = function (val){
		if(val != null) create_date = val;
		return create_date;
	};
	this.from = function (val){
		if(val != null) from = val;
		return from;
	};
	this.to = function (val){
		if(val != null) to = val;
		return to;
	};
	this.type = function (val){
		if(val != null) type = val;
		color = typeToColor(type);
		return type;
	};
	this.alpha = function (val){
		if(val != null) alpha = val;
		return alpha;
	};
	this.factor = function (val){
		if(val != null) factor = val;
		return factor;
	};
	this.isToDraw = function (val){
		if(val != true || val == false) toDraw = val;
		return toDraw;
	};
	
	function typeToColor (val){
		if(val > 0 && val <= 255){
			// alert("rgb(255,"+ (255-val) +","+ (255-val) +")");
			return "rgb(255,"+ (255-val) +","+ (255-val) +")";
		}else if(val < 0 && val >= -255){
			// alert( "rgb("+ (255 - (val*-1)) +",255,"+ (255 - (val*-1)) +")" );
			return "rgb("+ (255 - (val*-1)) +",255,"+ (255 - (val*-1)) +")";
		}else{
			// alert( "rgb(250,250,250)" );
			return "rgb(250,250,250)";
		}
	};
	
	this.draw = function (ctx, ressources){
		
		if(ressources != null && alpha > 0){
		
			var ressFrom = ressources[from];
			var ressTo = ressources[to];
			
			ctx.lineCap = 'round';
			ctx.lineWidth = 2;
			ctx.globalAlpha = alpha;
			
			ctx.strokeStyle = color;
			
			var v = new Vector( ressFrom.x(), ressFrom.y(), ressTo.x(), ressTo.y() );
			var av = new Vector( -v.y()*factor, v.x()*factor );
			// av.resize( -av.getNorme()/factor );
			ctx.beginPath();
			ctx.moveTo(ressFrom.x(), ressFrom.y());
			// ctx.moveTo(ressFrom.top_left_center.x+(ressFrom.width()/2), ressFrom.top_left_center.y+(ressFrom.height()/2));
			// ctx.bezierCurveTo(
				// ressFrom.x() + av.x(),
				// ressFrom.y() + av.y(),
				// ressTo.x() + av.x(),
				// ressTo.y() + av.y(),
				// ressTo.x(),
				// ressTo.y()
			// );
			ctx.quadraticCurveTo(
				ressFrom.x() + av.x() + (v.x()/2),
				ressFrom.y() + av.y() + (v.y()/2),
				ressTo.x(),
				ressTo.y()
			);
			
			// ctx.lineTo(ressTo.x(), ressTo.y());
			// ctx.closePath();
			ctx.stroke();
		}
				
	};
	
}




/*********************************************************************************
*********************************************************************************
					RESSOURCE
*********************************************************************************/

function Ressource (tab, context){
		
	var id = tab.id,
		val = tab.val,
		create_date = tab.create_date,
		trend = ((tab.trend > 20) ? 20 : tab.trend),
		type = parseInt(tab.type),
		entry_id = tab.entry_id,
		category_id = tab.category_id,
		alert = tab.alert,
		titre = tab.titre,
		links = [],
		context = context;

	var center = {x:0,y:0},
		size = {x:0,y:0},
		direction = {x:0,y:0},
		vitesse = 1,
		visible = false,
		alpha = 0.5,
		backgroundColor = "rgb(0,0,0)";

	this.top_left_center = {x: 0, y: 0};

	// if( (type >= 100 && type < 200) || (type >= 500 && type < 600) ){
		// size.y = 10;
		// size.x = size.y;
	// }

	this.id = function (value){
		if(value != null) id = value;
		return id;
	};
	this.val = function (value){
		if(value != null) val = value;
		return val;
	};
	this.calculWidth = function (ctx){
		
		ctx.font = size.y+'px Jura';
		ctx.textAlign = 'center';
		if(type >= 100 && type < 200) var metrics = ctx.measureText((titre != null) ? titre : "Vidéo");
		else if(type >= 200 && type < 300) var metrics = ctx.measureText((titre != null) ? titre : "Audio");
		else if(type >= 300 && type < 400) var metrics = ctx.measureText((titre != null) ? titre : "Image");
		else if(type >= 400 && type < 500) var metrics = ctx.measureText((titre != null) ? titre : "Lien");
		else if(type == 950) var metrics = ctx.measureText( val );
		else if(type == 990) var metrics = ctx.measureText( (titre == null) ? ( val.substring(0, 16)+"..." ) : titre );
		size.x = metrics.width;
	};
	this.width = function (val){
		if(val != null) size.x = value;
		return size.x;
	};
	this.height = function (){
		return size.y;
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
			this.top_left_center.x = value-(size.x/2);
		}
		return center.x;
	};
	this.y = function (value){
		if(value != null){
			center.y = value;
			this.top_left_center.y = value-(size.y/2);
		}
		return center.y;
	};
	this.w = function (value){
		if(value != null) w = value;
		return w;
	};
	this.radius = function (value){
		if(value != null){
			size.y = value;
			// if( (type >= 100 && type < 200) || (type >= 500 && type < 600) ){size.x = size.y;}
		}
		return size.y;
	};
	this.visible = function (value){
		if(value != null) visible = value;
		return visible;
	};
	this.alpha = function (value){
		if(value != null) alpha = value;
		return alpha;
	};
	this.backgroundColor = function (value){
		if(value != null) backgroundColor = value;
		return backgroundColor;
	};
	
	this.shortName = function (){
		
		if(titre != null) return titre;
		if(val.length > 19) return val.substring(0, 16)+"...";
		else return val;
		
	};
	
	this.draw = function (ctx, img){

		ctx.globalAlpha = alpha;
		ctx.fillStyle = backgroundColor;
		
		if(type >= 100 && type < 200){
		
			ctx.font = size.y+'px Jura';
			ctx.textAlign = 'center';
			ctx.fillStyle = 'black';
			ctx.textBaseline = 'middle';
			ctx.fillText( (titre != null) ? titre: "Vidéo" , center.x, center.y);
			ctx.drawImage(img.video, center.x-(size.x/2)-25, center.y-10, 20, 20);
			
		}else if(type >= 200 && type < 300){
		
			ctx.font = size.y+'px Jura';
			ctx.textAlign = 'center';
			ctx.fillStyle = 'black';
			ctx.textBaseline = 'middle';
			ctx.fillText((titre != null) ? titre : "Audio", center.x, center.y);
			ctx.drawImage(img.audio, center.x-(size.x/2)-25, center.y-10, 20, 20);
			
		}else if(type >= 300 && type < 400){
		
			ctx.font = size.y+'px Jura';
			ctx.textAlign = 'center';
			ctx.fillStyle = 'black';
			ctx.textBaseline = 'middle';
			ctx.fillText((titre != null) ? titre : "Image", center.x, center.y);
			ctx.drawImage(img.image, center.x-(size.x/2)-25, center.y-10, 20, 20);
			
		}else if(type >= 400 && type < 500){
		
			ctx.font = size.y+'px Jura';
			ctx.textAlign = 'center';
			ctx.fillStyle = 'black';
			ctx.textBaseline = 'middle';
			ctx.fillText((titre != null) ? titre : "Lien", center.x, center.y);
			ctx.drawImage(img.link, center.x-(size.x/2)-25, center.y-10, 20, 20);
			
		}else{
		
			ctx.font = size.y+'px Jura';
			ctx.textAlign = 'center';
			ctx.fillStyle = 'black';
			ctx.textBaseline = 'middle';
			if(titre == null){
				ctx.fillText( (type == 990 ) ? val.substring(0, 16)+"..." : val , center.x, center.y);
				if(type == 990)ctx.drawImage(img.text, center.x-(size.x/2)-25, center.y-10, 20, 20);
			}else{
				ctx.fillText( titre , center.x, center.y);
				ctx.drawImage(img.text, center.x-(size.x/2)-25, center.y-10, 20, 20);
			}
			
		}
		
	}

	this.addLink = function (obj){
		if(obj instanceof Link) links.push(obj);
	};
	this.getLinks = function (){
		return links;
	};

	this.distanceTo = function (obj){
		if(obj instanceof Ressource){
			
			return Math.sqrt( Math.pow(obj.x() - center.x, 2) + Math.pow(obj.y() - center.y, 2) ) - (obj.radius() + size.y);
			
		}
	}

	this.spaceBeetween = function (obj){
	
		if(obj instanceof Ressource){
			
			var v = new Vector(center.x,center.y, obj.x(), obj.y());
			var space = {x:0,y:0};
		
			space.x = (Math.abs( v.x() ) - ( (obj.width() + size.x)/2 )) - 20;
			space.y = Math.abs( v.y() ) - ( (obj.height() + size.y)/2 );
			
			return space;
			
		}
	}

	this.direction = function (val){
		if(val != null) direction = val;
		return direction;
	}

	this.move = function (vector){
		
		if(vector != null){
			center.x += vector.x*vitesse;
			center.y += vector.y*vitesse;
		}else{
			center.x += direction.x*vitesse;
			center.y += direction.y*vitesse;
		}
			this.top_left_center.x = center.x - (size.x/2);
			this.top_left_center.y = center.y - (size.y/2);
	};

	this.getPos = function (){
		return center;
	};

	this.isOver = function (mouse){
		return (mouse.x >= this.top_left_center.x 
		&& mouse.x <= this.top_left_center.x + size.x 
		&& mouse.y >= this.top_left_center.y 
		&& mouse.y <= this.top_left_center.y + size.y) ? true : false;
	};
	
}