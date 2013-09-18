/******************************
	Graphical Process Unit
		by Elie MEIGNAN
			v. 0.06
*******************************/


/* SCREEN
******************************************************************/
window.GPUScreen = function (par){
	
	var canvas      = par.canvas,
		context     = par.canvas.getContext('2d'),
		autoRefresh = par.autoRefresh,
		fframe      = false,
		delay       = par.delay || 1,
		autoClear   = par.autoClear,
		delayTemp   = 0;
	 
	this.draw = function (frame){
		if(delay == 1 || (delayTemp + delay) == frame){
			
			if(autoClear)
				context.clearRect(0, 0, canvas.width, canvas.height);
				
			if(fframe != false)
				fframe.read(canvas, context, frame, fframe.getVariables());
			
			delayTemp = frame;
			
		}
	}
	
	this.clear = function (){context.clearRect(0, 0, canvas.width, canvas.height);};
	this.setFrame  = function (frame){fframe = frame;};
	this.setDelay  = function (val){delay = val;};
	this.getCanvas = function (){return canvas;};
	
	this.isAutoClear = function(val){
		if(val == true || val == false) autoClear = val;
		return autoClear;
	};
	this.isAutoRefresh = function(val){
		if(val == true || val == false) autoRefresh = val;
		return autoRefresh;
	};
	
}

/* FRAME
******************************************************************/
window.GPUFrame = function (func){
	
	var variables = {};
	
	var f = func;
	if(func == false){
		 f = function(){};
	}
	
	this.read = f;
	this.write = function (fun){this.read = fun;};
	this.addVariable = function (name, obj){variables[name] = obj;};
	this.getVariable = function (name){return variables[name];};
	this.getVariables = function (){return variables;};
	
}

