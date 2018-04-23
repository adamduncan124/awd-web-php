<?
/*
Project: AWD Framework
Created By Adam Duncan
Date: 04/18/2018

DataException
This class extends the awd base exception for the exceptions thrown in the data section
*/
namespace AWD\Exceptions{
	class DataException extends BaseException{		
		const MISSING_SQL_BUILDER_CODE = 2004;
		const MISSING_TABLE_OBJ_CODE = 2003;
		const MISSING_CONNECTION_CODE = 2002;
		const MISSING_TABLE_CODE = 2001;
		const GENERIC_CODE = 2000;
		
		protected $code = self::GENERIC_CODE;
	
		protected function SetMessageByCode($code){
			switch($code){
				case self::MISSING_SQL_BUILDER_CODE:
					return "Data: This class doesn't have a live Sql Builder class present";
				case self::MISSING_TABLE_OBJ_CODE:
					return "Data: This class requires a Table class";
				case self::MISSING_CONNECTION_CODE:
					return "Data: There is not a live Connection class present";
				case self::MISSING_TABLE_CODE:
					return "Data: No Table Set";
			}	
		}
		
		public static function ThrowMissingSqlBuilder(){
			throw new self(self::MISSING_SQL_BUILDER_CODE);
		}
		
		public static function ThrowMissingTableObj(){
			throw new self(self::MISSING_TABLE_OBJ_CODE);
		}
		
		public static function ThrowMissingConnection(){
			throw new self(self::MISSING_CONNECTION_CODE);
		}
		
		public static function ThrowMissingTable(){
			throw new self(self::MISSING_TABLE_CODE);
		}
	}
}
?>