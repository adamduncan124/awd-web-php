<?
/*
Project: AWD Framework
File: Layouts (class.layouts.php)
Created By Adam Duncan
Date: 09/03/2015
*/
namespace AWD{
	class Layout{
		private $page;
		private $type;
		private $apiRequestType;
		private $pageEvents;
		
		public function __construct($page, $type, $request_type = null) {
			global $AWD;
			
			//load page, type and events
			$this->page = $page; 
			$this->type = $type;
			$this->apiRequestType = $request_type;
			
			$this->pageEvents = new PageEvents($this->page, $this->type);
		}
	
		public function LoadFunctions(){
			//if the layout has functions, load it.			
			if(file_exists(AWD_LAYOUTS_PATH . "/functions.php"))
				require_once(AWD_LAYOUTS_PATH . "/functions.php");
		}
	
		public function GetHeader(){
			if($this->type!=="html")
				return;
			
			if(file_exists(AWD_LAYOUTS_PATH . "/header.php"))
				require_once(AWD_LAYOUTS_PATH . "/header.php");
		}
		
		public function GetFooter(){
			if($this->type!=="html")
				return;
			
			if(file_exists(AWD_LAYOUTS_PATH . "/footer.php"))
				require_once(AWD_LAYOUTS_PATH . "/footer.php");
		}
		
		public function GetPage(){
			switch(strtolower($this->page)){
				case "invalid":
					echo Messages::InValid($this->type);
					break;
				default: //loads page
					if(file_exists(AWD_PAGES_PATH . "/type_only/" . $this->type . "_pages/" . $this->page . ".php")){
						include_once(AWD_PAGES_PATH . "/type_only/" . $this->type . "_pages/" . $this->page . ".php");
					}elseif(file_exists(AWD_PAGES_PATH . "/" . $this->page . ".php")){
						//note: this can only be done by html.  the type only field will be for everything else.  throw exception
						if($this->type!=="html")
							\AWD\Exceptions\LayoutException::ThrowGeneric("Layout: This page can only be loaded in a browser.  it is not an api page.");
						
						include_once(AWD_PAGES_PATH . "/" . $this->page . ".php");
					}else{
						if(awd_is_apitype($this->type))
							awd_write_apirequest($this->type, $this->page, $this->apiRequestType);
						elseif(!awd_is_custompageload($this->page, $this->type))
							echo Messages::InValid($this->type);
					}
					break;
			}
		}
		
		/* ---- STATIC FUNCTIONS ---- */
		public static function Render($page, $type, $request_type = null){
			$s = new self($page, $type, $request_type);
			
			$s->LoadFunctions();
			
			$s->pageEvents->Handle("preload");
			$s->GetHeader();
			
			$s->pageEvents->Handle("load");
			//catch page specific errors for formating
			try
			{
				$s->GetPage();
			}
			catch(Exception $e)
			{
				Messages::Error($s->type, $e);
			}
			
			$s->GetFooter();
			$s->pageEvents->Handle("postload");
		}
	}
}
?>
