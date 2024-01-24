<?php

namespace Modules\Report\Zip\Fetchers;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\CachingStream;
use Modules\Report\Zip\Contracts\UrlFetcherContract;

class StreamFetcher implements UrlFetcherContract
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
     * @return CachingStream
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle($url): CachingStream
    {
        $response = $this->client->request('GET', $url, ['stream' => true]);
        $csvData = $response->getBody();

        return new CachingStream($csvData);
    }
}
