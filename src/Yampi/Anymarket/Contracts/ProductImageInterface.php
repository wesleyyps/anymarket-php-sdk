<?php

namespace Yampi\Anymarket\Contracts;

interface ProductImageInterface
{
    public function get($offset = 0, $limit = 50);

    public function create(array $params);

    public function update($id, array $params);

    public function find($id);

    public function delete($id);

    public function setProduct($product);
}
