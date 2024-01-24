<?php

namespace Modules\Report\Zip\Contracts;

use Psr\Http\Message\StreamInterface;

interface UrlFetcherContract
{
    /**
     * @param $url
     *
     * @return StreamInterface
     */
    public function handle($url): StreamInterface;
}
