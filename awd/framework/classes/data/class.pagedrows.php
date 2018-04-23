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
	class PagedRows extends DataObject implements \AWD\Api\iResponseMulti{
		protected $tableFilters;
		
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
			$this->SetTablePageAndSize($this->page, $this-size);
			$this->WorkWithApiFilter();
			$this->WorkWithApiSorting();
		}
		
		public function ApiSave(){
			//do nothing on this
		}
		
		public function ApiDelete(){
			//do nothing on this
		}
		//--End API Code
		
		public function SetTableFilters($obj){
			$this->tableFilters = $obj;
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
		
		//NOTE: just skip if there are issues
		private function WorkWithApiFilter(){
			//1st make sure array
			if(!is_array($this->filters)
				return;
			
			if(!isset($this->tableFilters))
				$this->tableFilters = new TableFilters();
			
			foreach($this->filters as $key => $value){
				//try to add to tableFilter
				$this->tableFilters->AddFilter($value);
			}
		}
		
		//NOTE: just skip if there are issues
		private function WorkWithApiSorting(){
			//1st make sure array
			if(!is_array($this->sorting)
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