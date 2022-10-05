<?php
namespace Yampi\Anymarket\Contracts;

use Closure;

interface RequestHandlerInterface
{
    /**
     * @param Closure $request
     * @return mixed
     * @throws Yampi\Anymarket\Exceptions\AnymarketException
     * @throws Yampi\Anymarket\Exceptions\AnymarketValidationException
     */
    public function handle(Closure $request);
}