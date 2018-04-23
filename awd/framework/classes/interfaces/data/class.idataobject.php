<?
/*
Project: AWD Framework
Created By Adam Duncan
Date: 04/18/2018

iDataObject
this is an interface that forces all data objects to have the correct properties
*/
namespace AWD\Interfaces{
	interface iDataObject{
		
		protected $dataTable;
		protected $postPrefix;
		public function SetSelectList($obj);
		protected function Load($obj);
		
	}
}
?>