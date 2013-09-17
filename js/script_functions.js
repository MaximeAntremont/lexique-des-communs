
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
	
$(function (){
	
	
});