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
// function normeVector (v){
	// return Math.sqrt( Math.pow(v.x,2) + Math.pow(v.y,2) );
// }

// function addVector(a, b){
	// return {x: b.x+a.x, y: b.y+a.y};
// }

// function resizeVector(v, factor){
	// var norme = normeVector(v);
	// if(v.x == 0 && v.y == 0)
		// return {
			// x: 0,
			// y: 0
		// };
	// else
		// return {
			// x: (v.x*factor)/norme,
			// y: (v.y*factor)/norme
		// };
// }

// function getVector (a, b){
	// return {x: b.x-a.x, y: b.y-a.y};
// }


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
		
		wait = true;
		waiting();
		
	};
	
	this.stopWaiting = function (close){
	
		if(close == true) waitCallback = function (){JQcache.hide();};
		else if(close) waitCallback = close;
		
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
		type = tab.type;
	
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
		return type;
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
		links = [],
		context = context;

	var center = {x:0,y:0},
		size = {x:0,y:0},
		direction = {x:0,y:0},
		vitesse = 1,
		visible = false,
		alpha = 0.5;

	this.top_left_center = {x: 0, y: 0};

	if( (type >= 100 && type < 200) || (type >= 500 && type < 600) ){
		size.y = 10;
		size.x = size.y;
	}

	this.id = function (value){
		if(value != null) id = value;
		return id;
	};
	this.val = function (value){
		if(value != null) val = value;
		return val;
	};
	this.calculWidth = function (ctx){
		if( (type >= 100 && type < 200) || (type >= 500 && type < 600) ) return;
		ctx.font = size.y+'px TEX';
		ctx.textAlign = 'center';
		var metrics = ctx.measureText(val);
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
			if( (type >= 100 && type < 200) || (type >= 500 && type < 600) ){size.x = size.y;}
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

	this.draw = function (ctx){

		context.globalAlpha = alpha;
		
		if(type >= 100 && type < 200 || (type >= 500 && type < 600) ){
			ctx.fillStyle = 'black';
			ctx.fillRect(center.x - (size.x/2), center.y - (size.y/2), size.x, size.y);
			ctx.beginPath();
			ctx.font = '10px TEX';
			ctx.textAlign = 'center';
			ctx.fillStyle = 'white';
			ctx.textBaseline = 'middle';
			ctx.fillText((type >= 500 && type < 600) ? "lien" : "vidéo", center.x, center.y);
			ctx.fill();
		}else{
			ctx.beginPath();
			ctx.font = size.y+'px TEX';
			ctx.textAlign = 'center';
			ctx.fillStyle = 'black';
			ctx.textBaseline = 'middle';
			ctx.fillText(val, center.x, center.y);
			ctx.fill();
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
			
			if( (type >= 100 && type < 200) || (type >= 500 && type < 600) ){
				space.x = Math.abs( v.x() ) - ( (obj.width() + size.x)/2 );
				space.y = Math.abs( v.y() ) - ( (obj.height() + size.y)/2 );
			}else{
				space.x = Math.abs( v.x() ) - ( (obj.width() + size.x)/2 );
				space.y = Math.abs( v.y() ) - ( (obj.height() + size.y)/2 );
			}
			
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