<?php

class HttpClient
{
	private $data;

	function __construct()
	{
		$this->data = array();
	}

	public function get($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		$data = curl_exec($ch);
		curl_close($ch);
		$this->data = json_decode($data);
		return $this->data;
	}
	public function post()
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		$data = curl_exec($ch);
		curl_close($ch);
		$this->data = json_decode($data);
		return $this->data;
	}
	public function put()
	{

	}
}