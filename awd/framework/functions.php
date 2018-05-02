<?php
/*
Project: AWD Framework
File: Framework functions
Created By Adam Duncan
Date: 01/25/2016
Desc: these are the global functions needed everywhere on the site
------------------------------
Change Log
------------------------------
04/16/2018 awd - added iconnection functions
04/16/2018 awd - added api functions
04/16/2018 awd - renamed all special functions with an "awd_" prefix
------------------------------
TODO: move all to static functions in classes
------------------------------
*/

/*
AWD FUNCTIONS
NOTE: all functions for awd framework specifically have a "awd_" prefix
*/

//this is the main array for the framework based on url (TODO: make this a class one day)
function awd_main_array(){
	global $AWD_CONFIG;
	
	//this figures out the type of pages
	$uri = $_SERVER["REQUEST_URI"];
	$uri_AWD_BASEURL = (strrpos(AWD_BASEURL,"/",-1)) ? substr(AWD_BASEURL,strrpos(AWD_BASEURL,"/",-1)) . "/" : "";
	$uri_AWD_BASEURL = str_replace("//","/",$uri_AWD_BASEURL); //clean up
	$uri_explode = str_replace($uri_AWD_BASEURL,"",$uri);

	$AWD = array(
		"uri" => $uri,
		"uri_arr" => explode("/",$uri_explode),
		"page" => "",
		"type" => "",
		"get_variables" => null,
		"request_type" => null //only set for api
	);

	//fix uri to remove blanks and set AWD variables
	if(count($AWD['uri_arr'])>0){
		for($awd=0;$awd<count($AWD['uri_arr']);$awd++){
			if(trim($AWD['uri_arr'][$awd])==""){
				array_splice($AWD['uri_arr'], $awd, 1);
			}
		}
	}

	//get all the variables for the array
	if(count($AWD['uri_arr'])>0){ //it has a page sent
	   if(awd_istype($AWD['uri_arr'][0])){
		 $AWD['type'] = strtolower($AWD['uri_arr'][0]) == "api" ? awd_gettype_fromheader() : $AWD['uri_arr'][0];
		 if(count($AWD['uri_arr'])>1){ $AWD['page'] = $AWD['uri_arr'][1]; }
		 if(count($AWD['uri_arr'])>2){
		   $AWD['get_variables'] = $AWD['uri_arr'];
		   array_splice($AWD['get_variables'], 0, 1);
		   array_splice($AWD['get_variables'], 0, 1);
		 }
	   } else {
		 $AWD['page'] = $AWD['uri_arr'][0];
		 if(count($AWD['uri_arr'])>1){
			$AWD['get_variables'] = $AWD['uri_arr'];
			array_splice($AWD['get_variables'], 0, 1);
		 }
	   }
	}

	//also lower case everything
	$AWD['page'] = strtolower($AWD['page']);
	$AWD['type'] = strtolower($AWD['type']);
	//note: html is default if not provided
	if($AWD['type'] === ""){ $AWD['type'] = "html"; }
	if($AWD['page'] === ""){ $AWD['page'] = $AWD_CONFIG['defaultPage']; }
	
	if(isset($AWD['get_variables']) && $AWD['type'] != "html")
		$AWD['request_type'] = $AWD['get_variables'][0];
	
	return $AWD;
}

//this tries to load the websites specific settings (TEST: currently just loads nothing)
function awd_load_websitesettings($setting_type){
	return null;
}

//test main type variables.  must be in sync with index.php
function awd_istype($type){
	switch($type){
		case "html":
		case "xml":
		case "json":
		case "api":
		case "awd":
			return true;
		default:
			return false;
	}
}

//test content type from header, if nothing just return default
function awd_gettype_fromheader(){
	$accept = $_SERVER['HTTP_ACCEPT'];
	
	if(!isset($accept))
		return "html";
	
	$new_accept = [];
    foreach ($accept as $mimeType) {
        if (strpos($mimeType, '+') !== false) { // Contains +
            $arr = explode('/', $mimeType);
            $type = $arr[0];
            $medias = explode('+', $arr[1]);
            foreach ($medias as $media) {
                $new_accept[] = strtolower($type."/".$media); // Flatten
            }
        } else {
            $new_accept[] =  $mimeType;
        }
    }
    
	$unique_accept = array_unique($new_accept);
	
	if(in_array("application/xml", $unique_accept))
		return "xml";
	
	if(in_array("application/json", $unique_accept))
		return "json";
	
	return "html";
}

