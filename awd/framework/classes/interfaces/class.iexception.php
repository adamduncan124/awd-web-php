<?
/*
Project: AWD Framework
Created By Adam Duncan
Date: 04/18/2018

iException
this is an interface that all exceptions must follow
*/
namespace AWD\Interfaces{
	interface iException{
		
		//protected $code;                 		      // User-defined exception code
		//protected $file;                              // Source filename of exception
		//protected $line;                              // Source line of exception
		
		public function getMessage();                 // Exception message 
		public function getCode();                    // User-defined Exception code
		public function getFile();                    // Source filename
		public function getLine();                    // Source line
		public function getTrace();                   // An array of the backtrace()
		public function getTraceAsString();           // Formated string of trace
		
		public function __toString();                 // formated string for display
		public function __construct($message = null, $code = 0);
		
	}
}
?>