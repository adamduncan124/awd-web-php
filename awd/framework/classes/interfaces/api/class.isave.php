<?
/*
Project: AWD Framework
Created By Adam Duncan
Date: 04/16/2018

Api\iSave
this is an interface for the api object.  this allows for a save request
*/
namespace AWD\Interfaces\Api{
	interface iSave extends iPublic{
		
		public function ApiSave();
		
		public function ApiSaveAccess();
		
	}
}
?>