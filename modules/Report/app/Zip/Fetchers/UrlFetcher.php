<?php

namespace Modules\Report\Zip\Fetchers;

use GuzzleHttp\Client;
use Modules\Report\Zip\Contracts\UrlFetcherContract;
use Psr\Http\Message\StreamInterface;

class UrlFetcher implements UrlFetcherContract
{
    /**
     * @param Client $client
     */
    public function __construct(protected Client $client)
    {
    }

    /**
     * @param $url
     *
     * @return StreamInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle($url): StreamInterface
    {
        return $this->client->request('GET', $url)->getBody();
    }
}
