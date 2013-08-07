<?php namespace Session;

class Session {

	function __construct($httponly = true, $expire = 180, $secure=true, $cache_limiter = 'private, must-revalidate', $session_hash = 'sha512'){
		
		if(!session_id()){ 

			if (in_array($session_hash, hash_algos())) {
	      	  ini_set('session.hash_function', $session_hash);
	   		}

	   		ini_set('session.hash_bits_per_character', 5);
	 		ini_set('session.use_only_cookies', (int)(bool)$httponly);
			
			session_cache_expire((int)$expire);
			
			if(in_array($cache_limiter, array('public', 'private, must-revalidate', 'private_no_expire', 'private', 'nocache', 'must-revalidate'))){
				session_cache_limiter($cache_limiter);
			}
			
			ini_set('session.cookie_httponly', (int)(bool)$httponly);
			ini_set('session.cookie_secure', (int)(bool)$secure);
			
			session_start();

			$ident = md5($_SERVER['REMOTE_ADDR']);

			$_SESSION['ident'] = $ident;

			$_SESSION[ $ident ] = array();

			$this->SetOnce('time', time());			
		}
		
	}
	
	function Set($key, $val){
		if($ident = $this->ValidIdent()){
			$_SESSION[$ident][$key] = $val;
		}
	}
	
	function Forget($key){
		if($ident = $this->ValidIdent()){
			unset($_SESSION[$ident][$key]);
		}
	}
	
	function SetOnce($key, $val){
		if(!$this->Get($key)){
			$this->Set($key, $val);
		}
	}
	
	function Get($key){
		if($ident = $this->ValidIdent()){
			if(isset($_SESSION[$ident][$key])){
				return $_SESSION[$ident][$key];
			}
		}
		return null;
	}
	
	function GetOnce($key){
		if($val = $this->Get($key)){
			$this->Forget($key);
			return $val;
		}
		return null;
	}
	
	function ValidIdent(){
		$ident = md5($_SERVER['REMOTE_ADDR']);
		if(session_id() && array_key_exists('ident', $_SESSION) && ($_SESSION['ident'] === $ident)){
			if(isset($_SESSION[$ident]) && is_array($_SESSION[$ident])){
				return $ident;
			}
		}
		return false;
	}
		

}