<?
/*
Project: AWD Framework
Created By Adam Duncan
Date: 04/18/2018

LayoutException
This class extends the awd base exception for the exceptions thrown in the layout section
*/
namespace AWD\Exceptions{
	class LayoutException extends BaseException{		
		const MISSING_CUSTOMPAGE_CODE = 1001;
		const GENERIC_CODE = 1000;
		
		protected $code = self::GENERIC_CODE;
	
		protected function SetMessageByCode($code){
			return "Layout Excpetion";
		}
		
		public static function ThrowCustomPage($message){
			throw new self(self::MISSING_CUSTOMPAGE_CODE, $message);
		}
	}
}
?>