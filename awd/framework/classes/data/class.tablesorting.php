<?php
/*
Project: AWD Framework
Created By Adam Duncan
Date: 04/18/2018
data/class.tablesorting.php
-------------------------
Purpose
----------------
this creates the sort clause minus the "ORDER BY "
-------------------------
*/
namespace AWD\Data{
	class TableSorting{
		private aSorting;
		
		public function __construct(){
			$this->aSorting = array();
		}
		
		public function AddSort($obj){
			if(!is_array($obj)
				|| !array_key_exists('field', $obj)
				|| !array_key_exists('is_asc', $obj)
				|| !array_key_exists('table', $obj)
			)
				return;
				
				$this->AddSort($obj['field'], $obj['is_asc'], $obj['table']);
		}
		
		public function AddSort($field, $is_asc, $table=null){
			$sort = array();
			
			$sort['field'] = $field;
			$sort['is_asc'] = $is_asc;
			$sort['table'] = $table;
		}
		
		public function HasSorting(){
			return count($this->aSorting) > 0;
		}
		
		public function ReturnSortingString(){
			$sql = "";
			
			for($i=0; $i<count($this->aSorting); $i++){
				if($sql!="")
					$sql .= ", ";
				
				$sql .= isset($this->aSorting['table']) ? "`" . $this->aSorting['table'] . "`." : "";
				$sql .= $this->aSorting['field'] . $this->aSorting['is_asc'] ? " ASC" : " DESC";
			}
			
			return $sql;
		}
	}
}	
