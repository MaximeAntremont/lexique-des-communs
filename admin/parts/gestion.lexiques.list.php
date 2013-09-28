<?php session_start();

$lines = file('../bdd_lexiques.txt', FILE_SKIP_EMPTY_LINES);

foreach($lines as $line){
	
	preg_match('#([^"]+)"([^"]+)"#i', $line, $out);
	
	$lexique  = '<div lexique="'. $out[2] .'" class="listSelector">';
	$lexique .= '<h3>'. $out[1] .'</h3>';
	$lexique .= '</div>';
	
	echo $lexique;
}