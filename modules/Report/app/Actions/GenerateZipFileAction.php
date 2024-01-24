<?php

namespace Modules\Report\Actions;

use Illuminate\Support\Str;
use Modules\Report\Zip\FileFetcherManager;
use Modules\Report\Zip\UploadS3ZipHelper;
use Psr\Log\LoggerInterface;

class GenerateZipFileAction
{
    /**
     * @param LoggerInterface    $log
     * @param FileFetcherManager $fileFetcherManager
     */
    public function __construct(protected LoggerInterface $log, protected FileFetcherManager $fileFetcherManager)
    {
    }

    /**
     * @param string      $zipPath
     * @param string      $url
     * @param string|null $fileName
     *
     * @return void
     */
    public function handle(string $zipPath, string $url, string $fileName = null)
    {
        $this->log->info('Started creating zip file', [
            'zipPath' => $zipPath,
            'urlFile' => $url,
        ]);

        try {
            $zipStream = UploadS3ZipHelper::registerAwsStream($zipPath);
            if (!$fileName) {
                $fileName = $this->getNameByPath($url);
            }
            UploadS3ZipHelper::addFileToZip($zipStream, $fileName, $this->fileFetcherManager->fetch($url));
            UploadS3ZipHelper::closeZipStream($zipStream);
        } catch (\Throwable $exception) {
            $this->log->info('Error creating zip file', [
                'zipPath'      => $zipPath,
                'urlFile'      => $url,
                'errorMessage' => $exception->getMessage()
            ]);
        }

        $this->log->info('Zip file has been successfully', [
            'zipPath' => $zipPath,
            'urlFile' => $url
        ]);
    }

    /**
     * @param string $url
     *
     * @return string
     */
    private function getNameByPath(string $url): string
    {
        return Str::afterLast($url, '/');
    }
}
