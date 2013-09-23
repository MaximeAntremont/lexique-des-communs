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
		5** - liens
	
*/
	$ress_mot = array(
		'regex' => "#^[0-9A-Za-z]{2,20}$#"
	);
	$ress_lien = array(
		'regex' => "#^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$#",
		'embed' => function($id){
			return '<a href="'. $id .'" target="_BLANK">'. preg_replace('#^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$#', "$2.$3", $id  ) .'</a>';
		}
	);
	$ress_video_vimeo = array(
		'regex' => "#^https://vimeo.com/+[0-9]*#",
		'embed' => function($id){
			return '<iframe src="//player.vimeo.com/video/'. $id .'" width="300" height="225" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
		},
		'replace' => '#^https://vimeo.com/#'
	);
	$ress_video_youtube = array(
		'regex' => "#^http://www.youtube.com/watch\?v=+[0-9a-zA-Z]*#",
		'embed' => function($id){
			return '<div class="iframe"><iframe width="300" height="225" src="//www.youtube.com/embed/'. $id .'" frameborder="0" allowfullscreen></iframe></div>';
		},
		'replace' => '#^http://www.youtube.com/watch?v=#'
	);
	
	$ress_dico = array(
		101 => $ress_video_vimeo,
		102 => $ress_video_youtube,
		201 => $ress_mot,
		500 => $ress_lien
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