/* GPU
******************************************************************/
function GPU (doILaunchMe){

	var screens = [],
	frameLength = 1000/25;
	frame = 1;
	
	window.requestAnimFrame = (function(callback) {
		return window.requestAnimationFrame
		|| window.webkitRequestAnimationFrame
		|| window.mozRequestAnimationFrame
		|| window.oRequestAnimationFrame
		|| window.msRequestAnimationFrame
		|| function(callback) {
			window.setTimeout(callback, 1000 / 60);
		};
	})();

	this.addCanvas = function (screen){
		screens.push(screen);
	};
	
	function drawAllCanvas (){
		
		screensL = screens.length;
		for(var i=0;i<screensL;i++){
			if(screens[i].isAutoRefresh()){screens[i].draw(frame);}
		}
		
	}
	
	this.drawAllCanvas = function (){drawAllCanvas();};

	this.drawCanvas = function (canvas){
		
		screensL = screens.lentgh;
		
		for(var i=0;i<screensL;i++){
			if(screens[i] == canvas) screens[i].draw(frame);
		}
		
	};
		
	function autoRefresh (){
		
		drawAllCanvas();
		frame++;
		requestAnimFrame(function(){autoRefresh();});
		
	}
	this.autoRefresh = function (){autoRefresh();	};
	
	this.setFrameRate = function (frameRate){
		frameLength = 1000/frameRate;
	};
	
	this.getFrame = function (){return frame;};
	
	
	
	this.Circle = function (par){
		
		var center      = {x: par.x || 0, y: par.y || 0 },
			radius      = par.radius || 0,
			fill        = {color: par.fillColor || false },
			stroke      = {size: par.strokeSize || 0, color: par.strokeColor || "black" },
			opacity     = par.opacity || 1,
			isMouseover = false;
		
		this.opacity = function (val, offset){
			if(val == undefined)
				return opacity;
				
			if(offset == true)
				opacity += val;
			else
				opacity = val;
				
			if(opacity < 0)
				opacity = 0;
			if(opacity > 1)
				opacity = 1;
			
		};
		this.radius = function (val, offset){
			if(val == undefined)
				return radius;
			if(offset == true)
				radius += val;
			else
				radius = val;
		};
		this.strokeSize = function (val, offset){
			if(val == undefined)
				return stroke.size;
			if(offset == true)
				stroke.size += val;
			else
				stroke.size = val;
		};
		this.strokeColor = function (val){
			if(val == undefined)
				return stroke.color;
			else
				stroke.color = val;
		};
		
		this.setOver = function (val){
			if(val == true || val == false) isMouseover = val;
			return isMouseover;
		};
		
		
		this.isOver = function (val){return isMouseover;};
		
		this.draw = function(ctx){
			ctx.beginPath();
			ctx.globalAlpha = opacity;
			ctx.arc(center.x, center.y, radius, 0 , 2 * Math.PI, false);
			if(fill.color != false){
				ctx.fillStyle = fill.color;
				ctx.fill();
			}
			if(stroke.size > 0){
				ctx.lineWidth = stroke.size;
				ctx.strokeStyle = stroke.color;
				ctx.stroke();
			}
		}
		
		this.detect = function(par, min){
		
			var tNorme = norme(par, center);
			var maxR = radius + (stroke.size/2);
			var minR = min || 0;
			
			if( tNorme <= maxR
			 && tNorme > minR ){
				isMouseover = true;
				return true;
				
			}else{
				isMouseover = false;
				return false;
			}
			
		};

	}
	
	this.Grid = function (par){
		var column = par.column || 1,
			line = par.line || 1;
		var grid = [];
		var cursor = {x:0, y:0};
		
		
		this.clearAll = function (){
			for(var y=0; y < line; y++){
				grid[y] = [];
				for(var x=0; x<column; x++){
					grid[y][x] = { x: x, y: y, val: null, isVoid: true };
				}
			}
		};
		this.getColumnLength = function (){return column;};
		this.getLineLength = function (){return line;};
		this.resetCursor = function (){cursor = {x:0, y:0};};
		this.setCursor = function (x, y){if(x >= 0 && x < column && y >= 0 && y < line){cursor = {x:x, y:y};}else{return false;}};
		this.getCursor = function (){return cursor;};
		this.get = function (x,y){if(x >= 0 && x < column && y >= 0 && y < line){return grid[y][x];}else{return null;}};
		this.set = function (x,y,val){
			if(x >= 0 && x < column && y >= 0 && y < line){grid[y][x] = { x: x, y: y, val: val, isVoid: false };return true;}else{return null;}
		};
		this.exchange = function (x,y,X,Y){
			if(x >= 0 && x < column && y >= 0 && y < line &&
			   X >= 0 && X < column && Y >= 0 && Y < line){
				var a = this.get(x,y);
				var b = this.get(X,Y);
				this.set(X,Y,a.val);
				this.set(x,y,b.val);
				return true;
			}else{return false;}
		};
		this.addColumn = function (index, func){
			if(index >= column || index == undefined || index < 0){
				for(var y=0; y<line; y++){
				
					var value = null;
					var isVoid = true;
					if(func != undefined){
						value = func(y);
						isVoid = false;
					}
					grid[y][column] = { x: column, y: y, val: value, isVoid: isVoid };
					
				}
				column++;
			}else if (index >= 0){
				column++;
				for(var y=0; y<line; y++){
					
					var value = null;
					var isVoid = true;
					if(func != undefined){
						value = func(y);
						isVoid = false;
					}
					
					var a = { x: index, y: y, val: value, isVoid: isVoid };
					var b = null;
					for(var x=index; x<column; x++){
					
						b = this.get(x,y);
						this.set(x,y,a.val);
						a = b;
						
					}
					
				}
			}
		};
		this.addLine = function (index, func){
			if(index >= column || index == undefined || index < 0){
				grid[line] = [];
				for(var x=0; x<column; x++){
				
					var value = null;
					var isVoid = true;
					if(func != undefined){
						value = func(x);
						isVoid = false;
					}
					
					grid[line][x] = { x: x, y: line, val: value, isVoid: isVoid };
					
				}
				line++;
			}else if (index >= 0){
				line++;
				var a = [];
				for(var x=0; x<column; x++){
				
					var value = null;
					var isVoid = true;
					if(func != undefined){
						value = func(x);
						isVoid = false;
					}
					
					a[x] = { x: x, y: index, val: value, isVoid: isVoid };
					
				}
				var b = null;
				
				for(var y=index; y<line; y++){
					if(y<line-1)
						for(var x=0; x<column; x++){
							grid[y][x].y++;
						}
					b = grid[y];
					grid[y] = a;
					a = b;
				}
				
			}
		}
		this.inject = function (val){
			
			if(cursor.y >= line){return false;}
			if(cursor.x >= column){
				
				cursor.x = 0;
				cursor.y++;
				
				if(cursor.y >= line){return false;}
				else{
					// alert("cursor: " + cursor.x + ", " + cursor.y + " <- " + val);
					grid[cursor.y][cursor.x] = { x: cursor.x, y: cursor.y, val: val, isVoid: false };
					cursor.x++;
					return true;
				}
				
			}else{
				// alert("cursor: " + cursor.x + ", " + cursor.y + " <- " + val);
				grid[cursor.y][cursor.x] = { x: cursor.x, y: cursor.y, val: val, isVoid: false };
				cursor.x++;
				return true;
			}
			
		};
		this.fetch = function (){
			
			if(cursor.y >= line){return null;}
			else{
				var temp = grid[cursor.y][cursor.x];
				// alert("cursor: " + cursor.x + ", " + cursor.y + " -> " + temp);
				cursor.x++;
				if(cursor.x >= column){
					cursor.x = 0;
					cursor.y++;
				}
				return temp;
			}
			
		}
		
		
		//To construct the grid
		this.clearAll();
	}
	
	this.randomColor = function (seuil){
		if(seuil < 0)
			seuil = 0;
		else if(seuil > 255)
			seuil = 255;
		else if(seuil == undefined)
			seuil = 0;
		var toReturn = "rgb("
		+ Math.ceil((Math.random()*(255-seuil))+seuil) + ","
		+ Math.ceil((Math.random()*(255-seuil))+seuil) + ","
		+ Math.ceil((Math.random()*(255-seuil))+seuil) + ")";
		// writeInConsole(seuil + toReturn);
		return toReturn;
	};
	
	
	// Just let it at the end ! It will launch the GPU at start if it's ask.
	if(doILaunchMe) this.autoRefresh();
	
	function norme(A,B){return Math.sqrt(Math.pow(B.x - A.x, 2)+Math.pow(B.y - A.y,2));}
}
/* INITIALISATION
******************************************************************/
window.GPU = new GPU(true);

/* TEMPORARY
******************************************************************/
function writeInConsole (text) {
    if (typeof console !== 'undefined') {
        console.log(text);    
    }
    else {
        alert(text);    
    }
}