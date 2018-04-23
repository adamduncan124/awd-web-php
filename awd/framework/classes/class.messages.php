<?php
/*
Project: AWD Framework
File: Messages (class.message.php)
Created By Adam Duncan
Date: 04/25/2015
Desc: this formats all messages depending on type
------------------------------
types 	  | xml,json,html
------------------------------
*/
namespace AWD{
  class Messages{

	public static function NotAllowed($type){
		return self::StandardFormat($type,"This request is not allowed");
	}
	
	public static function InValid($type){
		return self::StandardFormat($type,"This request is invalid");
	}
	
	public static function ExitMessage($type,$message){
		exit(self::StandardFormat($type,$message));
	}
	//todo: change object to responseerror
	public static function Error($type, $e){
		$obj;
		
		if($e instanceof \AWD\Exceptions\BaseException){
			$obj = array();
			
			//show friendly message
			$obj['message'] = (Environment==="prod" && $type=="html") ?
			"There was an error in your request.  Try again, and contact us if the issue continues" :
			$e;
			
			$obj['code'] = $e->getCode();
			
			//AWD to do add log here
		}else{
			$obj = $e;
		}		
		
		if(!isset($obj))
			$obj = "There was an error in your request";
		
		return self::StandardFormat($type, $obj);
	}
	
	public static function StandardFormat($type, $obj){	
		switch($type){
			case "xml":
				$return_str = "";
				foreach($obj as $key => $value)
					$return_str .= "<$key>$value</$key>";
				return $return_str;
			case "json":
				return array_to_json($obj);
			case "html":
				$message = is_array($obj) ? $obj['message'] : $obj;
				return "<br /><br /><div style=\"text-align: center;\">$message</div><br /><br />";
		}
	}

  }
}
