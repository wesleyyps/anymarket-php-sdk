<?php

namespace Yampi\Anymarket\Services;

use Yampi\Anymarket\Anymarket;
use Yampi\Anymarket\Contracts\ProductImageInterface;

class ProductImage extends BaseRequest implements ProductImageInterface
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

    public function setProduct($product)
    {
        $this->product = $product;

        return $this;
    }

    public function setParams(array $value)
    {
        $this->params = $value;

        return $this;
    }

    public function get($offset = 0, $limit = 50)
    {
        $url = sprintf('%s/%s/%s/images?offset=%s&limit=%s', $this->anymarket->getEndpoint(), $this->service, $this->product, $offset, $limit);

        return $this->sendRequest('GET', $url);
    }

    public function create( array $params)
    {
        $url = sprintf('%s/%s/%s/images', $this->anymarket->getEndpoint(), $this->service, $this->product);

        return $this->setParams($params)->sendRequest('POST', $url);
    }

    public function update($id, array $params)
    {
        $url = sprintf('%s/%s/%s/images', $this->anymarket->getEndpoint(), $this->service, $this->product, $id);

        return $this->setParams($params)->sendRequest('PUT', $url);
    }

    public function find($id)
    {
        $url = sprintf('%s/%s/%s/images/%s', $this->anymarket->getEndpoint(), $this->service, $this->product, $id);

        return $this->sendRequest('GET', $url);
    }

    public function delete($id)
    {
        $url = sprintf('%s/%s/%s/images/%s', $this->anymarket->getEndpoint(), $this->service, $this->product, $id);

        return $this->sendRequest('DELETE', $url);
    }
}
