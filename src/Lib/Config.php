<?php

namespace PaymentwallLaravel\Lib;

class Config
{
	const VERSION = '2.0.0';

	const API_VC	= 1;
	const API_GOODS	= 2;
	const API_CART	= 3;

	protected $apiType = self::API_GOODS;
	protected $publicKey;
	protected $privateKey;
	protected $apiBaseUrl;

	private static $instance;

    public function __construct()
    {
        $this->apiBaseUrl = config('paymentwall.base_url');
        $this->publicKey = config('paymentwall.public_key');
        $this->privateKey = config('paymentwall.private_key');
    }

	public function getApiBaseUrl()
	{
		return $this->apiBaseUrl;
	}

	public function setApiBaseUrl($url = '')
	{
		$this->apiBaseUrl = $url;
	}

	public function getLocalApiType()
	{
		return $this->apiType;
	}

	public function setLocalApiType($apiType = 0)
	{
		$this->apiType = $apiType;
	}

	public function getPublicKey()
	{
		return $this->publicKey;
	}

	public function setPublicKey($key = '')
	{
		$this->publicKey = $key;
	}

	public function getPrivateKey()
	{
		return $this->privateKey;
	}

	public function setPrivateKey($key = '')
	{
		$this->privateKey = $key;
	}

	public function getVersion()
	{
		return self::VERSION;
	}

	public function isTest()
	{
		return strpos($this->getPublicKey(), 't_') === 0;
	}

	public function set($config = [])
	{
		if (isset($config['api_base_url'])) {
			$this->setApiBaseUrl($config['api_base_url']);
		}
		if (isset($config['api_type'])) {
			$this->setLocalApiType($config['api_type']);
		}
		if (isset($config['public_key'])) {
			$this->setPublicKey($config['public_key']);
		}
		if (isset($config['private_key'])) {
			$this->setPrivateKey($config['private_key']);
		}
	}

	/**
        * @return $this Returns class instance.
        */
	public static function getInstance()
	{
		if (!isset(self::$instance)) {
			$className = __CLASS__;
			self::$instance = new $className;
		}
		return self::$instance;
	}

	private function __clone()
	{
	}
}
