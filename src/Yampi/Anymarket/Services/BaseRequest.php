<?php

namespace Yampi\Anymarket\Services;

use Closure;
use Yampi\Anymarket\Anymarket;
use Yampi\Anymarket\Contracts\BaseRequestInterface;

abstract class BaseRequest implements BaseRequestInterface
{
    protected $params;

    protected $service;

    protected $http;

    protected $anymarket;

    public function __construct(Anymarket $anymarket, $service, $http)
    {
        $this->anymarket = $anymarket;
        $this->service = $service;
        $this->http = $http;
    }

    public function setParams($value)
    {
        $this->params = $value;

        return $this;
    }

    public function get($offset = 0, $limit = 50)
    {
        $url = sprintf('%s/%s?offset=%s&limit=%s', $this->anymarket->getEndpoint(), $this->service, $offset, $limit);

        return $this->sendRequest('GET', $url);
    }

    public function create(array $params)
    {
        $url = sprintf('%s/%s', $this->anymarket->getEndpoint(), $this->service);

        return $this->setParams($params)->sendRequest('POST', $url);
    }

    public function update($id, array $params)
    {
        $url = sprintf('%s/%s/%s', $this->anymarket->getEndpoint(), $this->service, $id);

        return $this->setParams($params)->sendRequest('PUT', $url);
    }

    public function find($id)
    {
        $url = sprintf('%s/%s/%s', $this->anymarket->getEndpoint(), $this->service, $id);

        return $this->sendRequest('GET', $url);
    }

    public function delete($id)
    {
        $url = sprintf('%s/%s/%s', $this->anymarket->getEndpoint(), $this->service, $id);

        return $this->sendRequest('DELETE', $url);
    }

    /**
     * @param string $method
     * @param string $url
     * @return mixed
     * @throws Yampi\Anymarket\Exceptions\AnymarketException
     * @throws Yampi\Anymarket\Exceptions\AnymarketValidationException
     */
    public function sendRequest($method, $url, $headers = [])
    {
        if (!is_null($this->anymarket->getLogger())) {
            $this->anymarket->getLogger()->debug(sprintf('%s %s', $method, $url), [
                'params' => is_array($headers) && isset($headers['Content-Type']) && $headers['Content-Type'] == 'application/xml'  
                    ? substr($this->params, 0, 255) . '(truncated)' 
                    : $this->params,
                'headers' => $headers
            ]);
        }
        return $this->anymarket->getRequestHandler()->handle(Closure::bind(function() use ($method, $url, $headers) {
            $hasHeaders = count($headers) > 0;

            $requestParams = [];

            if ($hasHeaders) {
                $requestParams['headers'] = $headers;
            }

            if (in_array($method, ['PUT', 'POST'])) {
                if ($hasHeaders && $headers['Content-Type'] == 'application/xml') {
                    $requestParams['body'] = $this->params;
                } else {
                    $requestParams['json'] = $this->params;
                }
            }

            $response = $this->http->request($method, $url, $requestParams);
            $responseBody = $response->getBody()->getContents();
            if (!is_null($this->anymarket->getLogger())) {
                $this->anymarket->getLogger()->debug(sprintf('%s %s RESPONSE', $method, $url), [
                    $responseBody
                ]);
            }
            $jsonDecoded = json_decode($responseBody, true);
            return $response->getHeader('Content-Type') == 'application/json' ? $jsonDecoded : ($jsonDecoded === null ? $responseBody : $jsonDecoded);
        }, $this));
    }
}
