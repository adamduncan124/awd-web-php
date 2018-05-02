<?php
/*
Project: AWD Framework
File: Framework Config file
Created By Adam Duncan
Date: 01/25/2016
Desc: this is the framework config file.  it will merge missing default values with the environment config files

NOTE: These constants came from the the main awd_framework.php page.  AWD_ENV and AWD_DIR
*/

if(isset($AWD_CONFIG))
	$AWD_CONFIG_ENV = $AWD_CONFIG;

$AWD_CONFIG = array(
	"db" => array(
	  0 => array(
		"dbname" => "", 
		"username" => "", 
		"password" => "", 
		"servername" => ""
	  )
	),
	"url" => array(
		"base" => "http://" . $_SERVER['SERVER_NAME'],
		"private" => "https://" . $_SERVER['SERVER_NAME'],
		"cookie" => $_SERVER['SERVER_NAME']
	), 
	"path" => array(
		"scripts" => "scripts",
		"events" => "events",
		"pages" => "pages",
		"layouts" => "layouts",
		"resources" => array(
			"framework" => AWD_DIR . "/framework",
			"userbuilt" => AWD_DIR . "/userbuilt",
			"upload" => AWD_DIR . "/upload",
			"framework_pages" => AWD_DIR . "/framework/pages"
		)
	),
	"selectedlayout" => "blank",
	"defaultpage" => "home",
	"conn" => array(
		"useiconnection" => true,
		"connectiontype" => "\AWD\Data\MySQL"
	)
);

if(isset($AWD_CONFIG_ENV))
	$AWD_CONFIG = array_merge($AWD_CONFIG, $AWD_CONFIG_ENV);

//---start auto load
//this will load classes if called
//function __autoload($class_name) {
function my_autoloader($class_name) {
	$class_array = explode('\\', $class_name);
	$class_path = "classes/";
	$isFramework = false;
	
	for ($i = 0; $i < (count($class_array) - 1); $i++) {
	   if(($i === 0) && (strtolower($class_array[$i]) === "awd"))
	   	$isFramework = true;
	   else
		$class_path .= strtolower($class_array[$i]) . "/";
	}
	
	if($isFramework){
		if(file_exists(AWD_FRAMEWORK_PATH . "/" . $class_path . "class." . strtolower(end($class_array)) . '.php')){
			require_once AWD_FRAMEWORK_PATH . "/" . $class_path . "class." . strtolower(end($class_array)) . '.php';
		}
	}else{
		if(file_exists(AWD_USERBUILT_PATH . "/" . $class_path . "class." . strtolower(end($class_array)) . '.php')){
			require_once AWD_USERBUILT_PATH . "/" . $class_path . "class." . strtolower(end($class_array)) . '.php';
		}
	}
}

spl_autoload_register('my_autoloader');

// Or, using an anonymous function as of PHP 5.3.0
//spl_autoload_register(function ($class) {
//    include 'classes/' . $class . '.class.php';
//});
//--end of auto load

//make easier constants for some of the path folders
//defined("Upload_Path") or define("Upload_Path",$AWD_CONFIG['path']['resources']['upload'],false);
defined("AWD_PAGEEVENTS_PATH") or define("AWD_PAGEEVENTS_PATH", $AWD_CONFIG['path']['events'], false);
defined("AWD_LAYOUTS_PATH") or define("AWD_LAYOUTS_PATH",$AWD_CONFIG['path']['layouts'] . "/" . $AWD_CONFIG['selectedlayout'],false);
defined("AWD_HTML_SCRIPTS_PATH") or define("AWD_HTML_SCRIPTS_PATH",$AWD_CONFIG['url']['base']. "/" . $AWD_CONFIG['path']['scripts'],false);
defined("AWD_HTML_CSS_PATH") or define("AWD_HTML_CSS_PATH",$AWD_CONFIG['url']['base']. "/" . $AWD_CONFIG['path']['layouts'] . "/" . $AWD_CONFIG['selectedlayout'] . "/css",false);
defined("AWD_HTML_FRAMEWORK_PATH") or define("AWD_HTML_FRAMEWORK_PATH",$AWD_CONFIG['url']['base']. "/" . $AWD_CONFIG['path']['resources']['framework'],false);
defined("AWD_FRAMEWORK_PATH") or define("AWD_FRAMEWORK_PATH",$AWD_CONFIG['path']['resources']['framework'],false);
defined("AWD_FRAMEWORK_PAGES_PATH") or define("AWD_FRAMEWORK_PAGES_PATH",$AWD_CONFIG['path']['resources']['framework_pages'],false);
defined("AWD_USERBUILT_PATH") or define("AWD_USERBUILT_PATH",$AWD_CONFIG['path']['resources']['userbuilt'],false);
defined("AWD_PAGES_PATH") or define("AWD_PAGES_PATH",$AWD_CONFIG['path']['pages'],false);
defined("AWD_BASEURL") or define("AWD_BASEURL",$AWD_CONFIG['url']['base'],false);
defined("AWD_COOKIEURL") or define("AWD_COOKIEURL",$AWD_CONFIG['url']['cookie'],false);
?>
