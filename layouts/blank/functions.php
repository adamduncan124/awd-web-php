<?php
/*
Project: AWD Framework
File: Blank Layout - functions
Created By Adam Duncan
Date: 04/23/2018
*/

function blank_write_menu($file_type){
	$hasFiles = false;
	
	echo "<ul>";
	
	$files = scandir(AWD_PAGES_PATH);
	foreach($files as $key => $name){
		if($name == ".." || $name == ".")
			continue;
		
		$name = str_replace(".php", "", $name);
		
		echo "  <li><a href='" . AWD_BASEURL . "/$name'>" . ucfirst($name) . "</a></li>";
		$hasFiles = true;
	}
	
	if(!$hasFiles)
		echo "  <li style='width: 100%;'>No Pages</li>";
	
	echo "</ul>";
}

function blank_load_websitesettings($file_type){
	$settings = awd_load_websitesettings(null);
	
	if(!isset($settings)){
		$settings = array(
			"title" => "AWD - Blank Layout",
			"keywords" => "awd framework, blank layout",
			"description" => "this is a demo of the AWD framework",
			"site-description" => "this is a demo of the AWD framework"
		)
	}
	
	return $settings;
}

?>