<?
/*
Project: AWD Framework
Created By Adam Duncan
Date: 04/16/2018

Api\iResponseList
this is an interface for the api object.  this is for responses to return results with multiple lines and filtered by page
*/
namespace AWD\Interfaces\Api{
	interface iResponseList extends iRequestList{
		
		public results;
		public total;
		
	}
}
?>