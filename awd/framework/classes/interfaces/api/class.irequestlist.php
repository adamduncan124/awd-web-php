<?
/*
Project: AWD Framework
Created By Adam Duncan
Date: 04/16/2018

Api\iRequestList
this is an interface for the api object.  this is for requests to return results with multiple lines and filtered by page
*/
namespace AWD\Interfaces\Api{
	interface iRequestList extends iRequestSingle{
		
		public page;
		public size;
		public filters;
		public sorting;
		
	}
}
?>