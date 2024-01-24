<?php

namespace Modules\Report\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Modules\Report\Models\Report;

/**
 * @mixin \Modules\Report\Models\Report
 */
trait AWSReport
{
    use GenerateReportFilename;

    /**
     * @param Report $report
     *
     * @return string
     */
    protected function generateDownloadUrl(Report $report): string
    {
        return Storage::temporaryUrl(
            $report->path,
            Carbon::now()->addMinutes(config('report.url_expiration')),
            $this->getHeaders($report)
        );
    }

    /**
     * Content-Disposition header allows file to be downloaded with provided name, not with the saved one.
     *
     * @param Report $report
     *
     * @return array
     * @throws \App\Exceptions\BaseException
     */
    private function getHeaders(Report $report): array
    {
        return [
            'ResponseContentDisposition' => sprintf(
                'attachment; filename="%s"',
                addslashes($this->getFileName($report))
            ),
        ];
    }

    /**
     * @param Report $report
     *
     * @return string
     * @throws \App\Exceptions\BaseException
     */
    public function getFileName(Report $report): string
    {
        $fileName = match (pathinfo($this->path, PATHINFO_EXTENSION)) {
            'zip' => $this->createEncodedFilenameZip($report->name),
            'csv' => $this->createEncodedFilenameCsv($report->name),
            default => $this->createFilename($report),
        };

        return $fileName;
    }
}
