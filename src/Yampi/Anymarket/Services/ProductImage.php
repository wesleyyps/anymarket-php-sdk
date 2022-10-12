<?php

namespace Yampi\Anymarket\Services;

use Closure;
use Yampi\Anymarket\Anymarket;
use Yampi\Anymarket\Contracts\ProductImageInterface;

class ProductImage implements ProductImageInterface
{
    protected $params;

    protected $service;

    protected $http;

    protected $anymarket;

    public function __construct(Anymarket $anymarket, $http)
    {
        $this->anymarket = $anymarket;
        $this->service = 'products';
        $this->http = $http;
    }

    public function setParams(array $value)
    {
        $this->params = $value;

        return $this;
    }

    public function get($productId, $offset = 0, $limit = 50)
    {
        $url = sprintf('%s/%s/%s/images?offset=%s&limit=%s', $this->anymarket->getEndpoint(), $this->service, $productId, $offset, $limit);

        return $this->sendRequest('GET', $url);
    }

    public function create($productId, array $params)
    {
        $url = sprintf('%s/%s/%s/images', $this->anymarket->getEndpoint(), $this->service, $productId);

        return $this->setParams($params)->sendRequest('POST', $url);
    }

    public function update($productId, $id, array $params)
    {
        $url = sprintf('%s/%s/%s/images', $this->anymarket->getEndpoint(), $this->service, $productId, $id);

        return $this->setParams($params)->sendRequest('PUT', $url);
    }

    public function find($productId, $id)
    {
        $url = sprintf('%s/%s/%s/images/%s', $this->anymarket->getEndpoint(), $this->service, $productId, $id);

        return $this->sendRequest('GET', $url);
    }

    public function delete($productId, $id)
    {
        $url = sprintf('%s/%s/%s/images/%s', $this->anymarket->getEndpoint(), $this->service, $productId, $id);

        return $this->sendRequest('DELETE', $url);
    }

    /**
     * @param string $method
     * @param string $url
     * @return mixed
     * @throws Yampi\Anymarket\Exceptions\AnymarketException
     * @throws Yampi\Anymarket\Exceptions\AnymarketValidationException
     */
    public function sendRequest($method, $url)
    {
        return $this->anymarket->getRequestHandler()->handle(Closure::bind(function() use ($method, $url) {
            $requestParams = [];

            if (in_array($method, ['PUT', 'POST'])) {
                $requestParams = [
                    'json' => $this->params,
                ];
            }

            $request = $this->http->request($method, $url, $requestParams);

            return json_decode($request->getBody()->getContents(), true);
        }, $this));
    }
}
