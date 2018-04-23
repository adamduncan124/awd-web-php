<?
/*
Project: AWD Framework
File: PageEvents (class.pageevents.php
Created By Adam Duncan
Date: 09/03/2015

Note: checks always in each event folder
      check for page name in root
      check for type and page name in type_only folder
*/
namespace AWD{
	class PageEvents{
		private $page;
		private $type;
		
		public function __construct($page, $type){
			$this->page = $page;
			$this->type = $type;
		}
		
		public function Handle($eventType){
			//checks for folder
			if(file_exists(AWD_PAGEEVENTS_PATH . "/" . $eventType . "/type_only/" . $this->type . "_pages/" . $this->page . ".php"))
				include(AWD_PAGEEVENTS_PATH . "/" . $eventType . "/type_only/" . $this->type . "_pages/" . $this->page . ".php");
			elseif(file_exists(AWD_PAGEEVENTS_PATH . "/" . $eventType . "/" . $this->page . ".php"))
				include(AWD_PAGEEVENTS_PATH . "/" . $eventType . "/" . $this->page . ".php");
			
			
			if(file_exists(AWD_PAGEEVENTS_PATH . "/" . $eventType . "/type_only/" . $this->type . "_pages/always.php"))
				include(AWD_PAGEEVENTS_PATH . "/" . $eventType . "/type_only/" . $this->type . "_pages/always.php");
			elseif(file_exists(AWD_PAGEEVENTS_PATH . "/" . $eventType . "/always.php"))
				include(AWD_PAGEEVENTS_PATH . "/" . $eventType . "/always.php");
		}
	}
}
