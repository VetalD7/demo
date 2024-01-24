<?php

namespace Modules\Report\Zip;

use GuzzleHttp\Client;
use Modules\Report\Zip\Contracts\UrlFetcherContract;
use Modules\Report\Zip\Fetchers\StreamFetcher;
use Modules\Report\Zip\Fetchers\UrlFetcher;
use Psr\Http\Message\StreamInterface;
use Psr\Log\LoggerInterface;

class FileFetcherManager
{
    /**
     * @var int
     */
    protected int $largeFileSize = 5 * 1024 * 1024; //5 mb

    /**
     * @param LoggerInterface $log
     * @param Client          $client
     */
    public function __construct(protected LoggerInterface $log, protected Client $client)
    {
    }

    /**
     * @param $url
     *
     * @return StreamInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function fetch($url): StreamInterface
    {
        $fetcher = $this->getFetcher($url);

        $this->log->info('[FileFetcherManager] Fetch data by url', [
            'path'          => $url,
            'handlerAction' => $fetcher::class
        ]);

        return $fetcher->handle($url);
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function getFetcher($url): UrlFetcherContract
    {
        $fileSize = $this->getFileSize($url);

        if (is_null($fileSize) || $fileSize > $this->largeFileSize) {
            $fetcher = app(StreamFetcher::class);
        } else {
            $fetcher = app(UrlFetcher::class);
        }

        return $fetcher;
    }

    /**
     * @param $url
     *
     * @return int|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function getFileSize($url): ?int
    {
        try {
            $response = $this->client->get($url, ['stream' => true]);
            return $response->hasHeader('Content-Length') ? (int)$response->getHeaderLine('Content-Length') : null;
        } catch (\Exception $e) {
            return null;
        }
    }
}
