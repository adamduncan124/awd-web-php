<?php
/*
Project: AWD Framework
Created By Adam Duncan
Date: 03/13/2017
data/class.row.php
-------------------------
Purpose
----------------
allows user to alter or use a single row of data from a table
-------------------------
Change Log
-------------------------
04/16/2018 awd - moved Setproperties to object, added db, cleaned up duplicate, and commented out control part
04/17/2018 awd - added Api to this class
-------------------------
*/
namespace AWD\Data{
	class Row extends DataObject{
		//private $dataControls;
		
		//--DataObject Code
		protected function Load($obj){
			$this->LoadRow($obj);
		}
		//--End DataObject Code
		
		protected function LoadRow($obj){
			if(!isset($obj))
				return;
			
			if($row=$this->dataTable->LoadData($obj))
				$this->SetProperties($row);
			else
				$this->columns;
		}
		
		protected function LoadByFilteredColumns(){
			return $this->LoadRow($this->data);
		}		
		
		public function SaveFromPost(){
			$this->LoadFromPost();
			return $this->SaveRow();
		}
		
		public function LoadFromPost(){
			$this->SetProperties($this->dataTable->PostedDataRow());			
		}
		
		public function SaveRow($alterList=null){
			if(isset($alterList))
				$this->dataTable->alterList = $alterList;
			
			if($this->dataTable->SaveRow($this->data)){
				$this->SetProperties($this->dataTable->lastAlteredRow);				
				return true;
			}else{
				$this->errorMsg = $this->dataTable->errorMsg;
				return false;				
			}	
		}
		//--API Code
		public function ApiSave(){
			//$this->SaveFromPost();
			if(!$this->SaveRow($this->apiAlterList))
				\AWD\Exceptions\ApiException::ThrowGeneric($this->dataTable->errorMsg);
		}
		
		public function ApiDelete(){
			//$this->LoadFromPost();
			if($this->Isset("id")){
				if(!$this->dataTable->DeleteSingle($this->id))
					\AWD\Exceptions\ApiException::ThrowGeneric($this->dataTable->errorMsg);
			}else{
				\AWD\Exceptions\ApiException::ThrowGeneric("Missing the id to delete");
			}
				
		}
		//--End API Code
		/*
		protected function SetControlsFromTable(){
			if(!isset($this->dataTable))
				$this->SetTable($this->tableName, $this->postPrefix);
			
			//cache for reuse
			if(!isset($this->dataControls))
				$this->dataControls = $this->dataTable->PostControls($this->data);
		}
		
		public function GetAllControls(){
			$this->SetControlsFromTable();
			return $this->dataControls;
		}
		
		public function GetControl($key){
			$this->SetControlsFromTable();
			return $this->dataControls[$key];
		}
		*/
	}
}
?>