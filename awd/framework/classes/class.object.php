<?php
/*
Project: AWD Framework
Created By Adam Duncan
Date: 06/17/2015
data/class.object.php
-------------------------
Purpose
----------------
allows a class to extend this and have a list of public properties.  
goes together with dataconnection when you use the data namespace
-------------------------
Change Log
-------------------------
04/16/2018 awd - added return xml and return json properties, and added SetProperties
04/16/2018 awd - moved to awd root for all classes
-------------------------
*/
namespace AWD{
  class Object{
    protected $idFieldName = "id";
	public $xmlValueField = "value";
    protected $objectTag = null;
    public $errorMsg;

    public $data = array();

    public function __construct($i=null){
      if($i!=null)
        $this->data["id"]=$i;
    }

    public function __get($name){	
      if(array_key_exists($name, $this->data)){
        return $this->data[$name];
      }elseif(strtolower($name)==strtolower($this->idFieldName)){
        return $this->data["id"];
      }else{
        return null;
      }
    }	
	
    public function __set($name, $val){ 
      //can not set id field
      if(strtolower($name)==strtolower($this->idFieldName)){
        $this->data["id"] = $val;
      }

      if(array_key_exists($name, $this->data))
        $this->data[$name]=$val;
    }
	
	public function __isset($key) {
       return $this->IssetData($key);
    }
	
	public function HasProperty($key){
		return array_key_exists($key, $this->data);
	}
	
	public function IssetData($key){
		return isset($this->data[$key]);
	}
	
	public function SetProperties($row){
		if($row instanceof self)
			$this->data = $row->data; //only want the data
		else
			$this->data = $row; 		
	}

    public function ReturnXMLArray($alt_data=null){
		if(!isset($alt_data))
			$alt_data = $this->data;
		
        $tag = array();
		$innerValue = null;
		
		//if no object, set it to class name
		$objectTag = isset($this->objectTag) ? $this->objectTag : get_class($this);
		
        if(is_array($alt_data)){
			foreach($alt_data as $key => $value) {
			  if($this->xmlValueField === $key)
				$innerValue = $value;
			  else
				$tag["attribute__" . $key] = $innerValue;
			}
        }
		
		if($innerValue instanceof self)
		  $tag["tagName__" . $objectTag] = $innerValue->ReturnXMLArray();
        else
          $tag["tagName__" . $objectTag] = $innerValue;

        return $tag;
    }
	
	public function ReturnXML($alt_data=null){
		return array_to_xml($this->returnXMLArray($alt_data), get_class($this));
	}
	
	public function ReturnJSON($alt_data=null){
		if(!isset($alt_data))
			$alt_data = $this->data;
		
		return array_to_json($alt_data);
	}

    public function AddToErrorMsg($str){
      if(!isset($str))
        return;
      
      if(isset($this->$errorMsg))
         $this->$errorMsg .= ", $str";
      else
         $this->$errorMsg = $str;
    }
  }
}
?>
