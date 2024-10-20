<?php

namespace PaymentwallLaravel\Lib\Signature;

class SignatureWidget extends SignatureAbstract
{
	public function process($params = [], $version = 0)
	{
		$baseString = '';

		if ($version == self::VERSION_ONE) {

			$baseString .= isset($params['uid']) ? $params['uid'] : '';
			$baseString .= $this->getConfig()->getPrivateKey();

			return md5($baseString);

		} else {

			self::ksortMultiDimensional($params);

			$baseString = $this->prepareParams($params, $baseString);

			$baseString .= $this->getConfig()->getPrivateKey();

			if ($version == self::VERSION_TWO) {
				return md5($baseString);
			}

			return hash('sha256', $baseString);
		}
	}

	public function prepareParams($params = [], $baseString = '')
	{
		foreach ($params as $key => $value) {
			if (!isset($value)) {
				continue; 
			}
			if (is_array($value)) {
				foreach ($value as $k => $v) {
					$baseString .= $key . '[' . $k . ']' . '=' . ($v === false ? '0' : $v);
				}
			} else {
				$baseString .= $key . '=' . ($value === false ? '0' : $value);
			}
		}
		return $baseString;
	}
}
