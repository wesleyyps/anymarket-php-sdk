<?php

namespace Yampi\Anymarket\Contracts;

interface ProductImageInterface
{
    public function get($productId, $offset = 0, $limit = 50);

    public function create($productId, array $params);

    public function update($productId, $id, array $params);

    public function find($productId, $id);

    public function delete($productId, $id);
}