//test if this is a framework page
function awd_is_frameworkpage(){
	global $AWD;
	
	if($AWD['type']=="awd"){
		if(file_exists(AWD_FRAMEWORK_PAGES_PATH . "/" . $AWD['page'] . ".php"))
			include(AWD_FRAMEWORK_PAGES_PATH . "/" . $AWD['page'] . ".php");
			
		return true;
	}
	
	return false;
}

//because we use clean urls, going to use this function to get all page variables get variables
function awd_querystring(){
	global $AWD;
	
	if(!isset($AWD['get_variables'])){
		return false;
	}else{
		return $AWD['get_variables'];
	}
}

function awd_classname_urlpath($path){
	$buildClassName = str_replace(" ", "\\", ucfirst(str_replace("-", " ", $path)));		
	$buildClassName = (strpos($path, "-") ?  "\\" : "") . $buildClassName;
	
	return $buildClassName;
}

//this function writes out the main css tags
function awd_write_cssframework_tags($awd_file=null){
	if(!isset($awd_file))
		echo "<link rel='stylesheet' href=\"" . AWD_HTML_FRAMEWORK_PATH . "/css/awd-main.css?" . date("U") . "\" type='text/css' media='all' />";
	else
		echo "<link rel='stylesheet' href=\"" . AWD_HTML_FRAMEWORK_PATH . "/css/awd-$awd_file-main.css?" . date("U") . "\" type='text/css' media='all' />";
}

function awd_csslayout_tags($allow_mobile){
		echo "<link rel='stylesheet' id='awd-layout-css' href='" . AWD_HTML_CSS_PATH . "/layout.css?" . date("U") . "' type='text/css' media='all' />";
		
		if(($allow_mobile)&&(is_mobile()))
			echo "<link rel='stylesheet' id='awd-layout-mobile-css' href='" . AWD_HTML_CSS_PATH . "/layout-mobile.css?" . date("U") . "' type='text/css' media='all' />";
}

//this function writes out all the js include tags
function awd_write_frameworkscript_tags($awd_file=null){
	if(!isset($awd_file)){
		echo "<script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js\"></script>";
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"https://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css\" />";
		echo "<script src=\"https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js\"></script>";
		echo "<script type='text/javascript'>var AWD_BASEURLFrameWork = \"" . AWD_HTML_FRAMEWORK_PATH . "\";</script>";
		echo "<script src=\"" . AWD_HTML_FRAMEWORK_PATH . "/js/AWD_Jquery.js\" type=\"text/javascript\"></script>";
		echo "<script src=\"" . AWD_HTML_FRAMEWORK_PATH . "/js/AWD" . $awd_file . ".js?" . date("U") . "\" type=\"text/javascript\"></script>";
	}else{
		echo "<script src=\"" . AWD_HTML_FRAMEWORK_PATH . "/js/AWD-" . $awd_file . ".js?" . date("U") . "\" type=\"text/javascript\"></script>";	
	}
}

function awd_write_metadata($title, $keywords, $desc, $fb = false, $image_url = "", $append_page = true){
	//--test type of page variables to dipslay in head tag
	global $AWD;
	
	$meta_str = "";
	
	$http_str = is_secure() ? "https" : "http";
	
	$link = "$http_str://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			
	$title = $title . ($append_page ? " - " . ucfirst(str_replace("_", " ", $AWD['page'])) : "");
	$keywords = $keywords . ($append_page ? ", " . $AWD['page'] : "");
			
	//traditional meta
	$meta_str = "<title>$title</title>\n";
	
	$meta_str .= "<meta name=\"keywords\" content=\"" . $keywords . "\" />\n";
	$meta_str .= "<meta name=\"description\" content=\"" . $desc . "\" />\n";
			
	$meta_str .= "<link rel=\"shortcut icon\" type=\"image/x-icon\" href=\"" . AWD_BASEURL . "/favicon.ico\" />\n";
	$meta_str .= "<link rel=\"icon\" type=\"image/x-icon\" href=\"" . AWD_BASEURL . "/favicon.ico\" />\n";
			
	//OG tags (facebook)
	if($fb){
	   $meta_str .= "	<meta property=\"og:locale\" content=\"en_US\" />\n";
	   $meta_str .= "	<meta property=\"og:type\" content=\"article\" />\n";
	   $meta_str .= "	<meta property=\"og:title\" content=\"" . $title . "\" />\n";
	   $meta_str .= "	<meta property=\"og:description\" content=\"" . $desc . "\" />\n";
	   $meta_str .= "	<meta property=\"og:url\" content=\"" . $link . "\" />\n";
	   $meta_str .= "	<meta property=\"og:site_name\" content=\"" . $title . "\" />\n";
	   $meta_str .= "	<meta property=\"og:image\" content=\"" . $image_url . "\" />\n";
	   $meta_str .= "	<meta name=\"generator\" content=\"" . $title . "\" />\n";
	   $meta_str .= " 	<link rel=\"image_src\" href=\"" . $image_url . "\" />\n";
	}
			
	//add canonical
	if($link!="")
		$meta_str .= "	<link rel=\"canonical\" href=\"" . $link . "\" />\n";
	
	echo $meta_str;
}

