<?php

namespace Modules\Report\Actions;

use Illuminate\Contracts\Filesystem\Factory as Storage;
use Illuminate\Log\Logger;
use Modules\Report\Models\Report;

class DestroyReportFileAction
{
    /**
     * @var Logger
     */
    protected Logger $log;

    /**
     * @var Storage|\Illuminate\Contracts\Filesystem\Filesystem
     */
    protected $storage;

    /**
     * @param Logger  $log
     * @param Storage $storage
     */
    public function __construct(Logger $log, Storage $storage)
    {
        $this->log = $log;
        $this->storage = $storage;
    }

    /**
     * Delete the report, relations and resources.
     *
     * @param Report $report
     */
    public function handle(Report $report): void
    {
        $path = $report->path;
        if ($path && $this->exists($path)) {
            $this->log->info('Report file to be removed.', ['report_id' => $report->id]);
            $this->storage->delete($path);
            $this->log->info('Report file was successfully removed.', ['report_id' => $report->id]);
        }
    }

    /**
     * @param string $path
     *
     * @return bool
     */
    protected function exists(string $path): bool
    {
        try {
            return $this->storage->exists($path);
        } catch (\Throwable $e) {
            $this->log->warning('File does not exists', [
                'reason' => $e->getMessage(),
            ]);

            return false;
        }
    }
}
