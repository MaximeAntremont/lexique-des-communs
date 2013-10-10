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
	define("DB_PREFIX", 'bc_');

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
		9** - textuel
*/

	$ress_mot = array(
		'name' => 'Mot',
		'regex' => function ($val){
			
			return preg_match("#^.{2,20}$#", $val);
			
		},
	);
	$ress_texte = array(
		'name' => 'Texte',
		'regex' => function ($val){
			
			return preg_match("#.{21,}#", $val);
			
		},
		'embed' => function($id){
			return $id;
		}
	);
	$ress_lien = array(
		'name' => 'Lien',
		'regex' => function ($val){
			
			return preg_match("#^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$#", $val);
			
		},
		'embed' => function($id){
			return '<a href="'. $id .'" target="_BLANK">'. preg_replace('#^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})(.*)\/?$#', "$2.$3.$4", $id  ) .'</a>';
		}
	);
	
	
	$ress_lien_pearlTree = array(
		'name' => 'PearlTree',
		'regex' => function ($val){
			
			return preg_match('#https?://(pear\.ly|www\.pearltrees\.com)/.+#', $val);
			
		},
		'embed' => function($id){
		
			return '<a href="'. $id .'" target=_BLANK >Lien vers le Pearltree</a>';
			
		},
		'get' => function ($val){
			
			return $val;
		
		}
	);
	
	
	$ress_video_vimeo = array(
		'name' => 'Vimeo',
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
		'name' => 'Youtube',
		'regex' => function ($val){
			
			return preg_match('#src="//www\.youtube\.com/embed/#', $val);
			
		},
		'embed' => function($id){
		
			return '<iframe width="100%" height="200" src="//www.youtube.com/embed/'. $id .'" frameborder="0" allowfullscreen></iframe>';
			
		},
		'get' => function ($val){
			
			return preg_filter(
				'#.*src="//www\.youtube\.com/embed/([^?"]+)(\?[^"]*)*".*#',
				"$1",
				$val
				);
		
		}
	);	
	
	
	$ress_video_dailymotion = array(
		'name' => 'Dailymotion',
		'regex' => function ($val){
			
			return preg_match('#http://www\.dailymotion\.com/embed/video/([[:alnum:]]+)#', $val);
			
		},
		'embed' => function($id){
		
			return '<iframe frameborder="0" width="300" height="200" src="http://www.dailymotion.com/embed/video/'. $id .'"></iframe>';
			
		},
		'get' => function ($val){
			
			return preg_filter(
				'#.*src="https?://www\.dailymotion\.com/embed/video/([a-zA-Z0-9]+)".*#',
				"$1",
				$val);
			
		}
	);
	
	
	$ress_audio_soundCloud = array(
		'name' => 'SoundCloud',
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
		'name' => 'flickr',
		'regex' => function ($val){
			
			return preg_match(
				'#src="http://farm[0-9].staticflickr.com/([-/_@0-9a-zA-Z]+(\.[a-zA-Z]*){1})"#',
				$val);
		},
		'embed' => function($tab){
		
			return '<a href="http://www.flickr.com/photos/'. $tab[0] .'" title="'. $tab[2] .'" target=_BLANK ><img src="'. $tab[1] .'" width="300" alt="'. $tab[2] .'"></a>';
			
		},
		'get' => function ($val){
			$tab = array();
			$tab[0] = preg_filter(
				'#.*href="http://www\.flickr\.com/photos/([/@0-9a-zA-Z]+)".*#',
				"$1",
				$val
			);
			$tab[1] = preg_filter(
				'#.*(http://farm[0-9].staticflickr.com/([-/_@0-9a-zA-Z]+(\.[a-zA-Z]*)))".*#',
				"$1",
				$val
			);
			$tab[2] = preg_filter(
				'#.*title="([^"]*)".*#',
				"$1",
				$val
			);
			return $tab;
		}
	);
	
	
	
	
	
	$ress_dico = array(
		101 => $ress_video_vimeo,
		102 => $ress_video_youtube,
		103 => $ress_video_dailymotion,
				
		201 => $ress_audio_soundCloud,
		
		301 => $ress_img_flickr,
		
		401 => $ress_lien_pearlTree,
		499 => $ress_lien,
		
		950 => $ress_mot,
		990 => $ress_texte
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