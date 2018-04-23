<?

//Requirements for fieldSettings Array (currently nothing tests if these keys exist)
//--Field
//--Type
//--Null
//--Key
//--Default
//--Extra

//Requrirements for row Array
//--key (Field)
//--value

//Desc: Option call isValid(row) for collection isValid(field, value) for individual

namespace AWD\Data{
	class Validation{
		private $rowSettings;
		private $types;
		
		public $message;
		public $isForSaving;
			
		public function __construct($rowSettings, $types=null, $is_for_saving=false){
			$this->rowSettings = $rowSettings;			
			$this->types = (isset($types)) ? $types : new Types();
			$this->isForSaving = false;
		}
		
		public function isValid($row){
			$bolReturn = true;
			
			foreach($this->row as $key => $value){
				$bolReturn = $this->TestField($key, $value) ? $bolReturn : false;
			}
			
			return $bolReturn;
		}
		
		public function isValid($field, $value){
			return $bolReturn = $this->TestField($key, $value);
		}
		
		
		private function AddToMessage($str){
			if(!isset($this->Message))
				$this->Message = "";
			else
				$this->Message = ", ";
			
			$this->Message .= $str;
		}
		
		private function TestField($key, $value){
			if(!isset($this->rowSettings))
				return true; //just skip
			
			$fieldSetting = Table::ReturnFieldSettings($this->rowSettings, $key);			
			
			if(!isset($fieldSetting)){
				$this->AddToMessage(sprintf("%s is not in the table.", $key));
				return false;
			}
			
			if(!isset($value) && !$this->isForSaving){
				if(strtolower($fieldSetting['Null'])=="yes"){
					return true;
				}else{
					$this->AddToMessage(sprintf("%s must have a value.", $key));
					return false;
				}
			}
			
			$typeName = strtolower($fieldSetting['Type']);
			$typeLength = -1;
			
			if($pos = strpos($fieldSetting['Type'], "(")){
				$pos2 = strpos($fieldSetting['Type'], ")");
				
				
				$typeLength = substr($typeName, $pos, ($pos2 - $pos));
				$typeName = substr($typeName, ($pos - 1));
			}
			
			if(!$valType = $this->types->ReturnValidationType($typeName)){
				$this->AddToMessage(sprintf("%s is not an accepted validation type.", $key));
				return false;
			}
			
			return call_user_func_array(array($this, $valType), array($key, $value, $typeLength);
		}
		
		private function TestInteger($value, $length){
			if(!is_int($value){
				$this->AddToMessage(sprintf("%s must have an integer value.", $key));
				return false;
			}
			
			return true;
		}
		
		private function TestDecimal($key, $value, $length){
			if(!is_numeric($value){
				$this->AddToMessage(sprintf("%s must be a number.", $key));
				return false;
			}
			
			return true;
		}
		
		private function TestBool($key, $value, $length){
			//do nothing. the formating will fix it if bad
			return true;
		}
		
		private function TestString($key, $value, $length){
			if(strlen($value) > $length){
				$this->AddToMessage(sprintf("%s can not have more than %s characters.", $key, $length));
				return false;	
			}
			
			return true;
		}
		
		private function TestDate($key, $value, $length){
			return ValidateDate($value, 'Y-m-d', $key);
		}
		
		private function TestDateTime($key, $value, $length){
			return ValidateDate($value, 'Y-m-d H:i:s', $key);
		}
		
		private function TestTime($key, $value, $length){
			return ValidateDate($value, 'H:i:s', $key);
		}
		
		private function ValidateDate($date_str, $format, $key = ''){
			$dTest = DateTime::createFromFormat($format, $date_str);
			if($dTest && $dTest->format($format) == $date_str){
				$this->AddToMessage(sprintf("%s must be in this date/time format %s", $key, $format));
				return false;
			}
			return true;
		}
	}
}
?>