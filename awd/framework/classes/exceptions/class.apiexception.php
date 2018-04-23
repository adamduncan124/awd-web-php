<?
/*
Project: AWD Framework
Created By Adam Duncan
Date: 04/18/2018

ApiException
This class extends the awd base exception for the exceptinos thrown in the api section
*/
namespace AWD\Exceptions{
	class ApiException extends BaseException{		
		const REQUEST_REQUIRE_JSON_CODE = 5006;
		const INVALID_REQUEST_VALIDATION_CODE = 5005;
		const MISSING_RETURN_TYPE_CODE = 5004;
		const MISSING_API_CLASS_CODE = 5003;
		const RESTRICTED_REQUEST_CODE = 5002;
		const INVALID_REQUEST_CODE = 5001;
		const GENERIC_CODE = 5000;
		
		protected $code = self::GENERIC_CODE;
	
		protected function SetMessageByCode($code){
			switch($code){
				case self::REQUEST_REQUIRE_JSON_CODE:
					return "Api: this request requires the content to be json.";
				case self::INVALID_REQUEST_VALIDATION_CODE:
					return "Api: the data sent in the request isn't valid.  reformat request data, and try again";
				case self::MISSING_RETURN_TYPE_CODE:
					return "Api: there was an error in your requested return type";
				case self::MISSING_API_CLASS_CODE;
					return "Api: the request class doesn't have the api class information.  we can not complete your request at this time";
				case self::RESTRICTED_REQUEST_CODE:
					return "Api: this request type isn't allowed for the requested class";
				case self::INVALID_REQUEST_CODE:
					return "Api: request class does not exist in our system";
			}			
		}
		
		public static function ThrowRequestRequireJson(){
			throw new self(self::REQUEST_REQUIRE_JSON_CODE);
		}
		
		public static function ThrowInvalidRequestValidation(){
			throw new self(self::INVALID_REQUEST_VALIDATION_CODE);
		}
		
		public static function ThrowMissingReturnType(){
			throw new self(self::MISSING_RETURN_TYPE_CODE);
		}
		
		public static function ThrowMissingApiClass(){
			throw new self(self::MISSING_API_CLASS_CODE);
		}
		
		public static function ThrowRestrictedRequest(){
			throw new self(self::RESTRICTED_REQUEST_CODE);
		}
		
		public static function ThrowInvalidRequest(){
			throw new self(self::INVALID_REQUEST_CODE);
		}
	}
}
?>