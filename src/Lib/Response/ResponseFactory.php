<?php

namespace PaymentwallLaravel\Lib\Response;

class ResponseFactory
{
	const CLASS_NAME_PREFIX = 'Response';

	const RESPONSE_SUCCESS = 'success';
	const RESPONSE_ERROR = 'error';

	public static function get($response = [])
	{
		$responseModel = null;

		$responseModel = self::getClassName($response);

		return new $responseModel($response);
	}

	public static function getClassName($response = []) {
		$responseType = (isset($response['type']) && $response['type'] == 'Error') ? self::RESPONSE_ERROR : self::RESPONSE_SUCCESS;
		return self::CLASS_NAME_PREFIX . ucfirst($responseType);
	}
}