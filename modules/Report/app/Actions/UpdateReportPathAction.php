<?php

namespace Modules\Report\Actions;

use Carbon\Carbon;
use Illuminate\Log\Logger;
use Modules\Report\Models\Report;
use Modules\Report\Models\ReportStatus;

class UpdateReportPathAction
{
    /**
     * @var Logger
     */
    protected Logger $log;

    /**
     * @var DestroyReportFileAction
     */
    protected DestroyReportFileAction $destroyReportFileAction;

    /**
     * @param Logger                  $log
     * @param DestroyReportFileAction $destroyReportFileAction
     */
    public function __construct(Logger $log, DestroyReportFileAction $destroyReportFileAction)
    {
        $this->log = $log;
        $this->destroyReportFileAction = $destroyReportFileAction;
    }

    /**
     * @param Report $report
     * @param string $path
     *
     * @throws \Modules\Daapi\Exceptions\CanNotApplyStatusException
     * @throws \SM\SMException
     */
    public function handle(Report $report, string $path): void
    {
        if (config('report.delete_previous_scheduled_report_file')) {
            $this->log->info('Deleting previously generated scheduled report files is enabled. Report to delete:', [
                'report_id' => $report->id,
            ]);
            $this->destroyReportFileAction->handle($report);
        }

        $this->log->info('Save new report file path to report.', [
            'report_id' => $report->id,
        ]);

        // Save S3 link in DB and update report status
        $report->path = $path;
        $report->generated_at = Carbon::now();

        $this->log->info('Report file path saved. Changing status to COMPLETED.', [
            'report_id' => $report->id,
        ]);

        $report->applyInternalStatus(ReportStatus::COMPLETED);
    }
}
