<?php 

namespace Yampi\Anymarket\Contracts;

interface OrderInterface
{
    public function get($offset, $limit);
    public function create(array $params);
    public function update($id , array $params);
    public function find($id);
    public function delete($id);
    public function updateStatus($id, $params);
    public function feed();
    public function feedUpdate($id, $token);
}