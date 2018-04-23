<?
/*
Project: AWD Framework
File: Data\Api (class.api.php)
Created By Adam Duncan
Date: 04/16/2018
Desc: this is the main Api class.  it uses items from the object class, 
and a class that inherits this must have an api interface to use that requested action
*/
namespace AWD{
	abstract class Api extends Object implements \AWD\Interfaces\iApi{
		protected $apiAlterList;
		
		public $request_type;		
		public page;
		public size;
		public filters;
		public sorting;
		public total;
		public results;
		
		public function ApiProcessRequest(){
			if($this instanceof \AWD\Interfaces\Api\iRequestSingle)
				ApiProcessSingleRequest();
			elseif($this instanceof \AWD\Interfaces\Api\iRequestList)
				ApiProcessListRequest();
			
			\AWD\Exceptions\ApiException::ThrowInvalidRequestValidation();
		}
		
		public function ApiSelect(){
			//by default, does nothing
		}
		
		abstract public function ApiColumns();
		
		abstract public function ApiSave();
		
		abstract public function ApiDelete();

		public function ReturnApiJSON(){			
			return $this->returnJSON();
		}
		
		public function ReturnApiXML(){
			return $this->returnXML();
		}
		
		protected function ApiProcessSingleRequest(){
			$this->ApiProcessMethod();
			
		}
		
		protected function ApiProcessListRequest(){
			$this->ApiProcessMethod();
			
		}
		
		//NOTE: this requires application/json on posts
		private function ApiProcessMethod(){
			global $AWD;
			
			$this->request_type = $AWD['request_type']; //get from main request
			
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				if(!strtolower($_SERVER['HTTP_CONTENT_TYPE']) == "application/json")
					\AWD\Exceptions\ApiException::ThrowRequestRequireJson();
				
				$json_str = file_get_contents("php://input");
				
				if (strlen($json_str) > 0){
					$json = json_decode($json_str, true);					
				}
				
				if(isset($json) && json_last_error() != JSON_ERROR_NONE)
					$request_json_data = $json;
				else
					\AWD\Exceptions\ApiException::ThrowRequestRequireJson();
				
				foreach($this->request_json_data as $key => $value){
					if(!isset($this->apiAlterList))
						$this->apiAlterList = [];
					
					$this[$key] = $value;
					$this->apiAlterList[] = $key;
				}
			}
		}
	}
}