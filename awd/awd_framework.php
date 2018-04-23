<?php
/*
Project: AWD PHP Website Framework
Created By Adam Duncan
Date: 12/20/2015
Desc: this is the required page for a website to work

-- Loads variables from querystring, and builds website array used on entire site
-- Then calls framework, loads a page, or loads an api

------------------------------------------------------------------------------------
					VARIABLES (set in the awd_main_array())
------------------------------------------------------------------------------------
AWD array -> 
	0 - type, (1st folder /)
	1 - page, (2nd folder /)
	2 - uri_arr, (all folders /)
	3 - uri,
	4 - page_variables, (all folders besides 1st and 2nd)
	5 - request_type (used for api, but will be null for everything else)
*/

try
{
	//AWD_DIR is set it above file.  if not, a default is set here
	defined("AWD_DIR") or define("AWD_DIR", "awd", false);

	//These 5 requires must load in this order.  
	//NOTE: if first config with AWD_ENV exists, it doesn't load config.php in the userbuilt folder
	if(file_exists(AWD_DIR . "/userbuilt/config." . AWD_ENV . ".php"))
		require_once(AWD_DIR . "/userbuilt/config." . AWD_ENV . ".php");
	elseif(file_exists(AWD_DIR . "/userbuilt/config.php"))
		require_once(AWD_DIR . "/userbuilt/config.php");

	require_once(AWD_DIR . "/framework/functions.php");
	require_once(AWD_DIR . "/framework/config.php");

	if(file_exists(AWD_DIR . "/userbuilt/functions.php"))
		require_once(AWD_DIR . "/userbuilt/functions.php");


	$AWD = awd_main_array();
}
catch(Exception $e)
{
	echo AWD\Message::Error(isset($AWD) ? $AWD['type'] : "html", $e);
}

try
{	

	//test if framework, if not call layout stuff
	if(!awd_is_frameworkpage())
		AWD\Layout::Render($AWD['page'], $AWD['type'], $AWD['request_type']);
	
	//close a connection if iconnection is selected
	awd_conn_close();
}
catch (Exception $e) 
{
	echo AWD\Messages::Error($AWD['type'], $e);
}

?>
