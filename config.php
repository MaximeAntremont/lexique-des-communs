<?php

/* Il s'agit, ici, des infos de connection à la base de donnée */

/* true = en ligne | false = en local
	merci de ne pas faire de commit si la modification implique seulement
	le switch de vette valeur.
*/
if(false){
	
	define("DB_HOST", '');
	define("DB_PORT", '');
	define("DB_NAME", '');
	define("DB_USERNAME", '');
	define("DB_PASSWORD", '');
	
}else{
	define("DB_HOST", 'localhost');
	define("DB_PORT", '');
	define("DB_NAME", '');
	define("DB_USERNAME", 'root');
	define("DB_PASSWORD", '');
	
}