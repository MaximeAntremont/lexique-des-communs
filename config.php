<?php

/* 
	NE SURTOUT PAS METTRE D'INFORMATION PERSONNELLE DANS CE FICHIER !!!! (ex: mot de passe)
	A DECOMMENTER & COMPLETER SEULEMENT APRES MISE EN SERVICE SUR LE SERVEUR EN LIGNE
*/
	// define("DB_HOST", '');
	// define("DB_PORT", '');
	// define("DB_NAME", '');
	// define("DB_USERNAME", '');
	// define("DB_PASSWORD", '');

	header('Content-type: text/html; charset=utf-8');
	
	
	
	
/*************************************************************************************************
 *************************************************************************************************
	
	La partie ci-dessous permet de classer les ressources.
	Dans un premier temps, créez un tableau permettant d'identifier la ressource.
	default:
		$ress_video_vimeo = array(
			'name' => '', //partie servant à nommer le média (pour les logs et bugs)
			'regexp' => "", //partie sans les # pour reconnaître le type du média
			'embed' => "" //partie pour l'intégration de la vidéo
		);
	code:
		1** - vidéo
		2** -
		3** -
		4** -
		5** -
	
*/
	
	$ress_video_vimeo = array(
		'name' => 'vimeo',
		'regexp' => "vimeo+\.+com",
		'embed' => "<iframe src=\"//player.vimeo.com/video/$0\" width=\"500\" height=\"281\" frameborder=\"0\" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe> <p><a href=\"http://vimeo.com/70443172\">Don't Fear Death</a> from <a href=\"http://vimeo.com/diceproductions\">Dice Productions</a> on <a href=\"https://vimeo.com\">Vimeo</a>.</p>"
	);
	$ress_video_youtube = array(
		'name' => 'youtube',
		'regexp' => "",
		'embed' => ""
	);
	
	$ress_dico = array(
		
		101 => $ress_video_vimeo,
		102 => $ress_video_youtube
	);
	
	
	
/*************************************************************************************************
 *************************************************************************************************
	
	Ce qui se trouve en dessous permet d'identifier les logs.
	
*/

	$log_dico = array(
		201 => "enregistrement d'une entrée dans le serveur"
	);