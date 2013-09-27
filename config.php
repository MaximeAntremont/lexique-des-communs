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
		2** - son
		3** - img
		4** - liens
		5** - textuel
	
	$ress_video_vimeo = array(
		'regex' => "#^(https?://)?(www\.)?vimeo.com/+[0-9]*#",
		'embed' => function($id){
			return '<iframe src="//player.vimeo.com/video/'. $id .'" width="300" height="225" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
		},
		'replace' => '#^(https?://)?(www\.)?vimeo.com/#'
	);
	
	$ress_video_youtube = array(
		'regex' => "#^(https?://)?(www\.)?youtu(be)?\.(com|be)/(watch\?v=)?+.*#",
		http://youtu.be/-gwQhX5nZy8
		'embed' => function($id){
			return '<div class="iframe"><iframe width="300" height="225" src="//www.youtube.com/embed/'. $id .'" frameborder="0" allowfullscreen></iframe></div>';
		},
		'replace' => '#^(https?://)?(www\.)?youtu(be)?\.(com|be)/(watch\?v=)?#'
	);
*/
	$ress_mot = array(
		'regex' => function ($val){
			
			return preg_match("#^.{2,20}$#", $val);
			
		},
	);
	$ress_texte = array(
		'regex' => function ($val){
			
			return preg_match("#.{21,}#", $val);
			
		},
		'embed' => function($id){
			return $id;
		}
	);
	$ress_lien = array(
		'regex' => function ($val){
			
			return preg_match("#^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$#", $val);
			
		},
		'embed' => function($id){
			return '<a href="'. $id .'" target="_BLANK">'. preg_replace('#^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$#', "$2.$3", $id  ) .'</a>';
		}
	);
	
	
	$ress_video_vimeo = array(
		'regex' => function ($val){
			
			return preg_match('#.*src="//player\.vimeo\.com/video/([0-9]+){1}".*#', $val);
			
		},
		'embed' => function($id){
		
			return '<iframe src="//player.vimeo.com/video/'. $id .'" width="100%" height="200" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
			
		},
		'get' => function ($val){
			
			return preg_filter(
				'#.*src="//player\.vimeo\.com/video/([0-9]+){1}(\?[a-zA-Z0-9=]*)*".*#',
				"$1",
				$val
				);
		
		}
	);
	
	
	$ress_video_youtube = array(
		'regex' => function ($val){
			
			return preg_match('#src="//www\.youtube\.com/embed/#', $val);
			
		},
		'embed' => function($id){
		
			return '<iframe width="100%" height="200" src="//www.youtube.com/embed/'. $id .'" frameborder="0" allowfullscreen></iframe>';
			
		},
		'get' => function ($val){
			
			return preg_filter(
				'#.*src="//www\.youtube\.com/embed/([-a-zA-Z-0-9]+){1}(\?[a-zA-Z0-9=]*)*".*#',
				"$1",
				$val
				);
		
		}
	);
	
	$ress_audio_soundCloud = array(
		'regex' => function ($val){
			
			return preg_match('#"https?://w\.soundcloud\.com/player/\?url=.*"#', $val);
			
		},
		'embed' => function($id){
		
			return '<iframe width="100%" height="166" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url='. $id .'"></iframe>';
		
		},
		'get' => function ($val){
			
			return preg_filter(
				'#.*"https?://w\.soundcloud\.com/player/\?url=(.*)".*#',
				"$1",
				$val
				);
		
		}
	);
	
	
	$ress_img_flickr = array(
		'regex' => function ($val){
			
			echo preg_match_all('~
				# Match Flickr link and embed code
				(?:<a [^>]*href=")?		# If a tag match up to first quote of src
				(?:				# Group Flickr url
					https?:\/\/		# Either http or https
					(?:[\w]+\.)*		# Optional subdomains
					(?:               		# Group host alternatives.
						flic\.kr     	# Either flic.kr
								| flickr\.com	# or flickr.com 
					)			# End Host Group
					(?:\/photos)?		# Optional video sub directory
					\/[^\/]+\/		# Slash and stuff before Id
					([0-9a-zA-Z]+)	# $1: PHOTO_ID is numeric
					[^\s]*			# Not a space
				)				# End group
				"?				# Match end quote if part of src
				(?:.*></a>)?			# Match the end of the a tag
				~ix', $val, $out);
			return false;
		},
		'embed' => function($tab){
		
			return '<a href="http://www.flickr.com/photos/'. $tab[0] .'" title="On the rocks (Explore 25.09.2013) de Keith Grafton, sur Flickr"><img src="http://farm4.staticflickr.com/'. $tab[1] .'" width="240" height="160" alt="On the rocks (Explore 25.09.2013)"></a>';
			
		},
		'get' => function ($val){
			$tab = array();
			$tab[0] = preg_filter(
				'#.*href="http://www\.flickr\.com/photos/([/@0-9a-zA-Z]+){1}".*#',
				"$1",
				$val
			);
			$tab[1] = preg_filter(
				'#.*src="http://farm4.staticflickr.com/([-/_@0-9a-zA-Z]+\.[a-zA-Z]*{1}){1}".*#',
				"$1",
				$val
			);
		
		}
	);
	
	
	
	
	
	$ress_dico = array(
		101 => $ress_video_vimeo,
		102 => $ress_video_youtube,
				
		201 => $ress_audio_soundCloud,
		
		301 => $ress_img_flickr,
		
		401 => $ress_lien,
		
		501 => $ress_mot,
		502 => $ress_texte
	);
	
	
	
/*************************************************************************************************
 *************************************************************************************************
	
	Ce qui se trouve en dessous permet d'identifier les logs.
	
*/

	$log_dico = array(
		101 => "Alerte d'un bug par un visiteur",
		
		201 => "enregistrement d'une entrée dans le serveur",
		202 => "enregistrement d'une ressource dans le serveur",
		
		301 => "incrementation du trend d'une ressource",
		302 => "decrementation du trend d'une ressource",
		303 => "alerte d'une ressource"
	);