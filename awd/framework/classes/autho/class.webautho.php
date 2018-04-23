<?php
/*
Project: AWD framework
Created By Adam Duncan
Date: 10/19/2017
autho/class.webautho.php
Purpose
----------------
trying out web specific functions for authentication
----------------
*/
namespace Autho{
	class WebAutho extends \AWD\Data\Row implements \AWD\Interfaces\iAutho{
		protected static $boundary = "clecle!|tTiCd124!@{yo}";
		public $isAuthenticated;
		
		function __construct(){
			//nothing now
		}
		
		public function LoadCookieAutho(){
			if(self::HasUserCookie()){
				$l = explode(self::$boundary, $_COOKIE["l"]);
				
				//confirm 2 records
				if(isset($l) && count($l) == 2){
					$this->SetVariables($l[0], $l[1]);
					return true;
				}
			}
			
			return false;
		}		
		
		public function TtyAuthentication($userGuid, $sessionGuid){
			if(
			  $this->data['guid'] == $userGuid &&
			  $this->data['session_guid'] == $sessionGuid
			){
				$this->isAuthenticated = true;
				return true;
			}else{
				return false;
			}
		}
		
		public function SetCookie(){
			self::UpdateCookie($this->guid, $this->session_guid);
		}	
		
		public static function LogOut(){
			if(self::HasUserCookie()){
				unset($_COOKIE["l"]);			
				setcookie("l", null, -1, "/", CookieUrl);	
			}	
		}
		
		public static function HasUserCookie(){
			if(isset($_COOKIE["l"]) && $_COOKIE["l"] != "")
				return true;
			else
				return false;
		}	
		
		protected static function UpdateCookie($userGuid, $sessionGuid){
			$minInDay = 86400;
			$numDays = 14;
			setcookie("l", $userGuid . self::$boundary . $sessionGuid, (time() + ($minInDay * $numDays)), "/", CookieUrl);
		}
				
		private function SetVariables($userGuid, $sessionGuid){
			$this->data['guid'] = $userGuid;
			$this->data['session_guid'] = $sessionGuid;				
		}
	}
}

?>