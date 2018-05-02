<?php
/*
Project: AWD Framework
Created By Adam Duncan
Date: 04/18/2018
data/class.pagedrows.php
-------------------------
Purpose
----------------
allows user to get paged row objects from the table object
-------------------------
*/
namespace AWD\Data{
	class PagedRows extends DataObject{				
		
		//--DataObject Code
		public function Load($obj){
			$this->data['results'] = $obj;
		}
		//--End DataObject Code
		
		//--iResponse objects (all in Api class)
		/*public page;
		public size;
		public filters;
		public sorting;
		public total;
		public results;*/
		//--End iResponse objects
		
		//--API Code
		public function ApiSelect(){
			$this->SetDefaults();
			$this->WorkWithApiFilter();
			$this->WorkWithApiSorting();
			$this->Load($this->LoadRows(false)); //set others before
		}
		
		public function ApiSave(){
			//do nothing on this
		}
		
		public function ApiDelete(){
			//do nothing on this
		}
		//--End API Code
		
		protected function LoadRows($setDefaults = true){
			if($setDefaults)
				$this->SetDefaults();
			
			return $this->dataTable->LoadData($this->tableFilters);
		}
		
		protected function SetRowClassName($className){
			$this->dataTable->rowClassName = $className;
		}		
		
		public function SetTableSorting($obj){
			$this->IsTableSet();
			$this->dataTable->orderBy = $obj;
		}
		
		public function SetTablePageAndSize($page, $size){
			$this->IsTableSet();
			$this->dataTable->offset = ($page - 1);
			$this->dataTable->limit = $size;
		}
		
		//AWD: used because i kept getting confused which method to call, and i'm lazy today :)
		protected function SetChildDefaults(){
			//does nothing right now.
		}
		
		protected function SetDefaults(){
			$this->SetChildDefaults();
			$this->SetTablePageAndSize($this->page, $this-size);
			
			//reset filters
			$this->tableFilters = $this->defaultTableFilters;
			
			if(!isset($this->tableFilters))
				$this->tableFilters = new TableFilters();
		}
		
		//NOTE: just skip if there are issues
		private function WorkWithApiFilter(){
			//1st make sure array
			if(!is_array($this->filters))
				return;			
			
			foreach($this->filters as $key => $value){
				//try to add to tableFilter
				$this->tableFilters->AddFilter($value);
			}
		}
		
		//NOTE: just skip if there are issues
		private function WorkWithApiSorting(){
			//1st make sure array
			if(!is_array($this->sorting))
				return;
			
			$orderBy = new TableSorting();			
			foreach($this->sorting as $key => $value){
				//try to add to tableFilter
				$orderBy->AddSort($value);
			}
			
			$this->SetTableSorting($orderBy);
		}
	}
}