function awd_redirect_tosecure(){
	global $AWD;
	
	if(is_secure()){
		$secure_url = $config['url']['private'];
		
		for($awd=0;$awd<count($AWD['uri_arr']);$awd++){
			$secure_url .= "/" . $AWD['uri_arr'][$awd];
		}
		
		header("Location: $secure_url");
		die();
	}
}

//----awd iConnection functions
//NOTE these two go by the conn iConnection
$AWD_CONN = null;

function awd_conn_int(){
	if(isset($AWD_CONN))
		return true;
	
	if($config['conn']['useiconnection']){
		$namespace_type = $config['conn']['connectiontype'];
		$class_conn = $namespace_type . "\Connection";
		
		if(class_exists($class_conn)){
			$obj = new $class_conn();
			
			if($obj instanceof AWD\Interfaces\iConnection)
				$AWD_CONN = $obj;
			else
				AWD\Exceptions\ConnectionException::ThrowMissingInterface();
		}else{
			AWD\Exceptions\ConnectionException::ThrowClassNotExists();
		}

		return true;
	}
	
	return false;
}

function awd_conn_sqlbuilder($table_name){ //table name is really important. so adding it here for now.
	if($config['conn']['useiconnection']){
		$namespace_type = $config['conn']['connectiontype'];
		$class_sql_builder = $namespace_type . "\SqlBuilder";
		
		if(class_exists($class_sql_builder)){
			$obj = new $class_sql_builder();
			
			if($obj instanceof \AWD\Interfaces\Data\iSqlBuilder){	
				$obj->tableName = $table_name;
				return $obj;
			}else
				\AWD\Exceptions\DataException::ThrowMissingSqlBuilder();
		}else{
			\AWD\Exceptions\DataException::ThrowMissingSqlBuilder();
		}
	}
	
	return false;
}

function awd_conn_injectconstruct($data_table, $table_name, $post_prefix, $dbname=null){
	if(!awd_conn_int())
		return;	
	
	$AWD_CONN->SelectDb($dbname);
	$data_table = new \AWD\Data\Table($AWD_CONN, awd_conn_sqlbuilder($table_name), $post_prefix);
	
	//AWD NOTE: let class decide
	//if(!class_exists($class))
	//	AWD\Exceptions\ConnectionException::ThrowInvalidClass();
	//if((!$obj instanceof AWD\Data\Table) && (!$obj instanceof AWD\Data\Row))
	//	AWD\Exceptions\ConnectionException::ThrowInvalidChild();
}

function awd_conn_close(){
	if(!awd_conn_int())
		return;
	
	$AWD_CONN->Close();
}

function awd_find_dbconfig($dbname){
	global $config;
	
	if((count($config['db'])>1)||($dbname!="")){
	  for($awd=0;$awd<count($config['db']);$awd++){
		if($config['db'][$awd]['dbname']==$dbname){
			return $config['db'][$awd];
		}
	  }
	}else{
	  if(count($config['db'])>0)
		return $config['db'][0];
	}
	
	return false;
}

//END----awd iConnection functions

function awd_is_custompageload($page, $type){
	global $AWD_CONFIG;
	
	//test if config file has a custom page load
	$custompage_load_str = $AWD_CONFIG['custompageload'];
	$has_custompage = isset($custompage_load_str);	

	if($has_custompage){
		$custompage_class = isset($custompage_load_str) ? new $custompage_load_str() : null;
		
		if($custom_page_class instanceof Interfaces\IPageLoad){
			$custompage_class->GetPage($page,$type);
			return true;
		}else{
			AWD\Exceptions\LayoutException::ThrowCustomPage("CustomPage: The configured page is not a member of IPageLoad");
		}
	}
	
	return false;
}

