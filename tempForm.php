<?php

include_once('../config.php');
include_once('config.php');
include_once('class/tools.php');
include_once('class/entry.class.php');
include_once('class/ressource.class.php');
include_once('class/log.class.php');
include_once('class/link.class.php');
include_once('class/user.class.php');
include_once('class/manager.class.php');

//partie pour la sécurité
foreach($_POST as $key => $val){
	$_POST[$key] = htmlspecialchars(trim($val));
	echo "[$key]".$val."<br/>";
}

//partie pour la création des objets
$entry = new Entry($_POST);
$ressource = new Ressource($_POST);
$link = new Link($_POST);

if($db = getConnection()){
	$manager = new Manager($db);
	
	if(!$manager->isEntrySet($entry)) $manager->sendNewEntry($entry);
	if($manager->sendNewRessource($ressource)) header("location: ressource.test.php?tempId=".$ressource->entry_id());
	if($manager->sendNewLink($link)) header("location: ressource.test.php?tempId=".$link->entry_id());
	
}

header("location: entry.test.php");