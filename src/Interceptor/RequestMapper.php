<?php

namespace Amp\Http\Client\Interceptor;

use Amp\CancellationToken;
use Amp\Http\Client\Connection\Connection;
use Amp\Http\Client\NetworkInterceptor;
use Amp\Http\Client\Request;
use Amp\Promise;
use function Amp\call;

final class RequestMapper implements NetworkInterceptor
{
    /** @var callable */
    private $mapper;

    public function __construct(callable $mapper)
    {
        $this->mapper = $mapper;
    }

    public function interceptNetworkRequest(
        Request $request,
        CancellationToken $cancellationToken,
        Connection $connection
    ): Promise {
        return call(function () use ($request, $cancellationToken, $connection) {
            $request = yield call($this->mapper, $request);

            return $connection->request($request, $cancellationToken);
        });
    }
}