<?php

namespace Modules\Report\Zip;

use GuzzleHttp\Psr7\CachingStream;
use Illuminate\Support\Facades\Storage;
use ZipStream\Option\Archive;
use ZipStream\ZipStream;

class UploadS3ZipHelper
{
    /**
     * @param string $path
     *
     * @return ZipStream
     */
    public static function registerAwsStream(string $path): ZipStream
    {
        Storage::disk('s3')->getClient()->registerStreamWrapper();
        $zipFile = fopen(config('filesystems.disks.s3.path_stream') . '/' . $path, 'w');

        return new ZipStream(null, self::getOptionsStream($zipFile));
    }

    /**
     * @param ZipStream $zip
     * @param string    $csvName
     * @param string    $data
     *
     * @return void
     */
    public static function addFileToZip(ZipStream $zip, string $csvName, $data)
    {
        if ($data instanceof CachingStream) {
            $zip->addFileFromPsr7Stream($csvName, $data);
            return;
        }

        $zip->addFile($csvName, $data);
    }

    /**
     * @param ZipStream $zipStream
     *
     * @return void
     * @throws \ZipStream\Exception\OverflowException
     */
    public static function closeZipStream(ZipStream $zipStream)
    {
        $zipStream->finish();
    }

    /**
     * @param resource $outputStream
     *
     * @return Archive
     */
    public static function getOptionsStream($outputStream): Archive
    {
        $options = new Archive();
        $options->setEnableZip64(false);
        $options->setOutputStream($outputStream);

        return $options;
    }
}
