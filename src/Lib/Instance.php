<?php

namespace PaymentwallLaravel\Lib;

use PaymentwallLaravel\Lib\Config;

abstract class Instance
{
	protected $config;
	protected $errors = [];

	public function getErrorSummary()
	{
		return implode("\n", $this->getErrors());
	}

	protected function getConfig()
	{
		if (!isset($this->config)) {
			$this->config = Config::getInstance();
		}
		return $this->config;
	}

	protected function getApiBaseUrl()
	{
		return $this->getConfig()->getApiBaseUrl();
	}

	protected function getApiType()
	{
		return $this->getConfig()->getLocalApiType();
	}

	protected function getPublicKey()
	{
		return $this->getConfig()->getPublicKey();
	}

	protected function getPrivateKey()
	{
		return $this->getConfig()->getPrivateKey();
	}

	protected function appendToErrors($error = '')
	{
		$this->errors[] = $error;
	}

	protected function getErrors()
	{
		return $this->errors;
	}
}