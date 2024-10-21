<?php

namespace PaymentwallLaravel\Lib\Response;

abstract class ResponseAbstract
{
	protected $response;

	public function __construct($response = [])
	{
		$this->response = $response;
	}

	protected function wrapInternalError()
	{
		$response = [
			'success' => 0,
			'error' => [
				'message' => 'Internal error'
			]
		];
		return json_encode($response);
	}
}