<?
/*
Project: AWD Framework
Created By Adam Duncan
Date: 04/16/2018

Api\iResponseError
this is an interface for the api object.  all api errors must follow this structure
*/
namespace AWD\Interfaces\Api{
	interface iResponseError{
		
		public message;
		public code;
		
	}
}
?>