<?
/*
Project: AWD Framework
Created By Adam Duncan
Date: 04/18/2018

ConnectionException
This class extends the awd base exception for the exceptinos thrown in the connection section
*/
namespace AWD\Exceptions{
	class ConnectionException extends BaseException{		
		const INVALID_CHILD_CODE = 3004;
		const INVALID_CLASS_CODE = 3003;
		const MISSING_INTERFACE_CODE = 3002;
		const CLASS_NOT_EXISTS_CODE = 3001;
		const GENERIC_CODE = 3000;
		
		protected $code = self::GENERIC_CODE;
	
		protected function SetMessageByCode($code){
			switch($code){
				case self::INVALID_CHILD_CODE:
					return "Connection: class does not extend Table or Row class";
				case self::INVALID_CLASS_CODE:
					return "Connection: Not a valid class name to inject connection.";
				case self::MISSING_INTERFACE_CODE:
					return "Connection: selected conn object does not implement iConnection";
				case self::CLASS_NOT_EXISTS_CODE:
					return "Connection: Class name doesn't exist";
			}		
		}
		
		public static function ThrowInvalidChild(){
			throw new self(self::INVALID_CHILD_CODE);
		}
		
		public static function ThrowInvalidClass(){
			throw new self(self::INVALID_CLASS_CODE);
		}

		public static function ThrowMissingInterface(){
			throw new self(self::MISSING_INTERFACE_CODE);
		}
		
		public static function ThrowClassNotExists(){
			throw new self(self::CLASS_NOT_EXISTS_CODE);
		}
	}
}
?>