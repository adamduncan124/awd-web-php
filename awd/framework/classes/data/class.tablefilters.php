<?php
/*
Project: AWD Framework
Created By Adam Duncan
Date: 06/25/2017
data/class.tablefilters.php
-------------------------
Purpose
----------------
this creates a filter object that can be used in both table and table join classes
-------------------------
*/
namespace AWD\Data{
	class TableFilters{
		private $aFilters;
		private $filterCount;
		private $aOrFields;
		private $orFieldCount;
		private $validation;
		
		public function __construct($columns=null){
			$this->validation = new Validation($columns);
			$this->aFilters = array();
			$this->filterCount = 0;
			$this->aOrFields = array();
			$this->orFieldCount = 0;
		}
		
		//NOTE: if not valid filter, just exclude
		public function AddFilter($obj)
		{
			if(!is_array($obj)
				|| !array_key_exists('field', $obj)
				|| !array_key_exists('item_value', $obj)
				|| !array_key_exists('opperator', $obj)
				|| !array_key_exists('is_add', $obj)
			)
				return;

			
			$this->AddFilter($obj['field'], $obj['item_value'], $obj['opperator'], $obj['is_add'])
		}
		
		public function AddFilter($field, $item_value, $opperator="like", $is_and = true){
			$fitler = array();
			
			$fitler['field'] = $field;
			$fitler['item_value'] = $item_value;
			$fitler['opperator'] = $opperator;
			
			if($is_and){
				$this->aFilters[$this->filterCount] = $fitler;
				$this->filterCount += 1;
			}else{
				$this->aOrFields[$this->orFieldCount] = $fitler;
				$this->orFieldCount += 1;
			}
		}
		
		public function ReturnFilterString(){
			return sprintf("%s%s", $this->BuildFilterString(), $this->BuildOrFieldsString());
		}
		
		private function BuildFilterString(){
			$sql = "";
			
			foreach($this->aFilters as $key => $fitler){
				if(!$this->TestValidation($orfield['field'], $orfield['item_value']))
					continue;
				
				$filterStr = $this->FormatFilterBlock($fitler['field'], $fitler['item_value'], $fitler['opperator'], " and ");
				
				$sql .= ($filterStr=="") ? sprintf(" and `%s` like '%s'", $fitler['field'], $fitler['item_value']) : $filterStr;
			}
			
			return $sql;
		}
	
		private function BuildOrFieldsString(){
			$sql = "";
			
			foreach($this->aOrFields as $key => $orfield){
				if(!$this->TestValidation($orfield['field'], $orfield['item_value']))
					continue;
			
				$delimiter = ($sql!="") ? " or " : "";
				
				$filterStr = $this->FormatFilterBlock($orfield['field'], $orfield['item_value'], $orfield['opperator'], $delimiter);
				
				$sql .= ($filterStr=="") ? sprintf("$delimiter`%s` like '%s'", $orfield['field'], $orfield['item_value']) : $filterStr;
			}
			
			if($sql!="")
				$sql = " and ($sql)";
	
			return $sql;
		}
	
		private function FormatFilterBlock($field, $value, $opperator, $delimiter){
			$filterStr = "";
			
			if(isset($opperator)){				
				switch($opperator){
					case ">":
					case "<":
					case "<=":
					case "=<":
						$filterStr = sprintf("$delimiter`%s` %s %s", $field, $opperator, $value);
						break;
					case "|":
					case "between":
						$aDates = explode('|', $value);
						$filterStr = sprintf("$delimiter`%s` between '%s' and '%s'", $field, $aDates[0], $aDates[1]); //skip first array element
						break;
					case ",":
					case "in":
						$filterStr = sprintf("$delimiter`%s` in (%s)", $field, $value); //skip first comma
						break;
					case "field":
						$filterStr = sprintf("$delimiter%s = %s", $field, $value); //field compare
				}
			}
			
			return $filterStr;
		}
		
		private function TestValidation($field, $value){
			return $validation->isValid();
			//$this->errorMsg = $validation->Message;
		}
	}
}

?>