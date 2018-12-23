<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');  
require_once('TwitterAPIExchange.php');

/**
ini library buat twitter ya
created by hananda abdurahman ramadani
*/
class Twitter_Lib
{
	protected $settings = array(
	    'oauth_access_token' => "452185682-doux4nPH1g84dB8MWUnhyeQJglKD7UdyoVTyZjJ9",
	    'oauth_access_token_secret' => "1UHidWSNwbri2SNMTihGeXSctCyqq2XWn0GI1ccZp1dpi",
	    'consumer_key' => "8BTJpuRJcUXJwwp4yc4DQByqX",
	    'consumer_secret' => "euOGUaKAyTiz8fVJfqetxBeZCJ9QZI9D7qfWrGlqcArEgIHTP1"
	);
	protected $url = '';
	protected $requestMethod = 'GET';
	protected $dataparam;

	function __construct()
	{
		
	}

	function search_tweet($param = array())
	{
		/*
		param yang dikirim
			until = 'tgl tweet'
			count = jumlah tweet yang diambil
			q = keyword
		*/
		$this->url = 'https://api.twitter.com/1.1/search/tweets.json';
		$param['lang'] = 'en';
		$param['result_type'] = 'mixed';
		$this->dataparam = http_build_query($param);
		$data = $this->_exec();
		return $data;
	}

	function _exec()
	{
		$twitter = new TwitterAPIExchange($this->settings);
		$data = $twitter->setGetfield('?'.$this->dataparam)
		             ->buildOauth($this->url, $this->requestMethod)
		             ->performRequest();
		return $data;
	}
}


?>