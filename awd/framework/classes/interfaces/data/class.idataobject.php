<?
/*
Project: AWD Framework
Created By Adam Duncan
Date: 04/18/2018

iDataObject
this is an interface that forces all data objects to have the correct properties
*/
namespace AWD\Interfaces\Data{
	interface iDataObject{
		
		//protected $dataTable;
		//protected $postPrefix;
		function SetSelectList($obj);
		function Load($obj);
		
	}
}
?>