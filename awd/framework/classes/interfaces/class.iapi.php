<?
/*
Project: AWD Framework
Created By Adam Duncan
Date: 04/18/2018

iApi
this is an interface that is the main wrapper for all api calls
*/
namespace AWD\Interfaces{
	interface iApi{
		
		public function ApiColumns();
		
		public function ApiSelect();
		
		public function ApiSave();
		
		public function ApiDelete();
		
		public function ReturnApiJSON();
		
		public function ReturnApiXML();
		
	}
}
?>