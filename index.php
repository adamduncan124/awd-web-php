<?php
/*
Project: AWD PHP Website Framework
Created By Adam Duncan
Date: 12/20/2015
Desc: this is the main index page of the site. along with the htaccess file it will process all http requests
*/


//--NOTE: adjust variable to the environment you want the config file to pick up, and also the variable used in your custom code.  
//if blank or not found, config.php alone is looked for.
defined("AWD_ENV") or define("AWD_ENV", "prod", false);

//--NOTE: adjust this variable to the folder if you change the folder name
defined("AWD_DIR") or define("AWD_DIR", "awd", false);

//--framework start
require_once(AWD_DIR . "/awd_framework.php");

?>
