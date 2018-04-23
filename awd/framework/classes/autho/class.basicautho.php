<?php
/*
Project: AWD framework
Created By Adam Duncan
Date: 04/19/2018
autho/class.webautho.php
Purpose
----------------
trying out basic authentication in the header and by php prompt if needed
----------------
*/
namespace Autho{
	class BasicAutho implements \AWD\Interfaces\iAutho{
		const HTTP_AUTHORIZATION = "HTTP_AUTHORIZATION";
		const PHP_AUTH_USER = "PHP_AUTH_USER";
		const PHP_AUTH_PW = "PHP_AUTH_PW";
		const BOUNDARY = ":";
		
		protected $type = "Basic";
		public $realm = "My Realm";
		public $isAuthenticated;
		
		private $username = null;
		private $password = null;
		
		public function __construct($username=null, $password=null){
			$this->username = $username;
			$this->password = $password;
		}
		
		public function LoadHeaderAutho(){
			if($this->LoadPhpAuth())
				return true;
			
			if($this->LoadHttpAutho())
				return true;
			
			return false;
		}	
		
		private function LoadPhpAuth(){
			if (isset($_SERVER[self::PHP_AUTH_USER])) {
				$this->username = $_SERVER[self::PHP_AUTH_USER];
				$this->password = $_SERVER[self::PHP_AUTH_PW];
				return true;
			}
			
			return false;
		}
		
		private function LoadHttpAuth(){
			if (!isset($_SERVER[self::HTTP_AUTHORIZATION]))
			
			if (strpos(strtolower($_SERVER[self::HTTP_AUTHORIZATION]), strtolower($this->type))===0){
				list($this->username,$this->password) 
				= explode(
					self::BOUNDARY, 
					self::Decrypt(substr($_SERVER[self::HTTP_AUTHORIZATION], 6))
				);
				
				return true;
			}
		}
		
		public function TryAuthentication($username, $password){
			if(
			  $this->username == $username &&
			  $this->password == $password
			){
				$this->isAuthenticated = true;
				return true;
			}else{
				return false;
			}
		}
		
		public function ClientHeaders(){
			header("Authorization: {$this->type} {self::FormatAuthorization($this->username, $this->password)}");
		}
		
		public function ServerHeaders(){
			//no extra for basic
		}
		
		public function NotAuhorized(){
			header("WWW-Authenticate: {$this->type} realm=\"{$this->realm}\"");
			header('HTTP/1.0 401 Unauthorized');
			echo 'You are not authorized to view this page without a valid username and password';
			die();
		}
		
		public static function FormatAuthorization($username, $password){
			return self::Encrypt("{$username}{self::BOUNDARY}{$password}";)
		}
		
		public static function Encrypt($value){
			return base64_encode($value);
		}
		
		public static function Decrypt($value){
			return base64_decode($value);
		}
	}
}