//--iApi FUNCTIONS
function awd_is_apitype($type){
	switch($type){
		case "json":
		case "xml":
		case "api":
			return true;
		default:
			return false;
	}
}

function awd_apiclass($classpath){
	$class = awd_classname_urlpath($classpath);
	
	if(!class_exists($class))
		AWD\Exceptions\ApiException::ThrowInvalidRequest();
	
	$obj = new $class();
	
	if(!$obj instanceof AWD\Data\Api)
		AWD\Exceptions\ApiException::ThrowMissingApiClass();
	
	return $obj;
}

function awd_write_apirequest($type, $classpath, $request_type){
	$obj = awd_apiclass($classpath);	
	$obj->ApiProcessRequest();
	
	$allow_request = false;
	switch($request_type){
		case "columns":
			if($allow_request = ($obj instanceof AWD\Interfaces\Api\iColumn)
				&& ($obj->ApiAllowPublic || $obj->ApiSelectAccess()))
				$obj->ApiSelect();
			break;
		case "select":
			if($allow_request = ($obj instanceof AWD\Interfaces\Api\iSelect)
				&& ($obj->ApiAllowPublic || $obj->ApiSelectAccess()))
				$obj->ApiSelect();
			break;
		case "save":
			if($allow_request = ($obj instanceof AWD\Interfaces\Api\iSave)
				&& ($obj->ApiAllowPublic || $obj->ApiSaveAccess()))
				$obj->ApiSave();
			break;
		case "delete":
			if($allow_request = ($obj instanceof AWD\Interfaces\Api\iDelete)
				&& ($obj->ApiDeleteAccess()))
				$obj->ApiDelete();
			break;
	}
	
	if(!$allow_request)
		AWD\Exceptions\ApiException::ThrowRestrictedRequest();
	
	switch($type){
		case "xml":
			echo $obj->ReturnApiXML();
		case "json":
			echo $obj->ReturnApiJSON();
		default:
			AWD\Exceptions\ApiException::ThrowMissingReturnType();
	}
}
//--End iApi FUNCTIONS


/*
END AWD FUNCTIONS
*/


/*
XML and JSON FUNCTIONS
*/
//add attributes to xml node
function add_attributes_to_xml_node($myNode, $myPropArray){
    if(!isset($myPropArray))
    	return;

    foreach($myPropArray as $key => $value){
		$myNode->addAttribute(str_replace("_","-",$key), $value);
    }
}

//single array to xml
//note: if properties needed, put a "attribute-" key value before the attribute name.  all property value keys get held for the next node in the loop
//note: all attribute values must be before the tag you want to write
//note: if "tagName-" then it will pass the value back to the sigle_array with the array numbered object and write all tags with that value
function single_array_to_xml($array, &$xml_obj, $altNumberValue=null, $myPropHold=null, $ignoreCreateIfArrayNumber=false) {
    $propHold = $myPropHold;
    foreach($array as $key => $value) {
    	 $bolTag = false;
    	 $bolAtt = false;
    	 
	 $balls=strpos("a" . $key, "attribute__");
    	 if($balls>0){
    	 	$bolAtt = true;
    	 }
    	 
    	 $balls=strpos("a" . $key, "tagName__");
    	 if($balls>0){
    	 	$bolTag = true;
    	 }

        if(is_array($value)) {
            if(is_numeric($key)){
			  if(isset($altNumberValue)){
			    if($ignoreCreateIfArrayNumber)
				$subnode = $xml_obj;
			    else
			       $subnode = $xml_obj->addChild("$altNumberValue");
			  }else{
			    if($ignoreCreateIfArrayNumber)
			      $subnode = $xml_obj;
			    else
				$subnode = $xml_obj->addChild("item$key");
			  }
				
			single_array_to_xml($value, $subnode);
			add_attributes_to_xml_node($subnode, $propHold);
			$propHold = null; 
	     } elseif($bolTag){
		  $keyNew = str_replace("tagName__", "", $key);
		  $subnode = $xml_obj->addChild("$keyNew");
		  add_attributes_to_xml_node($subnode, $propHold);
		  single_array_to_xml($value, $subnode, $keyNew, null,true);	
            } else{
            	  $subnode = $xml_obj->addChild("$key");
                single_array_to_xml($value, $subnode,null,null,true);
		  add_attributes_to_xml_node($subnode, $propHold);
		  $propHold = null;
            }
        } else {
			if($bolAtt){
				if(!isset($propHold)){ $propHold = array(); }
				$keyNew = str_replace("attribute__", "", $key);
				$propHold["$keyNew"] = $value;
			} elseif($bolTag){
				$keyNew = str_replace("tagName__", "", $key);
				$subnode = $xml_obj->addChild("$keyNew",$value);
				add_attributes_to_xml_node($subnode, $propHold);
				$propHold = null;
			}elseif(is_numeric($key)){
			  if(isset($altNumberValue)){
				$subnode = $xml_obj->addChild("$altNumberValue",$value);
			  }else{
			       $subnode = $xml_obj->addChild("item$key",$value);
			  
				add_attributes_to_xml_node($subnode, $propHold);
				$propHold = null;
			  }
			}else{
            			$subnode = $xml_obj->addChild("$key",$value);
				add_attributes_to_xml_node($subnode, $propHold);
				$propHold = null;
			}
        }
    }
}

