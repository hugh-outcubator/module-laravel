<?php

namespace PaymentwallLaravel\Lib;

use GuzzleHttp\Client;
use PaymentwallLaravel\Lib\ApiObject;

class HttpAction extends Instance
{
	protected $apiObject;
	protected $apiParams = [];
	protected $apiHeaders = [];
	protected $responseLogInformation = [];

	public function __construct($object, $params = [], $headers = [])
	{
		$this->setApiObject($object);
		$this->setApiParams($params);
		$this->setApiHeaders($headers);
	}

	public function getApiObject()
	{
		return $this->apiObject;
	}

	public function setApiObject(ApiObject $apiObject)
	{
		$this->apiObject = $apiObject;
	}

	public function getApiParams()
	{
		return $this->apiParams;
	}

	public function setApiParams($params = [])
	{
		$this->apiParams = $params;
	}

	public function getApiHeaders()
	{
		return $this->apiHeaders;
	}

	public function setApiHeaders($headers = [])
	{
		$this->apiHeaders = $headers;
	}

	public function run()
	{
		$result = [];

		if ($this->getApiObject() instanceof ApiObject) {
			$result = $this->apiObjectPostRequest($this->getApiObject());
		}

		return $result;
	}

	public function apiObjectPostRequest(ApiObject $object)
	{
		return $this->request('POST', $object->getApiUrl(), $this->getApiParams(), $this->getApiHeaders());
	}

	public function post($url = '')
	{
		return $this->request('POST', $url, $this->getApiParams(), $this->getApiHeaders());
	}

	public function get($url = '')
	{
		return $this->request('GET', $url, $this->getApiParams(), $this->getApiHeaders());
	}

    /**
     * @param $method
     * @param $url
     * @param $params
     * @param $headers
     * @param $rawResponse
     * @return mixed|string|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function request($method, $url, $params = [], $headers = [])
    {
        $client = new Client();
        $headers = array_merge($headers, [
            $this->getLibraryDefaultRequestHeader()
        ]);

        $formParams = [
            'form_params' => $params,
            'headers' => $headers,
            'verify' => false,
            'timeout' => 60
        ];

        $response = [];
        try {
            $response = $client->request($method, $url, $formParams);
            $response = $response->getBody()->getContents();
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return !empty($response) ? $response : null;
    }

	protected function getLibraryDefaultRequestHeader()
	{
		return 'User-Agent: Paymentwall PHP Library v. ' . $this->getConfig()->getVersion();
	}

	public function getResponseLogInformation() {
		return $this->responseLogInformation;
	}
}
