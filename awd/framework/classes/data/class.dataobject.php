<?php
/*
Project: AWD Framework
Created By Adam Duncan
Date: 04/18/2018
data/class.dataobject.php
-------------------------
Purpose
----------------
this class has all the common methods used in all the data layer classes that use the table object as the "dataTable" property like row and pagedrows
-------------------------
*/

namespace AWD\Data{
	abstract class DataObject extends \AWD\Api implements \AWD\Interfaces\Data\iDataObject{
		protected $defaultTableFilters;
		protected $tableFilters;
		protected $tableName;		
		protected $dataTable;
		protected $dbName = null;

		private $_apiColumns;
		
		public function __construct() {
			$get_arguments = func_get_args();
			$number_of_arguments = func_num_args();

//			if($number_of_arguments<=0){ $number_of_arguments=1; } (allow zero)

			if (method_exists($this, $method_name = '__construct'.$number_of_arguments)) {
				call_user_func_array(array($this, $method_name), $get_arguments);
			}
		}
		
		public function __construct0(){
			$this->SetTable($this->dbName);
		}
		
		public function __construct1($db_name=null){
			if(isset($this->tableName) && isset($db_name)){
				$this->SetTable($db_name);				
			}
		}		
		
		public function __construct2($dataTable, $obj){
			$this->dataTable = $dataTable;
			$this->SetProperties($obj);
		}
		
		public function __construct3($db_name, $tableName, $obj){
			$this->tableName = $tableName;
			$this->SetTable($db_name);
			$this->Load($obj);
		}
		
		public function __construct4($db, $sqlBuilder, $tableName, $obj){
			$this->tableName = $tableName;
			$this->SetTable($db, $sqlBuilder);
			$this->Load($obj);
		}	
		
		public function __construct5($db, $sqlBuilder, $tableName, $obj, $postPrefix){
			$this->tableName = $tableName;
			$this->SetTable($db, $sqlBuilder, $postPrefix);
			$this->Load($obj);
		}	
		
		//AWD NOTE: both are set here, but default will never change unless this method is called again. table filter will change as the objects go up
		public function SetDefaultFilters($obj){
			$this->defaultTableFilters = $obj;
			$this->tableFilters = $obj;
		}
		
		public function SetJoins($obj){
			$this->dataTable->sqlBuilder->tableJoins = $obj;
		}
		
		private function SetTable($db, $sqlBuilder=null, $postPrefix=null){
			if($db instanceof \AWD\Interfaces\Data\iConnection){
				$this->dataTable = new Table($db, $sqlBuilder, $postPrefix);	
			}else{
				awd_conn_injectconstruct($this->dataTable, $this->tableName, null, $db);
			}			
		}
		
		public function SetSelectList($obj){
			$this->dataTable->sqlBuilder->selectList = $obj;
		}
		
		public function ApiColumns(){
			//do nothing.  the ReturnApiXML and the ReturnApiJSON handles it object does it all
		}
		
		abstract public function Load($obj);		
		
		public function ApiSave(){
			//does nothing
		}
		
		public function ApiDelete(){
			//does nothing
		}
		
		public function ReturnApiJSON(){			
			return $this->returnJSON(
				$this->request_type == 'columns' ? 
				$this->apiColumns :
				null
			);
		}
		
		public function ReturnApiXML(){
			return $this->returnXML(
				$this->request_type == 'columns' ? 
				$this->apiColumns :
				null
			);
		}
		
		public function __get($name){						
			switch(strtolower($name)){
				case "columns":
					$this->IsTableSet();
					return $this->dataTable->columns;
				case "db":
					$this->IsTableSet();
					return $this->dataTable->db;
				case "apiColumns":
					if(!isset($_apiColumns))
						$this->_apiColumns = $this->ReturnApiColumns();
					
					return $this->_apiColumns;
			}
			
			return parent::__get($name);
		}	

		private function IsTableSet(){
			if(!$this->dataTable instanceof Table)
				\AWD\Exceptions\DataException::ThrowMissingTableObj();
		}
		
		//TODO: work on types to match angular js types right now all is 1 or varchar
		protected function ReturnApiColumns(){
			$api_columns = [];
			
			//right now all are sortable, show, and the order stays the same
			for($i=0; $i < count($this->columns); $i++){
				$api_columns[] = array(
					"name" => $this->columns[$i]['Field'],
					"columnType" => $this->types->EnumType($this->columns[$i]['Type']),
					"order" => $i,
					"display" => ucfirst($this->columns[$i]['Field'])
				);
			}
			
			return $api_columns;
		}
		
		protected static function FormatDisplay($field){
			return ucwords(str_replace("_", " ", str_replace("-", " ", $field)));
		}
	}
}

?>