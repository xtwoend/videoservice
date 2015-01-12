<?php namespace Xtwoend\Videoplus\Users;

/**
  * Copyright 2014 MerahPutih, Inc
  * PHP SDK MP Login
  * VERSI 1.0
  * Issued 01-09-2014
  */

class MPSdk {
	
	protected $state;
	protected $user;
	protected $resultToken = array();
	protected $array_params = array();
	
	/* Konstruktur */
	public function __construct($parVal = null) {
		if (!isset($_SESSION)) { session_start();}
		$this->array_params = $parVal;
		
		// make tokenState
		$state = $this->getSesData('state');
		if (!empty($state)) {
		  $this->state = $this->getSesData('state');
		}
	}

	public static $DOMAIN_MAP = array(
		'oauth' => 'http://developer.merahputih.com/resources/',
		'www'   => 'http://developer.merahputih.com/',
	  );
	
	/*
	public static $DOMAIN_MAP = array(
		'oauth' => 'http://mpserver.loc/resources/',
		'www'   => 'http://mpserver.loc/',
	  );
	*/
	
	
	public function getUser(){
		return $this->request_api();
	}	
	/**
	* Make an API call.
	*
	* @return mixed The decoded response
	*/
	public function api(/* polymorphic */) {
		$args = func_get_args();
		if (is_array($args)) {
			return $this->resultToken[$args[0]];
		}
	}
	
	/**
	* Get a Login URL for use with redirects. By default, full page redirect is
	* assumed. If you are using the generated URL with a window.open() call in
	* JavaScript, you can pass in display=popup as part of the $params.
	*
	* The parameters:
	* - redirect_uri: the url to go to after a successful login
	*
	* @param array $params Provide custom parameters
	* @return string The URL for the login flow
	*/
	public function getLoginUrl($params=array()) {
		$this->tokenState();
		return $this->getUrl('www','mp/oauth/',
		  array_merge(array(
			'app_id' => $this->array_params['appId'],
			'redirect_uri' => $this->getCurrentUrl(), // possibly overwritten
			'state' => $this->state),
		  $params));
	}

	/**
	* Get a Logout URL suitable for use with redirects.
	*
	* The parameters:
	* - redirect_uri: the url to go to after a successful logout
	*
	* @param array $params Provide custom parameters
	* @return string The URL for the logout flow
	*/
	public function getLogoutUrl($params=array()) {
		$this->tokenState();
		$state = $this->state;
		return $this->getUrl('www','mp/logout/', array_merge(array(
			'redirect_uri' => $this->getCurrentUrl(),
			'state' => $state), $params));
	}
	
		/**	Protected Func	**/		
		protected function getUrl($name, $path='', $params=array()) {
			$url = self::$DOMAIN_MAP[$name];
			if ($path) {
			if ($path[0] === '/') {
			$path = substr($path, 1);
			}
			$url .= $path;
			}
			if ($params) {
				$url .= '?' . http_build_query($params, null, '&');
			}
			return $url;
		}
		
		protected function setSesData($key, $value) {
			$_SESSION[$key] = $value;
		}

		protected function getSesData($key, $default = false) {
			return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
		}

		protected function clearSesData($key) {
			unset($_SESSION[$key]);
		}

		protected function clearAllSesData() {
			foreach ($_SESSION as $key => $value) {
				$this->clearSesData($key);
			}
		}
		
		protected function resetTokenState() {
			$this->state = md5(uniqid(mt_rand(), true));
			$this->setSesData('state', $this->state);
			
		}
		protected function tokenState() {
			if ($this->state === null) {
				$this->state = md5(uniqid(mt_rand(), true));
				$this->setSesData('state', $this->state);
			}
		}
		/**
		* Returns the Current URL.
		*
		* @return string Tautan URL
		*/
		protected function getCurrentUrl() {
			$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https://' : 'http://';
			$currentUrl = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			$parts = parse_url($currentUrl);

			$query = '';
			if (!empty($parts['query'])) {
				$params = explode('&', $parts['query']);
				$retained_params = array();
				foreach ($params as $param) {
					$retained_params[] = $param;
				}

				if (!empty($retained_params)) {
					$query = '?'.implode($retained_params, '&');
				}
			}

			// use port if non default
			$port =
			isset($parts['port']) &&
			(($protocol === 'http://' && $parts['port'] !== 80) ||
			($protocol === 'https://' && $parts['port'] !== 443))
			? ':' . $parts['port'] : '';

			// rebuild
			return $protocol . $parts['host'] . $port . $parts['path'] . $query;
		}
		
		/*
		/* @return ip
		*/
		protected function getIP() {
		   if (!empty($_SERVER['HTTP_CLIENT_IP'])) {  //check ip from share internet
			 $ip=$_SERVER['HTTP_CLIENT_IP'];
		   } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {  //to check ip is pass from proxy
			 $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
		   } else {
			 $ip=$_SERVER['REMOTE_ADDR'];
		   }
		   return $ip;
		}
		
		/*
		/* @return requested
		*/		
		protected function request_api($request_type='userdata'){
			$data_string = json_encode(array(
				'appId' => $this->array_params['appId'],
				'secretKey' => $this->array_params['secretKey'],
				'tokenState' => $this->state,
				'uagent' => $_SERVER['HTTP_USER_AGENT'],
				'clientIP' => $this->getIP(),
				'requestType' => $request_type,
			));
			$ch = curl_init($this->getUrl('oauth','api/'));
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string); 			
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
				'Content-Type: application/json',                                                                                
				'Content-Length: ' . strlen($data_string))                                                                       
			);			
			$result = curl_exec($ch);                            
			curl_close($ch);
			
			$json_result = json_decode($result, true);
			if($json_result['status']=="success"){$this->resultToken[$request_type] = $json_result['data'];	return 1;}
			elseif($json_result['status']=="expired"){ $this->resetTokenState(); return 0;}
			else {return 0;}
		}	
}