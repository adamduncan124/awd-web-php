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
	class TableJoin{
		private $tableName;
		private $joinType;
		public $tableFilters;
		public $selectList;
		public $statusFilter;
		
		public function __construct($tableName, $joinType="inner", $tableFilters=null){
			$this->tableName = $tableName;
			$this->joinType = $joinType;
			$this->statusFilter = "`$tableName`.status = '1'";
			$this->tableFilters = isset($tableFilters) ? $tableFilters : new \AWD\Data\TableFilters();			
		}
		
		public function ReturnJoinString(){
			return $this->joinType . " join `" . $this->tableName . "` on " . $this->statusFilter . $this->tableFilters->ReturnFilterString();
		}
		
		//returns empty string if nothing
		public function ReturnSelectStatement(){
			$sql = "";
			
			if(isset($this->selectList)){
				if(is_array($this->selectList)){	
					foreach($this->selectList as $key => $item){
						$sql .= ",`" . $this->tableName ."`." . $item;
					}
				}else{
					$sql .= ",`" . $this->tableName ."`." . $this->selectList;	
				}
			}
			
			return $sql;
		}
	}
}

?>