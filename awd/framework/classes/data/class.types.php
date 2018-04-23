<?php

namespace AWD\Data{
	class Types{
		private $types;
		
		public function __construct($passedType){	
			$this->BuildTypes();			
		}		
		
		public function TypeSettings(){
			if($index = array_search($this->types, $this->types))
				return $this->types[$index];
			
			return array("unknown", "");
		}
		
		public function FormatValue($value){
			if(!isset($value))
				return "NULL";
			
			$setting = $this->TypeSettings();
			
			switch($setting[0]){
				/*
				Do nothing with date because validation handled it
				case "date":
				case "datetime":
				case "time":
				case "timestamp":
				*/
				case "bit";
				case "bool":
					return $value ? "'1'" : "'0'";
			}
				
			return "'$value'";			
		}
		
		public function ControlType($passedType){
			return $this->FindTypeByTypesIndex($passedType, 2);
		}
		
		public function ValidationType($passedType){
			return $this->FindTypeByTypesIndex($passedType, 1);
		}
		
		public function EnumType($passedType){
			return $this->FindTypeByTypesIndex($passedType, 3);
		}
		
		private function FindTypeByTypesIndex($passedType, $index){
			$type = $this->GetTypeLenghtArray($passedType);
			
			for($i=0;$i<count($this->types);$i++){
				if($type[0]==$this->types[$i][0])
					return $this->types[$i][$index];
			}
			
			return "text";
		}
		
		private function BuildTypes(){
			//-Db Type -- Val Function -- Html Control -- Enum for Js
			$this->types = array(
				array("char", "TestString", "text", 0), 
				array("varchar", "TestString", "text", 0), 
				array("tinytext", "TestString", "text", 0), 
				array("text", "TestString", "text", 1), 
				array("mediumtext", "TestString", "text", 1), 
				array("longtext", "TestString", "text", 1), 
				array("tinyint", "TestInteger", "text", 3),
				array("smallint", "TestInteger", "text", 3),
				array("mediumint", "TestInteger", "text", 3),
				array("int", "TestInteger", "text", 3),
				array("bigint", "TestInteger", "text", 3),
				array("float", "TestDecimal", "text", 6),
				array("double", "TestDecimal", "text", 6),
				array("decimal", "TestDecimal", "text", 6),
				array("date", "TestDate", "date", 4),
				array("datetime", "TestDateTime", "datetime", 4),
				array("timestamp", "TestInteger", "datetime", 4),
				array("time", "TestTime", "time", 4),
				array("bit", "TestBool", "radio", 5),
				array("bool", "TestBool", "radio", 5)
			);
		}
		
		private function GetTypeLenghtArray($passedType){
			$type = [];
			
			if($pos=strpos($passedType,"(")){
				$pos2 = strpos($passedType, ")");
				$pos2start = ($pos + 1);
				$pos2end = ($pos2 - $pos2start);
				
				$type['type'] = substr($passedType, 0, $pos);
				$type['length'] = $pos2 ? substr($passedType, $pos2start, $pos2end) : 0;
			}else{
				$type['type'] = $passedType;
				$type['length'] = 0;
			}
			
			return $type;
		}
	}
}

?>