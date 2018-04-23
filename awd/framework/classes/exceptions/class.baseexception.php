<?
/*
Project: AWD Framework
Created By Adam Duncan
Date: 04/18/2018

BaseException
This class is the awd base exception all specific types inherit
*/
namespace AWD\Exceptions{
	class BaseException extends \Exception implements \AWD\Interfaces\iException{
		protected $message = 'Awd Exception';    	  // Exception message
		protected $code = 0;                          // User-defined exception code
		protected $file;                              // Source filename of exception
		protected $line;                              // Source line of exception
		
		public function __construct($code = null, $message = null)
		{
			if (!$message) {
				$message = $this->SetMessageByCode($code);
			}
			
			if(isset($code))
				$this->code = $code;
			
			parent::__construct($message, $this->code);
		}
		
		public function __toString()
		{
			return get_class($this) . " '{$this->message}' in {$this->file}({$this->line})\n"
									. "{$this->getTraceAsString()}";
		}
		
		protected function SetMessageByCode($code){
			return "AWD Uknown: " . get_class($this);
		}
		
		public static function ThrowGeneric($message){
			throw new self(null, $message);
		}
	}
}
?>