//convert array to xml
function array_to_xml_obj( $array, $xml_root_str, $docType='' ,$xml_root_properties=null, $rootAltNumberValue=null) {
	if( !is_array( $array ) ){
        return false;
    }

	// creating object of SimpleXMLElement
	$docTypeWrite = '';
	if($docType!=''){ $docTypeWrite=$doctype . "\n"; }
	$xml_prop = "";
	if(isset($xml_root_properties)){ 
	  if(is_array($xml_root_properties)){
		foreach($xml_root_properties as $key => $value){
			$xml_prop .= " $key=\"$value\"";
		}
	  }else{
		$xml_prop=$xml_root_properties;
	  }
	}
	$xml_obj = new SimpleXMLElement("<?xml version=\"1.0\" encoding=\"utf-8\"?>$docTypeWrite<$xml_root_str$xml_prop></$xml_root_str>");

	// function call to convert array to xml
	single_array_to_xml($array,$xml_obj,$rootAltNumberValue);

	//saving generated xml file
	return $xml_obj;

}
function array_to_xml( $array, $xml_root_str, $docType='', $xml_root_properties=null, $rootAltNumberValue=null) {
	$xml_obj = array_to_xml_obj($array, $xml_root_str, $docType,$xml_root_properties,$rootAltNumberValue);
	return $xml_obj->asXML();
}

//convert array to json
function array_to_json( $array ){

    if( !is_array( $array ) ){
        return false;
    }

    $associative = count( array_diff( array_keys($array), array_keys( array_keys( $array )) ));
    if( $associative ){

        $construct = array();
        foreach( $array as $key => $value ){

            // We first copy each key/value pair into a staging array,
            // formatting each key and value properly as we go.

            // Format the key:
            if( is_numeric($key) ){
                $key = "key_$key";
            }
            $key = "\"".addslashes($key)."\"";

            // Format the value:
			if($value instanceof AWD\Data\Object){
				$value = array_to_json($value->data);
			} else if( is_array( $value )){
                $value = array_to_json( $value );
            } else if( !is_numeric( $value ) || is_string( $value ) ){
                //$value = "\"".addslashes(str_replace("<br>","  ",str_replace("<br />","  ",$value)))."\"";
				$value = "\"" . str_replace("\"","&quot;",str_replace("\t"," ",str_replace("\n"," ",$value))) . "\""; //html encode instead
            }

            // Add to staging array:
            $construct[] = "$key: $value";
        }

        // Then we collapse the staging array into the JSON form:
        $result = "{ " . implode( ", ", $construct ) . " }";

    } else { // If the array is a vector (not associative):

        $construct = array();
        foreach( $array as $value ){

            // Format the value:
			if($value instanceof AWD\Data\Object){
				$value = array_to_json($value->data);
			} else if( is_array( $value )){
                $value = array_to_json( $value );
            } else if( !is_numeric( $value ) || is_string( $value ) ){
                //$value = "'".addslashes($value)."'";
				$value = "\"" . str_replace("\"","&quot;",str_replace("\t"," ",str_replace("\n"," ",$value))) . "\""; //html encode instead
            } 

            // Add to staging array:
            $construct[] = $value;
        }

        // Then we collapse the staging array into the JSON form:
        $result = "[ " . implode( ", ", $construct ) . " ]";
    }

    return $result;
}
/*
END XML AND JSON FUNCTIONS
*/




