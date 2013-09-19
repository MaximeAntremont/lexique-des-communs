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
		2** - mots
		3** -
		4** -
		5** -
	
*/
	$ress_mo = array(
		'^*{20}'
	);
	$ress_video_vimeo = array(
		"vimeo+\.+com"
	);
	$ress_video_youtube = array(
		'name' => 'youtube',
		'regexp' => "",
		'embed' => ""
	);
	
	$ress_dico = array(
		101 => $ress_video_vimeo,
		102 => $ress_video_youtube,
		201 => $ress_mo
	);
	
	
	
/*************************************************************************************************
 *************************************************************************************************
	
	Ce qui se trouve en dessous permet d'identifier les logs.
	
*/

	$log_dico = array(
		201 => "enregistrement d'une entrée dans le serveur",
		202 => "enregistrement d'une ressource dans le serveur",
		
		301 => "incrementation du trend d'une ressource",
		302 => "decrementation du trend d'une ressource",
		303 => "alerte d'une ressource"
	);