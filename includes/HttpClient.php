<?php


class HttpClient
{
	private $data;
	private $tenant;
	private $user;
	private $final_token;

	function __construct()
	{
		$this->data = array();
		$this->tenant = get_caag_tenant_token();
		$this->user = get_caag_user_token();
		$this->final_token = base64_encode($this->tenant . ':' . $this->user);
	}

	/*
	 * Http Get Request
	 */
	public function get($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Authorization: Basic '.$this->final_token
		));

		if(curl_error($ch)){
			echo 'error:' . curl_error($ch);
		}
		if(curl_exec($ch) === false){
			echo 'Curl error: ' . curl_error($ch);
		}else{
			$data = curl_exec($ch);
		}
		curl_close($ch);
		$this->data = json_decode($data);
		return $this->data;
	}
	
	public function post($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_POST, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		if(curl_error($ch)){
			echo 'error:' . curl_error($ch);
		}
		if(curl_exec($ch) === false){
			echo 'Curl error: ' . curl_error($ch);
		}else{
			$data = curl_exec($ch);
		}
		curl_close($ch);
		$this->data = json_decode($data);
		return $this->data;
	}
	public function put()
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		if(curl_error($ch)){
			echo 'error:' . curl_error($ch);
		}
		if(curl_exec($ch) === false){
			echo 'Curl error: ' . curl_error($ch);
		}else{
			$data = curl_exec($ch);
		}
		curl_close($ch);
		$this->data = json_decode($data);
		return $this->data;
	}
}
/*
 * Error values
 * object(stdClass)#5596 (2) { ["message"]=> string(24) "Invalid tenant api token" ["status_code"]=> int(403) }
 */