<?
/*
Project: AWD Framework
Created By Adam Duncan
Date: 09/03/2015

iPageLoad
this is an interface that is called in the Layouts page if setup in the config settings and another used class extends it.
*/
namespace AWD\Interfaces{
	interface iPageLoad{
		
		public function GetPage($page,$type);
		
	}
}
?>