/*
HTTP FUNCTIONS
*/

//because i'm unsure the proper process, going to use this as the single place for all form request
function get_request_var($name){
	return ((isset($_POST)) && isset($_POST[$name])) ? $_POST[$name] : "";
}

function is_mobile(){
	// Get the user agent

	$user_agent = $_SERVER['HTTP_USER_AGENT'];

	// Create an array of known mobile user agents
	// This list is from the 21 October 2010 WURFL File.
	// Most mobile devices send a pretty standard string that can be covered by
	// one of these.  I believe I have found all the agents (as of the date above)
	// that do not and have included them below.  If you use this function, you 
	// should periodically check your list against the WURFL file, available at:
	// http://wurfl.sourceforge.net/


	$mobile_agents = Array(


		"240x320",
		"acer",
		"acoon",
		"acs-",
		"abacho",
		"ahong",
		"airness",
		"alcatel",
		"amoi",	
		"android",
		"anywhereyougo.com",
		"applewebkit/525",
		"applewebkit/532",
		"asus",
		"audio",
		"au-mic",
		"avantogo",
		"becker",
		"benq",
		"bilbo",
		"bird",
		"blackberry",
		"blazer",
		"bleu",
		"cdm-",
		"compal",
		"coolpad",
		"danger",
		"dbtel",
		"dopod",
		"elaine",
		"eric",
		"etouch",
		"fly " ,
		"fly_",
		"fly-",
		"go.web",
		"goodaccess",
		"gradiente",
		"grundig",
		"haier",
		"hedy",
		"hitachi",
		"htc",
		"huawei",
		"hutchison",
		"inno",
		"ipad",
		"ipaq",
		"ipod",
		"jbrowser",
		"kddi",
		"kgt",
		"kwc",
		"lenovo",
		"lg ",
		"lg2",
		"lg3",
		"lg4",
		"lg5",
		"lg7",
		"lg8",
		"lg9",
		"lg-",
		"lge-",
		"lge9",
		"longcos",
		"maemo",
		"mercator",
		"meridian",
		"micromax",
		"midp",
		"mini",
		"mitsu",
		"mmm",
		"mmp",
		"mobi",
		"mot-",
		"moto",
		"nec-",
		"netfront",
		"newgen",
		"nexian",
		"nf-browser",
		"nintendo",
		"nitro",
		"nokia",
		"nook",
		"novarra",
		"obigo",
		"palm",
		"panasonic",
		"pantech",
		"philips",
		"phone",
		"pg-",
		"playstation",
		"pocket",
		"pt-",
		"qc-",
		"qtek",
		"rover",
		"sagem",
		"sama",
		"samu",
		"sanyo",
		"samsung",
		"sch-",
		"scooter",
		"sec-",
		"sendo",
		"sgh-",
		"sharp",
		"siemens",
		"sie-",
		"softbank",
		"sony",
		"spice",
		"sprint",
		"spv",
		"symbian",
		"tablet",
		"talkabout",
		"tcl-",
		"teleca",
		"telit",
		"tianyu",
		"tim-",
		"toshiba",
		"tsm",
		"up.browser",
		"utec",
		"utstar",
		"verykool",
		"virgin",
		"vk-",
		"voda",
		"voxtel",
		"vx",
		"wap",
		"wellco",
		"wig browser",
		"wii",
		"windows ce",
		"wireless",
		"xda",
		"xde",
		"zte"
	);

	// Pre-set $is_mobile to false.

	$is_mobile = false;

	// Cycle through the list in $mobile_agents to see if any of them
	// appear in $user_agent.

	foreach ($mobile_agents as $device) {

		// Check each element in $mobile_agents to see if it appears in
		// $user_agent.  If it does, set $is_mobile to true.

		if (stristr($user_agent, $device)) {

			$is_mobile = true;

			// break out of the foreach, we don't need to test
			// any more once we get a true value.

			break;
		}
	}

	return $is_mobile;
}

function is_secure() {
  return
    (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || $_SERVER['SERVER_PORT'] == 443;
}

/*
HTTP FUNCTIONS
*/
?>
