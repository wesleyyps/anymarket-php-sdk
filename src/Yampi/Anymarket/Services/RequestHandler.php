<?php
namespace Yampi\Anymarket\Services;

use Closure;
use Exception;
use GuzzleHttp\Exception\BadResponseException;
use Yampi\Anymarket\Contracts\RequestHandlerInterface;
use Yampi\Anymarket\Exceptions\AnymarketException;
use Yampi\Anymarket\Exceptions\AnymarketValidationException;

class RequestHandler implements RequestHandlerInterface
{
    /**
     * @param Closure $request
     * @return mixed
     * @throws Yampi\Anymarket\Exceptions\AnymarketException
     * @throws Yampi\Anymarket\Exceptions\AnymarketValidationException
     */
    public function handle(Closure $request)
    {
        try {
            return $request();
        } catch (BadResponseException $e) {
            $message = $e->getMessage();
            
            $e->getResponse()->getBody()->rewind();
            $body = @json_decode($e->getResponse()->getBody()->getContents(), true);
            if (isset($body['message']) && trim($body['message']) != '') {
                $message = trim($body['message']);
            }

            if ($e->getCode() == 422) {
                throw new AnymarketValidationException($message, $e->getCode(), $e);
            }

            throw new AnymarketException($message, $e->getCode(), $e);
        } catch(Exception $e) {
            throw new AnymarketException($e->getMessage(), $e->getCode(), $e);
        }
    }
}