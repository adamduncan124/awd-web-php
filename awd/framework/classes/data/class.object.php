<?php
/*
Project: AWD Framework
Created By Adam Duncan
Date: 06/17/2015
data/class.object.php
-------------------------
Purpose
----------------
allows a class to extend this and have a list of public properties.  goes together with dataconnection
-------------------------
Change Log
-------------------------
04/16/2018 awd - added return xml and return json properties, and added SetProperties
-------------------------
*/
namespace AWD\Data{
  class Object{
    protected $idFieldName = "id";
    public $innerText = "";
    public $innerArray = null;
    protected $objectTag = "object";
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

	public function SetProperties($row){
		if($row instanceof self)
			$this->data = $row->data; //only want the data
		else
			$this->data = $row; 		
	}
	
    public function __set($name, $val){ 
      //can not set id field
      if(strtolower($name)==strtolower($this->idFieldName)){
        $this->data["id"] = $val;
      }

      if(array_key_exists($name, $this->data))
        $this->data[$name]=$val;
    }

    public function returnXMLArray(){
        $myTag = array();
        if(is_array($this->data)){
        foreach($this->data as $key => $value) {
          $myTag["attribute__" . $key] = $value;
        }
        }
        if($this->innerText!="")
          $myTag["tagName__" . $this->objectTag] = $this->innerText;
        else
          $myTag["tagName__" . $this->objectTag] = $this->innerArray;

        return $myTag;
    }
	
	public function returnXML(){
		return array_to_xml($this->returnXMLArray(), get_class($this));
	}
	
	public function returnJSON(){
		return array_to_json($this->data);
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
