<?php

namespace PaymentwallLaravel\Lib\Response;

class ResponseSuccess extends ResponseAbstract implements ResponseInterface
{
	public function process()
	{
		if (!isset($this->response)) {
			return $this->wrapInternalError();
		}

		$response = [
			'success' => 1
		];

		return json_encode($response);
	}
}