<?php
/*
Project: AWD Framework
Created By Adam Duncan
Date: 03/13/2017
data/class.table.php
-------------------------
Purpose
----------------
allows user to alter or select data from a specific table in a database
-------------------------
Change Log
-------------------------
04/16/2018 awd - added connection iconnection test, commented out post data, and moved items to protected (need to client up post data)
04/16/2018 awd - refactored to test table joins object and filters
04/20/2018 awd - moved all sql buidling to an interface to allow easy changes letter if it is moved to other than mysql
*/
namespace AWD\Data{

	class Table{
		private $_columns;
		private $_types;
		private $db;
		private $sqlBuilder;	
		private $postPrefix;
		
		public $lastAlteredRow;		
		public $debugSql;
		
		public function __get($name){	
			
			switch(strtolower($name)){
				case "types":
					if(!isset($this->_types))
						$this->_types = new Types();
					
					return $this->_types;
				case "columns":
					if(!isset($this->_columns))
						$this->_columns = $this->db->LoadTableColumns($this->tableName, true);	
					
					return $this->_columns;
			}
		}
		
		public function __construct($db, $sqlBuilder, $table, $postPrefix=null){
			$this->_db = $db;
			$this->_sqlBuilder = $sqlBuilder 
			$this->sqlBuilder->tableName = $table;
			
			if(isset($postPrefix))
				$this->postPrefix = $postPrefix;
			
			$this->ValidateRequiredProperties();
		}
		
		private function ValidateRequiredProperties(){
			
			if(!isset($this->_db))
				\AWD\Exceptions\DataException::ThrowMissingConnection();
			
			if(!$this->_db instanceof \AWD\Interfaces\Data\iConnection)	
				\AWD\Exceptions\DataException::ThrowMissingConnection();
		
			if(!isset($this->sqlBuilder))
				\AWD\Exceptions\DataException::ThrowMissingSqlBuilder();
					
			if(!$this->sqlBuilder instanceof \AWD\Interfaces\Data\iSqlBuilder)		
				\AWD\Exceptions\DataException::ThrowMissingSqlBuilder();
			
			if(!isset($this->sqlBuilder->tableName) || empty($this->sqlBuilder->tableName))
					\AWD\Exceptions\DataException::ThrowMissingTable();

		}		
		
		/*
		public function PostedDataRow(){
			$dataRow = $this->ColumnNameList(true);
			return self::BuildDataRowFromPost($dataRow, $this->postPrefix);
		}
		
		
		public function PostControls($dataRow=null){
			if(!isset($dataRow))
				$dataRow = $this->ColumnNameList(true);
			
			return self::BuildPostControls($this->columns, $dataRow, $this->postPrefix);
		}*/
								   
		public function ColumnNameList($emptyRow=false){
			$colNames = array();
			
			foreach($this->columns as $key => $value){
				if($emptyRow)
					$colNames[$value['Field']] = null;
				else
					$colNames[$key] = $value['Field'];
			}
			
			return $colNames;
		}		
		
		public function SaveRow($DataRow){
			$insertDone = false;

			$this->lastAlteredRow = null; //reset
			$validation = new Validation($this->columns, $this->types, 
			true);
			
			if(!$validation->isValid($DataRow)){
				$this->errorMsg = $validation->Message;
				return false;
			}				

			$id = (int)$DataRow['id'];
			$aAlteredDataRow = self::BuildStatementRow($this->columns, $DataRow);					

			//build sql			
			if($id>0){
				$sql = $this->sqlBuilder->UpdateSql($aAlteredDataRow, $id);
				$this->lastAlteredRow = $DataRow;
				$this->lastAlteredRow['id'] = $id;
			}else{
				$sql = $this->sqlBuilder->InsertSql($aAlteredDataRow);
				$this->lastAlteredRow = $DataRow;
				$insertDone = true;
			}
			
			if($this->debugSql)
				echo $sql;
			
			if(!$this->db->ExecuteSql($sql)){
				$this->errorMsg = "Error Saving Data Row";
				return false;
			}
			
			//set last altered if inserted
			if($insertDone)				
				$this->lastAlteredRow['id'] = $this->db->ReturnIdentity();
			
			return true;
		}
		
		public function DeleteSingle($id){		
			$sql = $this->sqlBuilder->DeleteSql();
			
			if(!$this->db->ExecuteSql($sql)){
				$this->errorMsg = "Error Saving Data Row";
				return false;
			}
		}
		
		public function LoadSingle($obj){
			$DataRow = new Row();
			
			if($row=$this->LoadData($obj)){
				$DataRow->SetTable($this->tableName, $this->postPrefix);
				$DataRow->SetProperties($row);	
			}
			
			return $DataRow;
		}
		
		public function LoadData($obj, $onlyOneRow=true){
			$sql = $this->sqlBuilder->SelectSql($obj);
			
			if($this->debugSql)
				echo $sql;
			
			if($this->db->SetResults($sql)){	
				if($onlyOneRow){
					if($row = $this->db->FetchRow()){
						return $row;
					}
				}else{
					$results = array();		
					$i = 0;
				
					while($row = $this->db->FetchRow()){
						$results[$i] = $row;
						$i++;
					}
				
					return $results;
				}
			}
			
			return false;
		}
		
		public function LoadList($obj=null){		
			$sql = $this->sqlBuilder->SelectSql($obj);
			
			if($this->debugSql)
				echo $sql;
			
			if($this->db->SetResults($sql)){
				$results = array();		
				$i = 0;
				
				while($row = $this->db->FetchRow()){
					$DataRow = new Row();
					$DataRow->SetTable($this->tableName, $this->postPrefix);
					$DataRow->SetProperties($row);
					
					$results[$i] = $DataRow;
					
					$i++;
				}
				
				return $results;
			}
			
			return null;
		}
		
		public static function ReturnFieldSettings($columnsSettings, $key){				  
			if(($index = array_search($key, array_column($columnsSettings, 'Field'))) !== false)
				return $columnsSettings[$index];

			return null;
		}
		
		public static function BuildStatementRow($columnsSettings, $dataRow){
			//remove id
			$aAlteredDataRow = $dataRow;
			
			$i = array_search('id', $aAlteredDataRow);
			unset($aAlteredDataRow[$i]);
			
			//loop and format values
			foreach($aAlteredDataRow as $key => $value){
				$fieldSetting = self::ReturnFieldSettings($columnsSettings, $key);
				
				$aAlteredDataRow[$key] = $types->ReturnFormatedValue($fieldSetting['Type'], $value);
			}
					
			return $aAlteredDataRow;
		}
											   
		public static function BuildDataRowFromPost($compareRow, $prefixCompare=null){
			$aAlteredDataRow = array();
			
			if(isset($prefixCompare))
				$prefixCompare = "";
			
			foreach($compareRow as $key => $value){
				$value = get_request_var($prefixCompare . $key);
				$aAlteredDataRow[$key] = $value;
			}
			
			return $aAlteredDataRow;
		}
		
/*		
		public static function BuildPostControls($columnsSettings, $dataRow, $prefixCompare=null){
			$aControls = array();
			
			foreach($dataRow as $key => $value){

				$fieldSetting = self::ReturnFieldSettings($columnsSettings, $key);
				$control = new \AWD\HTML\Control($key, new Types($fieldSetting['Type']), $prefixCompare);
				
				if((isset($value)) && ($value != ""))
					$control->value = $value;
				
				$aControls[$key] = $control;
			}

			return $aControls;
		}*/
	}
}	
?>